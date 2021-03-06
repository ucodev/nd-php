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
				/* Check if this is a file field... if not, ignore it. If it is, grant that its contents are not null */
				if ((substr($field, 0, 6) != '_file_') || ($value === NULL))
					continue;

				/* Grant that all the required file properties are set */
				foreach (array('name', 'type') as $property) {
					if (!isset($value[$property]))
						return array(false, '403', NDPHP_LANG_MOD_MISSING_FILE_PROPERTY . ': ' . $property, $this->config['default_charset']);
				}

				/* Set file metadata */
				$meta['driver'] = $this->config['upload_file_driver'];
				$meta['from_json'] = true;
				$meta['name'] = $value['name'];
				$meta['type'] = $value['type'];

				/* Created time is optional */
				if (isset($value['created']) && $value['created']) {
					$meta['created'] = $this->timezone->convert($value['created'], $this->config['session_data']['timezone'], $this->config['default_timezone'], DateTime::ATOM);
				} else {
					$meta['created'] = date('Y-m-d\TH:i:sP');
				}

				/* Modified time is optional */
				if (isset($value['created']) && isset($value['modified']) && $value['modified']) {
					$meta['modified'] = $this->timezone->convert($value['modified'], $this->config['session_data']['timezone'], $this->config['default_timezone'], DateTime::ATOM);
				} else {
					$meta['modified'] = $meta['created'];
				}

				/* If the file size was specified in the request, use it for now... (it may be replaced if 'contents' is set) */
				if (isset($value['size']))
					$meta['size'] = $value['size'];

				/* When no content is specified, the size propery must be explicit */
				if (!isset($value['contents']) && !isset($value['size']))
					return array(false, '403', NDPHP_LANG_MOD_MISSING_FILE_PROPERTY_SIZE_NO_CONTENT);

				/* Compute file path based on the selected upload driver */
				switch ($meta['driver']) {
					case 'local': $meta['path'] = $field . '/' . $meta['name']; break;
					case 's3':    $meta['path'] = $this->config['session_data']['user_id'] . '/' . $this->config['name'] . '/' . openssl_digest(time() . $field . rawurldecode($meta['name']), 'sha256') . '.' . end(explode('.', $meta['name'])); break;
				}

				/* If content is set, decode it and store the data in a temporary file */
				if (isset($value['contents'])) {
					/* Content cannot be empty */
					if (!$value['contents'])
						return array(false, '400', NDPHP_LANG_MOD_ERROR_UPLOAD_NO_CONTENT);

					/* Is content is set, the enconding shall be explicit */
					if (!isset($value['encoding']))
						return array(false, '400', NDPHP_LANG_MOD_MISSING_CONTENT_ENCODING);

					/* Create a temporary file */
					if (($tfile = tempnam(sys_get_temp_dir(), 'ndfile')) === false)
						return array(false, '500', NDPHP_LANG_MOD_FAILED_CREATE_TEMP_FILE);

					/* Decode the file contents, if required */
					if ($value['encoding'] == 'base64') {
						/* Try to decode the file contents */
						$fcontents = base64_decode($value['contents']);

						/* Check if we've successfully decoded the contents */
						if ($fcontents === false) {
							unlink($tfile);
							return array(false, '400', NDPHP_LANG_MOD_UNABLE_DECODE_BASE64);
						}

						/* Re-assign the decoded data */
						$value['contents'] = $fcontents;

						/* Clear $fcontents */
						$fcontents = NULL;
					} else if ($value['encoding'] == 'plain') {
						/* No action required */
					} else {
						return array(false, '400', NDPHP_LANG_MOD_UNSUPPORTED_CONTENT_ENCODING);
					}

					/* Store the contents of the uploaded file into the local temporary file */
					if (($wsize = file_put_contents($tfile, $value['contents'])) === false) {
						unlink($tfile);
						return array(false, '500', NDPHP_LANG_MOD_UNABLE_PUT_TEMP_FILE_CONTENTS);
					}

					/* Grant that all bytes were written */
					if (($wsize != strlen($value['contents']))) {
						unlink($tfile);
						return array(false, '500', NDPHP_LANG_MOD_UNABLE_PUT_TEMP_FILE_CONTENTS);
					}

					/* Clear contents from value */
					$value['contents'] = NULL;

					/* Set size property based on file size */
					$meta['size'] = filesize($tfile);

					/* Compute the file signature */
					$meta['hash'] = openssl_digest($tfile, 'sha256');

					/* If file is an image, also set the image properties */
					if (($img_props = getimagesize($tfile)) !== false) {
						$meta['image'] = array();
						$meta['image']['width'] = $img_props[0];
						$meta['image']['height'] = $img_props[1];

						/* Check image file extension */
						if (!in_array($this->image->file_extension($meta['name']), $this->config['upload_file_image_extensions']))
							return array(false, '400', NDPHP_LANG_MOD_INVALID_IMAGE_FILE_EXTENSION . $this->image->file_extension($meta['name']));

						/* Check image size */
						if ($meta['image']['width'] < $this->config['upload_file_image_width_min'])
							return array(false, '400', NDPHP_LANG_MOD_INVALID_IMAGE_WIDTH_TOO_SMALL . $meta['image']['width']);
						
						if ($meta['image']['width'] > $this->config['upload_file_image_width_max'])
							return array(false, '400', NDPHP_LANG_MOD_INVALID_IMAGE_WIDTH_TOO_LARGE . $meta['image']['width']);

						if ($meta['image']['height'] < $this->config['upload_file_image_height_min'])
							return array(false, '400', NDPHP_LANG_MOD_INVALID_IMAGE_HEIGHT_TOO_SMALL . $meta['image']['height']);

						if ($meta['image']['height'] > $this->config['upload_file_image_height_max'])
							return array(false, '400', NDPHP_LANG_MOD_INVALID_IMAGE_HEIGHT_TOO_LARGE . $meta['image']['height']);
					}

					/* Push file metadata into uploads array */
					array_push($file_uploads, array($field, $meta));

					/* Map the JSON encoded data to native PHP files global */
					$_FILES[$field]['name'] = $meta['name'];
					$_FILES[$field]['size'] = $meta['size'];
					$_FILES[$field]['error'] = 0;
					$_FILES[$field]['tmp_name'] = $tfile;
				}

				/* Set the POST variable value */
				$this->request->post_set($field, json_encode($meta));
			}
		} else {
			foreach ($_FILES as $k => $v) {
				/* If there's no file name set, ignore this entry */
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
				$meta['hash'] = openssl_digest($_FILES[$field]['tmp_name'], 'sha256');

				/* If file is an image, also set the image properties */
				if (($img_props = getimagesize($_FILES[$field]['tmp_name'])) !== false) {
					$meta['image'] = array();
					$meta['image']['width'] = $img_props[0];
					$meta['image']['height'] = $img_props[1];

					/* Check image file extension */
					if (!in_array($this->image->file_extension($meta['name']), $this->config['upload_file_image_extensions']))
						return array(false, '400', NDPHP_LANG_MOD_INVALID_IMAGE_FILE_EXTENSION . $this->image->file_extension($meta['name']));

					/* Check image size */
					if ($meta['image']['width'] < $this->config['upload_file_image_width_min'])
						return array(false, '400', NDPHP_LANG_MOD_INVALID_IMAGE_WIDTH_TOO_SMALL . $meta['image']['width']);
					
					if ($meta['image']['width'] > $this->config['upload_file_image_width_max'])
						return array(false, '400', NDPHP_LANG_MOD_INVALID_IMAGE_WIDTH_TOO_LARGE . $meta['image']['width']);

					if ($meta['image']['height'] < $this->config['upload_file_image_height_min'])
						return array(false, '400', NDPHP_LANG_MOD_INVALID_IMAGE_HEIGHT_TOO_SMALL . $meta['image']['height']);

					if ($meta['image']['height'] > $this->config['upload_file_image_height_max'])
						return array(false, '400', NDPHP_LANG_MOD_INVALID_IMAGE_HEIGHT_TOO_LARGE . $meta['image']['height']);
				}

				$meta['created'] = date('Y-m-d\TH:i:sP');
				$meta['modified'] = $meta['created'];

				/* Compute file path based on the selected upload driver */
				switch ($meta['driver']) {
					case 'local': $meta['path'] = $k . '/' . $meta['name']; break;
					case 's3':    $meta['path'] = $this->config['session_data']['user_id'] . '/' . $this->config['name'] . '/' . openssl_digest(time() . $field . rawurldecode($meta['name']), 'sha256') . '.' . end(explode('.', $meta['name'])); break;
				}

				/* Push file metadata into uploads array */
				array_push($file_uploads, array($k, $meta));

				/* Set the POST variable value */
				$this->request->post_set($k, json_encode($meta));
			}	
		}

		/* All good */
		return array(true, '201', $file_uploads);
	}

	public function process_file($table, $id, $file) {
		$field = $file[0];
		$meta = $file[1];

		if (!isset($_FILES[$field]['error']) || is_array($_FILES[$field]['error'])) {
			unlink($_FILES[$field]['tmp_name']);
			return array(false, '500', NDPHP_LANG_MOD_UNABLE_FILE_UPLOAD . ' "' . $_FILES[$field]['name'] . '": ' . NDPHP_LANG_MOD_INVALID_PARAMETERS);
		}

		/* Grant that there are no errors */
		if ($_FILES[$field]['error'] > 0) {
			unlink($_FILES[$field]['tmp_name']);
			return array(false, '403', NDPHP_LANG_MOD_UNABLE_FILE_UPLOAD . ' "' . $_FILES[$field]['name'] . '": ' . error_upload_file($_FILES[$field]['error']));
		}

		/* Validate file size (This is a fallback for php settings) */
		if ($_FILES[$field]['size'] > $this->config['upload_file_max_size']) {
			unlink($_FILES[$field]['tmp_name']);
			return array(false, '403', NDPHP_LANG_MOD_UNABLE_FILE_UPLOAD . ' "' . $_FILES[$field]['name'] . '": ' . NDPHP_LANG_MOD_INVALID_SIZE_TOO_BIG);
		}

		/* Compute file hash */
		$file_hash = openssl_digest($_FILES[$field]['name'], 'sha256');

		/* Process the file accordingly to the selected driver */
		if ($meta['driver'] == 'local') {
			/* Craft destination path */
			$dest_path = SYSTEM_BASE_DIR . '/uploads/' . $this->config['session_data']['user_id'] . '/' . $table . '/' . $id . '/' . $field;

			/* Create directory if it doesn't exist */
			if (mkdir($dest_path, 0750, true) === false) {
				unlink($_FILES[$field]['tmp_name']);
				return array(false, '500', NDPHP_LANG_MOD_UNABLE_FILE_UPLOAD . ' "' . $_FILES[$field]['name'] . '": ' . NDPHP_LANG_MOD_UNABLE_CREATE_DIRECTORY);
			}

			/* Move file from temporary location */
			if ($meta['from_json'] === false) {
				/* The file was uploaded with multipart encoding, so move_uploaded_file() shall be used */
				if (move_uploaded_file($_FILES[$field]['tmp_name'], $dest_path . '/' . $file_hash) === false) {
					unlink($_FILES[$field]['tmp_name']);
					return array(false, '403', NDPHP_LANG_MOD_UNABLE_FILE_COPY . ' "' . $_FILES[$field]['name'] . '"');
				}
			} else {
				/* The file was uploaded via REST API (JSON encoded), so rename() shall be used
				 * since this is a regular temporary file, created by the REST API upload handler.
				 */
				if (rename($_FILES[$field]['tmp_name'], $dest_path . '/' . $file_hash) === false) {
					unlink($_FILES[$field]['tmp_name']);
					return array(false, '403', NDPHP_LANG_MOD_UNABLE_FILE_COPY . ' "' . $_FILES[$field]['name'] . '"');
				}
			}

			/* Encrypt file, if required */
			if ($this->config['upload_file_encryption'] === true) {
				/* FIXME: TODO: For limited type tables, we should use the user's private encryption key here */
				$content_ciphered = $this->encrypt->encode(file_get_contents($dest_path . '/' . $file_hash));

				/* Open destination file that will hold the encrypted data */
				if (($fp = fopen($dest_path . '/' . $file_hash, 'w')) === false) {
					unlink($_FILES[$field]['tmp_name']);
					return array(false, '403', NDPHP_LANG_MOD_UNABLE_FILE_OPEN_WRITE . ' "' . $dest_path . '/' . $file_hash . '"');
				}

				/* Store encrypted content into file */
				if (($wfile = fwrite($fp, $content_ciphered)) === false) {
					unlink($_FILES[$field]['tmp_name']);
					fclose($fp);
					return array(false, '500', NDPHP_LANG_MOD_UNABLE_FILE_WRITE . ' "' . $dest_path . '/' . $file_hash . '"');
				}

				/* Grant that all bytes were written */
				if ($wfile != strlen($content_ciphered)) {
					unlink($_FILES[$field]['tmp_name']);
					fclose($fp);
					return array(false, '500', NDPHP_LANG_MOD_UNABLE_FILE_WRITE . ' "' . $dest_path . '/' . $file_hash . '"');
				}

				fclose($fp);
			}
		} else if ($meta['driver'] == 's3') {
			$this->load->module('s3');

			if ($meta['from_json'] === false) {
				/* The file was uploaded with multipart encoding, so move_uploaded_file() shall be used */

				/* Create a temporary file name */
				if (($tfile = tempnam(sys_get_temp_dir(), 'ndfile')) === false) {
					/* Unlink the temporary file */
					unlink($_FILES[$field]['tmp_name']);

					return array(false, '500', NDPHP_LANG_MOD_FAILED_CREATE_TEMP_FILE);
				}

				/* Move the uploaded file into a well known temporary file */
				if (move_uploaded_file($_FILES[$field]['tmp_name'], $tfile) === false) {
					/* Unlink the temporary file */
					unlink($_FILES[$field]['tmp_name']);

					return array(false, '500', NDPHP_LANG_MOD_UNABLE_FILE_COPY . ' "' . $_FILES[$field]['name'] . '"');
				}

				/* Upload the file to S3 bucket */
				if ($this->s3->upload($meta['path'], file_get_contents($tfile), $this->config['upload_file_encryption']) === false) {
					/* Unlink the temporary file */
					unlink($tfile);

					return array(false, '502', NDPHP_LANG_MOD_FAILED_AWS_S3_UPLOAD);
				}

				/* If this is an image file, create the resized image versions, if configured */
				if (isset($meta['image']) && ($meta['image']['width'] > 0)) {
					$s3_upload_status = $this->_aws_s3_upload_resized_images($tfile, intval($meta['image']['width']), $meta['path'], $this->config['upload_file_encryption']);

					if ($s3_upload_status[0] !== true) {
						/* Unlink the temporary file */
						unlink($_FILES[$field]['tmp_name']);

						return $s3_upload_status;
					}
				}

				/* Unlink the temporary file */
				unlink($tfile);
			} else {
				/* The file was uploaded via REST API (JSON encoded), so no rename() shall be used
				 * since the temporary file can be directly uploaded to S3.
				 */
				if ($this->s3->upload($meta['path'], file_get_contents($_FILES[$field]['tmp_name']), $this->config['upload_file_encryption']) === false) {
					/* Unlink the temporary file */
					unlink($_FILES[$field]['tmp_name']);

					return array(false, '502', NDPHP_LANG_MOD_FAILED_AWS_S3_UPLOAD);
				}

				/* If this is an image file, create the resized image versions, if configured */
				if (isset($meta['image']) && ($meta['image']['width'] > 0)) {
					$s3_upload_status = $this->_aws_s3_upload_resized_images($_FILES[$field]['tmp_name'], intval($meta['image']['width']), $meta['path'], $this->config['upload_file_encryption']);

					if ($s3_upload_status[0] !== true) {
						/* Unlink the temporary file */
						unlink($_FILES[$field]['tmp_name']);

						return $s3_upload_status;
					}
				}

				/* Unlink the temporary file */
				unlink($_FILES[$field]['tmp_name']);
			}
		} else {
			/* Unlink the temporary file */
			unlink($_FILES[$field]['tmp_name']);

			return array(false, '403', NDPHP_LANG_MOD_ERROR_UPLOAD_NO_DRIVER);
		}

		/* All good */
		return array(true, '201', NULL);
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
					return array(false, '502', NDPHP_LANG_MOD_FAILED_AWS_S3_REMOVE, $this->config['default_charset'], !$this->request->is_ajax());

				/* Remove resized images, if any */
				if (isset($meta['image']) && ($meta['image']['width'] > 0))
					$this->_aws_s3_drop_resized_images($meta['image']['width'], $meta['path']);
			} break;
			default: return array(false, '403', NDPHP_LANG_MOD_ERROR_UPLOAD_NO_DRIVER, $this->config['default_charset'], !$this->request->is_ajax());
		}

		return array(true, '200', NULL);
	}

	private function _rrmdir($dir) {
		/* TODO: FIXME: Add support for different drivers */

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

	/* TODO: FIXME: Evaluate if this method should be migrated into the S3 module, or if makes more sense to keep it here */
	private function _aws_s3_upload_resized_images($tfile, $width, $orig_s3_file_path, $encryption = false) {
		/* NOTE: S3 module already loaded */

		/* Access global configuration */
		global $config;

		/* Check if image resize feature is enabled  */
		if ($config['aws']['bucket_img_resize'] === true) {
			/* Possible available versions */
			$ver_list = array('xxsmall', 'xsmall', 'small', 'medium', 'large', 'xlarge', 'xxlarge');

			/* Iterate over the possible available versions */
			foreach ($ver_list as $ver) {
				if (isset($config['aws']['bucket_img_resize_' . $ver . '_dir']) && isset($config['aws']['bucket_img_resize_' . $ver . '_width']) && ($width >= $config['aws']['bucket_img_resize_' . $ver . '_width'])) {
					$resize_status = $this->image->resize(
						/* orig */    $tfile,
						/* dest */    $tfile . '.' . $ver,
						/* width */   $config['aws']['bucket_img_resize_' . $ver . '_width'],
						/* height */  -1,
						/* quality */ $config['aws']['bucket_img_resize_quality'],
						/* mode */    $config['aws']['bucket_img_resize_mode']
					);

					/* Check if resize succeeded */
					if ($resize_status !== true) {
						/* Unlink temporary file */
						unlink($tfile);

						return array(false, '500', NDPHP_LANG_MOD_UNABLE_SCALE_IMG_RES . ' (' . $ver . ')');
					}

					/* Upload resized image into S3 bucket */
					if ($this->s3->upload($config['aws']['bucket_img_resize_subdir'] . '/' . $config['aws']['bucket_img_resize_' . $ver . '_dir'] . '/' . $orig_s3_file_path, file_get_contents($tfile . '.' . $ver), $encryption) === false) {
						/* Unlink the temporary files */
						unlink($tfile . '.' . $ver);
						unlink($tfile);

						return array(false, '502', NDPHP_LANG_MOD_FAILED_AWS_S3_UPLOAD . ' (' . $ver . ')');
					}

					/* Unlink resized image file */
					unlink($tfile . '.' . $ver);
				}
			}
		}

		return array(true, '201', NULL);
	}

	private function _aws_s3_drop_resized_images($width, $orig_s3_file_path) {
		/* NOTE: S3 module already loaded */

		/* Access global configuration */
		global $config;

		/* Check if image resize feature is enabled  */
		if ($config['aws']['bucket_img_resize'] === true) {
			/* Possible available versions */
			$ver_list = array('xxsmall', 'xsmall', 'small', 'medium', 'large', 'xlarge', 'xxlarge');

			/* Iterate over the possible available versions */
			foreach ($ver_list as $ver) {
				/* Check if it's likely for a specific scaled image to exist and attempt to drop it */
				if (isset($config['aws']['bucket_img_resize_' . $ver . '_dir']) && isset($config['aws']['bucket_img_resize_' . $ver . '_width']) && ($width >= $config['aws']['bucket_img_resize_' . $ver . '_width'])) {
					/* Drop image */
					if ($this->s3->drop($config['aws']['bucket_img_resize_subdir'] . '/' . $config['aws']['bucket_img_resize_' . $ver . '_dir'] . '/' . $orig_s3_file_path) === false)
						error_log(__FILE__ . ': ' . __FUNCTION__ . '(): Unable to remove image from S3 bucket: ' . $config['aws']['bucket_img_resize_subdir'] . '/' . $config['aws']['bucket_img_resize_' . $ver . '_dir'] . '/' . $orig_s3_file_path);
				}
			}
		}
	}
}
