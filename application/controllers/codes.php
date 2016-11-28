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

class Codes extends ND_Controller {
	/* Constructor */
	public function __construct($session_enable = true, $json_replies = false) {
		parent::__construct($session_enable, $json_replies);

		/* Initialize controller */
		$this->_init(get_class(), true);
	}
	
	/** Hooks **/
	
	/** Other overloads **/

	/* Aliases for the current table field names */
	protected $_table_field_aliases = array(
		'code' => NDPHP_LANG_MOD_COMMON_CODE,
		'remaining' => NDPHP_LANG_MOD_COMMON_REMAINING,
		'valid_from' => NDPHP_LANG_MOD_COMMON_VALID_FROM,
		'valid_to' => NDPHP_LANG_MOD_COMMON_VALID_TO
	);

	protected $_rel_table_fields_config = array(
		'codes_types' => array(NDPHP_LANG_MOD_COMMON_CODE_TYPE, NULL, array(1), array('id', 'asc'), NULL),
		'roles' => array(NDPHP_LANG_MOD_COMMON_ROLE, NULL, array(1), array('id', 'asc'), NULL)
	);


	/** Custom functions **/

}
