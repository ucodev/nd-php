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
	private $_ndphp_github_content_url = 'https://raw.githubusercontent.com/ucodev/nd-php/';
	private $_ndphp_url = 'http://www.nd-php.org';
	private $_ndphp_version = '0.01v';

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
		/** Stage 0: Fetch tracker **/
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->_ndphp_github_content_url . 'master/install/updates/tracker.json');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$tracker_file_contents = curl_exec($ch);
		curl_close($ch);

		echo($this->_ndphp_github_content_url . 'master/install/updates/tracker.json');
		echo($tracker_file_contents);
		if (($tracker = json_decode($tracker_file_contents, true)) === NULL) {
			header('HTTP/1.1 500 Internal Server Error');
			die(NDPHP_LANG_MOD_UNABLE_UPDATE_DECODE_TRACKER);
		}

		/** Stage 1: Determine the tracker entry to be used **/
		$from_version = $this->_ndphp_version;

		if (!isset($tracker[$from_version]))
			$from_version = 'any';

		if (!isset($tracker[$from_version])) {
			header('HTTP/1.1 404 Not Found');
			die(NDPHP_LANG_MOD_UNABLE_UPDATE_NOSUIT_VERSION);
		}

		/** Stage 2: Create required directories **/
		foreach ($tracker[$from_version]['directories'] as $directory) {
			if (file_exists(SYSTEM_BASE_DIR . '/' . $directory))
				continue;

			if (mkdir(SYSTEM_BASE_DIR . '/' . $directory) === false) {
				header('HTTP/1.1 403 Forbidden');
				die(NDPHP_LANG_MOD_UNABLE_CREATE_DIRECTORY . ': ' . SYSTEM_BASE_DIR . '/' . $directory);
			}
		}

		/** Stage 3: Check if we've permissions to overwrite the files to be updated **/
		foreach ($tracker[$from_version]['files'] as $file) {
			$fp = fopen(SYSTEM_BASE_DIR . '/' . $file, 'a+');

			if ($fp === false) {
				header('HTTP/1.1 403 Forbidden');
				die(NDPHP_LANG_MOD_INSTALL_WRITE_NO_PRIV . ': ' . SYSTEM_BASE_DIR . '/' . $file);
			}
		}

		/** Stage 4: Fetch and replace files **/
		foreach ($tracker[$from_version]['files'] as $file) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $this->_ndphp_github_content_url . $tracker[$from_version]['to'] . '/' . $file);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$file_contents = curl_exec($ch);
			curl_close($ch);

			if (!$file_contents) {
				header('HTTP/1.1 500 Internal Server Error');
				die(NDPHP_LANG_MOD_UNABLE_RETRIEVE_FILE_DATA . ': ' . SYSTEM_BASE_DIR . '/' . $file);
			}

			$fp = fopen(SYSTEM_BASE_DIR . '/' . $file, 'w');

			if ($fp === false) {
				header('HTTP/1.1 403 Forbidden');
				die(NDPHP_LANG_MOD_INSTALL_WRITE_NO_PRIV . ': ' . SYSTEM_BASE_DIR . '/' . $file);
			}

			/* Update file contents */
			if (fwrite($fp, $file_contents) === false) {
				header('HTTP/1.1 403 Forbidden');
				die(NDPHP_LANG_MOD_INSTALL_WRITE_NO_PRIV . ': ' . SYSTEM_BASE_DIR . '/' . $file);
			}

			fclose($fp);

			/* Reset time limit */
			set_time_limit(30); /* We do not expect that a file update will take longer than 30 seconds... */
		}

		/** Stage 5: Execute any required SQL queries **/
		foreach ($tracker[$from_version]['data_model_queries'] as $query) {
			$this->db->trans_begin();

			$this->db->query($query);

			if ($this->db->trans_status() === false) {
				$this->db->trans_rollback();
				header('HTTP/1.1 500 Internal Server Error');
				die(NDPHP_LANG_MOD_UNABLE_UPDATE_EXEC_QUERY);
			}

			$this->db->trans_commit();
		}

		/** Stage 6: Wait a little while... */
		sleep(3);

		/** Stage 7: Redirect to the post update method **/
		redirect($tracker[$from_version]['post_update_redirect']);
	}

	public function index() {
		header('HTTP/1.1 500 Internal Server Error');
		die('RELOAD THE PAGE');
	}

	public function post_update($from, $to) {
		redirect('/');
	}
}