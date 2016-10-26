<?php if (!defined('FROM_BASE')) { header('HTTP/1.1 403 Forbidden'); die('Invalid requested path.'); }

/* Author: Pedro A. Hortas
 * Email: pah@ucodev.org
 * Date: 26/10/2016
 * License: GPLv3
 */

/*
 * This file is part of uweb.
 *
 * uWeb - uCodev Low Footprint Web Framework (https://github.com/ucodev/uweb)
 * Copyright (C) 2014-2016  Pedro A. Hortas
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

class UW_Base {
	public function __construct() {
		return;
	}
}

class UW_Encrypt extends UW_Base {
	private $_n_size;
	
	public function __construct() {
		global $config;
		$this->_n_size = mcrypt_get_iv_size($config['encrypt']['cipher'], $config['encrypt']['mode']);
	}
	
	public function encrypt($m, $k, $b64_encode = true) {
		global $config;

		/* Pad key */
		$k = substr(str_pad($k, $this->_n_size, "\0"), 0, $this->_n_size);

		$n = mcrypt_create_iv($this->_n_size, MCRYPT_RAND);
		$c = mcrypt_encrypt($config['encrypt']['cipher'], $k, $m, $config['encrypt']['mode'], $n);

		return ($b64_encode === true) ? base64_encode($n . $c) : ($n . $c);
	}
	
	public function encode($m, $b64_encode = false) {
		global $config;

		return $this->encrypt($m, $config['encrypt']['key'], $b64_encode);
	}

	public function decrypt($c, $k, $b64_decode = true) {
		global $config;

		/* Pad key */
		$k = substr(str_pad($k, $this->_n_size, "\0"), 0, $this->_n_size);

		if ($b64_decode === true)
			$c = base64_decode($c);

		$n = substr($c, 0, $this->_n_size);
		$c = substr($c, $this->_n_size);

		return mcrypt_decrypt($config['encrypt']['cipher'], $k, $c, $config['encrypt']['mode'], $n);
	}

	public function decode($c, $b64_decode = false) {
		global $config;

		return $this->decrypt($c, $config['encrypt']['key'], $b64_decode);
	}
}

class UW_SessionHandlerDb implements SessionHandlerInterface {
	private $db = NULL;
	private $cache = NULL;

	public function __construct($db = NULL, $cache = NULL) {
		$this->db = $db;
		$this->cache = $cache;
	}

	public function open($save_path, $name) {
		global $config;

		$this->db->load($config['session']['sssh_db_alias']);

		return true;
	}

	public function close() {
		return true;
	}

	public function read($session_id) {
		global $config;

		/* Check if session data is cached */
		if ($this->cache->is_active()) {
			if ($this->cache->get('s_session_' . $session_id)) {
				return $this->cache->get('d_session_' . $session_id);
			}
		}

		/* Otherwise, fetch session data from database */
		$this->db->select($config['session']['sssh_db_field_session_data'] . ' AS session_data');

		$this->db->from($config['session']['sssh_db_table']);
		$this->db->where($config['session']['sssh_db_field_session_id'], $session_id);
		$this->db->where($config['session']['sssh_db_field_session_valid'], true);

		$q = $this->db->get();

		if (!$q->num_rows())
			return '';

		$row = $q->row_array();

		/* Refresh cache */
		if ($this->cache->is_active()) {
			$this->cache->set('s_session_' . $session_id, true);
			$this->cache->set('d_session_' . $session_id, $row['session_data']);
		}

		return $row['session_data'];
	}

	public function write($session_id, $session_data) {
		global $config;

		/* Invalidate cache entry, if any */
		if ($this->cache->is_active())
			$this->cache->delete('s_session_' . $session_id);

		$this->db->trans_begin();

		/* Check if session id already exists */
		$this->db->select($config['session']['sssh_db_field_session_valid'] . ',' . $config['session']['sssh_db_field_session_end_time']);
		$this->db->from($config['session']['sssh_db_table']);
		$this->db->where($config['session']['sssh_db_field_session_id'], $session_id);
		$q = $this->db->get();

		if (!$q->num_rows()) {
			/* Create the session */
			$this->db->insert($config['session']['sssh_db_table'], array(
				$config['session']['sssh_db_field_session_id'] => $session_id,
				$config['session']['sssh_db_field_session_valid'] => true,
				$config['session']['sssh_db_field_session_start_time'] => date('Y-m-d H:i:s'),
				$config['session']['sssh_db_field_session_change_time'] => date('Y-m-d H:i:s'),
				$config['session']['sssh_db_field_session_data'] => $session_data
			));
		} else {
			$row = $q->row_array();

			/* If the session is not valid or was already destroyed, return false */
			if (!$row[$config['session']['sssh_db_field_session_valid']]) {
				$this->db->trans_rollback();
				return false;
			}

			/* Update the current session data */
			$this->db->where($config['session']['sssh_db_field_session_id'], $session_id);
			$this->db->where($config['session']['sssh_db_field_session_valid'], true);

			$uq = $this->db->update($config['session']['sssh_db_table'], array(
				$config['session']['sssh_db_field_session_change_time'] => date('Y-m-d H:i:s'),
				$config['session']['sssh_db_field_session_data'] => $session_data
			));

			/* If no rows were affected, this means that session is invalid... so return false */
			if (!$uq->num_rows()) {
				$this->db->trans_rollback();
				return false;
			}
		}

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			return false;
		}

		$this->db->trans_commit();

		/* Refresh cache */
		if ($this->cache->is_active()) {
			$this->cache->set('s_session_' . $session_id, true);
			$this->cache->set('d_session_' . $session_id, $session_data);
		}

		return true;
	}

	public function destroy($session_id) {
		global $config;

		/* Invalidate cache entry, if any */
		if ($this->cache->is_active())
			$this->cache->delete('s_session_' . $session_id);

		$this->db->trans_begin();

		$this->db->where($config['session']['sssh_db_field_session_id'], $session_id);

		$this->db->update($config['session']['sssh_db_table'], array(
			$config['session']['sssh_db_field_session_end_time'] => date('Y-m-d H:i:s'),
			$config['session']['sssh_db_field_session_data'] => ''
		));

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			return false;
		}

		$this->db->trans_commit();

		return true;
	}

	public function gc($maxlifetime) {
		global $config;

		$this->db->trans_begin();

		$this->db->where($config['session']['sssh_db_field_session_change_time'] . ' <', date('Y-m-d H:i:s', time() + $maxlifetime));

		$this->db->update($config['session']['sssh_db_table'], array(
			$config['session']['sssh_db_field_session_end_time'] => date('Y-m-d H:i:s'),
			$config['session']['sssh_db_field_session_data'] => ''
		));

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			return false;
		}

		$this->db->trans_commit();

		return true;
	}
}

class UW_Session extends UW_Base {
	private $_session_id = NULL;
	private $_session_data = array();
	private $_encryption = false;

	private function _session_start() {
		session_start();
	}

	private function _session_close() {
		session_write_close();
	}

	private function _session_data_serialize($session_start = true, $session_close = true) {
		/* Start the session */
		if ($session_start === true)
			$this->_session_start();

		/* Encrypt session data if _encryption is enabled */
		if ($this->_encryption) {
			global $config;
			$cipher = new UW_Encrypt;
			$_SESSION['data'] = $cipher->encrypt(json_encode($this->_session_data), $config['encrypt']['key']);
		} else {
			$_SESSION['data'] = json_encode($this->_session_data);
		}

		if ($session_close === true)
			$this->_session_close();
	}

	private function _session_data_unserialize($session_start = true, $session_close = true, $session_abort = false) {
		global $config;

		/* Start the session */
		if ($session_start === true)
			$this->_session_start();

		$this->_session_id = session_id();

		/* Evaluate if we're using encrypted sessions */
		$this->_encryption = $config['session']['encrypt'];

		/* Load user data */
		if (array_key_exists('data', $_SESSION)) {
			/* Decrypt session data if _encryption is enabled */
			if ($this->_encryption === true) {
				$cipher = new UW_Encrypt;

				/* NOTE: mcrypt_decrypt() returns a padded $m with trailing \0 to match $k length...
				 *       We need to rtrim() those \0, but only when we're sure they weren't there
				 *		 in the first place (which in this case, they were not because it's an JSON
				 *		 encoded string).
				 */
				$this->_session_data = json_decode(rtrim($cipher->decrypt($_SESSION['data'], $config['encrypt']['key']), "\0"), true);
			} else {
				/* Unencrypted session */
				$this->_session_data = json_decode($_SESSION['data'], true);
			}
		}

		/* Abort session? */
		if ($session_abort === true && $session_close === false)
			session_abort();

		/* Close the session */
		if ($session_close === true && $session_abort === false)
			$this->_session_close();
	}

	public function __construct($db = NULL, $cache = NULL) {
		global $config;

		/* Call the parent constructor */
		parent::__construct();

		/* Check if we're using sessions */
		if (!$config['session']['enable'])
			return ;

		/* Check if we can use sessions */
		if (session_status() == PHP_SESSION_DISABLED) {
			header("HTTP/1.1 403 Forbbiden");
			die("PHP Sessions are disabled.");
		}

		/* Change session handlers if database session data is enabled */
		if ($config['session']['sssh_db_enabled']) {
			$sssh = new UW_SessionHandlerDb($db, $cache);

			if (session_set_save_handler($sssh, true) === false) {
				header('HTTP/1.1 500 Internal Server Error');
				die('Unable to set session handler interface.');
			}
		}

		/* Get the default cookie parameters */
		$cookie = session_get_cookie_params();

		/* Initialize cookie parameters */
		session_set_cookie_params(0, '/', $config['session']['cookie_domain'], false, false);

		/* Set custom cookie parameters */
		session_set_cookie_params(
			$config['session']['cookie_lifetime'], $config['session']['cookie_path'],
			$config['session']['cookie_domain'],
			$config['session']['cookie_secure'], $config['session']['cookie_httponly']);

		/* Name the session */
		session_name($config['session']['name']);
	}

