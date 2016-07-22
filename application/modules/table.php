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

class UW_Table extends UW_Module {
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

	public function join_rels($table, $join_type = 'left', $excludes = array()) {
		$this->load->module('get');

		/* Join all single relationship tables */
		$rel_single_list = $this->get->relative_tables($table, 'single');

		foreach ($rel_single_list as $rel_single) {
			if (in_array($rel_single, $excludes))
				continue;

			$this->db->join($rel_single, '`' . $table . '`.`' . $rel_single . '_id` = `' . $rel_single . '`.`id`', $join_type);
		}

		/* Join all multiple relationship tables */
		$rel_multiple_list = $this->get->relative_tables($table, 'multiple');

		foreach ($rel_multiple_list as $rel_multiple) {
			if (in_array($rel_multiple, $excludes))
				continue;

			$this->db->join($rel_multiple, '`' . $rel_multiple . '`.`' . $table . '_id` = `' . $table . '`.`id`', $join_type);

			$foreign_table = array_pop(array_diff($this->get->multiple_rel_table_names($rel_multiple, $table), array($table)));

			if (in_array($foreign_table, $excludes))
				continue;

			$this->db->join($foreign_table, '`' . $rel_multiple . '`.`' . $foreign_table . '_id` = `' . $foreign_table . '`.`id`', $join_type);
		}

		/* Join all mixed relationship tables */
		$rel_mixed_list = $this->get->relative_tables($table, 'mixed');

		foreach ($rel_mixed_list as $rel_mixed) {
			if (in_array($rel_mixed, $excludes))
				continue;

			$this->db->join($rel_mixed, '`' . $rel_mixed . '`.`' . $table . '_id` = `' . $table . '`.`id`', $join_type);

			$foreign_table = array_pop(array_diff($this->get->mixed_rel_table_names($rel_mixed, $table), array($table)));

			if (in_array($foreign_table, $excludes))
				continue;

			$this->db->join($foreign_table, '`' . $rel_mixed . '`.`' . $foreign_table . '_id` = `' . $foreign_table . '`.`id`', $join_type);
		}
	}
}