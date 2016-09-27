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

class Configuration extends ND_Controller {
	/* Constructor */
	public function __construct($session_enable = true, $json_replies = false) {
		parent::__construct($session_enable, $json_replies);

		/* Initialize controller */
		$this->_init(get_class(), true);

		/* Grant that only ROLE_ADMIN is able to access this controller */
		if (!$this->security->im_admin())
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_ONLY_ADMIN, $this->config['default_charset'], !$this->request->is_ajax());
	}


	/** Hooks **/
	protected function _hook_insert_post(&$id, &$POST, &$fields, $hook_pre_return) {
		/* If this is an active configuration, perform any required system updates routines that are based on configuration */
		if ($POST['active']) {
			/* Update cache configuration */
			$this->_memcached_config_setup();

			/* Clear cache */
			if ($this->cache->is_active()) {
				$this->cache->flush();
			}
		}
	}

	protected function _hook_update_pre(&$id, &$POST, &$fields) {
		$hook_pre_return = NULL;
		
		if (isset($POST['active']) && !$POST['active']) {
			/* Check if there's another active configuration */
			$this->db->from('configuration');
			$this->db->where('active', 1);
			$this->db->where('id !=', $id);
			$q = $this->db->get();

			/* Check if there is, at least, one other active configuration.
			 * If this one is going to be inactive, we need to grant that there will be
			 * at least another active configuration after this update is performed.
			 */
			if ($q->num_rows() < 1)
				$this->response->code('403', NDPHP_LANG_MOD_INFO_CONFIG_INACTIVE, $this->config['default_charset'], !$this->request->is_ajax());
		}

		return $hook_pre_return;
	}

	protected function _hook_update_post(&$id, &$POST, &$fields, $hook_pre_return) {
		/* If this is an active configuration, perform any required system updates routines that are based on configuration */
		if ($POST['active']) {
			/* Update cache configuration */
			$this->_memcached_config_setup();

			/* Clear cache */
			if ($this->cache->is_active()) {
				$this->cache->flush();
			}
		}
	}

	protected function _hook_delete_pre(&$id, &$POST, &$fields) {
		$hook_pre_return = NULL;
		
		/* Check if we're deleting an active configuration ... */
		$this->db->from('configuration');
		$this->db->where('active', 1);
		$this->db->where('id', $id);
		$q = $this->db->get();

		if ($q->num_rows()) {
			/* If so, we cannot allow that... */
			$this->response->code('403', NDPHP_LANG_MOD_INFO_CONFIG_DELETE_ACTIVE, $this->config['default_charset'], !$this->request->is_ajax());
		}

		return $hook_pre_return;
	}

	protected function _hook_view_generic_leave(&$data, &$id, &$export, $hook_enter_return) {
		/* Unset fields based on disabled features */
		$this->_feature_filter_data_fields($data);
	}

	protected function _hook_remove_generic_leave(&$data, &$id, $hook_enter_return) {
		/* Unset fields based on disabled features */
		$this->_feature_filter_data_fields($data);
	}

	protected function _hook_edit_generic_leave(&$data, &$id, $hook_enter_return) {
		/* Unset fields based on disabled features */
		$this->_feature_filter_data_fields($data);
	}

	protected function _hook_create_generic_leave(&$data, $hook_enter_return) {
		/* Unset fields based on disabled features */
		$this->_feature_filter_data_fields($data);
	}

	protected function _hook_export_leave(&$data, &$export_query, &$type, $hook_enter_return) {
		/* Unset fields based on disabled features */
		$this->_feature_filter_data_fields($data);
	}

	protected function _hook_list_generic_leave(&$data, &$field, &$order, &$page, $hook_enter_return) {
		/* Unset fields based on disabled features */
		$this->_feature_filter_data_fields($data);
	}

	protected function _hook_result_generic_leave(&$data, &$type, &$result_query, &$order_field, &$order_type, &$page, $hook_enter_return) {
		/* Unset fields based on disabled features */
		$this->_feature_filter_data_fields($data);
	}

	protected function _hook_search_generic_leave(&$data, &$advanced, $hook_enter_return) {
		/* Unset fields based on disabled features */
		$this->_feature_filter_data_fields($data);
	}


	/** Other overloads **/

	protected $_upload_file_encryption = false;

	protected $_links_submenu_body_list = array(
		/* array('Description', $sec_perm, method, 'image/path/img.png', 'ajax' / 'export' / 'method' / 'modal' / 'raw', with id?, access key) */
		array(NDPHP_LANG_MOD_OP_CREATE,			'C', 'create',		NULL, 'ajax',   false,	NDPHP_LANG_MOD_OP_ACCESS_KEY_CREATE	),
		array(NDPHP_LANG_MOD_OP_GROUPS,			'R', 'groups',		NULL, 'ajax',	false,	NDPHP_LANG_MOD_OP_ACCESS_KEY_GROUPS	),
		array(NDPHP_LANG_MOD_OP_SEARCH,			'R', 'search',		NULL, 'ajax',   false,	NDPHP_LANG_MOD_OP_ACCESS_KEY_SEARCH	),
		array(NDPHP_LANG_MOD_OP_EXPORT_PDF,		'R', 'pdf',			NULL, 'export', false,	NULL 								),
		array(NDPHP_LANG_MOD_OP_EXPORT_CSV,		'R', 'csv',			NULL, 'export', false,	NULL 								),
		array(NDPHP_LANG_MOD_OP_CHARTS,			'R', 'charts',		NULL, 'ajax',	false,	NULL 								),
		array(NDPHP_LANG_MOD_OP_SCHEDULER,		'R', 'scheduler',	NULL, 'ajax',	false,	NULL 								),
		array(NDPHP_LANG_MOD_OP_BACKUP,			'R', 'backup',		NULL, 'method', false,	NULL 								),
		array(NDPHP_LANG_MOD_OP_CACHE_CLEAR,	'R', 'cache_clear',	NULL, 'method', false,	NULL 								),
		array(NDPHP_LANG_MOD_OP_UPDATE,			'R', 'system_update',NULL, 'method', false,	NULL 								)
	);

	protected $_links_submenu_body_result = array(
		/* array('Description', $sec_perm, method, 'image/path/img.png', 'ajax' / 'export' / 'method' / 'modal' / 'raw', with id?, access key) */
		array(NDPHP_LANG_MOD_OP_CREATE,			'C', 'create',		NULL, 'ajax',   false,	NDPHP_LANG_MOD_OP_ACCESS_KEY_CREATE	),
		array(NDPHP_LANG_MOD_OP_LIST,			'R', 'list',		NULL, 'ajax',   false,	NDPHP_LANG_MOD_OP_ACCESS_KEY_LIST	),
		array(NDPHP_LANG_MOD_OP_GROUPS,			'R', 'groups',		NULL, 'ajax',	false,	NDPHP_LANG_MOD_OP_ACCESS_KEY_GROUPS	),
		array(NDPHP_LANG_MOD_OP_SEARCH,			'R', 'search',		NULL, 'ajax',   false,	NDPHP_LANG_MOD_OP_ACCESS_KEY_SEARCH	),
		array(NDPHP_LANG_MOD_OP_EXPORT_PDF,		'R', 'pdf',			NULL, 'export', false,	NULL 								),
		array(NDPHP_LANG_MOD_OP_EXPORT_CSV,		'R', 'csv',			NULL, 'export', false,	NULL 								),
		array(NDPHP_LANG_MOD_OP_SAVE_SEARCH,	'R', 'search_save',	NULL, 'modal',	false,	NULL 								),
		array(NDPHP_LANG_MOD_OP_CHARTS,			'R', 'charts',		NULL, 'ajax',	false,	NULL 								),
		array(NDPHP_LANG_MOD_OP_SCHEDULER,		'R', 'scheduler',	NULL, 'ajax',	false,	NULL 								),
		array(NDPHP_LANG_MOD_OP_BACKUP,			'R', 'backup',		NULL, 'method', false,	NULL 								),
		array(NDPHP_LANG_MOD_OP_CACHE_CLEAR,	'R', 'cache_clear',	NULL, 'method', false,	NULL 								),
		array(NDPHP_LANG_MOD_OP_UPDATE,			'R', 'system_update',NULL, 'method', false,	NULL 								)
	);

	/* Hidden fields per view.
	 *
	 * Note that for relationship fields, the field name used here must be the one
	 * corresponding to the foreign table field.
	 * 
	 */
	protected $_hide_fields_create = array('id');
	protected $_hide_fields_edit = array('id');
	protected $_hide_fields_view = array();
	protected $_hide_fields_remove = array();
	protected $_hide_fields_list = array('project_date', 'tagline', 'description', 'author', 'page_rows', 'temporary_directory', 'smtp_password', 'smtp_username', 'smtp_server', 'smtp_port', 'smtp_ssl', 'smtp_tls', 'roles_id', 'memcached_server', 'memcached_port', 'recaptcha_priv_key', 'recaptcha_pub_key', 'rel_configuration_features');
	protected $_hide_fields_result = array('project_date', 'tagline', 'description', 'author', 'page_rows', 'temporary_directory', 'smtp_password', 'smtp_username', 'smtp_server', 'smtp_port', 'smtp_ssl', 'smtp_tls', 'roles_id', 'memcached_server', 'memcached_port', 'recaptcha_priv_key', 'recaptcha_pub_key', 'rel_configuration_features');
	protected $_hide_fields_search = array(); // Includes fields searched on searchbar (basic)
	protected $_hide_fields_export = array();

	/* Aliases for the current table field names */
	protected $_table_field_aliases = array(
		'configuration' => NDPHP_LANG_MOD_COMMON_CONFIGURATION,
		'base_url' => NDPHP_LANG_MOD_COMMON_BASE_URL,
		'support_email' => NDPHP_LANG_MOD_COMMON_SUPPORT_EMAIL,
		'page_rows' => NDPHP_LANG_MOD_COMMON_RPP,
		'temporary_directory' => NDPHP_LANG_MOD_COMMON_TEMP_DIR,
		'maintenance' => NDPHP_LANG_MOD_COMMON_MAINTENANCE,
		'active' => NDPHP_LANG_MOD_COMMON_ACTIVE,
		'model' => NDPHP_LANG_MOD_COMMON_MODEL,
		'_separator_project' => NDPHP_LANG_MOD_SEP_CONFIGURATION_PROJECT,
		'project_name' => NDPHP_LANG_MOD_COMMON_PROJECT_NAME,
		'project_version' => NDPHP_LANG_MOD_COMMON_VERSION,
		'project_date' => NDPHP_LANG_MOD_COMMON_LAST_UPDATE,
		'tagline' => NDPHP_LANG_MOD_COMMON_TAGLINE,
		'description' => NDPHP_LANG_MOD_COMMON_DESCRIPTION,
		'author' => NDPHP_LANG_MOD_COMMON_AUTHOR,
		'_separator_smtp' => 'SMTP',
		'smtp_username' => NDPHP_LANG_MOD_COMMON_SMTP_USER,
		'smtp_password' => NDPHP_LANG_MOD_COMMON_SMTP_PASSWORD,
		'smtp_server' => NDPHP_LANG_MOD_COMMON_SMTP_HOST,
		'smtp_port' => NDPHP_LANG_MOD_COMMON_SMTP_PORT,
		'smtp_ssl' => 'SMTP SSL',
		'smtp_tls' => 'SMTP TLS',
		'_separator_memcached' => 'Memcached',
		'memcached_server' => NDPHP_LANG_MOD_COMMON_MEMCACHED_SERVER,
		'memcached_port' => NDPHP_LANG_MOD_COMMON_MEMCACHED_PORT,
		'_separator_recaptcha' => 'reCAPTCHA',
		'recaptcha_priv_key' => 'reCAPTCHA ' . NDPHP_LANG_MOD_COMMON_PRIV_KEY,
		'recaptcha_pub_key' => 'reCAPTCHA ' . NDPHP_LANG_MOD_COMMON_PUB_KEY
	);

	protected $_rel_table_fields_config = array(
		'themes' => array(NDPHP_LANG_MOD_COMMON_THEME, NULL, array(1), array('id', 'asc')),
		'timezones' => array(NDPHP_LANG_MOD_COMMON_TIMEZONE, NULL, array(1), array('id', 'asc')),
		'roles' => array(NDPHP_LANG_MOD_COMMON_REGULAR_USER_ROLE, NULL, array(1), array('id', 'asc')),
		'features' => array(NDPHP_LANG_MOD_SEP_CONFIGURATION_FEATURES, NULL, array(1), array('id', 'asc'))
	);


	/** Custom functions **/
	private function _feature_filter_data_fields(&$data) {
		/* Unset fields based on disabled features */
		if (!$data['config']['features']['system_memcached']) {
			unset($data['view']['fields']['_separator_memcached']);
			unset($data['view']['fields']['memcached_server']);
			unset($data['view']['fields']['memcached_port']);
		}

		if (!$data['config']['features']['register_with_recaptcha']) {
			unset($data['view']['fields']['_separator_recaptcha']);
			unset($data['view']['fields']['recaptcha_priv_key']);
			unset($data['view']['fields']['recaptcha_pub_key']);
		}
	}

	private function _memcached_config_setup() {
		$cache['driver'] = 'memcached';
		$cache['host'] = '127.0.0.1';
		$cache['port'] = '11211';
		$cache['key_prefix'] = 'nd_'; /* TODO: FIXME: Missing data model support for memcached key_prefix */
		$cache['active'] = false;

		$this->db->select('memcached_server,memcached_port');
		$this->db->from('configuration');
		$this->db->join('rel_configuration_features', 'configuration.id = rel_configuration_features.configuration_id', 'inner');
		$this->db->join('features', 'features.id = rel_configuration_features.features_id', 'inner');
		$this->db->where('features.feature', 'FEATURE_SYSTEM_MEMCACHED');
		$q = $this->db->get();

		if ($q->num_rows()) {
			$row = $q->row_array();
			$cache['host'] = $row['memcached_server'];
			$cache['port'] = $row['memcached_port'];
			$cache['active'] = true;
		}

		/* TODO: FIXME: Before writing the changes, grant that the settings are correct by connecting to the memcached */

		/* Craft user/config/cache.php contents */
		$cache_config =
			'<?php if (!defined(\'FROM_BASE\')) { header(\'HTTP/1.1 403 Forbidden\'); die(\'Invalid requested path.\'); }' . "\n" .
			'' . "\n" .
			'/* Cache settings */' . "\n" .
			'$cache[\'driver\'] = \'memcached\';' . "\n" .
			'$cache[\'host\'] = \'' . $cache['host'] . '\';' . "\n" .
			'$cache[\'port\'] = \'' . $cache['port'] . '\';' . "\n" .
			'$cache[\'key_prefix\'] = \'' . $cache['key_prefix'] . '\';' . "\n" .
			'$cache[\'active\'] = ' . ($cache['active'] === true ? 'true' : 'false') . ';' . "\n" .
			'' . "\n";

		/* Flush cache configuration to user/config/cache.php */
		if (file_put_contents(SYSTEM_BASE_DIR . '/user/config/cache.php', $cache_config) === false)
			$this->response->code('500', NDPHP_LANG_MOD_UNABLE_FILE_OPEN_WRITE . ': ' . SYSTEM_BASE_DIR . '/user/config/cache.php', $this->config['default_charset'], !$this->request->is_ajax());
	}

	public function maintenance_enter() {
		$this->db->where('active', true);
		$this->db->update('configuration', array(
			'maintenance' => true
		));
	}

	public function maintenance_leave() {
		$this->db->where('active', true);
		$this->db->update('configuration', array(
			'maintenance' => false
		));
	}

	public function backup($charset = 'utf8', $timezone = '+00:00') {
		/* Enter Maintenance Mode */
		$this->maintenance_enter();

		/** Backup database **/

		/* Set the sql dump filename */
		$filename_db_dump = SYSTEM_BASE_DIR . '/backups/dumps/ndphp_db_dump_' . date('Ymd_His') . '.sql';

		/* Dump the database structure and data */
		$db_dump = $this->db->dump($charset, $timezone, "\n");

		/* Create the .sql file */
		if (($fp = fopen($filename_db_dump, 'w')) === FALSE) {
			/* Leave Maintenance Mode */
			$this->maintenance_leave();
			$this->response->code('500', NDPHP_LANG_MOD_UNABLE_FILE_OPEN_WRITE . ': ' . $filename_db_dump, $this->config['default_charset'], !$this->request->is_ajax());
		}

		/* Write dump to file */
		if (fwrite($fp, $db_dump) === FALSE) {
			/* Leave Maintenance Mode */
			$this->maintenance_leave();
			$this->response->code('500', NDPHP_LANG_MOD_UNABLE_FILE_WRITE . ': ' . $filename_db_dump, $this->config['default_charset'], !$this->request->is_ajax());
		}

		/* Flush file data */
		fflush($fp);

		/* Close file */
		fclose($fp);

		/** Backup Project (including dump directory) **/

		/* Set the tar filename */
		$filename_project = SYSTEM_BASE_DIR . '/backups/archives/ndphp_backup_' . date('Ymd_His') . '.tar';

		/* Store and compress the project directory */
		try {
			/* Create the container */
			$backup = new PharData($filename_project);
			/* Store the entire project directory, but ignore backups/archive/*.tar* files */
			$backup->buildFromDirectory(SYSTEM_BASE_DIR, '/^((?!' . str_replace('/', '\/', SYSTEM_BASE_DIR) . '\/backups\/archives\/.*\.tar.*).)*$/');
			/* Compress data */
			$backup->compress(Phar::GZ); /* A new file (.tar.gz) will be created (note that .tar will still exist) */
		} catch (Exception $e) {
			/* Leave Maintenance Mode */
			$this->maintenance_leave();

			$this->response->code('500', NDPHP_LANG_MOD_UNABLE_BACKUP_PROJECT_DIR, $this->config['default_charset'], !$this->request->is_ajax());
		}

		/* Unlink unnecessary files */
		unlink($filename_project); /* Delete the .tar file */
		unlink($filename_db_dump); /* Delete the .sql file */

		/* Deliver backup as file download */
		$this->response->header('Content-Type', 'application/x-gzip');
		$this->response->header('Content-Disposition', 'attachment; filename=' . end(explode('/', $filename_project)) . '.gz');

		readfile($filename_project . '.gz');

		/* Unlink the backup */
		unlink($filename_project . '.gz'); /* Delete the .tar.gz file */

		/* Leave Maintenance Mode */
		$this->maintenance_leave();
	}

	public function cache_clear() {
		/* Clear cache */
		if ($this->cache->is_active()) {
			$this->cache->flush();
		}

		redirect('/');
	}

	public function system_update() {
		redirect('/update/system_update');
	}

	public function charts_body_ajax() {
		redirect('/charts_config');
	}

	public function scheduler_body_ajax() {
		redirect('/scheduler');
	}
}