	public function set($variable, $value) {
		$this->_session_data_unserialize(true, false); /* Start the session, but dont close it */

		$this->_session_data[$variable] = $value;

		$this->_session_data_serialize(false, true); /* Close the session, without starting it */
	}

	public function set_userdata($variable, $value = NULL) {
		if ($value !== NULL) {
			$this->set($variable, $value);
		} else if (gettype($variable) == "array") {
			$this->_session_data_unserialize(true, false); /* Start the session, but dont close it */

			$this->_session_data = $variable; /* $variable should be an array */

			$this->_session_data_serialize(false, true); /* Close the session, without starting it */
		} else {
			header("HTTP/1.1 500 Internal Server Error");
			die("set_userdata(): First argument should be an array when no value is specified on second argument.");
		}
	}

	public function get($variable) {
		$this->_session_data_unserialize(true, false, true);

		if (!isset($this->_session_data[$variable]))
			return NULL;

		return $this->_session_data[$variable];
	}
	
	public function userdata($variable) {
		return $this->get($variable);
	}

	public function all_userdata() {
		$this->_session_data_unserialize(true, false, true);

		return $this->_session_data;
	}

	public function clear($variable) {
		$this->_session_data_unserialize(true, false); /* Start the session, but dont close it */

		unset($this->_session_data[$variable]);

		$this->_session_data_serialize(false, true); /* Close the session, without starting it */
	}

	public function unset_userdata($variable) {
		$this->clear($variable);
	}

	public function cleanup() {
		session_start();
		$_SESSION = array();
		session_unset(); /* Probably not required for newer versions of PHP */
		session_write_close();
	}
	
	public function regenerate($destroy_old_session = false) {
		session_start();
		session_regenerate_id($destroy_old_session);
		session_write_close();
	}

	public function destroy() {
		session_start();
		session_destroy();
		session_write_close();
	}
}

class UW_Cache extends UW_Base {
	private $_c = NULL;
	private $_kp = '';

	public function __construct() {
		global $config;

		parent::__construct();

		/* If no cache system is configured, do not try to load it */
		if (!isset($config['cache']) || !count($config['cache'])) {
			$config['cache'] = array();
			$config['cache']['active'] = false;
			return;
		}

		/* If it is configured, but disabled, do not proceed */
		if ($config['cache']['active'] !== true)
			return;

		/* Currently, only a single instance of memcached is supported */
		if ($config['cache']['driver'] == 'memcached') {
			$this->_c = new Memcached($config['cache']['key_prefix']);
			$this->_c->setOption(Memcached::OPT_LIBKETAMA_COMPATIBLE, true);

			/* Only add servers if the list is empty.
			 * TODO: FIXME: Instead of counting, check for differences and change the server list accordingly.
			 */
			if (!count($this->_c->getServerList()))
				$this->_c->addServer($config['cache']['host'], intval($config['cache']['port']));
		}

		$this->_kp = $config['cache']['key_prefix'];
	}

	public function is_active() {
		global $config;

		return $config['cache']['active'];
	}

	public function add($k, $v, $expiration = 0) {
		if ($this->is_active() !== true)
			return false;

		return $this->_c->add($this->_kp . $k, $v, $expiration);
	}

	public function set($k, $v, $expiration = 0) {
		if ($this->is_active() !== true)
			return false;

		return $this->_c->set($this->_kp . $k, $v, $expiration);
	}

	public function get($k) {
		if ($this->is_active() !== true)
			return false;

		return $this->_c->get($this->_kp . $k);
	}

	public function delete($k, $time = 0) {
		if ($this->is_active() !== true)
			return false;

		return $this->_c->delete($this->_kp . $k, $time);
	}

	public function flush($delay = 0) {
		if ($this->is_active() !== true)
			return false;

		return $this->_c->flush($delay);
	}

	public function result() {
		if ($this->is_active() !== true)
			return false;

		return $this->_c->getResultCode();
	}
}

class UW_Database extends UW_Base {
	private $_db = NULL;
	private $_cur_db = NULL;
	private $_res = NULL;
	private $_stmt = NULL;
	private $_cfg_use_stmt = true;
	private $_q_select = NULL;
	private $_q_distinct = false;
	private $_q_from = NULL;
	private $_q_join = NULL;
	private $_q_where = NULL;
	private $_q_group_by = NULL;
	private $_q_having = NULL;
	private $_q_order_by = NULL;
	private $_q_limit = NULL;
	private $_q_args = array();
	private $_q_objects = NULL;
	private $_trans_status_invoked = false; /* Used to indicate if trans_status() function was used prior to trans_commit() [Old API] */

	public $database = NULL; /* Current loaded database name */

	private function _q_reset_all() {
		/* Reset query data */
		$this->_q_select = NULL;
		$this->_q_distinct = false;
		$this->_q_from = NULL;
		$this->_q_join = NULL;
		$this->_q_where = NULL;
		$this->_q_group_by = NULL;
		$this->_q_having = NULL;
		$this->_q_order_by = NULL;
		$this->_q_limit = NULL;
		$this->_q_args = array();
	}

	private function _has_special($value) {
		/* TODO: Should a better approach (Prehaps regex? Or now we have two problems?) be implemented here? */
		if (    strpos($value, '#')  !== false || strpos($value, '(')  !== false ||
				strpos($value, ')')  !== false || strpos($value, ',')  !== false ||
			    strpos($value, '/*') !== false || strpos($value, '--') !== false ||
				strpos($value, ';')  !== false || strpos($value, '*')  !== false ||
				strpos($value, '\'') !== false || strpos($value, '"')  !== false)
		{
			return true;
		}

		return false;
	}

	private function _table_field_enforce($field) {
		$field_enforced = NULL;

		$field = str_replace('`', '', $field); /* Remove any ` chars as they will be inserted as required */

		if (strpos($field, '.') !== false) {
			$field_enforced = '`' . implode('`.`', explode('.', $field)) . '`';
		} else {
			$field_enforced = '`' . $field . '`';
		}

		return $field_enforced;
	}

	private function _query_aggregate_args($query, $data = NULL) {
		if (!$data)
			return $query;

		$q = explode('?', $query);

		if ((count($q) - 1) != count($data)) {
			header('HTTP/1.1 500 Internal Server Error');
			die('_query_aggregate_args(): Query and Data counts do not match.');
		}

		$aggregate = '';

		for ($i = 0; $i < count($q); $i ++) {
			/* Check if we've reached the last slice */
			if ($i == (count($q) - 1)) {
				$aggregate .= $q[$i];
				break;
			}

			/* Aggregate data value to query */
			$aggregate .= $q[$i] . $this->quote($data[$i]);
		}

		return $aggregate;
	}

	private function _convert_boolean($value) {
		if (gettype($value) == "boolean") {
			return $value === true ? 1 : 0;
		}

		return $value;
	}

	public function quote($value) {
		/* FIXME: TODO: Missing charset setup */
		return $this->_db[$this->_cur_db]->quote($value);
	}

	public function select($fields = NULL, $enforce = true) {
		if (!$fields) {
			header('HTTP/1.1 500 Internal Server Error');
			die('select(): No fields selected.');
		}

		/* Escape field if enforce is set */
		if ($enforce) {
			/* Strip all '`' characters as we'll check fields individually and apply '`' as required */
			$fields = str_replace('`', '', $fields);

			/* $fields shall not contain any spaces ' ' unless ' AS ' is used */
			if (strpos($fields, ' AS ') === false) /* TODO: this validation should be case insensitive */
				$fields = str_replace(' ', '', $fields);

			/* Boom */
			$field_parsed = explode(',', $fields);

			/* $field_parsed shall not contain any comments */
			foreach ($field_parsed as $field) {
				if ($this->_has_special($field)) {
					header('HTTP/1.1 500 Internal Server Error');
					die('select(): Enforced select() shall not contain any comments nor special characters.');
				}
			}

			/* Glue it */
			$fields = '';

			foreach ($field_parsed as $f) {
				/* Process special case AS (aliases) */
				if (strpos($f, ' AS ') !== false) { /* TODO: this validation should be case insensitive */
					$f_alias_parsed = explode(' AS ', $f); /* TODO: case insensitive */
					$fields .= str_replace(' ', '', $this->_table_field_enforce($f_alias_parsed[0])) . ' AS `' . str_replace(' ', '', $f_alias_parsed[1]) . '`,';
				} else {
					$fields .= $this->_table_field_enforce($f) . ',';
				}
			}

			$fields = rtrim($fields, ',');
		}

		$this->_q_select = 'SELECT ' . $fields . ' ';

		return $this;
	}

	public function distinct() {
		$this->_q_distinct = true;

		return $this;
	}

	public function from($table = NULL, $enforce = true) {
		if (!$table) {
			header('HTTP/1.1 500 Internal Server Error');
			die('from(): No table specified.');
		}

		if ($enforce) {
			/* Filter any previous escapes */
			$table = str_replace('`', '', $table);

			/* $table shall not contain any whitespaces nor comments */
			if ($this->_has_special($table) || strpos($table, ' ') !== false) {
				header('HTTP/1.1 500 Internal Server Error');
				die('select(): Enforced select() shall not contain any comments');
			}

			$this->_q_from = ' FROM `' . $table . '` ';
		} else {
			$this->_q_from = ' FROM ' . $table . ' ';
		}

		return $this;
	}

