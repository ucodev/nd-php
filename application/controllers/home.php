<?php if (!defined('FROM_BASE')) { header($_SERVER['SERVER_PROTOCOL'] . ' 403'); die('Invalid requested path.'); }

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

class Home extends ND_Controller {
	/* Constructor */
	public function __construct($session_enable = true, $json_replies = false) {
		parent::__construct($session_enable, $json_replies);

		/* Initialize controller */
		$this->_init(get_class(), true);
	}

	/* Custom functions */
	public function dashboard_generic() {
		/* If logging is enabled, log access to the dashboard */
		$this->logging->log(
			/* op         */ 'VIEW',
			/* table      */ $this->config['name'],
			/* field      */ 'DASHBOARD',
			/* entry_id   */ NULL,
			/* value_new  */ NULL,
			/* value_old  */ NULL,
			/* session_id */ $this->config['session_data']['sessions_id'],
			/* user_id    */ $this->config['session_data']['user_id'],
			/* log it?    */ $this->config['logging']
		);

		$data = $this->get->view_data_generic(NDPHP_LANG_MOD_COMMON_HOME . ' - ' . NDPHP_LANG_MOD_COMMON_DASHBOARD, NDPHP_LANG_MOD_COMMON_HOME . ' - ' . NDPHP_LANG_MOD_COMMON_DASHBOARD);

		$data['view']['links']['breadcrumb'] = $this->get->view_breadcrumb('dashboard', NDPHP_LANG_MOD_COMMON_DASHBOARD);

		return $data;
	}

	public function dashboard() {
		$data = $this->dashboard_generic();

		$this->load->view('themes/' . $this->config['default_theme'] . '/' . 'header', $data);
		$this->load->view('themes/' . $this->config['default_theme'] . '/' . $this->config['name'] . '/home_header', $data);
		$this->load->view('themes/' . $this->config['default_theme'] . '/' . $this->config['name'] . '/home_data', $data);
		$this->load->view('themes/' . $this->config['default_theme'] . '/' . $this->config['name'] . '/home_footer', $data);
		$this->load->view('themes/' . $this->config['default_theme'] . '/' . 'footer', $data);
	}

	public function dashboard_body_ajax() {
		$data = $this->dashboard_generic();

		$this->load->view('themes/' . $this->config['default_theme'] . '/' . $this->config['name'] . '/home_header', $data);
		$this->load->view('themes/' . $this->config['default_theme'] . '/' . $this->config['name'] . '/home_data', $data);
		$this->load->view('themes/' . $this->config['default_theme'] . '/' . $this->config['name'] . '/home_footer', $data);
	}

	public function index_ajax() {
		$this->dashboard_body_ajax();
	}

	public function index() {
		$this->dashboard();
	}

	public function result_global_generic() {
		/* If logging is enabled, log this listing request */
		if ($this->config['logging'] === true) {
			$log_transaction_id = openssl_digest('RESULT GLOBAL' . $this->config['name'] . $this->session->userdata('sessions_id') . date('Y-m-d H:i:s') . rand(1000000, 9999999), 'sha1');

			$this->db->insert('logging', array(
				'operation' => 'RESULT GLOBAL',
				'_table' => $this->config['name'],
				'transaction' => $log_transaction_id,
				'registered' => date('Y-m-d H:i:s'),
				'sessions_id' => $this->session->userdata('sessions_id'),
				'users_id' => $this->session->userdata('user_id')
			));
		}

		/* Fetch generic view data */
		$data = $this->get->view_data_generic(NDPHP_LANG_MOD_COMMON_HOME . ' - ' . NDPHP_LANG_MOD_OP_RESULT, NDPHP_LANG_MOD_COMMON_HOME . ' - ' . NDPHP_LANG_MOD_OP_RESULT);

		/* Initialize result array */
		$data['view']['result_array'] = array();

		/* Get user id */
		$user_id = $this->session->userdata('user_id');

		/* Get user api key */
		$this->db->select('apikey');
		$this->db->from('users');
		$this->db->where('id', $user_id);
		$q = $this->db->get();
		$userinfo = $q->row_array();
		$apikey = $userinfo['apikey'];

		/* Fetch all eligible tables */
		$controllers = $this->get->controller_list();

		/* Search on every controller [NOTE: Database persistent connection should be enabled,
		 * otherwise this will cause a HUGE ammount of concurrent connections]
		 */
		foreach ($controllers as $ctrl) {
			/* Check if this controller is set to be ignored in global search */
			if (in_array($ctrl, $this->_hide_global_search_controllers))
				continue;

			/* Instantiate foreign object */
			$cobj = $this->access->controller($ctrl, true, true); /* JSON replies is enabled in order to disable pagination */

			/* Search the controller for the search_value key value (in the $_POST) */
			$cobj_data = $cobj->result_generic('basic');

			/* Skip this controller if nothing was returned */
			if (!$cobj_data)
				continue;

			/* Assign result data */
			$result['data'] = $cobj_data['view']['result_array'];

			/* Grant that we've usable data */
			if (!count($result['data']))
				continue;

			/* Check if there is an alias set for this controller */
			$viewname = NULL;

			if (isset($this->config['menu_entries_aliases'][$ctrl])) {
				$viewname = $this->config['menu_entries_aliases'][$ctrl];
			} else {
				/* If not, use the default (capitalize the first character) */
				$viewname = ucfirst($ctrl);
			}

			/* Push the results into view data */
			array_push($data['view']['result_array'], array(
				'controller' => $ctrl,
				'viewname' => $viewname,
				'result' => $result['data']
			));

			/* Destroy the foreign object */
			$cobj = NULL;
		}

		/* Set breadcrumb */
		$data['view']['links']['breadcrumb'] = $this->get->view_breadcrumb('result_global', NDPHP_LANG_MOD_SEARCH_GLOBAL_RESULT);

		/* Set the search_value to be filled in the global search bar */
		$data['view']['search_value'] = $this->request->post('search_value');

		/* All good */
		return $data;
	}

	public function result_global() {
		$data = $this->result_global_generic();

		$this->load->view('themes/' . $this->config['default_theme'] . '/' . 'header', $data);
		$this->load->view('themes/' . $this->config['default_theme'] . '/' . $this->config['name'] . '/home_header', $data);
		$this->load->view('themes/' . $this->config['default_theme'] . '/' . $this->config['name'] . '/home_result_global', $data);
		$this->load->view('themes/' . $this->config['default_theme'] . '/' . $this->config['name'] . '/home_footer', $data);
		$this->load->view('themes/' . $this->config['default_theme'] . '/' . 'footer', $data);
	}

	public function result_global_body_ajax() {
		$data = $this->result_global_generic();

		$this->load->view('themes/' . $this->config['default_theme'] . '/' . $this->config['name'] . '/home_header', $data);
		$this->load->view('themes/' . $this->config['default_theme'] . '/' . $this->config['name'] . '/home_result_global', $data);
		$this->load->view('themes/' . $this->config['default_theme'] . '/' . $this->config['name'] . '/home_footer', $data);
	}
}
