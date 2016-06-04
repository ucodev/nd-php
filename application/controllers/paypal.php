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

class Paypal extends ND_Controller {
	/* General settings */
	protected $_author = "ND PHP Framework";	// Project Author
	protected $_name;				// Controller segment / Table name (must be lower case)
	protected $_viewhname;			// The name used in the view headers

	private $paypal_host = "www.paypal.com";
	private $paypal_email = "paypal@your.domain";
	private $paypal_currency_code = 'EUR';
	private $return_url = 'https://localhost/ndphp/index.php/paypal_ipn/payment_successful';
	private $cancel_url = 'https://localhost/ndphp/index.php/paypal_ipn/payment_cancelled';
	private $notify_url = 'https://localhost/ndphp/index.php/paypal_ipn/payment_response';
	private $local_vies_json_url = 'https://localhost/ndphp/checkvat.php'; /* TODO: FIXME: Not yet implemented */
	private $tax_source_country_code = 'PT';

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

	/* Constructor */
	public function __construct($session_enable = true, $json_replies = false) {
		parent::__construct($session_enable, $json_replies);

		$this->_viewhname = get_class();
		$this->_name = strtolower($this->_viewhname);
		$this->_hook_construct();

		/* Include any setup procedures from ide builder. */
		include('lib/ide_setup.php');
	}

	private function payment_init($quantity = 10) {
		$items_id = 1;	// Paypal credits

		/* Initialize payment */
		$this->db->trans_begin();

		$this->db->select('item,price');
		$this->db->from('items');
		$this->db->where('id', $items_id);
		$query = $this->db->get();
		$item_row = $query->row_array();
		$amount_no_tax = $item_row['price'] * $quantity;
		$item_name = $item_row['item'];

		$qinsdata['txnid'] = 'PAYPAL';
		$qinsdata['payment_types_id'] = $this->payment_types_paypal_id;
		$qinsdata['amount'] = $amount_no_tax;
		$qinsdata['payment_fee'] = $this->payment_get_mc_fee($amount_no_tax); 
		$qinsdata['total_tax'] = round((($amount_no_tax + $qinsdata['payment_fee']) * ($qinsdata['tax_rate'] / 100.0)) + $qinsdata['payment_fee'], 2);
		$qinsdata['payment_status_id'] = $this->payment_status_pending_id;
		$qinsdata['status_desc'] = 'Pending';
		$qinsdata['items_id'] = $items_id;
		$qinsdata['item_price'] = $item_row['price'];
		$qinsdata['item_quantity'] = $quantity;
		$qinsdata['item_description'] = 'None';
		$qinsdata['created'] = date('Y-m-d H:i:s');
		$qinsdata['users_id'] = $this->session->userdata('user_id');
		$qinsdata['payment_actions_id'] = $this->payment_actions_pending_id;

		$features = $this->_get_features();

		if ($features['register_confirm_vat_eu']) {
			$qinsdata['tax_rate'] = $this->payment_user_vat_eu(); /* TODO: FIXME: Not yet implemented */
		} else {
			$qinsdata['tax_rate'] = '0';
		}

		$this->db->insert('payments', $qinsdata);

		$payments_id = $this->db->insert_id();

		if (!$payments_id) {
			error_log('paypal.php: payment_init(): Insert failed.');
			header('HTTP/1.0 500 Internal Server Error');
			die(NDPHP_LANG_MOD_FAILED_TRANSACTION . '. #1');
		}

		if ($this->db->trans_status() === FALSE) {
			error_log('paypal.php: payment_init(): Transaction failed.');
			$this->db->trans_rollback();
			header('HTTP/1.0 500 Internal Server Error');
			die(NDPHP_LANG_MOD_FAILED_TRANSACTION . '. #2');
		} else {
			$this->db->trans_commit();
		}

		$data['payments_id'] = $payments_id;
		$data['amount_no_tax'] = $amount_no_tax;
		$data['item_name'] = $item_name;
		$data['item_number'] = $qinsdata['items_id'];
		$data['item_price'] = $qinsdata['item_price'];
		$data['total_tax'] = $qinsdata['total_tax'];

		return $data;
	}

	private function payment_filter_post_data(&$POST) {
		$new_data = array();

		/* Fielter POST data */
		if (isset($_POST['item_quantity'])) {
			$new_data['item_quantity'] = intval($POST['item_quantity']);
		} else {
			header('HTTP/1.1 403 Forbidden');
			die(NDPHP_LANG_MOD_INVALID_POST_DATA);
		}

		$POST = $new_data;

		return $POST;
	}

	private function payment_validate_quantity($quantity) {
		$this->db->select('transaction_min_amount,transaction_max_amount');
		$this->db->from('payment_types');
		$this->db->where('id', '1');
		$query = $this->db->get();
		$rawdata = $query->row_array();

		if ($quantity > $rawdata['transaction_max_amount'])
			return false;

		if ($quantity < $rawdata['transaction_min_amount'])
			return false;

		return true;
	}