	public function join($table = NULL, $on = NULL, $type = 'INNER', $enforce = true) {
		if (!$table || !$on) {
			header('HTTP/1.1 500 Internal Server Error');
			die('join(): Missing required arguments.');
		}

		if (!$this->_q_join)
			$this->_q_join = '';

		$type = strtoupper($type);

		if ($type != "INNER" && $type != "LEFT" && $type != "RIGHT") {
			header('HTTP/1.1 500 Internal Server Error');
			die('join(): $type must be one of INNER, LEFT or RIGHT.');
		}

		/* Escape and filter on enforce */
		if ($enforce) {
			/* Filter any previous escapes */
			$table = str_replace('`', '', $table);

			/* $table shall not contain any whitespaces nor comments */
			if ($this->_has_special($table) || strpos($table, ' ') !== false) {
				header('HTTP/1.1 500 Internal Server Error');
				die('join(): Enforced join() shall not contain any comments nor whitespaces on $table name.');
			}

			/* $on shall not contain any comments */
			/* FIXME: TODO: Strip '`' from $on and analyze each component, setting '`' as required . Also check '.' */
			if ($this->_has_special($on)) {
				header('HTTP/1.1 500 Internal Server Error');
				die('join(): Enforced join() shall not contain any comments on $on clause.');
			}

			$this->_q_join .= ' ' . strtoupper($type) . ' JOIN `' . $table . '` ON ' . $on . ' ';
		} else {
			$this->_q_join .= ' ' . strtoupper($type) . ' JOIN ' . $table . ' ON ' . $on . ' ';
		}

		return $this;
	}

	public function where($field_cond = NULL, $value = NULL, $enforce = true, $or = false, $in = false, $like = false, $not = false, $is_null = false, $is_not_null = false, $between = false) {
		/* Sanity checks */
		if ($in && $like) {
			header('HTTP/1.1 500 Internal Server Error');
			die('where(): IN and LIKE are mutual exclusive.');
		}

		if ($not && !$in && !$like) {
			header('HTTP/1.1 500 Internal Server Error');
			die('where(): NOT only accepted when IN or LIKE are used.');
		}

		if (!$field_cond) {
			header('HTTP/1.1 500 Internal Server Error');
			die('where(): No fields were specified.');
		}

		if (!$this->_q_where)
			$this->_q_where = ' WHERE ';
		else if ($or)
			$this->_q_where .= ' OR ';
		else
			$this->_q_where .= ' AND ';

		$raw_value = false;

		if ($enforce !== true && $enforce !== false) {
			switch ($enforce) {
				case 'raw': {
					$enforce = false;
					$raw_value = true;
				} break;
				case 'name_only': {
					$enforce = true;
					$raw_value = true;
				} break;
				default: {
					header('HTTP/1.1 500 Internal Server Error');
					die('where(): Invalid value for $enforce (only boolean true, boolean false, string \'raw\' and string \'name_only\' are accepted).');
				}
			}
		}

		/* Escape field if enforce is set */
		if ($enforce) {
			/* Filter any previous escapes */
			$field_cond = str_replace('`', '', $field_cond);

			/* ... and grant that there aren't any other undesired characters */
			if ($this->_has_special($field_cond)) {
				header('HTTP/1.1 500 Internal Server Error');
				die('where(): Field names cannot contain comments nor special charaters when enfoce is used.');
			}

			/* Split and glue - FIXME: Grant that if a ' ' exists, the seconds field is effectively a VALID comparator */
			                 /* Also grant that there is a space between field and comparator (it currently may be joined) */
			$field_cond_parsed = explode(' ', $field_cond); /* Expected to have 2 arguments (field name and comparator) */
			$field_cond = $this->_table_field_enforce($field_cond_parsed[0]) . ' ' . implode(' ', array_slice($field_cond_parsed, 1));
			$field_cond = rtrim($field_cond, ' ');
		}

		$this->_q_where .= ' ' . $field_cond;

		/* Check the special cases of [ IS NULL / IS NOT NULL ] */
		if ($value === NULL) {
			if ($is_null && strpos($field_cond, ' ') === false) {
				$this->_q_where .= ' IS NULL ';
			} else if ($is_not_null && strpos($field_cond, ' ') === false) {
				$this->_q_where .= ' IS NOT NULL ';
			} else {
				header('HTTP/1.1 500 Internal Server Error');
				die('where(): For NULL comparations, use is_null(), is_not_null(), or_is_null() and or_is_not_null() functions and do not use any comparators on first parameter.');
			}

			return $this;
		}

		if ($not)
			$this->_q_where .= ' NOT ';

		if ($between) {
			$value[0] = $this->_convert_boolean($value[0]);
			$value[1] = $this->_convert_boolean($value[1]);

			if ($raw_value) {
				$this->_q_where .= ' BETWEEN ' . $value[0] . ' AND ' . $value[1] . ' '; /* Raw value. Won't be passed as argument to prepared statements */
			} else {
				$this->_q_where .= ' BETWEEN ? AND ? ';
			}
		} else if ($in) {
			$raw_value = false; /* Currently, raw values are unsupported under IN clauses */

			$this->_q_where .= ' IN (';
			for ($i = 0; $i < count($value); $i ++) {
				/* Convert booleans */
				$value[$i] = $this->_convert_boolean($value[$i]);

				/* Add prepared statement argument indicator */
				$this->_q_where .= '?,';
			}
			$this->_q_where = rtrim($this->_q_where, ',') . ') ';
		} else if ($like) {
			if ($raw_value) {
				$this->_q_where .= ' LIKE ' . $value . ' '; /* Raw value. Won't be passed as argument to prepared statements */
			} else {
				$this->_q_where .= ' LIKE ? ';
			}
		} else if (strpos($field_cond, '=') !== false || strpos($field_cond, '>') !== false || strpos($field_cond, '<') !== false) {
			if ($raw_value) {
				$this->_q_where .= ' ' . $value . ' '; /* Raw value. Won't be passed as argument to prepared statements */
			} else {
				$this->_q_where .= ' ? ';
			}
		} else {
			if ($raw_value) {
				$this->_q_where .= ' = ' . $value . ' '; /* Raw value. Won't be passed as argument to prepared statements */
			} else {
				$this->_q_where .= ' = ? ';
			}
		}

		/* If raw values are being used, we must not add them to query arguments */
		if ($raw_value)
			return $this;

		/* Push value into data array */
		if ($in || $between) {
			/* $value is an array */
			foreach ($value as $v)
				array_push($this->_q_args, $this->_convert_boolean($v));
		} else {
			/* $value is a string */
			array_push($this->_q_args, $this->_convert_boolean($value));
		}

		return $this;
	}

	public function where_append($raw) {
		$this->_q_where .= $raw;
	}

	public function or_where($field_cond = NULL, $value = NULL, $enforce = true) {
		return $this->where($field_cond, $value, $enforce, true /* OR */);
	}

	public function or_where_in($field_cond = NULL, $value = NULL, $enforce = true) {
		return $this->where($field_cond, $value, $enforce, true /* OR */, true /* IN */);
	}

	public function or_where_not_in($field_cond = NULL, $value = NULL, $enforce = true) {
		return $this->where($field_cond, $value, $enforce, true /* OR */, true /* IN */, false /* like */, true /* NOT */);
	}

	public function where_in($field_cond = NULL, $value = NULL, $enforce = true) {
		return $this->where($field_cond, $value, $enforce, false /* or */, true /* IN */);
	}

	public function where_not_in($field_cond = NULL, $value = NULL, $enforce = true) {
		return $this->where($field_cond, $value, $enforce, false /* or */, true /* IN */, false /* like */, true /* NOT */);
	}

	public function like($field_cond = NULL, $value = NULL, $enforce = true) {
		return $this->where($field_cond, $value, $enforce, false /* or */, false /* in */, true /* LIKE */);
	}

	public function or_like($field_cond = NULL, $value = NULL, $enforce = true) {
		return $this->where($field_cond, $value, $enforce, true /* OR */, false /* in */, true /* LIKE */);
	}

	public function not_like($field_cond = NULL, $value = NULL, $enforce = true) {
		return $this->where($field_cond, $value, $enforce, false /* or */, false /* in */, true /* LIKE */, true /* NOT */);
	}

	public function or_not_like($field_cond = NULL, $value = NULL, $enforce = true) {
		return $this->where($field_cond, $value, $enforce, true /* OR */, false /* in */, true /* LIKE */, true /* NOT */);
	}

	public function is_null($field_cond = NULL, $enforce = true) {
		return $this->where($field_cond, NULL, $enforce, false /* or */, false /* in */, false /* like */, false /* not */, true /* IS NULL */);
	}

	public function or_is_null($field_cond = NULL, $enforce = true) {
		return $this->where($field_cond, NULL, $enforce, true /* OR */, false /* in */, false /* like */, false /* not */, true /* IS NULL */);
	}

	public function is_not_null($field_cond = NULL, $enforce = true) {
		return $this->where($field_cond, NULL, $enforce, false /* or */, false /* in */, false /* like */, false /* not */, false /* is null */, true /* IS NOT NULL */);
	}

	public function or_is_not_null($field_cond = NULL, $enforce = true) {
		return $this->where($field_cond, NULL, $enforce, true /* OR */, false /* in */, false /* like */, false /* not */, false /* is null */, true /* IS NOT NULL */);
	}

	public function between($field_cond = NULL, $value1 = NULL, $value2 = NULL, $enforce = true) {
		return $this->where($field_cond, array($value1, $value2), $enforce, false /* or */, false /* in */, false /* like */, false /* not */, false /* is null */, false /* is not null */, true /* BETWEEN */);
	}

