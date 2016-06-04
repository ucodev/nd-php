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

class UW_Ndphp extends UW_Model {
	function arithmetic_op_user_credit($user_id, $ammount, $op = 'add') {
		$this->db->trans_begin();

		$this->db->select('credit');
		$this->db->from('users');
		$this->db->where('id', $user_id);

		$query = $this->db->get();

		if (!$query->num_rows()) {
			$this->db->trans_rollback();
			return false;
		}

		$row = $query->row_array();

		if ($op == 'add') {
			$userdata['credit'] = $row['credit'] + $ammount;
		} else {
			$userdata['credit'] = $row['credit'] - $ammount;
		}

		$this->db->where('id', $user_id);
		$this->db->update('users', $userdata);

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
		}

		return true;
	}

	function add_user_credit($user_id, $ammount) {
		return $this->arithmetic_op_user_credit($user_id, $ammount, 'add');
	}

	function sub_user_credit($user_id, $ammount) {
		return $this->arithmetic_op_user_credit($user_id, $ammount, 'sub');
	}

	function get_user_credit($user_id) {
		$this->db->select('credit');
		$this->db->from('users');
		$this->db->where('id', $user_id);

		$query = $this->db->get();

		if (!$query->num_rows())
			return 0;

		$row = $query->row_array();

		return $row['credit'];
	}

	function set_user_credit($user_id, $credit) {
		$userdata['credit'] = $credit;

		$this->db->trans_begin();

		$this->db->where('id', $user_id);
		$this->db->update('users', $userdata);

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
		}

		return true;
	}

	function safe_b64encode($input) {
		return str_replace('/', '@', base64_encode($input));
	}

	function safe_b64decode($input) {
		return base64_decode(str_replace('@', '/', $input));
	}

	function no_cache() {
		header('Expires: Sun, 01 Jan 2006 00:00:00 UTC');
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' UTC');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header('Cache-Control: post-check=0, pre-check=0', false);
		header('Pragma: no-cache');
	}
}
