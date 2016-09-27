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

class Roles extends ND_Controller {
	/* Constructor */
	public function __construct($session_enable = true, $json_replies = false) {
		parent::__construct($session_enable, $json_replies);

		/* Initialize controller */
		$this->_init(get_class(), true);
	}

	/** Hooks **/
	protected function _hook_update_pre(&$id, &$POST, &$fields) {
		$hook_pre_return = NULL;

		/* Do not allow changes to ROLE_ADMIN name */
		if ($id == 1 && isset($POST['role']) && $POST['role'] != 'ROLE_ADMIN')
			$this->response->code('403', NDPHP_LANG_MOD_CANNOT_CHANGE_ROLE_ADMIN, $this->config['default_charset'], !$this->request->is_ajax());

		return $hook_pre_return;
	}

	protected function _hook_delete_pre(&$id, &$POST, &$fields) {
		$hook_pre_return = NULL;

		/* Do now allow the ROLE_ADMIN to be deleted */
		if ($id == 1)
			$this->response->code('403', NDPHP_LANG_MOD_CANNOT_DELETE_ROLE_ADMIN, $this->config['default_charset'], !$this->request->is_ajax());

		return $hook_pre_return;
	}

	/** Other overloads **/

	/* Aliases for the current table field names */
	protected $_table_field_aliases = array(
		'role' => NDPHP_LANG_MOD_COMMON_ROLE,
		'description' => NDPHP_LANG_MOD_COMMON_DESCRIPTION
	);


	/** Custom functions **/

	public function setup_role($role)
	{
		/* NOTE: WARNING: users.users_id field shall never have any permissions set */

		/* TODO: FIXME: Grant that users.users_id permissions are not set */
		/* TODO: FIXME: Check if max_input_vars (php.ini) value is suitable for the ammount of variables that will be generated */

		/* If the users doesn't belong to ROLE_ADMIN, then it's not allowed to edit roles */
		if (!$this->security->im_admin())
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->config['default_charset'], !$this->request->is_ajax());

		$data = $this->get->view_data_generic();

		$data['view']['title'] = $this->config['viewhname'] . " - " . NDPHP_LANG_MOD_COMMON_SETUP_ROLE;

		/* Get existing tables from DBMS */
		$query = $this->db->query('SHOW TABLES');

		/* Populate a dictionary with the discovered tables and respective columns */
		$tables = array();

		foreach ($query->result_array() as $field => $value) {
			foreach ($value as $header => $table) {
				if ($table[0] == '_') continue; /* Ignore hidden/detached tables */
				if (substr($table, 0, 4) == 'rel_') continue; /* Ignore multiple relationship tables */
				if (substr($table, 0, 6) == 'mixed_') continue; /* Ignore mixed relationship tables */

				/* Get table fields */
				$table_fields = array();

				foreach ($this->get->fields($table, array(), true) as $field => $meta) {
					array_push($table_fields, $field);
				}

				$tables[$table] = $table_fields;
			}
		}

		/* Get table permissions */
		$this->db->from('_acl_rtp');
		$this->db->where('roles_id', $role);
		$query_table_perms = $this->db->get();
		$table_perms = array();
		foreach ($query_table_perms->result_array() as $table_perms_row) {
			$table_perms[$table_perms_row['_table']] = $table_perms_row['permissions'];
		}

		/* Get table columns permissions */
		$this->db->from('_acl_rtcp');
		$this->db->where('roles_id', $role);
		$query_table_col_perms = $this->db->get();
		$table_col_perms = array();
		foreach ($query_table_col_perms->result_array() as $table_col_perms_row) {
			$table_col_perms[$table_col_perms_row['_table']][$table_col_perms_row['_column']] = $table_col_perms_row['permissions'];
		}

		/* Get role name */
		$this->db->select('role');
		$this->db->where('id', $role);
		$query = $this->db->get($this->config['name']);
		$row = $query->row_array();

		/* Setup view data */
		$data['view']['role_name'] = $row['role'];
		$data['view']['role'] = $role;
		$data['view']['tables'] = $tables;
		$data['view']['table_perms'] = $table_perms;
		$data['view']['table_col_perms'] = $table_col_perms;

