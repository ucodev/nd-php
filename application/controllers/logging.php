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

class Logging extends ND_Controller {
	/* Constructor */
	public function __construct($session_enable = true, $json_replies = false) {
		parent::__construct($session_enable, $json_replies);

		/* Initialize controller */
		$this->_init(get_class(), true);

		/* Grant that only ROLE_ADMIN is able to access this controller */
		if (!$this->security->im_admin())
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_ONLY_ADMIN, $this->config['default_charset'], !$this->request->is_ajax());
	}
	
	/** Hooks **/
	
	/** Other overloads **/

	/* Direction by which the listing views shall be ordered by */
	protected $_table_field_order_list_modifier = 'desc';

	/* Direction by which the result views shall be ordered by */
	protected $_table_field_order_result_modifier = 'desc';

	/* Hidden fields per view. */
	protected $_hide_fields_create = array('id');
	protected $_hide_fields_edit = array('id');
	protected $_hide_fields_view = array();
	protected $_hide_fields_remove = array();
	protected $_hide_fields_list = array('value_old', 'value_new', 'transaction');
	protected $_hide_fields_result = array('value_old', 'value_new', 'transaction');
	protected $_hide_fields_search = array(); // Includes fields searched on searchbar (basic)
	protected $_hide_fields_export = array();

	/* Aliases for the current table field names */
	protected $_table_field_aliases = array(
		'operation' => NDPHP_LANG_MOD_COMMON_OPERATION,
		'_table' => NDPHP_LANG_MOD_COMMON_TABLE,
		'_field' => NDPHP_LANG_MOD_COMMON_FIELD,
		'entryid' => NDPHP_LANG_MOD_COMMON_ENTRY_ID,
		'value_old' => NDPHP_LANG_MOD_COMMON_VALUE_OLD,
		'value_new' => NDPHP_LANG_MOD_COMMON_VALUE_NEW,
		'transaction' => NDPHP_LANG_MOD_COMMON_TRANSACTION,
		'registered' => NDPHP_LANG_MOD_COMMON_REGISTERED,
		'rolled_back' => NDPHP_LANG_MOD_COMMON_ROLLED_BACK
	);

	protected $_rel_table_fields_config = array(
		'sessions' => array(NDPHP_LANG_MOD_COMMON_SESSION, NULL, array(1), array('id', 'asc')),
	);

	/* Quick Operations Links (Listing and Result views) */
	protected $_links_quick_modal_list = array(
		/* array('Description', $sec_perm, $full_url_prefix, 'image/path/img.png', $modal_width) */
		array(NDPHP_LANG_MOD_COMMON_ROLLBACK,	'R', 'rollback_modalbox',    'icons/quick_rollback.png', 600),
		array(NDPHP_LANG_MOD_OP_QUICK_VIEW,		'R', 'view_data_modalbox',   'icons/quick_view.png',     600),
		array(NDPHP_LANG_MOD_OP_QUICK_EDIT,		'U', 'edit_data_modalbox',   'icons/quick_edit.png',     800),
		array(NDPHP_LANG_MOD_OP_QUICK_REMOVE,	'D', 'remove_data_modalbox', 'icons/quick_remove.png',   600)
	);

	protected $_links_quick_modal_result = array(
		/* array('Description', $sec_perm, $function, 'image/path/img.png') */
		array(NDPHP_LANG_MOD_COMMON_ROLLBACK,	'R', 'rollback_modalbox',    'icons/quick_rollback.png', 600),
		array(NDPHP_LANG_MOD_OP_QUICK_VIEW,		'R', 'view_data_modalbox',   'icons/quick_view.png',     600),
		array(NDPHP_LANG_MOD_OP_QUICK_EDIT,		'U', 'edit_data_modalbox',   'icons/quick_edit.png',     800),
		array(NDPHP_LANG_MOD_OP_QUICK_REMOVE,	'D', 'remove_data_modalbox', 'icons/quick_remove.png',   600)
	);

	protected $_links_submenu_body_list = array(
		/* array('Description', $sec_perm, method, 'image/path/img.png', 'ajax' / 'export' / 'method' / 'modal' / 'raw', with id?, access key) */
		array(NDPHP_LANG_MOD_OP_CREATE,			'C', 'create',		NULL, 'ajax',   false,	NDPHP_LANG_MOD_OP_ACCESS_KEY_CREATE	),
		array(NDPHP_LANG_MOD_OP_GROUPS,			'R', 'groups',		NULL, 'ajax',	false,	NDPHP_LANG_MOD_OP_ACCESS_KEY_GROUPS	),
		array(NDPHP_LANG_MOD_OP_SEARCH,			'R', 'search',		NULL, 'ajax',   false,	NDPHP_LANG_MOD_OP_ACCESS_KEY_SEARCH	),
		array(NDPHP_LANG_MOD_OP_EXPORT_PDF,		'R', 'pdf',			NULL, 'export', false,	NULL 								),
		array(NDPHP_LANG_MOD_OP_EXPORT_CSV,		'R', 'csv',			NULL, 'export', false,	NULL 								),
		array(NDPHP_LANG_MOD_OP_IMPORT_CSV,		'C', 'import_csv',	NULL, 'modal',	false,	NULL 								),
		array(NDPHP_LANG_MOD_OP_ERROR_LOG,		'R', 'error_log',	NULL, 'method',	false,	NULL 								)
	);

	protected $_links_submenu_body_result = array(
		/* array('Description', $sec_perm, method, 'image/path/img.png', 'ajax' / 'export' / 'method' / 'modal' / 'raw', with id?, access key) */
		array(NDPHP_LANG_MOD_OP_CREATE,			'C', 'create',		NULL, 'ajax',   false,	NDPHP_LANG_MOD_OP_ACCESS_KEY_CREATE	),
		array(NDPHP_LANG_MOD_OP_LIST,			'R', 'list',		NULL, 'ajax',   false,	NDPHP_LANG_MOD_OP_ACCESS_KEY_LIST	),
		array(NDPHP_LANG_MOD_OP_GROUPS,			'R', 'groups',		NULL, 'ajax',	false,	NDPHP_LANG_MOD_OP_ACCESS_KEY_GROUPS	),
		array(NDPHP_LANG_MOD_OP_SEARCH,			'R', 'search',		NULL, 'ajax',   false,	NDPHP_LANG_MOD_OP_ACCESS_KEY_SEARCH	),
		array(NDPHP_LANG_MOD_OP_EXPORT_PDF,		'R', 'pdf',			NULL, 'export', false,	NULL 								),
		array(NDPHP_LANG_MOD_OP_EXPORT_CSV,		'R', 'csv',			NULL, 'export', false,	NULL 								),
		array(NDPHP_LANG_MOD_OP_IMPORT_CSV,		'C', 'import_csv',	NULL, 'modal',	false,	NULL 								),
		array(NDPHP_LANG_MOD_OP_SAVE_SEARCH,	'R', 'search_save',	NULL, 'modal',	false,	NULL 								),
		array(NDPHP_LANG_MOD_OP_ERROR_LOG,		'R', 'error_log',	NULL, 'method',	false,	NULL 								)
	);

	/** Custom functions **/
	public function rollback($transaction, $force = 'no') {
		/* Fetch the log entries associated to the requested transaction */
		$this->db->select('_table,_field,entryid,value_old,value_new');
		$this->db->from('logging');
		$this->db->where('operation', 'UPDATE'); /* Only supported for UPDATE operation */
		$this->db->where('transaction', $transaction);
		$q_log = $this->db->get();

		if (!$q_log->num_rows())
			$this->response->code('404', NDPHP_LANG_MOD_UNABLE_FIND_TRANSACTION . ': ' . $transaction, $this->config['default_charset'], !$this->request->is_ajax());

		/* Start the rollback process */
		$this->db->trans_begin();

		$log_transaction_id = openssl_digest('ROLLBACK' . $this->config['name'] . $this->session->userdata('sessions_id') . date('Y-m-d H:i:s') . mt_rand(1000000, 9999999), 'md5');

		foreach ($q_log->result_array() as $row) {
			/* TODO: FIXME: Missing handler for 'rel_' and 'mixed_' fields */

			/* Fetch the current value for this field */
			$this->db->select($row['_field']);
			$this->db->from($row['_table']);
			$this->db->where('id', $row['entryid']);
			$q_current = $this->db->get();
			$row_current = $q_current->row_array();

			/* Check if we're forcing the rollback... */
			if ($force == 'no') {
				/* If $force is set to 'no', we need to check if the current
				 * field value on the table matches the value_new on the
				 * transaction that's being rolled back.
				 */

				if ($row_current[$row['_field']] != $row['value_new']) {
					$this->db->trans_rollback();

					$this->response->code('403', NDPHP_LANG_MOD_UNABLE_ROLLBACK_TRANSACTION . ' (' . $transaction . '): ' . NDPHP_LANG_MOD_INFO_ENTRY_CHANGED, $this->config['default_charset'], !$this->request->is_ajax());
				}
			}

			/* Update the table field value based on the log value */
			$this->db->where('id', $row['entryid']);
			$this->db->update($row['_table'], array(
				$row['_field'] => $row['value_old']
			));

			/* Log this operation */
			$this->db->insert('logging', array(
				'operation' => 'ROLLBACK',
				'_table' => $row['_table'],
				'_field' => $row['_field'],
				'entryid' => $row['entryid'],
				'value_new' => $row['value_old'],
				'value_old' => $row_current[$row['_field']],
				'transaction' => $log_transaction_id,
				'registered' => date('Y-m-d H:i:s'),
				'sessions_id' => $this->session->userdata('sessions_id'),
				'users_id' => $this->session->userdata('user_id')
			));
		}

		/* Set the transaction as rolled back */
		$this->db->where('transaction', $transaction);
		$this->db->update('logging', array(
			'rolled_back' => true
		));

		/* Commit database transaction */
		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			$this->response->code('500', NDPHP_LANG_MOD_UNABLE_ROLLBACK_TRANSACTION . ':' . $transaction, $this->config['default_charset'], !$this->request->is_ajax());
		} else {
			$this->db->trans_commit();
		}

		/* All good */
		echo(NDPHP_LANG_MOD_SUCCESS_ROLLBACK_TRANSACTION . ' ' . $transaction . '.');
	}

	public function rollback_modalbox($id) {
		$data['config']['modalbox'] = true;
		$data['config']['charset'] = $this->config['default_charset'];
		$data['view']['ctrl'] = $this->config['name'];

		/* Fetch transaction identifier from database */
		$this->db->select('operation,transaction,rolled_back');
		$this->db->from('logging');
		$this->db->where('id', $id);
		$q = $this->db->get();
		$row = $q->row_array();

		/* Check if the transaction was already rolled back */
		if ($row['rolled_back'])
			$this->response->code('403', NDPHP_LANG_MOD_INFO_ROLLBACK_ALREADY . ' (' . $row['transaction'] . ').', $this->config['default_charset'], !$this->request->is_ajax());

		/* Fetch all the changed items related to the transaction */
		$this->db->select('_table,_field,entryid,value_old,value_new,registered');
		$this->db->from('logging');
		$this->db->where('transaction', $row['transaction']);
		$q = $this->db->get();
		$changes = $q->result_array();

		/* Check if this operation is prone to rollbacks */
		if ($row['operation'] != 'UPDATE')
			$this->response->code('403', NDPHP_LANG_MOD_CANNOT_ROLLBACK_NON_UPDATE, $this->config['default_charset'], !$this->request->is_ajax());

		/* Setup view data */
		$data['view']['transaction'] = $row['transaction'];
		$data['view']['changes'] = $changes;

		/* Load confirmation view */
		$this->load->view('themes/' . $this->config['default_theme'] . '/' . $this->config['name'] . '/rollback_transaction', $data);
	}

	public function error_log() {
		/* Read log file contents */
		$error_log = file_get_contents(SYSTEM_BASE_DIR . '/logs/error.log');

		/* Check if we've been able to read it */
		if ($error_log === false)
			$this->response->code('403', 'Unable to retrieve contents from file' . ': ' . ' logs/error.log', $this->config['default_charset'], !$this->request->is_ajax());

		/* Setup view data */
		$data['config']['charset'] = $this->config['default_charset'];
		$data['view']['error_log'] = $error_log;

		$this->load->view('themes/' . $this->config['default_theme'] . '/' . $this->config['name'] . '/error_log', $data);
	}
}
