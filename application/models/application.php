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

class UW_Application extends UW_Model {
	/***************************/
	/*  Current Configuration  */
	/***************************/
	private $_charset = NDPHP_LANG_MOD_DEFAULT_CHARSET;
	private $_theme = 'Blueish';

	public function __construct() {
		parent::__construct();

		/* Fetch theme name from the currently active configuration */
		$this->db->select('themes.theme AS name');
		$this->db->from('configuration');
		$this->db->join('themes', 'configuration.themes_id = themes.id', 'left');
		$this->db->where('configuration.active', true);
		$q = $this->db->get();

		if (!$q->num_rows()) {
			header('HTTP/1.1 500 Internal Server Error.');
			die('No active configuration could be found.');
		}

		$row = $q->row_array();

		$this->_theme = $row['name'];
	}


	/******************************/
	/*  GLOBAL PRE-COMPUTED DATA  */
	/******************************/

	private $_app_menu_hidden_list = array(
		'builder',
		'charts_config',
		'charts_geometry',
		'charts_types',
		'configuration',
		'dbms',
		'documentation',
		'payment_actions',
		'payment_status',
		'payment_types',
		'payments',
		'items',
		'timezones',
		'transaction_history',
		'themes',
		'countries',
		'subscription_types',
		'transaction_types',
		'features',
		'logging',
		'accounting',
		'scheduler',
		'sessions',
		'themes_animations_default',
		'themes_animations_ordering',
		'users',
		'roles',
		'update',
		'weekdays',
		'months',
		'notifications'
	);

	private $_app_menu_aliases_list = array(
		'builder' => NDPHP_LANG_MOD_MENU_BUILDER_NAME,
		'charts_config' => NDPHP_LANG_MOD_MENU_CHARTS_CONFIG_NAME,
		'charts_geometry' => NDPHP_LANG_MOD_MENU_CHARTS_GEOMETRY_NAME,
		'charts_types' => NDPHP_LANG_MOD_MENU_CHARTS_TYPES_NAME,
		'configuration' => NDPHP_LANG_MOD_MENU_CONFIGURATION_NAME,
		'countries' => NDPHP_LANG_MOD_MENU_COUNTRIES_NAME,
		'dbms' => NDPHP_LANG_MOD_MENU_DBMS_NAME,
		'documentation' => NDPHP_LANG_MOD_MENU_DOCUMENTATION_NAME,
		'features' => NDPHP_LANG_MOD_MENU_FEATURES_NAME,
		'items' => NDPHP_LANG_MOD_MENU_ITEMS_NAME,
		'logging' => NDPHP_LANG_MOD_MENU_LOGGING_NAME,
		'months' => NDPHP_LANG_MOD_MENU_MONTHS_NAME,
		'notifications' => NDPHP_LANG_MOD_MENU_NOTIFICATIONS_NAME,
		'payment_actions' => NDPHP_LANG_MOD_MENU_PAYMENT_ACTIONS_NAME,
		'payment_status' => NDPHP_LANG_MOD_MENU_PAYMENT_STATUS_NAME,
		'payment_types' => NDPHP_LANG_MOD_MENU_PAYMENT_TYPES_NAME,
		'payments' => NDPHP_LANG_MOD_MENU_PAYMENTS_NAME,
		'roles' => NDPHP_LANG_MOD_MENU_ROLES_NAME,
		'scheduler' => NDPHP_LANG_MOD_MENU_SCHEDULER_NAME,
		'sessions' => NDPHP_LANG_MOD_MENU_SESSIONS_NAME,
		'subscription_types' => NDPHP_LANG_MOD_MENU_SUBSCRIPTION_TYPES_NAME,
		'themes' => NDPHP_LANG_MOD_MENU_THEMES_NAME,
		'themes_animations_default' => NDPHP_LANG_MOD_MENU_THEMES_ANIM_DEFAULT_NAME,
		'themes_animations_ordering' => NDPHP_LANG_MOD_MENU_THEMES_ANIM_ORDERING_NAME,
		'timezones' => NDPHP_LANG_MOD_MENU_TIMEZONES_NAME,
		'transaction_history' => NDPHP_LANG_MOD_MENU_TRANSACTION_HISTORY_NAME,
		'transaction_types' => NDPHP_LANG_MOD_MENU_TRANSACTION_TYPES_NAME,
		'users' => NDPHP_LANG_MOD_MENU_USERS_NAME,
		'weekdays' => NDPHP_LANG_MOD_MENU_WEEKDAYS_NAME
	);

	private $_app_menu_order_list = array();

	private function _app_compute_menu_hidden($app_model) {
		foreach ($app_model['menus'] as $menu) {
			if (isset($menu['options']['hidden']) && $menu['options']['hidden'])
				array_push($this->_app_menu_hidden_list, $menu['db']['name']);
		}
	}

	private function _app_compute_menu_aliases($app_model) {
		foreach ($app_model['menus'] as $menu) {
			if ($menu['type'] != 'detached' && isset($menu['properties']['alias']) && $menu['properties']['alias']) {
				$this->_app_menu_aliases_list[$menu['db']['name']] = str_replace("\"", "\\\"", $menu['properties']['alias']);
			}
		}
	}

	private function _app_compute_menu_order($app_model) {
		foreach ($app_model['menus'] as $menu) {
			if (isset($menu['options']['hidden']) && $menu['options']['hidden'])
				continue;

			array_push($this->_app_menu_order_list, $menu['db']['name']);
		}
	}

	private function _app_create_ctrl_ide_setup() {
		/* Populate hidden menu entries */
		$hidden_menu_list = 'array(';
		if (count($this->_app_menu_hidden_list)) {
			$hidden_menu_list .= '\'' . implode('\',\'', $this->_app_menu_hidden_list) . '\'';
		}
		$hidden_menu_list .= ')';

		/* Populate aliased menu entries */
		$alias_menu_list = '';
		foreach ($this->_app_menu_aliases_list as $menu_name => $menu_alias) {
			$alias_menu_list .= "\t\t'" . $menu_name . "' => \"" . $menu_alias . "\",\n";
		}

		/* Populate hidden menu entries order */
		$order_menu_list = 'array(';
		if (count($this->_app_menu_order_list)) {
			$order_menu_list .= '\'' . implode('\',\'', $this->_app_menu_order_list) . '\'';
		}
		$order_menu_list .= ')';

		/* Craft ide_setup.php file contents */
		$ide_setup_content = '' .
			'<?php' . "\n" .
			"\n" .
			'/* DO NOT MODIFY THIS FILE */' . "\n" .
			"\n" .
			'/* This file is included on the __construct() of all controllers that are' . "\n" .
 			' * not managed by the IDE Builder.' . "\n" .
 			' * The IDE Builder creates this file in order gain some control over' . "\n" .
 			' * the unmanaged controllers.' . "\n" .
 			' * ' . "\n" .
 			' * TODO: In the future, all the controllers shall be managed (created)' . "\n" .
 			' * by the IDE Builder' . "\n" .
 			' * ' . "\n" .
 			' */' . "\n" .
 			"\n" .
			'$this->_hide_menu_entries = array_merge($this->_hide_menu_entries, ' . $hidden_menu_list . ');' . "\n" .
			"\n" .
			'$this->_hide_global_search_controllers = $this->_hide_menu_entries;' . "\n" .
			"\n" .
			'$this->_menu_entries_order = array_merge($this->_menu_entries_order, ' . $order_menu_list . ');' . "\n" .
			"\n" .
			'$this->_menu_entries_aliases = array_merge($this->_menu_entries_aliases, array(' . "\n" .
			'		/* Main Menu entries alias */' . "\n" .
					rtrim($alias_menu_list, ",\n") . "\n" .
			'	)' . "\n" .
			");\n" .
			"\n";

		/* Recreate ide_setup.php file. FIXME: Error checking missing on file operations */
		$ide_setup_file = fopen(SYSTEM_BASE_DIR . '/application/controllers/lib/ide_setup.php', "w");
		fwrite($ide_setup_file, $ide_setup_content);
		fflush($ide_setup_file);
		fclose($ide_setup_file);

		/* All good */
		return true;
	}

	private function _app_compute_pre($app_model) {
		$this->_app_compute_menu_hidden($app_model);
		$this->_app_compute_menu_aliases($app_model);
		$this->_app_compute_menu_order($app_model);
		$this->_app_create_ctrl_ide_setup();
	}


	/************************/
	/*   RELATIONSHIP API   */
	/************************/

	private $_table_rel_components_multiple = array();
	private $_table_rel_components_mixed = array();

	private function _table_rel_add_components($type, $table, $component1, $component2) {
		if ($type == 'multiple') {
			$this->_table_rel_components_multiple[$table] = array($component1, $component2);
		} else if ($type == 'mixed') {
			$this->_table_rel_components_mixed[$table] = array($component1, $component2);
		} else {
			header('HTTP/1.1 500 Internal Server Error');
			die('Unknown relationship type: ' . $type);
		}
	}

	private function _table_rel_get_components($type, $table) {
		if ($type == 'multiple') {
			if (isset($this->_table_rel_components_multiple[$table]))
				return $this->_table_rel_components_multiple[$table];
		} else if ($type == 'mixed') {
			if (isset($this->_table_rel_components_mixed[$table]))
				return $this->_table_rel_components_mixed[$table];
		}

		return NULL;
	}


	/************************/
	/*  POSTPONE BATCH API  */
	/************************/

	private $_pp_batch_db_fk = array();
	private $_pp_batch_db_special_tables = array();

	private function _pp_batch_db_key_foreign_store($src_table, $src_field, $foreign_table, $foreign_field, $cascade_delete = false, $cascade_update = false) {
		/* Stores foreign key to be commited by _batch_db_commit() */
		array_push($this->_pp_batch_db_fk, array(
			'src_table' => $src_table,
			'src_field' => $src_field,
			'foreign_table' => $foreign_table,
			'foreign_field' => $foreign_field,
			'cascade_delete' => $cascade_delete,
			'cascade_update' => $cascade_update
		));
	}

	private function _pp_batch_db_special_table_store($table, $type, $op = 'create', $parent_table = NULL) {
		/* Stores a table name and type to be created/commited by _batch_db_commit() */
		array_push($this->_pp_batch_db_special_tables, array(
			'name' => $table,
			'type' => $type,
			'op' => $op, /* $op may assume 'create' or 'rebuild' */
			'parent_table' => $parent_table
		));
	}