	public function not_between($field_cond = NULL, $value1 = NULL, $value2 = NULL, $enforce = true) {
		return $this->where($field_cond, array($value1, $value2), $enforce, false /* or */, false /* in */, false /* like */, true /* NOT */, false /* is null */, false /* is not null */, true /* BETWEEN */);
	}

	public function or_between($field_cond = NULL, $value1 = NULL, $value2 = NULL, $enforce = true) {
		return $this->where($field_cond, array($value1, $value2), $enforce, true /* OR */, false /* in */, false /* like */, false /* not */, false /* is null */, false /* is not null */, true /* BETWEEN */);
	}

	public function or_not_between($field_cond = NULL, $value1 = NULL, $value2 = NULL, $enforce = true) {
		return $this->where($field_cond, array($value1, $value2), $enforce, true /* OR */, false /* in */, false /* like */, true /* NOT */, false /* is null */, false /* is not null */, true /* BETWEEN */);
	}

	public function group_by($fields, $enforce = true) {
		if (!$fields) {
			header('HTTP/1.1 500 Internal Server Error');
			die('group_by(): No fields were specified.');
		}

		if (gettype($fields) == "string") {
			if ($enforce) {
				/* Filter any previous escapes */
				$fields = str_replace('`', '', $fields);

				/* Grant that there aren't any comments to block */
				if ($this->_has_special($fields)) {
					header('HTTP/1.1 500 Internal Server Error');
					die('group_by(): Enforced function shall not contain comments in it.');
				}

				/* Split fields by ',' */
				$fields_list = explode(',', $fields);
				
				$fields = '';

				/* Glue enforced fields */
				foreach ($fields_list as $f) {
					$fields = $this->_table_field_enforce($f) . ',';
				}

				$fields = rtrim($fields, ',');
			} else {
				$fields = implode(',', $fields);
			}

			$this->_q_group_by = ' GROUP BY ' . $fields . ' ';
		} else if (gettype($fields) == "array") {
			if ($enforce) {
				/* Grant that there aren't any comments to block */
				foreach ($fields as $field) {
					if ($this->_has_special($field)) {
						header('HTTP/1.1 500 Internal Server Error');
						die('group_by(): Enforced function shall not contain comments in it.');
					}
				}

				/* Escape each field during implode (enforce) */
				$this->_q_group_by = ' GROUP BY ';

				foreach ($fields as $f) {
					/* Filter any previous escapes */
					$f = str_replace('`', '', $f);

					$this->_q_group_by .= $this->_table_field_enforce($f) . ',';
				}

				$this->_q_group_by = rtrim($this->_q_group_by, ',');
			} else {
				$this->_q_group_by = ' GROUP BY ' . implode(',', $fields);
			}
		} else {
			header('HTTP/1.1 500 Internal Server Error');
			die('group_by(): Invalid argument type.');
		}

		return $this;
	}

	public function having($fields_cond, $value = null, $enforce = true, $or = false) {
		if (!$fields_cond) {
			header('HTTP/1.1 500 Internal Server Error');
			die('having(): No fields were specified.');
		}

		$data = array();

		if (gettype($fields_cond) == "string") {
			if ($value) {
				/* Value is not part of first argument */
				if (strpos($fields_cond, '=') !== false|| strpos($fields_cond, '>') !== false || strpos($fields_cond, '<') !== false) {
					if ($enforce) {
						/* Filter any previous escapes */
						$fields_cond = str_replace('`', '', $fields_cond);

						/* Check if there are any comments to block */
						if ($this->_has_special($fields_cond)) {
							header('HTTP/1.1 500 Internal Server Error');
							die('having(): Enforced function shall not contain comments in it.');
						}

						/* Separate fields by ' ' (only 2 fields expected) and rejoin them by escaping the first */
						$field = explode(' ', $fields_cond);
						$fields_cond = ' ' . $this->_table_field_enforce($field[0]) . ' ' . $field[1];
					}

					$this->_q_having = ' ' . $fields_cond . ' ? ';
					array_push($this->_q_args, $this->_convert_boolean($value));
				} else {
					if ($enforce) {
						/* Check if there are any comments to block */
						if ($enforce) {
							if ($this->_has_special($fields_cond)) {
								header('HTTP/1.1 500 Internal Server Error');
								die('having(): Enforced function shall not contain comments in it.');
							}
						}

						$this->_q_having = ' ' . $this->_table_field_enforce($fields_cond) . ' = ? ';
						array_push($this->_q_args, $this->_convert_boolean($value));
					} else
						$this->_q_having = ' ' . $fields_cond . ' = ? ';
						array_push($this->_q_args, $this->_convert_boolean($value));
				}
			} else {
				/* Value is part of the first argument and enforce value is disregarded */
				$this->_q_having = ' ' . $fields_cond . ' ';
			}
		} else if (gettype($fields_cond) == "array") {
			$this->_q_having = '';
			foreach ($fields_cond as $k => $v) {
				/* Check if there are any comments to block */
				if ($enforce) {
					/* Filter any previous escapes */
					$k = str_replace('`', '', $k);

					if ($this->_has_special($k)) {
						header('HTTP/1.1 500 Internal Server Error');
						die('having(): Enforced function shall not contain comments in it.');
					}
				}

				if ($this->_q_having) {
					if ($or) {
						$this->_q_having .= ' OR ';
					} else {
						$this->_q_having .= ', ';
					}
				}

				/* Check if there's already a comparator */
				if (strpos($k, '=') !== false || strpos($k, '>') !== false || strpos($k, '<') !== false) {
					/* Escape fields if enforce is set */
					if ($enforce) {
						/* NOTE: $k must have a space separating field name from comparator */
						$c = explode(' ', $k);

						$k = $this->_table_field_enforce($c[0]) . ' ' . $c[1];
					}

					$this->_q_having .= ' ' . $k . ' ? ';
					array_push($this->_q_args, $this->_convert_boolean($v));
				} else {
					/* Escape fields if enforce is set */
					$this->_q_having .= ' ' . ($enforce ? $this->_table_field_enforce($k) : $k) . ' = ? '; /* If no comparator, assume = as default */

					array_push($this->_q_args, $this->_convert_boolean($v));
				}
			}
		}

		$this->_q_having = ' HAVING ' . $this->_q_having;

		return $this;
	}

	public function or_having($fields_cond, $value = null, $enforce = true) {
		return $this->having($fields_cond, $value, $enforce, true);
	}

	public function order_by($field, $order = 'ASC', $enforce = true) {
		if (!$field) {
			header('HTTP/1.1 500 Internal Server Error');
			die('order_by(): No fields were specified.');
		}

		$order = strtoupper($order);

		/* Validate $order */
		if ($order != 'ASC' && $order != 'DESC') {
			header('HTTP/1.1 500 Internal Server Error');
			die('order_by(): $order shall only assume ASC or DESC.');
		}

		/* Include keyword or separator, based on previous _q_order_by value */
		if ($this->_q_order_by) {
			$this->_q_order_by .= ', ';
		} else {
			$this->_q_order_by = ' ORDER BY ';
		}

		/* Extra validation and escaping if $enforce is set */
		if ($enforce) {
			/* Filter any previous escapes */
			$field = str_replace('`', '', $field);

			if ($this->_has_special($field)) {
				header('HTTP/1.1 500 Internal Server Error');
				die('order_by(): Enforced function shall not contain comments in it.');
			}
			$this->_q_order_by .= ' ' . $this->_table_field_enforce($field) . ' ' . $order;
		} else {
			$this->_q_order_by .= ' ' . $field . ' ' . $order;
		}

		return $this;
	}

	public function limit($limit, $offset = NULL, $enforce = true) {
		if ($enforce) {
			/* Validate fields */
			if ($this->_has_special($limit) || $this->_has_special($offset)) {
				header('HTTP/1.1 500 Internal Server Error');
				die('limit(): Enforced function shall not contain comments in it.');
			}
		}
		
		/* Grant that parameters are integers */
		$limit = intval($limit);
		$offset = intval($offset);

		if ($offset !== NULL) {
			$this->_q_limit = ' LIMIT ' . $offset . ', ' . $limit . ' ';
		} else {
			$this->_q_limit = ' LIMIT ' . $limit . ' ';
		}

		return $this;
	}

	public function table_exists($table, $enforce = true) {
		if ($enforce) {
			/* Validate table name */
			if ($this->_has_special($table)) {
				header('HTTP/1.1 500 Internal Server Error');
				die('table_exists(): Enforced function shall not contain comments in it.');
			}

			$table = str_replace('`', '', $table);
		}

		$q = $this->query('SHOW TABLES LIKE \'' . $table . '\'');

		if ($q->num_rows() > 0)
			return true;

		return false;
	}

	public function table_rename($table, $new_table, $enforce = true) {
		if ($enforce) {
			/* Validate table names */
			if ($this->_has_special($table) || $this->_has_special($new_table)) {
				header('HTTP/1.1 500 Internal Server Error');
				die('table_rename(): Enforced function shall not contain comments in it.');
			}

			$table = str_replace('`', '', $table);
			$new_table = str_replace('`', '', $new_table);
		}

		/* FIXME: MySQL/MariaDB only */
		return $this->query('RENAME TABLE `' . $table . '` TO `' . $new_table . '`');
	}

