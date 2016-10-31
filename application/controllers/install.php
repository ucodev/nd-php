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
 * TODO: FIXME: A lot of security checks are still missing in this installation
 * process in order to filter user (input) data. Although this is an installation
 * which usually is performed under a controlled environment, enforcing security
 * is always welcome.
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

class Install extends UW_Controller {
	protected $_default_charset = NDPHP_LANG_MOD_DEFAULT_CHARSET;
	protected $_default_timezone = NDPHP_LANG_MOD_DEFAULT_TIMEZONE;

	/* ND PHP Framework - update settings */
	private $_ndphp_url = 'http://www.nd-php.org';

	/* Validation routines config */
	private $_retries_max = 15;
	private $_sleep_secs = 1;
	private $_enc_key_len = 32; /* Key size in bytes */

	/* Location of the ND PHP Database Dump file */
	private $_db_dump_file = 'install/ndphp.sql';

	/* Installation control file */
	private $_inst_ctl_file = 'done.ctl';

	/* Required PHP extensions */
	private $_extensions_required = array(
		'curl',
		'gd',
		'hash',
		'json',
		'mcrypt',
		'memcached',
		'openssl',
		'pcre',
		'PDO',
		'pdo_mysql',
		'phar',
		'session',
		'zlib'
	);

	/* Required writable directories */
	private $_dir_writable_required = array(
		'application/controllers',
		'application/controllers/lib',
		'application/static/images/menu',
		'backups/archives',
		'backups/dumps',
		'install',
		'install/updates',
		'uploads',
		'user/config'
	);

	/* Required writable files */
	private $_file_writable_required = array(
		'application/controllers/lib/ide_setup.php',
		'user/config/autoload.php',
		'user/config/base.php',
		'user/config/database.php',
		'user/config/encrypt.php',
		'user/config/session.php'
	);

	private function _check_php_extensions() {
		$extensions_missing = array();

		foreach ($this->_extensions_required as $ext) {
			if (!extension_loaded($ext))
				array_push($extensions_missing, $ext);
		}

		return $extensions_missing;
	}

	private function _check_dir_writable() {
		$non_writable = array();

		foreach ($this->_dir_writable_required as $dir) {
			$fp = fopen(SYSTEM_BASE_DIR . '/' . $dir . '/test.write', 'w');

			if ($fp === false) {
				array_push($non_writable, SYSTEM_BASE_DIR . '/' . $dir);
				continue;
			}

			fflush($fp);
			fclose($fp);

			unlink(SYSTEM_BASE_DIR . '/' . $dir . '/test.write');
		}

		return $non_writable;
	}

	private function _check_file_writable() {
		$non_writable = array();

		foreach ($this->_file_writable_required as $file) {
			$fp = fopen(SYSTEM_BASE_DIR . '/' . $file, 'a+');

			if ($fp === false) {
				array_push($non_writable, SYSTEM_BASE_DIR . '/' . $file);
				continue;
			}

			fflush($fp);
			fclose($fp);
		}

		return $non_writable;
	}