	private function _pp_batch_db_commit() {
		/* Commit special tables */
		foreach ($this->_pp_batch_db_special_tables as $table) {
			if ($table['type'] == 'multiple') {
				if ($table['op'] == 'create') {
					if (!$this->_special_table_create_rel($table['name']))
						return false;
				} else if ($table['op'] == 'rebuild') {
					if (!$this->_special_table_rebuild_rel($table['name'], $table['parent_table']))
						return false;
				}
			} else if ($table['type'] == 'mixed') {
				if ($table['op'] == 'create') {
					if (!$this->_special_table_create_mixed($table['name']))
						return false;
				} else if ($table['op'] == 'rebuild') {
					if (!$this->_special_table_rebuild_mixed($table['name'], $table['parent_table']))
						return false;
				} else {
					error_log('_pp_batch_db_commit(): Unrecognized $table[\'op\']: ' . $table['op']);
					return false;
				}
			} else {
				error_log('_pp_batch_db_commit(): Unrecognized $table[\'type\']: ' . $table['type']);
				return false;
			}
		}

		/* Commit foreign keys */
		foreach ($this->_pp_batch_db_fk as $fkey) {
			$this->db->table_key_column_foreign_add($fkey['src_table'], $fkey['src_field'], $fkey['foreign_table'], $fkey['foreign_field'], $fkey['cascade_delete'], $fkey['cascade_update']);
		}

		/* All good */
		return true;
	}


	/********************/
	/*    PARSING API   */
	/********************/

	private function _parse_rel_table_names($rel, $knowable, $mixed = false) {
		if (!strpos($rel, $knowable))
			return array();

		$foreign_table_raw = str_replace($knowable, '', substr($rel, $mixed ? 6 : 4));

		/* After removing the $target table name from the string, if the remaining starts with '_', then
		 * that foreign table was positioned at the end of the relationship table name.
		 */
		return ($foreign_table_raw[0] == '_') ? array($knowable, trim($foreign_table_raw, '_')) : array(trim($foreign_table_raw, '_'), $knowable);
	}


	/********************/
	/*  CONVERTION API  */
	/********************/

	private $_convert_renamed_tables = array(
		/* 'old_name' => 'new_name', */
	);
	private $_convert_changed_fields = array(
		/* 'new_table_name' => array('old_field' => $field) */
	);

	/* TODO: FIXME: Unused... Currently, we cannot change menu types */


	private function _convert_db_table_to_generic($table) {
		/* TODO: FIXME: Check if the table is detached (prefixed with '_') and rename it to strip the prefix */

		/* Remove a users_id field if it exists */
		if ($this->db->table_column_exists($table, 'users_id')) {
			$this->db->table_key_column_foreign_drop($table, 'users_id');
			$this->db->table_column_drop($table, 'users_id');
		}

		return true;
	}

	private function _convert_db_table_to_limited($table) {
		/* TODO: FIXME: Check if the table is detached (prefixed with '_') and rename it to strip the prefix */

		/* Add a users_id field if it does not exist */
		if (!$this->db->table_column_exists($table, 'users_id')) {
			$this->db->table_column_create($table, 'users_id', 'int(11)', false /* NOT NULL */, 1 /* DEFAULT */);
			$this->_pp_batch_db_key_foreign_store($table, 'users_id', 'users', 'id', true /* CASCADE DELETE */);
		}

		return true;
	}

	private function _convert_db_table_to_custom($table) {
		/* TODO ... */
		return true;
	}

	private function _convert_db_table_to_detached($table) {
		/* TODO: FIXME: Check if the table has users_id field (was limited type) and remove it */

		/* Rename table to add a _ prefix if it does not already have it */
		if (!$this->db->table_exists('_' . $table) && $this->db->table_exists($table)) {
			/* Table $table exists, but the _ is missing */
			$this->db->table_rename($table, '_' . $table);
		} else if ($this->db->table_exists($table)) {
			/* TODO .... */
		}

		return true;
	}

	private function _convert_db_table($table, $to) {
		switch ($to) {
			case "generic"  : $this->_convert_db_table_to_generic($table);  break;
			case "limited"  : $this->_convert_db_table_to_limited($table);  break;
			case 'custom'	: $this->_convert_db_table_to_custom($table);	break;
			case "detached" : $this->_convert_db_table_to_detached($table); break;
			default         : return false;
		}

		return true;
	}


	/****************/
	/*  OBJECT API  */
	/****************/

	private function _object_exists($object) {
		/* Check if $object exists */
		$this->db->select('object');
		$this->db->from('model_objects');
		$this->db->where('object', $object);
		$q = $this->db->get();

		if ($q->num_rows())
			return true;

		return false;
	}

	private function _object_purge($object) {
		/* Removes the object reference and associated tables/fields from the database */

		/* Get object information */
		$this->db->from('model_objects');
		$this->db->where('object', $object);
		$q = $this->db->get();

		$obj = $q->row_array();

		if ($obj['is_table']) {
			/* Delete all the field objects associated to this table */
			$this->db->from('model_objects');
			$this->db->where('db_table', $obj['db_table']);
			$this->db->where('is_field', true);
			$q = $this->db->get();

			foreach ($q->result_array() as $row) {
				$this->_object_purge($row['object']);
			}

			/* Drop the object from database... It can be a TABLE or a VIEW... */
			if ($obj['type'] == 'custom') {
				/* The object is a view (custom menu entries are database views) */
				$this->db->query('DROP VIEW IF EXISTS `' . $obj['db_table'] . '`');

				/* TODO: Also drop _acl_* and _help_* references */
			} else {
				/* The object is a table */
				$this->db->table_drop($obj['db_table'], true /* If Exists */, true /* Force */);

				/* TODO: Also drop _acl_* and _help_* references */
			}
		} else if ($obj['is_field']) {
			if ($obj['type'] == 'dropdown') {
				/* If the object is a special column, drop the FK before droping the field */
				$this->db->table_key_column_foreign_drop($obj['db_table'], $obj['db_table_field']);
				$this->db->table_column_drop($obj['db_table'], $obj['db_table_field']);
			} else if ($obj['type'] == 'multiple') {
				/* If the object is a many-to-many relational table, drop the table */
				$this->db->table_drop($obj['db_table_field'], true /* If Exists */, true /* Force */);
			} else if ($obj['type'] == 'mixed') {
				/* If the object is a many-to-many mixed table, drop the table */
				$this->db->table_drop($obj['db_table_field'], true /* If Exists */, true /* Force */);
			} else {
				/* If the object is a common column, drop the column */
				$this->db->table_column_drop($obj['db_table'], $obj['db_table_field']);
			}
		}

		/* Delete the object reference */
		$this->db->delete('model_objects', array('object' => $object));

		/* All good */
		return true;
	}

	private function _object_ref_del($object) {
		$this->db->delete('model_objects', array('object' => $object));

		/* All good */
		return true;
	}

	private function _object_ref_table_add($object, $table_name, $type) {
		$this->db->insert('model_objects', array(
			'object' => $object,
			'db_table' => $table_name,
			'is_table' => true,
			'is_field' => false,
			'type' => $type
		));

		/* All good */
		return true;
	}

	private function _object_ref_table_field_add($object, $table_name, $table_field_name, $type) {
		$this->db->insert('model_objects', array(
			'object' => $object,
			'db_table' => $table_name,
			'db_table_field' => $table_field_name,
			'is_table' => false,
			'is_field' => true,
			'type' => $type
		));

		/* All good */
		return true;
	}

	private function _object_search_table_name($object) {
		/* Return the table name for the searched $object */
		$this->db->from('model_objects');
		$this->db->where('object', $object);
		$this->db->where('is_table', true);
		$q = $this->db->get();

		$row = $q->row_array();

		return $row;
	}

	private function _object_search_table_field_name($object) {
		/* Return the table field name for the searched $object */
		$this->db->from('model_objects');
		$this->db->where('object', $object);
		$this->db->where('is_field', true);
		$q = $this->db->get();

		$row = $q->row_array();

		return $row;
	}

	private function _object_get_stored_table_list() {
		/* Retrieve the table object list from database */
		$this->db->select('object');
		$this->db->from('model_objects');
		$this->db->where('is_table', true);
		$q = $this->db->get();

		$obj_list = array();

		foreach ($q->result_array() as $row) {
			array_push($obj_list, $row['object']);
		}

		return $obj_list;
	}

	private function _object_get_stored_table_field_list() {
		/* Retrieve the table field object list from database */
		$this->db->select('object');
		$this->db->from('model_objects');
		$this->db->where('is_field', true);
		$q = $this->db->get();

		$obj_list = array();

		foreach ($q->result_array() as $row) {
			array_push($obj_list, $row['object']);
		}

		return $obj_list;
	}

	private function _object_remove_non_existent($obj_list) {
		$this->db->select('object');
		$this->db->from('model_objects');
		$q = $this->db->get();

		foreach ($q->result_array() as $row) {
			if (in_array($row['object'], $obj_list))
				continue;

			$this->_object_purge($row['object']);
		}

		/* All good */
		return true;
	}


	/***********************/
	/*  SPECIAL TABLE API  */
	/***********************/

