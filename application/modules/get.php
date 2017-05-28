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

class UW_Get extends UW_Module {
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

	public function tables() {
		/* If we already have a populated table list, just return it... */
		if (count($this->config['cache_tables']))
			return $this->config['cache_tables']; /* All good */

		/* Check if we're using an external cache mechanism and, if so, read data from it */
		if ($this->cache->is_active()) {
			if ($this->cache->get('s_cache_tables')) {
				return $this->cache->get('d_cache_tables');
			}
		}

		/* Fetch the tables from the database */
		$query = $this->db->query("SHOW TABLES");

		/* If we're unable to retrieve the database tables, we can't proceed */
		if (!$query)
			$this->response->code('500', NDPHP_LANG_MOD_UNABLE_FETCH_CRIT_DATA_DBMS, $this->config['default_charset'], !$this->request->is_ajax());

		/* Populate the tables list */
		foreach ($query->result_array() as $field => $value) {
			foreach ($value as $header => $table) {
				array_push($this->config['cache_tables'], $table);
			}
		}

		/* Check if we're using an external cache mechanism and, if so, write data to it */
		if ($this->cache->is_active()) {
			$this->cache->set('s_cache_tables', true);
			$this->cache->set('d_cache_tables', $this->config['cache_tables']);
		}

		/* All good */
		return $this->config['cache_tables'];
	}

	public function table_desc($table = NULL) {
		/* If no table was specified, used this controller table */
		if ($table === NULL)
			$table = $this->config['name'];

		/* If we already have data cached for the $table, just return it... */
		if (isset($this->config['cache_table_desc'][$table]))
			return $this->config['cache_table_desc'][$table];

		/* Check if we're using an external cache mechanism and, if so, read data from it */
		if ($this->cache->is_active()) {
			if ($this->cache->get('s_cache_table_desc_' . $table)) {
				return $this->cache->get('d_cache_table_desc_' . $table);
			}
		}

		/* Otherwise, fetch it... */
		$this->config['cache_table_desc'][$table] = $this->db->describe_table($table);

		/* Check if we're using an external cache mechanism and, if so, write data to it */
		if ($this->cache->is_active()) {
			$this->cache->set('s_cache_table_desc_' . $table, true);
			$this->cache->set('d_cache_table_desc_' . $table, $this->config['cache_table_desc'][$table]);
		}

		/* All good */
		return $this->config['cache_table_desc'][$table];
	}

	public function table_fields($table = NULL) {
		/* If no table was specified, used this controller table */
		if ($table === NULL)
			$table = $this->config['name'];

		/* If we already have data cached for the $table, just return it... */
		if (isset($this->config['cache_table_fields'][$table]))
			return $this->config['cache_table_fields'][$table];

		/* Check if we're using an external cache mechanism and, if so, read data from it */
		if ($this->cache->is_active()) {
			if ($this->cache->get('s_cache_table_fields_' . $table)) {
				return $this->cache->get('d_cache_table_fields_' . $table);
			}
		}

		/* Otherwise, fetch it... */
		$this->config['cache_table_fields'][$table] = $this->db->list_fields($table);

		/* Check if we're using an external cache mechanism and, if so, write data to it */
		if ($this->cache->is_active()) {
			$this->cache->set('s_cache_table_fields_' . $table, true);
			$this->cache->set('d_cache_table_fields_' . $table, $this->config['cache_table_fields'][$table]);
		}

		/* All good */
		return $this->config['cache_table_fields'][$table];
	}

	public function help($table = NULL) {
		/* REST JSON requests do not require this data */
		if ($this->request->is_json())
			return NULL;

		/* If no table was specified, used this controller table */
		if ($table === NULL)
			$table = $this->config['name'];

		/* If we already have a populated the help data cache, just return it... */
		if (isset($this->config['cache_help'][$table]))
			return $this->config['cache_help'][$table];

		/* Check if we're using an external cache mechanism and, if so, read data from it */
		if ($this->cache->is_active()) {
			if ($this->cache->get('s_cache_help_' . $table)) {
				return $this->cache->get('d_cache_help_' . $table);
			}
		}

		/* Fetch help data from the database */
		$this->db->select('field_name, placeholder,field_units,units_on_left,input_pattern,help_description,help_url');
		$this->db->from('_help_tfhd');
		$this->db->where('table_name', $table);

		$query = $this->db->get();

		/* If there's no help data, nullify the entry and return it */
		if (!$query->num_rows()) {
			$this->config['cache_help'][$table] = NULL;
			return NULL;
		}

		/* Initialize help data entry for $table */
		$this->config['cache_help'][$table] = array();

		/* Populate help data */
		foreach ($query->result_array() as $row) {
			/* If there is no field assigned to this row, this help entry is related to the table, not the field... */
			if (!$row['field_name']) {
				/* ... So we use a special entry _self to store it */
				$this->config['cache_help'][$table]['_self'] = $row;
				continue;
			}

			$this->config['cache_help'][$table][$row['field_name']] = $row;
		}

		/* Check if we're using an external cache mechanism and, if so, write data to it */
		if ($this->cache->is_active()) {
			$this->cache->set('s_cache_help_' . $table, true);
			$this->cache->set('d_cache_help_' . $table, $this->config['cache_help'][$table]);
		}

		/* All good */
		return $this->config['cache_help'][$table];
	}

	public function build() {
		/* REST JSON requests do not require this data */
		if ($this->request->is_json())
			return NULL;

		/* If we already have a populated the build data cache, just return it... */
		if (isset($this->config['cache_build']))
			return $this->config['cache_build'];

		/* Check if we're using an external cache mechanism and, if so, read data from it */
		if ($this->cache->is_active()) {
			if ($this->cache->get('s_cache_build')) {
				return $this->cache->get('d_cache_build');
			}
		}

		/* Fetch build information */
		$this->db->select('build,created');
		$this->db->from('builder');
		$this->db->limit(1);
		$this->db->order_by('created', 'desc');
		$q = $this->db->get();

		$build = array();

		if (!$q->num_rows()) {
			$build['number'] = '0';
			$build['date'] = '2016-01-01';
		} else {
			$row = $q->row_array();

			$build['number'] = $row['build'];
			$build['date'] = $row['created'];
		}

		/* Initialize cache entry for build data */
		$this->config['cache_build'] = $build;

		/* Check if we're using an external cache mechanism and, if so, write data to it */
		if ($this->cache->is_active()) {
			$this->cache->set('s_cache_build', true);
			$this->cache->set('d_cache_build', $this->config['cache_build']);
		}

		return $build;
	}

	public function theme() {
		/* REST JSON requests do not require this data */
		if ($this->request->is_json())
			return NULL;

		/* If we already have a populated the theme data cache, just return it... */
		if (isset($this->config['cache_theme']))
			return $this->config['cache_theme'];

		/* Check if we're using an external cache mechanism and, if so, read data from it */
		if ($this->cache->is_active()) {
			if ($this->cache->get('s_cache_theme')) {
				return $this->cache->get('d_cache_theme');
			}
		}

		$this->db->select(
			'themes.theme AS name,'.
			'themes.animation_default_delay AS animation_default_delay,themes.animation_ordering_delay AS animation_ordering_delay,'.
			'themes_animations_default.animation AS animation_default_type,themes_animations_ordering.animation AS animation_ordering_type'
		);
		$this->db->from('themes');
		$this->db->join('themes_animations_default', 'themes_animations_default.id = themes.themes_animations_default_id', 'left');
		$this->db->join('themes_animations_ordering', 'themes_animations_ordering.id = themes.themes_animations_ordering_id', 'left');
		$this->db->where('theme', $this->config['default_theme']);
		$q = $this->db->get();

		/* Initialize cache entry for theme data */
		$this->config['cache_theme'] = $q->row_array();

		/* Check if we're using an external cache mechanism and, if so, write data to it */
		if ($this->cache->is_active()) {
			$this->cache->set('s_cache_theme', true);
			$this->cache->set('d_cache_theme', $this->config['cache_theme']);
		}

		return $this->config['cache_theme'];
	}

