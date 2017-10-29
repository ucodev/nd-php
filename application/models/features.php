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

class UW_Features extends UW_Model {
	/* ND PHP Framework Feature Configuration */

	/* Features translation table (from database name to $features array key */
	private $_features_translate = array(
		'FEATURE_ACCESSIBILITY' => 'accessibility',
		'FEATURE_MULTI_USER' =>  'multi_user',
		'FEATURE_USER_CREDIT' => 'user_credit_control',
		'FEATURE_USER_REGISTRATION' => 'user_registration',
		'FEATURE_USER_RECOVERY' => 'user_recovery',
		'FEATURE_SYSTEM_MEMCACHED' => 'system_memcached'
	);

	public function get_features() {
		$features = array();

		/* Assume all features disabled by default */
		$features['accessibility'] = false;
		$features['multi_user'] = false;
		$features['user_credit_control'] = false;
		$features['user_registration'] = false;
		$features['user_recovery'] = false;
		$features['system_memcached'] = false;

		/* Fetch enabled features from database based on the currently active configuration */
		$this->db->select('features.feature AS feature');
		$this->db->from('configuration');
		$this->db->join('rel_configuration_features', 'rel_configuration_features.configuration_id = configuration.id', 'left');
		$this->db->join('features', 'features.id = rel_configuration_features.features_id', 'left');
		$this->db->where('configuration.active', true);
		$q = $this->db->get();

		/* If no features are enabled ... */
		if (!$q->num_rows())
			return $features;

		/* Enable configured features */
		foreach ($q->result_array() as $row) {
			$features[$this->_features_translate[$row['feature']]] = true;
		}

		/* All good */
		return $features;
	}
}
	
