<?php if (!defined('FROM_BASE')) { header('HTTP/1.1 403 Forbidden'); die('Invalid requested path.'); }

/*
 * This file is part of ND PHP Framework.
 *
 * ND PHP Framework - An handy PHP Framework (www.nd-php.org)
 * Copyright (C) 2015-2016  Pedro A. Hortas (pah@ucodev.org)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

/*
 * ND PHP Framework (www.nd-php.org) - Contributor Agreement
 *
 * When contributing material to this project, please read, accept, sign
 * and send us the Contributor Agreement located in the file NDCA.pdf
 * inside the documentation/ directory.
 *
 */

class Paypal_ipn extends UW_Controller {
	private $_default_charset = NDPHP_LANG_MOD_DEFAULT_CHARSET;
	private $_default_theme = 'Blueish';

	private $paypal_host = "www.paypal.com";
	private $paypal_email = "paypal@your.domain";
	private $paypal_currency_code = NDPHP_LANG_MOD_DEFAULT_CURRENCY;
	private $_default_database = 'default';
	private $return_url = 'https://localhost/ndphp/index.php/paypal_ipn/payment_successful';
	private $cancel_url = 'https://localhost/ndphp/index.php/paypal_ipn/payment_cancelled';
	private $notify_url = 'https://localhost/ndphp/index.php/paypal_ipn/payment_response';

	/* Hardcoded database entry IDs */
	private $payment_types_paypal_id = 1;
	private $payment_status_pending_id = 1;
	private $payment_status_success_id = 2;
	private $payment_status_failed_id = 3;
	private $payment_status_fraudulent_id = 4;
	private $payment_status_invalid_id = 5;
	private $payment_actions_pending_id = 1;
	private $payment_actions_processed_id = 2;
	private $payment_actions_failed_id = 3;

	protected function _get_theme() {
		$this->db->select(
			'themes.theme AS name,'.
			'themes.animation_default_delay AS animation_default_delay,themes.animation_ordering_delay AS animation_ordering_delay,'.
			'themes_animations_default.animation AS animation_default_type,themes_animations_ordering.animation AS animation_ordering_type'
		);
		$this->db->from('themes');
		$this->db->join('themes_animations_default', 'themes_animations_default.id = themes.themes_animations_default_id', 'left');
		$this->db->join('themes_animations_ordering', 'themes_animations_ordering.id = themes.themes_animations_ordering_id', 'left');
		$this->db->where('theme', $this->_default_theme);
		$q = $this->db->get();

		return $q->row_array();
	}

	private function check_txnid($txnid) {
		return true;
	}
	
	private function check_price($price, $id) {
		return true;
	}

	private function users_credit_update_from_payment($payments_id) {
		/* Get user id and payed amount from payments */
		$this->db->select('users_id,amount');
		$this->db->from('payments');
		$this->db->where('id', $payments_id);
		error_log('Updating users credit');

		$query = $this->db->get();

		if (!$query->num_rows())
			$this->response->code('403', NDPHP_LANG_MOD_PAYMENT_UNABLE_ID_USER, $this->_default_charset, !$this->request->is_ajax());

		$row_payment = $query->row_array();

		error_log('Updating users credit: ' . $row_payment['users_id'] . ',' . $row_payment['amount']);
		/* Get the current credit information from user */
		$this->db->trans_begin();

		$this->db->select('credit,vat');
		$this->db->from('users');
		$this->db->where('id', $row_payment['users_id']);

		$query = $this->db->get();

		if (!$query->num_rows()) {
			$this->db->trans_rollback();
			$this->response->code('403', NDPHP_LANG_MOD_UNABLE_USER_CREDIT_INFO, $this->_default_charset, !$this->request->is_ajax());
		}

		$row_user = $query->row_array();

		$userdata['credit'] = $row_user['credit'] + $row_payment['amount'];

		$this->db->where('id', $row_payment['users_id']);
		$this->db->update('users', $userdata);

		/* Insert record in transaction history */
		$historydata['transaction_date'] = date('Y-m-d H:i:s');
		$historydata['transaction_types_id'] = '2';
		$historydata['ammount'] = $row_payment['amount'];
		$historydata['description'] = 'Paypal payment (ID: ' . $payments_id . '). VAT Nr: ' . $row_user['vat'] . '.';
		$historydata['users_id'] = $row_payment['users_id'];

		$this->db->insert('transaction_history', $historydata);

		if ($this->db->trans_status() === FALSE) {
			error_log('paypal_ipn.php: users_credit_update_from_payment(): ' . NDPHP_LANG_MOD_FAILED_TRANSACTION);
			$this->db->trans_rollback();
			$this->response->code('500', NDPHP_LANG_MOD_INFO_USER_CREDIT_UPDATE . ': ' . NDPHP_LANG_MOD_FAILED_TRANSACTION . '. #1', $this->_default_charset, !$this->request->is_ajax());
		} else {
			$this->db->trans_commit();
		}
	}

