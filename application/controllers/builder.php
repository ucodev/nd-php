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

/*
 * TODO:
 *
 * - Change field and menu entry types (Currently we need to remove them and add them again as a new type, losing all the data for that field).
 * - Droping a non-instantiated pool object over an instantiated object of the same family shall change the latest type/title (with confirmation dialog).
 * - Relational field name aliases (such as Mixed relationship fields) are not being processed (no rel_table_config is being set on the controller with the aliases for that table).
 * - Download button to export ndmodel (application model in JSON format)
 * - Download button to export nddata (table dump in JSON format)
 * - Import button to import ndmodel (application model in JSON format)
 * - Import button to import nddata (table dump in JSON format)
 * - [IN PROGRESS] A lot of javascript validations are missing:
 *  * Validate all data input with regex (including constraints, properties, etc either on menu and field objects).
 *  * Multiple relationship field objects shall be replicated to the foreign table, or ignored from the foreign table if the user replicated them (Choose one...)
 *  * Check for reserved prefixes for menu: rel_ and mixed_
 *  * Check for reserved prefixes on fields: _file_, _timer_, _separator_
 *  * Check for reserved suffixes on fields: _id, _time
 * - Field 'Hidden' constraint must go.
 * - IDE Builder view should contain a link to open the webapp (in a new tab).
 * * Add context-menu insert options on Controller code edition (to insert templates for hooks, charts, etc.).
 * * When a table is renamed containing _file_* fields and is referenced as mixed, the uploads/ directory must be changed to keep the integrity of uploaded files links.
 * * When sharding is enabled, all the databases need to be updated, not just the default database.
 *
 */

class Builder extends ND_Controller {
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

	/* Direction by which the listing views shall be ordered by */
	protected $_direction_listing_order = 'desc';

	/* Direction by which the result views shall be ordered by */
	protected $_direction_result_order = 'desc';


	/** Custom functions **/

	public function ide() {
		/* Fetch all available roles */
		$this->db->select('role');
		$this->db->from('roles');
		$query = $this->db->get();

		$data = $this->_get_view_data_generic('ND PHP Framework - Builder IDE', 'Builder IDE');

		/* Setup view data: Load available menu icons */
		$data['view']['menu_icons'] = array();
		foreach (glob(SYSTEM_BASE_DIR . implode('/', array_slice(explode('/', static_images_dir()), 2)) . '/themes/' . $this->_theme . '/menu/iconset/png/24x24/*') as $icon_file_path) {
			/* Strip the directory path, leaving only the filename ... */
			array_push($data['view']['menu_icons'], end(explode('/', $icon_file_path)));
		}

		/* Setup view data: Load roles */
		$data['view']['roles'] = $query->result_array();

		$this->db->select('model');
		$this->db->from('configuration');
		$this->db->where('active', true);
		$query = $this->db->get();

		/* Setup view data: Load application model (JSON) */
		$row = $query->row_array();
		if ($row['model']) {
			if (($data['view']['application'] = json_decode($row['model'], true)) === NULL) {
				header('HTTP/1.1 500 Internal Server Error');
				die(NDPHP_LANG_MOD_UNABLE_DECODE_DATA_JSON);
			}
		} else {
			$data['view']['application'] = array();
		}

		/* Load IDE Builder view */
		$this->load->view($this->_name . '/ide', $data);
	}

	public function save_model() {
		/* Read JSON data */
		$json_raw = file_get_contents('php://input');
		if (($application = json_decode($json_raw, true)) === NULL) {
			header('HTTP/1.1 500 Internal Server Error');
			die(NDPHP_LANG_MOD_UNABLE_DECODE_DATA_JSON);
		}

		/* Save the new application model */
		$this->db->trans_begin();

		$this->db->where('active', true);
		$this->db->update('configuration', array('model' => $json_raw));

		/* Commit transaction */
		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			header("HTTP/1.1 500 Internal Server Error");
			die(NDPHP_LANG_MOD_FAILED_UPDATE_APP_MODEL);
		} else {
			$this->db->trans_commit();
		}

		echo('Saved.');
	}

	public function deploy_model() {
		/* Read JSON data */
		$json_raw = file_get_contents('php://input');
		if (($application = json_decode($json_raw, true)) === NULL) {
			header('HTTP/1.1 500 Internal Server Error');
			die(NDPHP_LANG_MOD_UNABLE_DECODE_DATA_JSON);
		}

		/* Save the new application model */
		$this->db->trans_begin();

		$this->db->where('active', true);
		$this->db->update('configuration', array('model' => $json_raw));

		/* Commit transaction */
		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			header("HTTP/1.1 500 Internal Server Error");
			die(NDPHP_LANG_MOD_FAILED_UPDATE_APP_MODEL);
		} else {
			$this->db->trans_commit();
		}

		/* Process the raw application model, deploy it, and receive the complete application model */
		if (($app_model = $this->application->deploy_model($application)) === false) {
			header('HTTP/1.1 500 Internal Server Error.');
			die(NDPHP_LANG_MOD_UNABLE_PROCESS_APP_MODEL);
		}

		/* Update the new application model with a more complete set of data */
		$this->db->trans_begin();

		$this->db->where('active', true);
		$this->db->update('configuration', array('model' => json_encode($app_model)));

		/* Commit transaction */
		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			header("HTTP/1.1 500 Internal Server Error");
			die(NDPHP_LANG_MOD_FAILED_UPDATE_APP_MODEL);
		} else {
			$this->db->trans_commit();
		}

		/* Record build number */
		$this->db->select('build');
		$this->db->from('builder');
		$this->db->order_by('build', 'desc');
		$this->db->limit(1);
		$q = $this->db->get();
		$build_current = $q->row_array()['build'] + 1;

		$this->db->insert('builder', array(
			'build' => $build_current,
			'created' => date('Y-m-d H:i:s'),
			'model' => $json_raw
		));

		/* XXX: Debug */
		echo(ucfirst(NDPHP_LANG_MOD_WORD_BUILD) . ' ' . $build_current . ' ' . NDPHP_LANG_MOD_SUCCESS_DEPLOY_ON . ' ' . date('Y-m-d H:i:s') . '.');
	}

	public function view_model() {
		$this->db->select('model');
		$this->db->from('configuration');
		$this->db->where('active', true);
		$query = $this->db->get();

		echo($query->row_array()['model']);
	}

	public function load_model() {
		/* TODO: FIXME: 'model' from configuration table should be replaced with a builder_id entry */

		$application = $this->application->generate_model();

		$json_raw = json_encode($application);

		/* Save the new application model */
		$this->db->trans_begin();

		$this->db->where('active', true);
		$this->db->update('configuration', array('model' => $json_raw));

		/* Commit transaction */
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			header("HTTP/1.1 500 Internal Server Error");
			die(NDPHP_LANG_MOD_FAILED_UPDATE_APP_MODEL);
		} else {
			$this->db->trans_commit();
		}

		/* XXX: Debug */
		echo(NDPHP_LANG_MOD_SUCCESS_LOAD_APP_MODEL);
	}
}

