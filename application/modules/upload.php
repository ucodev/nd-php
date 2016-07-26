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

class UW_Upload extends UW_Module {
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

	public function process_file($table, $id, $field) {
		if (!isset($_FILES[$field]['error']) || is_array($_FILES[$field]['error']))
			$this->response->code('500', NDPHP_LANG_MOD_UNABLE_FILE_UPLOAD . ' "' . $_FILES[$field]['name'] . '": ' . NDPHP_LANG_MOD_INVALID_PARAMETERS, $this->config['default_charset'], !$this->request->is_ajax());

		/* Grant that there are no errors */
		if ($_FILES[$field]['error'] > 0)
			$this->response->code('403', NDPHP_LANG_MOD_UNABLE_FILE_UPLOAD . ' "' . $_FILES[$field]['name'] . '": ' . error_upload_file($_FILES[$field]['error']), $this->config['default_charset'], !$this->request->is_ajax());

		/* Validate file size (This is a fallback for php settings) */
		if ($_FILES[$field]['size'] > $this->config['upload_file_max_size'])
			$this->response->code('403', NDPHP_LANG_MOD_UNABLE_FILE_UPLOAD . ' "' . $_FILES[$field]['name'] . '": ' . NDPHP_LANG_MOD_INVALID_SIZE_TOO_BIG, $this->config['default_charset'], !$this->request->is_ajax());

		/* Craft destination path */
		$dest_path = SYSTEM_BASE_DIR . '/uploads/' . $this->config['session_data']['user_id'] . '/' . $table . '/' . $id . '/' . $field;

		/* Create directory if it doesn't exist */
		if (mkdir($dest_path, 0750, true) === false)
			$this->response->code('500', NDPHP_LANG_MOD_UNABLE_FILE_UPLOAD . ' "' . $_FILES[$field]['name'] . '": ' . NDPHP_LANG_MOD_UNABLE_CREATE_DIRECTORY, $this->config['default_charset'], !$this->request->is_ajax());

		/* Compute file hash */
		$file_hash = openssl_digest($_FILES[$field]['name'], 'sha256');

		if (move_uploaded_file($_FILES[$field]['tmp_name'], $dest_path . '/' . $file_hash) === false)
			$this->response->code('403', NDPHP_LANG_MOD_UNABLE_FILE_COPY . ' "' . $_FILES[$field]['name'] . '"', $this->config['default_charset'], !$this->request->is_ajax());

		/* Encrypt file, if required */
		if ($this->config['upload_file_encryption'] === true) {
			/* FIXME: TODO: For limited type tables, we should use the user's private encryption key here */
			$content_ciphered = $this->encrypt->encode(file_get_contents($dest_path . '/' . $file_hash));
			if (($fp = fopen($dest_path . '/' . $file_hash, 'w')) === false)
				$this->response->code('403', NDPHP_LANG_MOD_UNABLE_FILE_OPEN_WRITE . ' "' . $dest_path . '/' . $file_hash . '"', $this->config['default_charset'], !$this->request->is_ajax());

			if (fwrite($fp, $content_ciphered) === false)
				$this->response->code('500', NDPHP_LANG_MOD_UNABLE_FILE_WRITE . ' "' . $dest_path . '/' . $file_hash . '"', $this->config['default_charset'], !$this->request->is_ajax());

			fclose($fp);
		}
	}

	public function remove_file($table, $id, $field) {
		$dest_path = SYSTEM_BASE_DIR . '/uploads/' . $this->config['session_data']['user_id'] . '/' . $table . '/' . $id . '/' . $field;

		$this->_rrmdir($dest_path);
	}

	public function purge_entry_files($table, $id) {
		$dest_path = SYSTEM_BASE_DIR . '/uploads/' . $this->config['session_data']['user_id'] . '/' . $table . '/' . $id;

		$this->_rrmdir($dest_path);
	}

	private function _rrmdir($dir) {
		/* Sanity checks */
		if (strpos($dir, '/..') || substr($dir, 0, 2) == '..')
			return;

		/* Recursively deletes a directory and all its contents */
		if (is_dir($dir)) {
			$objects = scandir($dir);

			foreach ($objects as $object) {
				if (($object != ".") && ($object != "..")) {
					if (filetype($dir . "/" . $object) == "dir") {
						$this->_rrmdir($dir . "/" . $object);
					} else {
						unlink($dir . "/" . $object);
					}
				}
			}

			reset($objects);
			rmdir($dir);
		}
	}
}