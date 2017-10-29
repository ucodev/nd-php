<?php if (!defined('FROM_BASE')) { header($_SERVER['SERVER_PROTOCOL'] . ' 403'); die('Invalid requested path.'); }

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

/*
 * TODO:
 *
 * - alt tags on input fields are missing (required for accessibility)
 * - A major cleanup/redesign of this controller is required, but probably it'll be cleaned up gradually over the next releases.
 *
 */

class ND_Register extends UW_Controller {
	protected $_author = "ND PHP Framework";	// Project Author
	protected $_project_name = "ND php";
	protected $_tagline = "Framework";
	protected $_description = "An handy PHP Framework";
	protected $_default_timezone = NDPHP_LANG_MOD_DEFAULT_TIMEZONE;
	protected $_default_database = 'default';
	protected $_theme = 'Blueish';
	protected $_base_url = 'http://localhost/ndphp/';
	protected $_temp_dir = '/tmp/';
	protected $_charset = NDPHP_LANG_MOD_DEFAULT_CHARSET;
	protected $_logging = true;
	protected $_accounting = true;

	protected $_word_true = NDPHP_LANG_MOD_WORD_TRUE;
	protected $_word_false = NDPHP_LANG_MOD_WORD_FALSE;

	private $nd_app_base_url = 'https://localhost/ndphp';
	private $nd_username_safe_chars = "a-zA-Z0-9_\-\.";
	private $roles_regular_id = 4;	/* Regular user roles_id (for newly registered users) */
	private $default_countries_id = 242; /* None */
	private $default_currencies_id = 1;
	private $default_timezones_id = 383;
	private $default_genders_id = 3;
	private $external_confirm = true; /* If the accounts are confirmed by an external module or service, preventing date_confirmed from being updated by this controller  */


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

	protected function _get_features() {
		return $this->features->get_features();
	}

	/* Constructor */
	public function __construct()
	{
		parent::__construct();

		/* Load configuration */
		$config = $this->configuration->get();

		$this->_base_url = base_url();
		$this->_author = $config['author'];
		$this->_project_name = $config['project_name'];
		$this->_tagline = $config['tagline'];
		$this->_description = $config['description'];
		$this->_default_timezone = $config['timezone'];
		$this->_theme = $config['theme'];
		$this->_temp_dir = $config['temporary_directory'];

		/* Check if we're under maintenance mode */
		if ($config['maintenance'] && !$this->security->im_admin())
			$this->response->code('503', NDPHP_LANG_MOD_MGMT_UNDER_MAINTENANCE, $this->_charset, !$this->request->is_ajax());

		/* Set base application url */
		$this->nd_app_base_url = base_url();

		/* Default role for newly registered users */
		$this->roles_regular_id = $config['roles_id'];

		/* Features */
		$features = $this->_get_features();

		/* Check if we're under multi or single user mode */
		if (!$features['multi_user'])
			/* If we're under single user mode, user registration is not available */
			$this->response->code('403', NDPHP_LANG_MOD_DISABLED_MULTI_USER, $this->_charset, !$this->request->is_ajax());
	}

	private function rand_string($length = 20) {
		$chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ\\{}()[]#$%&/!*+-';
		$rand_str = '';

		for ($i = 0; $i < $length; $i ++) {
			$rand_str .= $chars[mt_rand(0, strlen($chars) - 1)];
		}

		return $rand_str;
	}

	public function index() {
		$features = $this->_get_features();

		if (!$features['user_registration'])
			$this->response->code('403', NDPHP_LANG_MOD_DISABLED_USER_REGISTER, $this->_charset, !$this->request->is_ajax());

		$this->db->select('id,country,code');
		$this->db->from('countries');
		$this->db->order_by('country', 'asc');

		$query = $this->db->get();

		if (!$query->num_rows())
			$this->response->code('500', NDPHP_LANG_MOD_UNABLE_REGISTER_NEW_USERS, $this->_charset, !$this->request->is_ajax());

		$data = array();

		$data['config'] = array();
		$data['config']['author'] = $this->_author;
		$data['config']['charset'] = $this->_charset;
		$data['config']['features'] = $features;
		$data['config']['theme'] = $this->_get_theme();

		$data['project'] = array();
		$data['project']['author'] = $this->_author;
		$data['project']['name'] = $this->_project_name;
		$data['project']['tagline'] = $this->_tagline;
		$data['project']['description'] = $this->_description;

		$data['view'] = array();
		$data['view']['title'] = NDPHP_LANG_MOD_REGISTER_USER_REGISTRATION;
		$data['view']['description'] = NDPHP_LANG_MOD_REGISTER_USER_REGISTRATION;
		$data['view']['countries'] = $query;

		$this->load->view('themes/' . $this->_theme . '/' . 'register/register_form', $data);
	}

