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

class UW_Accounting extends UW_Model {
	protected $_avail_counters = array(
		'acct_rest_list',
		'acct_rest_result',
		'acct_rest_view',
		'acct_rest_delete',
		'acct_rest_update',
		'acct_rest_insert'
	);

	public function user_counter_reset_all($user_id) {
		if (!in_array($counter, $this->_avail_counters)) {
			error_log('UW_Accounting: user_counter_reset_all(): Unrecognized counter: ' . $counter);
			return false;
		}

		foreach ($this->_avail_counters as $counter) {
			if ($this->counter_reset($user_id, $counter) === false) {
				error_log('UW_Accounting: user_counter_reset_all(): Failed.');
				return false;
			}
		}

		/* Update last reset date */
		$this->db->trans_begin();

		$this->db->where('id', $user_id);
		$this->db->update('users', array(
			'acct_last_reset' => date('Y-m-d H:i:s')
		));

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			error_log('UW_Accounting: user_counter_reset_all(): Unable to update user id ' . $user_id . ' last reset date.');
			return false;
		} else {
			$this->db->trans_commit();
		}

		/* All good */
		return true;
	}

	public function user_counter_reset($user_id, $counter) {
		if (!in_array($counter, $this->_avail_counters)) {
			error_log('UW_Accounting: user_counter_reset(): Unrecognized counter: ' . $counter);
			return false;
		}

		/* Reset the counter to zero */
		$this->db->trans_begin();

		$this->db->where('id', $user_id);
		$this->db->update('users', array(
			$counter => 0
		));

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			error_log('UW_Accounting: user_counter_reset(): Unable to reset user id ' . $user_id . ' counter: ' . $counter);
			return false;
		} else {
			$this->db->trans_commit();
		}

		/* All good */
		return true;
	}

	public function user_counter_increment($user_id, $counter) {
		if (!in_array($counter, $this->_avail_counters)) {
			error_log('UW_Accounting: user_counter_increment(): Unrecognized counter: ' . $counter);
			return false;
		}

		/* Increment the counter */
		$this->db->trans_begin();

		$this->db->select($counter);
		$this->db->from('users');
		$this->db->where('id', $user_id);
		$q = $this->db->get();

		if ($q->num_rows()) {
			$row = $q->row_array();
			$this->db->where('id', $user_id);
			$this->db->update('users', array(
				$counter => $row[$counter] + 1
			));
		}

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			error_log('UW_Accounting: user_counter_increment(): Unable to increment user id ' . $user_id . ' counter: ' . $counter);
			return false;
		} else {
			$this->db->trans_commit();
		}

		/* All good */
		return true;
	}

	public function user_counter_decrement($user_id, $counter) {
		if (!in_array($counter, $this->_avail_counters)) {
			error_log('UW_Accounting: user_counter_increment(): Unrecognized counter: ' . $counter);
			return false;
		}

		/* Increment the counter */
		$this->db->trans_begin();

		$this->db->select($counter);
		$this->db->from('users');
		$this->db->where('id', $user_id);
		$q = $this->db->get();

		if ($q->num_rows()) {
			$row = $q->row_array();
			$this->db->where('id', $user_id);
			$this->db->update('users', array(
				$counter => $row[$counter] - 1
			));
		}

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			error_log('UW_Accounting: user_counter_increment(): Unable to increment user id ' . $user_id . ' counter: ' . $counter);
			return false;
		} else {
			$this->db->trans_commit();
		}

		/* All good */
		return true;
	}
}