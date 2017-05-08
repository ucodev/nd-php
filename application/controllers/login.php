<?php if (!defined('FROM_BASE')) { header('HTTP/1.1 403 Forbidden'); die('Invalid requested path.'); }

/*
 * This file is part of ND PHP Framework.
 *
 * ND PHP Framework - An handy PHP Framework (www.nd-php.org)
 * Copyright (C) 2015-2017  Pedro A. Hortas (pah@ucodev.org)
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
	protected $_name;					// Controller segment / Table name (must be lower case)
	protected $_viewhname;			// The name used in the view headers

	protected $_project_author = "ND PHP Framework";	// Project Author
	protected $_project_name = "ND php";
	protected $_project_tagline = "Framework";
	protected $_project_description = "An handy PHP Framework";

	protected $_default_charset = NDPHP_LANG_MOD_DEFAULT_CHARSET;
	protected $_default_timezone = NDPHP_LANG_MOD_DEFAULT_TIMEZONE;
	protected $_default_database = 'default';
	protected $_default_theme = 'Blueish';

	protected $_base_url = 'http://localhost/ndphp/';
	protected $_temp_dir = '/tmp/';

	protected $_logging = true;

	protected $_word_true = NDPHP_LANG_MOD_WORD_TRUE;
	protected $_word_false = NDPHP_LANG_MOD_WORD_FALSE;

	protected $_security_safe_chars = "a-zA-Z0-9_"; /* Mainly used to validate names of tables, fields and keys */

	private $_table_users = "users";
	private $_table_rel_roles = "rel_users_roles";
	private $_table_roles = "roles";

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
		$this->db->where('theme', $this->_default_theme);
		$q = $this->db->get();

		return $q->row_array();
	}

	public function __construct() {
		parent::__construct();

		/* Grant that the configured cookie domain matches the server name */
		if (current_config()['session']['cookie_domain'] != $_SERVER['SERVER_NAME'])
			$this->response->code('403', NDPHP_LANG_MOD_INVALID_SERVER_NAME, $this->_default_charset, !$this->request->is_ajax());

		/* POST data handlers */
		if (count($_POST)) {
			/* Set all $_POST keys to lowercase */
			foreach ($_POST as $key => $value) {
				unset($_POST[$key]);
				$_POST[strtolower($key)] = $value;
			}

			/* Grant that $_POST keys are safe, if any */
			if (count($_POST) && !$this->security->safe_keys($_POST, $this->_security_safe_chars))
				$this->response->code('403', NDPHP_LANG_MOD_INVALID_POST_KEYS, $this->_default_charset, !$this->request->is_ajax());
		}

		$this->_viewhname = get_class();
		$this->_name = strtolower($this->_viewhname);

		/* Load configuration */
		$config = $this->configuration->get();

		$this->_base_url = $config['base_url'];

		$this->_default_timezone = $config['timezone'];
		$this->_default_theme = $config['theme'];

		$this->_project_author = $config['author'];
		$this->_project_name = $config['project_name'];
		$this->_project_tagline = $config['tagline'];
		$this->_project_description = $config['description'];

		$this->_temp_dir = $config['temporary_directory'];
		$this->_maintenance_enabled = $config['maintenance'];

		/* NOTE: Maintenance mode verification is placed after session_setup() completes */
	}

	public function login($referer = NULL) {
		$data['config'] = array();
		$data['config']['charset'] = $this->_default_charset;
		$data['config']['theme'] = $this->_get_theme();

		$data['project'] = array();
		$data['project']['author'] = $this->_project_author;
		$data['project']['name'] = $this->_project_name;
		$data['project']['tagline'] = $this->_project_tagline;
		$data['project']['description'] = $this->_project_description;

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
		$this->load->view('themes/' . $this->_default_theme . '/' . $this->_name . '/login_form', $data);
	}

	public function index() {
		$this->login();
	}
	
	/* TODO: FIXME: This function shall be migrated into Ndphp model to be used on ND_Controller __construct
	 * and ND_Users hooks (when user data is updated, session data must be refreshed).
	 */
	private function session_setup($user_id = NULL, $username = NULL, $plain_password = NULL, $email = NULL, $first_name = NULL, $photo = NULL) {
		/* Sanity checks */
		if (!$user_id || !$username || !$plain_password || !$email)
			$this->response->code('403', 'session_setup(): ' . NDPHP_LANG_MOD_MISSING_REQUIRED_ARGS, $this->_default_charset, !$this->request->is_ajax());

		if ($this->request->remote_addr() == 'Unspecified')
			$this->response->code('403', 'session_setup(): ' . NDPHP_LANG_MOD_MISSING_REMOTE_ADDRESS, $this->_default_charset, !$this->request->is_ajax());

		/* Check if $photo is json encoded */
		if ($photo) {
			$photo_obj = json_decode($photo, true);

			if ($photo_obj !== NULL)
				$photo = $photo_obj;
		}

		/* Get user's roles */
		$this->db->select('roles.id,roles.is_admin,roles.is_superuser');
		$this->db->from('users');
		$this->db->join($this->_table_rel_roles, $this->_table_users . '.id = ' . $this->_table_rel_roles . '.users_id', 'left');
		$this->db->join($this->_table_roles, $this->_table_rel_roles . '.roles_id = ' . $this->_table_roles . '.id', 'left');
		$this->db->where($this->_table_users . '.id', $user_id);
		$query = $this->db->get();

		/* Check if there are any roles assigned to this user */
		if (!$query->num_rows())
			$this->response->code('401', 'session_setup(): ' . NDPHP_LANG_MOD_ACCESS_FORBIDDEN, $this->_default_charset, !$this->request->is_ajax());

		/* Fetch roles */
		$roles = $query->result_array();

		/* Initialize user role variables */
		$user_roles = array();
		$user_admin = false;
		$user_superuser = false;

		foreach ($roles as $role) {
			array_push($user_roles, $role['id']);

			/* Check if this is an admin role */
			if ($role['is_admin'])
				$user_admin = true;

			/* Check if this is a superuser role */
			if ($role['is_superuser'])
				$user_superuser = true;
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

		if (!$query->num_rows())
			$this->response->code('500', NDPHP_LANG_MOD_UNABLE_FETCH_CRIT_DATA_DBMS, $this->_default_charset, !$this->request->is_ajax());

		$pek_row = $query->row_array();

		/* Decrypt the stored key with the user's plain password */
		$privenckey = $this->encrypt->decrypt($pek_row['privenckey'], $plain_password, false);

		if (strlen($privenckey) != 256)
			$this->response->code('500', NDPHP_LANG_MOD_INVALID_PRIV_ENC_KEY, $this->_default_charset, !$this->request->is_ajax());

		/* Regenerate user session */
		$this->session->regenerate();

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
				'photo' => $this->field->mangle_file($photo, $user_id),
				'timezone' => $timezone,
				'database' => $database,
				'roles' => $user_roles,
				'is_admin' => $user_admin,
				'is_superuser' => $user_superuser,
				'privenckey' => bin2hex($privenckey),
				'logged_in' => true,
				'sessions_id' => 0, /* Will be set when session table is queried */
				'_apicall' => (strstr($this->request->header('Accept'), 'application/json') !== false)
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
				'ip_address' => $this->request->remote_addr(),
				'user_agent' => $this->request->header('User-Agent') ? $this->request->header('User-Agent') : 'Unspecified',
				'last_login' => date('Y-m-d H:i:s'),
				'users_id' => $user_id
			));

			/* Update $sessions_id */
			$row = $q->row_array();
			$sessions_id = $row['id'];
		} else {
			/* The session doesn't exist... Unauthorized */
			$this->response->code('401', NDPHP_LANG_MOD_ATTN_NO_SESSION_FOUND, $this->_default_charset, !$this->request->is_ajax());
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

	public function authenticate() {
		/* Check if this is a JSON encoded request. If so, replace POST data with JSON data */
		if ($this->request->is_json()) {
			/* Fetch JSON data */
			$json_req = $this->request->json();

			/* If JSON data exists and is valid, replace POST data with it */
			if ($json_req)
				$this->request->post_set_all($json_req);
		}

		/* Load pre plugins */
		foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/authenticate_pre.php') as $plugin)
			include($plugin);

		/* Check if this is an authentication based on api key */
		if (!isset($_POST['username'])) {
			$this->response->code('403', NDPHP_LANG_MOD_MISSING_AUTH_METHOD, $this->_default_charset, !$this->request->is_ajax());
		} else {
			/* Retrive username information from database */
			$this->db->where('username', $_POST['username']);
			$query = $this->db->get($this->_table_users);
			$row = $query->row_array();

			/* Validade if user exists */
			if (!$row)
				$this->response->code('403', NDPHP_LANG_MOD_INVALID_USER_OR_PASSWORD, $this->_default_charset, !$this->request->is_ajax());

			/* Check encryption algorithm */
			if (substr($row['password'], 0, 7) == '$2y$10$') {
				/* Crypt Blowfish */
				$passwd_digest = crypt($_POST['password'], substr($row['password'], 0, 29));
			} else if (strlen($row['password']) == 128) {
				/* SHA512 (deprecated... still here for backward compatibility) */
				$passwd_digest = openssl_digest($_POST['password'], 'sha512');
			} else {
				/* Unrecognized hash */
				$this->response->code('403', NDPHP_LANG_MOD_ATTN_CONTACT_SUPPORT, $this->_default_charset, !$this->request->is_ajax());
			}

			/* Validade password */
			if ($passwd_digest != $row['password'])
				$this->response->code('403', NDPHP_LANG_MOD_INVALID_USER_OR_PASSWORD, $this->_default_charset, !$this->request->is_ajax());
		}

		/* Check if account is active */
		if ($row['active'] != 1)
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_ACCT_INACTIVE . ' ' . NDPHP_LANG_MOD_ATTN_CONTACT_SUPPORT, $this->_default_charset, !$this->request->is_ajax());

		/* Check if account is locked */
		if ($row['locked'] != 0)
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_ACCT_LOCKED . ' ' . NDPHP_LANG_MOD_ATTN_CONTACT_SUPPORT, $this->_default_charset, !$this->request->is_ajax());

		/* Check if account is expired */
		if ($row['expire']) {
			$expired_date = strtotime($row['expire']);
			$current_date = time();

			if ($current_date >= $expired_date)
				$this->response->code('403', NDPHP_LANG_MOD_ACCESS_ACCT_EXPIRED . ' ' . NDPHP_LANG_MOD_ATTN_CONTACT_SUPPORT, $this->_default_charset, !$this->request->is_ajax());
		}

		/* Validade email address (regex) */		
		if (validate_email($row['email']) == false)
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_ACCT_INVALID_EMAIL . ' ' . NDPHP_LANG_MOD_ATTN_CONTACT_SUPPORT, $this->_default_charset, !$this->request->is_ajax());

		/**** All sanity checks successfully passed ****/

		/* Setup user session */
		$this->session_setup($row['id'], $row['username'], $_POST['password'], $row['email'], $row['first_name'], $row['_file_photo']);

		/* Check if we're under maintenance mode */
		if ($this->_maintenance_enabled && !$this->security->im_admin())
			$this->response->code('403', NDPHP_LANG_MOD_MGMT_UNDER_MAINTENANCE, $this->_default_charset, !$this->request->is_ajax());

		/* If logging is enabled, log this LOGIN entry */
		$this->logging->log(
			/* op         */ 'LOGIN',
			/* table      */ 'users',
			/* field      */ NULL,
			/* entry_id   */ NULL,
			/* value_new  */ NULL,
			/* value_old  */ NULL,
			/* session_id */ $this->session->userdata('sessions_id'),
			/* user_id    */ $this->session->userdata('user_id'),
			/* log it?    */ $this->_logging
		);		

		/* Load post plugins */
		foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/authenticate_post.php') as $plugin)
			include($plugin);

		/* User is authenticated... redirecting to application contents */
		if ($_POST['referer']) {
			/* TODO: FIXME: If the referer is the login page itself, we should ignore it and redirect to / instead...
			 * Otherwise we'll have 2 consecutive logins and the session ID will be regenetared twice (waste of time and resources).
			 */
			redirect($this->ndphp->safe_b64decode($_POST['referer']), false, true); /* Full URL redirect */
		} else {
			if ($this->request->is_json()) {
				$data['status'] = true;
				$data['data']['user_id'] = intval($row['id']);
				$data['data']['session_id'] = $this->session->userdata('sessions_id');
				$data['data']['timezone'] = $this->session->userdata('timezone');
				$data['data']['photo'] = $this->session->userdata('photo');
				$data['data']['roles'] = $this->session->userdata('roles');
				$data['data']['is_admin'] = $this->session->userdata('is_admin');
				$data['data']['is_superuser'] = $this->session->userdata('is_superuser');
				$data['data']['apikey'] = $row['apikey'];

				$this->response->header('Content-Type', 'application/json');
				$this->response->output(json_encode($data));
			} else {
				redirect('/');
			}
		}
	}

	public function logout() {
		/* If logging is enabled, log this LOGOUT request */
		$this->logging->log(
			/* op         */ 'LOGOUT',
			/* table      */ 'users',
			/* field      */ NULL,
			/* entry_id   */ NULL,
			/* value_new  */ NULL,
			/* value_old  */ NULL,
			/* session_id */ $this->session->userdata('sessions_id'),
			/* user_id    */ $this->session->userdata('user_id'),
			/* log it?    */ $this->_logging
		);

		/* Clear user session dataa */
		$this->session->set_userdata(array(
			'logged_in' => false
		));

		$this->session->cleanup();
		$this->session->destroy();

		/* If this was a JSON request ... */
		if ($this->request->is_json()) {
			/* Reply with JSON data */
			$data['status'] = true;
			$data['data']['logout'] = true;
			$this->response->header('Content-Type', 'application/json');
			$this->response->output(json_encode($data));
		} else {
			/* Otherwise redirect to base URL */
			redirect('/');
		}
	}
}