	public function country_get_code($id) {
		$this->db->select('code');
		$this->db->from('countries');
		$this->db->where('id', $id);
		$this->db->where('eu_state', '1');

		$query = $this->db->get();

		if (!$query->num_rows()) {
			echo('');
			return;
		}

		$row = $query->row_array();

		echo($row['code']);
	}

	protected function register_pre_process(&$POST) {
		return;
	}

	protected function register_post_process($users_id) {
		return;
	}

	public function newuser($ajax = 0) {
		/* Check if this is a JSON encoded request. If so, replace POST data with JSON data */
		if ($this->request->is_json()) {
			/* Fetch JSON data */
			$json_req = $this->request->json();

			/* If JSON data exists and is valid, replace POST data with it */
			if ($json_req)
				$this->request->post_set_all($json_req);
		}

		/* Invoke pre hook */
		$this->register_pre_process($_POST);

		/* Register user */
		$users_id = $this->newuser_protected($ajax);

		/* If logging is enabled, log this registration request */
		if ($this->_logging === true) {
			$this->logging->log(
				/* op         */ 'REGISTER',
				/* table      */ 'users',
				/* field      */ NULL,
				/* entry_id   */ NULL,
				/* value_new  */ NULL,
				/* value_old  */ NULL,
				/* session_id */ session_id(),
				/* user_id    */ $users_id,
				/* log it?    */ $this->_logging
			);
		}

		/* Invoke post hook */
		$this->register_post_process($users_id);
	}