	public function table_create($table, $first_column, $column_type, $is_null = false, $auto_increment = true, $primary_key = true, $enforce = true) {
		if ($enforce) {
			/* Validate table and column names */
			if ($this->_has_special($table) || $this->_has_special($first_column)) {
				header('HTTP/1.1 500 Internal Server Error');
				die('table_create(): Enforced function shall not contain comments in it.');
			}

			/* Validate column type */
			if (!preg_match('/^([a-zA-Z]+|[a-zA-Z]+\([0-9\,\.]+\))$/i', $column_type)) {
				header('HTTP/1.1 500 Internal Server Error');
				die('table_create(): Invalid format for column type.');
			}

			$table = str_replace('`', '', $table);
			$first_column = str_replace('`', '', $first_column);
		}

		return $this->query('CREATE TABLE `' . $table . '` (`' . $first_column . '` ' . $column_type . ($is_null ? ' NULL' : ' NOT NULL') . ($auto_increment ? ' AUTO_INCREMENT' : '') . ($primary_key ? ' PRIMARY KEY' : '') . ')');
	}

	public function table_drop($table, $if_exists = false, $force = false, $enforce = true) {
		if ($enforce) {
			/* Validate table name */
			if ($this->_has_special($table)) {
				header('HTTP/1.1 500 Internal Server Error');
				die('table_drop(): Enforced function shall not contain comments in it.');
			}

			$table = str_replace('`', '', $table);
		}

		if ($force) {
			/* FIXME: MySQL/MariaDB Only */
			$this->query('SET foreign_key_checks = 0');
		}

		$ret = $this->query('DROP TABLE' . ($if_exists ? ' IF EXISTS' : '') . ' `' . $table . '`');

		if ($force) {
			/* FIXME: MySQL/MariaDB Only */
			$this->query('SET foreign_key_checks = 1');
		}

		return $ret;
	}

	public function table_column_exists($table, $column, $enforce = true) {
		if ($enforce) {
			/* Validate table and column names */
			if ($this->_has_special($table) || $this->_has_special($column)) {
				header('HTTP/1.1 500 Internal Server Error');
				die('table_column_exists(): Enforced function shall not contain comments in it.');
			}

			$table = str_replace('`', '', $table);
			$column = str_replace('`', '', $column);
		}

		$q = $this->query('SHOW COLUMNS FROM `' . $table . '` LIKE \'' . $column . '\'');

		if ($q->num_rows() > 0)
			return true;

		return false;
	}

	public function table_column_create($table, $column, $type, $is_null = true, $default = 'NULL', $after = NULL, $enforce = true) {
		if ($enforce) {
			/* Validate table and column names */
			if ($this->_has_special($table) || $this->_has_special($column)) {
				header('HTTP/1.1 500 Internal Server Error');
				die('table_column_create(): Enforced function shall not contain comments in it.');
			}

			/* Validate column type */
			if (!preg_match('/^([a-zA-Z]+|[a-zA-Z]+\([0-9\,\.]+\))$/i', $type)) {
				header('HTTP/1.1 500 Internal Server Error');
				die('table_column_create(): Invalid format for column type.');
			}

			/* Validate default value */
			if ($default[0] != '\'') { /* If the default value isn't a quoted string ... */
				if ($this->_has_special($default)) { /* ... and contains special characters in it ... */
					header('HTTP/1.1 500 Internal Server Error');
					die('table_column_create(): Default value isn\'t a quoted string but contains special characters on it.');
				}
			} else { /* If the default value is a quoted string ... */
				if (substr($default[0], -1) != '\'') { /* ... and it does not end with a quote ... */
					header('HTTP/1.1 500 Internal Server Error');
					die('table_column_create(): Default value starts with a quote, indicating it\'s a quoted string, but it lacks a quote as its last character.');
				}

				/* Strip enclosing quotes from default value */
				$default = trim($default, '\'');

				/* Escape $default contents */
				$default = $this->quote($default);
			}
				
			$table = str_replace('`', '', $table);
			$column = str_replace('`', '', $column);
		}

		return $this->query('ALTER TABLE `' . $table . '` ADD COLUMN `' . $column . '` ' . $type . ($is_null ? ' NULL' : ' NOT NULL') . ' DEFAULT ' . $default . ($after ? ' AFTER `' . $after . '`' : ''));
	}

	public function table_column_drop($table, $column, $enforce = true) {
		if ($enforce) {
			/* Validate table and column names */
			if ($this->_has_special($table) || $this->_has_special($column)) {
				header('HTTP/1.1 500 Internal Server Error');
				die('table_column_drop(): Enforced function shall not contain comments in it.');
			}

			$table = str_replace('`', '', $table);
			$column = str_replace('`', '', $column);
		}

		return $this->query('ALTER TABLE `' . $table . '` DROP COLUMN `' . $column . '`');
	}

	public function table_column_change($table, $column, $new_column, $type, $is_null = true, $default = 'NULL', $after = NULL, $enforce = true) {
		if ($enforce) {
			/* Validate table and column names */
			if ($this->_has_special($table) || $this->_has_special($column) || $this->_has_special($new_column)) {
				header('HTTP/1.1 500 Internal Server Error');
				die('table_column_change(): Enforced function shall not contain comments in it.');
			}

			/* Validate column type */
			if (!preg_match('/^([a-zA-Z]+|[a-zA-Z]+\([0-9\,\.]+\))$/i', $type)) {
				header('HTTP/1.1 500 Internal Server Error');
				die('table_column_change(): Invalid format for column type.');
			}

			/* Validate default value */
			if ($default[0] != '\'') { /* If the default value isn't a quoted string ... */
				if ($this->_has_special($default)) { /* ... and contains special characters in it ... */
					header('HTTP/1.1 500 Internal Server Error');
					die('table_column_change(): Default value isn\'t a quoted string but contains special characters on it.');
				}
			} else { /* If the default value is a quoted string ... */
				if (substr($default[0], -1) != '\'') { /* ... and it does not end with a quote ... */
					header('HTTP/1.1 500 Internal Server Error');
					die('table_column_change(): Default value starts with a quote, indicating it\'s a quoted string, but it lacks a quote as its last character.');
				}

				/* Strip enclosing quotes from default value */
				$default = trim($default, '\'');

				/* Escape $default contents */
				$default = $this->quote($default);
			}
				
			$table = str_replace('`', '', $table);
			$column = str_replace('`', '', $column);
			$new_column = str_replace('`', '', $new_column);
		}

		return $this->query('ALTER TABLE `' . $table . '` CHANGE COLUMN `' . $column . '` `' . $new_column . '` ' . $type . ($is_null ? ' NULL' : ' NOT NULL') . ' DEFAULT ' . $default . ($after ? ' AFTER `' . $after . '`' : ''));
	}

	public function table_column_unique_add($table, $column, $enforce = true) {
		if ($enforce) {
			/* Validate table and column names */
			if ($this->_has_special($table) || $this->_has_special($column)) {
				header('HTTP/1.1 500 Internal Server Error');
				die('table_column_unique_add(): Enforced function shall not contain comments in it.');
			}

			$table = str_replace('`', '', $table);
			$column = str_replace('`', '', $column);
		}

		return $this->query('ALTER TABLE `' . $table . '` ADD CONSTRAINT uw_unique_' . $table . '_' . $column . ' UNIQUE (`' . $column . '`)');
	}

	public function table_column_unique_drop($table, $column, $if_exists = false, $if_exists_from_table = NULL, $enforce = true) {
		if ($enforce) {
			/* Validate table and column names */
			if ($this->_has_special($table) || $this->_has_special($column)) {
				header('HTTP/1.1 500 Internal Server Error');
				die('table_column_unique_drop(): Enforced function shall not contain comments in it.');
			}

			if ($if_exists_from_table !== NULL && $this->_has_special($if_exists_from_table)) {
				header('HTTP/1.1 500 Internal Server Error');
				die('table_column_unique_drop(): Enforced function shall not contain comments in it.');
			}

			$table = str_replace('`', '', $table);
			$column = str_replace('`', '', $column);

			if ($if_exists_from_table !== NULL)
				$if_exists_from_table = str_replace('`', '', $if_exists_from_table);
		}

		/* FIXME: MySQL/MariaDB only */
		if ($if_exists) {
			$q = $this->query('SHOW INDEX FROM `' . ($if_exists_from_table !== NULL ? $if_exists_from_table : $table) . '` WHERE KEY_NAME = \'uw_unique_' . $table . '_' . $column . '\'');

			if (!$q->num_rows())
				return true;
		}

		return $this->query('ALTER TABLE `' . $table . '` DROP INDEX uw_unique_' . $table . '_' . $column);
	}

	public function table_key_column_foreign_add($table, $column, $foreign_table, $foreign_column, $cascade_delete = false, $cascade_update = false, $enforce = true) {
		if ($enforce) {
			/* Validate table and column names */
			if ($this->_has_special($table) || $this->_has_special($column) || $this->_has_special($foreign_table) || $this->_has_special($foreign_column)) {
				header('HTTP/1.1 500 Internal Server Error');
				die('table_key_column_foreign_add(): Enforced function shall not contain comments in it.');
			}

			$table = str_replace('`', '', $table);
			$foreign_table = str_replace('`', '', $foreign_table);
			$column = str_replace('`', '', $column);
			$foreign_column = str_replace('`', '', $foreign_column);
		}

		return $this->query('ALTER TABLE `' . $table . '` ADD CONSTRAINT uw_fk_' . $table . '_' . $column . ' FOREIGN KEY (`' . $column . '`) REFERENCES `' . $foreign_table . '`(`' . $foreign_column . '`)' . ($cascade_delete ? ' ON DELETE CASCADE' : '') . ($cascade_update ? ' ON UPDATE CASCADE' : ''));
	}