	private function _special_table_rebuild_rel($table_name, $parent_table) {
		/* Check if there was a change in table names and rename table and fields accordingly,
		 * updating the corresponding foreign keys
		 */

		$old_table_name = $table_name;

		/* Fetch relationship table components */
		$columns = $this->_table_rel_get_components('multiple', $table_name);

		if (!$columns) {
			/* Since we've one parent table name, we should be able to retrieve both components from it... */
			$columns = $this->_parse_rel_table_names($table_name, $parent_table, false);

			if (!$columns) {
				/* Our hands are tied... there's still a way that may allow us to fetch both components without ambiguity,
				 * but such method will require the analysis of the full application model, processing every controller...
				 * If we were unable to retrieve the components based on any of the used methods, this is probably an issue
				 * caused somewhere else and needs to be fixed there.
				 */
				error_log('_special_table_rebuild_rel(): Unable to retrieve relationship table components: ' . $table_name);
				return false;
			}
		}

		/* Drop foreign keys */
		$this->db->table_key_column_foreign_drop($table_name, $columns[0] . '_id');
		$this->db->table_key_column_foreign_drop($table_name, $columns[1] . '_id');

		/* Check if the first table reference was changed */
		if (isset($this->_convert_renamed_tables[$columns[0]])) {
			/* Modify table and columns */
			$this->db->table_rename($table_name, 'rel_' . $this->_convert_renamed_tables[$columns[0]] . '_' . $columns[1]);
			$table_name = 'rel_' . $this->_convert_renamed_tables[$columns[0]] . '_' . $columns[1];
			$this->db->table_column_change($table_name, $columns[0] . '_id', $this->_convert_renamed_tables[$columns[0]] . '_id', 'int(11)', false, '1');
		}

		/* Update columns if they have been modified */
		if (isset($this->_convert_renamed_tables[$columns[0]]))
			$columns[0] = $this->_convert_renamed_tables[$columns[0]];

		/* Update relationship table components */
		$this->_table_rel_add_components('multiple', $table_name, $columns[0], $columns[1]);

		/* Check if the second table reference was changed */
		if (isset($this->_convert_renamed_tables[$columns[1]])) {
			/* Modify table and columns */
			$this->db->table_rename($table_name, 'rel_' . $columns[0] . '_' . $this->_convert_renamed_tables[$columns[1]]);
			$table_name = 'rel_' . $columns[0]  . '_' . $this->_convert_renamed_tables[$columns[1]];
			$this->db->table_column_change($table_name, $columns[1] . '_id', $this->_convert_renamed_tables[$columns[1]] . '_id', 'int(11)', false, '1');
		}

		/* Update columns if they have been modified */
		if (isset($this->_convert_renamed_tables[$columns[1]]))
			$columns[1] = $this->_convert_renamed_tables[$columns[1]];

		/* Update relationship table components */
		$this->_table_rel_add_components('multiple', $table_name, $columns[0], $columns[1]);

		/* Store foreign keys to be added later */
		$this->_pp_batch_db_key_foreign_store($table_name, $columns[0] . '_id', $columns[0], 'id', true /* CASCADE DELETE */);
		$this->_pp_batch_db_key_foreign_store($table_name, $columns[1] . '_id', $columns[1], 'id', true /* CASCADE DELETE */);

		/* Update db_table_field of $columns[1] table */
		$this->db->where('db_table_field', $old_table_name);
		$this->db->where('db_table', $columns[0]);
		$this->db->where('is_field', true);
		$this->db->update('model_objects', array('db_table_field' => $table_name));

		/* All good */
		return true;
	}

	private function _special_table_create_rel($table_name) {
		/* Fetch relationship table components */
		$columns = $this->_table_rel_get_components('multiple', $table_name);

		if (!$columns) {
			error_log('_special_table_create_rel(): Unable to retrieve relationship table components.');
			return false;
		}

		/* Create table and first field */
		$this->db->table_create($table_name, $columns[0] . '_id', 'int(11)', false, false, false);

		/* Create second field */
		$this->db->table_column_create($table_name, $columns[1] . '_id', 'int(11)', false, '1');

		/* Add foreign keys */
		$this->_pp_batch_db_key_foreign_store($table_name, $columns[0] . '_id', $columns[0], 'id', true /* CASCADE DELETE */);
		$this->_pp_batch_db_key_foreign_store($table_name, $columns[1] . '_id', $columns[1], 'id', true /* CASCADE DELETE */);

		/* All good */
		return true;
	}

	private function _special_table_rebuild_mixed($table_name, $parent_table) {
		/* Check if there was a change in table names and rename table and fields accordingly,
		 * updating the corresponding foreign keys.
		 */

		$old_table_name = $table_name;

		/* Fetch relationship table components */
		$columns = $this->_table_rel_get_components('mixed', $table_name);

		if (!$columns) {
			/* Since we've one parent table name, we should be able to retrieve both components from it... */
			$columns = $this->_parse_rel_table_names($table_name, $parent_table, true);

			if (!$columns) {
				/* Our hands are tied... there's still a way that may allow us to fetch both components without ambiguity,
				 * but such method will require the analysis of the full application model, processing every controller...
				 * If we were unable to retrieve the components based on any of the used methods, this is probably an issue
				 * caused somewhere else and needs to be fixed there.
				 */
				error_log('_special_table_rebuild_mixed(): Unable to retrieve relationship table components: ' . $table_name);
				return false;
			}
		}

		/* Drop foreign keys */
		$this->db->table_key_column_foreign_drop($table_name, $columns[0] . '_id');
		$this->db->table_key_column_foreign_drop($table_name, $columns[1] . '_id');

		/* Check if the first table reference was changed */
		if (isset($this->_convert_renamed_tables[$columns[0]])) {
			/* Modify table and columns */
			$this->db->table_rename($table_name, 'mixed_' . $this->_convert_renamed_tables[$columns[0]] . '_' . $columns[1]);
			$table_name = 'mixed_' . $this->_convert_renamed_tables[$columns[0]] . '_' . $columns[1];
			$this->db->table_column_change($table_name, $columns[0] . '_id', $this->_convert_renamed_tables[$columns[0]] . '_id', 'int(11)', false, '1');
		}

		/* Update columns if they have been modified */
		if (isset($this->_convert_renamed_tables[$columns[0]]))
			$columns[0] = $this->_convert_renamed_tables[$columns[0]];

		/* Update relationship table components */
		$this->_table_rel_add_components('mixed', $table_name, $columns[0], $columns[1]);

		/* Check if the second table reference was changed */
		if (isset($this->_convert_renamed_tables[$columns[1]])) {
			/* Modify table and columns */
			$this->db->table_rename($table_name, 'mixed_' . $columns[0] . '_' . $this->_convert_renamed_tables[$columns[1]]);
			$table_name = 'mixed_' . $columns[0]  . '_' . $this->_convert_renamed_tables[$columns[1]];
			$this->db->table_column_change($table_name, $columns[1] . '_id', $this->_convert_renamed_tables[$columns[1]] . '_id', 'int(11)', false, '1');
		}

		/* Update columns if they have been modified */
		if (isset($this->_convert_renamed_tables[$columns[1]]))
			$columns[1] = $this->_convert_renamed_tables[$columns[1]];

		/* Update relationship table components */
		$this->_table_rel_add_components('mixed', $table_name, $columns[0], $columns[1]);

		/* Store foreign keys to be added later */
		$this->_pp_batch_db_key_foreign_store($table_name, $columns[0] . '_id', $columns[0], 'id', true /* CASCADE DELETE */);
		$this->_pp_batch_db_key_foreign_store($table_name, $columns[1] . '_id', $columns[1], 'id', true /* CASCADE DELETE */);

		/* Fetch the foreign table fields data */
		$ftable_data = array();

		/* Convert table description into a integer indexed array */
		foreach ($this->db->describe_table($columns[1]) as $ftable_field) {
			array_push($ftable_data, $ftable_field);
		}

		/* If no data was retrieved, log it and exit */
		if (!count($ftable_data)) {
			error_log('_special_table_rebuild_mixed(): Unable to fetch foreign table information.');
			return false;
		}

		/* TODO: Process changes to the foreign table. If a field was changed, it was recorded before */

		/* Fetch the mixed table fields */
		$mtable_data = array();

		/* Create a list of the mixed table fields */
		foreach ($this->db->describe_table($table_name) as $mtable_field) {
			array_push($mtable_data, $mtable_field);
		}

		/* If no fields were retrieved, log it and exit */
		if (!count($mtable_data)) {
			error_log('_special_table_rebuild_mixed(): Unable to fetch mixed table information: ' . $table_name);
			return false;
		}

		/* A list of mixed table fields will be created in the following array */
		$mtable_fields = array();

		/* Check if there are any fields to be changed and change them (before adding or removing any field) */
		foreach ($mtable_data as $field) {
			if (isset($this->_convert_changed_fields[$columns[1]]) && isset($this->_convert_changed_fields[$columns[1]][$field['name']])) {
				/* This field was changed... */
				$field_new = $this->_convert_changed_fields[$columns[1]][$field['name']];

				/* Check if the field name was changed */
				if ($field_new['name'] != $field['name']) {
					/* Remove any field from this mixed table that match the new name */
					foreach ($mtable_data as $field_collision) {
						if ($field_collision['name'] == $field_new['name'])
							$this->db->table_column_drop($table_name, $field_collision['name']);
					}
				}

				/* Compute column type */
				$type = NULL;

				if (end(explode('_', $field_new['name'])) == 'id') {
					/* Single relationship fields (dropdown types) are stored as string literal on mixed tables, not by id (in order to preserve the value through time) */
					$type = 'varchar(255)'; /* TODO: FIXME: We should fetch the table's second field type */
				} else {
					$type = $field_new['db']['type'];
				}

				/* Compute after */
				$after = $field_new['db']['after'];

				if ($after == $ftable_data[1]['name']) {
					/* The field is positioned right after the second column of the foreign table (so it's the 3rd field), which should be placed at the 5th column on the mixed table */
					$after = $columns[1] . '_id'; /* 4th column of mixed table */
				}

				/* Change the the mixed table column */
				$this->db->table_column_change($table_name, $field['name'], $field_new['db']['name'], $type, $field_new['db']['is_nullable'], $field_new['db']['default'], $after);

				$field_name = $field_new['db']['name'];
			} else {
				$field_name = $field['name'];
			}

			/* Also build a list of fields for the mixed table */
			array_push($mtable_fields, $field_name);
		}

		/* A list of the foreign table fields will be created in the following array */
		$ftable_fields = array();

		/* Now we need to compare the foreign table fields, one by one, and create the fields that are missing */
		for ($i = 0; $i < count($ftable_data); $i ++) {
			/* Populate the list of foreign table fields */
			array_push($ftable_fields, $ftable_data[$i]['name']);

			/* Ignore 'id' field */
			if ($ftable_data[$i]['name'] == 'id')
				continue;

			/* FIXME: TODO: If a users_id field is present, should we create it too in the mixed table? */

			/* Compute column type */
			$type = NULL;

			if (end(explode('_', $ftable_data[$i]['name'])) == 'id') {
				/* Single relationship fields (dropdown types) are stored as string literal on mixed tables, not by id (in order to preserve the value through time) */
				$type = 'varchar(255)'; /* FIXME: We should fetch the table's second field type */
			} else if ($ftable_data[$i]['max_length']) {
				$type = $ftable_data[$i]['type'] . '(' . $ftable_data[$i]['max_length'] . ')';
			} else {
				$type = $ftable_data[$i]['type'];
			}

			/* Compute default */
			$default = 'NULL';

			if ($ftable_data[$i]['default'] !== NULL) {
				if (strpos($ftable_data[$i]['type'], 'int')) {
					/* If it's an integer type, no '' are required */
					$default = $ftable_data[$i]['default'];
				} else {
					/* ... otherwise, the value must be quoted (treated as string) */
					$default = '\'' . $ftable_data[$i]['default'] . '\'';
				}
			}

			/* Compute after */
			$after = '';

			if ($i == 1) {
				/* $i will never be less than 1, since 'id' field will be ignored earlier */
				$after = 'id';
			} else if ($i == 2) {
				/* If this is the third field of the foreign table, move it to the 5th position of the mixed table */
				$after = $columns[1] . '_id';
			} else {
				/* Just set it to be the previous element of the array */
				$after = $ftable_data[$i - 1]['name'];
			}

			/* If the field is already present in the mixed table, skip it */
			if (in_array($ftable_data[$i]['name'], $mtable_fields)) {
				/* Process field changes as column position may have been changed */
				$this->db->table_column_change($table_name, $ftable_data[$i]['name'], $ftable_data[$i]['name'], $type, $ftable_data[$i]['null'], $default, $after);
			} else {
				/* If it's not present, create it */
				$this->db->table_column_create($table_name, $ftable_data[$i]['name'], $type, $ftable_data[$i]['null'], $default, $after);
			}
		}

		/* Here we'll remove any field that is no longer present on the foreign table */
		for ($i = 4; $i < count($mtable_fields); $i ++) {
			/* FIXME: TODO: Ignore _tc_ fields */

			/* If the field is present in the foreign table, skip it */
			if (in_array($mtable_fields[$i], $ftable_fields))
				continue;

			/* If the field is not present in the foreign table, remove it */
			$this->db->table_column_drop($table_name, $mtable_fields[$i]);
		}

		/* Update db_table_field of $columns[1] table */
		$this->db->where('db_table_field', $old_table_name);
		$this->db->where('db_table', $columns[0]);
		$this->db->where('is_field', true);
		$this->db->update('model_objects', array('db_table_field' => $table_name));

		/* All good */
		return true;
	}

