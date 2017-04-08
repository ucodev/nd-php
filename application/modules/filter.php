<?php if (!defined('FROM_BASE')) { header('HTTP/1.1 403 Forbidden'); die('Invalid requested path.'); }

/*
 * This file is part of ND PHP Framework.
 *
 * ND PHP Framework - An handy PHP Framework (www.nd-php.org)
 * Copyright (C) 2015-2017  Pedro A. Hortas (pah@ucodev.org)
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

class UW_Filter extends UW_Module {
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

	public function table_row_apply($table = NULL) {
		$this->load->module('get');

		/* Superadmin users do not have row filters applied */
		if ($this->security->im_superadmin())
			return;

		if ($this->config['table_row_filtering'] === true) {
			$field_list = $this->get->table_fields($table ? $table : $this->config['name']);

			foreach ($this->config['table_row_filtering_config'] as $key => $value) {
				if (in_array($key, $field_list)) {
					/* If this is a filter based on user ID, grant that the special privileges regarding superadmin, admin and superuser are applied */
					if ($this->config['table_row_filtering_config'][$key] == 'user_id') {
						if ($this->security->im_admin()) {
							/* Admin users cannot access rows owned by superadmins */
							$exclude_users_id = $this->security->users_superadmin();

							if (count($exclude_users_id))
								$this->db->where_not_in(($table ? $table : $this->config['name']) . '.' . $key, $exclude_users_id);
						} else if ($this->security->im_superuser()) {
							/* Superuser users cannot access rows owned by superadmins nor admins */
							$exclude_users_id = array_merge($this->security->users_superadmin(), $this->security->users_admin());

							if (count($exclude_users_id))
								$this->db->where_not_in(($table ? $table : $this->config['name']) . '.' . $key, $exclude_users_id);
						} else {
							/* All other users can only access rows that they own */
							$this->db->where(($table ? $table : $this->config['name']) . '.' . $key, $this->config['session_data'][$this->config['table_row_filtering_config'][$key]]);
						}
					} else {
						$this->db->where(($table ? $table : $this->config['name']) . '.' . $key, $this->config['session_data'][$this->config['table_row_filtering_config'][$key]]);
					}
				}
			}
		}
	}

	public function table_row_perm($id = false, $table = NULL, $id_field = 'id') {
		$this->load->module('get');

		/* Superuser users do not have row filters applied */
		if ($this->security->im_superadmin())
			return true;

		if ($id === false)
			return false;

		if ($this->config['table_row_filtering'] === true) {
			$field_list = $this->get->table_fields($table ? $table : $this->config['name']);

			foreach ($this->config['table_row_filtering_config'] as $key => $value) {
				if (in_array($key, $field_list)) {
					$this->db->select($key);
					$this->db->from($table ? $table : $this->config['name']);
					$this->db->where(($table ? $table : $this->config['name']) . '.' . $id_field, $id);

					/* If this is a filter based on user ID, grant that the special privileges regarding superadmin, admin and superuser are applied */
					if ($this->config['table_row_filtering_config'][$key] == 'userid') {
						if ($this->security->im_admin()) {
							/* Admin users cannot access rows owned by superadmins */
							$exclude_users_id = $this->security->users_superadmin();

							if (count($exclude_users_id))
								$this->db->where_not_in(($table ? $table : $this->config['name']) . '.' . $key, $exclude_users_id);
						} else if ($this->security->im_superuser()) {
							/* Superuser users cannot access rows owned by superadmins nor admins */
							$exclude_users_id = array_merge($this->security->users_superadmin(), $this->security->users_admin());

							if (count($exclude_users_id))
								$this->db->where_not_in(($table ? $table : $this->config['name']) . '.' . $key, $exclude_users_id);
						} else {
							/* All other users can only access rows that they own */
							$this->db->where(($table ? $table : $this->config['name']) . '.' . $key, $this->config['session_data'][$this->config['table_row_filtering_config'][$key]]);
						}
					} else {
						$this->db->where(($table ? $table : $this->config['name']) . '.' . $key, $this->config['session_data'][$this->config['table_row_filtering_config'][$key]]);
					}

					$query = $this->db->get();

					/* If there are no results, then this user has no privileges to access the requested entry */
					if (!$query->num_rows())
						return false;
				}
			}
		}

		return true;
	}

	public function table_row_get($table = NULL) {
		$this->load->module('get');

		/* Evaluates if there's a table row filter to be applied. If so, an assoc array is created for each configured filtering key */

		$res = array();

		if ($this->config['table_row_filtering'] === true) {
			$field_list = $this->get->table_fields($table ? $table : $this->config['name']);

			foreach ($this->config['table_row_filtering_config'] as $key => $value) {
				if (in_array($key, $field_list)) {
					$res[$key] = $this->config['session_data'][$value];
				}
			}
		}

		return $res;
	}

	public function fields($sec_perms, $req_perm, $fields_array) {
		/* Filter fields from $field_array based on the requested permission ($req_perm) */
		$fields_array_filtered = array();

		foreach ($fields_array as $field => $meta) {
			if ($meta['type'] == 'rel') {
				if (!$this->security->perm_check($sec_perms, $this->security->perm_read, $meta['table'])) /* FIXME: Multiple and mixed use different assignments to $meta['table'] ?? */
					continue;

				if (!$this->security->perm_check($sec_perms, $req_perm, $meta['base_table'], $field))
					continue;
			} else if ($meta['type'] == 'mixed') {
				if (!$this->security->perm_check($sec_perms, $this->security->perm_read, $meta['rel_table']))
					continue;

				if (!$this->security->perm_check($sec_perms, $req_perm, $meta['base_table'], $field))
					continue;
			} else if ($meta['input_type'] == 'select') {
				/* If we don't have permissions to read the foreign table, we won't have permissions to do anything else on this field */
				if (!$this->security->perm_check($sec_perms, $this->security->perm_read, $meta['table']))
					continue;

				/* If we have read permissions on the foreign table, we still need to grant that we have the requested permission on the field */
				if (!$this->security->perm_check($sec_perms, $req_perm, $this->config['name'], $field))
					continue;
			} else {
				if (!$this->security->perm_check($sec_perms, $req_perm, $this->config['name'], $field))
					continue;
			}

			$fields_array_filtered[$field] = $meta;
		}

		return $fields_array_filtered;
	}

	public function selected_fields($fields_array, $where_array = array(), $hidden_filter = array()) {
		/* Selects (query) the table columns based on $fields_array and applies the $where_array clauses. */
		$selected = '';

		foreach ($fields_array as $field => $meta) {
			/* Multiple and Mixed relationships are not database columns, so skip them */
			if ($meta['type'] == 'rel' || $meta['type'] == 'mixed')
				continue;

			$selected .= $field . ',';
		}

		$selected = rtrim($selected, ',');

		/* Select only the available fields */
		$this->db->select($selected);

		/* Apply the query clauses */
		foreach ($where_array as $field => $value) {
			$this->db->where($field, $value);
		}
	}
}