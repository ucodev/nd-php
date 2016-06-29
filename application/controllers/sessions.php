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

class Sessions extends ND_Controller {
	/* Constructor */
	public function __construct($session_enable = true, $json_replies = false) {
		parent::__construct($session_enable, $json_replies);

		$this->_viewhname = get_class();
		$this->_name = strtolower($this->_viewhname);

		/* Include any setup procedures from ide builder. */
		include('lib/ide_setup.php');

		/* Grant that only ROLE_ADMIN is able to access this controller */
		if (!$this->security->im_admin())
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_ONLY_ADMIN, $this->_default_charset, !$this->request->is_ajax());

		/* Populate controller configuration */
		$this->config_populate();

		/* Call construct hook */
		$this->_hook_construct();
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
	protected $_hide_fields_list = array('ip_address', 'user_agent');
	protected $_hide_fields_result = array('ip_address', 'user_agent');
	protected $_hide_fields_search = array(); // Includes fields searched on searchbar (basic)
	protected $_hide_fields_export = array();

	/* Aliases for the current table field names */
	protected $_table_field_aliases = array(
		'session' => NDPHP_LANG_MOD_COMMON_SESSION,
		'ip_address' => NDPHP_LANG_MOD_COMMON_IP_ADDRESS,
		'user_agent' => NDPHP_LANG_MOD_COMMON_USER_AGENT,
		'start_time' => NDPHP_LANG_MOD_COMMON_START_TIME,
		'last_login' => NDPHP_LANG_MOD_COMMON_LAST_LOGIN
	);

	/** Custom functions **/

}