	private function _special_table_create_mixed($table_name) {
		/* Fetch relationship table components */
		$columns = $this->_table_rel_get_components('mixed', $table_name);

		if (!$columns) {
			error_log('_special_table_create_mixed(): Unable to retrieve relationship table components.');
			return false;
		}

		$ftable_data = array();

		/* Convert table description into a integer indexed array */
		foreach ($this->db->describe_table($columns[1]) as $ftable_field) {
			array_push($ftable_data, $ftable_field);
		}

		/* If no data was retrieved, log it and exit */
		if (!count($ftable_data)) {
			error_log('_special_table_create_mixed(): Unable to fetch foreign table information.');
			return false;
		}


		/* Create the mixed relationship table */
		$this->db->table_create($table_name, 'id', 'int(11)');

		/* Compute default value for $ftable_data[1] */
		$default = 'NULL';

		if ($ftable_data[1]['default'] !== NULL) {
			if (strpos($ftable_data[1]['type'], 'int')) {
				/* If it's an integer type, no '' are required */
				$default = $ftable_data[1]['default'];
			} else {
				/* ... otherwise, the value must be quoted (treated as string) */
				$default = '\'' . $ftable_data[1]['default'] . '\'';
			}
		}

		$this->db->table_column_create($table_name, $ftable_data[1]['name'], $ftable_data[1]['type'] . '(' . $ftable_data[1]['max_length'] . ')', $ftable_data[1]['null'], $default);
		$this->db->table_column_create($table_name, $columns[0] . '_id', 'int(11)', false, '1');
		$this->db->table_column_create($table_name, $columns[1] . '_id', 'int(11)', false, '1');

		/* Replicate the remaining fields from the foreign table... */
		for ($i = 2; $i < count($ftable_data); $i ++) {
			/* FIXME: TODO: If a users_id field is present, should we create it too in the mixed table? */

			/* Compute column type */
			$type = NULL;

			if (end(explode('_', $ftable_data[$i]['name'])) == 'id') {
				/* Single relationship fields (dropdown types) are stored as string literals on mixed tables, not by id (in order to preserve the value through time) */
				$type = 'varchar(255)'; /* TODO: FIXME: We should fetch the foreign table's second field type */
			} else if ($ftable_data[$i]['max_length']) {
				$type = $ftable_data[$i]['type'] . '(' . $ftable_data[$i]['max_length'] . ')';
			} else {
				$type = $ftable_data[$i]['type'];
			}

			/* Compute default value */
			$default = 'NULL';

			if ($ftable_data[$i]['default'] !== NULL) {
				if (strpos($ftable_data[$i]['type'], 'int')) {
					/* If it's an integer type, no '' are required */
					$default = $ftable_data[$i]['default'];
				} else {
					/* ... otherwise, the value must be quoted (treated as string) */
					$default = '\'' . $ftable_data[$i]['default'] . '\'';
				}
			}

			/* Create table column */
			$this->db->table_column_create($table_name, $ftable_data[$i]['name'], $type, $ftable_data[$i]['null'], $default);
		}

		/* Add foreign keys */
		$this->_pp_batch_db_key_foreign_store($table_name, $columns[0] . '_id', $columns[0], 'id', true /* CASCADE DELETE */);
		$this->_pp_batch_db_key_foreign_store($table_name, $columns[1] . '_id', $columns[1], 'id', true /* CASCADE DELETE */);

		/* All good */
		return true;
	}



	/**********************/
	/*  DATA PROCESS API  */
	/**********************/

	private function _process_menu_field_changes($menu, $field) {
		/* Check if this is a view table type (custom menu) */
		if ($menu['type'] == 'custom')
			return true; /* ... in this case we shall not attempt to create any fields */

		/* Fetch stored menu field object */
		$field_obj = $this->_object_search_table_field_name($field['obj_id']);

		/* Check if this is a special field that requires a table/foreign keys */
		if ($field['db']['has_keys']) {
			if ($field['db']['type'] == 'int(11)') {
				/* Check if the table was renamed... */
				if (isset($this->_convert_renamed_tables[$field_obj['db_table']])) {
					/* We need to force the index name based on the old table name */
					$this->db->table_key_column_foreign_drop($menu['db']['name'], $field_obj['db_table_field'], 'uw_fk_' . $field_obj['db_table'] . '_' . $field_obj['db_table_field']);
				} else {
					$this->db->table_key_column_foreign_drop($menu['db']['name'], $field_obj['db_table_field']);
				}

				/* Change the single relationship field */
				$this->db->table_column_change($menu['db']['name'], $field_obj['db_table_field'], $field['db']['name'], $field['db']['type'], $field['db']['is_nullable'], $field['db']['default'], $field['db']['after']);

				/* We cannot create the foreign key just yet because not all the
				 * tables may be created at this point. We need to store this operation to be
				 * done after all tables are created.
				 */
				$this->_pp_batch_db_key_foreign_store($menu['db']['name'], $field['db']['name'], implode('_', array_slice(explode('_', $field['db']['name']), 0, -1)) /* Removing _id suffix */, 'id', true /* CASCADE DELETE */);
			} else if ($field['db']['type'] == 'multiple') {
				/* Rebuild rel table to match the source and foreign table primary keys */
				/* We cannot rebuild the table just yet because not all the
				 * tables may be created at this point. We need to store this operation to be
				 * done after all tables are created.
				 */
				$this->_pp_batch_db_special_table_store($field_obj['db_table_field'], 'multiple', 'rebuild', $field_obj['db_table']); /* We need to pass the old name because it is the old table */
			} else if ($field['db']['type'] == 'mixed') {
				/* Rebuild mixed table to match the foreign table fields */
				/* We cannot rebuild the table just yet because not all the
				 * tables may be created at this point. We need to store this operation to be
				 * done after all tables are created.
				 */
				$this->_pp_batch_db_special_table_store($field_obj['db_table_field'], 'mixed', 'rebuild', $field_obj['db_table']); /* We need to pass the old name because it is the old table */
			}
		} else { /* This is a common field */
			/* Always try to remove any old unique key */
			$this->db->table_column_unique_drop($field_obj['db_table'], $field_obj['db_table_field'], true /* IF EXISTS (equivalent) */, $menu['db']['name'] /* If the table was renamed, force the table name where we need to check for the index */);

			/* Change the table column (even if there are no changes) */
			$this->db->table_column_change($menu['db']['name'], $field_obj['db_table_field'], $field['db']['name'], $field['db']['type'], $field['db']['is_nullable'], $field['db']['default'], $field['db']['after']);

			/* If it is unique, add new unique key */
			if ($field['db']['is_unique']) /* FIXME: WARNING: If the table is already populated, this may cause an error if there isn't data uniqueness */
				$this->db->table_column_unique_add($menu['db']['name'], $field['db']['name']);

			/* Check if field type is of _file_* and take appropriate actions on the file system based on the changes */
			if (substr($field_obj['db_table_field'], 0, 6) == '_file_') {
				if (substr($field['db']['name'], 0, 6) == '_file_') {
					/* Rename the old directory to the new directory */
					foreach (glob(SYSTEM_BASE_DIR . '/uploads/*/' . $field_obj['db_table'] . '/*/' . $field_obj['db_table_field']) as $field_dir) {
						// error_log('RENAMING "' . $field_dir . '" to "' . implode('/', array_slice(explode('/', $field_dir), 0, -1)) . '/' . $field['db']['name'] . '"');
						rename($field_dir, implode('/', array_slice(explode('/', $field_dir), 0, -1)) . '/' . $field['db']['name']);
					}
				}
			}
		}

		/* Check if the field name or table name was changed and update object reference record accordingly */
		if ($field['db']['name'] != $field_obj['db_table_field'] || isset($this->_convert_renamed_tables[$field_obj['db_table']])) {
			/* Delete current stored object */
			$this->_object_ref_del($field['obj_id']);

			/* Create a new and updated object reference */
			$this->_object_ref_table_field_add($field['obj_id'], $menu['db']['name'], $field['db']['name'], $field['type']);

			/* Store the field change as this indication may be required if there are mixed tables associated to it */
			$this->_convert_changed_fields[$menu['db']['name']][$field_obj['db_table_field']] = $field;
		}

		/* All good */
		return true;
	}