	private function users_payment_invoice($payments_id) {
		return true;
	}

	private function payment_success_post_update($payments_id) {
		$this->users_credit_update_from_payment($payments_id);
		$this->users_payment_invoice($payments_id);
	}

	private function payment_update($data, $payments_id, $payment_status_id) {
		$qupddata['txnid'] = $data['txn_id'];
		$qupddata['payment_status_id'] = $payment_status_id;
		$qupddata['status_desc'] = $data['payment_status'];
		$qupddata['item_description'] = $data['item_name'];
		$qupddata['updated'] = date('Y-m-d H:i:s');
		$qupddata['payer_email'] = $data['payer_email'];
		$qupddata['payer_status'] = $data['payer_status'];
		$qupddata['payer_first_name'] = $data['payer_first_name'];
		$qupddata['payer_last_name'] = $data['payer_last_name'];
		$qupddata['payer_address_name'] = $data['payer_address_name'];
		$qupddata['payer_address_country'] = $data['payer_address_country'];
		$qupddata['payer_address_city'] = $data['payer_address_city'];
		$qupddata['payer_address_street'] = $data['payer_address_street'];
		$qupddata['payer_address_zip'] = $data['payer_address_zip'];
		$qupddata['payer_address_state'] = $data['payer_address_state'];
		$qupddata['payer_address_status'] = $data['payer_address_status'];
		$qupddata['payer_residence_country'] = $data['payer_residence_country'];
		$qupddata['payer_payment_date'] = $data['payer_payment_date'];

		$this->db->trans_begin();

		$this->db->where('id', $payments_id);
		$this->db->update('payments', $qupddata);

		if ($this->db->trans_status() === FALSE) {
			error_log('paypal_ipn.php: payment_update(): Transaction failed.');
			$this->db->trans_rollback();
			$this->response->code('500', NDPHP_LANG_MOD_INFO_PAYMENT_UPDATE . ': ' . NDPHP_LANG_MOD_FAILED_TRANSACTION . '. #1', $this->_default_charset, !$this->request->is_ajax());
		} else {
			$this->db->trans_commit();
		}

		if ($payment_status_id == $this->payment_status_success_id) {
			$this->payment_success_post_update($payments_id);
		}
	}

	public function index() {
		$data = array();

		$data['config'] = array();
		$data['config']['charset'] = $this->_default_charset;
		$data['config']['theme'] = $this->_get_theme();

		$this->load->view('themes/' . $this->_default_theme . '/' . 'paypal/payment');
	}

	public function payment_successful() {
		$data = array();

		$data['config'] = array();
		$data['config']['charset'] = $this->_default_charset;
		$data['config']['theme'] = $this->_get_theme();

		$data['project'] = array();
		$data['project']['author'] = $this->_author;
		$data['project']['name'] = $this->_project_name;
		$data['project']['tagline'] = $this->_tagline;
		$data['project']['description'] = $this->_description;

		$data['view'] = array();
		$data['view']['title'] = NDPHP_LANG_MOD_PAYMENT_PAYPAL_IPN_SUCCESS;
		$data['view']['description'] = NDPHP_LANG_MOD_PAYMENT_PAYPAL_IPN_SUCCESS;
		$data['view']['payment_amount'] = $_POST['mc_gross'] - $_POST['tax'];
		$data['view']['payment_currency'] = $_POST['mc_currency'];
		$data['view']['payment_transaction_id'] = $_POST['txn_id'];

		$this->load->view('themes/' . $this->_default_theme . '/' . 'paypal/payment_successful', $data);
	}

	public function payment_cancelled() {
		$data = array();

		$data['config'] = array();
		$data['config']['charset'] = $this->_default_charset;
		$data['config']['theme'] = $this->_get_theme();

		$data['project'] = array();
		$data['project']['author'] = $this->_author;
		$data['project']['name'] = $this->_project_name;
		$data['project']['tagline'] = $this->_tagline;
		$data['project']['description'] = $this->_description;

		$data['view'] = array();
		$data['view']['title'] = NDPHP_LANG_MOD_PAYMENT_PAYPAL_IPN_CANCEL;
		$data['view']['description'] = NDPHP_LANG_MOD_PAYMENT_PAYPAL_IPN_CANCEL;

		$this->load->view('themes/' . $this->_default_theme . '/' . 'paypal/payment_cancelled');
	}