	protected function newuser_protected($ajax = 0) {
		/* Validate username length */
		if (strlen($_POST['username']) > 32)
			$this->response->code('400', NDPHP_LANG_MOD_INVALID_USERNAME_TOO_LONG, $this->_charset, !$this->request->is_ajax());

		if (strlen($_POST['username']) < 5)
			$this->response->code('400', NDPHP_LANG_MOD_INVALID_USERNAME_TOO_SHORT, $this->_charset, !$this->request->is_ajax());

		/* Validate username characters */
		if (!preg_match('/^[' . $this->nd_username_safe_chars . ']+$/', $_POST['username']))
			$this->response->code('422', NDPHP_LANG_MOD_INVALID_USERNAME_CHARS, $this->_charset, !$this->request->is_ajax());

		/* Validate First Name */
		if (isset($_POST['first_name']) && preg_match("/^[^\ \<\>\%\'\\\"\.\,\;\:\~\^\`\{\[\]\}\?\!\#\&\/\(\)\=\|\\\*\+\-\_\@]+$/", $_POST['first_name']) === false)
			$this->response->code('422', NDPHP_LANG_MOD_INVALID_FIRST_NAME, $this->_charset, !$this->request->is_ajax());

		/* Validate Last Name */
		if (isset($_POST['last_name']) && preg_match("/^[^\ \<\>\%\'\\\"\.\,\;\:\~\^\`\{\[\]\}\?\!\#\&\/\(\)\=\|\\\*\+\-\_\@]+$/", $_POST['last_name']) === false)
			$this->response->code('422', NDPHP_LANG_MOD_INVALID_LAST_NAME, $this->_charset, !$this->request->is_ajax());

		/* Validate password */
		if (strlen($_POST['password']) < 8)
			$this->response->code('400', NDPHP_LANG_MOD_INVALID_PASSWORD_TOO_SHORT, $this->_charset, !$this->request->is_ajax());

		if (strlen($_POST['password']) > 32)
			$this->response->code('400', NDPHP_LANG_MOD_INVALID_PASSWORD_TOO_LONG, $this->_charset, !$this->request->is_ajax());

		if ($_POST['password'] != $_POST['password_check'])
			$this->response->code('400', NDPHP_LANG_MOD_INFO_PASSWORD_NO_MATCH, $this->_charset, !$this->request->is_ajax());

		if (!isset($_POST['terms']) || ($_POST['terms'] != '1'))
			$this->response->code('400', NDPHP_LANG_MOD_ATTN_READ_ACCEPT_TERMS, $this->_charset, !$this->request->is_ajax());

		/* Check username availability */
		$this->db->select('id');
		$this->db->from('users');
		$this->db->where('username', $_POST['username']);
		$query = $this->db->get();

		if ($query->num_rows())
			$this->response->code('409', NDPHP_LANG_MOD_INFO_TAKEN_USERNAME, $this->_charset, !$this->request->is_ajax());

		/* Validate country */
		if (!isset($_POST['countries_id'])) {
			/* If countries_id is not set, assume the default countries_id */
			$_POST['countries_id'] = $this->default_countries_id;
		}

		$this->db->select('id,code,eu_state');
		$this->db->from('countries');
		$this->db->where('id', intval($_POST['countries_id']));

		$query = $this->db->get();

		if (!$query->num_rows())
			$this->response->code('400', NDPHP_LANG_MOD_INVALID_COUNTRY, $this->_charset, !$this->request->is_ajax());

		$row = $query->row_array();

		/* TODO: FIXME: What is this used for? */
		$countries_id = $row['id'];
		$country_code = $row['code'];

		/* Validate email */
		if (validate_email($_POST['email']) === false)
			$this->response->code('422', NDPHP_LANG_MOD_INVALID_EMAIL, $this->_charset, !$this->request->is_ajax());

		$this->db->select('id');
		$this->db->from('users');
		$this->db->where('email', $_POST['email']);
		$query = $this->db->get();

		if ($query->num_rows())
			$this->response->code('409', NDPHP_LANG_MOD_INFO_EMAIL_REGISTERED, $this->_charset, !$this->request->is_ajax());

		/* Setup user data row */
		if (isset($_POST['first_name']))
			$userdata['first_name'] = $_POST['first_name'];

		if (isset($_POST['last_name']))
			$userdata['last_name'] = $_POST['last_name'];

		if (isset($_POST['birthdate']))
			$userdata['birthdate'] = $_POST['birthdate'];

		$userdata['username'] = $_POST['username'];
		$userdata['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT, array('cost' => 10));
		$userdata['privenckey'] = $this->encrypt->encrypt(openssl_random_pseudo_bytes(256), $userdata['password'], false);
		$userdata['apikey'] = openssl_digest(openssl_random_pseudo_bytes(256), 'sha1');
		$userdata['email'] = $_POST['email'];

		if (isset($_POST['phone']))
			$userdata['phone'] = $_POST['phone'];

		if (isset($_POST['company']) && ($_POST['company']))
			$userdata['company'] = $_POST['company'];

		if (isset($_POST['brand']) && ($_POST['brand']))
			$userdata['brand'] = $_POST['brand'];

		if (isset($_POST['vat']))
			$userdata['vat'] = $_POST['vat'];

		$userdata['countries_id'] = $_POST['countries_id']; /* TODO: FIXME: needs validation. UPDATE: Already validated at the beginning of this call */

		if (isset($_POST['timezones_id'])) {
			$userdata['timezones_id'] = intval($_POST['timezones_id']); /* TODO: FIXME: needs validation */
		} else {
			$userdata['timezones_id'] = $this->default_timezones_id;
		}

		if (isset($_POST['currencies_id'])) {
			$userdata['currencies_id'] = intval($_POST['currencies_id']); /* TODO: FIXME: needs validation */
		} else {
			$userdata['currencies_id'] = $this->default_currencies_id;
		}

		if (isset($_POST['genders_id'])) {
			$userdata['genders_id'] = intval($_POST['genders_id']); /* TODO: FIXME: needs validation */
		} else {
			$userdata['genders_id'] = $this->default_genders_id;
		}

		if (isset($_POST['website']))
			$userdata['website'] = $_POST['website'];

		if (isset($_POST['about']))
			$userdata['about'] = $_POST['about'];

		if (isset($_POST['code']))
			$userdata['registration_code'] = $_POST['code'];

		$userdata['active'] = 0;
		$userdata['locked'] = 0;

		/* TODO: FIXME: This should be configurable */
		//$userdata['expire'] = date('Y-m-d H:m:i', strtotime("+7 days"));
		$userdata['expire'] = '2030-12-31 23:59:59';

		$userdata['registered'] = date('Y-m-d H:i:s');

		$data['config'] = array();
		$data['config']['author'] = $this->_author;
		$data['config']['charset'] = $this->_charset;
		$data['config']['theme'] = $this->_get_theme();

		$data['project'] = array();
		$data['project']['author'] = $this->_author;
		$data['project']['name'] = $this->_project_name;
		$data['project']['tagline'] = $this->_tagline;
		$data['project']['description'] = $this->_description;

		$data['view'] = array();
		$data['view']['title'] = NDPHP_LANG_MOD_REGISTER_CONFIRM_EMAIL_STATUS;
		$data['view']['description'] = NDPHP_LANG_MOD_REGISTER_CONFIRM_EMAIL_STATUS;

		$this->db->trans_begin();

		$this->db->insert('users', $userdata);
		$users_id = $this->db->last_insert_id(); /* Must be called before trans_status() */

		if ($this->db->trans_status() === false) {
			error_log('register.php: newuser(): Insert failed.');
			$this->db->trans_rollback();
			$this->response->code('500', NDPHP_LANG_MOD_FAILED_TRANSACTION, $this->_charset, !$this->request->is_ajax());
		} else {
			$users_id_enc = urlencode($this->ndphp->safe_b64encode($this->encrypt->encode($users_id . '.' . mt_rand(100000, 999999))));

			$this->db->trans_commit();

			$res = $this->user_try_unlock($users_id);

			if ($res === true) {
				$this->user_active_process($users_id);

				if (strstr($this->request->header('accept'), 'application/json') !== false) {
					$data['status'] = true;
					$data['data']['user_id'] = $users_id;
					$data['data']['registered'] = true;

					$this->response->header('content-type', 'application/json');
					$this->response->output(json_encode($data));
				} else {
					/* This should be a view */
					echo('<br />' . NDPHP_LANG_MOD_REGISTER_USER_ACCT_IS_NOW . ' <span style="font-weight: bold">' . NDPHP_LANG_MOD_WORD_ACTIVE_F . '</span>.<br />');
					echo('<br /><br /><center><a href="' . base_url() . '/index.php/login" class="register_button_link">' . NDPHP_LANG_MOD_LOGIN_LOGIN . '</a></center>');
				}
			} else {
				$this->response->code('500', NDPHP_LANG_MOD_UNABLE_ACTIVATED_ACCOUNT . ' ' . NDPHP_LANG_MOD_ATTN_CONTACT_SUPPORT . ' #1', $this->_charset, !$this->request->is_ajax());
			}
		}

		return $users_id;
	}

	public function recover_password_form() {
		$features = $this->_get_features();

		if (!$features['user_recovery'])
			$this->response->code('500', NDPHP_LANG_MOD_DISABLED_USER_PASS_RECOVER, $this->_charset, !$this->request->is_ajax());

		$this->db->select('id,country,code');
		$this->db->from('countries');
		$this->db->order_by('country', 'asc');

		$query = $this->db->get();

		if (!$query->num_rows())
			$this->response->code('500', NDPHP_LANG_MOD_UNABLE_RECOVER_CREDENTIALS, $this->_charset, !$this->request->is_ajax());

		$data = array();

		$data['config'] = array();
		$data['config']['author'] = $this->_author;
		$data['config']['charset'] = $this->_charset;
		$data['config']['features'] = $features;
		$data['config']['theme'] = $this->_get_theme();
		$data['config']['use_recaptcha'] = $this->register_with_recaptcha;
		$data['config']['recaptcha_public_key'] = $this->recaptcha_public_key;

		$data['project'] = array();
		$data['project']['author'] = $this->_author;
		$data['project']['name'] = $this->_project_name;
		$data['project']['tagline'] = $this->_tagline;
		$data['project']['description'] = $this->_description;

		$data['view'] = array();
		$data['view']['title'] = NDPHP_LANG_MOD_REGISTER_USER_REGISTRATION;
		$data['view']['description'] = NDPHP_LANG_MOD_REGISTER_USER_REGISTRATION;
		$data['view']['countries'] = $query;

		$this->load->view('themes/' . $this->_theme . '/' . 'register/recover_password', $data);
	}

	public function recover_password() {
		if ($this->register_with_recaptcha == '1') {
			$res = recaptcha_check_answer($this->recaptcha_private_key, $_SERVER['REMOTE_ADDR'], $_POST['recaptcha_challenge_field'], $_POST['recaptcha_response_field']);

			if (!$res->is_valid) {
				header($_SERVER['SERVER_PROTOCOL'] . ' 403');
				die(NDPHP_LANG_MOD_INVALID_RECAPTCHA_VALUE);
			}
		}

		if (!isset($_POST['email'])) {
			$this->response->code('403', NDPHP_LANG_MOD_MISSING_EMAIL, $this->_charset, !$this->request->is_ajax());
		} else {
			$email = $_POST['email'];
		}

		if (!isset($_POST['first_name'])) {
			$this->response->code('403', NDPHP_LANG_MOD_MISSING_FIRST_NAME, $this->_charset, !$this->request->is_ajax());
		} else {
			$first_name = $_POST['first_name'];
		}

		if (!isset($_POST['last_name'])) {
			$this->response->code('403', NDPHP_LANG_MOD_MISSING_LAST_NAME, $this->_charset, !$this->request->is_ajax());
		} else {
			$last_name = $_POST['last_name'];
		}

		if (!isset($_POST['countries_id']) || !$_POST['countries_id'] || ($_POST['countries_id'] == 242)) {
			$this->response->code('403', NDPHP_LANG_MOD_MISSING_VALID_COUNTRY, $this->_charset, !$this->request->is_ajax());
		} else {
			$countries_id = $_POST['countries_id'];
		}

		$this->db->select('id,username');
		$this->db->from('users');
		$this->db->where('email', $email);
		$this->db->where('first_name', $first_name);
		$this->db->where('last_name', $last_name);
		$this->db->where('countries_id', $countries_id);

		$query = $this->db->get();

		if (!$query->num_rows()) {
			error_log('recover_password(): No data match.');
			$this->response->code('403', NDPHP_LANG_MOD_REGISTER_NO_DATA_MATCH, $this->_charset, !$this->request->is_ajax());
		}

		$rawdata = $query->row_array();

		if ($rawdata['id'] == 1) {
			error_log('recover_password(): No data match.');
			$this->response->code('403', NDPHP_LANG_MOD_REGISTER_NO_DATA_MATCH, $this->_charset, !$this->request->is_ajax());
		}

		$plain_password = $this->rand_string(24);
		$userdata['password'] = password_hash($plain_password, PASSWORD_BCRYPT, array('cost' => 10));

		$this->db->where('id', $rawdata['id']);
		$this->db->update('users', $userdata);

		$this->send_credentials_email($email, $first_name, $last_name, $rawdata['username'], $plain_password);

		echo('<br />' . NDPHP_LANG_MOD_REGISTER_EMAIL_RECOVER_INFO . '<br />');
		echo('<br /><br /><center><a href="' . base_url() . '/index.php/login" class="register_button_link">' . NDPHP_LANG_MOD_LOGIN_LOGIN . '</a></center>');
	}

	private function user_active_process($users_id) {
		/* Update user data */
		$userdata['users_id'] = $users_id;
		$userdata['acct_last_reset'] = date('Y-m-d H:i:s');
		$userdata['expire'] = '2030-12-31 23:59:59';
		$userdata['active'] = 1;

		$this->db->trans_begin();

		$this->db->where('id', $users_id);
		$this->db->update('users', $userdata);

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			error_log('register.php: user_active_process(): Unable to update users_id on User ID: ' . $users_id);
			$this->response->code('500', NDPHP_LANG_MOD_UNABLE_UPDATE_TABLE_USERS, $this->_charset, !$this->request->is_ajax());
		} else {
			$this->db->trans_commit();
		}

		/* Setup roles */
		$roledata['users_id'] = $users_id;
		$roledata['roles_id'] = $this->roles_regular_id;

		$this->db->trans_begin();

		$this->db->insert('rel_users_roles', $roledata);

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			error_log('register.php: user_active_process(): Failed to update rel_users_roles on User ID: ' . $users_id);
			$this->response->code('500', NDPHP_LANG_MOD_FAILED_UPDATE_USER_ROLES, $this->_charset, !$this->request->is_ajax());
		} else {
			$this->db->trans_commit();
		}