	private function _process_menu_field_create($menu, $field) {
		/* Create new object reference */
		$this->_object_ref_table_field_add($field['obj_id'], $menu['db']['name'], $field['db']['name'], $field['type']);

		/* Check if this is a view table type (custom menu) */
		if ($menu['type'] == 'custom')
			return true; /* ... in this case we shall not attempt to create any fields */

		/* Check if this is a special field that requires a table/foreign keys */
		if ($field['db']['has_keys']) {
			if ($field['db']['type'] == 'int(11)') {
				/* Create the single relationship field */
				$this->db->table_column_create($menu['db']['name'], $field['db']['name'], $field['db']['type'], $field['db']['is_nullable'], $field['db']['default'], $field['db']['after']);				

				/* We cannot create the foreign key just yet because not all the
				 * tables may be created at this point. We need to store this operation to be
				 * done after all tables are created.
				 */
				$this->_pp_batch_db_key_foreign_store($menu['db']['name'], $field['db']['name'], implode('_', array_slice(explode('_', $field['db']['name']), 0, -1)) /* Removing _id suffix */, 'id', true /* CASCADE DELETE */);
			} else if ($field['db']['type'] == 'multiple') {
				/* We cannot create the table just yet because not all the
				 * tables may be created at this point. We need to store this operation to be
				 * done after all tables are created.
				 */
				$this->_pp_batch_db_special_table_store($field['db']['name'], 'multiple', 'create');
			} else if ($field['db']['type'] == 'mixed') {
				/* We cannot create the table just yet because not all the
				 * tables may be created at this point. We need to store this operation to be
				 * done after all tables are created.
				 */
				$this->_pp_batch_db_special_table_store($field['db']['name'], 'mixed', 'create');
			}
		} else { /* This is a common field */
			/* Create table column */
			$this->db->table_column_create($menu['db']['name'], $field['db']['name'], $field['db']['type'], $field['db']['is_nullable'], $field['db']['default'], $field['db']['after']);

			/* If it is unique, add new unique key */
			if ($field['db']['is_unique'])
				$this->db->table_column_unique_add($menu['db']['name'], $field['db']['name']);
		}

		/* All good */
		return true;
	}

	private function _process_menu_fields($menu) {
		foreach ($menu['fields'] as $field) {
			if ($this->_object_exists($field['obj_id'])) {
				$this->_process_menu_field_changes($menu, $field);
			} else {
				$this->_process_menu_field_create($menu, $field);
			}
		}

		/* All good */
		return true;
	}

	private function _process_menu_changes($menu) {
		/* Fetch stored menu object */
		$menu_obj = $this->_object_search_table_name($menu['obj_id']);

		/* Check if database name is to be changed */
		if ($menu['db']['name'] != $menu_obj['db_table']) {
			/* Remove the current object reference */
			$this->_object_ref_del($menu['obj_id']);

			/* Rename table (if exists is required here for 'custom' menus which views may have not been created yet */
			if ($this->db->table_exists($menu_obj['db_table']))
				$this->db->table_rename($menu_obj['db_table'], $menu['db']['name']);

			/* TODO: FIXME: Update field objects from this menu to reflect table rename (is this required?) */

			/* If the table changed its name, check if it has foreign keys to multiple and
			 * mixed relationships. Flag them to be processed later by _process_menu_fields()
			 */
			$this->_convert_renamed_tables[$menu_obj['db_table']] = $menu['db']['name'];

			/* Add new object reference */
			$this->_object_ref_table_add($menu['obj_id'], $menu['db']['name'], $menu['type']);
		}

		/* All good */
		return true;
	}

	private function _process_menu_create($menu) {
		/* Add new object reference */
		$this->_object_ref_table_add($menu['obj_id'], $menu['db']['name'], $menu['type']);

		/* Check if this is a view table type (custom menu) */
		if ($menu['type'] == 'custom') {
			/* ... in this case we shall create a dummy view (The framework will alter this view when loaded for the first time) */
			$this->db->query('CREATE OR REPLACE VIEW ' . $menu['db']['name'] . ' AS SELECT 1');
			return true;
		}

		/* Create menu table */
		$this->db->table_create($menu['db']['name'], 'id', 'int(11)');

		/* All good */
		return true;
	}

	private function _process_menu_help_recreate($menu) {
		/* Delete all help entries related to this table */
		$this->db->delete('_help_tfhd', array(
			'table_name' => $menu['db']['name']
		));

		/* Insert the menu help, if there's any */
		if (isset($menu['properties']['help']) && $menu['properties']['help']) {
			$this->db->insert('_help_tfhd', array(
				'table_name' => $menu['db']['name'],
				'help_description' => $menu['properties']['help']
			));
		}

		/* Insert menu fields help */
		foreach ($menu['fields'] as $field) {
			if (isset($field['properties']['help']) || isset($field['properties']['units']) || isset($field['properties']['input_pattern']) || isset($field['properties']['placeholder'])) {
				$help_desc = '';
				$placeholder = NULL;
				$field_units = '';
				$units_on_left = false;
				$input_pattern = NULL;

				if (isset($field['properties']['help']))
					$help_desc = $field['properties']['help'];

				if (isset($field['properties']['placeholder']))
					$placeholder = $field['properties']['placeholder'];

				if (isset($field['properties']['units']))
					$field_units = $field['properties']['units'];

				if (isset($field['properties']['units_on_left']))
					$units_on_left = $field['properties']['units_on_left'];

				if (isset($field['properties']['input_pattern']))
					$input_pattern = $field['properties']['input_pattern'];

				if ($help_desc || $placeholder || $field_units || $input_pattern) {
					$this->db->insert('_help_tfhd', array(
						'table_name' => $menu['db']['name'],
						'field_name' => $field['db']['name'],
						'placeholder' => $placeholder,
						'field_units' => $field_units,
						'units_on_left' => $units_on_left,
						'input_pattern' => $input_pattern,
						'help_description' => $help_desc
					));
				}
			}
		}

		/* All Good */
		return true;
	}

	private function _process_menu_acl_recreate($menu) {
		/* Drop all ACLs related to this table */
		$this->db->delete('_acl_rtp', array(
			'_table' => $menu['db']['name']
		));

		$this->db->delete('_acl_rtcp', array(
			'_table' => $menu['db']['name']
		));

		/* Check if there are any permissions set */
		if (!isset($menu['permissions'])) {
			/* If no permissions are set, insert at least ROLE_ADMIN */
			$menu['permissions'] = array();
			$menu['permissions']['read'] = array('ROLE_ADMIN');

			/* Custom menu types are immutable */
			if ($menu['type'] != 'custom') {
				$menu['permissions']['create'] = array('ROLE_ADMIN');
				$menu['permissions']['update'] = array('ROLE_ADMIN');
				$menu['permissions']['delete'] = array('ROLE_ADMIN');
			}
		} else {
			/* Custom menu types are immutable */
			if ($menu['type'] == 'custom') {
				$menu['permissions']['create'] = array();
				$menu['permissions']['update'] = array();
				$menu['permissions']['delete'] = array();
			}
		}

		/* Create a role translation table */
		$this->db->select('id,role');
		$this->db->from('roles');
		$q = $this->db->get();

		$role_to_id = array();

		foreach ($q->result_array() as $row)
			$role_to_id[$row['role']] = $row['id'];

		/* Create a role permission array */
		$roles = array();

		/* ... CREATE */
		if (isset($menu['permissions']['create'])) {
			foreach ($menu['permissions']['create'] as $role) {
				if (!isset($roles[$role_to_id[$role]]))
					$roles[$role_to_id[$role]] = '';

				$roles[$role_to_id[$role]] .= 'C';
			}
		}

		/* ... READ */
		if (isset($menu['permissions']['read'])) {
			foreach ($menu['permissions']['read'] as $role) {
				if (!isset($roles[$role_to_id[$role]]))
					$roles[$role_to_id[$role]] = '';

				$roles[$role_to_id[$role]] .= 'R';
			}
		}

		/* ... UPDATE */
		if (isset($menu['permissions']['update'])) {
			foreach ($menu['permissions']['update'] as $role) {
				if (!isset($roles[$role_to_id[$role]]))
					$roles[$role_to_id[$role]] = '';

				$roles[$role_to_id[$role]] .= 'U';
			}
		}

		/* ... DELETE */
		if (isset($menu['permissions']['delete'])) {
			foreach ($menu['permissions']['delete'] as $role) {
				if (!isset($roles[$role_to_id[$role]]))
					$roles[$roles[$role_to_id[$role]]] = '';

				$roles[$role_to_id[$role]] .= 'D';
			}
		}

		/* Insert permissions into acl table */
		foreach ($roles as $role_id => $perm) {
			$this->db->insert('_acl_rtp', array(
				'roles_id' => $role_id,
				'_table' => $menu['db']['name'],
				'permissions' => $perm
			));
		}

		/* Process menu fields' permissions */
		foreach ($menu['fields'] as $field) {
			if (!isset($field['permissions'])) {
				/* If no permissions are set, insert at least ROLE_ADMIN */
				$field['permissions'] = array();
				$field['permissions']['read'] = array('ROLE_ADMIN');
				$field['permissions']['search'] = array('ROLE_ADMIN');

				/* Fields from custom menu types are immutable */
				if ($menu['type'] != 'custom') {
					$field['permissions']['create'] = array('ROLE_ADMIN');
					$field['permissions']['update'] = array('ROLE_ADMIN');
				}
			} else {
				/* Fields from custom menu types are immutable */
				if ($menu['type'] == 'custom') {
					$field['permissions']['create'] = array();
					$field['permissions']['update'] = array();
				}
			}

			/* Reset role perm list */
			$roles = array();

			/* ... CREATE */
			if (isset($field['permissions']['create'])) {
				foreach ($field['permissions']['create'] as $role) {
					if (!isset($roles[$role_to_id[$role]]))
						$roles[$role_to_id[$role]] = '';

					$roles[$role_to_id[$role]] .= 'C';
				}
			}

			/* ... READ */
			if (isset($field['permissions']['read'])) {
				foreach ($field['permissions']['read'] as $role) {
					if (!isset($roles[$role_to_id[$role]]))
						$roles[$role_to_id[$role]] = '';

					$roles[$role_to_id[$role]] .= 'R';
				}
			}

			/* ... UPDATE */
			if (isset($field['permissions']['update'])) {
				foreach ($field['permissions']['update'] as $role) {
					if (!isset($roles[$role_to_id[$role]]))
						$roles[$role_to_id[$role]] = '';

					$roles[$role_to_id[$role]] .= 'U';
				}
			}

			/* ... SEARCH */
			if (isset($field['permissions']['search'])) {
				foreach ($field['permissions']['search'] as $role) {
					if (!isset($roles[$role_to_id[$role]]))
						$roles[$role_to_id[$role]] = '';

					$roles[$role_to_id[$role]] .= 'S';
				}
			}

			/* Insert field permissions into acl table */
			foreach ($roles as $role_id => $perm) {
				$this->db->insert('_acl_rtcp', array(
					'roles_id' => $role_id,
					'_table' => $menu['db']['name'],
					'_column' => $field['db']['name'],
					'permissions' => $perm
				));
			}
		}

		/* Finally, insert the required permissions for 'id' field for each role */
		foreach ($roles as $role_id => $perm) {
			$this->db->insert('_acl_rtcp', array(
				'roles_id' => $role_id,
				'_table' => $menu['db']['name'],
				'_column' => 'id',
				'permissions' => 'CRUS'
			));
		}

		/* All good */
		return true;
	}

