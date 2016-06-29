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

class Subscription_types extends ND_Controller {
	/* Constructor */
	public function __construct($session_enable = true, $json_replies = false) {
		parent::__construct($session_enable, $json_replies);

		$this->_viewhname = get_class();
		$this->_name = strtolower($this->_viewhname);

		/* Include any setup procedures from ide builder. */
		include('lib/ide_setup.php');

		/* Populate controller configuration */
		$this->config_populate();

		/* Call construct hook */
		$this->_hook_construct();
	}
	
	/** Hooks **/
	
	/** Other overloads **/
	/* Aliases for the current table field names */
	protected $_table_field_aliases = array(
		'subscription_type' => NDPHP_LANG_MOD_COMMON_SUBSCRIPTION_TYPE,
		'description' => NDPHP_LANG_MOD_COMMON_DESCRIPTION,
		'price' => NDPHP_LANG_MOD_COMMON_PRICE,
		'api_extended' => NDPHP_LANG_MOD_COMMON_API_EXTENDED
	);

	/** Custom functions **/
	private function subscriptions_form_upgrade_generic() {
		$this->db->select('subscription_types_id');
		$this->db->from('users');
		$this->db->where('id', $this->session->userdata('user_id'));
		$query = $this->db->get();
		$rawdata = $query->row_array();

		$data = $this->_get_view_data_generic();

		$data['view']['user_current_plan_id'] = $rawdata['subscription_types_id'];

		$this->db->select('id,subscription_type,price');
		$this->db->from('subscription_types');
		$this->db->where('id <=', 4); /* FIXME: TODO: Number of subscription should not be hardcoded */
		$query = $this->db->get();

		$data['view']['subscription_types'] = array();

		foreach ($query->result_array() as $row)
			$data['view']['subscription_types'][$row['id']] = array($row['subscription_type'], $row['price']);

		return $data;
	}

	public function subscriptions_form_upgrade_ajax() {
		$data = $this->subscriptions_form_upgrade_generic();

		$this->load->view('themes/' . $this->_default_theme . '/' . $this->_name . '/upgrade', $data);
	}

	public function subscription_upgrade() {
		$this->db->select('subscription_types_id');
		$this->db->from('users');
		$this->db->where('id', $this->session->userdata('user_id'));
		$query = $this->db->get();
		$rawdata = $query->row_array();

		$user_current_plan_id = $rawdata['subscription_types_id'];

		$this->db->select('id,subscription_type,price');
		$this->db->from('subscription_types');
		$this->db->where('id <=', 4); /* FIXME: TODO: Number of subscription should not be hardcoded */
		$query = $this->db->get();

		$plan_id_exists = false;
		$plan_name = NULL;
		$plan_price = NULL;

		foreach ($query->result_array() as $row) {
			if ($row['id'] == $_POST['subscription_types_id']) {
				$plan_id_exists = true;
				$plan_name = $row['subscription_type'];
				$plan_price = $row['price'];
				break;
			}
		}

		if ($plan_id_exists === false)
			$this->response->code('403', NDPHP_LANG_MOD_INVALID_SUBSCRIPTION_TYPE, $this->_default_charset, !$this->request->is_ajax());

		if ($user_current_plan_id >= $_POST['subscription_types_id'])
			$this->response->code('403', NDPHP_LANG_MOD_CANNOT_DOWNGRADE_SUBSCRIPTION . ' ' . NDPHP_LANG_MOD_ATTN_CONTACT_SUPPORT, $this->_default_charset, !$this->request->is_ajax());

		$this->db->trans_begin();

		$this->db->select('credit,allow_negative');
		$this->db->from('users');
		$this->db->where('id', $this->session->userdata('user_id'));
		$query = $this->db->get();
		$rawdata = $query->row_array();

		$user_credit = $rawdata['credit'];
		$allow_negative = $rawdata['allow_negative'];

		if (($user_credit < $plan_price) && ($allow_negative != '1')) {
			$this->db->trans_rollback();
			$this->response->code('403', NDPHP_LANG_MOD_CANNOT_UPGRADE_SUBSCR_CREDIT . ' ' . NDPHP_LANG_MOD_ATTN_ADD_FUNDS, $this->_default_charset, !$this->request->is_ajax());
		}

		$user_credit -= $plan_price;

		$userdata['credit'] = $user_credit;
		$userdata['subscription_types_id'] = $_POST['subscription_types_id'];
		$userdata['subscription_change_date'] = date('Y-m-d H:i:s');
		$userdata['subscription_renew_date'] = date('Y-m-d', strtotime('+1 month'));

		$this->db->where('id', $this->session->userdata('user_id'));
		$this->db->update('users', $userdata);

		$historydata['transaction_date'] = date('Y-m-d H:i:s');
		$historydata['transaction_types_id'] = '1';
		$historydata['ammount'] = $plan_price;
		$historydata['description'] = NDPHP_LANG_MOD_SUBSCRIPTION_UPGRADE . ': ' . $plan_name;
		$historydata['users_id'] = $this->session->userdata('user_id');

		$this->db->insert('transaction_history', $historydata);

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			$this->response->code('500', NDPHP_LANG_MOD_CANNOT_UPGRADE_SUBSCRIPTION . ' ' . NDPHP_LANG_MOD_ATTN_CONTACT_SUPPORT, $this->_default_charset, !$this->request->is_ajax());
		} else {
			$this->db->trans_commit();
		}

		$data = $this->_get_view_data_generic();

		$data['view']['plan_name'] = $plan_name;
		$data['view']['plan_price'] = $plan_price;

		$this->load->view('themes/' . $this->_default_theme . '/' . $this->_name . '/upgrade_successful.php', $data);
	}
}

