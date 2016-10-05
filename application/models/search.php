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

/*
 *
 * The acronym 'ndsl' stands for ND Search Language (a DSL for searching ND PHP Framekwork data records)
 *
 */

class UW_Search extends UW_Model {
	private $_result_error = 'None';

	private function _get_type($value) {
		if (is_array($value)) {
			return "array";
		} else if ($value[0] == '+' || $value[0] == '-') {
			return "interval";
		} else if (strptime($value, '%Y-%m-%d %H:%M:%S') !== false) {
			return "datetime";
		} else if (strptime($value, '%Y-%m-%d') !== false) {
			return "date";
		} else if (strptime($value, '%H:%M:%S') !== false) {
			return "time";
		} else if (gettype($value) == "integer" || gettype($value) == "double") {
			return "numeric";
		} else {
			return "string";
		}

		return NULL;
	}

	private function _set_result_error($error) {
		$this->_result_error = $error;
	}

	public function get_result_error() {
		return $this->_result_error;
	}

	public function is_ndsl($data) {
		if (rtrim($data, ' ')[0] == '{' && json_decode($data) !== NULL)
			return true;

		return false;
	}

	public function ndsl_to_advsearch($query) {
		$ndsl = json_decode($query, true);
		$nadv = array();

		$nadv['fields_criteria'] = array();
		$nadv['fields_result'] = isset($ndsl['_show']) ? $ndsl['_show'] : array();

		foreach ($ndsl as $field => $criteria) {
			array_push($nadv['fields_criteria'], $field);

			foreach ($criteria as $cond => $value) {
				switch ($this->_value_type($value)) {
					case "string": {
						if ($cond != "contains")
					} break;
				}
				/** Pre-process values **/

				if (!isset($ast[$field]['value']))
					$ast[$field]['value'] = $value;


				$ast[$field]['type'] = $this->_value_type($value);
				$ast[$field]['condition'] = $cond;
			}
		}

		return $ast;
	}

				if (is_array($value)) {

				} else {
					
					if ($value[0] == '+' || $value[0] == '-') {
						/* Custom field values must start with a '+' or a '-' */
						$ndadv[$field . '_custom'] = $value;
					} else if (strptime($value, '%Y-%m-%d %H:%M:%S') !== false) {
						/* Datetime fields must have two components (a date and a time) separated by space ' ' */
						$datetime = explode(' ', $value);
						$ndadv[$field . '_from'] = $datetime[0];
						$ndadv[$field . '_from_time'] = $datetime[1];
					} else if ((strptime($value, '%Y-%m-%d') !== false) || (strptime($value, '%H:%M:%S') !== false)) {
						/* This is either a date or a time value... */
						$ndadv[$field . '_from'] = $value;
					} else if (gettype($value) == "integer" || gettype($value) == "double") {
						/* Integers and doubles */
						$ndadv[$field . '_from'] = $value;
					} else {
						/* TODO: FIXME: Set error type / string before returning false, so it can be retrieved by the upper layers */
						return false;
					}
				}

				/* Process conditions */
				switch ($cond) {
					/* Basic conditions */
					case 'lt': $ndadv[$field] = $value; $ndadv[$field . '_cond'] = '<'; break;
					case "eq": $ndadv[$field] = $value; $ndadv[$field . '_cond'] = '='; break;
					case "ne": $ndadv[$field] = $value; $ndadv[$field . '_cond'] = '!='; break;
					case "gt": $ndadv[$field] = $value; $nvadv[$field . '_cond'] = '>'; break;

					/* Text conditions */
					case "contains": $ndadv[$field] = $value; break;
					case "exact": $ndadv[$field . '_exact'] = '1'; break;
					case "diff": $ndadv[$field . '_diff'] = '1'; break;

					/* Relational field conditions */
					case "in": $ndadv[$field] = $value; break; /* TODO: Check if $value is array */
					case "is": $ndadv[$field] = $value; break; /* TODO: Check if $value is NOT array */

					case "between"
				}
			}
		}
	}
}