	private function payment_user_vat_eu() {
		$this->db->select('users.vat AS vat,countries.eu_state AS eu_state,countries.code AS code');
		$this->db->from('users');
		$this->db->join('countries', 'countries.id = users.countries_id', 'left');
		$this->db->where('users.id', $this->session->userdata('user_id'));
		$query = $this->db->get();
		$rawdata = $query->row_array();
		$vatraw = $rawdata['vat'];
		$eu_state = $rawdata['eu_state'];
		$country_code = $rawdata['code'];

		$this->db->select('vat_rate');
		$this->db->from('countries');
		$this->db->where('code', $this->tax_source_country_code);
		$query = $this->db->get();
		$rawdata = $query->row_array();
		$country_vat_rate = $rawdata['vat_rate'];

		if ($eu_state != '1')
			return '0';

		if ($country_code == $this->tax_source_country_code)
			return $country_vat_rate;

		if (!$vatraw || ($vatraw == ''))
			return $country_vat_rate;

		$vat_cc = substr($vatraw, 0, 2);
		$vat_vn = substr($vatraw, 2);

		if (!$vat_cc || !$vat_vn)
			return $country_vat_rate;

		$vat_json = file_get_contents($this->local_vies_json_url . '/?cc=' . $vat_cc . '&vn=' . $vat_vn);

		$vatinfo = json_decode($vat_json, true);

		if (!$vatinfo || $vatinfo == array())
			return $country_vat_rate;

		if ($vatinfo['valid'] !== true)
			return $country_vat_rate;

		return '0';
	}

	private function payment_get_mc_fee($amount) {
		$this->db->select('transaction_fee_percentage,transaction_fee_fixed');
		$this->db->from('payment_types');
		$this->db->where('id', '1');
		$query = $this->db->get();
		$rawdata = $query->row_array();

		$mc_fee = $amount * ($rawdata['transaction_fee_percentage'] / 100.0);
		$mc_fee += $rawdata['transaction_fee_fixed'];

		return $mc_fee;
	}

	public function index() {
		$data = $this->_get_view_data_generic($this->_viewhname, $this->_viewhname);

		$this->load->view('themes/' . $this->_theme . '/' . 'header', $data);
		$this->load->view('themes/' . $this->_theme . '/' . 'paypal/payment', $data);
		$this->load->view('themes/' . $this->_theme . '/' . 'footer', $data);
	}

	public function payment_form_ajax() {
		$data = $this->_get_view_data_generic($this->_viewhname, $this->_viewhname);

		$this->load->view('themes/' . $this->_theme . '/' . 'paypal/payment', $data);
	}

	public function payment_request() {
		/* Apply filters */
		$_POST = $this->payment_filter_post_data($_POST);

		$item_quantity = $_POST['item_quantity'];

		/* Validate payment ammount */
		if ($this->payment_validate_quantity($item_quantity) !== true) {
			header('HTTP/1.1 403 Forbidden');
			die(NDPHP_LANG_MOD_PAYMENT_INVALID_AMMOUNT);
		}

		$payment_data = $this->payment_init($item_quantity);

		$item_name = $payment_data['item_name'];
		$item_number = $payment_data['item_number'];
		$item_price = $payment_data['item_price'];
		$amount_no_tax = $payment_data['amount'] * $item_quantity;
		$total_tax = $payment_data['total_tax'];
		
		// Firstly Append paypal account to querystring
		$querystring .= '?business=' . urlencode($this->paypal_email) . '&';

		// Append amount& currency (£) to quersytring so it cannot be edited in html
	
		//The item name and amount can be brought in dynamically by querying the $_POST['item_number'] variable.
		$querystring .= 'item_name=' . urlencode($item_name) . '&';
		$querystring .= 'item_number=' . urlencode($item_number) . '&';
		$querystring .= 'amount=' . urlencode(round($item_price, 2)) . '&';
		$querystring .= 'quantity=' . $item_quantity . '&';
		$querystring .= 'payment_gross=' . urlencode($item_amount) . '&';
		$querystring .= 'currency_code=' . urlencode($this->paypal_currency_code) . '&';

		/* Setup tax and fees */
		$querystring .= 'tax=' . $total_tax . '&';
		//$querystring .= 'tax_rate=' . $this->payment_user_vat_eu() . '&';

		/* Other required stuff */
		$querystring .= 'cmd=_xclick&';
		$querystring .= 'no_note=1&';
		$querystring .= 'lc=PT&';
		$querystring .= 'bn=PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest&';

		/* Append paypal return addresses */
		$querystring .= 'return=' . urlencode(stripslashes($this->return_url)) . '&';
		$querystring .= 'cancel_return=' . urlencode(stripslashes($this->cancel_url)) . '&';
		$querystring .= 'notify_url=' . urlencode(stripslashes($this->notify_url)) . '&';
		$querystring .= 'custom=' . urlencode(base64_encode($this->encrypt->encode($payment_data['payments_id'] . '.' . rand(100000, 1000000))));
	
		/* Redirect to paypal IPN */
		header('Location: https://' . $this->paypal_host . '/cgi-bin/webscr' . $querystring);
	}
}

