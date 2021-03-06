<?php if (!defined('FROM_BASE')) { header($_SERVER['SERVER_PROTOCOL'] . ' 403'); die('Invalid requested path.'); }

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

class Documentation extends ND_Controller {
	/* Constructor */
	public function __construct($session_enable = true, $json_replies = false) {
		parent::__construct($session_enable, $json_replies);

		/* Initialize controller */
		$this->_init(get_class(), true);
	}
	
	/** Hooks **/
	
	/** Other overloads **/

	/* Direction by which the listing views shall be ordered by */
	protected $_table_field_order_list_modifier = 'desc';

	/* Direction by which the result views shall be ordered by */
	protected $_table_field_order_result_modifier = 'desc';

	/** Custom functions **/

	/* Documentation */
	private function _doc_revision() {
		/* Fetch documentation revision */
		$q = $this->db->get($this->config['name']);

		if ($q->num_rows()) {
			$row = $q->row_array();
			return $row['revision'] . ' (' . $row['changed'] . ')';
		}

		return 'N/A';
	}

	public function hooks() {
		$data = $this->get->view_data_generic();

		$data['view']['doc_revision'] = $this->_doc_revision();

		/* Load documentation view */
		$this->load->view('documentation/hooks', $data);
	}

	public function overloads() {
		$data = $this->get->view_data_generic();

		$data['view']['doc_revision'] = $this->_doc_revision();

		/* Load documentation view */
		$this->load->view('documentation/overloads', $data);
	}

	public function api() {
		$data = $this->get->view_data_generic();

		$data['view']['doc_revision'] = $this->_doc_revision();

		/* Load documentation view */
		$this->load->view('documentation/api', $data);
	}

	public function internals() {
		$data = $this->get->view_data_generic();

		$data['view']['doc_revision'] = $this->_doc_revision();

		/* Load documentation view */
		$this->load->view('documentation/internals', $data);
	}

	public function rest($controller = 'index') {
		if ($controller == 'index') {
			$data = $this->get->view_data_generic();

			$data['view']['doc_revision'] = $this->_doc_revision();
			$data['view']['controllers'] = $this->get->controller_list();

			/* Load documentation view */
			$this->load->view('documentation/rest', $data);
		} else {
			redirect($controller . '/json_doc');
		}
	}

	public function ide() {
		$data = $this->get->view_data_generic();

		$data['view']['doc_revision'] = $this->_doc_revision();

		/* Load documentation view */
		$this->load->view('documentation/ide', $data);
	}

	public function index() {
		$data = $this->get->view_data_generic();

		$data['view']['doc_revision'] = $this->_doc_revision();

		/* Load documentation view */
		$this->load->view('documentation/intro', $data);
	}
}

