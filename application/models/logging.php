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

class UW_Logging extends UW_Model {
	private $_logging_enabled = true;
	private $_logging_table = 'logging';
	private $_in_transaction = false;
	private $_transaction_id = NULL;

	public function __construct() {
		parent::__construct();

		/* Access global configuration */
		global $config;

		/* Check if logging is enabled */
		if (!isset($config['logging']['enabled']) || !$config['logging']['enabled']) {
			$this->_logging_enabled = false;
			return;
		}

		/* Setup logging object according to selected driver */
		switch ($config['logging']['driver']['name']) {
			case 'database': {
				/* If logging driver is 'database', set the database table where the logs will reside */
				$this->_logging_table = $config['logging']['driver']['database']['table'];
			} break;

			case 'syslog': {
				/* If logging driver is 'syslog', open a connection to the system logger */
				openlog($config['logging']['driver']['syslog']['ident'], $config['logging']['driver']['syslog']['option'], $config['logging']['driver']['syslog']['facility']);
			} break;

			default: error_log(__FILE__ . ': ' . __FUNCTION__ . '(): No suitable logging driver was defined.');
		}
	}

	public function log($op, $table, $field, $entry_id, $value_new, $value_old, $session_id, $user_id, $log = true) {
		global $config;

		/* If the log request is to do not perform the logging action or if the logging support is globally disabled, just return */
		if (($log !== true) || ($this->_logging_enabled !== true))
			return;

		$log_transaction_id = NULL;

		/* Check if we're inside a logging transaction */
		if ($this->_in_transaction === true) {
			/* We need to initialize the internal transaction id if it isn't yet initialized ... */
			if ($this->_transaction_id === NULL)
				$this->_transaction_id = openssl_digest($op . $table . $session_id . date('Y-m-d H:i:s') . mt_rand(1000000, 9999999), 'sha1');

			/* Inherit the current transaction id */
			$log_transaction_id = $this->_transaction_id;
		} else {
			/* We're not inside a transaction. Generate a new and unique transaction id for this logging entry */
			$log_transaction_id = openssl_digest($op . $table . $session_id . date('Y-m-d H:i:s') . mt_rand(1000000, 9999999), 'sha1');
		}

		/* Insert the log entry into the database */
		if ($config['logging']['driver']['name'] == 'database') {
			$this->db->insert($this->_logging_table, array(
				'operation' => $op,
				'_table' => $table,
				'_field' => $field,
				'entryid' => $entry_id,
				'value_new' => $value_new,
				'value_old' => $value_old,
				'transaction' => $log_transaction_id,
				'registered' => date('Y-m-d H:i:s'),
				'sessions_id' => $session_id,
				'users_id' => $user_id
			));
		} else if ($config['logging']['driver']['name'] == 'syslog') {
			syslog(LOG_INFO,
				/* Operation */
				$config['logging']['driver']['syslog']['field_map']['operation'] .
					'=' . $op . $config['logging']['driver']['syslog']['field_delim'] .
				/* Object */
				$config['logging']['driver']['syslog']['field_map']['_table'] .
					'=' . $table . $config['logging']['driver']['syslog']['field_delim'] .
				/* Property */
				$config['logging']['driver']['syslog']['field_map']['_field'] .
					'=' . $field . $config['logging']['driver']['syslog']['field_delim'] .
				/* Entry */
				$config['logging']['driver']['syslog']['field_map']['entryid'] .
					'=' . $entry_id . $config['logging']['driver']['syslog']['field_delim'] .
				/* New value */
				$config['logging']['driver']['syslog']['field_map']['value_new'] .
					'=' . $value_new . $config['logging']['driver']['syslog']['field_delim'] .
				/* Old value */
				$config['logging']['driver']['syslog']['field_map']['value_old'] .
					'=' . $value_old . $config['logging']['driver']['syslog']['field_delim'] .
				/* Transaction */
				$config['logging']['driver']['syslog']['field_map']['transaction'] .
					'=' . $log_transaction_id . $config['logging']['driver']['syslog']['field_delim'] .
				/* Registered */
				$config['logging']['driver']['syslog']['field_map']['registered'] .
					'=' . date('Y-m-d H:i:s') . $config['logging']['driver']['syslog']['field_delim'] .
				/* Session ID */
				$config['logging']['driver']['syslog']['field_map']['sessions_id'] .
					'=' . $session_id . $config['logging']['driver']['syslog']['field_delim'] .
				/* User ID */
				$config['logging']['driver']['syslog']['field_map']['users_id'] .
					'=' . $user_id . $config['logging']['driver']['syslog']['field_delim'] .
				/* IP Address */
				(
					$config['logging']['driver']['syslog']['include_ip_address']
					?
					($config['logging']['driver']['syslog']['field_map']['ip_address'] . '=' . remote_addr() . $config['logging']['driver']['syslog']['field_delim'])
					:
					''
				) .
				/* User-Agent */
				(
					$config['logging']['driver']['syslog']['include_user_agent']
					?
					($config['logging']['driver']['syslog']['field_map']['user_agent'] . '=' . $_SERVER['HTTP_USER_AGENT'] . $config['logging']['driver']['syslog']['field_delim'])
					:
					''
				)
			);
		} else {
			error_log(__FILE__ . ': ' . __FUNCTION__ . '(): No suitable logging driver was defined.');
		}
	}

	public function trans_begin() {
		$this->_transaction_id = NULL;
		$this->_in_transaction = true;
	}

	public function trans_active() {
		return $this->_in_transaction;
	}

	public function trans_id($tid = NULL) {
		if ($tid === NULL)
			return $this->_transaction_id;

		$this->_transaction_id = $tid;
	}

	public function trans_end() {
		$this->_in_transaction = false;
		$this->_transaction_id = NULL;
	}
}
