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

class Notifications extends ND_Controller {
	/* Constructor */
	public function __construct($session_enable = true, $json_replies = false) {
		parent::__construct($session_enable, $json_replies);

		$this->_viewhname = get_class();
		$this->_name = strtolower($this->_viewhname);

		/* Include any setup procedures from ide builder. */
		include('lib/ide_setup.php');

		/* Populate controller configuration */
		$this->config_populate();

		/* Call construct hook */
		$this->_hook_construct();
	}
	

	/** Hooks **/
	protected function _hook_view_generic_enter(&$data, &$id, &$export) {
		/* Mark the notification as seen */
		$this->db->where('id', $id);
		$this->db->where('when <', date('Y-m-d H:i:s'));
		$this->db->update($this->_name, array(
			'seen' => true
		));

		return NULL;
	}


	/** Other overloads **/

	protected $_hide_fields_list = array('description', 'url', 'all');
	protected $_hide_fields_result = array('description', 'url', 'all');

	/* Field by which the listing views shall be ordered by */
	protected $_field_listing_order = 'seen';

	/* Field by which the result views shall be ordered by */
	protected $_field_result_order = 'seen';

	/* Direction by which the listing views shall be ordered by */
	protected $_direction_listing_order = 'asc';

	/* Direction by which the result views shall be ordered by */
	protected $_direction_result_order = 'asc';

	/* Aliases for the current table field names */
	protected $_table_field_aliases = array(
		'notification' => NDPHP_LANG_MOD_COMMON_NOTIFICATION,
		'description' => NDPHP_LANG_MOD_COMMON_DESCRIPTION,
		'url' => NDPHP_LANG_MOD_COMMON_URL,
		'seen' => NDPHP_LANG_MOD_COMMON_SEEN,
		'all' => NDPHP_LANG_MOD_COMMON_NOTIFY_ALL,
		'when' => NDPHP_LANG_MOD_COMMON_NOTIFY_WHEN
	);

	protected $_rel_table_fields_config = array(
		'users' => array(NDPHP_LANG_MOD_COMMON_USERNAME, NULL, array(1), array('id', 'asc'))
	);


	/** Custom functions **/
	public function get_count() {
		$this->db->select('COUNT(id) AS total', false);
		$this->db->from($this->_name);
		$this->db->where('users_id', $this->session->userdata('user_id'));
		$this->db->where('seen', false);
		$this->db->where('when <=', date('Y-m-d H:i:s'));
		$q = $this->db->get();
		$row = $q->row_array();
		echo($row['total']);
	}
}