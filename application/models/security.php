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

class UW_Security extends UW_Model {
	public $perm_create = 'C';
	public $perm_read   = 'R';
	public $perm_update = 'U';
	public $perm_delete = 'D';
	public $perm_search = 'S';

	public function perm_set($user_id, $table, $table_reqperm, $column = NULL, $column_reqperm = NULL) {
		/* Fetch user roles */
		$this->db->select('roles_id');
		$this->db->from('rel_users_roles');
		$this->db->join('users', 'users.id = rel_users_roles.users_id', 'left');
		$this->db->where('users.id', $user_id);
		$q = $this->db->get();

		if (!$q->num_rows())
			return false;

		/* Begin transaction */
		$this->db->trans_begin();

		/* Insert permissions on a per-role basis */
		foreach ($q->result_array as $row) {
			/* Check if there are any permissions set for rtp */
			$this->db->select('id');
			$this->db->from('_acl_rtp');
			$this->db->where('roles_id', $row['roles_id']);
			$this->db->where('_table', $table);
			$qr = $this->db->get();

			/* If there are any permissions set, delete them */
			if ($qr->num_rows()) {
				$qr_row = $qr->row_array();
				$this->db->delete('_acl_rtp', array('id' => $qr_row['id']));
			}

			/* Insert new permissions */
			$this->db->insert('_acl_rtp', array(
				'roles_id' => $row['roles_id'],
				'_table' => $table,
				'permissions' => $table_reqperm
			));

			/* Check if there's a request for rtcp */
			if ($column === NULL || $column_reqperm === NULL)
				continue;

			/* Check if there are any permissions set for rtp */
			$this->db->select('id');
			$this->db->from('_acl_rtcp');
			$this->db->where('roles_id', $row['roles_id']);
			$this->db->where('_table', $table);
			$this->db->where('_column', $column);
			$qr = $this->db->get();

			/* If there are any permissions set, delete them */
			if ($qr->num_rows()) {
				$qr_row = $qr->row_array();
				$this->db->delete('_acl_rtcp', array('id' => $qr_row['id']));
			}

			/* Insert new permissions */
			$this->db->insert('_acl_rtcp', array(
				'roles_id' => $row['roles_id'],
				'_table' => $table,
				'_column' => $column,
				'permissions' => $column_reqperm
			));
		}

		/* Check transaction status */
		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			return false;
		}

		/* Commit transaction */
		$this->db->trans_commit();

		/* Unset perms cache for this user */
		if ($this->cache->is_active())
			$this->cache->delete('s_cache_perms_user_' . $user_id);