	public function table_key_column_foreign_drop($table, $column, $index_name = NULL, $enforce = true) {
		if ($enforce) {
			/* Validate table and column names */
			if ($this->_has_special($table) || $this->_has_special($column)) {
				header('HTTP/1.1 500 Internal Server Error');
				die('table_key_column_foreign_drop(): Enforced function shall not contain comments in it.');
			}

			if ($index_name !== NULL && $this->_has_special($index_name)) {
				header('HTTP/1.1 500 Internal Server Error');
				die('table_key_column_foreign_drop(): Enforced function shall not contain comments in it.');
			}

			$table = str_replace('`', '', $table);
			$column = str_replace('`', '', $column);

			if ($index_name !== NULL)
				$index_name = str_replace('`', '', $index_name);
		}

		if (!$index_name)
			$index_name = 'uw_fk_' . $table . '_' . $column;

		/* FIXME: MySQL/MariaDB only */
		if (!$this->query('ALTER TABLE `' . $table . '` DROP FOREIGN KEY ' . $index_name))
			return false;

		if (!$this->query('ALTER TABLE `' . $table . '` DROP INDEX ' . $index_name))
			return false;

		return true;
	}

	public function get_compiled_select($table = NULL, $enforce = true, $reset = true) {
		$query = NULL;
		$data = NULL;

		if (!$table) {
			/* SELECT */
			if (!$this->_q_select) {
				$query = 'SELECT * ';
			} else {
				$query  = $this->_q_select . ' ';
			}
			
			/* DISTINCT */
			if ($this->_q_distinct)
				$query .= ' DISTINCT ';

			/* FROM */
			if (!$this->_q_from) {
				header('HTTP/1.1 500 Internal Server Error');
				die('get_compiled_select(): No argument supplied ($table) and no from() was called.');
			} else {
				$query .= ' ' . $this->_q_from . ' ';
			}

			/* JOIN */
			if ($this->_q_join)
				$query .= ' ' . $this->_q_join . ' ';

			/* WHERE */
			if ($this->_q_where)
				$query .= ' ' . $this->_q_where . ' ';

			/* GROUP BY */
			if ($this->_q_group_by)
				$query .= ' ' . $this->_q_group_by . ' ';

			/* HAVING */
			if ($this->_q_having)
				$query .= ' ' . $this->_q_having . ' ';

			/* ORDER BY */
			if ($this->_q_order_by)
				$query .= ' ' . $this->_q_order_by . ' ';

			/* LIMIT */
			if ($this->_q_limit)
				$query .= ' ' . $this->_q_limit . ' ';

			$data = $this->_q_args;
		} else {
			/* $table shall not contain any whitespaces nor comments */
			if ($enforce) {
				if ($this->_has_special($table) || strpos($table, ' ') !== false) {
					header('HTTP/1.1 500 Internal Server Error');
					die('get_compiled_select(): Enforced functions shall not contain any comments in their protected arguments.');
				}

				$query = 'SELECT * FROM `' . $table . '`';
			} else {
				$query = 'SELECT * FROM ' . $table;
			}
		}

		/* Reset query data */
		if ($reset)
			$this->_q_reset_all();

		/* Store query objects */
		$this->_q_objects = array($query, $data);

		/* Return the prepared statement objects */
		return $this->_q_objects;
	}

	public function get_compiled_select_str($table = NULL, $enforce = true, $reset = true) {
		$query_obj = $this->get_compiled_select($table, $enforce, $reset);

		return $this->_query_aggregate_args($query_obj[0], $query_obj[1], $reset);
	}

	public function get($table = NULL, $enforce = true) {
		/* Basically, if from() wasn't called and get() receives a table, pass this table to from */
		/* FIXME: This is dangerous... we need to check every _q_* and not just _q_where */
		if ($table && $this->_q_where && !$this->_q_from) {
			$this->from($table);
			$table = NULL;
		}

		$query_data = $this->get_compiled_select($table, $enforce);

		/* Reset all stored query elements */
		$this->_q_reset_all();

		/* Execute Query */
		return $this->query($query_data[0], $query_data[1]);
	}

	public function get_where($table, $fields_cond, $limit = NULL, $offset = NULL, $enforce = true) {
		$query = NULL;
		$data = array();

		/* SELECT */
		if ($enforce) {
			/* $table shall not contain any whitespaces nor comments */
			if ($this->_has_special($table) || strpos($table, ' ') !== false) {
				header('HTTP/1.1 500 Internal Server Error');
				die('get_where(): Enforced functions shall not contain any comments in their protected arguments.');
			}

			$query = 'SELECT * FROM `' . $table . '` ';
		} else {
			$query = 'SELECT * FROM ' . $table . ' ';
		}

		/* WHERE */
		foreach ($fields_cond as $k => $v)
			$this->where($k, $v, $enforce);

		/* Merge args and query */
		$data = array_merge($data, $this->_q_args);
		$query .= ' ' . $this->_q_where . ' ';

		/* LIMIT */
		if ($limit !== NULL) {
			$this->limit($limit, $offset);

			$query .= ' ' . $this->_q_limit . ' ';
		}

		/* Reset all stored query elements */
		$this->_q_reset_all();

		/* Execute Query */
		return $this->query($query, $data);
	}

	public function count_all_results($table = NULL) {
		if (!$table) {
			$this->get_compiled_select();
			$this->query($this->_q_objects[0], $this->_q_objects[1]);
		} else {
			$this->get($table);
		}

		return $this->num_rows();
	}

	public function count_all($table) {
		return $this->count_all_results($table);
	}

	public function insert($table, $kv, $enforce = true) {
		if (!$table) {
			header('HTTP/1.1 500 Internal Server Error');
			die('insert(): No table was specified.');
		}

		if (!$kv) {
			header('HTTP/1.1 500 Internal Server Error');
			die('insert(): No K/V pairs were specified.');
		}

		if ($enforce) {
			/* $table shall not contain any whitespaces nor comments */
			if ($this->_has_special($table) || strpos($table, ' ') !== false) {
				header('HTTP/1.1 500 Internal Server Error');
				die('insert(): Enforced functions shall not contain any comments in their protected arguments.');
			}
		}

		$values = '(';
		$data = array();

		if ($enforce) {
			$query = 'INSERT INTO `' . $table . '` (';
		} else {
			$query = 'INSERT INTO ' . $table . ' (';
		}

		/* Iterate k/v */
		foreach ($kv as $k => $v) {
			if ($enforce && $this->_has_special($k)) {
				header('HTTP/1.1 500 Internal Server Error');
				die('insert(): Enforced functions shall not contain any comments in their protected arguments (K/V).');
			}

			$query .= ($enforce ? $this->_table_field_enforce($k) : $k) . ',';
			$values .= '?,';
			array_push($data, $this->_convert_boolean($v));
		}

		$values = rtrim($values, ',') . ')';
		$query = rtrim($query, ',') . ') VALUES ' . $values;

		/* Reset all stored query elements */
		$this->_q_reset_all();

		return $this->query($query, $data);
	}

	public function update($table, $kv, $enforce = true) {
		if (!$table) {
			header('HTTP/1.1 500 Internal Server Error');
			die('update(): No table was specified.');
		}

		if (!$kv) {
			header('HTTP/1.1 500 Internal Server Error');
			die('update(): No K/V pairs were specified.');
		}

		if ($enforce) {
			/* $table shall not contain any whitespaces nor comments */
			if ($this->_has_special($table) || strpos($table, ' ') !== false) {
				header('HTTP/1.1 500 Internal Server Error');
				die('update(): Enforced functions shall not contain any comments in their protected arguments.');
			}
		}

		$data = array();

		if ($enforce) {
			$query = 'UPDATE `' . $table . '` SET ';
		} else {
			$query = 'UPDATE ' . $table . ' SET ';
		}

		foreach ($kv as $k => $v) {
			if ($enforce && $this->_has_special($k)) {
				header('HTTP/1.1 500 Internal Server Error');
				die('update(): Enforced functions shall not contain any comments in their protected arguments (K/V).');
			}

			$query .= ($enforce ? $this->_table_field_enforce($k) : $k) . ' = ?,';
			array_push($data, $this->_convert_boolean($v));
		}

		$query = rtrim($query, ',');

		/* WHERE */
		$query .= ' ' . $this->_q_where;

		/* Aggregate args */
		$data = array_merge($data, $this->_q_args);

		/* Reset all stored query elements */
		$this->_q_reset_all();

		/* Execute query */
		return $this->query($query, $data);
	}

	public function delete($table, $fields_cond = NULL, $enforce = true) {
		if (!$table) {
			header('HTTP/1.1 500 Internal Server Error');
			die('delete(): No table was specified.');
		}

		if ($enforce) {
			/* $table shall not contain any whitespaces nor comments */
			if ($this->_has_special($table) || strpos($table, ' ') !== false) {
				header('HTTP/1.1 500 Internal Server Error');
				die('delete(): Enforced functions shall not contain any comments in their protected arguments.');
			}
		}

		$data = array();

		if ($enforce) {
			$query = 'DELETE FROM `' . $table . '` ';
		} else {
			$query = 'DELETE FROM ' . $table . ' ';
		}

		/* WHERE */
		if ($fields_cond) {
			foreach ($fields_cond as $k => $v)
				$this->where($k, $v, $enforce);
		}

		$query .= ' ' . $this->_q_where;
		$data = array_merge($data, $this->_q_args);

		/* Reset all stored query elements */
		$this->_q_reset_all();

		return $this->query($query, $data);
	}

