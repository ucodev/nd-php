<?php if (!defined('FROM_BASE')) { header('HTTP/1.1 403 Forbidden'); die('Invalid requested path.'); }

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
 * TODO:
 *
 * * Before deploying new models, backup the entire database and application.
 * - Add a new property field (named index_uri) which will allow the user to set the default index redirection location for a particular controller.
 * - Change field and menu entry types (Currently we need to remove them and add them again as a new type, losing all the data for that field).
 * - Droping a non-instantiated pool object over an instantiated object of the same family shall change the latest type/title (with confirmation dialog).
 * - [DONE] Download button to export ndmodel (application model in JSON format)
 * - Download button to export nddata (table dump in JSON format)
 * - Import button to import ndmodel (application model in JSON format)
 * - Import button to import nddata (table dump in JSON format)
 * - [IN PROGRESS] A lot of javascript validations are missing:
 *  * Multiple relationship field objects shall be replicated to the foreign table, or ignored from the foreign table if the user replicated them (Choose one...)
 * - Field 'Hidden' constraint must go.
 * - IDE Builder view should contain a link to open the webapp (in a new tab).
 * * Add context-menu insert options on Controller code edition (to insert templates for hooks, charts, etc.).
 * * When a table is renamed containing _file_* fields and is referenced as mixed, the uploads/ directory must be changed to keep the integrity of uploaded files links.
 * * When sharding is enabled, all the databases need to be updated, not just the default database.
 *
 * FIXME:
 *
 * * Changing controllers' fields that are already linked as mixed relationships will break the model (fields will be missing on the mixed view).
 *
 */

class Builder extends ND_Controller {
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

	/* Direction by which the listing views shall be ordered by */
	protected $_table_field_order_list_modifier = 'desc';

	/* Direction by which the result views shall be ordered by */
	protected $_table_field_order_result_modifier = 'desc';


	/** Custom functions **/

	public function ide() {
		global $config;

		/* Fetch all available roles */
		$this->db->select('role');
		$this->db->from('roles');
		$query = $this->db->get();

		$data = $this->get->view_data_generic('ND PHP Framework - Builder IDE (' . $config['base']['type'] . ')', 'Builder IDE (' . $config['base']['type'] . ')');

		/* Setup view data: Load available menu icons */
		$data['view']['menu_icons'] = array();
		$images_path = SYSTEM_BASE_DIR . '/' . preg_replace('/' . preg_quote(base_dir(), '/') . '/', '', static_images_dir(), 1);
		foreach (glob($images_path . '/themes/' . $this->config['default_theme'] . '/menu/iconset/png/24x24/*') as $icon_file_path) {
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
			if (($data['view']['application'] = json_decode($row['model'], true)) === NULL)
				$this->response->code('500', NDPHP_LANG_MOD_UNABLE_DECODE_DATA_JSON, $this->config['default_charset'], !$this->request->is_ajax());
		} else {
			$data['view']['application'] = array();
		}

		/* Load IDE Builder view */
		$this->load->view($this->config['name'] . '/ide', $data);
	}

	public function save_model() {
		global $config;

		/* Check if we are a master node */
		if (!isset($config['base']['type']) || ($config['base']['type'] != 'master'))
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_ONLY_MASTER, $this->config['default_charset'], !$this->request->is_ajax());

		/* Read JSON data */
		$json_raw = file_get_contents('php://input');

		if (($application = json_decode($json_raw, true)) === NULL)
			$this->response->code('500', NDPHP_LANG_MOD_UNABLE_DECODE_DATA_JSON, $this->config['default_charset'], !$this->request->is_ajax());

		/* Save the new application model */
		$this->db->trans_begin();

		$this->db->where('active', true);
		$this->db->update('configuration', array('model' => $json_raw));

