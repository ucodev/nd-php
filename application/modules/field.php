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

class UW_Field extends UW_Module {
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

	public function value_mangle($fields, $query) {
		$result_mangled = array();

		foreach ($query->result_array() as $data) {
			/* Reset row data */
			$row = array();

			foreach ($data as $field => $value) {
				if ($fields[$field]['type'] == 'datetime' && $value) {
					/* NOTE: Currently, we only need to mangle datetime fields (to support user timezone) */
					/* Convert data from database default timezone to user timezone */
					$row[$field] = $this->timezone->convert($value, $this->config['default_timezone'], $this->config['session_data']['timezone']);
				} else {
					/* Just push the value without modification */
					$row[$field] = $value;
				}
			}

			/* Push row into result */
			array_push($result_mangled, $row);
		}

		return $result_mangled;
	}

	public function resolve($fields, $selected = array(), $criteria_req = array()) {
		$this->load->module('get');

		$select = array();

		/* Check for single relationships */
		foreach ($fields as $field => $meta) {
			if ($meta['type'] == 'mixed')
				continue;

			/* Check if we already have a set of previously selected fields */
			if ($selected) {
				/* If we already have a set of selected fields, do not resolve fields that
				 * do not belong to this set and are not required to be resolved (not in $criteria_req).
				 */
				if (!in_array($field, $selected) && !in_array($field, $criteria_req))
					continue;
			}

			/* If field suffix is '_id', join to the related table (prefix name) and
			 * select its second field by default, unless a rel_table_fields_config entry
			 * is set for this particular table. In this last case, we need to determine
			 * which field(s) is(are) to be selected.
			 * 
			 * If the field is of type 'rel', then process it as a multiple relationship.
			 * 
			 * If none of the above, process it as a normal field.
			 * 
			 */
			if (substr($field, -3, 3) == '_id') {
				$table = substr($field, 0, -3);
				$table_fields = $this->get->table_fields($table);
				$this->db->join($table, $table . '.id = ' . $this->config['name'] . '.' . $field, 'left');

				/* Concatenate configured fields on _rel_table_fields_config in the results
				 * for this table, if more than one field was configured.
				 */
				$cc_fields = '';
				if (isset($this->config['rel_table_fields_config'][$table]) &&
						($this->config['rel_table_fields_config'][$table][2] != NULL) &&
						(count($this->config['rel_table_fields_config'][$table][2]) > 1)) {
					/* Initialize concat field separator */
					$cc_fields = 'CONCAT_WS(\'' . $this->config['rel_table_fields_config'][$table][1] . '\',';
					foreach ($this->config['rel_table_fields_config'][$table][2] as $cc_field) {
						$cc_fields .= '`' . $table . '`.' . '`' . $table_fields[$cc_field] . '`,';
					}

					$cc_fields = trim($cc_fields, ',') . ')';
				} else {
					/* No specific configuration was set for this table through
					 * _rel_table_fields_config, or only one field was registered in the
					 * fields array.
					 */
					$rel_field = isset($this->config['rel_table_fields_config'][$table][2][0]) ? $this->config['rel_table_fields_config'][$table][2][0] : 1;
					$cc_fields = '`' . $table . '`.`' . $table_fields[$rel_field] . '`'; 
				}

				/* Only push this field into the $select set if it is marked for selection (empty $selected, or present in $selected)...
				 * Fields beloging only to $criteria_req are only required to be resolved, not selected.
				 */
				if ((!count($selected) && !in_array($field, $criteria_req)) || in_array($field, $selected))
					array_push($select, $cc_fields . ' AS `' . $field . '`');
			} else if ($meta['type'] == 'rel') {
				/* If this is a multiple relationship field */
				$table_fields = $this->get->table_fields($meta['table']);

				$this->db->join($meta['rel_table'], $this->config['name'] . '.id = ' . $meta['rel_table'] . '.' . $this->config['name'] . '_id', 'left');
				$this->db->join($meta['table'], $meta['table'] . '.id = ' . $meta['rel_table'] . '.' . $meta['table'] . '_id', 'left');

				/* Concatenate configured fields on _rel_table_fields_config in the results
				 * for this table, if more than one field was configured.
				 */
				$cc_fields = '';
				if (isset($this->config['rel_table_fields_config'][$meta['table']]) &&
						($this->config['rel_table_fields_config'][$meta['table']][2] != NULL) &&
						(count($this->config['rel_table_fields_config'][$meta['table']][2]) > 1)) {
					/* Initialize concat field separator */
					$cc_fields = 'CONCAT_WS(\'' . $this->config['rel_table_fields_config'][$meta['table']][1] . '\',';
					foreach ($this->config['rel_table_fields_config'][$meta['table']][2] as $cc_field) {
						$cc_fields .= '`' . $meta['table'] . '`.' . '`' . $table_fields[$cc_field] . '`,';
					}
					
					$cc_fields = trim($cc_fields, ',') . ')';
				} else {
					/* No specific configuration was set for this table through
					 * _rel_table_fields_config, or only one field was registered in the
					 * fields array.
					 */
					$cc_fields = '`' . $meta['table'] . '`.`' . $meta['rel_field'] . '`';
				}

				/* Only push this field into the $select set if it is marked for selection (empty $selected, or present in $selected)...
				 * Fields beloging only to $criteria_req are only required to be resolved, not selected.
				 */
				if ((!count($selected) && !in_array($field, $criteria_req)) || in_array($field, $selected))
					array_push($select, 'GROUP_CONCAT(DISTINCT ' . $cc_fields . ' SEPARATOR \'' . $this->config['rel_group_concat_sep'] . '\') AS `' . $field . '`');
			} else {
				/* Otherwise, just select the current table field */

				/* Only push this field into the $select set if it is marked for selection (empty $selected, or present in $selected)...
				 * Fields beloging only to $criteria_req are only required to be resolved, not selected.
				 */
				if ((!count($selected) && !in_array($field, $criteria_req)) || in_array($field, $selected))
					array_push($select, '`' . $this->config['name'] . '`.`' . $field . '`');
			}
		}
		
		/* Build select statement */
		$select_str = '';
		foreach ($select as $field)
			$select_str = $select_str . ',' . $field;

		$select_str = ltrim($select_str, ',');
		
		$this->db->group_by($this->config['name'] . '.' . 'id');
		
		/* NOTE: We cannot use enforce here due to SELECT functions used (GROUP_CONCAT() and CONCAT_WS()).
		 * We need to grant that everything is checked before passing it to select() to avoid SQLi
		 */
		$this->db->select($select_str, false);
	}

	public function unambig($field, $types) {
		if (isset($types[$field]['table'])) {
			return '`' . $types[$field]['table'] . '`.`' . $field . '`';
		} else {
			return '`' . $this->config['name'] . '`.`' . $field . '`';
		}
	}

}