	private function _process_menu_controller_recreate($menu) {
		/* Check if there is already a controller for this menu */
		if (!file_exists(SYSTEM_BASE_DIR . '/application/controllers/' . $menu['db']['name'] . '.php')) {
			/* .. If not, craft a brand new controller. TODO: FIXME: Add correct authorship and the IDE Builder version used to generated the file */
			$controller_content = '' .
				'<?php if (!defined(\'FROM_BASE\')) { header(\'HTTP/1.1 403 Forbidden\'); die(\'Invalid requested path.\'); }' . "\n" .
				'/*' . "\n" .
				' * This file was generated by ND PHP Framework (www.nd-php.org)' . "\n" .
				' *' . "\n" .
				' * ND PHP Framework - An handy PHP Framework (www.nd-php.org)' . "\n" .
				' * Copyright (C) 2015-' . date('Y') . '  ND PHP Framework' . "\n" .
				' *' . "\n" .
				' * This program is free software: you can redistribute it and/or modify' . "\n" .
				' * it under the terms of the GNU General Public License as published by' . "\n" .
				' * the Free Software Foundation, either version 3 of the License, or' . "\n" .
				' * (at your option) any later version.' . "\n" .
				' *' . "\n" .
				' * This program is distributed in the hope that it will be useful,' . "\n" .
				' * but WITHOUT ANY WARRANTY; without even the implied warranty of' . "\n" .
				' * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the' . "\n" .
				' * GNU General Public License for more details.' . "\n" .
				' *' . "\n" .
				' * You should have received a copy of the GNU General Public License' . "\n" .
				' * along with this program.  If not, see <http://www.gnu.org/licenses/>.' . "\n" .
				' *' . "\n" .
				' */' . "\n" .
	 			'' . "\n" .
				'class ' . ucfirst($menu['db']['name']) . ' extends ND_Controller {' . "\n" .
				'	/*************************************************/' . "\n" .
				'	/*  DO NOT TOUCH THE FOLLOWING RESERVED REGIONS  */' . "\n" .
				'	/*************************************************/' . "\n" .
				'' . "\n" .
				'' . "\n" .
				'	/*** BEGIN IDE REGION ***/' . "\n" .
				'' . "\n" .
				'	/*** END IDE REGION ***/' . "\n" .
				'' . "\n" .
				'' . "\n" .
				'	/*** BEGIN USER REGION ***/' . "\n" .
				'' . "\n" .
				'	/*** END USER REGION ***/' . "\n" .
				'' . "\n" .
				'' . "\n" .
				'	/**************************************************/' . "\n" .
				'	/*  MODIFICATIONS ARE ALLOWED BELOW THIS COMMENT  */' . "\n" .
				'	/**************************************************/' . "\n" .
				'' . "\n" .
				'}';

			/* Create controller file. TODO: FIXME: Error checking missing.... */
			$cfile = fopen(SYSTEM_BASE_DIR . '/application/controllers/' . $menu['db']['name'] . '.php', "w");

			/* Write contents */
			fwrite($cfile, $controller_content);
			fflush($cfile);

			/* Close it */
			fclose($cfile);
		}

		/* Create the list of fields that should be hidden on respective views */
		$hide_create = array();
		$hide_view = array();
		$hide_edit = array();
		$hide_remove = array();
		$hide_list = array();
		$hide_result = array();
		$hide_search = array();
		$hide_export = array();
		$hide_mixed = array();
		$rel_field_aliases = '';
		$mixed_field_aliases = '';
		$field_aliases = '';

		/* Populate the hiding lists for each visualization type and respective aliases */
		foreach ($menu['fields'] as $field) {
			/* Check if there are visualization settings */
			if (isset($field['visualization'])) {
				/* Populate hiding list */
				if (!$field['visualization']['create'])
					array_push($hide_create, $field['db']['name']);

				if (!$field['visualization']['view'])
					array_push($hide_view, $field['db']['name']);

				if (!$field['visualization']['edit'])
					array_push($hide_edit, $field['db']['name']);

				if (!$field['visualization']['remove'])
					array_push($hide_remove, $field['db']['name']);

				if (!$field['visualization']['list'])
					array_push($hide_list, $field['db']['name']);

				if (!$field['visualization']['result'])
					array_push($hide_result, $field['db']['name']);

				if (!$field['visualization']['search'])
					array_push($hide_search, $field['db']['name']);

				if (!$field['visualization']['export'])
					array_push($hide_export, $field['db']['name']);

				if (!$field['visualization']['mixed'])
					array_push($hide_mixed, $field['db']['name']);
			}

			/* Populate aliases list */
			if ($field['properties']['alias']) {
				/* Evaluate if this is a relational field and if so, process the aliased names */
				if ($field['type'] == 'dropdown' || $field['type'] == 'multiple') {
					$rel_field_aliases .= "\t\t'" . strtolower(str_replace(' ', '_', $field['name'])) . "' => array(\"" . str_replace("\"", "\\\"", $field['properties']['alias']) . "\", NULL, array(1), array('id', 'asc')),\n";
				} else if ($field['type'] == 'mixed') {
					/* Mixed relationship field alias */
					$mixed_field_aliases .= "\t\t'" . strtolower(str_replace(' ', '_', $field['name'])) . "' => \"" . str_replace("\"", "\\\"", $field['properties']['alias']) . "\",\n";
				} else {
					/* This is a regular field type */
					$field_aliases .= "\t\t'" . $field['db']['name'] . "' => \"" . str_replace("\"", "\\\"", $field['properties']['alias']) . "\",\n";
				}
			}
		}

		/* Populate hidden menu entries */
		$hidden_menu_list = 'array(';
		if (count($this->_app_menu_hidden_list)) {
			$hidden_menu_list .= '\'' . implode('\',\'', $this->_app_menu_hidden_list) . '\'';
		}
		$hidden_menu_list .= ')';

		/* Populate aliased menu entries */
		$alias_menu_list = '';
		foreach ($this->_app_menu_aliases_list as $menu_name => $menu_alias) {
			$alias_menu_list .= "\t\t'" . $menu_name . "' => \"" . $menu_alias . "\",\n";
		}

		/* Populate hidden menu entries order */
		$order_menu_list = 'array(';
		if (count($this->_app_menu_order_list)) {
			$order_menu_list .= '\'' . implode('\',\'', $this->_app_menu_order_list) . '\'';
		}
		$order_menu_list .= ')';

		/* Set default options if none is set */
		if (!isset($menu['options']) || !isset($menu['options']['logging'])) {
			$menu['options']['logging'] = true;
			$menu['options']['accounting'] = true;
			$menu['options']['linking'] = true;
		}

		/* Validate if we've all the required values, or assume default ones if they're unset */
		if (!$menu['properties']['rpp'])
			$menu['properties']['rpp'] = 10; /* FIXME: TODO: Fetch from app configuration */

		if (!$menu['properties']['order_field'])
			$menu['properties']['order_field'] = 'id';

		if (!$menu['properties']['order_direction'])
			$menu['properties']['order_direction'] = 'asc';

		$hide_create_str = 'array(\'id\')';
		if ($hide_create) {
			$hide_create_str = 'array(\'id\',' . '\'' . implode('\',\'', $hide_create) . '\')';
		}

		$hide_view_str = 'array()';
		if ($hide_view) {
			$hide_view_str = 'array(\'' . implode('\',\'', $hide_view) . '\')';
		}

		$hide_edit_str = 'array(\'id\')';
		if ($hide_edit) {
			$hide_edit_str = 'array(\'id\',' . '\'' . implode('\',\'', $hide_edit) . '\')';
		}

		$hide_remove_str = 'array()';
		if ($hide_remove) {
			$hide_remove_str = 'array(\'' . implode('\',\'', $hide_remove) . '\')';
		}

		$hide_list_str = 'array()';
		if ($hide_list) {
			$hide_list_str = 'array(\'' . implode('\',\'', $hide_list) . '\')';
		}

		$hide_result_str = 'array()';
		if ($hide_result) {
			$hide_result_str = 'array(\'' . implode('\',\'', $hide_result) . '\')';
		}

		$hide_search_str = 'array()';
		if ($hide_search) {
			$hide_search_str = 'array(\'' . implode('\',\'', $hide_search) . '\')';
		}

		$hide_export_str = 'array()';
		if ($hide_export) {
			$hide_export_str = 'array(\'' . implode('\',\'', $hide_export) . '\')';
		}

		$hide_mixed_create_str = 'array(\'id\')';
		$hide_mixed_edit_str = 'array(\'id\')';
		$hide_mixed_view_str = 'array()';
		$hide_mixed_remove_str = 'array()';
		if ($hide_mixed) {
			$hide_mixed_create_str = 'array(\'id\',' . '\'' . implode('\',\'', $hide_mixed) . '\')';
			$hide_mixed_edit_str = 'array(\'id\',' . '\'' . implode('\',\'', $hide_mixed) . '\')';
			$hide_mixed_view_str = 'array(\'' . implode('\',\'', $hide_mixed) . '\')';
			$hide_mixed_remove_str = 'array(\'' . implode('\',\'', $hide_mixed) . '\')';
		}

		/* Craft the controller IDE reserved region */
		$controller_ide_region = '' . "\n" .
			'	protected $_table_type_view = ' . ($menu['type'] == 'custom' ? 'true' : 'false') . ';' . "\n" .
			'' . "\n" .
			'	protected $_pagination_rpp = ' . $menu['properties']['rpp'] . ';' . "\n" .
			'' . "\n" .
			'	protected $_hide_fields_create = ' . $hide_create_str . ';' . "\n" .
			'	protected $_hide_fields_edit = ' . $hide_edit_str . ';' . "\n" .
			'	protected $_hide_fields_view = ' . $hide_view_str . ';' . "\n" .
			'	protected $_hide_fields_remove = ' . $hide_remove_str . ';' . "\n" .
			'	protected $_hide_fields_list = ' . $hide_list_str . ';' . "\n" .
			'	protected $_hide_fields_result = ' . $hide_result_str . ';' . "\n" .
			'	protected $_hide_fields_search = ' . $hide_search_str . '; // Includes fields searched on searchbar (basic)' . "\n" .
			'	protected $_hide_fields_export = ' . $hide_export_str . ';' . "\n" .
			'' . "\n" .
			'	protected $_hide_menu_entries = ' . $hidden_menu_list . ';' . "\n" .
			'' . "\n" .
			'	protected $_menu_entries_order = ' . $order_menu_list . ';' . "\n" .
			'' . "\n" .
			'	protected $_menu_entries_aliases = array(' . "\n" .
			'		/* Main Menu entries alias */' . "\n" .
					rtrim($alias_menu_list, ",\n") . "\n" .
			'	);' . "\n" .
			'' . "\n" .
			'	/* Aliases for the current table field names */' . "\n" .
			'	protected $_table_field_aliases = array(' . "\n" .
			'		/* \'field\' => \'alias\', */' . "\n" .
					rtrim($field_aliases, ",\n") . "\n" .
			'	);' . "\n" .
			'' . "\n" .
			'	/* Fieldset legend aliases for mixed relationships */' . "\n" .
			'	protected $_mixed_fieldset_legend_config = array(' . "\n" .
			'		/* \'table\' => \'legend\', */' . "\n" .
					rtrim($mixed_field_aliases, ",\n") . "\n" .
			'	);' . "\n" .
			'' . "\n" .
			'	/* The fields to be concatenated as the options of the relationship table. Also the place to set relational field name aliases. */' . "\n" .
			'	protected $_rel_table_fields_config = array(' . "\n" .
			'		/* \'table\' => array(\'ViewName\', \'separator\', array(field_nr_1, field_nr_2, ...), array(\'order_by field\', \'asc or desc\')), */' . "\n" .
					rtrim($rel_field_aliases, ",\n") . "\n" .
			'	);' . "\n" .
			'' . "\n" .
			'	/* Field by which the listing views shall be ordered by */' . "\n" .
			'	protected $_table_field_listing_order = \'' . strtolower($menu['properties']['order_field']) . '\';' . "\n" .
			'' . "\n" .
			'	/* Field by which the result views shall be ordered by */' . "\n" .
			'	protected $_table_field_result_order = \'' . strtolower($menu['properties']['order_field']) . '\';' . "\n" .
			'' . "\n" .
			'	/* Direction by which the listing views shall be ordered by */' . "\n" .
			'	protected $_table_field_listing_order_modifier = \'' . strtolower($menu['properties']['order_direction']) . '\';' . "\n" .
			'' . "\n" .
			'	/* Direction by which the result views shall be ordered by */' . "\n" .
			'	protected $_table_field_result_order_modifier = \'' . strtolower($menu['properties']['order_direction']) . '\';' . "\n" .
			'' . "\n" .
			'	protected $_accounting = ' . ($menu['options']['accounting'] ? 'true' : 'false') . ';' . "\n" .
			'	protected $_logging = ' . ($menu['options']['logging'] ? 'true' : 'false') . ';' . "\n" .
			'	protected $_table_fk_linking = ' . ($menu['options']['linking'] ? 'true' : 'false') . ';' . "\n" .
			'' . "\n" .
			'	/* Mixed Relationship hidden fields per view. */' . "\n" .
			'	protected $_mixed_hide_fields_create = ' . $hide_mixed_create_str . ';' . "\n" .
			'	protected $_mixed_hide_fields_edit = ' . $hide_mixed_edit_str . ';' . "\n" .
			'	protected $_mixed_hide_fields_view = ' . $hide_mixed_view_str . ';' . "\n" .
			'	protected $_mixed_hide_fields_remove = ' . $hide_mixed_remove_str . ';' . "\n" .
			'' . "\n";

		/* Craft the controller USER reserved region */
		$controller_user_region = '' .
				'' . "\n" .
				'	/* Constructor */' . "\n" .
				'	public function __construct($session_enable = true, $json_replies = false) {' . "\n" .
				'		parent::__construct($session_enable, $json_replies);' . "\n" .
				'' . "\n" .
				'		$this->_viewhname = get_class();' . "\n" .
				'		$this->_name = strtolower($this->_viewhname);' . "\n" .
				'		$this->_hide_global_search_controllers = $this->_hide_menu_entries;' . "\n" .
				'		$this->_hook_construct();' . "\n" .
				'	}' . "\n" .
				'' . "\n" .
				'' . "\t" . str_replace("\n", "\n\t", $menu['controller']['code']) . "\n";

		/* Now we need to replace the IDE REGION and USER REGION on the target controller */

		$controller_content = '';
		/* TODO: FIXME: Error checking missing.... */
		$cfile = fopen(SYSTEM_BASE_DIR . '/application/controllers/' . $menu['db']['name'] . '.php', "r");

		while (($line = fgets($cfile)) !== false) {
			$controller_content .= $line;

			/* Locate BEGIN IDE REGION */
			if (strpos($line, '/*** BEGIN IDE REGION ***/')) {
				/* ... start ignoring the contents until we find the END IDE REGION */
				while (($line = fgets($cfile)) !== false) {
					if (strpos($line, '/*** END IDE REGION ***/')) {
						/* END IDE REGION was found... update $controller_content with the new contents */
						$controller_content .= $controller_ide_region;
						$controller_content .= $line; /* Also add the END IDE REGION indicator (comment) */
						break;
					}
				}

				/* Check if we've found (or not) the END IDE REGION */
				if ($line === false) {
					fclose($cfile);
					error_log('_process_menu_controller_recreate(): Unable to locate the END IDE REGION on controller ' . $menu['db']['name'] . '.php');
					return false;
				}
			}

			/* Locate the BEGIN USER REGION */
			if (strpos($line, '/*** BEGIN USER REGION ***/')) {
				/* ... start ignoring the contents until we find the END USER REGION */
				while (($line = fgets($cfile)) !== false) {
					if (strpos($line, '/*** END USER REGION ***/')) {
						/* END USER REGION was found... update $controller_content with the new contents */
						$controller_content .= $controller_user_region;
						$controller_content .= $line; /* Also add the END USER REGION indicator (comment) */
						break;
					}
				}

				/* Check if we've found (or not) the END USER REGION */
				if ($line === false) {
					fclose($cfile);
					error_log('_process_menu_controller_recreate(): Unable to locate the END USER REGION on controller ' . $menu['db']['name'] . '.php');
					return false;
				}
			}
		}

		fclose($cfile);

		/* Recreate controller file (FIXME: TODO: Add error handling to the following file operations) */
		$cfile = fopen(SYSTEM_BASE_DIR . '/application/controllers/' . $menu['db']['name'] . '.php', "w");
		fwrite($cfile, $controller_content);
		fflush($cfile);
		fclose($cfile);

		/* All good */
		return true;
	}