	public function __construct() {
		global $config;

		parent::__construct();

		/* If no database is configured, do not try to load anything */
		if (!isset($config['database']) || !count($config['database']))
			return;

		/* Iterate over the configured databases */
		foreach ($config['database'] as $dbalias => $dbdata) {
			/* Set default database (first ocurrence) */
			if (!$this->_cur_db) {
				$this->_cur_db = $dbalias;
				$this->database = $config['database'][$dbalias]['name'];
			}

			/* FIXME: TODO: Remove the PDO() instantiation from this __construct().
			 * Databases shall be loaded through load() method only.
			 * Also implement the database autoload, or force the 'default' database to be loaded at start
			 */

			/* Initialize PDO attributes */
			$attr = array();
			
			/* Use presistent connections? */
			if (isset($config['database'][$dbalias]['persistent']) && $config['database'][$dbalias]['persistent'] === true)
				$attr[PDO::ATTR_PERSISTENT] = true;

			if (isset($config['database'][$dbalias]['strict']) && $config['database'][$dbalias]['strict'] === true && $config['database'][$dbalias]['driver'] == 'mysql')
				$attr[PDO::MYSQL_ATTR_INIT_COMMAND] = 'SET sql_mode = "STRICT_ALL_TABLES"';

			/* Try to connect to the database */
			try {
				/* FIXME: For MySQL and PostgreSQL drivers the following code will work fine.
				 *        Currently unsupported drivers: SQLServer and Oracle
				 */
				$this->_db[$dbalias] =
					new PDO(
						$config['database'][$dbalias]['driver'] . ':' .
						'host=' . $config['database'][$dbalias]['host'] . ';' .
						'port=' . $config['database'][$dbalias]['port'] . ';' .
						'dbname=' . $config['database'][$dbalias]['name'] . ';' .
						'charset=' . $config['database'][$dbalias]['charset'],
						$config['database'][$dbalias]['username'],
						$config['database'][$dbalias]['password'],
						$attr
					);
			} catch (PDOException $e) {
				/* Something went wrong ... */
				error_log('Database connection error (dbname: ' . $config['database'][$dbalias]['name'] . '): ' . $e);
				header('HTTP/1.1 503 Service Unavailable');
				die('Unable to connect to database.');
			}
		}
	}
	
	public function __destruct() {
		/* If there isn't anything loaded, do not try to close anything */
		if (!count($this->_db))
			return;

		/* Close connections */
		foreach ($this->_db as $dbalias => $dbconn) {
			$this->_db[$dbalias] = NULL;
		}
	}

	public function close() {
		$this->__destruct();
	}

	public function error_code() {
		return $this->_db[$this->_cur_db]->errorCode();
	}

	public function test($host, $dbname, $dbuser, $dbpass, $port = '3306', $driver = 'mysql', $charset = 'utf8') {
		$db_test = NULL;

		try {
			/* FIXME: For MySQL and PostgreSQL drivers the following code will work fine.
			 *        Currently unsupported drivers: SQLServer and Oracle
			 */
			$db_test = new PDO(
					$driver . ':' .
					'host=' . $host . ';' .
					'port=' . $port . ';' .
					'dbname=' . $dbname . ';' .
					'charset=' . $charset,
					$dbuser,
					$dbpass
				);
		} catch (PDOException $e) {
			$db_test = NULL;
			return false;
		}

		if ($db_test === NULL)
			return false;

		$db_test = NULL; // Destruct

		return true;
	}

	public function load($dbalias, $return_self = false) {
		global $config;

		if (isset($this->_db[$dbalias])) {
			if ($return_self === true) {
				/* Clone DB object and update it accordingly */
				$db_ret = clone $this;
				$db_ret->_cur_db = $dbalias;
				$db_ret->database = $config['database'][$dbalias]['name'];

				/* Return the cloned and updated DB object */
				return $db_ret;
			} else {
				$this->_cur_db = $dbalias;
				$this->database = $config['database'][$dbalias]['name'];	
			}

			return true;
		} else {
			error_log('$this->db->load(): Attempting to load a database that is not properly configured: ' . $dbalias);
			return false;
		}
	}

	public function describe_table($table) {
		$q = $this->query('DESCRIBE `' . $table . '`');

		$desc = array();

		foreach ($q->result_array() as $row) {
			$desc[$row['Field']]['name'] = $row['Field'];
			$type = explode('(', rtrim($row['Type'], ')'));
			$desc[$row['Field']]['type'] = $type[0];
			$desc[$row['Field']]['max_length'] = $type[1];
			$desc[$row['Field']]['null'] = $row['Null'] == 'YES' ? true : false;
			$desc[$row['Field']]['primary_key'] = $row['Key'] == 'PRI' ? true : false;
			$desc[$row['Field']]['key'] = $row['Key'];
			$desc[$row['Field']]['default'] = $row['Default'];
			$desc[$row['Field']]['extra'] = $row['Extra'];
		}

		return $desc;
	}

	public function list_fields($table) {
		$desc = $this->describe_table($table);

		$flist = array();

		foreach ($desc as $field)
			array_push($flist, $field['name']);

		return $flist;
	}

	public function query($query, $data = NULL) {
		if (!$query) {
			header('HTTP/1.1 500 Internal Server Error');
			die('query(): No query was specified.');
		}

		error_log($query);

		if ($this->_cfg_use_stmt) {
			try {
				$this->_stmt = $this->_db[$this->_cur_db]->prepare($query);
				
				if (!$this->_stmt) {
					error_log('$this->db->query(): PDOStatement::prepare(): Failed.');
					header('HTTP/1.1 500 Internal Server Error');
					die('query(): PDOStatement::prepare(): Failed.');
				}
			} catch (PDOException $e) {
				error_log('$this->db->query(): PDOStatement::prepare(): ' . $e);
				header('HTTP/1.1 500 Internal Server Error');
				die('query(): PDOStatement::prepare(): Failed.');
			}

			if ($data) {
				if (!$this->_stmt->execute($data)) {
					header('HTTP/1.1 500 Internal Server Error');
					die('query(): Failed to execute prepared statement.');
				}
			} else {
				if (!$this->_stmt->execute()) {
					header('HTTP/1.1 500 Internal Server Error');
					die('query(): Failed to execute prepared statement.');
				}
			}
		} else {
			/* Execute query without prepared statement allocation */
			if (!($this->_stmt = $this->_db[$this->_cur_db]->query($this->_query_aggregate_args($query, $data)))) {
				header('HTTP/1.1 500 Internal Server Error');
				die('query(): Failed to execute query.');
			}
		}

		/* Reset query objects */
		$this->_q_objects = NULL; /* FIXME: TODO: This shall be only used on get(), get_where(), insert(), update() and delete() */

		/* Clone the PDOStatement object */
		$q_stmt = clone $this;
		$q_stmt->_stmt = $this->_stmt;

		/* Return the cloned PDOStatement object */
		return $q_stmt;
	}
	
	public function fetchone($assoc = true) {
		return ($assoc == true) ? $this->_stmt->fetch(PDO::FETCH_ASSOC) : $this->_stmt->fetch();
	}

	public function row() {
		return $this->fetchone(false);
	}

	public function row_array() {
		return $this->fetchone(true);
	}

	public function fetchall($assoc = true) {
		return ($assoc == true) ? $this->_stmt->fetchAll(PDO::FETCH_ASSOC) : $this->_stmt->fetchAll(PDO::FETCH_NUM);
	}

	public function result() {
		return $this->fetchall(false);
	}

	public function result_array() {
		return $this->fetchall();
	}

	public function num_rows() {
		return $this->_stmt->rowCount();
	}
	
	public function last_insert_id() {
		return $this->_db[$this->_cur_db]->lastInsertId();
	}
	
	public function trans_begin() {
		$this->_db[$this->_cur_db]->beginTransaction();
	}
	
	public function trans_commit() {
		/* Compatibility with old interface trans_status() */
		if ($this->_trans_status_invoked) {
			$this->_trans_status_invoked = false;
			return true;
		}

		try {
			$this->_db[$this->_cur_db]->commit();
			
			return true;
		} catch (PDOException $e) {
			error_log('$this->db->trans_commit(): PDO::commit(): ' . $e);
			return false;
		}
	}

	public function trans_status() { /* Old interface. Backward compatibility */
		$this->_trans_status_invoked = false;

		$ret = $this->trans_commit();

		$this->_trans_status_invoked = true;

		return $ret;
	}

	public function trans_rollback() {
		/* Compatibility with old interface trans_status() */
		$this->_trans_status_invoked = false;

		/* Perform the rollback */
		if ($this->_db[$this->_cur_db]->inTransaction())
			$this->_db[$this->_cur_db]->rollBack();
	}

	public function stmt_disable() {
		/* Disable prepared statements */
		$this->_cfg_use_stmt = false;
	}

	public function stmt_enable() {
		/* Enable prepared statements (enabled by default) */
		$this->_cfg_use_stmt = true;
	}

