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

/*
 * Raw Roadmap
 *
 * Legend:
 *
 *			+ More likely to be done on next release
 *			* Less likely to be done on next release
 *			- Probably won't be done on next release
 *
 * TODO:
 *
 * * Make a copy of the current tree before performing the system update.
 *
 */

class Update extends ND_Controller {
	/* ND PHP Framework - update settings */
	private $_ndphp_github_content_url = 'https://raw.githubusercontent.com/ucodev/nd-php/';
	private $_ndphp_url = 'http://www.nd-php.org';

	/* Constructor */
	public function __construct($session_enable = true, $json_replies = false) {
		parent::__construct($session_enable, $json_replies);

		/* Initialize controller */
		$this->_init(get_class(), true);

		/* Grant that only ROLE_ADMIN is able to access this controller */
		if (!$this->security->im_superadmin())
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_ONLY_SUPERADMIN, $this->config['default_charset'], !$this->request->is_ajax());
	}

	
	/** Hooks **/
	

	/** Other overloads **/


	/** Custom functions **/

	public function system_update() {
		/** Stage 0: Check if repository version is different than this controller version **/
		$ch = curl_init();
		$headers = array(
			'Cache-Control: no-cache'
		);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_URL, $this->_ndphp_github_content_url . 'master/VERSION');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$version_file_contents = curl_exec($ch);
		curl_close($ch);

		$version = explode(' ', $version_file_contents);

		if ($version[0] == $this->config['ndphp_version']) {
			error_log('Already at the newest version (' . $this->config['ndphp_version'] . ').');

			/** Nothing to do **/
			redirect('/');
		} else {
			/** Stage 1: Fetch tracker **/
			$ch = curl_init();
			$headers = array(
				'Cache-Control: no-cache'
			);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_URL, $this->_ndphp_github_content_url . 'master/install/updates/tracker.json');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$tracker_file_contents = curl_exec($ch);
			curl_close($ch);

			if (($tracker = json_decode($tracker_file_contents, true)) === NULL)
				$this->response->code('500', NDPHP_LANG_MOD_UNABLE_UPDATE_DECODE_TRACKER, $this->config['default_charset'], !$this->request->is_ajax());

			/** Stage 2: Determine the tracker entry to be used **/
			$from_version = $this->config['ndphp_version'];

			if (!isset($tracker[$from_version]))
				$from_version = 'any';

			if (!isset($tracker[$from_version]))
				$this->response->code('500', NDPHP_LANG_MOD_UNABLE_UPDATE_NOSUIT_VERSION, $this->config['default_charset'], !$this->request->is_ajax());

			/** Stage 3: Create required directories **/
			foreach ($tracker[$from_version]['directories'] as $directory) {
				if (file_exists(SYSTEM_BASE_DIR . '/' . $directory))
					continue;

				if (mkdir(SYSTEM_BASE_DIR . '/' . $directory) === false)
					$this->response->code('403', NDPHP_LANG_MOD_UNABLE_CREATE_DIRECTORY . ': ' . SYSTEM_BASE_DIR . '/' . $directory, $this->config['default_charset'], !$this->request->is_ajax());
			}

			/** Stage 4: Check if we've permissions to overwrite the files to be updated **/
			foreach ($tracker[$from_version]['files'] as $file) {
				$fp = fopen(SYSTEM_BASE_DIR . '/' . $file, 'a+');

				if ($fp === false)
					$this->response->code('403', NDPHP_LANG_MOD_INSTALL_WRITE_NO_PRIV . ': ' . SYSTEM_BASE_DIR . '/' . $file, $this->config['default_charset'], !$this->request->is_ajax());
			}

			/** Stage 5: Touch files **/
			foreach ($tracker[$from_version]['touch'] as $file) {
				$fp = fopen(SYSTEM_BASE_DIR . '/' . $file, 'a+');

				if ($fp === false)
					$this->response->code('403', NDPHP_LANG_MOD_INSTALL_WRITE_NO_PRIV . ': ' . SYSTEM_BASE_DIR . '/' . $file, $this->config['default_charset'], !$this->request->is_ajax());
			}

			/** Stage 6: Fetch and update files **/
			foreach ($tracker[$from_version]['files'] as $file) {
				$ch = curl_init();
				$headers = array(
					'Cache-Control: no-cache'
				);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($ch, CURLOPT_URL, $this->_ndphp_github_content_url . $tracker[$from_version]['to'] . '/' . $file);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$file_contents = curl_exec($ch);

				/* Grant that we've received the file contents... and not just some error... */
				/* FIXME: We need to put some further validations here in order to grant that the validity and integrity of the contents */
				if (curl_getinfo($ch, CURLINFO_HTTP_CODE) != 200)
					$this->response->code('500', NDPHP_LANG_MOD_UNABLE_RETRIEVE_FILE_DATA . ': ' . SYSTEM_BASE_DIR . '/' . $file, $this->config['default_charset'], !$this->request->is_ajax());

				curl_close($ch);

				$fp = fopen(SYSTEM_BASE_DIR . '/' . $file, 'w');

				if ($fp === false)
					$this->response->code('403', NDPHP_LANG_MOD_INSTALL_WRITE_NO_PRIV . ': ' . SYSTEM_BASE_DIR . '/' . $file, $this->config['default_charset'], !$this->request->is_ajax());

				/* Update file contents */
				if (fwrite($fp, $file_contents) === false)
					$this->response->code('403', NDPHP_LANG_MOD_INSTALL_WRITE_NO_PRIV . ': ' . SYSTEM_BASE_DIR . '/' . $file, $this->config['default_charset'], !$this->request->is_ajax());

				fclose($fp);

				/* Reset time limit */
				set_time_limit(30); /* Reset the limit counter. We do not expect that a file update will take longer than 30 seconds... */
			}

			/** Stage 7: Remove unused files **/
			foreach ($tracker[$from_version]['remove'] as $file) {
				if (file_exists(SYSTEM_BASE_DIR . '/' . $file))
					unlink(SYSTEM_BASE_DIR . '/' . $file);
			}

			/** Stage 8: Replace files **/
			foreach ($tracker[$from_version]['replace'] as $file_rep) {
				if (copy(SYSTEM_BASE_DIR . '/' . $file_rep[1], SYSTEM_BASE_DIR . '/' . $file_rep[0]) === false)
					$this->response->code('500', NDPHP_LANG_MOD_INSTALL_WRITE_NO_PRIV . ': ' . SYSTEM_BASE_DIR . '/' . $file_rep[0], $this->config['default_charset'], !$this->request->is_ajax());
			}

			/** Stage 9: Execute any required SQL queries **/
			foreach ($tracker[$from_version]['data_model_queries'] as $query) {
				$this->db->trans_begin();

				$this->db->query($query);

				if ($this->db->trans_status() === false) {
					$this->db->trans_rollback();
					error_log(NDPHP_LANG_MOD_UNABLE_UPDATE_EXEC_QUERY . ': ' . $query);
					//$this->response->code('500', NDPHP_LANG_MOD_UNABLE_UPDATE_EXEC_QUERY, $this->config['default_charset'], !$this->request->is_ajax());
					continue;
				}

				$this->db->trans_commit();
			}

			/** Stage 10: Wait a little while... */
			sleep(3);

			/** Stage 11: Redirect to the post update method **/
			redirect($tracker[$from_version]['post_update_redirect']);
		}
	}

	public function index() {
		$this->response->code('500', 'RELOAD THE PAGE', $this->config['default_charset'], !$this->request->is_ajax());
	}

	public function post_update($from, $to) {
		error_log('Update from \'' . $from . '\' to \'' . $to . '\' successful.');

		redirect('/');
	}
}