	private function _db_import_dump($dump_file) {
		$query = '';

		/* Disable prepared statements */
		$this->db->stmt_disable();

		/* Init transactional import */
		$this->db->trans_begin();

		/* Read all lines from $dump_file, ignoring new lines and skiping empty lines */
		foreach (file($dump_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $query_partial) {
			/* Ignore comments to save time */
			if (substr($query_partial, 0, 2) == '--')
				continue;

			/* Add partial query data to $query string... Dumps usually contain multi-line queries... */
			$query .= ' ' . $query_partial;

			/* If the last character of the $query is ;, we've a full query ready to be executed... just strip the trailing ; */
			if (substr($query, -1) == ';') {
				$this->db->query(rtrim($query, ';'));
				$query = ''; /* Reset the $query string */
			}
		}

		/* Check transaction status */
		if ($this->db->trans_status() === false) {
			/* Something went wrong.. rollback and bail out */
			$this->db->trans_rollback();
			return false;
		}

		/* Commit transaction */
		$this->db->trans_commit();

		/* Re-enable prepared statements */
		$this->db->stmt_enable();

		/* All good */
		return true;
	}

	public function __construct() {
		parent::__construct();

		/* Grant that PHP version is suitable */
		$php_version = explode('.', phpversion());

		if (intval($php_version[0]) < 5 || (intval($php_version[0]) == 5 && intval($php_version[1]) < 6))
			$this->response->code('501', NDPHP_LANG_MOD_ATTN_PHP_VERSION, $this->_default_charset, !$this->request->is_ajax());

		/* Check if the framework is already installed */
		if (($fp = @fopen(SYSTEM_BASE_DIR . '/install/' . $this->_inst_ctl_file, 'r')) !== false) {
			@fclose($fp);
			$this->response->code('403', NDPHP_LANG_MOD_INFO_INSTALL_ALREADY_DONE, $this->_default_charset, !$this->request->is_ajax());
		}
	}

	public function pre_check() {
		/* Disable caching */
		$this->ndphp->no_cache();

		$data['charset'] = $this->_default_charset;
		$data['errors'] = false;

		/* Check PHP extensions */
		$ext_miss = $this->_check_php_extensions();
		if (count($ext_miss)) {
			$data['extensions'] = NDPHP_LANG_MOD_INSTALL_MISSING_EXTENSIONS . ': ' . rtrim(implode(', ', $ext_miss), ', ');
			$data['errors'] = true;
		} else {
			$data['extensions'] = 'OK';
		}

		/* Check directory permissions */
		$dir_non_writable = $this->_check_dir_writable();
		if (count($dir_non_writable)) {
			$data['dir_perms'] = NDPHP_LANG_MOD_INSTALL_WRITE_NO_PRIV . ': ' . rtrim(implode(', ', $dir_non_writable), ', ');
			$data['errors'] = true;
		} else {
			$data['dir_perms'] = 'OK';
		}

		/* Check file permissions */
		$file_non_writable = $this->_check_file_writable();
		if (count($file_non_writable)) {
			$data['file_perms'] = NDPHP_LANG_MOD_INSTALL_WRITE_NO_PRIV . ': ' . rtrim(implode(', ', $file_non_writable), ', ');
			$data['errors'] = true;
		} else {
			$data['file_perms'] = 'OK';
		}

		$this->load->view('install/pre_check', $data);
	}


	/**************************************/
	/* Database testing and configuration */
	/**************************************/

	public function db_test($dbhost, $dbport, $dbname, $dbuser, $dbpass, $test_privs = false) {
		/* Disable caching */
		$this->ndphp->no_cache();

		/* Test a database connection... all the variables are received in a safe (see Documentation) base64 encoded format */
		if (!$this->db->test($this->ndphp->safe_b64decode(rawurldecode($dbhost)), $this->ndphp->safe_b64decode(rawurldecode($dbname)), $this->ndphp->safe_b64decode(rawurldecode($dbuser)), $this->ndphp->safe_b64decode(rawurldecode($dbpass)), $this->ndphp->safe_b64decode(rawurldecode($dbport))))
			$this->response->code('403', NDPHP_LANG_MOD_INSTALL_UNABLE_DB_CONNECT, $this->_default_charset, !$this->request->is_ajax());

		/* If $test_privs is set to '1', we need test all privileges on the database */
		if ($test_privs == '1') {
			$this->db->trans_begin();

			$this->db->table_create('_test_ndphp_install', 'id', 'int(11)');	/* Test CREATE */
			$this->db->insert('_test_ndphp_install', array('id' => 1));			/* Test INSERT */
			$this->db->where('id', 1);
			$this->db->update('_test_ndphp_install', array('id' => 2));			/* Test UPDATE */
			$this->db->delete('_test_ndphp_install', array('id' => 2));			/* Test DELETE */
			$this->db->get('_test_ndphp_install');								/* Test SELECT */
			$this->db->table_column_unique_add('_test_ndphp_install', 'id');	/* Test ALTER  */
			$this->db->table_drop('_test_ndphp_install');						/* Test DROP   */

			if ($this->db->trans_status() === false) {
				$this->db->trans_rollback();
				$this->response->code('403', NDPHP_LANG_MOD_INSTALL_UNABLE_DB_OPERATION . ' (CREATE / INSERT / UPDATE / DELETE / SELECT / ALTER / DROP): "' . $this->ndphp->safe_b64decode($dbname) . '"', $this->_default_charset, !$this->request->is_ajax());
			}

			$this->db->trans_commit();
		}

		$this->response->output("OK");
	}

	public function db_config() {
		/* Disable caching */
		$this->ndphp->no_cache();

		$data['charset'] = $this->_default_charset;

		$this->load->view('install/db_config', $data);
	}

	public function db_config_apply($dbhost, $dbport, $dbname, $dbuser, $dbpass) {
		/* Create the user/config/database.php configuration file */
		$fp = fopen(SYSTEM_BASE_DIR . '/user/config/database.php', 'w');

		/* Check if we can open the file for writing */
		if ($fp === false)
			$this->response->code('403', NDPHP_LANG_MOD_INSTALL_UNABLE_OPEN_WRITE . ': ' . SYSTEM_BASE_DIR . '/user/config/database.php', $this->_default_charset, !$this->request->is_ajax());

		/* Craft database configuration */
		$database_config = '' .
			"<?php if (!defined('FROM_BASE')) { header('HTTP/1.1 403 Forbidden'); die('Invalid requested path.'); }\n" .
			"\n" .
			"/* MySQL / MariaDB */\n" .
			'$database' . "['default']['driver']   = 'mysql';\n" .
			'$database' . "['default']['host']     = '" . $this->ndphp->safe_b64decode(rawurldecode($dbhost)) . "';\n" .
			'$database' . "['default']['port']     = '" . $this->ndphp->safe_b64decode(rawurldecode($dbport)) . "';\n" .
			'$database' . "['default']['name']     = '" . $this->ndphp->safe_b64decode(rawurldecode($dbname)) . "';\n" .
			'$database' . "['default']['username'] = '" . $this->ndphp->safe_b64decode(rawurldecode($dbuser)) . "';\n" .
			'$database' . "['default']['password'] = '" . $this->ndphp->safe_b64decode(rawurldecode($dbpass)) . "';\n" .
			'$database' . "['default']['charset']  = 'utf8';\n" .
			'$database' . "['default']['persistent'] = true;\n" .
			'$database' . "['default']['strict']   = true;\n" .
			"\n" .
			'$database' . "['default_schema']['driver']    = 'mysql';\n" .
			'$database' . "['default_schema']['host']      = '" . $this->ndphp->safe_b64decode(rawurldecode($dbhost)) . "';\n" .
			'$database' . "['default_schema']['port']      = '" . $this->ndphp->safe_b64decode(rawurldecode($dbport)) . "';\n" .
			'$database' . "['default_schema']['name']      = 'information_schema';\n" .
			'$database' . "['default_schema']['username']  = '" . $this->ndphp->safe_b64decode(rawurldecode($dbuser)) . "';\n" .
			'$database' . "['default_schema']['password']  = '" . $this->ndphp->safe_b64decode(rawurldecode($dbpass)) . "';\n" .
			'$database' . "['default_schema']['charset']   = 'utf8';\n" .
			'$database' . "['default_schema']['persistent'] = true;\n" .
			'$database' . "['default_schema']['strict']    = true;\n" .
			"\n";

		/* Write the configuration data to database configuration file */
		if (fwrite($fp, $database_config) === false)
			$this->response->code('403', NDPHP_LANG_MOD_INSTALL_WRITE_NO_PRIV . ': ' . SYSTEM_BASE_DIR . '/user/config/database.php', $this->_default_charset, !$this->request->is_ajax());

		/* Close the handler */
		fflush($fp);
		fclose($fp);

		/* Wait for webserver to start replying with the new configuration */
		$this->grant_url_validation(base_url() . '/index.php/install/validate_db_config', $this->_retries_max, $this->_sleep_secs);

		/* All Good */
		$this->response->output(NDPHP_LANG_MOD_INSTALL_SUCCESS_DB_CONFIG . '<br />');
	}

	public function db_setup() {
		global $config;

		/* No time limit for database setup */
		set_time_limit(0);

		/* Disable caching */
		$this->ndphp->no_cache();

		/* Import ND PHP Database base data */
		if ($this->_db_import_dump(SYSTEM_BASE_DIR . '/' . $this->_db_dump_file) !== true)
			$this->response->code('500', NDPHP_LANG_MOD_INSTALL_UNABLE_DB_IMPORT . ': ' . SYSTEM_BASE_DIR . '/' . $this->_db_dump_file, $this->_default_charset, !$this->request->is_ajax());

		/* Set an initial active configuration */
		$this->db->trans_begin();

		$this->db->insert('configuration', array(
			'configuration' => 'default',
			'base_url' => base_url(),
			'themes_id' => 1,
			'timezones_id' => 383, /* Etc/UTC */
			'author' => 'ND PHP Framework',
			'page_rows' => 10,
			'temporary_directory' => SYSTEM_BASE_DIR . '/tmp/',
			'roles_id' => 4,
			'active' => true
		));

		$config_id = $this->db->last_insert_id();

		/* Enable default features */
		$this->db->insert('rel_configuration_features', array(
			'configuration_id' => $config_id,
			'features_id' => 1 /* FEATURE_ACCESSIBILITY */
		));

		$this->db->insert('rel_configuration_features', array(
			'configuration_id' => $config_id,
			'features_id' => 2 /* FEATURE_MULTI_TENANT */
		));

		$this->db->insert('rel_configuration_features', array(
			'configuration_id' => $config_id,
			'features_id' => 5 /* FEATURE_USER_NOTIFICATIONS */
		));

		$this->db->insert('rel_configuration_features', array(
			'configuration_id' => $config_id,
			'features_id' => 6 /* FEATURE_USER_REGISTRATION */
		));

		$this->db->insert('rel_configuration_features', array(
			'configuration_id' => $config_id,
			'features_id' => 7 /* FEATURE_USER_RECOVERY */
		));

		/* Update default database entry on dbms table */
		$this->db->where('id', 1);
		$this->db->update('dbms', array(
			'alias' => 'default',
			'name' => $config['database']['default']['name'],
			'host' => $config['database']['default']['host'],
			'port' => $config['database']['default']['port'],
			'username' => $config['database']['default']['username'],
			'password' => $config['database']['default']['password'],
			'charset' => $config['database']['default']['charset'],
			'persistent' => $config['database']['default']['persistent'],
			'strict' => $config['database']['default']['strict']
		));

		/* Commit transaction */
		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			$this->response->code('500', NDPHP_LANG_MOD_INSTALL_UNABLE_DB_ENTRY, $this->_default_charset, !$this->request->is_ajax());
		}

		$this->db->trans_commit();

		/* Redirect to the next installation step */
		redirect('install/user_config');
	}

	
	/**********************************************/
	/* Initial (admin) user installation routines */
	/**********************************************/