	public function dump($charset = 'utf8', $timezone = '+00:00', $newline = "\r\n") {
		global $config;

		if ($config['database'][$this->_cur_db]['driver'] != 'mysql') {
			header('HTTP/1.1 500 Internal Server Error');
			die('dump() is only available on database connectinos relying on mysql driver.');
		}

		/* Database dump header */
		$dump  = '--' . $newline;
		$dump .= '-- uWeb - MySQL / MariaDB Database Dump' . $newline;
		$dump .= '-- https://github.com/ucodev/uweb' . $newline;
		$dump .= '--' . $newline;
		$dump .= '-- Dump started at ' . date('Y-m-d H:i:s') . $newline;
		$dump .= '--' . $newline;

		/* Separator */
		$dump .= $newline;

		/* Pre-configuration of SQL dump (for MariaDB / MySQL) */
		$dump .= '/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;' . $newline;
		$dump .= '/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;' . $newline;
		$dump .= '/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;' . $newline;
		$dump .= '/*!40101 SET NAMES ' . $charset . ' */;' . $newline;
		$dump .= '/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;' . $newline;
		$dump .= '/*!40103 SET TIME_ZONE=\'' . $timezone . '\' */;' . $newline;
		$dump .= '/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;' . $newline;
		$dump .= '/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;' . $newline;
		$dump .= '/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE=\'NO_AUTO_VALUE_ON_ZERO\' */;' . $newline;
		$dump .= '/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;' . $newline;

		/* Start dumping data */
		foreach ($this->query('SHOW FULL TABLES')->result_array() as $entry) {
			/* Get table name and table type */
			$table = $entry['Tables_in_' . $config['database'][$this->_cur_db]['name']];
			$table_type = $entry['Table_type'];

			/* Only dump BASE TABLE types. (Ignore VIEW types) */
			if ($table_type != 'BASE TABLE')
				continue;

			/* Inform (comment) to which table the following structure belongs */
			$dump .= $newline;
			$dump .= '--' . $newline;
			$dump .= '-- Table structure for table `' . $table . '`' . $newline;
			$dump .= '--' . $newline;
			$dump .= $newline;

			/* Drop any previously existing table */
			$dump .= 'DROP TABLE IF EXISTS `' . $table . '`;' . $newline;

			/* Save the current charset and set a new client charset */
			$dump .= '/*!40101 SET @saved_cs_client     = @@character_set_client */;' . $newline;
			$dump .= '/*!40101 SET character_set_client = ' . $charset . ' */;' . $newline;

			/* Dump the table structure */
			$dump .= $this->query('SHOW CREATE TABLE ' . $table)->row_array()['Create Table'] . ';' . $newline;

			/* Load the previously saved charset */
			$dump .= '/*!40101 SET character_set_client = @saved_cs_client */;' . $newline;

			/* Fetch all table records */
			$q = $this->get($table);

			/* Check if there are any records and if so, dump them */
			if ($q->num_rows()) {
				/* Inform (comment) to which table the following data belongs */
				$dump .= $newline;
				$dump .= '--' . $newline;
				$dump .= '-- Dumping data for table `' . $table . '`' . $newline;
				$dump .= '--' . $newline;
				$dump .= $newline;

				/* Lock the current table for writing (before importing data) */
				$dump .= 'LOCK TABLES `' . $table . '` WRITE;' . $newline;

				/* Disable key constraints */
				$dump .= '/*!40000 ALTER TABLE `' . $table . '` DISABLE KEYS */;' . $newline;


				/* Dump table data */
				$dump .= 'INSERT INTO `' . $table . '` VALUES ';

				foreach ($q->result() as $row) {
					/* Quote values */
					$row_escaped = array();

					for ($i = 0; $i < count($row); $i ++)
						$row_escaped[$i] = $this->quote($row[$i]); /* Escape */

					/* Merge escaped rows */
					$dump .= '(' . implode(',', $row_escaped) . '),';
				}

				/* Remove the trailing , */
				$dump = rtrim($dump, ',') . ';' . $newline;

				/* Enable key constraints */
				$dump .= '/*!40000 ALTER TABLE `' . $table . '` ENABLE KEYS */;' . $newline;

				/* Unlock any previous lock */
				$dump .= 'UNLOCK TABLES;' . $newline;
			}
		}

		/* Separator */
		$dump .= $newline;

		/* Reload previously configured values */
		$dump .= '/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;' . $newline;
		$dump .= '/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;' . $newline;
		$dump .= '/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;' . $newline;
		$dump .= '/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;' . $newline;
		$dump .= '/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;' . $newline;
		$dump .= '/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;' . $newline;
		$dump .= '/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;' . $newline;
		$dump .= '/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;' . $newline;

		/* Separator */
		$dump .= $newline;

		/* Dump completed */
		$dump .= '-- Dump completed on ' . date('Y-m-d H:i:s') . $newline;

		/* All good */
		return $dump;
	}
}

class UW_View extends UW_Base {
	public function load($file, $data = NULL, $export_content = false, $enforce = true) {
		/* If enforce is set, grant that no potential harmful tags are exported to the view */
		if ($enforce && $data) {
			foreach ($data as $k => $v) {
				/* NOTE: This is only effective for string type values. Any other object won't be checked */
				if (gettype($v) == "string" && strpos(str_replace(' ', '', strtolower($v)), '<script') !== false) {
					header('HTTP/1.1 500 Internal Server Error');
					die('load(): Unable to load views with <script> tags on their $data strings when $enforce is set to true (default).');
				}
			}
		}

		/* Check if there's anything to extract */
		if ($data !== NULL)
			extract($data, EXTR_PREFIX_SAME, "uw_");

		/* Unset $data variable as it's no longer required */
		unset($data);

		/* Validate filename */
		if ($enforce) {
			if (strpos($file, '../') !== false) {
				header('HTTP/1.1 500 Internal Server Error');
				die('load(): Unable to load view files with \'../\' string on their names.');
			}
		}

		/* Load view from file */
		if ($export_content) {
			ob_start();
			include('application/views/' . $file . '.php');
			$content = ob_get_contents();
			ob_end_clean();
			return $content;
		} else {
			include('application/views/' . $file . '.php');
			return true;
		}
	}
}

class UW_Model {
	public $cache = NULL;
	public $db = NULL;
	public $session = NULL;
	public $encrypt = NULL;

	public function __construct() {
		/* Initialize system cache controller */
		$this->cache = new UW_Cache;

		/* Initialize system database controller */
		$this->db = new UW_Database;
		
		/* Initialize system session controller */
		$this->session = new UW_Session($this->db, $this->cache);

		/* Initialize system encryption controller */
		$this->encrypt = new UW_Encrypt;
	}
	
	public function load($model, $is_library = false, $tolower = false) {
		global $__objects;

		if (!preg_match('/^[a-zA-Z0-9_]+$/', $model))
			return false;

		if ($is_library === true) {
			/* We're loading a library */
			eval('$this->' . ($tolower ? strtolower($model) : $model) . ' = new ' . $model . '();');
		} else {
			/* Be default, model objects are instantiated only once and, on subsequent calls, a reference to the existing
			 * (instantiated) object is passed.
			 */
			if ($__objects['enabled'] === true) {
				if (isset($__objects['autoload'][$model])) {
					eval('$this->' . $model . ' = &$__objects[\'autoload\'][\'' . $model . '\'];');
				} else if (isset($__objects['adhoc'][$model])) {
					eval('$this->' . $model . ' = &$__objects[\'adhoc\'][\'' . $model . '\'];');
				} else {
					eval('$__objects[\'adhoc\'][\'' . $model . '\'] = new UW_' . ucfirst($model) . '();');
					eval('$this->' . $model . ' = &$__objects[\'adhoc\'][\'' . $model . '\'];');
				}
			} else {
				eval('$this->' . $model . ' = new UW_' . ucfirst($model) . '();');
			}
		}

		return true;
	}
}

/* Alias class for loading methods (Old API compatibility) */
class UW_Load extends UW_Model {
	private $_database = NULL;
	private $_view = NULL;
	private $_model = NULL;
	private $_extension = NULL;
	private $_library = NULL;

	public function __construct($database, $model, $view, $extension, $library) {
		/* Initialize system database controller */
		$this->_database = $database;
		
		/* Initialize model class */
		$this->_model = $model;

		/* Initialize system view controller */
		$this->_view = $view;

		/* Initialize system extensions */
		$this->_extension = $extension;

		/* Initialize libraries */
		$this->_library = $library;
	}

	public function view($file, $data = NULL, $export_content = false) {
		return $this->_view->load($file, $data, $export_content);
	}

	public function model($model) {
		return $this->_model->load($model, false, false);
	}

	public function module($module) {
		return $this->_model->load($module, false, false);
	}

	public function database($database, $return_self = false) {
		return $this->_database->load($database, $return_self);
	}

	public function extension($extension) {
		/* Extensions loading are treated as models, just a different name and a different directory */
		return $this->_model->load($extension);
	}

	public function library($library, $tolower = true) {
		/* Libraries loading are treated as models, with some minor changes (no UW_ prefix required on library main class and optional $tolower parameter) */
		return $this->_model->load($library, true, $tolower);
	}
}

class UW_Module extends UW_Model {
	public $view = NULL;
	public $model = NULL;
	public $module = NULL;
	public $extension = NULL;
	public $library = NULL;
	public $load = NULL;

	public function __construct() {
		global $config;

		parent::__construct();
		
		/* Initialize model class */
		$this->model = $this;

		/* Initialize module class */
		$this->module = $this;

		/* Initialize system view controller */
		$this->view = new UW_View;

		/* Initialize system extension class */
		$this->extension = $this; /* Extensions loading are treated as models, just a different name and a different directory */

		/* Initialize library class */
		$this->library = $this; /* Libraries loading are treated as models, just a different name and a different directory */

		/* Initialize load class */
		$this->load = new UW_Load($this->db, $this->model, $this->view, $this->extension, $this->library);

		/* Autoload configured libraries */
		foreach ($config['autoload']['libraries'] as $_lib)
			$this->load->library($_lib);

		/* Autoload configured extensions */
		foreach ($config['autoload']['extensions'] as $_ext)
			$this->load->extension($_ext);

		/* Autoload configured models */
		foreach ($config['autoload']['models'] as $_model)
			$this->load->model($_model);
	}
}

class UW_Controller extends UW_Module {
	public function __construct() {
		global $config;

		parent::__construct();

		/* Autoload configured interfaces */
		foreach ($config['autoload']['modules'] as $_module)
			$this->load->module($_module);
	}
}