	public function features() {
		/* If we already have a populated the features data cache, just return it... */
		if (isset($this->config['cache_features']))
			return $this->config['cache_features'];

		/* Check if we're using an external cache mechanism and, if so, read data from it */
		if ($this->cache->is_active()) {
			if ($this->cache->get('s_cache_features')) {
				return $this->cache->get('d_cache_features');
			}
		}

		/* Fetch features and initialize cache entry for features data */
		$this->config['cache_features'] = $this->features->get_features();

		/* Check if we're using an external cache mechanism and, if so, write data to it */
		if ($this->cache->is_active()) {
			$this->cache->set('s_cache_features', true);
			$this->cache->set('d_cache_features', $this->config['cache_features']);
		}

		return $this->config['cache_features'];
	}

	public function view_breadcrumb($method, $second_level = NULL, $id = NULL, $third_level = NULL) {
		/* REST JSON requests do not require this data */
		if ($this->request->is_json())
			return NULL;

		/* NOTE: Currently, breadcrumbs won't contain more than 3 levels */

		/* Re-initialize breadcrumb */
		$this->breadcrumb->set('levels', array());
		$this->breadcrumb->set('charset', $this->config['default_charset']);
		$this->breadcrumb->set('separator', $this->config['view_breadcrumb_sep']);

		/* Add first level */
		$this->breadcrumb->add(
			isset($this->config['menu_entries_aliases'][$this->config['name']]) ? $this->config['menu_entries_aliases'][$this->config['name']] :  $this->config['viewhname'],
			isset($this->config['menu_entries_aliases'][$this->config['name']]) ? $this->config['menu_entries_aliases'][$this->config['name']] :  $this->config['viewhname'],
			base_url() . 'index.php/' . $this->config['name'],
			'ndphp.ajax.load_body_menu(event, \'' . $this->config['name'] . '\', \'' . (isset($this->config['menu_entries_aliases'][$this->config['name']]) ? $this->config['menu_entries_aliases'][$this->config['name']] :  $this->config['viewhname']) . '\');'
		);

		/* Add second level, if exists */
		if ($second_level !== NULL) {
			$this->breadcrumb->add(
				$second_level,
				$second_level,
				base_url() . 'index.php/' . $this->config['name'] . '/' . $method,
				'ndphp.ajax.load_body_op(event, \'' . filter_html_js_str($this->config['name'], $this->config['default_charset']) . '\', \'' . filter_html_js_str($method, $this->config['default_charset']) . '\');'
			);
		}

		/* If the third level is defined, it can be an ID for the second level (if the type of $id is NOT array), or it can be
		 * a customized method with respective parameters if the type of $id is of array.
		 */
		if ($id !== NULL) {
			$params = NULL;
			$url = NULL;
			$onclick = NULL;

			if (gettype($id) == 'array') {
				/* If the $id is of type array, the first array element is the method name that will override the secodn level method.
				 * All the elements after the first array element are the parameters to the supplied method.
				  */
				$method = $id[0];
				$params = implode('/', array_slice($id, 1));
				$url = base_url() . 'index.php/' . $this->config['name'] . '/' . $method . '/' . $params;
				$onclick = 'ndphp.ajax.load_body_url(event, \'' . base_url() . 'index.php/' . $this->config['name'] . '/' . $method . '_body_ajax/' . $params . '\');';
				$id = $method; /* Will be used as name and title value if $third_level is NULL */
			} else {
				/* $id is a single identifier for the second level method */
				$params = $id;
				$url = base_url() . 'index.php/' . $this->config['name'] . '/' . $method . '/' . $params;
				$onclick = 'ndphp.ajax.load_body_op_id(event, \'' . filter_html_js_str($this->config['name'], $this->config['default_charset']) . '\', \'' . filter_html_js_str($method, $this->config['default_charset']) . '\', \'' . filter_html_js_str($id, $this->config['default_charset']) . '\');';
			}

			$this->breadcrumb->add(
				($third_level !== NULL) ? $third_level : $id,
				($third_level !== NULL) ? $third_level : $id,
				$url,
				$onclick
			);
		}

		/* Create breadcrumb HTML and return the result */
		$breadcrumb['html'] = $this->breadcrumb->create();
		$breadcrumb['anchors'] = $this->breadcrumb->anchors();

		return $breadcrumb;
	}

	public function rel_table_names($rel, $target = NULL, $mixed = false) {
		if ($target === NULL)
			$target = $this->config['name'];

		/* Check if target table is present in the relationship table name */
		if (!strpos($rel, $target))
			return array();

		/* Remove the prefix and target table name from the relationship table name */
		$foreign_table_raw = str_replace($target, '', substr($rel, $mixed ? 6 : 4));

		/* After removing the $target table name from the string, if the remaining string starts with '_', then
		 * that foreign table was positioned at the end of the relationship table name.
		 */
		$rel_tables = ($foreign_table_raw[0] == '_') ? array($target, trim($foreign_table_raw, '_')) : array(trim($foreign_table_raw, '_'), $target);

		/* Check if all tables in array are valid... otherwise this isn't a related table */
		$tables = $this->tables();

		if (!in_array($rel_tables[0], $tables))
			return array();

		if (!in_array($rel_tables[1], $tables))
			return array();
		
		/* All good */
		return $rel_tables;
	}

	public function single_rel_table_name($rel) {
		return substr($rel, 0, -3);
	}

	public function multiple_rel_table_names($rel, $target = NULL) {
		return $this->rel_table_names($rel, $target, false);
	}

	public function mixed_rel_table_names($rel, $target = NULL) {
		return $this->rel_table_names($rel, $target, true);
	}

	public function relative_tables($target = NULL, $type = 'multiple') {
		/* If no target was set, assume this controller table as default */
		if (!$target)
			$target = $this->config['name'];

		$relative = array();

		if ($type == 'single') {
			/* Single relationships are based on fields with '_id' suffix */
			$fields = $this->table_fields($target);

			foreach ($fields as $field) {
				if (substr($field, -3) == '_id')
					array_push($relative, $this->single_rel_table_name($field));
			}
		} else {
			/* Setup prefix */
			if ($type == 'multiple') {
				$prefix = 'rel_';
			} else if ($type == 'mixed') {
				$prefix = 'mixed_';
			}

			/* Build a list of tables that are related to $target, based on $type prefix */
			foreach ($this->tables() as $table) {
				if (substr($table, 0, strlen($prefix)) == $prefix) {
					/* If $type is multiple, any of the slices that match $target is a relationship */
					if ($type == 'multiple') {
						$slices = $this->multiple_rel_table_names($table, $target);

						if ($slices[0] == $target || $slices[1] == $target)
							array_push($relative, $table);
					} else if ($type == 'mixed') {
						$slices = $this->mixed_rel_table_names($table, $target);

						/* If the $type is mixed, only $slice[1] matches is considered a relationship */
						if ($slices[0] == $target)
							array_push($relative, $table);
					}
				}
			}
		}

		return $relative;
	}

	public function controller_list() {
		$controllers = array();

		foreach ($this->tables() as $table) {
			/* Validate table names */
			if (!$this->security->safe_names($table, $this->config['security_safe_chars'])) {
				error_log($this->config['name'] . 'get::controller_list(): Table `' . $table . '` contains unsafe characters on its name. Skipping...');
				continue;
			}

			/* Security check */
			if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_read, $table))
				continue;

