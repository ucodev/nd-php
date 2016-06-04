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

class Login extends UW_Controller {
	/* General settings */
	protected $_author = "ND PHP Framework";	// Project Author
	protected $_project_name = "ND php";
	protected $_tagline = "Framework";
	protected $_description = "An handy PHP Framework";
	protected $_name;					// Controller segment / Table name (must be lower case)
	protected $_viewhname;			// The name used in the view headers
	protected $_default_timezone = NDPHP_LANG_MOD_DEFAULT_TIMEZONE;
	protected $_default_database = 'default';
	protected $_theme = 'Blueish';
	protected $_base_url = 'http://localhost/ndphp/';
	protected $_temp_dir = '/tmp/';
	protected $_charset = NDPHP_LANG_MOD_DEFAULT_CHARSET;
	protected $_logging = true;

	protected $_word_true = NDPHP_LANG_MOD_WORD_TRUE;
	protected $_word_false = NDPHP_LANG_MOD_WORD_FALSE;

	private $_table_users = "users";
	private $_table_rel_roles = "rel_users_roles";

	private $_maintenance_enabled = false;

	protected function _get_theme() {
		$this->db->select(
			'themes.theme AS name,'.
			'themes.animation_default_delay AS animation_default_delay,themes.animation_ordering_delay AS animation_ordering_delay,'.
			'themes_animations_default.animation AS animation_default_type,themes_animations_ordering.animation AS animation_ordering_type'
		);
		$this->db->from('themes');
		$this->db->join('themes_animations_default', 'themes_animations_default.id = themes.themes_animations_default_id', 'left');
		$this->db->join('themes_animations_ordering', 'themes_animations_ordering.id = themes.themes_animations_ordering_id', 'left');
		$this->db->where('theme', $this->_theme);
		$q = $this->db->get();

		return $q->row_array();
	}

	private function validate_email($email)
	{
    		return preg_match("/^[_a-z0-9-]+(\.[_a-z0-9+-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i", $email);
	}

	public function __construct()
	{
		parent::__construct();

		$this->_viewhname = get_class();
		$this->_name = strtolower($this->_viewhname);

		/* Load configuration */
		$config = $this->configuration->get();

		$this->_base_url = $config['base_url'];
		$this->_author = $config['author'];
		$this->_project_name = $config['project_name'];
		$this->_tagline = $config['tagline'];
		$this->_description = $config['description'];
		$this->_default_timezone = $config['timezone'];
		$this->_theme = $config['theme'];
		$this->_temp_dir = $config['temporary_directory'];
		$this->_maintenance_enabled = $config['maintenance'];

		/* NOTE: Maintenance mode verification is placed after session_setup() completes */
	}

	public function login($referer = NULL)
	{
		$data['config'] = array();
		$data['config']['charset'] = $this->_charset;
		$data['config']['theme'] = $this->_get_theme();

		$data['project'] = array();
		$data['project']['author'] = $this->_author;
		$data['project']['name'] = $this->_project_name;
		$data['project']['tagline'] = $this->_tagline;
		$data['project']['description'] = $this->_description;

		$data['view'] = array();
		$data['view']['ctrl'] = $this->_name;
		$data['view']['title'] = $this->_project_name . " - " . NDPHP_LANG_MOD_LOGIN_LOGIN;
		$data['view']['description'] = "Login page";
		$data['view']['referer'] = $referer;		
		$data['view']['fields_extra'] = array(
			/*
			array(
				'viewname' => 'View Name',
				'input_name' => 'login_variable',
				'input_type' => 'text',
				'required' => false
			),
			...
			*/
		);

		/* Load login plugins */
		foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/login.php') as $plugin)
			include($plugin);

		/* Load view */
		$this->load->view('themes/' . $this->_theme . '/' . $this->_name . '/login_form', $data);
	}

	public function index()
	{
		$this->login();
	}
	
