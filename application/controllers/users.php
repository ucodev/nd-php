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

/**
 * Notes:
 *
 * - Field users.id and users.users_id must always match on users table for each row.
 *
 */

class Users extends ND_Controller {
	/* Constructor */
	public function __construct($session_enable = true, $json_replies = false) {
		parent::__construct($session_enable, $json_replies);

		/* Initialize controller */
		$this->_init(get_class(), true);

		/* TODO: FIXME: If sharding is enabled, we must load the main database here ('default') and then
		 * grant that all changes are also replicated to the user shard.
		 */
	}

	/** Hooks **/
	protected function _hook_insert_pre(&$POST, &$fields) {
		/* Create a hook context */
		$hook_pre_return = array();

		$features = $this->get->features();

		/* Check if we're under multi or single user mode */
		if (!$features['multi_user']) {
			/* If we're under single user mode, user registration is not available */
			$this->response->code('403', NDPHP_LANG_MOD_DISABLED_MULTI_USER, $this->config['default_charset'], !$this->request->is_ajax());
		}

		/* Check if the username is at least 5 characters long */
		if (strlen($this->request->post('username')) < 5) {
			$this->response->code('400', NDPHP_LANG_MOD_INVALID_USERNAME_TOO_SHORT, $this->config['default_charset'], !$this->request->is_ajax());
		}

		/* Validate roles */
		if ($this->request->post_isset('rel_users_roles') && count($this->request->post('rel_users_roles'))) {
			/* Check new roles */
			$this->db->select('id,is_admin,is_superuser');
			$this->db->from('roles');
			$this->db->where_in('id', $this->request->post('rel_users_roles'));
			$q = $this->db->get();

			if ($q->num_rows()) {
				foreach ($q->result_array() as $row) {
					/* Only ROLE_ADMIN (superadmin) can insert the superadmin role (ROLE_ADMIN). */
					if (($row['id'] == 1) && !$this->security->im_superadmin())
						$this->response->code('403', NDPHP_LANG_MOD_CANNOT_USERS_INSERT_ROLE_ADMIN, $this->config['default_charset'], !$this->request->is_ajax());

					/* Only ROLE_ADMIN (superadmin) can insert admin roles. */
					if ($row['is_admin'] && !$this->security->im_superadmin())
						$this->response->code('403', NDPHP_LANG_MOD_CANNOT_USERS_INSERT_ADMIN_ROLES, $this->config['default_charset'], !$this->request->is_ajax());

					/* Only admin users can insert superuser roles. */
					if ($row['is_superuser'] && !$this->security->im_admin())
						$this->response->code('403', NDPHP_LANG_MOD_CANNOT_USERS_INSERT_SUPERUSER_ROLES, $this->config['default_charset'], !$this->request->is_ajax());
				}
			}
		}

		/* Generate user's private key for encryption
		 *
		 * This key will be a pseudo random string with 256 bytes of length.
		 * It'll be encrypted with the user's password.
		 * Each time the user logs in, the private key is deciphered with the plain password used for authentication
		 * and the decrypted key will be stored as a session variable.
		 *
		 */
		$hook_pre_return['privenckey'] = $this->encrypt->encrypt(openssl_random_pseudo_bytes(256), $this->request->post('password'), false);

		/* Convert password to hash */
		$this->request->post_set('password', password_hash($this->request->post('password'), PASSWORD_BCRYPT, array('cost' => 10)));

		/* Return the hook context */
		return $hook_pre_return;
	}

