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

class UW_Configuration extends UW_Model {
	public function get() {
		/* Fetch the current configuration from database */
		$this->db->select('base_url,themes.theme AS theme,' .
			'project_name,tagline,configuration.description,author,' .
			'timezones.timezone AS timezone,page_rows,' .
			'temporary_directory,smtp_username,smtp_password,' .
			'smtp_server,smtp_ssl,smtp_tls,recaptcha_priv_key,' .
			'recaptcha_pub_key,roles_id,maintenance'
		);
		$this->db->from('configuration');
		$this->db->join('themes', 'themes.id = configuration.themes_id', 'left');
		$this->db->join('timezones', 'timezones.id = configuration.timezones_id', 'left');
		$this->db->where('active', 1);
		$query = $this->db->get();

		/* If there isn't any active configuration, we cannot proceed... */
		if (!$query->num_rows()) {
			header('HTTP/1.1 500 Internal Server Error');
			die('UW_Configuration::get(): ' . NDPHP_LANG_MOD_CANNOT_FIND_ACTIVE_CONFIG);
		}

		/* All good */
		return $query->row_array();
	}

	public function core_context_set($context) {
		$GLOBALS['_core_context'] = $context;
	}

	public function core_context_get() {
		return $GLOBALS['_core_context'];
	}

	public function core_set($config) {
		$GLOBALS['_core_config'][$this->core_context_get()] = $config;
	}

	public function core_get() {
		return $GLOBALS['_core_config'][$this->core_context_get()];
	}
}