	/* TODO: FIXME: This function shall be migrated into Ndphp model to be used on ND_Controller __construct
	 * and ND_Users hooks (when user data is updated, session data must be refreshed).
	 */
	private function session_setup($user_id = NULL, $username = NULL, $plain_password = NULL, $email = NULL, $first_name = NULL, $photo = NULL) {
		/* Sanity checks */
		if (!$user_id || !$username || !$plain_password || !$email) {
			header('HTTP/1.1 403 Forbidden');
			die('session_setup(): ' . NDPHP_LANG_MOD_MISSING_REQUIRED_ARGS);
		}

		if (!isset($_SERVER['REMOTE_ADDR']) || !$_SERVER['REMOTE_ADDR']) {
			header('HTTP/1.1 403 Forbidden');
			die('session_setup(): ' . NDPHP_LANG_MOD_MISSING_REMOTE_ADDRESS);
		}

		/* Get user's roles */
		$this->db->select('roles_id');
		$this->db->from($this->_table_rel_roles);
		$this->db->where('users_id', $user_id);
		$query = $this->db->get();
		$roles = $query->result_array();

		$user_roles = array();

		foreach ($roles as $role) {
			array_push($user_roles, $role['roles_id']);
		}

		/* Get user timezone */
		$timezone = $this->_default_timezone; /* Default timezone */

		$this->db->select('timezone');
		$this->db->from('timezones');
		$this->db->join('users', 'users.timezones_id = timezones.id', 'left');
		$this->db->where('users.id', $user_id);
		$this->db->is_not_null('users.timezones_id');
		$query = $this->db->get();

		if ($query->num_rows()) {
			$tzdata = $query->row_array();
			$timezone = $tzdata['timezone'];
		}

		/* Get user database */
		$database = $this->_default_database;

		$this->db->select('dbms.alias AS default_database');
		$this->db->from('users');
		$this->db->join('dbms', 'users.dbms_id = dbms.id', 'left');
		$this->db->where('users.id', $user_id);
		$query = $this->db->get();

		if ($query->num_rows()) {
			$dbdata = $query->row_array();
			$database = $dbdata['default_database'];
		}

		/* Get user private key */
		$this->db->select('privenckey');
		$this->db->from('users');
		$this->db->where('id', $user_id);
		$query = $this->db->get();

		if (!$query->num_rows()) {
			header('HTTP/1.1 500 Internal Server Error');
			die(NDPHP_LANG_MOD_UNABLE_FETCH_CRIT_DATA_DBMS);
		}

		$pek_row = $query->row_array();

		/* Decrypt the stored key with the user's plain password */
		$privenckey = $this->encrypt->decrypt($pek_row['privenckey'], $plain_password, false);

		if (strlen($privenckey) != 256) {
			header('HTTP/1.1 500 Internal Server Error');
			die(NDPHP_LANG_MOD_INVALID_PRIV_ENC_KEY);
		}

		/* Setup user session */
		/* NOTE: Grant that session data is stored under server sessions and not client cookies.
		 * Client cookies hold 4kB of data at most... if it is exceeded by the following data, bad things can happen.
		 */
		$this->session->set_userdata(
			array(
				'username' => $username,
				'user_id' => $user_id,
				'email' => $email,
				'first_name' => $first_name,
				'photo' => $photo ? (base_url() . 'index.php/files/access/users/' . $user_id . '/_file_photo/' . $photo) : NULL,
				'timezone' => $timezone,
				'database' => $database,
				'roles' => $user_roles,
				'privenckey' => base64_encode($privenckey),
				'logged_in' => true,
				'sessions_id' => 0, /* Will be set when session table is queried */
				'_apicall' => false
			)
		);

		/* Update last login */
		$userdata['last_login'] = date('Y-m-d H:i:s');

		$this->db->trans_begin();

		$this->db->where('id', $user_id);
		$this->db->update('users', $userdata);

		/* Check if this session already exists on sessions table */
		$this->db->select('id,session');
		$this->db->from('sessions');
		$this->db->where('session', session_id());
		$q = $this->db->get();

		$sessions_id = NULL; /* The'id' field value on sessions table... will be populated if there are results */

		if ($q->num_rows()) {
			/* Session already exists, so we just need to update it */
			$this->db->where('session', session_id());
			$this->db->update('sessions', array(
				'ip_address' => (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'Unspecified',
				'user_agent' => (isset($_SERVER['HTTP_USER_AGENT']) && $_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Unspecified',
				'last_login' => date('Y-m-d H:i:s'),
				'users_id' => $user_id
			));

			/* Update $sessions_id */
			$row = $q->row_array();
			$sessions_id = $row['id'];
		} else {
			/* The session doesn't exist... Unauthorized */
			header('HTTP/1.1 403 Unauthorized');
			die('No session found.');
		}

		/* Commit transaction if everything is fine. */
		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}

		/* Update sessions_id key on session data. We must do this after transaction completes, otherwise
		 * we'll cause a deadlock if transactional sessions based on dbms are being used (which is the default
		 * setting in ND PHP Framework).
		 */
		$this->session->set('sessions_id', $sessions_id);
	}

	public function authenticate($user_id = NULL, $api_key = NULL) {
		/* Load pre plugins */
		foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/authenticate_pre.php') as $plugin)
			include($plugin);

		/* Check if this is an authentication based on api key */
		if (($user_id != NULL) && ($api_key != NULL)) {
			$this->db->from('users');
			$this->db->where('id', $user_id);
			$this->db->where('apikey', $api_key);
			$query = $this->db->get();

			if ($query->num_rows() == 1) {
				$row = $query->row_array();
			} else {
				header('HTTP/1.0 403 Forbidden');
				die(NDPHP_LANG_MOD_INVALID_USER_OR_API_KEY);
			}
		} else if (!isset($_POST['username'])) {
			header('HTTP/1.0 403 Forbidden');
			die(NDPHP_LANG_MOD_MISSING_AUTH_METHOD);
		} else {
			/* Retrive username information from database */
			$this->db->where('username', $_POST['username']);
			$query = $this->db->get($this->_table_users);
			$row = $query->row_array();

			/* Validade if user exists */
			if (!$row) {
				header("HTTP/1.0 403 Forbidden");
				die(NDPHP_LANG_MOD_INVALID_USER_OR_PASSWORD);
			}

			/* Check encryption algorithm */
			if (substr($row['password'], 0, 7) == '$2y$10$') {
				/* Crypt Blowfish */
				$passwd_digest = crypt($_POST['password'], substr($row['password'], 0, 29));
			} else if (strlen($row['password']) == 128) {
				/* SHA512 */
				$passwd_digest = openssl_digest($_POST['password'], 'sha512');
			} else {
				/* Unrecognized hash */
				header('HTTP/1.0 403 Forbidden');
				die(NDPHP_LANG_MOD_ATTN_CONTACT_SUPPORT);
			}

			/* Validade password */
			if ($passwd_digest != $row['password']) {
				header("HTTP/1.0 403 Forbidden");
				die(NDPHP_LANG_MOD_INVALID_USER_OR_PASSWORD);
			}
		}

		/* Check if account is active */
		if ($row['active'] != 1) {
			header("HTTP/1.0 403 Forbidden");
			die(NDPHP_LANG_MOD_ACCESS_ACCT_INACTIVE . ' ' . NDPHP_LANG_MOD_ATTN_CONTACT_SUPPORT);
		}

		/* Check if account is locked */
		if ($row['locked'] != 0) {
			header("HTTP/1.0 403 Forbidden");
			die(NDPHP_LANG_MOD_ACCESS_ACCT_LOCKED . ' ' . NDPHP_LANG_MOD_ATTN_CONTACT_SUPPORT);
		}

		/* Check if account is expired */
		if ($row['expire']) {
			$expired_date = strtotime($row['expire']);
			$current_date = time();

			if ($current_date >= $expired_date) {
				header("HTTP/1.0 403 Forbidden");
				die(NDPHP_LANG_MOD_ACCESS_ACCT_EXPIRED . ' ' . NDPHP_LANG_MOD_ATTN_CONTACT_SUPPORT);
			}
		}

		/* Validade email address (regex) */		
		if ($this->validate_email($row['email']) == false) {
			header("HTTP/1.0 403 Forbidden");
			die(NDPHP_LANG_MOD_ACCESS_ACCT_INVALID_EMAIL . ' ' . NDPHP_LANG_MOD_ATTN_CONTACT_SUPPORT);
		}

		/**** All sanity checks successfully passed ****/

		/* Setup user session */
		$this->session_setup($row['id'], $row['username'], $_POST['password'], $row['email'], $row['first_name'], $row['_file_photo']);

		/* Check if we're under maintenance mode */
		if ($this->_maintenance_enabled && !$this->security->im_admin()) {
			header('HTTP/1.1 403 Forbidden');
			die(NDPHP_LANG_MOD_MGMT_UNDER_MAINTENANCE);
		}

		/* If logging is enabled, log this LOGIN entry */
		if ($this->_logging === true) {
			$log_transaction_id = openssl_digest('LOGIN' . $this->_name . $this->session->userdata('sessions_id') . date('Y-m-d H:i:s') . mt_rand(1000000, 9999999), 'md5');

			$this->db->insert('logging', array(
				'operation' => 'LOGIN',
				'_table' => 'users',
				'transaction' => $log_transaction_id,
				'registered' => date('Y-m-d H:i:s'),
				'sessions_id' => $this->session->userdata('sessions_id'),
				'users_id' => $row['id']
			));
		}

		/* Load post plugins */
		foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/authenticate_post.php') as $plugin)
			include($plugin);

		/* User is authenticated... redirecting to application contents */
		if ($_POST['referer']) {
			redirect($this->ndphp->safe_b64decode($_POST['referer']), false, true); /* Full URL redirect */
		} else {
			redirect('/');
		}
	}

	public function logout()
	{
		/* If logging is enabled, log this LOGOUT request */
		if ($this->_logging === true) {
			$log_transaction_id = openssl_digest('LOGOUT' . $this->_name . $this->session->userdata('sessions_id') . date('Y-m-d H:i:s') . mt_rand(1000000, 9999999), 'md5');

			$this->db->insert('logging', array(
				'operation' => 'LOGOUT',
				'_table' => 'users',
				'transaction' => $log_transaction_id,
				'registered' => date('Y-m-d H:i:s'),
				'sessions_id' => $this->session->userdata('sessions_id'),
				'users_id' => $this->session->userdata('user_id')
			));
		}

		/* Clear user session dataa */
		$this->session->set_userdata(array(
			'logged_in' => false
		));

		$this->session->cleanup();
		$this->session->destroy();

		/* Redirect to base */
		redirect('/');
	}
}