		/* Apply registration codes, if any */
		$this->db->trans_begin();

		$this->db->select('registration_code');
		$this->db->from('users');
		$this->db->where('id', $users_id);

		$q = $this->db->get();

		$user_row = $q->row_array();

		/* If there is a registration code ... */
		if ($user_row['registration_code']) {
			$this->db->select('id,roles_id,remaining');
			$this->db->from('codes');
			$this->db->where('codes_types_id', 1);
			$this->db->where('code', $user_row['registration_code']);
			$this->db->where('remaining >=', 1);
			$q = $this->db->get();

			$code_row = array();

			/* Check if the code matches an existing code... */
			if ($q->num_rows()) {
				/* And apply the corresponding role to this user */
				$code_row = $q->row_array();

				$roledata['users_id'] = $users_id;
				$roledata['roles_id'] = $code_row['roles_id'];

				$this->db->insert('rel_users_roles', $roledata);
			} else {
				$this->db->trans_rollback();
				error_log('register.php: user_active_process(): The registration code does not exist or is no longer valid. (User ID: ' . $users_id . ').');
				$this->response->code('403', NDPHP_LANG_MOD_INVALID_REGISTRATION_CODE, $this->_charset, !$this->request->is_ajax());
			}
		}

		/* Decrement the remaning code count */
		if (isset($code_row['id']) && $code_row['id']) {
			$this->db->where('id', $code_row['id']);
			$this->db->update('codes', array(
				'remaining' => ($code_row['remaining'] - 1)
			));
		}

