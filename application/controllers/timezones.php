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

class Timezones extends ND_Controller {
	/* Constructor */
	public function __construct($session_enable = true, $json_replies = false) {
		parent::__construct($session_enable, $json_replies);

		/* Initialize controller */
		$this->_init(get_class(), true);
	}
	
	/** Hooks **/
	

	/** Other overloads **/

	/* JSON REST API listing / result hard limits */
	protected $_json_result_hard_limit = 600;

	/* Aliases for the current table field names */
	protected $_table_field_aliases = array(
		'timezone' => NDPHP_LANG_MOD_COMMON_TIMEZONE,
		'utc' => 'UTC',
		'utc_dst' => 'UTC dst',
		'coordinates' => NDPHP_LANG_MOD_COMMON_COORDINATES
	);

	protected $_rel_table_fields_config = array(
		'countries' => array(NDPHP_LANG_MOD_COMMON_COUNTRY, NULL, array(1), array('id', 'asc'), NULL)
	);


	/** Custom functions **/
}