	private function _process_menu_icon_recreate($menu) {
		/* Craft the full (system) path to the static images directory */
		$images_path = SYSTEM_BASE_DIR . '/' . preg_replace('/' . preg_quote(base_dir(), '/') . '/', '', static_images_dir(), 1);

		/* Recreate the menu icon if required */
		if (!isset($menu['properties']['icon'])) {
			/* If it doesn't exist, create a blank icon. */
			return copy($images_path . '/themes/' . $this->_theme . '/menu/iconset/png/96x96/Empty button.png', $images_path . '/menu/' . $menu['db']['name'] . '.png');
		} else if ($menu['properties']['icon'] == "custom") {
			/* If the icon is custom, do not replace it if already present. */
			if (file_exists($images_path . '/menu/' . $menu['db']['name'] . '.png')) {
				return true; /* Icon exists and it's customized... ignoring... */
			} else {
				/* If it doesn't exist, create a blank icon. */
				return copy($images_path . '/themes/' . $this->_theme . '/menu/iconset/png/96x96/Empty button.png', $images_path . '/menu/' . $menu['db']['name'] . '.png');
			}
		} else {
			/* A specific icon was selected... replace the menu icon with it... */
			return copy($images_path . '/themes/' . $this->_theme . '/menu/iconset/png/96x96/' . $menu['properties']['icon'], $images_path . '/menu/' . $menu['db']['name'] . '.png');
		}

		return false;
	}

	private function _process_menu_type_conversions($menu) {
		/* Convert menu type if required */
		return $this->_convert_db_table($menu['db']['name'], $menu['type']);
	}

	private function _process_menus($menus) {
		/* Remove deleted objects */
		foreach ($menus as $menu) {
			/* If the menu object exists, process changes */
			if ($this->_object_exists($menu['obj_id'])) {
				if (!$this->_process_menu_changes($menu)) {
					error_log('_process_menus(): _process_menu_changes(\'' . $menu['name'] . '\'): Failed.');
					return false;
				}
			} else {
				/* ... otherwise create it from scratch */
				if (!$this->_process_menu_create($menu)) {
					error_log('_process_menus(): _process_menu_create(\'' . $menu['name'] . '\'): Failed.');
					return false;
				}
			}

			/* Process menu fields */
			if (!$this->_process_menu_fields($menu)) {
				error_log('_process_menus(): _process_menu_fields(\'' . $menu['name'] . '\'): Failed.');
				return false;
			}

			/* After processing all fields, we need to check the table type and perform the required conversions */
			if (!$this->_process_menu_type_conversions($menu)) {
				error_log('_process_menus(): _process_menu_type_conversions(\'' . $menu['name'] . '\'): Failed.');
			}

			/* Always recreate the help data, acl and controllers */
			if (!$this->_process_menu_help_recreate($menu)) {
				error_log('_process_menus(): _process_menu_help_recreate(\'' . $menu['name'] . '\'): Failed.');
				return false;
			}

			if (!$this->_process_menu_acl_recreate($menu)) {
				error_log('_process_menus(): _process_menu_acl_recreate(\'' . $menu['name'] . '\'): Failed.');
				return false;
			}

			if (!$this->_process_menu_controller_recreate($menu)) {
				error_log('_process_menus(): _process_menu_controller_recreate(\'' . $menu['name'] . '\'): Failed.');
				return false;
			}

			if (!$this->_process_menu_icon_recreate($menu)) {
				error_log('_process_menus(): _process_menu_icon_recreate(\'' . $menu['name'] . '\'): Failed.');
				return false;
			}
		}

		/* Commit postponed database changes */
		$this->_pp_batch_db_commit();

		/* All good */
		return true;
	}


	/***********************/
	/*  PRE-PROCESSOR API  */
	/***********************/

	private function _menu_safe_chars($menu) {
		if (!preg_match('/^[a-zA-Z0-9_\ ]+$/', $menu['name']))
			return false;

		return true;
	}

	private function _field_safe_chars($field) {
		if (!preg_match('/^[a-zA-Z0-9_\ ]+$/', $field['name']))
			return false;

		return true;
	}

	private function _field_has_length_set($field) {
		return (isset($field['properties']['len']) && $field['properties']['len']);
	}

