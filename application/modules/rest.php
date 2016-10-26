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

class UW_Rest extends UW_Module {
	private $config;	/* Configuration */

	private function _init() {
		/* Load configuration */
		$this->config = $this->configuration->core_get();

		/* Load required modules */
		$this->load->module('request');
		$this->load->module('response');
	}

	public function __construct() {
		parent::__construct();

		/* Initialize module */
		$this->_init();
	}

	/** JSON Interface **/

	public function json_doc() {
		$this->load->module('get');

		if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_read, $this->config['name']))
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_FORBIDDEN, $this->config['default_charset'], !$this->request->is_ajax());

		/* Setup basic view data */
		$data = $this->get->view_data_generic('JSON REST API', 'JSON REST API');

		$data['view']['data_fields'] = $this->get->fields();

		/* Get user api key */
		$this->db->select('apikey');
		$this->db->from('users');
		$this->db->where('id', $this->config['session_data']['user_id']);
		$q = $this->db->get();
		$userinfo = $q->row_array();

		/* Setup specific view data */
		$data['view']['user_id'] = $this->config['session_data']['user_id'];
		$data['view']['apikey'] = $userinfo['apikey'];

		/* TODO: Load a table entry (if exists) in order to create valid (and real) cURL examples for insert/update calls */


		/* TODO: FIXME: Missing multiple and mixed relationship documentation */
		$this->load->view('documentation/json', $data);
	}

	public function json_view($data) {
		$this->load->module('get');

		$json_res['status'] = true;
		$json_res['data']['fields'] = $data['view']['result_array'];
		$json_res['data']['rel'] = $data['view']['rel'];
		$json_res['data']['mixed'] = array();

		/* Fetch mixed relationship tables */
		$mixed_rels = $this->get->relative_tables($this->config['name'], 'mixed');
		
		/* Populate 'mixed' object with all mixed entries found per table */
		foreach ($mixed_rels as $mixed) {
			/* Fetch all entries related to this item from the mixed table */
			$this->db->from($mixed);
			$this->db->where($this->config['name'] . '_id', $data['view']['result_array'][0]['id']); /* Id must always be present */
			$q = $this->db->get();

			$json_res['data']['mixed'][$mixed] = array();

			foreach ($q->result_array() as $row) {
				array_push($json_res['data']['mixed'][$mixed], $row);
			}
		}

		/* Update accounting counters if accounting is enabled */
		if ($this->config['accounting'])
			$this->accounting->user_counter_increment($this->config['session_data']['user_id'], 'acct_rest_view');

		/* All good */
		return json_encode($json_res);
	}

	public function json_list($data) {
		$json_res['status'] = true;
		$json_res['data'] = $data['view']['result_array'];

		/* Update accounting counters if accounting is enabled */
		if ($this->config['accounting'])
			$this->accounting->user_counter_increment($this->config['session_data']['user_id'], 'acct_rest_list');

		return json_encode($json_res);
	}

	public function json_result($data) {
		$json_res['status'] = true;
		$json_res['data'] = $data['view']['result_array'];

		/* Update accounting counters if accounting is enabled */
		if ($this->config['accounting'])
			$this->accounting->user_counter_increment($this->config['session_data']['user_id'], 'acct_rest_result');

		return json_encode($json_res);
	}

	public function json_insert($insert_id) {
		$json_res['status'] = true;
		$json_res['data']['inserted'] = true;
		$json_res['data']['insert_id'] = $insert_id;

		/* Update accounting counters if accounting is enabled */
		if ($this->config['accounting'])
			$this->accounting->user_counter_increment($this->config['session_data']['user_id'], 'acct_rest_insert');

		return json_encode($json_res);
	}

	public function json_update() {
		$json_res['status'] = true;
		$json_res['data']['updated'] = true;

		/* Update accounting counters if accounting is enabled */
		if ($this->config['accounting'])
			$this->accounting->user_counter_increment($this->config['session_data']['user_id'], 'acct_rest_update');

		return json_encode($json_res);
	}

	public function json_delete($data) {
		$json_res['status'] = true;
		$json_res['data']['deleted'] = true;

		/* Update accounting counters if accounting is enabled */
		if ($this->config['accounting'])
			$this->accounting->user_counter_increment($this->config['session_data']['user_id'], 'acct_rest_delete');

		return json_encode($json_res);
	}
}