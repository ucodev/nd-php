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

class UW_Process extends UW_Module {
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

	/** Mixed handlers **/

	public function mixed_post_data($mixed_rels, $last_id, $ftypes, $remove_existing = false) {
		$this->load->module('filter');
		$this->load->module('get');

		if (count($mixed_rels) /* If $mixed_rels array is empty, do not insert, remove nor process updates on mixed fields */) {
			if ($remove_existing) {
				/* Remove old mixed relationship entries */
				foreach ($ftypes as $fname => $fmeta) {
					if ($fmeta['type'] == 'mixed') {
						/* Security Permissions Check */
						if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_read, $fmeta['rel_table'])) {
							$this->db->trans_rollback();
							$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->config['default_charset'], !$this->request->is_ajax());
						}
	        	
						$this->db->where($this->config['name'] . '_id', $last_id);
						$this->db->delete($fmeta['table']);
					}
				}
			}
        	
			/* Update mixed relationships */
			$mixed_foreign_value_id = array(); /* Will contain the entry id of foreign single relationship tables... (used by $_mixed_table_add_missing) */

			foreach ($mixed_rels as $mixed_table => $mixed_table_value) {
				foreach ($mixed_table_value as $mixed_id => $mixed_id_value) {
					foreach ($mixed_id_value as $mixed_field => $mixed_field_value) {
						if (($mixed_field_value == '') || ($mixed_field_value == NULL) || isset($mixed_insert_values[$mixed_field]))
							continue;
        	
						/* Check for exceptions, for example, datetime fields are split into _time and _date suffixes */
						if (isset($mixed_rels[$mixed_table][$mixed_id][$mixed_field . '_time'])) {
							$mixed_insert_values[$mixed_field] = $this->timezone->convert($mixed_field_value . ' ' . $mixed_rels[$mixed_table][$mixed_id][$mixed_field . '_time'], $this->config['session_data']['timezone'], $this->config['default_timezone']);
						} else if ((substr($mixed_field, -5) == '_time')) {
							$mixed_insert_values[substr($mixed_field, 0, -5)] = $this->timezone->convert($mixed_rels[$mixed_table][$mixed_id][substr($mixed_field, 0, -5)] . ' ' . $mixed_rels[$mixed_table][$mixed_id][$mixed_field], $this->config['session_data']['timezone'], $this->config['default_timezone']);
						} else if ((substr($mixed_field, -3) == '_id') && (strpos($mixed_field_value, '_'))) {
							/* Single relationship fields' identifiers on mixed relationships use the <id>_<value> format */
							$mixed_field_val_raw = explode('_', $mixed_field_value);
							$mixed_field_val_id = $mixed_field_val_raw[0];
							$mixed_field_val_value = $mixed_field_val_raw[1];
							$mixed_field_table_name = substr($mixed_field, 0, -3);
        	
							/* Grant that the user has privileges to access the foreign table item */
							if (!$this->filter->table_row_perm($mixed_field_val_id, $mixed_field_table_name)) {
								$this->db->trans_rollback();
								$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->config['default_charset'], !$this->request->is_ajax());
							}
        	
							/* Exclude the ID portion of the mixed_field_value and get only corresponding value */
							/* Note that the format of relational mixed fields is: '<id>_<value>' */
							$mixed_insert_values[$mixed_field] = $mixed_field_val_value;

							/* Also store the entry id... this may be required for later use if we need to add a missing entry */
							$mixed_foreign_value_id[$mixed_field_table_name] = $mixed_field_val_id;
						} else {
							$mixed_insert_values[$mixed_field] = $mixed_field_value;
						}
					}
        	
					/* Get optional fields and retrieve the respective values, if any */
					$mixed_table_fields = $this->get->table_fields('mixed_' . $this->config['name'] . '_' . $mixed_table);
					foreach ($mixed_table_fields as $mixed_field) {
						/* Check if this is a private field */
						if (substr($mixed_field, 0, 2) != '__')
							continue;
        	
						$pmfield = explode('_tc_', substr($mixed_field, 2));
        	
						$ftname = $pmfield[0];	/* Foreign Table name */
						$ftcname = $pmfield[1];	/* Foreign Table Column name */
						
						$this->db->select($ftcname);
						$this->db->distinct();	/* NOTE: Not required since the key we're looking for must be UNIQUE. */
						$this->db->from($ftname);
						$this->db->where('id', strstr($mixed_rels[$mixed_table][$mixed_id][$ftname . '_id'], '_', true));
						$this->filter->table_row_apply($ftname);
        	
						$query_mixed = $this->db->get();
        	
						/* If empty, there's no permissions */
						if (!$query_mixed->num_rows()) {
							$this->db->trans_rollback();
							$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->config['default_charset'], !$this->request->is_ajax());
						}
        	
						$row_mixed = $query_mixed->row_array();
						$mixed_insert_values[$mixed_field] = $row_mixed[$ftcname];
					}
        	
					/* Check if all secondary relational table foreign keys exist */
					$srtf_field_found = false;
					foreach ($mixed_insert_values as $field => $value) {
						if ($field == $mixed_table_fields[3]) {
							$srtf_field_found = true;
							break;
						}
					}

					if ($srtf_field_found === false) {
						/* Before checking for a matching key, validate if there's any value to search for... */
						if (!isset($mixed_insert_values[$mixed_table_fields[1]]) || $mixed_insert_values[$mixed_table_fields[1]] === NULL || $mixed_insert_values[$mixed_table_fields[1]] == '')
							continue; /* Nothing to be done with this entry as the key identifier isn't set or it's empty. */

						/* Update mixed_insert_values with missing relational field */
						$this->db->select('id');
						$this->db->from(substr($mixed_table_fields[3], 0, -3));
						$this->db->where($mixed_table_fields[1], $mixed_insert_values[$mixed_table_fields[1]]);

						$srtf_query = $this->db->get();

						if (!$srtf_query->num_rows()) {
							/* If $_mixed_table_add_missing is true, insert the element on the foreign table */
							if ($this->config['mixed_table_add_missing'] === true) {
								/* Clone the values to be inserted on mixed table */
								$secondary_insert_values = $mixed_insert_values;

								/* Unset relationships */
								unset($secondary_insert_values[$mixed_table_fields[2]]);
								unset($secondary_insert_values[$mixed_table_fields[3]]);

								/* Clear any _tc_ fields */
								foreach ($secondary_insert_values as $field => $value) {
									if (count(explode('_tc_', $field)) > 1) {
										unset($secondary_insert_values[$field]);
									}
								}

								/* Resolve single relationships back to their original entry id */
								foreach ($secondary_insert_values as $field => $value) {
									/* Ignore fields that are not single relationships */
									if (substr($field, -3) != '_id')
										continue;

									/* Fetch the previously stored entry id related to this single relationship */
									$secondary_insert_values[$field] = $mixed_foreign_value_id[substr($field, -3)];
								}

								/* Set any existing filtering fields */
								$secondary_insert_values = array_merge($secondary_insert_values, $this->filter->table_row_get($mixed_table));

								/* Insert data into the secondary table */
								$this->db->insert($mixed_table, $secondary_insert_values);

								/* Set the newly inserted id as the mixed relationship id */
								$mixed_insert_values[$mixed_table_fields[3]] = $this->db->last_insert_id();
							} else if (isset($this->config['mixed_table_set_missing'][$mixed_table])) {
								/* There's a default id to be used associated to this mixed table */
								$mixed_insert_values[$mixed_table_fields[3]] = $this->config['mixed_table_set_missing'][$mixed_table];
							} else {
								$this->db->trans_rollback();
								$this->response->code('403', NDPHP_LANG_MOD_INVALID_MIXED_VALUE, $this->config['default_charset'], !$this->request->is_ajax());
							}
						} else {
							$row = $srtf_query->row_array();

							$mixed_insert_values[$mixed_table_fields[3]] = $row['id'];
						}
					}

					/* If there's anything to be inserted, do it */
					if (isset($mixed_insert_values)) {
						/* Security Permissions Check */
						if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_read, $mixed_table)) {
							$this->db->trans_rollback();
							$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->config['default_charset'], !$this->request->is_ajax());
						}

						$mixed_insert_values[$this->config['name'] . '_id'] = $last_id;
						$this->db->insert('mixed_' . $this->config['name'] . '_' . $mixed_table, $mixed_insert_values);
						unset($mixed_insert_values);
					}
				}
			}
		}
	}
}