	public function user_config() {
		/* Disable caching */
		$this->ndphp->no_cache();

		$data['charset'] = $this->_default_charset;

		$this->load->view('install/user_config', $data);
	}

	public function user_setup($password, $email) {
		/* Disable caching */
		$this->ndphp->no_cache();

		/* Generate user's private key for encryption
		 *
		 * This key will be a pseudo random string with 256 bytes of length.
		 * It'll be encrypted with the user's password.
		 * Each time the user logs in, the private key is deciphered with the plain password used for authentication
		 * and the decrypted key will be stored as a session variable.
		 *
		 */
		$privenckey = $this->encrypt->encrypt(openssl_random_pseudo_bytes(256), $password, false);

		/* Update admin user information */
		$this->db->trans_begin();

		$this->db->where('id', 1);

		$this->db->update('users', array(
			'password' => password_hash($this->ndphp->safe_b64decode(rawurldecode($password)), PASSWORD_BCRYPT, array('cost' => 10)),
			'apikey' => openssl_digest(openssl_random_pseudo_bytes(256), 'sha1'),
			'email' => $this->ndphp->safe_b64decode(rawurldecode($email)),
			'phone_confirmed' => true,
			'email_confirmed' => true,
			'active' => true,
			'locked' => false,
			'timezones_id' => 383, /* Etc/UTC */
			'countries_id' => 1, /* Portugal */
			'expire' => '2030-12-31 23:59:59',
			'registered' => date('Y-m-d H:m:i'),
			'date_confirmed' => date('Y-m-d H:m:i'),
			'allow_negative' => true,
			'subscription_types_id' => 1,
			'subscription_change_date' => date('Y-m-d H:m:i'),
			'subscription_renew_date' => '2030-12-31 23:59:59',
			'acct_last_reset' => date('Y-m-d H:i:s'),
			'acct_rest_list' => 0,
			'acct_rest_result' => 0,
			'acct_rest_view' => 0,
			'acct_rest_delete' => 0,
			'acct_rest_update' => 0,
			'acct_rest_insert' => 0,
			'privenckey' => $privenckey
		));

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			$this->response->code('500', NDPHP_LANG_MOD_INSTALL_UNABLE_UPDATE_ADMIN, $this->_default_charset, !$this->request->is_ajax());
		}

		$this->db->trans_commit();