	public function payment_response() {
		// Response from Paypal
		//error_log('Validating response...');
	
		// read the post from PayPal system and add 'cmd'
		$req = 'cmd=_notify-validate';
		foreach ($_POST as $key => $value) {
			$value = urlencode(stripslashes($value));
			$value = preg_replace('/(.*[^%^0^D])(%0A)(.*)/i', '${1}%0D%0A${3}', $value); // IPN fix
			$req .= '&' . $key . '=' . $value;
		}
	
		// assign posted variables to local variables
		$data['item_name']				= $_POST['item_name'];
		$data['item_number'] 			= $_POST['item_number'];
		$data['payment_status'] 		= $_POST['payment_status'];
		$data['payment_amount'] 		= $_POST['mc_gross'];
		$data['quantity'] 				= $_POST['quantity'];
		$data['txn_id']					= $_POST['txn_id'];
		$data['receiver_email'] 		= $_POST['receiver_email'];
		$data['payer_email'] 			= $_POST['payer_email'];
		$data['payer_status'] 			= $_POST['payer_status'];
		$data['payer_first_name']		= $_POST['first_name'];
		$data['payer_last_name']		= $_POST['last_name'];
		$data['payer_address_name']		= $_POST['address_name'];
		$data['payer_address_country']	= $_POST['address_country'];
		$data['payer_address_city']		= $_POST['address_city'];
		$data['payer_address_street']	= $_POST['address_street'];
		$data['payer_address_zip']		= $_POST['address_zip'];
		$data['payer_address_state']	= $_POST['address_state'];
		$data['payer_address_status']	= $_POST['address_status'];
		$data['payer_residence_country']= $_POST['residence_country'];
		$data['payer_payment_date']		= $_POST['payment_date'];
		$data['mc_currency']			= $_POST['mc_currency'];
		$data['custom'] 				= $_POST['custom'];

		/* Decipher payments_id */
		$payments_id_raw = explode('.', $this->encrypt->decode(base64_decode($data['custom'])));
		$payments_id = $payments_id_raw[0];


		// post back to PayPal system to validate
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, 'https://' . $this->paypal_host . '/cgi-bin/webscr');
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

		if (!curl_exec($ch)) {
			error_log('paypal_ipn.php: cURL error: ' . curl_error($ch));
			$this->response->code('500', NDPHP_LANG_MOD_FAILED_VERIFY_TRANSACTION, $this->_default_charset, !$this->request->is_ajax());
		}

		$res = curl_multi_getcontent($ch);

		curl_close($ch);

		/* Update payment status description */
		$data['payment_status'] = $res;

		/* Retrieve original payment amount and item name */
		$this->db->select('payments.amount+payments.total_tax AS amount,payments.items_id AS item_number,payments.item_quantity AS quantity,items.item AS item_name', false);
		$this->db->from('payments');
		$this->db->join('items', 'payments.items_id = items.id', 'left');
		$this->db->where('payments.id', $payments_id);
		$query = $this->db->get();

		$payment_row = $query->row_array();
		$amount = $payment_row['amount'];
		$item_name = $payment_row['item_name'];
		$item_number = $payment_row['item_number'];
		$quantity = $payment_row['quantity'];

		//error_log('payment_amount: ' . $amount . ', ' . $data['payment_amount']);
		//error_log('item_name: ' . $item_name . ', ' . $data['item_name']);
		//error_log('item_number: ' . $item_number . ', ' . $data['item_number']);
		//error_log('quantity: ' . $quantity . ', ' . $data['quantity']);
		//error_log('currency: ' . $this->paypal_currency_code . ', ' . $data['mc_currency']);
		//error_log('receiver_email: ' . $this->paypal_email . ', ' . $data['receiver_email']);

		if (strcmp($res, "VERIFIED") == 0) {
			/* Validate critical data: Amount, item name and id, quantity, currency and receiver */
			if (($amount == $data['payment_amount']) &&
				($item_name == $data['item_name']) &&
				($item_number == $data['item_number']) &&
				($quantity == $data['quantity']) &&
				($data['mc_currency'] == $this->paypal_currency_code) &&
				($data['receiver_email'] == $this->paypal_email))
			{
				$this->payment_update($data, $payments_id, $this->payment_status_success_id);
				//error_log('SUCCESS');
				echo('SUCCESS');
			} else {
				$this->payment_update($data, $payments_id, $this->payment_status_fraudulent_id);
				//error_log('FRAUD');
				die('FRAUD');
			}
		} else if (strcmp($res, "INVALID") == 0) {
			$this->payment_update($data, $payments_id, $this->payment_status_invalid_id);
			//error_log('INVALID');
			die('INVALID');
		} else {
			$this->payment_update($data, $payments_id, $this->payment_status_failed_id);
			//error_log('NO RESPONSE');
			die('NO RESPONSE');
		}
	}
}

