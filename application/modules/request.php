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

class UW_Request extends UW_Module {
	private $_raw_data = NULL;
	private $_headers = array();
	private $_uploads = array();
	private $_default_charset = NDPHP_LANG_MOD_DEFAULT_CHARSET;

	public function get($key = NULL) {
		if ($key !== NULL)
			return $_GET[$key];

		return $_GET;
	}

	public function get_unset($key) {
		unset($_GET[$key]);
	}

	public function get_set($key, $value) {
		$_GET[$key] = $value;
	}

	public function get_set_all($data) {
		$_GET = $data;
	}

	public function get_isset($key) {
		return isset($_GET[$key]);
	}

	public function post($key = NULL) {
		if ($key !== NULL)
			return $_POST[$key];

		return $_POST;
	}

	public function post_unset($key) {
		unset($_POST[$key]);
	}

	public function post_set($key, $value) {
		$_POST[$key] = $value;
	}

	public function post_set_all($data) {
		$_POST = $data;
	}

	public function post_isset($key) {
		return isset($_POST[$key]);
	}

	public function input($key = NULL) {
		if ($key !== NULL)
			return $_REQUEST[$key];

		return $this->raw();
	}

	public function upload($input_name = NULL, $dest_path = SYSTEM_BASE_DIR . '/uploads', $random_filename = false, $upload_max_size = 16777216) {
		/* Check if the requested file was already processed... */
		if (isset($this->_uploads[$input_name]))
			return $this->_uploads[$input_name]; /* If so... just return the stored filename */

		/* We need the response module to throw upload errors back to the client */
		$this->load->module('response');

		/* Create directory if it doesn't exist */
		if (!file_exists($dest_path) && mkdir($dest_path, 0750, true) === false)
			$this->response->code('500', NDPHP_LANG_MOD_UNABLE_FILE_UPLOAD . ' "' . $_FILES[$input_name]['name'] . '": ' . NDPHP_LANG_MOD_UNABLE_CREATE_DIRECTORY, $this->_default_charset, !$this->is_ajax());

		/* Upload error pre-check */
		if (!isset($_FILES[$input_name]['error']) || is_array($_FILES[$input_name]['error']))
			$this->response->code('500', NDPHP_LANG_MOD_UNABLE_FILE_UPLOAD . ' "' . $_FILES[$input_name]['name'] . '": ' . NDPHP_LANG_MOD_INVALID_PARAMETERS, $this->_default_charset, !$this->is_ajax());

		/* Grant that there are no errors */
		if ($_FILES[$input_name]['error'] > 0)
			$this->response->code('403', NDPHP_LANG_MOD_UNABLE_FILE_UPLOAD . ' "' . $_FILES[$input_name]['name'] . '": ' . error_upload_file($_FILES[$input_name]['error']), $this->_default_charset, !$this->is_ajax());

		/* Validate file size (This is a fallback for php settings) */
		if ($_FILES[$input_name]['size'] > $upload_max_size)
			$this->response->code('403', NDPHP_LANG_MOD_UNABLE_FILE_UPLOAD . ' "' . $_FILES[$input_name]['name'] . '": ' . NDPHP_LANG_MOD_INVALID_SIZE_TOO_BIG, $this->_default_charset, !$this->is_ajax());

		/* Set the new file name */
		$file_name = '';

		if ($random_filename === true) {
			$file_name = openssl_digest($_FILES[$input_name]['name'] . mt_rand(1000000, 9999999), 'sha256');
		} else {
			$file_name = $_FILES[$input_name]['name'];
		}

		/* Move the temporary file to its new, permanent location */
		if (move_uploaded_file($_FILES[$input_name]['tmp_name'], $dest_path . '/' . $file_name) === false)
			$this->response->code('403', NDPHP_LANG_MOD_UNABLE_FILE_COPY . ' "' . $_FILES[$input_name]['name'] . '"', $this->_default_charset, !$this->is_ajax());

		/* Store the new path of the uploaded file */
		$this->_uploads[$input_name] = $dest_path . '/' . $file_name;

		/* Return the full path of the uploaded file */
		return $this->_uploads[$input_name];
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
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == strtolower('XMLHttpRequest'));
	}

	public function is_json() {
		if (strstr($this->header('Accept'), 'application/json') !== false)
			return true;

		return $this->json() !== NULL;
	}

	public function is_https() {
		return isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) != 'off');
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
		$raddr = remote_addr();

		return $raddr ? $raddr : 'Unspecified';
	}
}