		/* All good */
		return true;
	}

	public function perm_unset($user_id, $table, $column = NULL) {
		/* Fetch user roles */
		$this->db->select('roles_id');
		$this->db->from('rel_users_roles');
		$this->db->join('users', 'users.id = rel_users_roles.users_id', 'left');
		$this->db->where('users.id', $user_id);
		$q = $this->db->get();

		if (!$q->num_rows())
			return false;

		/* Begin transaction */
		$this->db->trans_begin();

		/* Delete permissions on a per-role basis */
		foreach ($q->result_array as $row) {
			/* Delete table perms from rtp */
			$this->db->delete('_acl_rtp', array('roles_id' => $row['roles_id'], '_table' => $table));

			/* Check if there's a request for rtcp */
			if ($column === NULL)
				continue;

			/* Delete table column perms from rtcp */
			$this->db->delete('_acl_rtcp', array('roles_id' => $row['roles_id'], '_table' => $table, '_column' => $column));
		}

		/* Check transaction status */
		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			return false;
		}

		/* Commit transaction */
		$this->db->trans_commit();

		/* Unset perms cache for this user */
		if ($this->cache->is_active())
			$this->cache->delete('s_cache_perms_user_' . $user_id);

		/* All good */
		return true;
	}

	public function perm_get($id, $type = 'user') {
		if ($type == 'user' || $type == 'limited') {
			$user_id = $id;

			/* Check if we're using an external cache mechanism and, if so, read data from it */
			if ($this->cache->is_active()) {
				if ($this->cache->get('s_cache_perms_user_' . $user_id)) {
					$perms = $this->cache->get('d_cache_perms_user_' . $user_id);

					/* Grant that the cached data is still a valid entry, otherwise we need to fetch everything again */
					if (is_array($perms))
						return $perms;
				}
			}

			/* Get table permissions */
			$this->db->select('_acl_rtp._table AS perm_table,GROUP_CONCAT(DISTINCT _acl_rtp.permissions SEPARATOR \'\') AS perms', false);
			$this->db->from('_acl_rtp');
			$this->db->join('rel_users_roles', 'rel_users_roles.roles_id = _acl_rtp.roles_id', 'left');
			$this->db->join('users', 'users.id = rel_users_roles.users_id');
			$this->db->where('users.id', $user_id);
			$this->db->group_by('_acl_rtp._table');
			$query_table_perms = $this->db->get();
			$table_perms = array();
			foreach ($query_table_perms->result_array() as $table_perms_row) {
				$table_perms[$table_perms_row['perm_table']] = $table_perms_row['perms'];
			}

			/* Get table columns permissions */
			$this->db->select('_acl_rtcp._table AS perm_table,_acl_rtcp._column AS perm_column,GROUP_CONCAT(DISTINCT _acl_rtcp.permissions SEPARATOR \'\') AS perms', false);
			$this->db->from('_acl_rtcp');
			$this->db->join('rel_users_roles', 'rel_users_roles.roles_id = _acl_rtcp.roles_id', 'left');
			$this->db->join('users', 'users.id = rel_users_roles.users_id');
			$this->db->where('users.id', $user_id);
			$this->db->group_by(array('_acl_rtcp._column','_acl_rtcp._table'));
			$query_table_col_perms = $this->db->get();
			$table_col_perms = array();
			foreach ($query_table_col_perms->result_array() as $table_col_perms_row) {
				$table_col_perms[$table_col_perms_row['perm_table']][$table_col_perms_row['perm_column']] = $table_col_perms_row['perms'];
			}

			$perms['table'] = $table_perms;
			$perms['column'] = $table_col_perms;

			/* Check if we're using an external cache mechanism and, if so, write data to it */
			if ($this->cache->is_active()) {
				$this->cache->set('s_cache_perms_user_' . $user_id, true);
				$this->cache->set('d_cache_perms_user_' . $user_id, $perms);
			}
		} else if ($type == 'role') {
			$role_id = $id;

			/* Check if we're using an external cache mechanism and, if so, read data from it */
			if ($this->cache->is_active()) {
				if ($this->cache->get('s_cache_perms_role_' . $role_id)) {
					$perms = $this->cache->get('d_cache_perms_role_' . $role_id);

					/* Grant that the cached data is still a valid entry, otherwise we need to fetch everything again */
					if (is_array($perms))
						return $perms;
				}
			}

			/* Get table permissions */
			$this->db->select('_acl_rtp._table AS perm_table,GROUP_CONCAT(DISTINCT _acl_rtp.permissions SEPARATOR \'\') AS perms', false);
			$this->db->from('_acl_rtp');
			$this->db->where('_acl_rtp.roles_id', $role_id);
			$this->db->group_by('_acl_rtp._table');
			$query_table_perms = $this->db->get();
			$table_perms = array();
			foreach ($query_table_perms->result_array() as $table_perms_row) {
				$table_perms[$table_perms_row['perm_table']] = $table_perms_row['perms'];
			}

			/* Get table columns permissions */
			$this->db->select('_acl_rtcp._table AS perm_table,_acl_rtcp._column AS perm_column,GROUP_CONCAT(DISTINCT _acl_rtcp.permissions SEPARATOR \'\') AS perms', false);
			$this->db->from('_acl_rtcp');
			$this->db->where('_acl_rtcp.roles_id', $role_id);
			$this->db->group_by(array('_acl_rtcp._column','_acl_rtcp._table'));
			$query_table_col_perms = $this->db->get();
			$table_col_perms = array();
			foreach ($query_table_col_perms->result_array() as $table_col_perms_row) {
				$table_col_perms[$table_col_perms_row['perm_table']][$table_col_perms_row['perm_column']] = $table_col_perms_row['perms'];
			}

			$perms['table'] = $table_perms;
			$perms['column'] = $table_col_perms;

			/* Check if we're using an external cache mechanism and, if so, write data to it */
			if ($this->cache->is_active()) {
				$this->cache->set('s_cache_perms_role_' . $role_id, true);
				$this->cache->set('d_cache_perms_role_' . $role_id, $perms);
			}
		} else {
			error_log(__FILE__ . ': ' . __LINE__ . ': ' . __FUNCTION__ . '(): Invalid value supplied for $type.');
			return false;
		}

		return $perms;
	}

	public function perm_check($security_perms, $reqperm, $table, $column = NULL) {
			/* Magic controller refers to a special temporary table, so it will always have full permissions */
			if ($table == 'magic')
				return true;

	        /* Check if we're validating column or table permissions */
	        if (!$column) {
	                /* Validate table permissions */
	                if (strpos($security_perms['table'][$table], $reqperm) === false)
	                        return false; /* Permission denied */

	                /* Permission granted */
	                return true;
	        } else {
	                /* Validate table column permissions */
	                if (strpos($security_perms['column'][$table][$column], $reqperm) === false)
	                        return false; /* Permission denied */

	                /* Permission granted */
	                return true;
	        }

	        /* Permission denied */
	        return false;
	}

	public function perm_filter_result($sec_perms, $reqperm, $ctrl, $result_array) {
		/* Iterates over a $result_array and filters unpermitted fields, generating a $result_array_filtered and returning it */

		$result_array_filtered = array();
		
		foreach ($result_array as $row) {
			foreach ($row as $field => $value) {
				/* TODO: FIXME: multiple and mixed relationships are not being handled here (and they're probably being ignored) */

				/* Check if the request permission is present in sec_perms for this particular field */
				if (!$this->perm_check($sec_perms, $reqperm, $ctrl, $field))
					unset($row[$field]); /* If not, unset this field from the row */
			}

			array_push($result_array_filtered, $row);
		}

		return $result_array_filtered;
	}

	public function safe_names($value, $safe_chars = 'a-zA-Z0-9_') {
		if (!preg_match('/^[' . $safe_chars .  ']+$/', $value))
			return false;

		return true;
	}

	public function safe_keys($array, $safe_chars = 'a-zA-Z0-9_') {
		foreach ($array as $k => $v) {
			if (!preg_match('/^[' . $safe_chars . ']+$/', $k))
				return false;
		}

		return true;
	}

	public function users_superadmin() {
		/* Fetch an array of user id's' with ROLE_ADMIN */

		/* Check if we're using an external cache mechanism and, if so, read data from it */
		if ($this->cache->is_active()) {
			if ($this->cache->get('s_cache_sec_users_superadmin')) {
				$users_superadmin = $this->cache->get('d_cache_sec_users_superadmin');

				/* Grant that the cached data is still a valid entry, otherwise we need to fetch everything again */
				if (is_array($users_superadmin))
					return $users_superadmin;
			}
		}

		/* Fetch data from database */
		$this->db->select('users.id');
		$this->db->distinct();
		$this->db->from('users');
		$this->db->join('rel_users_roles', 'rel_users_roles.users_id = users.id', 'left');
		$this->db->join('roles', 'rel_users_roles.roles_id = roles.id', 'left');
		$this->db->where('roles.id', 1); /* ROLE_ADMIN == 1 */
		$q = $this->db->get();

		if (!$q->num_rows())
			return array();

		$users_superadmin = array();

		foreach ($q->result_array() as $row)
			array_push($users_superadmin, $row['id']);

		/* Check if we're using an external cache mechanism and, if so, write data to it */
		if ($this->cache->is_active()) {
			$this->cache->set('s_cache_sec_users_superadmin', true);
			$this->cache->set('d_cache_sec_users_superadmin', $users_superadmin);
		}

		return $users_superadmin;
	}

	public function users_admin() {
		/* Fetch an array of user id's' with roles assigned with the is_admin privilege set */

		/* Check if we're using an external cache mechanism and, if so, read data from it */
		if ($this->cache->is_active()) {
			if ($this->cache->get('s_cache_sec_users_admin')) {
				$users_admin = $this->cache->get('d_cache_sec_users_admin');

				/* Grant that the cached data is still a valid entry, otherwise we need to fetch everything again */
				if (is_array($users_admin))
					return $users_admin;
			}
		}

		/* Fetch data from database */
		$this->db->select('users.id');
		$this->db->distinct();
		$this->db->from('users');
		$this->db->join('rel_users_roles', 'rel_users_roles.users_id = users.id', 'left');
		$this->db->join('roles', 'rel_users_roles.roles_id = roles.id', 'left');
		$this->db->where('roles.is_admin', 1);
		$q = $this->db->get();

		if (!$q->num_rows())
			return array();

		$users_admin = array();

		foreach ($q->result_array() as $row)
			array_push($users_admin, $row['id']);

		/* Check if we're using an external cache mechanism and, if so, write data to it */
		if ($this->cache->is_active()) {
			$this->cache->set('s_cache_sec_users_admin', true);
			$this->cache->set('d_cache_sec_users_admin', $users_admin);
		}

		return $users_admin;
	}

	public function users_superuser() {
		/* Fetch an array of user id's' with roles assigned with the is_superuser privilege set */

		/* Check if we're using an external cache mechanism and, if so, read data from it */
		if ($this->cache->is_active()) {
			if ($this->cache->get('s_cache_sec_users_superuser')) {
				$users_superuser = $this->cache->get('d_cache_sec_users_superuser');

				/* Grant that the cached data is still a valid entry, otherwise we need to fetch everything again */
				if (is_array($users_superuser))
					return $users_superuser;
			}
		}

		/* Fetch data from database */
		$this->db->select('users.id');
		$this->db->distinct();
		$this->db->from('users');
		$this->db->join('rel_users_roles', 'rel_users_roles.users_id = users.id', 'left');
		$this->db->join('roles', 'rel_users_roles.roles_id = roles.id', 'left');
		$this->db->where('roles.is_superuser', 1);

		$q = $this->db->get();

		if (!$q->num_rows())
			return array();

		$users_superuser = array();

		foreach ($q->result_array() as $row)
			array_push($users_superuser, $row['id']);

		/* Check if we're using an external cache mechanism and, if so, write data to it */
		if ($this->cache->is_active()) {
			$this->cache->set('s_cache_sec_users_superuser', true);
			$this->cache->set('d_cache_sec_users_superuser', $users_superuser);
		}

		return $users_superuser;
	}

	public function is_superadmin($user_id) {
		/* Check if $user_id is superadmin */
		return in_array($user_id, $this->users_superadmin());
	}

	public function is_admin($user_id) {
		/* Check if $user_id is admin */
		return in_array($user_id, $this->users_admin());
	}

	public function is_superuser($user_id) {
		/* Check if $user_id is superuser */
		return in_array($user_id, $this->users_superuser());
	}

	public function im_superadmin() {
		/* ROLE_ADMIN is always admin, even when is_admin is not set */
		return in_array(1, $this->session->userdata('roles'));
	}

	public function im_admin() {
		/* ROLE_ADMIN is always admin, even when is_admin is not set */
		return in_array(1, $this->session->userdata('roles')) || $this->session->userdata('is_admin');
	}

	public function im_superuser() {
		return $this->session->userdata('is_superuser');
	}
}
