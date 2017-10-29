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

class UW_S3 extends UW_Module {
	private $config;	/* Configuration */
	private $_s3;
	private $_bucket;

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

		/* Access global configuration */
		global $config;

		/* Load AWS SDK */
		require('Aws/autoload.php');

		/* Initialize S3 Client */
		$this->_s3 = new Aws\S3\S3Client([
			'version' => $config['aws']['version'],
			'region' => $config['aws']['region'],
			'credentials' => [
				'key' => $config['aws']['key'],
				'secret' => $config['aws']['secret'],
			],
			'use_accelerate_endpoint' => (isset($config['aws']['use_accelerate_endpoint']) ? $config['aws']['use_accelerate_endpoint'] : false)
		]);

		/* Set default bucket */
		$this->_bucket = $config['aws']['default_bucket'];

		/* Set base directory */
		$this->_bucket_base_dir = $config['aws']['bucket_base_dir'];
	}

	public function bucket($bucket = NULL) {
		/* Getter */
		if ($bucket === NULL)
			return $this->_bucket;

		/* Setter */
		$this->_bucket = $bucket;
	}

	public function bucket_base_dir($base_dir = NULL) {
		/* Getter */
		if ($base_dir === NULL)
			return $this->_bucket_base_dir;

		$this->_bucket_base_dir = $base_dir;
	}

	public function upload($filename, $contents, $encryption = false) {
		/* Requires s3:PutObject */
		try {
			if ($encryption === false) {
				$this->_s3->putObject([
					'ACL' => 'public-read', /* Requires s3:PutObjectAcl */
					'Bucket' => $this->_bucket,
					'Key' => ($this->_bucket_base_dir ? $this->_bucket_base_dir . '/' : '') . $filename,
					'Body' => $contents
				]);
			} else {
				$this->_s3->putObject([
					'ACL' => 'public-read',
					'Bucket' => $this->_bucket,
					'Key' => ($this->_bucket_base_dir ? $this->_bucket_base_dir . '/' : '') . $filename,
					'Body' => $contents,
					'ServerSideEncryption' => 'AES256'
				]);
			}
		} catch (Exception $e) {
			error_log(__FILE__ . ': ' . __FUNCTION__ . ': ' . __LINE__ . ': Failed to upload file \'' . $filename . '\' to S3 bucket: ' . $this->_bucket);
			return false;
		}

		return true;
	}

	public function download($filename) {
		/* Requires s3:GetObject */
		try {
			$result = $this->_s3->getObject([
				'Bucket' => $this->_bucket,
				'Key' => ($this->_bucket_base_dir ? $this->_bucket_base_dir . '/' : '') . $filename
			]);
		} catch (Exception $e) {
			error_log(__FILE__ . ': ' . __FUNCTION__ . ': ' . __LINE__ . ': Failed to download file \'' . $filename . '\' from S3 bucket: ' . $this->_bucket);
			return false;
		}

		return $result['Body'];
	}

	public function drop($filename) {
		/* Requires s3:DeleteObject */
		try {
			$this->_s3->deleteObject([
				'Bucket' => $this->_bucket,
				'Key' => ($this->_bucket_base_dir ? $this->_bucket_base_dir . '/' : '') . $filename
			]);
		} catch (Exception $e) {
			error_log(__FILE__ . ': ' . __FUNCTION__ . ': ' . __LINE__ . ': Failed to remove file \'' . $filename . '\' from S3 bucket: ' . $this->_bucket);
			return false;
		}

		return true;
	}
}
