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

class UW_Request extends UW_Model {
	private $_raw_data = NULL;
	private $_headers = array();

	public function get() {
		return $_GET;
	}

	public function post() {
		return $_POST;
	}

	public function raw() {
		return file_get_contents('php://input');
	}

	public function json() {
		if ($this->_raw_data === NULL)
			$this->_raw_data = file_get_contents('php://input');

		return json_decode($this->_raw_data, true);
	}

	public function method() {
		return $_SERVER['REQUEST_METHOD'];
	}

	public function is_get() {
		return $this->method() == 'GET';
	}

	public function is_post() {
		return $this->method() == 'POST';
	}

	public function is_put() {
		return $this->method() == 'PUT';
	}

	public function is_delete() {
		return $this->method() == 'DELETE';
	}

	public function is_ajax() {
		return (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == strtolower('XMLHttpRequest'));
	}

	public function is_json() {
		return $this->json() !== NULL;
	}

	public function headers() {
		if (count($this->_headers))
			return $this->_headers;

		foreach ($_SERVER as $header => $value) {
			if (substr($header, 0, 5) != 'HTTP_')
				continue;

			$this->_headers[ucwords(strtolower(str_replace('_', '-', substr($header, 5))), '-')] = $value;
		}

		return $this->_headers;
	}

	public function header($header) {
		$this->headers();

		if (isset($this->_headers[$header]))
			return $this->_headers[$header];

		return NULL;
	}

	public function remote_addr() {
		return (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'Unspecified';
	}
}