	protected function _hook_insert_post(&$id, &$POST, &$fields, $hook_pre_return) {
		/* Grant that users_id is set */
		$this->db->trans_begin();

		$this->db->where('users.id', $id);
		$this->db->update('users', array(
			'users_id' => $id,
			'privenckey' => $hook_pre_return['privenckey'],
		));

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();

			/* Try to delete the newly inserted user */
			$this->db->delete('users', array('id' => $id));

			$this->response->code('500', NDPHP_LANG_MOD_FAILED_UPDATE_USER_DATA, $this->config['default_charset'], !$this->request->is_ajax());
		} else {
			$this->db->trans_commit();
		}
	}

	protected function _hook_update_pre(&$id, &$POST, &$fields) {
		/* Create a hook context */
		$hook_pre_return = array();

		/* Block any attempt to remove ROLE_ADMIN from $id == 1 */
		if (($id == 1) && ($this->request->post_isset('rel_users_roles') && !in_array(1, $this->request->post('rel_users_roles'))))
			$this->response->code('403', NDPHP_LANG_MOD_CANNOT_ADMIN_USER_NO_ADMIN, $this->config['default_charset'], !$this->request->is_ajax());

		/* Check if the username is at least 5 characters long */
		if ($this->request->post_isset('username') && strlen($this->request->post('username')) < 5) {
			$this->response->code('400', NDPHP_LANG_MOD_INVALID_USERNAME_TOO_SHORT, $this->config['default_charset'], !$this->request->is_ajax());
		}

		/* Validate roles */
		if ($this->request->post_isset('rel_users_roles') && count($this->request->post('rel_users_roles'))) {
			/* Check new roles */
			$this->db->select('id,is_admin,is_superuser');
			$this->db->from('roles');
			$this->db->where_in('id', $this->request->post('rel_users_roles'));
			$q = $this->db->get();

			if ($q->num_rows()) {
				foreach ($q->result_array() as $row) {
					/* Only ROLE_ADMIN (superadmin) can add special superdamin roles (ROLE_ADMIN) to users */
					if (($row['id'] == 1) && !$this->security->im_superadmin())
						$this->response->code('403', NDPHP_LANG_MOD_CANNOT_USERS_ADD_ROLE_ADMIN, $this->config['default_charset'], !$this->request->is_ajax());

					/* Only ROLE_ADMIN (superadmin) can add admin roles to users */
					if ($row['is_admin'] && !$this->security->im_superadmin())
						$this->response->code('403', NDPHP_LANG_MOD_CANNOT_USERS_ADD_ADMIN_ROLES, $this->config['default_charset'], !$this->request->is_ajax());

					/* Only admin users can add superuser roles to users */
					if ($row['is_superuser'] && !$this->security->im_admin())
						$this->response->code('403', NDPHP_LANG_MOD_CANNOT_USERS_ADD_SUPERUSER_ROLES, $this->config['default_charset'], !$this->request->is_ajax());
				}
			}

			/* Check removed roles */
			$this->db->select('roles.id,roles.is_admin,roles.is_superuser');
			$this->db->from('roles');
			$this->db->join('rel_users_roles', 'rel_users_roles.roles_id = roles.id', 'left');
			$this->db->join('users', 'rel_users_roles.users_id = users.id', 'left');
			$this->db->where('users.id', $id);
			$this->db->where_not_in('roles.id', $this->request->post('rel_users_roles'));
			$q = $this->db->get();

			if ($q->num_rows()) {
				foreach ($q->result_array() as $row) {
					/* Only ROLE_ADMIN (superadmin) can manage ROLE_ADMIN on users with superadmin role (ROLE_ADMIN) */
					if (($row['id'] == 1) && !$this->security->im_superadmin())
						$this->response->code('403', NDPHP_LANG_MOD_CANNOT_USERS_MANAGE_ROLE_ADMIN, $this->config['default_charset'], !$this->request->is_ajax());

					/* Only ROLE_ADMIN (superadmin) can manage admin roles on users with admin roles */
					if ($row['is_admin'] && !$this->security->im_superadmin())
						$this->response->code('403', NDPHP_LANG_MOD_CANNOT_USERS_MANAGE_ADMIN_ROLES, $this->config['default_charset'], !$this->request->is_ajax());

					/* Only admin users can manage superuser roles on users with superuser roles */
					if ($row['is_superuser'] && !$this->security->im_admin())
						$this->response->code('403', NDPHP_LANG_MOD_CANNOT_USERS_MANAGE_SUPERUSER_ROLES, $this->config['default_charset'], !$this->request->is_ajax());
				}
			}
		}

		/* If password was changed ... */
		if ($this->request->post_isset('password')) {
			$this->db->select('password,privenckey');
			$this->db->from($this->config['name']);
			$this->db->where('id', $id);
			$query = $this->db->get();
			$row = $query->row_array();

			if ($row['password'] != $this->request->post('password')) {
				/* WARNING: If we're updating the password via REST API, we need to grant that the call provided the plain password
				 * for authentication (in the '_password' JSON request field) in addition to the API KEY. If not, the privenckey
				 * session variable is NULL and thus we cannot change the user password (or he will never access the private encrypted
				 * data again).
				 */

				$privenckey = hex2bin($this->config['session_data']['privenckey']);

				if (strlen($privenckey) != 256) {
					/* As stated, if the deciphered private encryption key doesn't seem right, we won't allow the password
					 * to be changed.
					 */
					$this->response->code('401', NDPHP_LANG_MOD_ATTN_INSUFFICIENT_CREDS .': ' . strlen($privenckey), $this->config['default_charset'], !$this->request->is_ajax());
				}

				/* Re-encrypt the user private encryption key with the new password */
				$hook_pre_return['privenckey'] = $this->encrypt->encrypt($privenckey, $this->request->post('password'), false);
				$hook_pre_return['old_password'] = $row['password'];

				/* hash new password */
				$this->request->post_set('password', password_hash($this->request->post('password'), PASSWORD_BCRYPT, array('cost' => 10)));
			} else {
				$this->request->post_unset('password');
			}
		}

		/* Grant that users_id is set */
		$this->request->post_set('users_id', $id);

		/* Return the hook context */
		return $hook_pre_return;
	}

	protected function _hook_update_post(&$id, &$POST, &$fields, $hook_pre_return) {
		/* Update privenckey, if necessary */
		if (isset($hook_pre_return['privenckey'])) {
			$this->db->trans_begin();

			$this->db->where('users.id', $id);
			$this->db->update('users', array(
				'privenckey' => $hook_pre_return['privenckey']
			));

			if ($this->db->trans_status() === false) {
				/* TODO: FIXME: This is really, really critical. User won't be able to retrieve any encrypted data if we're
				 * unable to store the newly encrypted private encryption key.
				 *
				 * If we're here... we must revert the password hash to the old one (set at $hook_pre_return['old_password'])),
				 * but if the connection to the database was lost and we can't recover it, we may be unable to do so...
				 *
				 * We should find a way to include the privenckey as part of the main update transaction. Currently this is not
				 * possible because if we set the privenckey as a POST field, it'll be ignored as there are no permissions for the
				 * user to be able to update it...
				 *
				 */
				$this->db->trans_rollback();

				$this->response->code('500', NDPHP_LANG_MOD_FAILED_UPDATE_USER_DATA, $this->config['default_charset'], !$this->request->is_ajax());
			} else {
				$this->db->trans_commit();
			}
		}

		/* Always update user session data after any user changes are performed */

		/* Query the database */
		$this->db->select('users.id AS user_id,users.username AS username,users.email AS email,users._file_photo AS photo,rel_users_roles.roles_id AS roles_id,timezones.timezone AS timezone,users.privenckey');
		$this->db->from('users');
		$this->db->join('rel_users_roles', 'rel_users_roles.users_id = users.id', 'left');
		$this->db->join('timezones', 'users.timezones_id = timezones.id', 'left');
		$this->db->where('users.id', $id);

		$query = $this->db->get();

		if (!$query->num_rows())
			$this->response->code('403', NDPHP_LANG_MOD_UNABLE_UPDATE_SESSION_DATA . ' ' . NDPHP_LANG_MOD_ATTN_LOGOUT_LOGIN, $this->config['default_charset'], !$this->request->is_ajax());

		/* Only update session data if the user being updated is the user who's performing the update */
		if ($this->config['session_data']['user_id'] == $id) {
			$user_roles = array();

			foreach ($query->result_array() as $row) {
				array_push($user_roles, $row['roles_id']);
			}
			
			/* Update user session data */
			$this->config['session_data']['username'] = $row['username'];
			$this->config['session_data']['photo'] = $row['photo'] ? (base_url() . 'index.php/files/access/users/_file_photo/' . json_decode($row['photo'], true)['name'] . '/' . $id) : NULL;
			$this->config['session_data']['email'] = $row['email'];
			$this->config['session_data']['timezone'] = $row['timezone'];
			$this->config['session_data']['privenckey'] = bin2hex($row['privenckey']);
			/* FIXME: Missing database variable? */
			$this->config['session_data']['roles'] = $user_roles;

			$this->session->set_userdata($this->config['session_data']);
		}
	}

	protected function _hook_delete_pre(&$id, &$POST, &$fields) {
		$hook_pre_return = NULL;
		
		if ($id == 1)
			$this->response->code('403', NDPHP_LANG_MOD_CANNOT_DELETE_ADMIN_USER, $this->config['default_charset'], !$this->request->is_ajax());

		/* Validate roles */
		if ($this->request->post_isset('rel_users_roles') && count($this->request->post('rel_users_roles'))) {
			/* Check current roles */
			$this->db->select('roles.id,roles.is_admin,roles.is_superuser');
			$this->db->from('roles');
			$this->db->join('rel_users_roles', 'rel_users_roles.roles_id = roles.id', 'left');
			$this->db->join('users', 'rel_users_roles.users_id = users.id', 'left');
			$this->db->where('users.id', $id);
			$q = $this->db->get();

			if ($q->num_rows()) {
				foreach ($q->result_array() as $row) {
					/* Only ROLE_ADMIN (superadmin) can delete users with superadmin role (ROLE_ADMIN). */
					if (($row['id'] == 1) && !$this->security->im_superadmin())
						$this->response->code('403', NDPHP_LANG_MOD_CANNOT_USERS_DELETE_ROLE_ADMIN, $this->config['default_charset'], !$this->request->is_ajax());

					/* Only ROLE_ADMIN (superadmin) can delete users with admin roles. */
					if ($row['is_admin'] && !$this->security->im_superadmin())
						$this->response->code('403', NDPHP_LANG_MOD_CANNOT_USERS_DELETE_ADMIN_ROLES, $this->config['default_charset'], !$this->request->is_ajax());

					/* Only admin users can manage delete users with superuser roles. */
					if ($row['is_superuser'] && !$this->security->im_admin())
						$this->response->code('403', NDPHP_LANG_MOD_CANNOT_USERS_DELETE_SUPERUSER_ROLES, $this->config['default_charset'], !$this->request->is_ajax());
				}
			}
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

	protected function _hook_groups_generic_leave(&$data, $hook_enter_return) {
		/* Unset fields based on disabled features */
		$this->_feature_filter_data_fields($data);

		/* Initialize a new groups array */
		$groups = array();

		/* Filter groups belonging to filtered fields */
		foreach ($data['view']['groups'] as $group) {
			if (!array_key_exists($group['table_field'], $data['view']['fields']))
				continue;

			array_push($groups, $group);
		}

		/* Update groups in view data */
		$data['view']['groups'] = $groups;
	}

	/** Other overloads **/
	/* Hidden fields per view.
	 *
	 * Note that for relationship fields, the field name used here must be the one
	 * corresponding to the foreign table field.
	 * 
	 */
	protected $_hide_fields_create = array('id');
	protected $_hide_fields_edit = array('id');
	protected $_hide_fields_view = array('password');
	protected $_hide_fields_remove = array('password');
	protected $_hide_fields_list = array('password', 'phone', 'birthdate', 'genders_id', 'currencies_id', 'address_line1', 'address_line2', 'city', 'postcode', 'vat', 'apikey', 'confirm_email_hash', 'confirm_phone_token', 'phone_confirmed', 'date_confirmed', 'registered', 'email_confirmed', 'allow_negative', 'expire', 'subscription_change_date', 'subscription_renew_date', 'company', 'brand', 'website', 'about', 'first_name', 'last_name', 'acct_last_reset', 'acct_rest_list', 'acct_rest_result', 'acct_rest_view', 'acct_rest_delete', 'acct_rest_update', 'acct_rest_insert', 'generic_counter_1', 'generic_counter_2', 'generic_counter_3', 'generic_counter_4', 'generic_text_1', 'generic_text_2', 'generic_text_3', 'generic_text_4', 'generic_datetime_1', 'generic_datetime_2', 'generic_datetime_3', 'generic_datetime_4', 'generic_string_1', 'generic_string_2', 'generic_string_3', 'generic_string_4', 'generic_boolean_1', 'generic_boolean_2', 'generic_boolean_3', 'generic_boolean_4');
	protected $_hide_fields_result = array('password', 'phone', 'birthdate', 'genders_id', 'currencies_id', 'address_line1', 'address_line2', 'city', 'postcode', 'vat', 'apikey', 'confirm_email_hash', 'confirm_phone_token', 'phone_confirmed', 'date_confirmed', 'registered', 'email_confirmed', 'allow_negative', 'expire', 'subscription_change_date', 'subscription_renew_date', 'company', 'brand', 'website', 'about', 'first_name', 'last_name', 'acct_last_reset', 'acct_rest_list', 'acct_rest_result', 'acct_rest_view', 'acct_rest_delete', 'acct_rest_update', 'acct_rest_insert', 'generic_counter_1', 'generic_counter_2', 'generic_counter_3', 'generic_counter_4', 'generic_text_1', 'generic_text_2', 'generic_text_3', 'generic_text_4', 'generic_datetime_1', 'generic_datetime_2', 'generic_datetime_3', 'generic_datetime_4', 'generic_string_1', 'generic_string_2', 'generic_string_3', 'generic_string_4', 'generic_boolean_1', 'generic_boolean_2', 'generic_boolean_3', 'generic_boolean_4');
	protected $_hide_fields_search = array('password'); // Include fields searched on searchbar (basic)
	protected $_hide_fields_export = array('password');

	protected $_links_submenu_body_view = array(
		/* array('Description', $sec_perm, method, 'image/path/img.png', 'ajax' / 'export' / 'method' / 'modal' / 'raw', with id?, access key) */
		array(NDPHP_LANG_MOD_OP_CREATE,			'C', 'create',		NULL, 'ajax',   false,	NDPHP_LANG_MOD_OP_ACCESS_KEY_CREATE	),
		array(NDPHP_LANG_MOD_OP_REMOVE,			'D', 'remove',		NULL, 'ajax',   true,	NDPHP_LANG_MOD_OP_ACCESS_KEY_REMOVE	),
		array(NDPHP_LANG_MOD_OP_EDIT,			'U', 'edit',		NULL, 'ajax',   true,	NDPHP_LANG_MOD_OP_ACCESS_KEY_EDIT	),
		array(NDPHP_LANG_MOD_OP_LIST,			'R', 'list',		NULL, 'ajax',   false,	NDPHP_LANG_MOD_OP_ACCESS_KEY_LIST	),
		array(NDPHP_LANG_MOD_OP_GROUPS,			'R', 'groups',		NULL, 'ajax',	false,	NDPHP_LANG_MOD_OP_ACCESS_KEY_GROUPS	),
		array(NDPHP_LANG_MOD_OP_SEARCH,			'R', 'search',		NULL, 'ajax',   false,	NDPHP_LANG_MOD_OP_ACCESS_KEY_SEARCH	),
		array(NDPHP_LANG_MOD_OP_EXPORT_PDF,		'R', 'pdf',			NULL, 'export', true,	NULL 								),
		array(NDPHP_LANG_MOD_OP_LOGOUT,			'R', 'logout',		NULL, 'method', false,	NULL 								)
	);

	/* Aliases for the current table field names */
	protected $_table_field_aliases = array(
		'username' => NDPHP_LANG_MOD_COMMON_USERNAME,
		'password' => NDPHP_LANG_MOD_COMMON_PASSWORD,
		'_file_photo' => NDPHP_LANG_MOD_COMMON_PHOTO,
		'email' => NDPHP_LANG_MOD_COMMON_EMAIL,
		'phone' => NDPHP_LANG_MOD_COMMON_PHONE,
		'active' => NDPHP_LANG_MOD_COMMON_ACTIVE,
		'locked' => NDPHP_LANG_MOD_COMMON_LOCKED,
		'_separator_personal' => NDPHP_LANG_MOD_SEP_USER_PERSONAL,
		'first_name' => NDPHP_LANG_MOD_COMMON_FIRST_NAME,
		'last_name' => NDPHP_LANG_MOD_COMMON_LAST_NAME,
		'birthdate' => NDPHP_LANG_MOD_COMMON_BIRTHDATE,
		'company' => NDPHP_LANG_MOD_COMMON_COMPANY_NAME,
		'brand' => NDPHP_LANG_MOD_COMMON_BRAND,
		'address_line1' => NDPHP_LANG_MOD_COMMON_ADDR_LINE1,
		'address_line2' => NDPHP_LANG_MOD_COMMON_ADDR_LINE2,
		'city' => NDPHP_LANG_MOD_COMMON_CITY,
		'postcode' => NDPHP_LANG_MOD_COMMON_POSTCODE,
		'vat' => NDPHP_LANG_MOD_COMMON_VAT_NUMBER,
		'website' => NDPHP_LANG_MOD_COMMON_WEBSITE,
		'about' => NDPHP_LANG_MOD_COMMON_ABOUT,
		'_separator_register' => NDPHP_LANG_MOD_SEP_USER_REGISTER,
		'expire' => NDPHP_LANG_MOD_COMMON_EXPIRE,
		'registered' => NDPHP_LANG_MOD_COMMON_REGISTERED,
		'last_login' => NDPHP_LANG_MOD_COMMON_LAST_LOGIN,
		'confirm_email_hash' => NDPHP_LANG_MOD_COMMON_CONFIRM_EMAIL_HASH,
		'confirm_phone_token' => NDPHP_LANG_MOD_COMMON_CONFIRM_PHONE_TOKEN,
		'email_confirmed' => NDPHP_LANG_MOD_COMMON_EMAIL_CONFIRMED,
		'phone_confirmed' => NDPHP_LANG_MOD_COMMON_PHONE_CONFIRMED,
		'date_confirmed' => NDPHP_LANG_MOD_COMMON_DATE_CONFIRMED,
		'_separator_credit' => NDPHP_LANG_MOD_SEP_USER_CREDIT,
		'credit' => NDPHP_LANG_MOD_COMMON_CREDIT,
		'allow_negative' => NDPHP_LANG_MOD_COMMON_ALLOW_NEG_CREDIT,
		'_separator_api' => NDPHP_LANG_MOD_SEP_USER_API,
		'apikey' => NDPHP_LANG_MOD_COMMON_API_KEY,
		'_separator_accounting' => NDPHP_LANG_MOD_SEP_USER_ACCOUNTING,
		'acct_last_reset' => NDPHP_LANG_MOD_COMMON_ACCT_LAST_RESET,
		'acct_rest_list' => NDPHP_LANG_MOD_COMMON_ACCT_REST_LIST_CNTR,
		'acct_rest_result' => NDPHP_LANG_MOD_COMMON_ACCT_REST_RESULT_CNTR,
		'acct_rest_view' => NDPHP_LANG_MOD_COMMON_ACCT_REST_VIEW_CNTR,
		'acct_rest_delete' => NDPHP_LANG_MOD_COMMON_ACCT_REST_DELETE_CNTR,
		'acct_rest_update' => NDPHP_LANG_MOD_COMMON_ACCT_REST_UPDATE_CNTR,
		'acct_rest_insert' => NDPHP_LANG_MOD_COMMON_ACCT_REST_INSERT_CNTR,
		'_separator_generic' => NDPHP_LANG_MOD_SEP_USER_GENERIC,
		'generic_counter_1' => NDPHP_LANG_MOD_COMMON_GENERIC_COUNTER_1,
		'generic_counter_2' => NDPHP_LANG_MOD_COMMON_GENERIC_COUNTER_2,
		'generic_counter_3' => NDPHP_LANG_MOD_COMMON_GENERIC_COUNTER_3,
		'generic_counter_4' => NDPHP_LANG_MOD_COMMON_GENERIC_COUNTER_4,
		'generic_text_1' => NDPHP_LANG_MOD_COMMON_GENERIC_TEXT_1,
		'generic_text_2' => NDPHP_LANG_MOD_COMMON_GENERIC_TEXT_2,
		'generic_text_3' => NDPHP_LANG_MOD_COMMON_GENERIC_TEXT_3,
		'generic_text_4' => NDPHP_LANG_MOD_COMMON_GENERIC_TEXT_4,
		'generic_datetime_1' => NDPHP_LANG_MOD_COMMON_GENERIC_DATETIME_1,
		'generic_datetime_2' => NDPHP_LANG_MOD_COMMON_GENERIC_DATETIME_2,
		'generic_datetime_3' => NDPHP_LANG_MOD_COMMON_GENERIC_DATETIME_3,
		'generic_datetime_4' => NDPHP_LANG_MOD_COMMON_GENERIC_DATETIME_4,
		'_separator_sharding' => NDPHP_LANG_MOD_COMMON_SHARDING
	);

	protected $_rel_table_fields_config = array(
		'timezones' => array(NDPHP_LANG_MOD_COMMON_TIMEZONE, NULL, array(1), array('id', 'asc'), NULL),
		'subscription_types' => array(NDPHP_LANG_MOD_COMMON_SUBSCRIPTION, NULL, array(1), array('id', 'asc'), NULL),
		'genders' => array(NDPHP_LANG_MOD_COMMON_GENDER, NULL, array(1), array('id', 'asc'), NULL),
		'countries' => array(NDPHP_LANG_MOD_COMMON_COUNTRY, NULL, array(1), array('id', 'asc'), NULL),
		'currencies' => array(NDPHP_LANG_MOD_COMMON_CURRENCY, NULL, array(1), array('id', 'asc'), NULL),
		'roles' => array(NDPHP_LANG_MOD_SEP_USER_ROLES, NULL, array(1), array('id', 'asc'), NULL)
	);

	/** Custom functions **/
	private function _feature_filter_data_fields(&$data) {
		/* Unset fields based on disabled features */
		if (!$data['config']['features']['user_credit_control']) {
			unset($data['view']['fields']['_separator_credit']);
			unset($data['view']['fields']['credit']);
			unset($data['view']['fields']['allow_negative']);
		}
	}

	public function user_credit_get() {
		$this->db->select('credit');
		$this->db->from('users');
		$this->db->where('id', $this->session->userdata('user_id'));
		$query = $this->db->get();
		$rawdata = $query->row_array();

		$this->response->output(round($rawdata['credit'], 2));
	}

	public function user_subscription_get() {
		$this->db->select('subscription_types.subscription_type AS subscription');
		$this->db->from('users');
		$this->db->join('subscription_types', 'subscription_types.id = users.subscription_types_id', 'left');
		$this->db->where('users.id', $this->session->userdata('user_id'));
		$query = $this->db->get();
		$rawdata = $query->row_array();

		$this->response->output($rawdata['subscription']);
	}

	public function logout() {
		redirect('/login/logout');
	}
}