		/* Proceeed to application details configuration */
		redirect('install/app_config');
	}

	/* Application configuration details */
	public function app_config() {
		/* Disable caching */
		$this->ndphp->no_cache();

		$data['charset'] = $this->_default_charset;

		$this->load->view('install/app_config', $data);
	}

	public function app_setup($name, $tagline, $description, $author) {
		/* Disable caching */
		$this->ndphp->no_cache();

		/* Decode data. FIXME: Filter the data to avoid security issues */
		$name = $this->ndphp->safe_b64decode(rawurldecode($name));
		$tagline = $this->ndphp->safe_b64decode(rawurldecode($tagline));
		$description = $this->ndphp->safe_b64decode(rawurldecode($description));
		$author = $this->ndphp->safe_b64decode(rawurldecode($author));

		/* Update application information */
		$this->db->trans_begin();

		$this->db->where('configuration', 'default');

		$this->db->update('configuration', array(
			'project_name' => $name,
			'project_date' => date('Y-m-d H:i:s'),
			'tagline' => $tagline,
			'description' => $description,
			'author' => $author
		));

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			$this->response->code('500', NDPHP_LANG_MOD_INSTALL_UNABLE_UPDATE_APP, $this->_default_charset, !$this->request->is_ajax());
		}

		$this->db->trans_commit();

		/* Proceed to the final configuration steps */
		redirect('install/post_install_setup');
	}


	/******************************/
	/* Post installation routines */
	/******************************/

	public function session_setup() {
		/* Create the user/config/session.php configuration file */
		$fp = fopen(SYSTEM_BASE_DIR . '/user/config/session.php', 'w');

		/* Check if we can open the file for writing */
		if ($fp === false)
			$this->response->code('403', NDPHP_LANG_MOD_INSTALL_UNABLE_OPEN_WRITE . ': ' . SYSTEM_BASE_DIR . '/user/config/session.php', $this->_default_charset, !$this->request->is_ajax());

		/* Generate a session name */
		if (!($session_name = trim(base_dir(), '/'))) {
			/* If we're unable to create a session name from the base directory, we need to generate something else... */
			/* FIXME: Maybe a static value here isn't the best approach, but will work for now... */
			$session_name = 'ndphp';
		}

		/* Craft database configuration */
		$session_config = '' .
			"<?php if (!defined('FROM_BASE')) { header('HTTP/1.1 403 Forbidden'); die('Invalid requested path.'); }\n" .
			"\n" .
			"/* Session settings */\n" .
			'$session' . "['enable']			= true;\n" .
			'$session' . "['name']				= '" . $session_name . "';\n" .
			'$session' . "['encrypt']			= true;\n" .
			'$session' . "['cookie_lifetime']	= 7200;\n" .
			'$session' . "['cookie_path']		= '" . base_dir() . "';\n" .
			'$session' . "['cookie_domain']		= '" . $_SERVER['SERVER_NAME'] . "';\n" .
			'$session' . "['cookie_secure']		= " . ((isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) != 'off')) ? 'true' : 'false') . ";\n" .
			'$session' . "['cookie_httponly']	= true;\n" .
			'$session' . "['sssh_db_enabled']	= true;\n" .
			'$session' . "['sssh_db_alias']		= 'default';\n" .
			'$session' . "['sssh_db_table']		= 'sessions';\n" .
			'$session' . "['sssh_db_field_session_id']		= 'session';\n" .
			'$session' . "['sssh_db_field_session_data']	= 'data';\n" .
			'$session' . "['sssh_db_field_session_valid'] 	= 'valid';\n" .
			'$session' . "['sssh_db_field_session_start_time'] = 'start_time';\n" .
			'$session' . "['sssh_db_field_session_change_time'] = 'change_time';\n" .
			'$session' . "['sssh_db_field_session_end_time'] = 'end_time';\n" .
			"\n";


		/* Write the configuration data to session configuration file */
		if (fwrite($fp, $session_config) === false)
			$this->response->code('403', NDPHP_LANG_MOD_INSTALL_WRITE_NO_PRIV . ': ' . SYSTEM_BASE_DIR . '/user/config/session.php', $this->_default_charset, !$this->request->is_ajax());

		/* Close the handler */
		fflush($fp);
		fclose($fp);

		/* All Good */
		$this->response->output(NDPHP_LANG_MOD_INSTALL_SUCCESS_SESS_CONFIG . '<br />');
	}

	public function encryption_setup() {
		/* Create the user/config/encrypt.php configuration file */
		$fp = fopen(SYSTEM_BASE_DIR . '/user/config/encrypt.php', 'w');

		/* Check if we can open the file for writing */
		if ($fp === false)
			$this->response->code('403', NDPHP_LANG_MOD_INSTALL_UNABLE_OPEN_WRITE . ': ' . SYSTEM_BASE_DIR . '/user/config/encrypt.php', $this->_default_charset, !$this->request->is_ajax());

		/* Craft database configuration */
		$encryption_config = '' .
			"<?php if (!defined('FROM_BASE')) { header('HTTP/1.1 403 Forbidden'); die('Invalid requested path.'); }\n" .
			"\n" .
			"/* Encryption settings */\n" .
			'$encrypt' . "['cipher']	= MCRYPT_RIJNDAEL_256;\n" .
			'$encrypt' . "['mode']		= MCRYPT_MODE_CBC;\n" .
			'$encrypt' . "['key']		= '" . openssl_digest(openssl_random_pseudo_bytes(256), 'md5') . "';\n" .
			"\n";

		/* Write the configuration data to session configuration file */
		if (fwrite($fp, $encryption_config) === false)
			$this->response->code('403', NDPHP_LANG_MOD_UNABLE_FILE_WRITE . ': ' . SYSTEM_BASE_DIR . '/user/config/encrypt.php', $this->_default_charset, !$this->request->is_ajax());

		/* Close the handler */
		fflush($fp);
		fclose($fp);

		/* All Good */
		$this->response->output(NDPHP_LANG_MOD_INSTALL_SUCCESS_ENC_CONFIG . '<br />');
	}

	public function base_config_setup() {
		if (copy(SYSTEM_BASE_DIR . '/install/autoload.php', SYSTEM_BASE_DIR . '/user/config/autoload.php') === false)
			$this->response->code('403', NDPHP_LANG_MOD_INSTALL_WRITE_NO_PRIV . ': ' . SYSTEM_BASE_DIR . '/user/config/autoload.php', $this->_default_charset, !$this->request->is_ajax());

		if (copy(SYSTEM_BASE_DIR . '/install/base.php', SYSTEM_BASE_DIR . '/user/config/base.php') === false)
			$this->response->code('403', NDPHP_LANG_MOD_INSTALL_WRITE_NO_PRIV . ': ' . SYSTEM_BASE_DIR . '/user/config/base.php', $this->_default_charset, !$this->request->is_ajax());

		$this->response->output(NDPHP_LANG_MOD_INSTALL_SUCCESS_BASE_CONFIG . '<br />');
	}

	public function help_data_setup() {
		/* Initialize transaction */
		$this->db->trans_begin();

		/* Builder help data */
		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'builder',
			'field_name'  => 'build',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_BUILDER_BUILD,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'builder',
			'field_name'  => 'created',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_BUILDER_CREATED,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'builder',
			'field_name'  => 'model',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_BUILDER_MODEL,
			'help_url' => '#'
		));


		/* Charts Config help data */
		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'charts_config',
			'field_name'  => 'title',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CHARTS_CONFIG_TITLE,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'charts_config',
			'field_name'  => 'controller',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CHARTS_CONFIG_CONTROLLER,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'charts_config',
			'field_name'  => 'charts_types_id',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CHARTS_CONFIG_CHARTS_TYPES_ID,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'charts_config',
			'field_name'  => 'charts_geometry_id',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CHARTS_CONFIG_CHARTS_GEOMETRY_ID,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'charts_config',
			'field_name'  => 'fields',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CHARTS_CONFIG_FIELDS,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'charts_config',
			'field_name'  => 'abscissa',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CHARTS_CONFIG_ABSCISSA,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name' => 'charts_config',
			'field_name' => 'foreign_table',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CHARTS_CONFIG_FOREIGN_TABLE,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'charts_config',
			'field_name'  => 'field',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CHARTS_CONFIG_FIELD,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'charts_config',
			'field_name'  => 'field_legend',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CHARTS_CONFIG_FIELD_LEGEND,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'charts_config',
			'field_name'  => 'field_total',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CHARTS_CONFIG_FIELD_TOTAL,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'charts_config',
			'field_name'  => 'import_ctrl',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CHARTS_CONFIG_IMPORT_CTRL,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'charts_config',
			'field_name'  => 'chartid',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CHARTS_CONFIG_CHARTID,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'charts_config',
			'field_name'  => 'field_ts',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CHARTS_CONFIG_FIELD_TS,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'charts_config',
			'field_name'  => 'start_ts',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CHARTS_CONFIG_START_TS,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'charts_config',
			'field_name'  => 'end_ts',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CHARTS_CONFIG_END_TS,
			'help_url' => '#'
		));


		/* Charts Geometry help data */
		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'charts_geometry',
			'field_name'  => 'chart_geometry',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CHARTS_GEOMETRY_CHART_GEOMETRY,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'charts_geometry',
			'field_name'  => 'description',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CHARTS_GEOMETRY_DESCRIPTION,
			'help_url' => '#'
		));

		/* Charts Types help data */
		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'charts_types',
			'field_name'  => 'chart_type',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CHARTS_TYPES_CHART_TYPE,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'charts_types',
			'field_name'  => 'description',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CHARTS_TYPES_DESCRIPTION,
			'help_url' => '#'
		));

		/* Configuration help data */
		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'configuration',
			'field_name'  => 'configuration',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CONFIGURATION_CONFIGURATION,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'configuration',
			'field_name'  => 'base_url',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CONFIGURATION_BASE_URL,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'configuration',
			'field_name'  => 'support_email',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CONFIGURATION_SUPPORT_EMAIL,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'configuration',
			'field_name'  => 'page_rows',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CONFIGURATION_PAGE_ROWS,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'configuration',
			'field_name'  => 'temporary_directory',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CONFIGURATION_TEMPORARY_DIRECTORY,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'configuration',
			'field_name'  => 'themes_id',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CONFIGURATION_THEMES_ID,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'configuration',
			'field_name'  => 'timezones_id',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CONFIGURATION_TIMEZONES_ID,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'configuration',
			'field_name'  => 'roles_id',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CONFIGURATION_ROLES_ID,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'configuration',
			'field_name'  => 'maintenance',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CONFIGURATION_MAINTENANCE,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'configuration',
			'field_name'  => 'active',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CONFIGURATION_ACTIVE,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'configuration',
			'field_name'  => 'model',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CONFIGURATION_MODEL,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'configuration',
			'field_name'  => 'project_name',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CONFIGURATION_PROJECT_NAME,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'configuration',
			'field_name'  => 'project_version',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CONFIGURATION_PROJECT_VERSION,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'configuration',
			'field_name'  => 'project_date',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CONFIGURATION_PROJECT_DATE,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'configuration',
			'field_name'  => 'tagline',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CONFIGURATION_TAGLINE,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'configuration',
			'field_name'  => 'description',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CONFIGURATION_DESCRIPTION,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'configuration',
			'field_name'  => 'author',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CONFIGURATION_AUTHOR,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'configuration',
			'field_name'  => 'smtp_username',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CONFIGURATION_SMTP_USERNAME,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'configuration',
			'field_name'  => 'smtp_password',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CONFIGURATION_SMTP_PASSWORD,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'configuration',
			'field_name'  => 'smtp_server',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CONFIGURATION_SMTP_SERVER,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'configuration',
			'field_name'  => 'smtp_port',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CONFIGURATION_SMTP_PORT,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'configuration',
			'field_name'  => 'smtp_ssl',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CONFIGURATION_SMTP_SSL,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'configuration',
			'field_name'  => 'smtp_tls',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CONFIGURATION_SMTP_TLS,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'configuration',
			'field_name'  => 'memcached_server',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CONFIGURATION_MEMCACHED_SERVER,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'configuration',
			'field_name'  => 'memcached_port',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CONFIGURATION_MEMCACHED_PORT,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'configuration',
			'field_name'  => 'recaptcha_priv_key',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CONFIGURATION_RECAPTCHA_PRIV_KEY,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'configuration',
			'field_name'  => 'recaptcha_pub_key',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CONFIGURATION_RECAPTCHA_PUB_KEY,
			'help_url' => '#'
		));


		/* Countries help data */
		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'countries',
			'field_name'  => 'country',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_COUNTRIES_COUNTRY,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'countries',
			'field_name'  => 'code',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_COUNTRIES_CODE,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'countries',
			'field_name'  => 'prefix',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_COUNTRIES_PREFIX,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'countries',
			'field_name'  => 'eu_state',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_COUNTRIES_EU_STATE,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'countries',
			'field_name'  => 'vat_rate',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_COUNTRIES_VAT_RATE,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'countries',
			'field_name'  => 'currencies_id',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_COUNTRIES_CURRENCIES_ID,
			'help_url' => '#'
		));


		/* Currencies help data */
		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'currencies',
			'field_name'  => 'currency',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CURRENCIES_CURRENCY,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'currencies',
			'field_name'  => 'code',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CURRENCIES_CODE,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'currencies',
			'field_name'  => 'sign',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CURRENCIES_SIGN,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'currencies',
			'field_name'  => 'sign_position',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CURRENCIES_SIGN_POSITION,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'currencies',
			'field_name'  => 'rate',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CURRENCIES_RATE,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'currencies',
			'field_name'  => 'updated',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CURRENCIES_UPDATED,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'currencies',
			'field_name'  => 'default',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_CURRENCIES_DEFAULT,
			'help_url' => '#'
		));


		/* DBMS help data */
		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'dbms',
			'field_name'  => 'alias',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_DBMS_ALIAS,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'dbms',
			'field_name'  => 'name',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_DBMS_NAME,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'dbms',
			'field_name'  => 'host',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_DBMS_HOST,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'dbms',
			'field_name'  => 'port',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_DBMS_PORT,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'dbms',
			'field_name'  => 'username',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_DBMS_USERNAME,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'dbms',
			'field_name'  => 'password',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_DBMS_PASSWORD,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'dbms',
			'field_name'  => 'charset',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_DBMS_CHARSET,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'dbms',
			'field_name'  => 'strict',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_DBMS_STRICT,
			'help_url' => '#'
		));


		/* Documentation help data */
		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'documentation',
			'field_name'  => 'revision',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_DOCUMENTATION_REVISION,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'documentation',
			'field_name'  => 'changed',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_DOCUMENTATION_CHANGED,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'documentation',
			'field_name'  => 'description',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_DOCUMENTATION_DESCRIPTION,
			'help_url' => '#'
		));


		/* Features help data */
		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'features',
			'field_name'  => 'feature',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_FEATURES_FEATURE,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'features',
			'field_name'  => 'description',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_FEATURES_DESCRIPTION,
			'help_url' => '#'
		));


		/* Genders help data */
		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'genders',
			'field_name'  => 'gender',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_GENDERS_GENDER,
			'help_url' => '#'
		));


		/* Items help data */
		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'items',
			'field_name'  => 'item',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_ITEMS_ITEM,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'items',
			'field_name'  => 'description',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_ITEMS_DESCRIPTION,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'items',
			'field_name'  => 'price',
			'field_units' => NDPHP_LANG_MOD_DEFAULT_CURRENCY_SYMBOL,
			'help_description' => NDPHP_LANG_MOD_HELP_ITEMS_PRICE,
			'help_url' => '#'
		));


		/* Logging help data */
		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'logging',
			'field_name'  => 'operation',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_LOGGING_OPERATION,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'logging',
			'field_name'  => '_table',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_LOGGING_TABLE,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'logging',
			'field_name'  => '_field',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_LOGGING_FIELD,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'logging',
			'field_name'  => 'entryid',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_LOGGING_ENTRYID,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'logging',
			'field_name'  => 'value_old',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_LOGGING_VALUE_OLD,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'logging',
			'field_name'  => 'value_new',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_LOGGING_VALUE_NEW,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'logging',
			'field_name'  => 'transaction',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_LOGGING_TRANSACTION,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'logging',
			'field_name'  => 'registered',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_LOGGING_REGISTERED,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'logging',
			'field_name'  => 'rolled_back',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_LOGGING_ROLLED_BACK,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'logging',
			'field_name'  => 'sessions_id',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_LOGGING_SESSIONS_ID,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'logging',
			'field_name'  => 'users_id',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_LOGGING_USERS_ID,
			'help_url' => '#'
		));


		/* Months help data */
		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'months',
			'field_name'  => 'month',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_MONTHS_MONTH,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'months',
			'field_name'  => 'number',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_MONTHS_NUMBER,
			'help_url' => '#'
		));


		/* Notifications help data */
		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'notifications',
			'field_name'  => 'notification',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_NOTIFICATIONS_NOTIFICATION,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'notifications',
			'field_name'  => 'description',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_NOTIFICATIONS_DESCRIPTION,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'notifications',
			'field_name'  => 'url',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_NOTIFICATIONS_URL,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'notifications',
			'field_name'  => 'seen',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_NOTIFICATIONS_SEEN,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'notifications',
			'field_name'  => 'all',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_NOTIFICATIONS_ALL,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'notifications',
			'field_name'  => 'when',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_NOTIFICATIONS_WHEN,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'notifications',
			'field_name'  => 'users_id',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_NOTIFICATIONS_USERS_ID,
			'help_url' => '#'
		));


		/* Payment Actions help data */
		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'payment_actions',
			'field_name'  => 'payment_action',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_PAYMENT_ACTIONS_PAYMENT_ACTION,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'payment_actions',
			'field_name'  => 'description',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_PAYMENT_ACTIONS_DESCRIPTION,
			'help_url' => '#'
		));


		/* Payment Status help data */
		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'payment_status',
			'field_name'  => 'payment_status',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_PAYMENT_STATUS_PAYMENT_STATUS,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'payment_status',
			'field_name'  => 'description',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_PAYMENT_STATUS_DESCRIPTION,
			'help_url' => '#'
		));


		/* Payment Types help data */
		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'payment_types',
			'field_name'  => 'payment_type',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_PAYMENT_TYPES_PAYMENT_TYPE,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'payment_types',
			'field_name'  => 'transaction_fee_percentage',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_PAYMENT_TYPES_TRANSACTION_FEE_PERCENTAGE,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'payment_types',
			'field_name'  => 'transaction_min_ammount',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_PAYMENT_TYPES_TRANSACTION_MIN_AMMOUNT,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'payment_types',
			'field_name'  => 'transaction_max_ammount',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_PAYMENT_TYPES_TRANSACTION_MAX_AMMOUNT,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'payment_types',
			'field_name'  => 'description',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_PAYMENT_TYPES_DESCRIPTION,
			'help_url' => '#'
		));


		/* Payments help data */
		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'payments',
			'field_name'  => 'txnid',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_PAYMENTS_TXNID,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'payments',
			'field_name'  => 'payment_types_id',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_PAYMENTS_PAYMENT_TYPES_ID,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'payments',
			'field_name'  => 'amount',
			'field_units' => NDPHP_LANG_MOD_DEFAULT_CURRENCY_SYMBOL,
			'help_description' => NDPHP_LANG_MOD_HELP_PAYMENTS_AMOUNT,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'payments',
			'field_name'  => 'tax_rate',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_PAYMENTS_TAX_RATE,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'payments',
			'field_name'  => 'payment_fee',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_PAYMENTS_PAYMENT_FEE,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'payments',
			'field_name'  => 'total_tax',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_PAYMENTS_TOTAL_TAX,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'payments',
			'field_name'  => 'payment_status_id',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_PAYMENTS_PAYMENT_STATUS_ID,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'payments',
			'field_name'  => 'status_desc',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_PAYMENTS_STATUS_DESC,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'payments',
			'field_name'  => 'items_id',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_PAYMENTS_ITEMS_ID,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'payments',
			'field_name'  => 'item_price',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_PAYMENTS_ITEM_PRICE,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'payments',
			'field_name'  => 'item_quantity',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_PAYMENTS_ITEM_QUANTITY,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'payments',
			'field_name'  => 'item_description',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_PAYMENTS_ITEM_DESCRIPTION,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'payments',
			'field_name'  => 'created',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_PAYMENTS_CREATED,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'payments',
			'field_name'  => 'updated',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_PAYMENTS_UPDATED,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'payments',
			'field_name'  => 'users_id',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_PAYMENTS_USERS_ID,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'payments',
			'field_name'  => 'payment_actions_id',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_PAYMENTS_PAYMENT_ACTIONS_ID,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'payments',
			'field_name'  => 'payer_email',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_PAYMENTS_PAYER_EMAIL,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'payments',
			'field_name'  => 'payer_first_name',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_PAYMENTS_PAYER_FIRST_NAME,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'payments',
			'field_name'  => 'payer_last_name',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_PAYMENTS_PAYER_LAST_NAME,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'payments',
			'field_name'  => 'payer_address_name',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_PAYMENTS_PAYER_ADDRESS_NAME,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'payments',
			'field_name'  => 'payer_address_country',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_PAYMENTS_PAYER_ADDRESS_COUNTRY,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'payments',
			'field_name'  => 'payer_address_city',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_PAYMENTS_PAYER_ADDRESS_CITY,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'payments',
			'field_name'  => 'payer_address_street',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_PAYMENTS_PAYER_ADDRESS_STREET,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'payments',
			'field_name'  => 'payer_address_zip',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_PAYMENTS_PAYER_ADDRESS_ZIP,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'payments',
			'field_name'  => 'payer_address_state',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_PAYMENTS_PAYER_ADDRESS_STATE,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'payments',
			'field_name'  => 'payer_address_status',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_PAYMENTS_PAYER_ADDRESS_STATUS,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'payments',
			'field_name'  => 'payer_status',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_PAYMENTS_PAYER_STATUS,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'payments',
			'field_name'  => 'payer_residence_country',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_PAYMENTS_PAYER_RESIDENCE_COUNTRY,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'payments',
			'field_name'  => 'payer_payment_date',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_PAYMENTS_PAYER_PAYMENT_DATE,
			'help_url' => '#'
		));


		/* Roles help data */
		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'roles',
			'field_name'  => 'role',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_ROLES_ROLE,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'roles',
			'field_name'  => 'description',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_ROLES_DESCRIPTION,
			'help_url' => '#'
		));


		/* Scheduler help data */
		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'scheduler',
			'field_name'  => 'entry_name',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_SCHEDULER_ENTRY_NAME,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'scheduler',
			'field_name'  => 'description',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_SCHEDULER_DESCRIPTION,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'scheduler',
			'field_name'  => 'url',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_SCHEDULER_URL,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'scheduler',
			'field_name'  => 'period',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_SCHEDULER_PERIOD,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'scheduler',
			'field_name'  => 'active',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_SCHEDULER_ACTIVE,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'scheduler',
			'field_name'  => 'registered',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_SCHEDULER_REGISTERED,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'scheduler',
			'field_name'  => 'last_run',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_SCHEDULER_LAST_RUN,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'scheduler',
			'field_name'  => 'next_run',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_SCHEDULER_NEXT_RUN,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'scheduler',
			'field_name'  => 'output',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_SCHEDULER_OUTPUT,
			'help_url' => '#'
		));


		/* Sessions help data */
		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'sessions',
			'field_name'  => 'session',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_SESSIONS_SESSION,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'session',
			'field_name'  => 'ip_address',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_SESSION_IP_ADDRESS,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'session',
			'field_name'  => 'user_agent',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_SESSION_USER_AGENT,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'session',
			'field_name'  => 'start_time',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_SESSION_START_TIME,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'session',
			'field_name'  => 'last_login',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_SESSION_LAST_LOGIN,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'session',
			'field_name'  => 'users_id',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_SESSION_USERS_ID,
			'help_url' => '#'
		));


		/* Subscription Types help data */
		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'subscription_types',
			'field_name'  => 'subscription_type',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_SUBSCRIPTION_TYPES_SUBSCRIPTION_TYPE,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'subscription_types',
			'field_name'  => 'description',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_SUBSCRIPTION_TYPES_DESCRIPTION,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'subscription_types',
			'field_name'  => 'price',
			'field_units' => NDPHP_LANG_MOD_DEFAULT_CURRENCY_SYMBOL,
			'help_description' => NDPHP_LANG_MOD_HELP_SUBSCRIPTION_TYPES_PRICE,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'subscription_types',
			'field_name'  => 'api_extended',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_SUBSCRIPTION_TYPES_API_EXTENDED,
			'help_url' => '#'
		));


		/* Themes help data */
		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'themes',
			'field_name'  => 'theme',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_THEMES_THEME,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'themes',
			'field_name'  => 'description',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_THEMES_DESCRIPTION,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'themes',
			'field_name'  => 'animation_default_delay',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_THEMES_ANIMATION_DEFAULT_DELAY,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'themes',
			'field_name'  => 'animation_ordering_delay',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_THEMES_ANIMATION_ORDERING_DELAY,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'themes',
			'field_name'  => 'animations_default_id',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_THEMES_ANIMATIONS_DEFAULT_ID,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'themes',
			'field_name'  => 'animations_ordering_id',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_THEMES_ANIMATIONS_ORDERING_ID,
			'help_url' => '#'
		));


		/* Themes Animations Default help data */
		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'themes_animations_default',
			'field_name'  => 'animation',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_THEMES_ANIMATIONS_DEFAULT_ANIMATION,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'themes_animations_default',
			'field_name'  => 'description',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_THEMES_ANIMATIONS_DEFAULT_DESCRIPTION,
			'help_url' => '#'
		));


		/* Themes Animations Ordering help data */
		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'themes_animations_ordering',
			'field_name'  => 'animation',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_THEMES_ANIMATIONS_ORDERING_ANIMATION,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'themes_animations_ordering',
			'field_name'  => 'description',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_THEMES_ANIMATIONS_ORDERING_DESCRIPTION,
			'help_url' => '#'
		));


		/* Timezones help data */
		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'timezones',
			'field_name'  => 'timezone',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_TIMEZONES_TIMEZONE,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'timezones',
			'field_name'  => 'countries_id',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_TIMEZONES_COUNTRIES_ID,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'timezones',
			'field_name'  => 'utc',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_TIMEZONES_UTC,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'timezones',
			'field_name'  => 'utc_dst',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_TIMEZONES_UTC_DST,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'timezones',
			'field_name'  => 'coordinates',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_TIMEZONES_COORDINATES,
			'help_url' => '#'
		));


		/* Transaction History help data */
		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'transaction_history',
			'field_name'  => 'transaction_date',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_TRANSACTION_HISTORY_TRANSACTION_DATE,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'transaction_history',
			'field_name'  => 'transaction_types_id',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_TRANSACTION_HISTORY_TRANSACTION_TYPES_ID,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'transaction_history',
			'field_name'  => 'amount',
			'field_units' => NDPHP_LANG_MOD_DEFAULT_CURRENCY_SYMBOL,
			'help_description' => NDPHP_LANG_MOD_HELP_TRANSACTION_HISTORY_AMOUNT,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'transaction_history',
			'field_name'  => 'description',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_TRANSACTION_HISTORY_DESCRIPTION,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'transaction_history',
			'field_name'  => 'users_id',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_TRANSACTION_HISTORY_USERS_ID,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'transaction_types',
			'field_name'  => 'transaction_type',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_TRANSACTION_TYPES_TRANSACTION_TYPE,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'transaction_types',
			'field_name'  => 'description',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_TRANSACTION_TYPES_DESCRIPTION,
			'help_url' => '#'
		));


		/* Users help data */
		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'users',
			'field_name'  => 'username',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_USERS_USERNAME,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'users',
			'field_name'  => 'password',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_USERS_PASSWORD,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'users',
			'field_name'  => '_file_photo',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_USERS_FILE_PHOTO,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'users',
			'field_name'  => 'email',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_USERS_EMAIL,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'users',
			'field_name'  => 'phone',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_USERS_PHONE,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'users',
			'field_name'  => 'active',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_USERS_ACTIVE,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'users',
			'field_name'  => 'locked',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_USERS_LOCKED,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'users',
			'field_name'  => 'subscription_types_id',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_USERS_SUBSCRIPTION_TYPES_ID,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'users',
			'field_name'  => 'subscription_change_date',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_USERS_SUBSCRIPTION_CHANGE_DATE,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'users',
			'field_name'  => 'subscription_renew_date',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_USERS_SUBSCRIPTION_RENEW_DATE,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'users',
			'field_name'  => 'first_name',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_USERS_FIRST_NAME,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'users',
			'field_name'  => 'last_name',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_USERS_LAST_NAME,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'users',
			'field_name'  => 'genders_id',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_USERS_GENDERS_ID,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'users',
			'field_name'  => 'birthdate',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_USERS_BIRTHDATE,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'users',
			'field_name'  => 'countries_id',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_USERS_COUNTRIES_ID,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'users',
			'field_name'  => 'currencies_id',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_USERS_CURRENCIES_ID,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'users',
			'field_name'  => 'timezones_id',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_USERS_TIMEZONES_ID,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'users',
			'field_name'  => 'company',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_USERS_COMPANY,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'users',
			'field_name'  => 'address_line1',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_USERS_ADDRESS_LINE1,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'users',
			'field_name'  => 'address_line2',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_USERS_ADDRESS_LINE2,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'users',
			'field_name'  => 'city',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_USERS_CITY,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'users',
			'field_name'  => 'postcode',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_USERS_POSTCODE,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'users',
			'field_name'  => 'vat',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_USERS_VAT,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'users',
			'field_name'  => 'expire',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_USERS_EXPIRE,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'users',
			'field_name'  => 'registered',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_USERS_REGISTERED,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'users',
			'field_name'  => 'last_login',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_USERS_LAST_LOGIN,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'users',
			'field_name'  => 'confirm_email_hash',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_USERS_CONFIRM_EMAIL_HASH,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'users',
			'field_name'  => 'confirm_phone_token',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_USERS_CONFIRM_PHONE_TOKEN,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'users',
			'field_name'  => 'email_confirmed',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_USERS_EMAIL_CONFIRMED,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'users',
			'field_name'  => 'phone_confirmed',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_USERS_PHONE_CONFIRMED,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'users',
			'field_name'  => 'date_confirmed',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_USERS_DATE_CONFIRMED,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'users',
			'field_name'  => 'credit',
			'field_units' => NDPHP_LANG_MOD_DEFAULT_CURRENCY_SYMBOL,
			'help_description' => NDPHP_LANG_MOD_HELP_USERS_CREDIT,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'users',
			'field_name'  => 'allow_negative',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_USERS_ALLOW_NEGATIVE,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'users',
			'field_name'  => 'apikey',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_USERS_APIKEY,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'users',
			'field_name'  => 'acct_last_rest',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_USERS_ACCT_LAST_REST,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'users',
			'field_name'  => 'acct_rest_list',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_USERS_ACCT_REST_LIST,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'users',
			'field_name'  => 'acct_rest_result',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_USERS_ACCT_REST_RESULT,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'users',
			'field_name'  => 'acct_rest_view',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_USERS_ACCT_REST_VIEW,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'users',
			'field_name'  => 'acct_rest_delete',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_USERS_ACCT_REST_DELETE,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'users',
			'field_name'  => 'acct_rest_update',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_USERS_ACCT_REST_UPDATE,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'users',
			'field_name'  => 'acct_rest_insert',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_USERS_ACCT_REST_INSERT,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'users',
			'field_name'  => 'dbms_id',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_USERS_DBMS_ID,
			'help_url' => '#'
		));


		/* Weekdays help data */
		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'weekdays',
			'field_name'  => 'weekday',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_WEEKDAYS_WEEKDAY,
			'help_url' => '#'
		));

		$this->db->insert('_help_tfhd', array(
			'table_name'  => 'weekdays',
			'field_name'  => 'number',
			'field_units' => NULL,
			'help_description' => NDPHP_LANG_MOD_HELP_WEEKDAYS_NUMBER,
			'help_url' => '#'
		));

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			$this->response->code('500', NDPHP_LANG_MOD_INSTALL_UNABLE_INSERT_DATA, $this->_default_charset, !$this->request->is_ajax());
		}

		$this->db->trans_commit();

		$this->response->output(NDPHP_LANG_MOD_INSTALL_SUCCESS_HELP_DATA . '<br />');
	}

	public function post_install_setup() {
		/* Disable caching */
		$this->ndphp->no_cache();

		$this->session_setup();
		$this->encryption_setup();
		$this->base_config_setup();

		/* Wait for webserver to start replying with the new configuration */
		$this->grant_url_validation(base_url() . '/index.php/install/validate_encrypt_config', $this->_retries_max, $this->_sleep_secs);

		/* Setup additional data */
		$this->help_data_setup();

		/* All good, lets finish this... */
		redirect('install/finish');
	}



	/**********************************/
	/* The final step (informational) */
	/**********************************/

	public function finish() {
		/* Disable caching */
		$this->ndphp->no_cache();

		/* Create a control file to indicate installation is complete */
		if (($fp = fopen(SYSTEM_BASE_DIR . '/install/' . $this->_inst_ctl_file, 'w')) === false)
			$this->response->code('500', NDPHP_LANG_MOD_INSTALL_UNABLE_OPEN_WRITE . ': ' . SYSTEM_BASE_DIR . '/install/' . $this->_inst_ctl_file, $this->_default_charset, !$this->request->is_ajax());

		fclose($fp);

		$data['charset'] = $this->_default_charset;

		$this->load->view('install/finish', $data);
	}


	/******************************/
	/* Where everything starts... */
	/******************************/

	public function index() {
		/* Disable caching */
		$this->ndphp->no_cache();

		/* TODO: Instead of redirecting directly to pre_check, create an introductory page first */
		redirect('install/pre_check');
	}


	/******************/
	/* Auto Installer */
	/******************/

	public function auto() {
		/* No time limit for install process */
		set_time_limit(0);

		/* Disable caching */
		$this->ndphp->no_cache();

		/* Read configuration data */
		$auto_contents = file_get_contents(SYSTEM_BASE_DIR . '/install/auto.json');

		/* Check if we've read anything */
		if (!$auto_contents)
			$this->response->code('500', NDPHP_LANG_MOD_INSTALL_NO_AUTO_DATA, $this->_default_charset, !$this->request->is_ajax());

		/* Decode auto install configuration data */
		$auto = json_decode($auto_contents, true);

		/* Check if data was decoded */
		if ($auto === NULL)
			$this->response->code('500', NDPHP_LANG_MOD_INSTALL_UNABLE_DECODE_AUTO_DATA, $this->_default_charset, $this->request->is_ajax());


		/** Database configuration **/

		/* Apply database configuration */
		$ch = curl_init(base_url() . 'index.php/install/db_config_apply/' .
			rawurlencode($this->ndphp->safe_b64encode($auto['db']['host'])) . '/' .
			rawurlencode($this->ndphp->safe_b64encode($auto['db']['port'])) . '/' .
			rawurlencode($this->ndphp->safe_b64encode($auto['db']['name'])) . '/' .
			rawurlencode($this->ndphp->safe_b64encode($auto['db']['username'])) . '/' .
			rawurlencode($this->ndphp->safe_b64encode($auto['db']['password']))
		);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		$out = curl_exec($ch);

		if (($code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) != 200) {
			ob_clean();
			$this->response->code('500', NDPHP_LANG_MOD_INSTALL_UNABLE_APPLY_DB_CONFIG . ': ' . $code, $this->_default_charset, !$this->request->is_ajax());
		}

		curl_close($ch);

		/* Test the database connection. $this->_retries_max attempts before failing with a $this->_sleep_secs seconds interval between each */
		for ($i = 0; $i < $this->_retries_max; $i ++) {
			$ch = curl_init(base_url() . 'index.php/install/db_test/' .
				rawurlencode($this->ndphp->safe_b64encode($auto['db']['host'])) . '/' .
				rawurlencode($this->ndphp->safe_b64encode($auto['db']['port'])) . '/' .
				rawurlencode($this->ndphp->safe_b64encode($auto['db']['name'])) . '/' .
				rawurlencode($this->ndphp->safe_b64encode($auto['db']['username'])) . '/' .
				rawurlencode($this->ndphp->safe_b64encode($auto['db']['password'])) . '/' .
				'1'
			);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			$out = curl_exec($ch);
			curl_close($ch);

			if ($out != "OK") {
				sleep($this->_sleep_secs); /* Sleep $this->_sleep_secs second before retry */
				continue;
			}

			break;
		}

		/* Check if the test succeeded */
		if ($i >= $this->_retries_max) {
			ob_clean();
			$this->response->code('500', NDPHP_LANG_MOD_INSTALL_DATABASE_TEST_FAILED, $this->_default_charset, !$this->request->is_ajax());
		}

		/* Setup database */
		$ch = curl_init(base_url() . 'index.php/install/db_setup');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_exec($ch);

		if (($code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) != 200) {
			ob_clean();
			$this->response->code('500', NDPHP_LANG_MOD_INSTALL_UNABLE_SETUP_DATABASE . ': ' . $code, $this->_default_charset, !$this->request->is_ajax());
		}

		curl_close($ch);


		/** Setup User **/
		$ch = curl_init(base_url() . 'index.php/install/user_setup/' .
			rawurlencode($this->ndphp->safe_b64encode($auto['user']['password'])) . '/' .
			rawurlencode($this->ndphp->safe_b64encode($auto['user']['email']))
		);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_exec($ch);

		if (($code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) != 200) {
			ob_clean();
			$this->response->code('500', NDPHP_LANG_MOD_INSTALL_UNABLE_SETUP_USER . ': ' . $code, $this->_default_charset, !$this->request->is_ajax());
		}

		curl_close($ch);


		/** Setup app **/
		$ch = curl_init(base_url() . 'index.php/install/app_setup/' .
			rawurlencode($this->ndphp->safe_b64encode($auto['app']['name'])) . '/' .
			rawurlencode($this->ndphp->safe_b64encode($auto['app']['tagline'])) . '/' .
			rawurlencode($this->ndphp->safe_b64encode($auto['app']['description'])) . '/' .
			rawurlencode($this->ndphp->safe_b64encode($auto['app']['author']))
		);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_exec($ch);

		if (($code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) != 200) {
			ob_clean();
			$this->response->code('500', NDPHP_LANG_MOD_INSTALL_UNABLE_SETUP_APP . ': ' . $code, $this->_default_charset, !$this->request->is_ajax());
		}

		curl_close($ch);

		/* Clean output buffer */
		ob_clean();

		/** Redirect to base **/
		redirect('/');
	}


	/**************/
	/* Validators */
	/**************/

	public function grant_url_validation($url, $retries, $delay) {
		/* This routine always expects an "OK" result from the validation url */
		while ($retries --) {
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$out = curl_exec($ch);
			curl_close($ch);

			if ($out != "OK") {
				sleep($delay);
				continue;
			}

			break;
		}

		/* Are we good? */
		if (!$retries)
			$this->response->code('500', NDPHP_LANG_MOD_INSTALL_UNABLE_FETCH_OK_URL . ': ' . $url, $this->_default_charset, !$this->request->is_ajax());
	}

	public function validate_db_config() {
		$this->db->trans_begin();
		$this->response->output("OK");
		$this->db->trans_commit();
	}

	public function validate_encrypt_config() {
		/* FIXME: Although this works fine, this should be re-implemented */
		global $config;

		if (strlen($config['encrypt']['key']) == $this->_enc_key_len) {
			$this->response->output("OK");
		} else {
			$this->response->output('FAIL');
		}
	}
}