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

class Update extends ND_Controller {
	/* ND PHP Framework - update settings */
	private $_ndphp_url = 'http://www.nd-php.org';
	private $_ndphp_version = '0.01';

	/* Constructor */
	public function __construct($session_enable = true, $json_replies = false) {
		parent::__construct($session_enable, $json_replies);

		$this->_viewhname = get_class();
		$this->_name = strtolower($this->_viewhname);
		$this->_hook_construct();

		/* Include any setup procedures from ide builder. */
		include('lib/ide_setup.php');

		/* Grant that only ROLE_ADMIN is able to access this controller */
		if (!$this->security->im_admin()) {
			header('HTTP/1.1 403 Forbidden');
			die(NDPHP_LANG_MOD_ACCESS_ONLY_ADMIN);
		}
	}

	
	/** Hooks **/
	

	/** Other overloads **/


	/** Custom functions **/

	public function update() {
		/** Stage 1 of update process **/

		/* Setup JSON request */
		$req_update = json_encode(array(
			'who' => 'ND PHP Framework',
			'stage' => 1,
			'from_url' => base_url(),
			'from_version' => $this->_ndphp_version
		));

		/* Fetch update file contents from server */
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->_ndphp_url . '/updates/');
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $req_update);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$update_file_contents = curl_exec($ch);
		curl_close($ch);

		if ($update_file_contents == '0') {
			echo(NDPHP_LANG_MOD_INFO_SYSTEM_UP_TO_DATE);
			return;
		}

		/* Craft file name */
		$update_file_name = SYSTEM_BASE_DIR . '/install/updates/ndphp_' . date('YmdHis') . '_' . openssl_digest($update_file_contents, 'sha1') . '.update';

		/* Create local file to hold update file contents */
		if (($fp = fopen($update_file_name, 'w')) === false) {
			header('HTTP/1.1 500 Internal Server Error');
			die(NDPHP_LANG_MOD_UNABLE_FILE_OPEN_WRITE . ' "' . $update_file_name . '"');
		}

		/* Write update file contents to local file */
		if (fwrite($fp, $update_file_contents) === false) {
			header('HTTP/1.1 500 Internal Server Error');
			die(NDPHP_LANG_MOD_UNABLE_FILE_WRITE . ' "' . $update_file_name . '"');
		}

		/* Flush data to file */
		fflush($fp);

		/* Close file pointer */
		fclose($fp);

		/* Overwrite the update.php controller */
		if (copy($update_file_name, SYSTEM_BASE_DIR . '/application/controllers/update.php') === false) {
			header('HTTP/1.1 403 Forbidden');
			die(NDPHP_LANG_MOD_INSTALL_WRITE_NO_PRIV . ': ' . SYSTEM_BASE_DIR . '/application/controllers/update.php');
		}

		/* Wait a little while ... */
		sleep(5);

		/* Redirect to the PHP upgrade script (Stage 2) */
		redirect('/update');
	}

	public function index() {
		header('HTTP/1.1 500 Internal Server Error');
		die('RELOAD THE PAGE');
	}
}