		/* Commit transaction */
		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			$this->response->code('500', NDPHP_LANG_MOD_FAILED_UPDATE_APP_MODEL, $this->config['default_charset'], !$this->request->is_ajax());
		} else {
			$this->db->trans_commit();
		}

		$this->response->output(ucfirst(NDPHP_LANG_MOD_WORD_SAVED));
	}

	public function deploy_model() {
		global $config;

		/* Check if we are a master node */
		if (!isset($config['base']['type']) || ($config['base']['type'] != 'master'))
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_ONLY_MASTER, $this->config['default_charset'], !$this->request->is_ajax());

		/* Read JSON data */
		$json_raw = file_get_contents('php://input');

		if (($application = json_decode($json_raw, true)) === NULL)
			$this->response->code('500', NDPHP_LANG_MOD_UNABLE_DECODE_DATA_JSON, $this->config['default_charset'], !$this->request->is_ajax());

		/* Clear cache if it is active */
		if ($this->cache->is_active())
			$this->cache->flush();

		/* Save the new application model */
		$this->db->trans_begin();

		$this->db->where('active', true);
		$this->db->update('configuration', array('model' => $json_raw));

		/* Commit transaction */
		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			$this->response->code('500', NDPHP_LANG_MOD_FAILED_UPDATE_APP_MODEL, $this->config['default_charset'], !$this->request->is_ajax());
		} else {
			$this->db->trans_commit();
		}

		/* Process the raw application model, deploy it, and receive the complete application model */
		if (($app_model = $this->application->deploy_model($application)) === false)
			$this->response->code('500', NDPHP_LANG_MOD_UNABLE_PROCESS_APP_MODEL, $this->config['default_charset'], !$this->request->is_ajax());

		/* Update the new application model with a more complete set of data */
		$this->db->trans_begin();

		$this->db->where('active', true);
		$this->db->update('configuration', array('model' => json_encode($app_model)));

		/* Commit transaction */
		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			$this->response->code('500', NDPHP_LANG_MOD_FAILED_UPDATE_APP_MODEL, $this->config['default_charset'], !$this->request->is_ajax());
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

		/* Re-clear cache if it is active (just to make sure) */
		if ($this->cache->is_active())
			$this->cache->flush();

		/* XXX: Debug */
		$this->response->output(ucfirst(NDPHP_LANG_MOD_WORD_BUILD) . ' ' . $build_current . ' ' . NDPHP_LANG_MOD_SUCCESS_DEPLOY_ON . ' ' . date('Y-m-d H:i:s') . '.');
	}

	public function commit_controllers() {
		global $config;

		/* Check if we are a slave node */
		if (!isset($config['base']['type']) || ($config['base']['type'] != 'slave'))
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_ONLY_SLAVE, $this->config['default_charset'], !$this->request->is_ajax());

		/* Read JSON data */
		$json_raw = file_get_contents('php://input');

		if (($application = json_decode($json_raw, true)) === NULL)
			$this->response->code('500', NDPHP_LANG_MOD_UNABLE_DECODE_DATA_JSON, $this->config['default_charset'], !$this->request->is_ajax());

		/* Clear cache if it is active */
		if ($this->cache->is_active())
			$this->cache->flush();

		/* Process the application model, but only deploy the controllers (no database changes will be performed) */
		if ($this->application->commit_controllers($application) === false)
			$this->response->code('500', NDPHP_LANG_MOD_UNABLE_COMMIT_CONTROLLERS, $this->config['default_charset'], !$this->request->is_ajax());

		/* Re-clear cache if it is active (just to make sure) */
		if ($this->cache->is_active())
			$this->cache->flush();

		/* Retrieve build number */
		$this->db->select('build');
		$this->db->from('builder');
		$this->db->order_by('build', 'desc');
		$this->db->limit(1);
		$q = $this->db->get();

		/* Check if there are any builds present */
		if (!$q->num_rows())
			$this->response->code('500', NDPHP_LANG_MOD_UNABLE_GET_CURRENT_BUILD, $this->config['default_charset'], !$this->request->is_ajax());

		/* Store current build data */
		$build_current = $q->row_array()['build'];

		/* All good */
		$this->response->output(NDPHP_LANG_MOD_INFO_IDE_CTRLS_DEPLOYED . $build_current, $this->config['default_charset'], !$this->request->is_ajax());
	}

	public function apply_acls() {
		global $config;

		/* Check if we are a master node */
		if (!isset($config['base']['type']) || ($config['base']['type'] != 'master'))
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_ONLY_MASTER, $this->config['default_charset'], !$this->request->is_ajax());

		/* Read JSON data */
		$json_raw = file_get_contents('php://input');

		if (($application = json_decode($json_raw, true)) === NULL)
			$this->response->code('500', NDPHP_LANG_MOD_UNABLE_DECODE_DATA_JSON, $this->config['default_charset'], !$this->request->is_ajax());

		/* Clear cache if it is active */
		if ($this->cache->is_active())
			$this->cache->flush();

		/* Process the application model, but only apply ACLs (no data model will be modified nor controllers updated) */
		if ($this->application->apply_acls($application) === false)
			$this->response->code('500', NDPHP_LANG_MOD_UNABLE_APPLY_ACLS, $this->config['default_charset'], !$this->request->is_ajax());

		/* Re-clear cache if it is active (just to make sure) */
		if ($this->cache->is_active())
			$this->cache->flush();

		/* Retrieve build number */
		$this->db->select('build');
		$this->db->from('builder');
		$this->db->order_by('build', 'desc');
		$this->db->limit(1);
		$q = $this->db->get();

		/* Check if there are any builds present */
		if (!$q->num_rows())
			$this->response->code('500', NDPHP_LANG_MOD_UNABLE_GET_CURRENT_BUILD, $this->config['default_charset'], !$this->request->is_ajax());

		/* Store current build data */
		$build_current = $q->row_array()['build'];

		/* All good */
		$this->response->output(NDPHP_LANG_MOD_INFO_IDE_ACLS_APPLIED . $build_current, $this->config['default_charset'], !$this->request->is_ajax());
	}

	public function view_model($mode = 'output') {
		$this->db->select('model');
		$this->db->from('configuration');
		$this->db->where('active', true);
		$query = $this->db->get();

		if ($mode == 'download') {
			$this->response->download(
				/* data */            $query->row_array()['model'],
				/* filename */        strtolower(str_replace(' ', '_', $this->config['project_name'])) . '.ndmodel',
				/* content-type */    'application/json',
				/* charset */         $this->config['default_charset'],
				/* content-encoding*/ NDPHP_LANG_MOD_DEFAULT_CHARSET
			);
		} else {
			$this->response->output($query->row_array()['model']);
		}
	}

	public function generate_model() {
		/* TODO: FIXME: 'model' from configuration table should be replaced with a builder_id entry */

		$application = $this->application->generate_model();

		$json_raw = json_encode($application);

		$this->_ndmodel_import_deploy($json_raw);

		/* XXX: Debug */
		$this->response->output(NDPHP_LANG_MOD_SUCCESS_LOAD_APP_MODEL);
	}

	public function wipe($magic = NULL, $wipe_models = false) {
		/* Grant (to a certain level) that we're not calling this method by mistake */
		if ($magic != gmdate('YmdHi'))
			$this->response->code('403', NDPHP_LANG_MOD_INVALID_MAGIC_IDENTIFIER, $this->config['default_charset'], !$this->request->is_ajax());

		/* Drop all main tables */
		$this->db->select('db_table AS table');
		$this->db->from('model_objects');
		$q = $this->db->get();

		foreach ($q->result_array() as $row) {
			$this->db->table_drop($row['table'], true, true);
		}

		/* Drop all relationship tables */
		$this->db->select('db_table_field AS table');
		$this->db->from('model_objects');
		$this->db->like('db_table_field', 'mixed_%');
		$this->db->or_like('db_table_field', 'rel_%');
		$q = $this->db->get();

		foreach ($q->result_array() as $row) {
			$this->db->table_drop($row['table'], true, true);
		}

		$this->db->delete('model_objects');

		/* Check if we're also wiping the application models */
		if ($wipe_models) {
			/* Drop all builds */
			$this->db->delete('builder');

			/* Drop model from current active configuration */
			$this->db->where('active', true);
			$this->db->update('configuration', array('model' => ''));
		}

		/* Clear cache if it is active */
		if ($this->cache->is_active())
			$this->cache->flush();

		/* Redirect to the IDE Builder */
		redirect('/builder/ide');
	}


	/**************/
	/*  Importer  */
	/**************/

	protected function _ndapp_import_deploy($ndapp_contents) {
		/* TODO: Not yet implemented */
		$this->response->output('nyi<br />');
		$this->response->output('ndapp contents:<br />');
		$this->response->output($ndapp_contents);
	}

	protected function _ndapp_import_from_url($url = NULL) {
		/* Fetch ND App contents from $url */
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		$ndapp_contents = curl_exec($ch);
		if (($code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) != 200) {
			ob_clean();
			$this->response->code('500', NDPHP_LANG_MOD_UNABLE_LOAD_NDAPP_URL . ': ' . $url, $this->config['default_charset'], !$this->request->is_ajax());
		}
		curl_close($ch);

		/* Setup ND App */
		$this->_ndapp_import_deploy($ndapp_contents);
	}

	protected function _ndapp_import_from_file() {
		$ndapp_file = $this->request->upload('ndapp_file', SYSTEM_BASE_DIR . '/uploads/import/ndapp');

		$ndapp_contents = file_get_contents($ndapp_file);

		unlink($ndapp_file);

		/* Setup ND App */
		$this->_ndmodel_import_deploy($ndapp_contents);
	}

	protected function _ndmodel_import_deploy($ndmodel_contents) {
		/* Save the new application model */
		$this->db->trans_begin();

		$this->db->where('active', true);
		$this->db->update('configuration', array('model' => $ndmodel_contents));

		/* Commit transaction */
		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			$this->response->code('500', NDPHP_LANG_MOD_FAILED_UPDATE_APP_MODEL, $this->config['default_charset'], !$this->request->is_ajax());
		} else {
			$this->db->trans_commit();
		}
	}

	protected function _ndmodel_import_from_data($ndmodel_contents) {
		$this->_ndmodel_import_deploy($ndmodel_contents);
	}

	protected function _ndmodel_import_from_file() {
		$ndmodel_file = $this->request->upload('ndmodel_file', SYSTEM_BASE_DIR . '/uploads/import/ndmodel');

		$ndmodel_contents = file_get_contents($ndmodel_file);

		unlink($ndmodel_file);

		$this->_ndmodel_import_deploy($ndmodel_contents);
	}

	protected function _nddata_import_deploy($nddata_contents) {
		/* TODO: Not yet implemented */
		$this->response->output('nyi<br />');
		$this->response->output('nddata contents:<br />');
		$this->response->output($nddata_contents);
	}

	protected function _nddata_import_from_data($nddata_contents) {
		$this->_ndmodel_import_deploy($nddata_contents);
	}

	protected function _nddata_import_from_file() {
		$nddata_file = $this->request->upload('nddata_file', SYSTEM_BASE_DIR . '/uploads/import/nddata');

		$nddata_contents = file_get_contents($nddata_file);

		unlink($nddata_file);

		$this->_nddata_import_deploy($nddata_contents);
	}

	public function import_ndapp() {
		/* Check if the application data should be fetched from an URL or from an uploaded file. */
		if (strlen($this->request->input('ndapp_url'))) {
			$this->_ndapp_import_from_url($this->request->input('ndapp_url'));
		} else {
			$this->_ndapp_import_from_file();
		}

		//redirect('builder/ide');
	}

	public function import_ndmodel() {
		/* Check if the application model should be fetched from the input field or from an uploaded file. */
		if (strlen($this->request->input('ndmodel_contents'))) {
			$this->_ndmodel_import_from_data($this->request->input('ndmodel_contents'));
		} else {
			$this->_ndmodel_import_from_file();
		}

		redirect('builder/ide');
	}

	public function import_nddata() {
		/* Check if the application model should be fetched from the input field or from an uploaded file. */
		if (strlen($this->request->input('nddata_contents'))) {
			$this->_nddata_import_from_data($this->request->input('nddata_contents'));
		} else {
			$this->_nddata_import_from_file();
		}

		//redirect('builder/ide');
	}

    public function importer() {
		$data = $this->get->view_data_generic('ND PHP Framework - Importer', 'Importer');
		$this->load->view($this->config['name'] . '/importer', $data);
    }


    /***********/
	/*  Tools  */
	/***********/

	public function transcoder() {
		$data = $this->get->view_data_generic('ND PHP Framework - Transcoder', 'Transcoder');

		$this->load->view($this->config['name'] . '/transcoder', $data);
	}

	public function converter() {
		$data = $this->get->view_data_generic('ND PHP Framework - Converter', 'Converter');

		$this->load->view($this->config['name'] . '/converter', $data);
	}
}

