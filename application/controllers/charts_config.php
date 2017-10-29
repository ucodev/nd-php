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

class Charts_config extends ND_Controller {
	/* Constructor */
	public function __construct($session_enable = true, $json_replies = false) {
		parent::__construct($session_enable, $json_replies);

		/* Initialize controller */
		$this->_init(get_class(), true);

		/* Grant that only ROLE_ADMIN is able to access this controller */
		if (!$this->security->im_admin())
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_ONLY_ADMIN, $this->config['default_charset'], !$this->request->is_ajax());
	}
	
	/** Hooks **/
	
	/** Other overloads **/

	/* Hidden fields per view.
	 *
	 * Note that for relationship fields, the field name used here must be the one
	 * corresponding to the foreign table field.
	 * 
	 */
	protected $_hide_fields_create = array('id');
	protected $_hide_fields_edit = array('id');
	protected $_hide_fields_view = array();
	protected $_hide_fields_remove = array();
	protected $_hide_fields_list = array('fields', 'abscissa', 'foreign_table', 'start_ts', 'end_ts', 'field', 'field_ts', 'field_legend', 'field_total', 'import_ctrl', 'chartid');
	protected $_hide_fields_result = array('fields', 'abscissa', 'foreign_table', 'start_ts', 'end_ts', 'field', 'field_ts', 'field_legend', 'field_total', 'import_ctrl', 'chartid');
	protected $_hide_fields_search = array(); // Includes fields searched on searchbar (basic)
	protected $_hide_fields_export = array('fields', 'abscissa', 'foreign_table', 'start_ts', 'end_ts', 'field', 'field_ts', 'field_legend', 'field_total', 'import_ctrl', 'chartid');

	/* Aliases for the current table field names */
	protected $_table_field_aliases = array(
		'title' => NDPHP_LANG_MOD_COMMON_TITLE,
		'controller' => NDPHP_LANG_MOD_COMMON_CONTROLLER,
		'table' => NDPHP_LANG_MOD_COMMON_TABLE,
		'fields' => NDPHP_LANG_MOD_COMMON_FIELDS,
		'abscissa' => NDPHP_LANG_MOD_COMMON_ABSCISSA,
		'foreign_table' => NDPHP_LANG_MOD_COMMON_FOREIGN_TABLE,
		'start_ts' => NDPHP_LANG_MOD_COMMON_START_TS,
		'end_ts' => NDPHP_LANG_MOD_COMMON_END_TS,
		'field' => NDPHP_LANG_MOD_COMMON_FIELD,
		'field_ts' => NDPHP_LANG_MOD_COMMON_FIELD_TS,
		'field_legend' => NDPHP_LANG_MOD_COMMON_FIELD_LEGEND,
		'field_total' => NDPHP_LANG_MOD_COMMON_FIELD_TOTAL,
		'import_ctrl' => NDPHP_LANG_MOD_COMMON_IMPORT_CTRL,
		'chartid' => NDPHP_LANG_MOD_COMMON_CHARTID
	);

	protected $_rel_table_fields_config = array(
		'charts_types' => array(NDPHP_LANG_MOD_COMMON_CHART_TYPE, NULL, array(1), array('id', 'asc'), NULL),
		'charts_geometry' => array(NDPHP_LANG_MOD_COMMON_CHART_GEOMETRY, NULL, array(1), array('id', 'asc'), NULL)
	);

	/* Table relational choices (conditional drop-down) */
	protected $_rel_choice_hide_fields = array(
		'charts_types_id' => array(
			1 => array('foreign_table', 'field', 'field_ts', 'field_legend', 'field_total', 'import_ctrl', 'chartid'),
			2 => array('fields', 'abscissa', 'foreign_table', 'field_legend', 'field_total', 'import_ctrl', 'chartid'),
			3 => array('abscissa', 'foreign_table', 'field', 'import_ctrl', 'chartid'),
			4 => array('field', 'field_ts', 'field_legend', 'field_total', 'import_ctrl', 'chartid'),
			5 => array('fields', 'abscissa', 'field_legend', 'field_total', 'import_ctrl', 'chartid'),
			6 => array('abscissa', 'field', 'import_ctrl', 'chartid'),
			7 => array('fields', 'abscissa', 'foreign_table', 'start_ts', 'end_ts', 'field', 'field_ts', 'field_legend', 'field_total', 'charts_geometry_id'),
			8 => array('fields', 'abscissa', 'foreign_table', 'start_ts', 'end_ts', 'field', 'field_ts', 'field_legend', 'field_total', 'charts_geometry_id')
		)
	);

	/** Custom functions **/

}