		/* Load views */
		$this->load->view('themes/' . $this->config['default_theme'] . '/' . 'header', $data);
		$this->load->view('themes/' . $this->config['default_theme'] . '/' . $this->config['name'] . '/setup_role', $data);
		$this->load->view('themes/' . $this->config['default_theme'] . '/' . 'footer', $data);
	}

	public function setup_role_update() {
		/* If the users doesn't belong to ROLE_ADMIN, then it's not allowed to edit roles */
		if (!$this->security->im_admin())
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->config['default_charset'], !$this->request->is_ajax());

		$role_id = intval($_POST['role_id']);
		
		if (!$role_id)
			$this->response->code('500', NDPHP_LANG_MOD_INVALID_DATA_FOUND . '.', $this->config['default_charset'], !$this->request->is_ajax());
		
		unset($_POST['role_id']);
		
		/* Translation table */
		$trans = array();
		$trans['table']['create'] = $this->security->perm_create;
		$trans['table']['read'] = $this->security->perm_read;
		$trans['table']['update'] = $this->security->perm_update;
		$trans['table']['delete'] = $this->security->perm_delete;
		$trans['field']['create'] = $this->security->perm_create;
		$trans['field']['read'] = $this->security->perm_read;
		$trans['field']['update'] = $this->security->perm_update;
		$trans['field']['search'] = $this->security->perm_search;

		$table_perms = array();
		$table_col_perms = array();

		/* Delete previous permission table referencing the current role_id */
		
		foreach ($_POST as $field => $value) {
			if (!$value)
				continue;

			$field_p = explode('_', $field);
			
			if ($field_p[0] != 'perm')
				$this->response->code('500', NDPHP_LANG_MOD_INVALID_DATA_FOUND . '.', $this->config['default_charset'], !$this->request->is_ajax());

			$field_name = implode('_', array_slice($field_p, 3));

			if ($field_p[2] == 'table') {
				if (!isset($trans['table'][$field_p[1]]))
					$this->response->code('500', NDPHP_LANG_MOD_INVALID_DATA_FOUND . '.', $this->config['default_charset'], !$this->request->is_ajax());

				if (isset($table_perms[$field_name])) {
					$table_perms[$field_name] .= $trans['table'][$field_p[1]];
				} else {
					$table_perms[$field_name] = $trans['table'][$field_p[1]];
				}
			} else if ($field_p[2] == 'field') {
				if (!isset($trans['field'][$field_p[1]]))
					$this->response->code('500', NDPHP_LANG_MOD_INVALID_DATA_FOUND . '.', $this->config['default_charset'], !$this->request->is_ajax());

				$__tcsep = explode('-', $field_name);
				$__table_name = $__tcsep[0];
				$__field_name = $__tcsep[1];
				if (isset($table_col_perms[$__table_name][$__field_name])) {
					$table_col_perms[$__table_name][$__field_name] .= $trans['field'][$field_p[1]];
				} else {
					$table_col_perms[$__table_name][$__field_name] = $trans['field'][$field_p[1]];
				}
			} else {
				$this->response->code('500', NDPHP_LANG_MOD_INVALID_DATA_FOUND . '.', $this->config['default_charset'], !$this->request->is_ajax());
			}
		}

		/* Start db transaction */
		$this->db->trans_begin();

		/* Delete current role permissions */
		$this->db->delete('_acl_rtp', array('roles_id' => $role_id));
		$this->db->delete('_acl_rtcp', array('roles_id' => $role_id));

		/* Update table permissions */
		foreach ($table_perms as $table => $perms) {
			$this->db->insert('_acl_rtp', array(
				'roles_id' => $role_id,
				'_table' => $table,
				'permissions' => $perms
			));
		}

		/* Update table column permissions */
		foreach ($table_col_perms as $table => $table_col) {
			foreach ($table_col as $col => $perms) {
				$this->db->insert('_acl_rtcp', array(
					'roles_id' => $role_id,
					'_table' => $table,
					'_column' => $col,
					'permissions' => $perms
				));
			}
		}

		/* Check if everything was done */
		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			$this->response->code('500', NDPHP_LANG_MOD_FAILED_ROLE_UPDATE, $this->config['default_charset'], !$this->request->is_ajax());
		}

		$this->db->trans_commit();

		/* Clear cache */
		if ($this->cache->is_active())
			$this->cache->flush();

		/* Response */
		$this->response->output(NDPHP_LANG_MOD_SUCCESS_ROLE_UPDATE);

		redirect('/');
	}
}