		/* Set the user registration code status to 'true' */
		$this->db->where('id', $users_id);
		$this->db->update('users', array(
			'registration_code_status' => true
		));

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			error_log('register.php: user_active_process(): Failed to update registration code logic for User ID: ' . $users_id . ' #2.');
			$this->response->code('500', NDPHP_LANG_MOD_FAILED_REGISTRATION_CODE, $this->_charset, !$this->request->is_ajax());
		}

		/* All good */
		$this->db->trans_commit();
	}

	private function user_try_unlock($users_id) {
		$this->db->select('email_confirmed,phone_confirmed');
		$this->db->from('users');
		$this->db->where('id', $users_id);

		$query = $this->db->get();

		if (!$query->num_rows())
			return false;

		$row = $query->row_array();

		if (($this->register_confirm_email == 1) && ($this->register_confirm_phone == 1)) {
			if (($row['email_confirmed'] != 1) || ($row['phone_confirmed'] != 1))
				return false;
		} else if ($this->register_confirm_email == 1) {
			if ($row['email_confirmed'] != 1)
				return false;
		} else if ($this->register_confirm_phone == 1) {
			if ($row['phone_confirmed'] != 1)
				return false;
		}

		$userdata['active'] = 1;
		$userdata['locked'] = 0;

		if (!$this->external_confirm)
			$userdata['date_confirmed'] = date('Y-m-d H:i:s');

		$userdata['expire'] = (date('Y') + 1) . '-' . date('m-d H:i:s'); // 1 year active before re-confirmation. FIXME: TODO: This shall be increased

		$this->db->trans_begin();

		$this->db->where('id', $users_id);
		$this->db->update('users', $userdata);

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			error_log('register.php: user_try_unlock(): Failed to update user lock on User ID: ' . $users_id);
			$this->response->code('500', NDPHP_LANG_MOD_FAILED_TRANSACTION, $this->_charset, !$this->request->is_ajax());
		} else {
			$this->db->trans_commit();
		}

		return true;
	}
}

