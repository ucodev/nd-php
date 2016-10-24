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

	public function in_file_uploads($file, $file_uploads) {
		foreach ($file_uploads as $item) {
			if ($file == $item[0])
				return true;
		}

		return false;
	}

	public function pre_process() {
		$file_uploads = array();

		/* Check if the file is being uploaded via JSON encoded request */
		if ($this->request->is_json()) {
			foreach ($this->request->post() as $field => $value) {
				if (substr($field, 0, 6) != '_file_')
					continue;

				if (!isset($value['contents']))
					$this->response->code('403', NDPHP_LANG_MOD_ERROR_UPLOAD_NO_CONTENT, $this->config['default_charset'], !$this->request->is_ajax());

				/* Set file metadata */
				$meta['driver'] = $this->config['upload_file_driver'];
				$meta['from_json'] = true;
				$meta['name'] = $value['name'];
				$meta['type'] = $value['type'];
				$meta['created'] = $value['created'];
				$meta['modified'] = $value['modified'];

				switch ($meta['driver']) {
					case 'local': $meta['path'] = $field . '/' . $meta['name']; break;
					case 's3':    $meta['path'] = $this->config['session_data']['user_id'] . '/' . $this->config['name'] . '/' . openssl_digest(time() . $field . rawurldecode($meta['name']), 'sha256') . '.' . end(explode('.', $meta['name'])); break;
				}

				$meta['url'] = $meta['path'];

				/* Create a temporary file */
				if (($tfile = tempnam(sys_get_temp_dir(), 'ndfile')) === false)
					$this->response->code('500', NDPHP_LANG_MOD_FAILED_CREATE_TEMP_FILE, $this->config['default_charset'], !$this->request->is_ajax());

				/* Store the contents of the uploaded file into the local temporary file */
				if (file_put_contents($tfile, base64_decode($value['contents'])) === false)
					$this->response->code('500', NDPHP_LANG_MOD_UNABLE_PUT_TEMP_FILE_CONTENTS, $this->config['default_charset'], !$this->request->is_ajax());

				/* Clear contents from value */
				$value['contents'] = NULL;

				$meta['size'] = filesize($tfile);

				/* Push file metadata into uploads array */
				array_push($file_uploads, array($field, $meta));

				/* Map the JSON encoded data to native PHP files global */
				$_FILES[$field]['name'] = $meta['name'];
				$_FILES[$field]['size'] = $meta['size'];
				$_FILES[$field]['error'] = NULL;
				$_FILES[$field]['tmp_name'] = $tfile;

				/* Set the POST variable value */
				$this->request->post_set($field, json_encode($meta));
			}
		} else {
			foreach ($_FILES as $k => $v) {
				if (!$_FILES[$k]['name'])
					continue;

				/* Filter filename */
				$_FILES[$k]['name'] = preg_replace('/[^' . $this->config['upload_file_name_filter'] . ']+/', '_', $_FILES[$k]['name']);

				switch ($_FILES[$k]['error']) {
					case UPLOAD_ERR_NO_FILE:
					case UPLOAD_ERR_PARTIAL: continue;
				}

				/* Set file metadata */
				$meta['driver'] = $this->config['upload_file_driver'];
				$meta['from_json'] = false;
				$meta['name'] = $_FILES[$k]['name'];
				$meta['type'] = NULL;
				$meta['size'] = $_FILES[$k]['size'];
				$meta['created'] = date('Y-m-d H:i:s');
				$meta['modified'] = $meta['created'];

				switch ($meta['driver']) {
					case 'local': $meta['path'] = $k . '/' . $meta['name']; break;
					case 's3':    $meta['path'] = $this->config['session_data']['user_id'] . '/' . $this->config['name'] . '/' . openssl_digest(time() . $field . rawurldecode($meta['name']), 'sha256') . '.' . end(explode('.', $meta['name'])); break;
				}

				$meta['url'] = $meta['path'];

				/* Push file metadata into uploads array */
				array_push($file_uploads, array($k, $meta));

				/* Set the POST variable value */
				$this->request->post_set($k, json_encode($meta));
			}	
		}

		return $file_uploads;
	}

	public function process_file($table, $id, $file) {
		$field = $file[0];
		$meta = $file[1];

		if (!isset($_FILES[$field]['error']) || is_array($_FILES[$field]['error']))
			$this->response->code('500', NDPHP_LANG_MOD_UNABLE_FILE_UPLOAD . ' "' . $_FILES[$field]['name'] . '": ' . NDPHP_LANG_MOD_INVALID_PARAMETERS, $this->config['default_charset'], !$this->request->is_ajax());

		/* Grant that there are no errors */
		if ($_FILES[$field]['error'] > 0)
			$this->response->code('403', NDPHP_LANG_MOD_UNABLE_FILE_UPLOAD . ' "' . $_FILES[$field]['name'] . '": ' . error_upload_file($_FILES[$field]['error']), $this->config['default_charset'], !$this->request->is_ajax());

		/* Validate file size (This is a fallback for php settings) */
		if ($_FILES[$field]['size'] > $this->config['upload_file_max_size'])
			$this->response->code('403', NDPHP_LANG_MOD_UNABLE_FILE_UPLOAD . ' "' . $_FILES[$field]['name'] . '": ' . NDPHP_LANG_MOD_INVALID_SIZE_TOO_BIG, $this->config['default_charset'], !$this->request->is_ajax());

		/* Compute file hash */
		$file_hash = openssl_digest($_FILES[$field]['name'], 'sha256');

		/* Process the file accordingly to the selected driver */
		if ($meta['driver'] == 'local') {
			/* Craft destination path */
			$dest_path = SYSTEM_BASE_DIR . '/uploads/' . $this->config['session_data']['user_id'] . '/' . $table . '/' . $id . '/' . $field;

			/* Create directory if it doesn't exist */
			if (mkdir($dest_path, 0750, true) === false)
				$this->response->code('500', NDPHP_LANG_MOD_UNABLE_FILE_UPLOAD . ' "' . $_FILES[$field]['name'] . '": ' . NDPHP_LANG_MOD_UNABLE_CREATE_DIRECTORY, $this->config['default_charset'], !$this->request->is_ajax());

			/* Move file from temporary location */
			if ($meta['from_json'] === false) {
				/* The file was uploaded with multipart encoding, so move_uploaded_file() shall be used */
				if (move_uploaded_file($_FILES[$field]['tmp_name'], $dest_path . '/' . $file_hash) === false)
					$this->response->code('403', NDPHP_LANG_MOD_UNABLE_FILE_COPY . ' "' . $_FILES[$field]['name'] . '"', $this->config['default_charset'], !$this->request->is_ajax());
			} else {
				/* The file was uploaded via REST API (JSON encoded), so rename() shall be used
				 * since this is a regular temporary file, created by the REST API upload handler.
				 */
				if (rename($_FILES[$field]['tmp_name'], $dest_path . '/' . $file_hash) === false)
					$this->response->code('403', NDPHP_LANG_MOD_UNABLE_FILE_COPY . ' "' . $_FILES[$field]['name'] . '"', $this->config['default_charset'], !$this->request->is_ajax());
			}

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
		} else if ($meta['driver'] == 's3') {
			$this->load->module('s3');

			if ($meta['from_json'] === false) {
				/* The file was uploaded with multipart encoding, so move_uploaded_file() shall be used */

				/* Create a temporary file name */
				if (($tfile = tempnam(sys_get_temp_dir(), 'ndfile')) === false)
					$this->response->code('500', NDPHP_LANG_MOD_FAILED_CREATE_TEMP_FILE, $this->config['default_charset'], !$this->request->is_ajax());

				/* Move the uploaded file into a well known temporary file */
				if (move_uploaded_file($_FILES[$field]['tmp_name'], $tfile) === false)
					$this->response->code('403', NDPHP_LANG_MOD_UNABLE_FILE_COPY . ' "' . $_FILES[$field]['name'] . '"', $this->config['default_charset'], !$this->request->is_ajax());

				/* Upload the file to S3 bucket */
				if ($this->s3->upload($meta['path'], file_get_contents($tfile), $this->config['upload_file_encryption']) === false)
					$this->response->code('500', NDPHP_LANG_MOD_FAILED_AWS_S3_UPLOAD, $this->config['default_charset'], !$this->request->is_ajax());

				/* Unlink the temporary file */
				unlink($tfile);
			} else {
				/* The file was uploaded via REST API (JSON encoded), so rename() shall be used
				 * since this is a regular temporary file, created by the REST API upload handler.
				 */
				if ($this->s3->upload($meta['path'], file_get_contents($_FILES[$field]['tmp_name']), $this->config['upload_file_encryption']) === false)
					$this->response->code('500', NDPHP_LANG_MOD_FAILED_AWS_S3_UPLOAD, $this->config['default_charset'], !$this->request->is_ajax());
			}
		} else {
			$this->response->code('403', NDPHP_LANG_MOD_ERROR_UPLOAD_NO_DRIVER, $this->config['default_charset'], !$this->request->is_ajax());
		}
	}

	public function remove_file($table, $id, $file) {
		$field = $file[0];
		$meta = $file[1];

		switch ($this->config['upload_file_driver']) {
			case 'local': {
				$dest_path = SYSTEM_BASE_DIR . '/uploads/' . $this->config['session_data']['user_id'] . '/' . $table . '/' . $id . '/' . $field;

				$this->_rrmdir($dest_path);
			} break;
			case 's3': {
				$this->load->module('s3');

				/* Remove the file from the S3 bucket */
				if ($this->s3->drop($meta['path']) === false)
					$this->response->code('500', NDPHP_LANG_MOD_FAILED_AWS_S3_REMOVE, $this->config['default_charset'], !$this->request->is_ajax());
			} break;
			default: $this->response->code('403', NDPHP_LANG_MOD_ERROR_UPLOAD_NO_DRIVER, $this->config['default_charset'], !$this->request->is_ajax());
		}
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