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
 * The acronym 'ndsl' stands for ND Search Language (a DSL for searching ND PHP Framekwork data records).
 *
 *
 * TODO:
 *
 *  * Missing language support for result errors.
 *  * Missing support for checkbox (booleans).
 *
 */

class UW_Search extends UW_Model {
	private $_result_error = 'None';
	private $_context = NULL;

	private function _get_type($value) {
		if (is_array($value)) {
			return "array";
		} else if ($value[0] == '+' || $value[0] == '-') {
			return "relative";
		} else if (strptime($value, '%Y-%m-%d %H:%M:%S') !== false) {
			return "datetime";
		} else if (strptime($value, '%Y-%m-%d') !== false) {
			return "date";
		} else if (strptime($value, '%H:%M:%S') !== false) {
			return "time";
		} else if (gettype($value) == "integer") {
			return "integer";
		} else if (gettype($value) == "double") {
			return "double";
		} else if (gettype($value) == "boolean") {
			return "boolean";
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
		/* Decode the NDSL query */
		$ndsl = json_decode($query, true);

		/* Initialize the advanced search context array */
		$nadv = array();

		/* Set the result fields */
		if (isset($ndsl['_show'])) {
			foreach ($ndsl['_show'] as $result_field)
				$nadv['__result_' . $result_field] = true;
		}

		/* Check if the 'id' field is part of the result list. If not, set it, as it is mandatory for any result */
		if (!isset($nadv['__result_id']))
			$nadv['__result_id'] = true;

		/* Unset any unrequired values from now on */
		unset($ndsl['_show']); /* NOTE: This MUST not be set in $nadv... otherwise it will conflict with any '_show' modifier that might be set with a REST RESULT call */

		/* Start processing fields and criteria */
		foreach ($ndsl as $field => $criteria) {
			$this->_context = NULL; /* Reset context */

			/* Set the criteria fields */
			$nadv['__criteria_' . $field] = true;

			/* Process criteria */
			foreach ($criteria as $cond => $value) {
				/* Evaluate conditions */
				switch ($cond) {
					case 'contains': {
						/* 'contains' condition expects no previous context */
						if ($this->_context !== NULL) {
							$this->_set_result_error("Unexpected context '" . $this->_context . "' found on field '" . $field . "' under condition '" . $cond . "'.");
							return false;
						}

						/* 'contains' condition accepts only strings */
						if ($this->_get_type($value) != 'string') {
							$this->_set_result_error("Unexpected type '" . $this->_get_type($value) . "' on condition '" . $cond . "' for field '" . $field . "'. Expecting: String.");
							return false;
						}

						/* Set the criteria value */
						$nadv[$field] = $value;

						/* Set the current context */
						$this->_context = $cond;
					} break;

					case 'exact': {
						/* 'exact' condition expects the context to be 'contains' */
						if ($this->_context != 'contains') {
							$this->_set_result_error("Unexpected condition '" . $cond . "' on field '" . $field . "'.");
							return false;
						}

						$nadv[$field . '_exact'] = $value;
					} break;

					case 'diff': {
						/* 'diff' condition expects the context to be 'contains' */
						if ($this->_context != 'contains') {
							$this->_set_result_error("Unexpected condition '" . $cond . "' on field '" . $field . "'.");
							return false;
						}

						$nadv[$field . '_diff'] = $value;
					} break;

					/* TODO: Can't decide if this approach is ugly or not... for now it'll be kept as is */
					case 'to':
					case 'lt': if (!isset($nadv[$field . '_cond'])) $nadv[$field . '_cond'] = '<';
					case 'eq': if (!isset($nadv[$field . '_cond'])) $nadv[$field . '_cond'] = '=';
					case 'ne': if (!isset($nadv[$field . '_cond'])) $nadv[$field . '_cond'] = '!=';
					case 'from':
					case 'gt': if (!isset($nadv[$field . '_cond'])) $nadv[$field . '_cond'] = '>';
					{
						$suffix = ''; /* will only be set if this is a between condition (a 'from' and 'to' criteria must be set withint the same field) */

						if ($cond == 'to') {
							/* If we're under a 'from' context, this is a between condition... */
							if ($this->_context == 'from') {
								$nadv[$field . '_cond'] = '><'; /* between */
								$suffix = '_to'; /* Set the suffix to '_to', enabling the second component of the search interval criteria */
							} else if ($this->_context !== NULL) {
								$this->_set_result_error("Unexpected context '" . $this->_context . "' found on field '" . $field . "' under condition '" . $cond . "'.");
								return false;
							}
						} else {
							/* 'lt', 'eq', 'ne' and 'gt' conditions expects no previous context */
							if ($this->_context !== NULL) {
								$this->_set_result_error("Unexpected context '" . $this->_context . "' found on field '" . $field . "' under condition '" . $cond . "'.");
								return false;
							}
						}

						/* Get value type */
						$vtype = $this->_get_type($value);

						/* 'gt' condition does not accept strings nor arrays */
						if ($vtype == 'string' || $vtype == 'array' || $vtype == 'boolean') {
							$this->_set_result_error("Unexpected type '" . $vtype . "' on condition '" . $cond . "' for field '" . $field . "'. Expecting: Integer, Double, Relative, Datetime, Date or Time.");
							return false;
						}

						/* datetime type needs special treatment ... */
						if ($vtype == 'datetime') {
							$datetime = explode(' ', $value); /* split segments */

							$nadv[$field . $suffix] = $datetime[0]; /* date segment */
							$nadv[$field . $suffix . '_time'] = $datetime[1]; /* time segment */
						} else if ($vtype == 'relative') {
							$nadv[$field . $suffix . '_custom'] = $value;
						} else {
							$nadv[$field . $suffix] = $value;
						}

						/* Set current context */
						$this->_context = $cond;
					} break;

					case 'in': {
						/* 'in' condition expects no previous context */
						if ($this->_context !== NULL) {
							$this->_set_result_error("Unexpected context '" . $this->_context . "' found on field '" . $field . "' under condition '" . $cond . "'.");
							return false;
						}

						/* 'in' condition only accepts arrays */
						if ($this->_get_type($value) != 'array') {
							$this->_set_result_error("Unexpected type '" . $this->_get_type($value) . "' on condition '" . $cond . "' for field '" . $field . "'. Expecting: Array.");
							return false;
						}

						/* Set the criteria value */
						$nadv[$field] = $value;

						/* Set current context */
						$this->_context = $cond;
					} break;

					case 'is': {
						/* 'is' condition expects no previous context */
						if ($this->_context !== NULL) {
							$this->_set_result_error("Unexpected context '" . $this->_context . "' found on field '" . $field . "' under condition '" . $cond . "'.");
							return false;
						}

						/* 'is' condition only accepts numeric */
						if ($this->_get_type($value) != 'boolean') {
							$this->_set_result_error("Unexpected type '" . $this->_get_type($value) . "' on condition '" . $cond . "' for field '" . $field . "'. Expecting: Boolean.");
							return false;
						}

						/* Set the criteria value */
						$nadv[$field] = ($value === true) ? '1' : '0';

						/* Set current context */
						$this->_context = $cond;
					} break;
				}
			}
		}

		/* Return the advanced search translation */
		return $nadv;
	}
}
