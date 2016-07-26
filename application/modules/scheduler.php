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

class UW_Scheduler extends UW_Module {
	private $config;	/* Configuration */

	private function _init() {
		/* Load configuration */
		$this->config = $this->configuration->core_get();

		/* Load required modules */
		$this->load->module('request');
		$this->load->module('response');
	}

	public function __construct() {
		parent::__construct();

		/* Initialize module */
		$this->_init();
	}

	private function _exec_queued_entries() {
		/* Execute scheduled entries */
		foreach ($this->config['scheduler']['queue'] as $entry) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $entry['url']);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$ret = curl_exec($ch);
			curl_close($ch);

			/* Check if $row['next_run_val'] date is in the past... if so, we need to keep adding period until we
			 * get a date pointing to the future.
			 */
			if ($entry['next_run_val'] !== NULL && $entry['next_run_val'] > 1451606400) { /* next run must be at least close to this epoch to be considered valid */
				while ($entry['next_run_val'] < time())
					$entry['next_run_val'] += $entry['period'];
			} else {
				/* Othersite, set it to the current time */
				$entry['next_run_val'] = time();
			}

			/* Initialize transaction */
			$this->db->trans_begin();

			$this->db->where('id', $entry['id']);
			$this->db->update('scheduler', array(
				'last_run' => date('Y-m-d H:i:s'),
				'next_run' => date('Y-m-d H:i:s', $entry['next_run_val']),
				'output' => strip_tags($ret),
				'queued' => false
			));

			/* Check if transaction succeeded */
			if ($this->db->trans_status() === false) {
				$this->db->trans_rollback();

				error_log('Scheduler::_exec_queued_entries(): Failed to execute scheduled entry: ' . $entry['id']);
			}

			/* Commit transaction */
			$this->db->trans_commit();
		}

		/* Reset scheduled entries */
		$this->config['sched_entries'] = array();
	}

	public function process() {
		/* Initialize transaction */
		$this->db->trans_begin();

		/* Fetch scheduler entries requiring immediate processing */
		$q = $this->db->query('SELECT *,UNIX_TIMESTAMP(DATE_ADD(next_run, INTERVAL period SECOND)) AS next_run_val FROM scheduler WHERE active = 1 AND queued = 0 AND (next_run <= NOW() OR next_run IS NULL)');

		/* Nothing to process */
		if (!$q->num_rows()) {
			$this->db->trans_commit();
			return;
		}

		/* Re-Initialize scheduler entries array */
		$this->config['scheduler']['queue'] = array();

		/* Populate scheduler entries array */
		foreach ($q->result_array() as $row) {
			array_push($this->config['scheduler']['queue'], $row);

			/* Set the 'queued' flag to avoid concurrent proccessing for the same entry */
			$this->db->where('id', $row['id']);
			$this->db->update('scheduler', array(
				'queued' => true
			));
		}

		/* Check if transaction succeeded */
		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();

			error_log('Scheduler::process(): Failed to process scheduled entry: ' . $row['id']);
		}

		/* Commit transaction */
		$this->db->trans_commit();

		if ($this->config['threading'] && $this->config['scheduler']['type'] == 'threaded') {
			/* TODO: Not implemented */
		} else {
			// if ($this->_scheduler['type'] == 'request' || $this->_scheduler['type'] == 'external') ...
			$this->_exec_queued_entries();
		}
	}
}