	private function _field_name_translate_to_database($menu_name, $field) {
		switch ($field['type']) {
			case 'file'		: return '_file_' . str_replace(' ', '_', strtolower($field['name']));
			case 'timer'	: return '_timer_' . str_replace(' ', '_', strtolower($field['name']));
			case 'separator': return '_separator_' . str_replace(' ', '_', strtolower($field['name']));
			case 'dropdown'	: return str_replace(' ', '_', strtolower($field['name'])) . '_id';
			case 'multiple'	: {
				/* Craft relationship table name */
				$rtable = 'rel_' . str_replace(' ', '_', strtolower($menu_name)) . '_' . str_replace(' ', '_', strtolower($field['name']));

				/* Store the components of the relationship table for future reference */
				$this->_table_rel_add_components('multiple', $rtable, str_replace(' ', '_', strtolower($menu_name)), str_replace(' ', '_', strtolower($field['name'])));

				/* Return the relationship table name */
				return $rtable;
			} break;
			case 'mixed'	: {
				/* Craft relationship table name */
				$rtable = 'mixed_' . str_replace(' ', '_', strtolower($menu_name)) . '_' . str_replace(' ', '_', strtolower($field['name']));

				/* Store the components of the relationship table for future reference */
				$this->_table_rel_add_components('mixed', $rtable, str_replace(' ', '_', strtolower($menu_name)), str_replace(' ', '_', strtolower($field['name'])));

				/* Return the relationship table name */
				return $rtable;
			} break;
		}

		/* multiple and mixed are not fields but special tables, so they'll have special treatment later in _process_menu_fields() */

		/* Everything else will just be converted to lowercase */
		/* Also, If the field has the 'hidden' constraint set, add a '_' prefix to it */
		return str_replace(' ', '_', strtolower($field['name']));
	}

	private function _field_type_translate_to_database(&$field) {
		switch ($field['type']) {
			case 'text'		: 	if ($this->_field_has_length_set($field)) {
									if ($field['properties']['len'] > 65532) {
										return 'text';
									} else {
										return 'varchar(' . $field['properties']['len'] . ')';
									}
								}
								return 'varchar(255)';
			case 'numeric'	: 	if ($this->_field_has_length_set($field)) {
									/* Replace any '.' character with ',' for coherency */
									$field['properties']['len'] = str_replace('.', ',', $field['properties']['len']);

									/* Check if this is a decimal number */
									if (strpos($field['properties']['len'], ',')) {
										/* Decide if we'll use decimal or double */
										$decimal_size = explode(',', '0' . $field['properties']['len']);

										/* If any of the decimal parts is 0, use float */
										if (!abs(intval($decimal_size[0])) || !abs(intval($decimal_size[1])))
											return 'float';

										/* .. otherwise use decimal() */
										return 'decimal(' . $field['properties']['len'] . ')';
									} else if ($field['properties']['len'] == 1) {
										return 'tinyint(' . $field['properties']['len'] . ')';
									} else {
										return 'int(' . abs(intval($field['properties']['len'])) . ')';
									}
								}
								return 'int(11)';
			case 'time'		: 	return 'time';
			case 'date'		: 	return 'date';
			case 'datetime'	: 	return 'datetime';
			case 'dropdown'	: 	return 'int(11)'; /* Will link to 'id' field of foreign table, which is also int(11) */
			case 'multiple'	: 	return 'multiple'; /* This isn't a field but a special table... so there's no native type for it */
			case 'mixed'	: 	return 'mixed'; /* This isn't a field but a special table... so there's no native type for it */
			case 'timer'	: 	return 'time';
			case 'file'		: 	return 'varchar(255)'; /* FIXME: We should use the length defined in the field properties */
			case 'separator':	return 'tinyint(1)';
		}
	}

	private function _field_require_keys($field) {
		switch ($field['type']) {
			case 'dropdown'	: return true;
			case 'multiple'	: return true;
			case 'mixed'	: return true;
		}

		return false;
	}

	private function _field_type_is_database_table($field) {
		switch ($field['type']) {
			case 'multiple'	: return true;
			case 'mixed'	: return true;
		}

		return false;
	}

	private function _field_is_nullable($field) {
		return !(isset($field['constraints']['required']) && $field['constraints']['required']);
	}

	private function _field_is_unique($field) {
		if (isset($field['constraints']['unique']) && $field['constraints']['unique'] === true) {
			/* Only the following field types can assume uniqueness */
			switch ($field['type']) {
				case 'text'		: return true;
				case 'numeric'	: return true;
			}
		}

		return false;
	}

	private function _create_application_object_list($app_model) {
		/* Creates an array of all application objects */
		$obj_list = array();

		foreach ($app_model['menus'] as $menu_item) {
			array_push($obj_list, $menu_item['obj_id']);

			foreach ($menu_item['fields'] as $field_item) {
				array_push($obj_list, $field_item['obj_id']);
			}
		}

		return $obj_list;
	}

	private function _pre_process_model(&$app_model) {
		/* Pre-process the application model, adding database information to each menu and field item */

		/* Create a list of all application model objects */
		$app_model['obj_list'] = $this->_create_application_object_list($app_model);

		/* Create database information */
		for ($i = 0; $i < count($app_model['menus']); $i ++) {
			/* Validate menu name */
			if ($this->_menu_safe_chars($app_model['menus'][$i]) !== true) {
				error_log('_pre_process_model(): Menu "' . $app_model['menus'][$i]['name'] . '" contains invalid characters.');
				return false;
			}

			/* Array to store database information */
			$app_model['menus'][$i]['db'] = array();

			/* Set database name for this table. NOTE: Detached tables shall use a '_' prefix */
			$app_model['menus'][$i]['db']['name'] = ($app_model['menus'][$i]['type'] == 'detached' ? '_' : '') . str_replace(' ', '_', strtolower($app_model['menus'][$i]['name']));

			/* Process table columns */
			for ($j = 0; $j < count($app_model['menus'][$i]['fields']); $j ++) {
				/* Validate field name */
				if ($this->_field_safe_chars($app_model['menus'][$i]['fields'][$j]) !== true) {
					error_log('_pre_process_model(): Field "' . $app_model['menus'][$i]['fields'][$j] . '" from menu "' . $app_model['menus'][$i] . '" contains invalid characters.');
					return false;
				}

				/* Array to store database information */
				$app_model['menus'][$i]['fields'][$j]['db'] = array();
				/* Resolve column type */
				$app_model['menus'][$i]['fields'][$j]['db']['type'] = $this->_field_type_translate_to_database($app_model['menus'][$i]['fields'][$j]);
				/* Set database table column name */
				$app_model['menus'][$i]['fields'][$j]['db']['name'] = $this->_field_name_translate_to_database(strtolower($app_model['menus'][$i]['name']), $app_model['menus'][$i]['fields'][$j]);
				/* Indicate if there are keys to be created for this column */
				$app_model['menus'][$i]['fields'][$j]['db']['has_keys'] = $this->_field_require_keys($app_model['menus'][$i]['fields'][$j]);
				/* Indicate if this is a relational table instead of a database table column */
				$app_model['menus'][$i]['fields'][$j]['db']['is_table'] = $this->_field_type_is_database_table($app_model['menus'][$i]['fields'][$j]);
				/* Is this column required? */
				$app_model['menus'][$i]['fields'][$j]['db']['is_nullable'] = $this->_field_is_nullable($app_model['menus'][$i]['fields'][$j]);
				/* Is this column unique? */
				$app_model['menus'][$i]['fields'][$j]['db']['is_unique'] = $this->_field_is_unique($app_model['menus'][$i]['fields'][$j]);

				/* FIXME: TODO: Set the default value */
				if (isset($app_model['menus'][$i]['fields'][$j]['properties']['default_value']) && $app_model['menus'][$i]['fields'][$j]['properties']['default_value']) {
					$app_model['menus'][$i]['fields'][$j]['db']['default'] = $app_model['menus'][$i]['fields'][$j]['properties']['default_value'];

					/* If the field isn't of type integer, the default value must be quoted */
					if (!strpos($app_model['menus'][$i]['fields'][$j]['db']['type'], 'int'))
						$app_model['menus'][$i]['fields'][$j]['db']['default'] = '\'' . $app_model['menus'][$i]['fields'][$j]['db']['default'] . '\'';
				} else {
					/* Set default value to NULL */
					$app_model['menus'][$i]['fields'][$j]['db']['default'] = 'NULL';

					/* Check if the column is nullable, since the default value was set to NULL */
					if (!$app_model['menus'][$i]['fields'][$j]['db']['is_nullable']) {
						/* TODO: FIXME: If this field is not nullable (required), the placeholder shall not be NULL */
						/* We currently force the column to be nullable if no placeholder is set */
						$app_model['menus'][$i]['fields'][$j]['db']['is_nullable'] = true;
					}
				}

				/* After which field? */
				if ($j) {
					/* We need to grant that $j isn't pointing to a multiple nor mixed type field, as they are not field but tables */
					for ($k = $j - 1; $k > 0; $k --) {
						if ($app_model['menus'][$i]['fields'][$k]['type'] == 'multiple' || $app_model['menus'][$i]['fields'][$k]['type'] == 'mixed')
							continue;

						break;
					}

					/* If this isn't the first field, it will be placed after the previous one */
					$app_model['menus'][$i]['fields'][$j]['db']['after'] = $app_model['menus'][$i]['fields'][$k]['db']['name'];
				} else {
					/* If this is the first field, it will be placed after the 'id' field */
					$app_model['menus'][$i]['fields'][$j]['db']['after'] = 'id';
				}
			}
		}

		return true;
	}


	/****************/
	/*  PUBLIC API  */
	/****************/

	public function deploy_model($app_model = NULL) {
		/* No model, no fun :() */
		if ($app_model === NULL) {
			error_log('process_model(): $application_model is NULL');
			return false;
		}

		/* Pre-process application model, validating and computing required information before processing changes */
		if ($this->_pre_process_model($app_model) !== true) {
			error_log('process_model(): _pre_process_model(): Failed.');
			return false;
		}

		/* Start processing changes */
		$this->db->trans_begin();

		/* Rmove database objects that do not exist on $app_model */
		$this->_object_remove_non_existent($app_model['obj_list']);

		/* Pre-compute some data */
		$this->_app_compute_pre($app_model);

		/* Process menus (Create or Change) */
		if (!$this->_process_menus($app_model['menus'])) {
			$this->db->trans_rollback();
			error_log('deploy_model(): _process_menus(): Failed.');
			return false;
		}

		/* Test if everything is OK ... */
		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			error_log('deploy_model(): Unable to commit changes to database.');
			return false;
		}

		/* Commit changes */
		$this->db->trans_commit();

		return $app_model;
	}

	public function generate_model() {
		$application_model = array();

		header('HTTP/1.1 403 Forbidden');
		die('Not yet implemented.');

		/* TODO: Create the application model based on database structure and controller data */

		return $model;
	}
}