			/* 
			 * Tables prefixed by one of the following are ignored:
			 * 
			 *  +------------+---------------------+
			 *  | Prefix     | Description         |
			 *  +------------+---------------------+
			 *  | 'rel_'     | Relational tables   |
			 *  | 'mixed_'   | Mixed relationships |
			 *  +------------+---------------------+
			 * 
			 */
			if ((substr($table, 0, 4) == 'rel_') || (substr($table, 0, 6) == 'mixed_'))
				continue;

			array_push($controllers, $table);
		}

		return $controllers;
	}

	public function menu_entries() {
		/* REST JSON requests do not require this data */
		if ($this->request->is_json())
			return array();

		$entries = array();

		foreach ($this->tables() as $table) {
			/* Validate table names */
			if (!$this->security->safe_names($table, $this->config['security_safe_chars'])) {
				error_log($this->config['name'] . 'get::menu_entries(): Table `' . $table . '` contains unsafe characters on its name. Skipping...');
				continue;
			}

			/* Security check */
			if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_read, $table))
				continue;

			/* Ignore hidden menu entries */
			if (in_array($table, $this->config['hide_menu_entries']))
				continue;

			/* 
			 * Tables prefixed by one of the following are ignored:
			 * 
			 *  +------------+---------------------+
			 *  | Prefix     | Description         |
			 *  +------------+---------------------+
			 *  | 'rel_'     | Relational tables   |
			 *  | 'mixed_'   | Mixed relationships |
			 *  | '_'        | Private tables      |
			 *  +------------+---------------------+
			 * 
			 */
			if ((substr($table, 0, 4) == 'rel_') || (substr($table, 0, 6) == 'mixed_') || ($table[0] == '_'))
				continue;

			/* Get help data */
			$help_data = $this->help($table);
			$help_description = $help_data[$table]['_self'] ? $help_data[$table]['_self'] : '';

			/* Insert element into $entries, resolving the aliased name, if any. */
			/* Format of menu entry is:
			 *
			 *  +-------------+-------------------+
			 *  |  $entry[0]  |  Table name       |
			 *  |  $entry[1]  |  View alias       |
			 *  |  $entry[2]  |  Help description |
			 *  +-------------+-------------------+
			 *
			 */
			array_push($entries, array($table, isset($this->config['menu_entries_aliases'][$table]) ? $this->config['menu_entries_aliases'][$table] : $table, $help_description));
		}


		/* Re-order $entries based on $this->config['menu_entries_order'] */
		if (count($this->config['menu_entries_order'])) {
			$entries_ordered = array();

			foreach ($this->config['menu_entries_order'] as $entry_name) {
				/* Ignore hidden menu entries (this is not really required, but *may* speed up a little) */
				if (in_array($entry_name, $this->config['hide_menu_entries']))
					continue;

				/* Fetch the entry from entries pool */
				foreach ($entries as $entry) {
					if ($entry[0] == $entry_name)
						array_push($entries_ordered, $entry);
				}
			}

			$entries = $entries_ordered;
		}

		return $entries;
	}

	public function field_help_desc($table, $field) {
		$help_data = $this->help($table);

		if ($help_data === NULL || !isset($help_data[$field]))
			return NULL;

		return $help_data[$field];
	}

	public function fields_basic_types($target = NULL, $hide_filter = array()) {
		$fields = NULL;

		$fields_raw = $this->table_desc($target != NULL ? $target : $this->config['name']);

		if (!$fields_raw)
			return NULL;

		foreach ($fields_raw as $field) {
			/* Filter hidden fields */
			if (in_array($field['name'], $hide_filter))
				continue;

			/* NOTE: Security check: If we cannot read the field, then it won't be shown in any request regardless of its nature */
			if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_read, $target !== NULL ? $target : $this->config['name'], $field['name']))
				continue;

			$fields[$field['name']]['type'] = $field['type'];
			$fields[$field['name']]['max_length'] = $field['max_length'];
			$fields[$field['name']]['primary_key'] = $field['primary_key'];
			$fields[$field['name']]['table'] = $this->config['name'];
			$fields[$field['name']]['altname'] = $field['name'];
		}

		return $fields;
	}

	public function fields($target = NULL, $hide_filter = array(), $skip_perm_check = false) {
		/* Load required modules for this method */
		$this->load->module('filter');
		$this->load->module('field');

		$fields = NULL;

		if ($target === NULL)
			$target = $this->config['name'];

		$fields_raw = $this->table_desc($target);

		if (!$fields_raw)
			return NULL;

		/* Convert database field types into html input types:
		 * 
		 * +--------------------------+---------------------+
		 * | DBMS Types               | HTML Input Types    |
		 * +--------------------------+---------------------+
		 * | varchar                  | text                |
		 * | text                     | textarea            |
		 * | int, bigint, timestamp   | number (HTML5 only) |
		 * | tinyint, bool            | checkbox            |
		 * | int (*_id) -> single rel | select              |
		 * | time (_timer_*)          | timer (Not HTML)    |
		 * | varchar (_file_*)        | file                |
		 * | varchar (_separator_*)   | tab
		 * | (default / others)       | text                |
		 * +--------------------------+---------------------|
		 * 
		 */ 
		foreach ($fields_raw as $field) {
			/* Filter hidden fields */
			if (in_array($field['name'], $hide_filter))
				continue;

			/* NOTE: Security check: If we cannot read the field, then it won't be shown in any request regardless of its nature */
			if (!$skip_perm_check && !$this->security->perm_check($this->config['security_perms'], $this->security->perm_read, $target, $field['name']))
				continue;

			/* Populate field properties */
			$fields[$field['name']]['type'] = $field['type'];
			$fields[$field['name']]['max_length'] = $field['max_length'];
			$fields[$field['name']]['primary_key'] = $field['primary_key'];
			$fields[$field['name']]['table'] = $target; 
			$fields[$field['name']]['rel_table'] = NULL;
			$fields[$field['name']]['options'] = array();
			$fields[$field['name']]['altname'] = $field['name'];
			$fields[$field['name']]['viewname'] = isset($this->config['table_field_aliases'][$field['name']]) ? $this->config['table_field_aliases'][$field['name']] : $field['name'];
			$fields[$field['name']]['input_name'] = NULL; 

			/* Get field help, if exists */
			$help_data = $this->field_help_desc($target, $field['name']);
			$fields[$field['name']]['placeholder'] = $help_data['placeholder'];
			$fields[$field['name']]['units'] = array();
			$fields[$field['name']]['units']['unit'] = $help_data['field_units'];
			$fields[$field['name']]['units']['left'] = $help_data['units_on_left'];
			$fields[$field['name']]['input_pattern'] = $help_data['input_pattern'];
			$fields[$field['name']]['help_desc'] = $help_data['help_description'];
			$fields[$field['name']]['help_url'] = $help_data['help_url'];

			/* Process field type */
			if (substr($field['name'], 0, 11) == '_separator_') {
				$fields[$field['name']]['type'] = 'separator';
				$fields[$field['name']]['input_type'] = 'separator';
				$fields[$field['name']]['altname'] = substr($field['name'], 11);

				if (substr($fields[$field['name']]['viewname'], 0, 11) == '_separator_')
					$fields[$field['name']]['viewname'] = ucfirst($fields[$field['name']]['altname']);
			} else if (substr($field['name'], 0, 7) == '_timer_') {
				$fields[$field['name']]['input_type'] = 'timer';
				$fields[$field['name']]['altname'] = substr($field['name'], 7);

				if (substr($fields[$field['name']]['viewname'], 0, 7) == '_timer_')
					$fields[$field['name']]['viewname'] = ucfirst($fields[$field['name']]['altname']);
			} else if (substr($field['name'], 0, 6) == '_file_') {
				$fields[$field['name']]['input_type'] = 'file';
				$fields[$field['name']]['altname'] = substr($field['name'], 6);

				if (substr($fields[$field['name']]['viewname'], 0, 6) == '_file_')
					$fields[$field['name']]['viewname'] = ucfirst($fields[$field['name']]['altname']);
			} else if (substr($field['name'], -3, 3) == '_id') {
				/* Relational (single) */
				$table = substr($field['name'], 0, -3);
				$fields[$field['name']]['table'] = $table;
				$table_fields = $this->table_fields($table);

				/* Check how many fields are required to be concatenated to craft the options
				 * values.
				 */
				if (isset($this->config['rel_table_fields_config'][$table])) {
					/* Setup the amount of concatenated fields required for the options */
					$rel_fields = '';
					foreach ($this->config['rel_table_fields_config'][$table][2] as $rel_field)
						$rel_fields .= $table_fields[$rel_field] . ',';
					$rel_fields = rtrim($rel_fields, ',');
				} else {
					/* If no concatenated fields were configured, use the default value.
					 * (The default value is always the second field from the rel table)
					 */
					$rel_fields = $table_fields[1];
				}

				/* Only fetch foreign table options if this isn't a REST JSON request */				
				if (!$this->request->is_json()) {
					/* TODO: FIXME: Cache the field options. Note that this cache should be invalidated when the corresponding
					* foreign table data is modified.
					*/

					/* Fetch foreign table entries */
					$this->db->select('id,' . $rel_fields);
					$this->db->from($table);

					/* Order and limit results */
					if (isset($this->config['rel_table_fields_config'][$table])) {
						if ($this->config['rel_table_fields_config'][$table][3] !== NULL)
							$this->db->order_by($this->config['rel_table_fields_config'][$table][3][0], $this->config['rel_table_fields_config'][$table][3][1]);

						if (isset($this->config['rel_table_fields_config'][$table][4]) && $this->config['rel_table_fields_config'][$table][4] !== NULL)
							$this->db->limit($this->config['rel_table_fields_config'][$table][4]);
					}

					/* Apply any applicable filters */
					$this->filter->table_row_apply($table);

					/* We need to use the value_mangle() method here to grant that options values are mangled
					*
					* We also use the fields_basic_types() method to reduce the overhead that would otherwise be caused if
					* recursion to the fields() method was applied here.
					*/
					$result_array = $this->field->value_mangle($this->fields_basic_types($table), $this->db->get());

					/* Craft options values based on concatenations' configuration (if any).
					* If no configuration is set for this particular relationship, the default
					* option value is used (this is, the values of the second field of the
					* relationship table).
					*/
					foreach ($result_array as $row) {
						if (isset($this->config['rel_table_fields_config'][$table]) && ($this->config['rel_table_fields_config'][$table][2] != NULL)) {
							/* Setup the amount of concatenated fields required for the options */
							$fields[$field['name']]['options'][$row['id']] = '';
							foreach ($this->config['rel_table_fields_config'][$table][2] as $rel_field)
								$fields[$field['name']]['options'][$row['id']] .= $row[$table_fields[$rel_field]] . (($this->config['rel_table_fields_config'][$table][1] != NULL) ? $this->config['rel_table_fields_config'][$table][1] : ' ');
								/* ^^^ -> Field Options Array                     ^^^ -> Resolved Option Field      ^^^ -> Separator   */

							/* Remove trailing separator */
							$fields[$field['name']]['options'][$row['id']] = trim($fields[$field['name']]['options'][$row['id']], (($this->config['rel_table_fields_config'][$table][1] != NULL) ? $this->config['rel_table_fields_config'][$table][1] : ' '));
						} else {
							/* If no concatenated fields were configured, use the default value.
							* (The default value is always the second field from the rel table)
							*/
							$fields[$field['name']]['options'][$row['id']] = $row[$table_fields[1]];
						}
					}
				}

				/* Set the altname and viewname */
				if (isset($this->config['rel_table_fields_config'][$table]) && ($this->config['rel_table_fields_config'][$table][2] != NULL)) {
					$fields[$field['name']]['altname'] = $table_fields[$this->config['rel_table_fields_config'][$table][2][0]];
					$fields[$field['name']]['viewname'] = $this->config['rel_table_fields_config'][$table][0];
				} else {
					$fields[$field['name']]['altname'] = $table_fields[1];
					$fields[$field['name']]['viewname'] = $table_fields[1];
				}

				/* Get field help, if exists */
				$help_data = $this->field_help_desc($target, $field['name']);
				$fields[$field['name']]['units'] = $help_data['field_units'];
				$fields[$field['name']]['help_desc'] = $help_data['help_description'];
				$fields[$field['name']]['help_url'] = $help_data['help_url'];

				/* Set field input type */
				$fields[$field['name']]['input_type'] = 'select';
			} else if ($field['type'] == 'varchar') {
				$fields[$field['name']]['input_type'] = 'text';
			} else if ($field['type'] == 'text') {
				$fields[$field['name']]['input_type'] = 'textarea';
			} else if (($field['type'] == 'tinyint') || ($field['type'] == 'bool') || ($field['max_length'] == 1)) { /* NOTE: boolean length check (1) must come before int and bigint type checks */
				$fields[$field['name']]['input_type'] = 'checkbox';
			} else if (($field['type'] == 'int') || ($field['type'] == 'bigint') || ($field['type'] == 'timestamp')) {
				$fields[$field['name']]['input_type'] = 'number';
			} else {
				/* By default, we assume all unknown types as 'text' */
				$fields[$field['name']]['input_type'] = 'text';
			}
		}
		
		/* Check for multiple relationships */
		foreach ($this->tables() as $table) {
			/* Ignore all non-relationship tables */
			if ((substr($table, 0, 4) != 'rel_'))
				continue;

			$rel_tables = array_diff($this->multiple_rel_table_names($table, $target), array($target));

			foreach ($rel_tables as $rel) {
				/* Security check */
				if (!$skip_perm_check && !$this->security->perm_check($this->config['security_perms'], $this->security->perm_read, $rel))
					continue;

				if (!$skip_perm_check && !$this->security->perm_check($this->config['security_perms'], $this->security->perm_read, $target, $table /* NOTE: $table is the field name: rel_<t1>_<ft2> */))
					continue;

				$table_fields = $this->table_fields($rel);
				$rel_field = isset($this->config['rel_table_fields_config'][$rel][2][0]) ? $this->config['rel_table_fields_config'][$rel][2][0] : 1;

				/* Filter hidden fields */
				if (in_array($table, $hide_filter))
					continue;

				/* Set the field name properties (field name is the rel_*_* table name) */
				$fields[$table]['type'] = 'rel';
				$fields[$table]['max_length'] = NULL;
				$fields[$table]['primary_key'] = NULL;
				$fields[$table]['table'] = $rel;
				$fields[$table]['options'] = array();
				$fields[$table]['input_type'] = 'select';
				$fields[$table]['base_table'] = $target;
				$fields[$table]['rel_table'] = $table;
				$fields[$table]['rel_field'] = $table_fields[$rel_field];

				/* Check how many fields are required to be concatenated to craft the options
				 * values.
				 */
				if (isset($this->config['rel_table_fields_config'][$rel]) && ($this->config['rel_table_fields_config'][$rel][2] != NULL)) {
					/* Setup the amount of concatenated fields required for the options */
					$rel_fields = '';
					foreach ($this->config['rel_table_fields_config'][$rel][2] as $rel_field)
						$rel_fields .= $table_fields[$rel_field] . ',';
					$rel_fields = rtrim($rel_fields, ',');
				} else {
					/* If no concatenated fields were configured, use the default value.
					 * (The default value is always the second field from the rel table)
					 */
					$rel_fields = $table_fields[1];
				}

				/* Only fetch foreign field options if this isn't a REST JSON request */
				if (!$this->request->is_json()) {
					/* TODO: FIXME: Cache the field options. Note that this cache should be invalidated when the corresponding
					* foreign table data is modified.
					*/

					/* Get foreign table contents */
					$this->db->select('id,' . $rel_fields);
					$this->db->from($rel);

					/* Order and limit results */
					if (isset($this->config['rel_table_fields_config'][$rel])) {
						if ($this->config['rel_table_fields_config'][$rel][3] !== NULL)
							$this->db->order_by($this->config['rel_table_fields_config'][$rel][3][0], $this->config['rel_table_fields_config'][$rel][3][1]);

						if (isset($this->config['rel_table_fields_config'][$rel][4]) && $this->config['rel_table_fields_config'][$rel][4] !== NULL)
							$this->db->limit($this->config['rel_table_fields_config'][$rel][4]);
					}

					/* Apply any applicable filters */
					$this->filter->table_row_apply($rel);

					/* We need to use the value_mangle() method here to grant that options values are mangled
					*
					* We also use the fields_basic_types() method to reduce the overhead that would otherwise be caused if
					* recursion to the fields() method was applied here.
					*/
					$result_array = $this->field->value_mangle($this->fields_basic_types($rel), $this->db->get());

					/* Craft options values based on concatenations' configuration (if any).
					* If no configuration is set for this particular relationship, the default
					* option value is used (this is, the values of the second field of the
					* relationship table).
					*/
					foreach ($result_array as $row) {
						if (isset($this->config['rel_table_fields_config'][$rel]) && ($this->config['rel_table_fields_config'][$rel][2] != NULL)) {
							/* Setup the amount of concatenated fields required for the options */
							$fields[$table]['options'][$row['id']] = '';
							foreach ($this->config['rel_table_fields_config'][$rel][2] as $rel_field)
								$fields[$table]['options'][$row['id']] .= $row[$table_fields[$rel_field]] . (($this->config['rel_table_fields_config'][$rel][1] != NULL) ? $this->config['rel_table_fields_config'][$rel][1] : ' ');
								/* ^^^ -> Field Options Array                                                                   ^^^ -> Resolved Option Field      ^^^ -> Separator   */

							/* Remove trailing separator */
							$fields[$table]['options'][$row['id']] = trim($fields[$table]['options'][$row['id']], (($this->config['rel_table_fields_config'][$rel][1] != NULL) ? $this->config['rel_table_fields_config'][$rel][1] : ' '));
						} else {
							/* If no concatenated fields were configured, use the default value.
							* (The default value is always the second field from the rel table)
							*/
							$fields[$table]['options'][$row['id']] = $row[$table_fields[1]];
						}
					}
				}

				/* Set the altname */
				if (isset($this->config['rel_table_fields_config'][$rel])) {
					$fields[$table]['altname'] = $table_fields[$this->config['rel_table_fields_config'][$rel][2][0]];
					$fields[$table]['viewname'] = $this->config['rel_table_fields_config'][$rel][0];
				} else {
					$fields[$table]['altname'] = $table_fields[1];
					$fields[$table]['viewname'] = $table_fields[1];
				}

				/* Get field help, if exists */
				$help_data = $this->field_help_desc($target, $table);
				$fields[$table]['units'] = $help_data['field_units'];
				$fields[$table]['help_desc'] = $help_data['help_description'];
				$fields[$table]['help_url'] = $help_data['help_url'];
			}
		}

		/* Check for mixed relationships (table prefix mixed_*) */
		foreach ($this->tables() as $table) {
			/* Ignore all non-relationship tables */
			if (substr($table, 0, 6) != 'mixed_')
				continue;

			$rel_tables = $this->mixed_rel_table_names($table, $target);
			
			/* Check mixed relationship precedence */
			if ($rel_tables[0] != $target) {
				/* There's a mixed relatinship for this table, but not in this order */
				continue;
			}
			
			/* Ignore relationships not belonging to the current table */
			if (!(in_array($target, $rel_tables)))
				continue;
			
			/* Remove the current table from the relationship array */
			$rel_tables = array_diff($rel_tables, array($target));
			
			/* Only one table is expected to be present in the array since mixed relationships
			 * do not support more than one relationship
			 */
			$rel = array_pop($rel_tables);

			/* Security check */
			if (!$skip_perm_check && !$this->security->perm_check($this->config['security_perms'], $this->security->perm_read, $rel))
				continue;

			if (!$skip_perm_check && !$this->security->perm_check($this->config['security_perms'], $this->security->perm_read, $target, $table /* NOTE: $table is the field name: mixed_<t1>_<ft2> */))
				continue;

			/* Filter hidden fields */
			if (in_array($table, $hide_filter))
				continue;

			$table_fields_all = $this->table_fields($table);
			$table_fields = array_merge(array('id'), $this->mixed_table_fields($rel, $target));
			$rel_field = isset($this->config['rel_table_fields_config'][$rel][2][0]) ? $this->config['rel_table_fields_config'][$rel][2][0] : 1;

			/* Check if this is a single mixed relationship */
			$schema = $this->load->database($this->config['default_database'] . '_schema', true);
			$schema->select('column_key')->from('columns')->where('table_schema', $this->db->database)->where('table_name', $table)->where('column_name', $table_fields_all[2]);
			$query = $schema->get();
			$query_row = $query->row_array();

			if ($query_row['column_key'] == 'UNI') {
				$fields[$table]['mixed_type'] = 'single';
			} else {
				$fields[$table]['mixed_type'] = 'multi';
			}
			$this->load->database($this->config['default_database']);

			/* NOTE: $fields entry name for mixed relationships is the relational table name ($rel) */
			$fields[$table]['type'] = 'mixed';
			$fields[$table]['input_type'] = 'mixed';
			$fields[$table]['max_length'] = NULL;
			$fields[$table]['primary_key'] = NULL;
			$fields[$table]['table'] = $table;
			$fields[$table]['options'] = array();
			$fields[$table]['base_table'] = $target;
			$fields[$table]['rel_table'] = $rel;
			$fields[$table]['mixed_fields'] = $table_fields;
			$fields[$table]['mixed_first_field'] = $table_fields[1];
			
			/* Get hidden mixed fields. FIXME: TODO: This will filter fields for all views, so currently we don't support
			 * customized hidden fields per view (create/edit/view/remove)
			 */
			$mixed_hide_fields = $this->access->controller($rel)->config['mixed_hide_fields_view'];

			/* Resolve foreign table field names aliases */
			$table_fields_aliases = array();
			
			foreach ($table_fields as $tfid => $tfname) {
				$tfname = $table_fields[$tfid];

				/* Ignore hidden fields */
				if (in_array($tfname, $mixed_hide_fields))
					continue;

				$help_data = $this->field_help_desc($table, $tfname);
				$rel_fields_help[$rtfname]['units'] = $help_data['field_units'];
				$rel_fields_help[$rtfname]['help_desc'] = $help_data['help_description'];
				$rel_fields_help[$rtfname]['help_url'] = $help_data['help_url'];

				/* Remove any special prefixes from $tfname, such as _file and _timer */
				$tfdata['alias'] = $tfname;

				/* Remove any special prefixes from $tfdata['alias'], such as _file and _timer */
				if (substr($tfdata['alias'], 0, 6) =='_file_') {
					$tfdata['alias'] = substr($tfdata['alias'], 6);
				} else if (substr($tfdata['alias'], 0, 7) =='_timer_') {
					$tfdata['alias'] = substr($tfdata['alias'], 7);
				}

				/* Set help description and URL for this table field */
				$tfdata['help_desc'] = $help_data['help_description'];
				$tfdata['help_url'] = $help_data['help_url'];

				foreach ($this->config['mixed_table_fields_config'] as $rtname => $rtvalue) {
					if ($rtname != $rel)
						continue;

					/* Set mixed field aliases */
					if (isset($this->config['mixed_table_fields_config'][$rtname][$tfid])) {
						$tfdata['alias'] = $this->config['mixed_table_fields_config'][$rtname][$tfid];
					}

					break;
				}

				array_push($table_fields_aliases, $tfdata);
			}

			$fields[$table]['mixed_fields_alias'] = $table_fields_aliases;

			/* Check how many fields are required to be concatenated to craft the options
			 * values.
			 */
			$rel_fields = $table_fields[1];

			/* Only fetch foreign field options if this isn't a REST JSON request */
			if (!$this->request->is_json()) {
				$this->db->select('id,' . $rel_fields);
				$this->db->from($rel);

				/* Filter the rows based on access configuration parameters */
				$this->filter->table_row_apply($rel);

				/* We need to use _field_value_mangle() here to grant that relationship values are mangled
				*
				* We also use the _get_fields_basic_types() to avoid the overhead that would be caused if
				* recursion of _get_fields() was used here.
				*/
				$result_array = $this->field->value_mangle($this->fields_basic_types($rel), $this->db->get());

				/* Set the altname and viewname */
				$fields[$table]['altname'] = $rel;
				$fields[$table]['viewname'] = isset($this->config['mixed_fieldset_legend_config'][$rel]) ? $this->config['mixed_fieldset_legend_config'][$rel] : $rel;
				
				/* Craft options values */
				foreach ($result_array as $row) {
					$fields[$table]['options'][$row['id']] = $row[$table_fields[1]];
				}
			}
		}
		
		return $fields;
	}

	public function mixed_table_fields($mixed_table, $origin) {
		$result_mixed_fields = array();

		foreach ($this->tables() as $table) {
			if (substr($table, 0, 6) != 'mixed_')
				continue;

			$slices = $this->mixed_rel_table_names($table, $origin);

			if (($slices[0] != $origin) || ($slices[1] != $mixed_table))
				continue;

			$mixed_table_fields_raw = $this->table_fields($table);
			$mixed_table_fields = array();

			/* Remove private fields, starting by '__' */
			foreach ($mixed_table_fields_raw as $field) {
				if (substr($field, 0, 2) == '__')
					continue;

				array_push($mixed_table_fields, $field);
			}

			array_push($result_mixed_fields, $mixed_table_fields[1]);

			$result_mixed_fields = array_merge($result_mixed_fields, array_slice($mixed_table_fields, 4));

			break;
		}

		return $result_mixed_fields;
	}

	public function saved_searches() {
		/* Get only the searches matching the current user_id */
		$this->db->select('id,search_name,description,result_query');
		$this->db->from('_saved_searches');
		$this->db->where('controller', $this->config['name']);
		$this->db->where('users_id', $this->config['session_data']['user_id']);
		$q = $this->db->get();

		$saved_searches = array();

		if (!$q->num_rows())
			return $saved_searches;
		
		foreach ($q->result_array() as $row)
			array_push($saved_searches, $row);

		return $saved_searches;
	}


	/** View Data **/

	public function views_base_dir($theme) {
		/* Any attempt to manipulate the views path to access parent directories should be blocked */
		if (strpos($theme, '..')) {
			/* We need to fail hard here... */
			$this->response->code('500', NDPHP_LANG_MOD_INVALID_VIEW_BASE_DIR_CHARS, $this->config['default_charset'], !$this->request->is_ajax());
		}

		/* Craft the views base directory path */
		$views_base_dir = SYSTEM_BASE_DIR . '/application/views/themes/' . $theme;

		/* Grant that the path is valid */
		if (file_exists($views_base_dir))
			return $views_base_dir;

		/* We need to fail hard here... */
		$this->response->code('500', NDPHP_LANG_MOD_UNABLE_VIEW_BASE_DIR, $this->config['default_charset'], !$this->request->is_ajax());
	}

	public function view_data_generic($title = 'NO_TITLE', $description = "NO_DESCRIPTION") {
		/* REST JSON requests do not require this data */
		if ($this->request->is_json())
			return array();

		$data = array();

		/* Configuration data - Used for configuration and control. It should not be used as 'printable' data */
		$data['config'] = array();
		$data['config']['theme'] = $this->theme();
		$data['config']['charset'] = $this->config['default_charset'];
		$data['config']['features'] = $this->features();
		$data['config']['fk_linking'] = $this->config['table_fk_linking'];
		$data['config']['truncate'] = array();
		$data['config']['truncate']['length'] = $this->config['string_truncate_len'];
		$data['config']['truncate']['trail'] = $this->config['string_truncate_trail'];
		$data['config']['truncate']['separator'] = $this->config['string_truncate_sep'];
		$data['config']['rich_text'] = $this->config['table_field_text_rich'];
		$data['config']['charts'] = array();
		$data['config']['charts']['enable_list'] = $this->config['charts_enable_list'];
		$data['config']['charts']['enable_result'] = $this->config['charts_enable_result'];
		$data['config']['charts']['enable_view'] = $this->config['charts_enable_result'];

		/* View data - Data that is intended to be 'printed' on the view */
		$data['view'] = array();
		$data['view']['ctrl'] = $this->config['name'];
		$data['view']['title'] = $title;
		$data['view']['description'] = $description;
		$data['view']['hname'] = isset($this->config['menu_entries_aliases'][$this->config['name']]) ? $this->config['menu_entries_aliases'][$this->config['name']] : $this->config['viewhname'];
		$data['view']['mainmenu'] = $this->menu_entries();
		$data['view']['crud_main_tab_name'] = $this->config['view_crud_main_tab_name'];
		$data['view']['crud_charts_tab_name'] = $this->config['view_crud_charts_tab_name'];
		$data['view']['base_dir'] = $this->views_base_dir($data['config']['theme']['name']);
		$data['view']['unique_id'] = mt_rand(10000, 99999); /* TODO: FIXME: Something better than a random value shall be used here... */

		/* Project data - Aditional project information. May be used as 'printable' data */
		$data['project'] = array();
		$data['project']['author'] = $this->config['project_author'];
		$data['project']['name'] = $this->config['project_name'];
		$data['project']['tagline'] = $this->config['project_tagline'];
		$data['project']['description'] = $this->config['project_description'];
		$data['project']['build'] = $this->build();
		$data['project']['ndphp_version'] = $this->config['ndphp_version'];
		$data['project']['support_email'] = $this->config['support_email'];

		/* Session Data - Control data. May be used for 'printing' and for control. */
		$data['session'] = $this->config['session_data'];

		/* Security Data - Security assessment and control. Not supposed to be used as 'printable' data. */
		$data['security'] = array();
		$data['security']['perms'] = $this->config['security_perms'];
		$data['security']['im_admin'] = $this->security->im_admin();

		return $data;
	}


	/** Interval processing API **/

	public function interval_fields($input_raw_string) {
		/* Compute the SQL interval string based on the supplied interval */
		$interval_fields = array();

		foreach (explode(' ', $input_raw_string) as $field) {
			if ($field === NULL || $field == '')
				continue;

			array_push($interval_fields, $field);
		}

		/* If there are only two fields, assume an integer as the first and a string as the second.
		 * Then extract the sign and absolute integer value and create a new array.
		 */
		if (count($interval_fields) == 2 && intval($interval_fields[0]))
			$interval_fields = array((intval($interval_fields[0]) < 0) ? '-' : '+', abs(intval($interval_fields[0])), $interval_fields[1]);

		/* Check if the number of fields is correct */
		if (count($interval_fields) != 3)
			return false;

		/* Grant that we've an acceptable sign (or equivalent word which requires translation) */
		switch (iconv($this->config['default_charset'], 'ASCII//TRANSLIT', $interval_fields[0])) { /* iconv() is used to get rid of accents */
			case NDPHP_LANG_MOD_SEARCH_INTERVAL_PREVIOUS:
			case NDPHP_LANG_MOD_SEARCH_INTERVAL_LAST:
			case '-': $interval_fields[0] = '-'; break;
			case NDPHP_LANG_MOD_SEARCH_INTERVAL_NEXT:
			case NDPHP_LANG_MOD_SEARCH_INTERVAL_IN:
			case '+': $interval_fields[0] = '+'; break;
			default: return false;
		}

		/* Fetch the integer value form second field */
		$interval_fields[1] = intval($interval_fields[1]);

		/* Grant that the integer value is really an integer or a string parsable to integer */
		if (!$interval_fields[1])
			return false;

		/* Grant that the third parameter is a possible value */
		switch (strtolower($interval_fields[2])) {
			case NDPHP_LANG_MOD_SEARCH_INTERVAL_SECONDS:
			case NDPHP_LANG_MOD_SEARCH_INTERVAL_SECOND:	$interval_fields[2] = 'SECOND';	break;
			case NDPHP_LANG_MOD_SEARCH_INTERVAL_MINUTES:
			case NDPHP_LANG_MOD_SEARCH_INTERVAL_MINUTE:	$interval_fields[2] = 'MINUTE';	break;
			case NDPHP_LANG_MOD_SEARCH_INTERVAL_HOURS:
			case NDPHP_LANG_MOD_SEARCH_INTERVAL_HOUR:	$interval_fields[2] = 'HOUR';	break;
			case NDPHP_LANG_MOD_SEARCH_INTERVAL_DAYS:
			case NDPHP_LANG_MOD_SEARCH_INTERVAL_DAY:	$interval_fields[2] = 'DAY';	break;
			case NDPHP_LANG_MOD_SEARCH_INTERVAL_WEEKS:
			case NDPHP_LANG_MOD_SEARCH_INTERVAL_WEEK:	$interval_fields[2] = 'WEEK';	break;
			case NDPHP_LANG_MOD_SEARCH_INTERVAL_MONTHS:
			case NDPHP_LANG_MOD_SEARCH_INTERVAL_MONTH:	$interval_fields[2] = 'MONTH';	break;
			case NDPHP_LANG_MOD_SEARCH_INTERVAL_YEARS:
			case NDPHP_LANG_MOD_SEARCH_INTERVAL_YEAR:	$interval_fields[2] = 'YEAR';	break;
			default: {
				return false;
			} break;
		}

		/* All good */
		return $interval_fields; /* Interval fields format: [0] - positive (+) or negative (-), [1] - Integer value, [2] - SECONDS/MINUTE/HOUR/... */
	}


	/** Value fetchers **/

	public function value_from_post($id, $field, $POST = NULL) {
		if ($POST === NULL)
			$POST = $this->request->post();

		return $POST[$field];
	}

	public function value_from_database($id, $field, $table = NULL) {
		if ($table === NULL)
			$table = $this->config['name'];

		if (substr($field, 0, 6) == 'mixed_') {
			/* Parse the mixed field */
			$mixed_field = $this->mixed_crud_field($field);

			/* Fetch the data from the database for this particular mixed field */
			$this->db->select($mixed_field[1]);
			$this->db->from('mixed_' . $this->config['name'] . '_' . $mixed_field[0]);
			$this->db->where($table . '_id', $id);
			$this->db->limit(1, $mixed_field[2] - 1); /* Fetch only the nth entry */
			$q_mixed = $this->db->get();

			/* If the nth entry does not exist, return boolean false (never return NULL, as NULL is a possible field value) */
			if (!$q_mixed->num_rows())
				return false;

			/* Fetch the row */
			$row_mixed = $q_mixed->row_array();

			/* Return the field value */
			return $row_mixed[$mixed_field[1]];
		} else if (substr($field, 0, 4) == 'rel_') {
			/* Determine te foreign table name and fetch data from the relational table for this $id */
			$foreign_table = array_pop(array_diff($this->multiple_rel_table_names($field, $table), array($table)));

			/* Fetch data from the database for this multiple relationship */
			$this->db->select($foreign_table . '_id');
			$this->db->from($field);
			$this->db->where($table . '_id', $id);
			$q_rel = $this->db->get();

			/* If there are no relationships, return an empty array */
			if (!$q_rel->num_rows())
				return array();

			/* Create a result array for the stored values of the field */
			$rel_db_values = array();
			foreach ($q_rel->result_array() as $row_rel) {
				array_push($rel_db_values, $row_rel[$foreign_table . '_id']);
			}

			/* Return all the relationship values as array() */
			return $rel_db_values;
		} else {
			/* Fetch the regular field value */
			$this->db->select($field);
			$this->db->from($table);
			$this->db->where('id', $id);
			$q = $this->db->get();

			/* If no entries were found for this ID, return boolean false (never return NULL, as NULL is a possible field value) */
			if (!$q->num_rows())
				return false;

			$row = $q->row_array();

			return $row[$field];
		}

		/* Unreachable... we hope */
		return false;
	}


	/** User input (POST) **/

	public function post_changed_fields_data($table, $id, $POST) {
		/* Returns a list of fields, including the changed data, whose values differ,
		 * from the $POST data to the database (stored) data
		 */

		$mixed_data = array(); /* If there are mixed relationships present in the POST data, we'll store the data in this array for later processing */

		/* Fetch the stored data */
		$this->db->from($table);
		$this->db->where('id', $id);
		$q = $this->db->get();

		/* Check if there are any results */
		if (!$q->num_rows())
			return array();

		$row = $q->row_array();

		$changed_data = array();

		/* Compare the stored data with the $POST data */
		foreach ($POST as $key => $value) {
			if (substr($key, 0, 6) == 'mixed_') {
				/* Check if the mixed relationship field value is about to be changed */

				/* Parse mixed field */
				$mixed_field = $this->mixed_crud_field($key);

				/* Keep track of existing db and post entries */
				if (!isset($mixed_data[$mixed_field[0]])) {
					/* Initialize the mixed data entry for this mixed table */
					$mixed_data[$mixed_field[0]] = array();
					$mixed_data[$mixed_field[0]]['fields'] = array(); /* This array will contain the field _set_ for the mixed relationship */
					$mixed_data[$mixed_field[0]]['entries_post'] = array(); /* This array will contain the mixed_id _set_ present on the post data for this mixed relationship */
					$mixed_data[$mixed_field[0]]['total_post_entries'] = 0; /* Will be incremented for each mixed entry (not mixed field) */

					/* Fetch the total number of rows currently stored in the database belonging to this $id and the current mixed relationship */
					$this->db->from('mixed_' . $this->config['name'] . '_' . $mixed_field[0]);
					$this->db->where($this->config['name'] . '_id', $id);
					$q_mixed_entries_db = $this->db->get();

					/* Set the total number of found rows in the database for this mixed relationship (assigned to $id) */
					$mixed_data[$mixed_field[0]]['total_db_entries'] = $q_mixed_entries_db->num_rows();
				}

				/* Populate one more field to $mixed_data[$mixed_field[0]]['fields'] array, if it isn't already present */
				if (!in_array($mixed_field[1], $mixed_data[$mixed_field[0]]['fields']))
					array_push($mixed_data[$mixed_field[0]]['fields'], $mixed_field[1]);

				/* Populate one more field to $mixed_data[$mixed_field[0]]['entries_post'] array, if it isn't already present */
				if (!in_array($mixed_field[2], $mixed_data[$mixed_field[0]]['entries_post'])) {
					$mixed_data[$mixed_field[0]]['total_post_entries'] ++;
					array_push($mixed_data[$mixed_field[0]]['entries_post'], $mixed_field[2]);
				}

				/* Craft mixed crud field name.
				 * POST mixed fields may assume any mixed_id and may not be sorted (and there may even be gaps between entries).
				 * So we need to craft a ordered entry list in order to keep track of the changes in a sorted fashion.
				 */
				$mixed_crud_field = 'mixed_' . $mixed_field[0] . '_' . $mixed_field[1] . '_' . $mixed_data[$mixed_field[0]]['total_post_entries'];

				/* Now fetch the data from the database for this particular mixed field */
				$mixed_db_value = $this->value_from_database($id, $mixed_crud_field);

				/* Check if there isn't an existing entry on the database for this mixed field... */
				if ($mixed_db_value === false) {
					array_push($changed_data, array(
						'field' => $mixed_crud_field,
						'value_old' => '',
						'value_new' => $value
					)); /* If not, then something is about to be changed (a new row will be added) */
				} else if ($mixed_db_value != $value) {
					array_push($changed_data, array(
						'field' => $mixed_crud_field,
						'value_old' => $mixed_db_value,
						'value_new' => $value
					));
				}

				/* We still need to check for removed mixed entries, but since the changes were already identified, the
				 * removed entries are the leftovers not present in the POST data. This will be processed ouside of this loop,
				 * at the end of this method.
				 */
			} else if (substr($key, 0, 4) == 'rel_') {
				/* If the last element of '$value' is zero (a control value), pop it out */
				if (!end($value))
					array_pop($value);

				$rel_db_values = $this->value_from_database($id, $key);

				/* Check if the number of entries in the database match the number of entries on the field's POST data */
				if (count($rel_db_values) != count($value)) {
					array_push($changed_data, array(
						'field' => $key,
						'value_old' => implode(',', $rel_db_values),
						'value_new' => implode(',', $value)
					));

					continue; /* If the number of entries do not match, then something was changed */
				}

				/* Check if all the entries on the database are present on this field's POST data */
				foreach ($rel_db_values as $rel_db_value) {
					if (!in_array($rel_db_value, $value)) {
						array_push($changed_data, array(
							'field' => $key,
							'value_old' => implode(',', $rel_db_values),
							'value_new' => implode(',', $value)
						));
						break; /* If at least one element is not present, then we can safely assume that this field was changed */
					}
				}
			} else if (!isset($row[$key])) { /* We must need to check $row keys after 'mixed_' and 'rel_' fields as they do not exist in the database */
				/* If there's a POST key that is not present in the database table, just ignore it */
				continue;
			} else {
				/* Check if there was a change for this field... */
				if ($row[$key] != $value) {
					/* ... and if so, add it to the result. TODO: FIXME: mixed and multiple relationships not yet supported  */
					array_push($changed_data, array(
						'field' => $key,
						'value_old' => $row[$key],
						'value_new' => $value
					));
				}
			}
		}

		/* Post-process mixed relationships. We'll need to add to $changed_data array any mixed entries that might have been deleted
		 * and were not caught by the previous routines (because they're based on POST data, not database data... so if there are more
		 * database entries than POST data entries for a particular mixed relationship, the exceeding database entries would
		 * be ignored if the following routine wasn't performed).
		 */
		foreach ($mixed_data as $mixed_table => $mixed_meta) {
			if ($mixed_meta['total_db_entries'] > count($mixed_meta['entries_post'])) {
				for ($i = count($mixed_meta['entries_post']); $i < $mixed_meta['total_db_entries']; $i ++) {
					foreach ($mixed_meta['fields'] as $mixed_field) {
						/* Craft mixed crud field name */
						$mixed_crud_field = 'mixed_' . $mixed_table . '_' . $mixed_field . '_' . ($i + 1);

						/* Fetch value for this field from the database */
						$mixed_db_value = $this->value_from_database($id, $mixed_crud_field);

						/* If an entry wasn't found, skip it */
						if ($mixed_db_value === false)
							break;

						/* Insert the value that will be deleted */
						array_push($changed_data, array(
							'field' => $mixed_crud_field,
							'value_old' => $mixed_db_value,
							'value_new' => ''
						));
					}
				}
			}
		}

		/* All good */
		return $changed_data;
	}


	/** Mixed handlers **/

	public function mixed_crud_field($field) {
		$mixed_field = array();

		/* Mixed field format is:
		 * 
		 * mixed_<table>_<field>_<mixed id>
		 *
		 * There's an exception for files and time counter fields which start with an underscore prefix.
		 * We'll first evaluate the $field contents for these exceptions before applying the default parser.
		 *
		 */

		/* NOTE: The following approach is not bullet proof... there is a possibility that two foreign table names
		 * may collide if a field name with underscores (or a table name) cause a multiple matches under the format
		 * mixed_<table>_<field>_<mixed id> ...
		 *
		 * Probably this won't be fixed in a near future, but should be well documented.
		 */

		/* Get foreign table name from $field */
		$mixed_foreign_table = NULL;

		foreach ($this->relative_tables($this->config['name'], 'mixed') as $mixed_rel_table) {
			$mixed_rel_foreign_table = array_pop(array_diff($this->mixed_rel_table_names($mixed_rel_table, $this->config['name']), array($this->config['name'])));

			/* Check if the field prefix matches the foreign table name */
			if (('mixed_' . $mixed_rel_foreign_table . '_') == substr($field, 0, 7 + strlen($mixed_rel_foreign_table))) {
				$mixed_foreign_table = $mixed_rel_foreign_table;
				break;
			}
		}

		/* If the table cannot be found, we cannot proceed */
		if ($mixed_foreign_table === NULL)
			$this->response->code('500', NDPHP_LANG_MOD_UNABLE_FIND_MIXED_REL_FIELD . ': ' . $field, $this->config['default_charset'], !$this->request->is_ajax());

		/* Retrieve table, field and mixed id */
		$mixed_field[0] = $mixed_foreign_table;
		$mixed_field[1] = implode('_', array_slice(explode('_', ltrim(str_replace($mixed_foreign_table, '', substr($field, 6)), '_')), 0, -1));
		$mixed_field[2] = end(explode('_', $field));

		/* Minor fix for special field types _file_* and _timer_* which have a '_' prefix */
		if (preg_match('/^mixed_[a-zA-Z0-9]+__file_.+$/i', $field) || preg_match('/^mixed_[a-zA-Z0-9]+__timer_.+$/i', $field)) {
			$mixed_field[1] = '_' . $mixed_field[1];
		}

		/* 
		 * Description:
		 * 
		 * $mixed_field[0] --> table name
		 * $mixed_field[1] --> field name
		 * $mixed_field[2] --> mixed field id
		 * 
		 */

		return $mixed_field;
	}

	/* Generic Fetchers */
	public function row($id, $table = NULL, $fields = array()) {
		if (count($fields)) {
			$this->db->select(implode(',', $fields));
		}
	}
}
