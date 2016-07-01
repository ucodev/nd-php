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
 * * IDE application model should validate everything that was previously validated by ide.js.
 * * [IN_PROGRESS] Database Sharding (per user).
 * * Add support for memcached to lower database overhead.
 * * Turn UI responsive.
 * * Forward/Back button should work with partial page updates (when performed via ajax).
 * * Refresh button should refresh the the last updated contents (even if performed via ajax).
 * * Add command line to IDE Builder.
 * * Controller methods such as insert() and update() when detect invalid data should return the offending fields back to the view ajax error handler.
 * * timer fields shall still count time even if the interface is closed without hiting the stop button.
 * * Add special extra field/query pairs to _field_resolve() and _field_value_mangle()
 * * Add a rollback context menu button on Logging (on View and Quick View) [currently only a Quick Rollback button is present on Listing/Result].
 * * Export View should export mixed relationships.
 * * Export View should export charts.
 * * On-the-fly field edit support [Views] (Double-click on a field value to edit it on the fly)
 * * Per user locale support.
 * * Per user charset support.
 * * Currency (per user) support
 * * Implement guest user support. Authentication for guest user is done automatically for controllers allowing it. This user must be disabled by default.
 * * Total mixed entries as a list_default() / result() field.
 * - Migrate Modalbox to something else (probably jQuery UI Dialog?).
 * - Implement different families of views (configurable per table: backend, blog, shop, page, etc...)
 * - Autocomplete fields (and matching fields) based on the values of a foreign table. (with a simple API)
 * - [POSTPONED] Support a table name resolver to implement quality and development platforms under the same app infrastructure:
 *  - [POSTPONED] Development users will have full separate tables and wont share real-time production data.
 *  - [POSTPONED] Quality users will share the production tables, except for _acl_* and _help_* tables.
 * - Implement the describe() method for table description (with role permission checking) for JSON RESTful API support
 * - Password recovery should be performed via a recovery form and not by sending a new password via email.
 * - CSV export support on view_generic() is missing.
 * - Javascript Library with ajax calls to fetch data from the controllers in order to integrate and populate a third-party/detached website with the framework.
 * - Implement Dashboards (Imports of other views, including charts, to a Dashboard view).
 * - Add export support for grouping views.
 * - Implement Clone operation (under entry view).
 * - Users should be able to disable some features (such as accessbility) even if [globally] activated by administrator.
 * - Add per role restrictions for List/Groups/Result/Export visualizations / sub menu access
 * - Add support for context awareness under hooks (the hook should know which preceeding operations (workflow) preceeded their invocation)
 * - Support edition on previously saved searches.
 * - Improve UI accessibility with ARIA attributes
 *
 * FIXME:
 *
 * + Chart generators should attempt to trigger both list and result filter hook.
 + + Chart imagemaps display incorrect captions on bar charts.
 * + Missing multiple and mixed relationship logging support (only basic fields are supported).
 * + On REST insert/update functions, when processing single, multiple and mixed relationships, evaluate if the value(s) are integers.. if not, translate the string value based on foreign table contents (useful for REST API calls).
 * + Browsing history (from browsing actions) should be cleaned up from time to time (eg, store only the last 20 or so entries)
 * * Framework core tables should be prefixed with nd_*
 * * Grouping features (Group views) requires a huge refactoring (current performance is very poor).
 * * Mixed and multiple relationships must work properly when javascript is disabled.
 * * Advanced search must work properly when javascript is disabled.
 * * Saved searches results do not have full breadcrumb support.
 * * Home controller do not currently have support for breadcrumb.
 * * Search form user data should be saved (and loaded when search form is loaded again). A form reset button must also be implemented.
 * * Input patterns not being validated on mixed relationship fields under controller code (insert() and update()).
 * * Fix advanced search form reset after a back (from browsing actions) is performed after a search is submited.
 * * When groups contain no data, a message "No entries found for this group" should be displayed in the group listing/results.
 * * Export to pdf (on listing/results) is not rendering images from _file_* fields.
 * * Advanced search: "Different than" option for mixed searches is currently bugged (due to the enclosure hack) and shall not be used as it'll return incorrect results
 * * Mixed relationship edits are retrieving the newer values of dropdowns (instead of reading the old static values)
 * * Mixed relationship _timer_ not fully supported (missing start and stop buttons).
 * * Paypal payments interface is outdated.
 *
 *
 */

class ND_Controller extends UW_Controller {
	/** Configuration **/
	public $config = array(); /* Will be populated in constructor */

	/* Framework version */
	protected $_ndphp_version = '0.02i';

	/* The controller name and view header name */
	protected $_name;				// Controller segment / Table name (must be lower case)
	protected $_viewhname;			// The name used in the view headers

	/* The default words (natural language) that will be used to indicate True and False values to the user */
	protected $_word_true = NDPHP_LANG_MOD_WORD_TRUE;
	protected $_word_false = NDPHP_LANG_MOD_WORD_FALSE;

	/* Built-in features (not yet configurable through features configuration menu) */
	protected $_logging = true;
	protected $_accounting = true;

	/* Default settings */
	protected $_default_charset = NDPHP_LANG_MOD_DEFAULT_CHARSET;
	protected $_default_timezone = NDPHP_LANG_MOD_DEFAULT_TIMEZONE;
	protected $_default_locale = NDPHP_LANG_MOD_DEFAULT_LOCALE;
	protected $_default_database = 'default';
	protected $_default_theme = 'Blueish';

	/* Project information */
	protected $_project_author = "ND PHP Framework";			// Project Author
	protected $_project_name = "ND php";						// The project name
	protected $_project_tagline = "Framework";					// The project tagline
	protected $_project_description = "An handy PHP Framework";	// The project description

	/* Truncate strings settings */
	protected $_string_truncate_len = 40;
	protected $_string_truncate_trail = 5;
	protected $_string_truncate_sep = '...';

	/* Base URL for this application */
	protected $_base_url = 'http://localhost/ndphp/';

	/* Temporary directory */
	protected $_temp_dir = SYSTEM_BASE_DIR . '/tmp/';

	/* Main Menu entries alias */
	protected $_menu_entries_aliases = array();

	/* Main menu entries order */
	protected $_menu_entries_order = array();

	/* Hidden Main Menu entries */
	protected $_hide_menu_entries = array();

	/* Global Search - Controller filter */
	protected $_hide_global_search_controllers = array();

	/* Hidden fields per view.
	 *
	 * Note that for relationship fields, the field name used here must be the one
	 * corresponding to the foreign table field.
	 * 
	 */
	protected $_hide_fields_create = array('id');
	protected $_hide_fields_edit   = array('id');
	protected $_hide_fields_view   = array();
	protected $_hide_fields_remove = array();
	protected $_hide_fields_list   = array();
	protected $_hide_fields_result = array();
	protected $_hide_fields_search = array(); // Includes fields searched on searchbar (basic)
	protected $_hide_fields_export = array();
	protected $_hide_fields_groups = array(); /* Also check the $_hide_groups setting */

	/* Array of single relationship fields or multiple relationship tables that should not be considered for
	 * groups processing (all entries must end with _id suffix or start with rel_ prefix).
	 */
	protected $_hide_groups = array();	/* Eg: array('users_id', 'rel_users_roles') */

	/* Aliases for the current table field names */
	protected $_table_field_aliases = array(
		/* 'field' => 'alias', */
	);

	/* Rich text editing (Fields for this array must be of type text) */
	protected $_table_field_text_rich = array(
		/* 'text_field1', 'text_field2' */
	);

	/* Field by which the listing views shall be ordered by */
	protected $_table_field_order_list = 'id';

	/* Field by which the result views shall be ordered by */
	protected $_table_field_order_result = 'id';

	/* Direction by which the listing views shall be ordered by */
	protected $_table_field_order_list_modifier = 'asc';

	/* Direction by which the result views shall be ordered by */
	protected $_table_field_order_result_modifier = 'asc';

	/* If enabled, show only the rows of configured tables that have a particular
	 * field name that matches a particular session variable.
	 *
	 * For example, if a table has a field named 'users_id' and you want to filter
	 * the listings/results (and other accesses like edit,delete,update) based on
	 * the logged on user, define a session variable named 'user_id' and uncomment
	 * the example in $_table_row_filtering_config array.
	 *
	 * The cofiguration map is made in $_table_row_filtering_config
	 */
	protected $_table_row_filtering = true;

	/* Table Row filtering configuration */
	protected $_table_row_filtering_config = array(
		/* 'table field' => 'session var' */
		'users_id' => 'user_id'
	);

	/* Pagination */
	protected $_table_pagination_rpp_list = 10;
	protected $_table_pagination_rpp_result = 10;

	/* Anchor foreign key values
	 * This option will enable or disable the links (anchors) created in the lists/results
	 * for foreign fields. Default is enabled (true).
	 * NOTE:
	 * This will resolve the values to links by crawling through the field options, comparing
	 * each one with the current value (quadratic algorithm... to be redesigned). 
	 * On high entry density foreign tables (tables with a high amount of rows),
	 * it is recommended to set this option to false (disabled), or a significant
	 * performance impact will be noticed.
	 */
	protected $_table_fk_linking = true;

	/* If this controller is associated to a DATABASE VIEW instead of a DATABASE TABLE, set the following variable to true */
	protected $_table_type_view = false;

	/* The query that will generate the view (requires $_table_type_view set to true) */
	protected $_table_type_view_query = ''; /* Eg: 'SELECT * FROM users WHERE id > 1' */

	/* The fields to be concatenated as the options of the relationship table. Also the place to set relational field name aliases. */
	protected $_rel_table_fields_config = array(
		/* 'table' => array('ViewName', 'separator', array(field_nr_1, field_nr_2, ...), array('order_by field', 'asc or desc')), */
	); /* TODO: If the selected field is of type _id (relationship), it won't be
	 	* resolved from the id number to the related table field. This is a very complex
	 	* feature to be implemented as it requires a cross controller process, capable
	 	* of recursively analyze the relationship configuration options of each table
	 	* involved, which will make the framework extremelly slow if it's not carefully
	 	* implemented.
	 	*
	 	* TODO: Field names should be accepted on 3rd array element, instead of using field numbers.
	 	*
	 	*/

	/* The separator to be used when MySQL GROUP_CONCAT() is invoked */
	protected $_rel_group_concat_sep = ' | ';

	/* Table relational choices (conditional drop-down) */
	protected $_rel_choice_hide_fields = array(
		/* 'field_id' => array(
		 * 		1 => array('field_to_hide1', 'field_to_hide2'),
		 * 		7 => array('field_to_hide3', 'field_to_hide8'),
		 * 		...
		 * )
		 */
	);

	protected $_rel_choice_hide_fields_create = array();
	protected $_rel_choice_hide_fields_edit   = array();
	protected $_rel_choice_hide_fields_view   = array();
	protected $_rel_choice_hide_fields_remove = array();

	/* Set a custom class for table row based on single relationship field values.
	 * Any class specified here must exist in a loaded CSS, with the following prefixes:
	 *
	 *   list_<class_suffix_name>
	 *   result_<class_suffix_name>
	 *   export_<class_suffix_name>
	 *
	 * Example:
	 *
	 *   tr.list_even,   tr.result_even,   tr.export_even   { ... }
	 *   tr.list_odd,    tr.result_odd,    tr.export_odd    { ... }
	 *   tr.list_red,    tr.result_red,    tr.export_red    { ... }
	 *   tr.list_yellow, tr.result_yellow, tr.export_yellow { ... }
	 *   tr.list_green,  tr.result_green,  tr.export_green  { ... }
	 *
	 * NOTE: The :hover modifier should also be set for list_ and result_ classes.
	 *
	 * There are already some predefined classes in main.css: odd, even, green, red, yellow, blue, orange and black
	 *
	 */
	protected $_rel_choice_table_row_class = array(
		/*
		'rel_field' => 'field_id',
		'class_even' => 'even',
		'class_odd' => 'odd',
		'values' => array(
			'CRITICAL' => 'red',
			'WARNING' => 'yellow',
			'OK' => 'green'
		)
		*/
	);

	/* Field name configuration for mixed relationships */
	protected $_mixed_table_fields_config = array(
		/* 'table' => array(field_nr1 => 'ViewName', field_nr2 => 'ViewName', ...), ...*/
	);

	/* Fieldset legend aliases for mixed relationships */
	protected $_mixed_fieldset_legend_config = array(
		/* 'table' => 'legend' */
	);

	/* If set to true, inserts user values into the foreign table if they do not exist.
	 * Also, if set to true, this option will cause the framework to ignore the $_mixed_table_set_missing settings.
	 */
	protected $_mixed_table_add_missing = true;

	/* When a mixed entry does not belong to any table row of the foreign table associated to the mixed relationship to
	 * be inserted or updated (this is, when a <foreign table>_id value is missing due to autocompletion is disabled or
	 * wasn't used by the user), we can force a default foreign table id value to be set, on a per-foreign-table basis.
	 *
	 * The framework will check the following array for any default id value set for the foreign table in case of
	 * such id is missing when inserting or updating mixed relationship entries.
	 *
	 */
	protected $_mixed_table_set_missing = array(
		/* 'table' => id */
	);

	/* Ajust the views of mixed relationship field widths (forced on element style= attribute) */
	protected $_mixed_table_fields_width = array(
		/*
		'field1' => '32px',
		'field2' => '250px'
		*/
	);

	/* Enable (set to true) or Disable (set to false) mixed relationships create/edit views autocompletation */
	protected $_mixed_views_autocomplete = true;

	/* Mixed Relationship hidden fields per view.
	 * TODO: FIXME: This is not fully supported by IDE Builder (it adds the field to be hidden to all four arrays below).
	 *              Also _get_fields() is now filtering foreign table fields based on application model configuration, which
	 *              means that, regardless of the view type (create/edit/view/remove), if a field isn't marked as visible
	 *              on Mixed checkbox (on the IDE Builder), it won't show up on any of the four views.
	 *              Note that maybe there's no effective advantage in implementing this segregation... probably if a field
	 *              is inteeded to be hiden in a mixe relationship view, it'll probably make sense to hide it in all views...
	 */
	protected $_mixed_hide_fields_create = array('id');
	protected $_mixed_hide_fields_edit   = array('id');
	protected $_mixed_hide_fields_view   = array();
	protected $_mixed_hide_fields_remove = array();

	/* CSV configuration */
	protected $_csv_sep = ',';	/* Default field separator */
	protected $_csv_delim = "\""; /* Default string delimiter */
	protected $_csv_from_encoding = NDPHP_LANG_MOD_DEFAULT_CHARSET;
	protected $_csv_to_encoding   = NDPHP_LANG_MOD_DEFAULT_CHARSET;

	/* Main tab name for CRUD views */
	protected $_view_crud_main_tab_name = NDPHP_LANG_MOD_TABS_TITLE_MAIN_GENERIC;
	protected $_view_crud_charts_tab_name = NDPHP_LANG_MOD_TABS_TITLE_MAIN_CHARTS;

	/* Title component separator */
	protected $_view_title_sep = ' - ';

	/* Breadcrumb component separator */
	protected $_view_breadcrumb_sep = ' - ';

	/* Append the following entry fields to view title (only effective on view, remove and edit views).
	 * By default, only the 'id' field value is appended to view title. Multiple fields can be specified
	 * in this array. Each field value will be separated from each other with the $_view_title_append_sep
	 * value.
	 */
	protected $_view_title_append_fields = array('id');

	/* The string value that will be used in the concatenation of $_view_title_append_fields values. */
	protected $_view_title_append_sep = ' - ';

	/* Image rendering - Render images on _file_* typed fields, where mime type matches an image format */
	protected $_view_image_file_rendering = true;

	/* Image rendering - Acceptable extensions - FIXME: Currently only extension is being checked, but MIME types must also be validated */
	protected $_view_image_file_rendering_ext = array('jpg', 'gif', 'png', 'ico', 'bmp', 'svg');

	/* The image scaling for the embedded images under the list / result views */
	protected $_view_image_file_rendering_size_list = array(
		'width'  => '32px',
		'height' => '32px'
	);

	/* The image scaling for the embedded images under the entry views */
	protected $_view_image_file_rendering_size_view = array(
		'width'  => '256px',
		'height' => '256px'
	);

	/* Quick Operations Links (Listing and Result views) */
	protected $_links_quick_modal_list = array(
		/* array('Description', $sec_perm, method, 'image/path/img.png', $modal_width) */
		array(NDPHP_LANG_MOD_OP_QUICK_VIEW,		'R', 'view_data_modalbox',   'icons/quick_view.png',   600),
		array(NDPHP_LANG_MOD_OP_QUICK_EDIT,		'U', 'edit_data_modalbox',   'icons/quick_edit.png',   900),
		array(NDPHP_LANG_MOD_OP_QUICK_REMOVE,	'D', 'remove_data_modalbox', 'icons/quick_remove.png', 600)
	);

	protected $_links_quick_modal_result = array(
		/* array('Description', $sec_perm, method, 'image/path/img.png', $modal_width) */
		array(NDPHP_LANG_MOD_OP_QUICK_VIEW,		'R', 'view_data_modalbox',   'icons/quick_view.png',   600),
		array(NDPHP_LANG_MOD_OP_QUICK_EDIT,		'U', 'edit_data_modalbox',   'icons/quick_edit.png',   900),
		array(NDPHP_LANG_MOD_OP_QUICK_REMOVE,	'D', 'remove_data_modalbox', 'icons/quick_remove.png', 600)
	);

	/* Header Submenu Operations Links (Create, Edit, List, Remove, Result, Search and View) */
	protected $_links_submenu_body_create = array(
		/* array('Description', $sec_perm, method, 'image/path/img.png', 'ajax' / 'export' / 'method' / 'modal' / 'raw', with id?, access key) */
		array(NDPHP_LANG_MOD_OP_LIST,			'R', 'list',		NULL, 'ajax',   false,	NDPHP_LANG_MOD_OP_ACCESS_KEY_LIST	),
		array(NDPHP_LANG_MOD_OP_SEARCH,			'R', 'search',		NULL, 'ajax',   false,	NDPHP_LANG_MOD_OP_ACCESS_KEY_SEARCH	)
	);

	protected $_links_submenu_body_edit = array(
		/* array('Description', $sec_perm, method, 'image/path/img.png', 'ajax' / 'export' / 'method' / 'modal' / 'raw', with id?, access key) */
		array(NDPHP_LANG_MOD_OP_CREATE,			'C', 'create',		NULL, 'ajax',   false,	NDPHP_LANG_MOD_OP_ACCESS_KEY_CREATE	),
		array(NDPHP_LANG_MOD_OP_REMOVE,			'D', 'remove',		NULL, 'ajax',   true,	NDPHP_LANG_MOD_OP_ACCESS_KEY_REMOVE	),
		array(NDPHP_LANG_MOD_OP_VIEW,			'R', 'view',		NULL, 'ajax',   true,	NDPHP_LANG_MOD_OP_ACCESS_KEY_VIEW	),
		array(NDPHP_LANG_MOD_OP_LIST,			'R', 'list',		NULL, 'ajax',   false,	NDPHP_LANG_MOD_OP_ACCESS_KEY_LIST	),
		array(NDPHP_LANG_MOD_OP_GROUPS,			'R', 'groups',		NULL, 'ajax',	false,	NDPHP_LANG_MOD_OP_ACCESS_KEY_GROUPS	),
		array(NDPHP_LANG_MOD_OP_SEARCH,			'R', 'search',		NULL, 'ajax',   false,	NDPHP_LANG_MOD_OP_ACCESS_KEY_SEARCH	)
	);

	protected $_links_submenu_body_remove = array(
		/* array('Description', $sec_perm, method, 'image/path/img.png', 'ajax' / 'export' / 'method' / 'modal' / 'raw', with id?, access key) */
		array(NDPHP_LANG_MOD_OP_CREATE,			'C', 'create',		NULL, 'ajax',   false,	NDPHP_LANG_MOD_OP_ACCESS_KEY_CREATE	),
		array(NDPHP_LANG_MOD_OP_EDIT,			'U', 'edit',		NULL, 'ajax',   true,	NDPHP_LANG_MOD_OP_ACCESS_KEY_EDIT	),
		array(NDPHP_LANG_MOD_OP_VIEW,			'R', 'view',		NULL, 'ajax',   true,	NDPHP_LANG_MOD_OP_ACCESS_KEY_VIEW	),
		array(NDPHP_LANG_MOD_OP_LIST,			'R', 'list',		NULL, 'ajax',   false,	NDPHP_LANG_MOD_OP_ACCESS_KEY_LIST	),
		array(NDPHP_LANG_MOD_OP_GROUPS,			'R', 'groups',		NULL, 'ajax',	false,	NDPHP_LANG_MOD_OP_ACCESS_KEY_GROUPS	),
		array(NDPHP_LANG_MOD_OP_SEARCH,			'R', 'search',		NULL, 'ajax',   false,	NDPHP_LANG_MOD_OP_ACCESS_KEY_SEARCH	)
	);

	protected $_links_submenu_body_search = array(
		/* array('Description', $sec_perm, method, 'image/path/img.png', 'ajax' / 'export' / 'method' / 'modal' / 'raw', with id?, access key) */
		array(NDPHP_LANG_MOD_OP_CREATE,			'C', 'create',		NULL, 'ajax',   false,	NDPHP_LANG_MOD_OP_ACCESS_KEY_CREATE	),
		array(NDPHP_LANG_MOD_OP_LIST,			'R', 'list',		NULL, 'ajax',   false,	NDPHP_LANG_MOD_OP_ACCESS_KEY_LIST	),
		array(NDPHP_LANG_MOD_OP_GROUPS,			'R', 'groups',		NULL, 'ajax',	false,	NDPHP_LANG_MOD_OP_ACCESS_KEY_GROUPS	)
	);

	protected $_links_submenu_body_view = array(
		/* array('Description', $sec_perm, method, 'image/path/img.png', 'ajax' / 'export' / 'method' / 'modal' / 'raw', with id?, access key) */
		array(NDPHP_LANG_MOD_OP_CREATE,			'C', 'create',		NULL, 'ajax',   false,	NDPHP_LANG_MOD_OP_ACCESS_KEY_CREATE	),
		array(NDPHP_LANG_MOD_OP_REMOVE,			'D', 'remove',		NULL, 'ajax',   true,	NDPHP_LANG_MOD_OP_ACCESS_KEY_REMOVE	),
		array(NDPHP_LANG_MOD_OP_EDIT,			'U', 'edit',		NULL, 'ajax',   true,	NDPHP_LANG_MOD_OP_ACCESS_KEY_EDIT	),
		array(NDPHP_LANG_MOD_OP_LIST,			'R', 'list',		NULL, 'ajax',   false,	NDPHP_LANG_MOD_OP_ACCESS_KEY_LIST	),
		array(NDPHP_LANG_MOD_OP_GROUPS,			'R', 'groups',		NULL, 'ajax',	false,	NDPHP_LANG_MOD_OP_ACCESS_KEY_GROUPS	),
		array(NDPHP_LANG_MOD_OP_SEARCH,			'R', 'search',		NULL, 'ajax',   false,	NDPHP_LANG_MOD_OP_ACCESS_KEY_SEARCH	),
		array(NDPHP_LANG_MOD_OP_EXPORT_PDF,		'R', 'pdf',			NULL, 'export', true,	NULL 								)
	);

	protected $_links_submenu_body_list = array(
		/* array('Description', $sec_perm, method, 'image/path/img.png', 'ajax' / 'export' / 'method' / 'modal' / 'raw', with id?, access key) */
		array(NDPHP_LANG_MOD_OP_CREATE,			'C', 'create',		NULL, 'ajax',   false,	NDPHP_LANG_MOD_OP_ACCESS_KEY_CREATE	),
		array(NDPHP_LANG_MOD_OP_GROUPS,			'R', 'groups',		NULL, 'ajax',	false,	NDPHP_LANG_MOD_OP_ACCESS_KEY_GROUPS	),
		array(NDPHP_LANG_MOD_OP_SEARCH,			'R', 'search',		NULL, 'ajax',   false,	NDPHP_LANG_MOD_OP_ACCESS_KEY_SEARCH	),
		array(NDPHP_LANG_MOD_OP_EXPORT_PDF,		'R', 'pdf',			NULL, 'export', false,	NULL 								),
		array(NDPHP_LANG_MOD_OP_EXPORT_CSV,		'R', 'csv',			NULL, 'export', false,	NULL 								),
		array(NDPHP_LANG_MOD_OP_IMPORT_CSV,		'C', 'import_csv',	NULL, 'modal',	false,	NULL 								)
	);

	protected $_links_submenu_body_result = array(
		/* array('Description', $sec_perm, method, 'image/path/img.png', 'ajax' / 'export' / 'method' / 'modal' / 'raw', with id?, access key) */
		array(NDPHP_LANG_MOD_OP_CREATE,			'C', 'create',		NULL, 'ajax',   false,	NDPHP_LANG_MOD_OP_ACCESS_KEY_CREATE	),
		array(NDPHP_LANG_MOD_OP_LIST,			'R', 'list',		NULL, 'ajax',   false,	NDPHP_LANG_MOD_OP_ACCESS_KEY_LIST	),
		array(NDPHP_LANG_MOD_OP_GROUPS,			'R', 'groups',		NULL, 'ajax',	false,	NDPHP_LANG_MOD_OP_ACCESS_KEY_GROUPS	),
		array(NDPHP_LANG_MOD_OP_SEARCH,			'R', 'search',		NULL, 'ajax',   false,	NDPHP_LANG_MOD_OP_ACCESS_KEY_SEARCH	),
		array(NDPHP_LANG_MOD_OP_EXPORT_PDF,		'R', 'pdf',			NULL, 'export', false,	NULL 								),
		array(NDPHP_LANG_MOD_OP_EXPORT_CSV,		'R', 'csv',			NULL, 'export', false,	NULL 								),
		array(NDPHP_LANG_MOD_OP_IMPORT_CSV,		'C', 'import_csv',	NULL, 'modal',	false,	NULL 								),
		array(NDPHP_LANG_MOD_OP_SAVE_SEARCH,	'R', 'search_save',	NULL, 'modal',	false,	NULL 								)
	);

	protected $_links_submenu_body_groups = array(
		/* array('Description', $sec_perm, method, 'image/path/img.png', 'ajax' / 'export' / 'method' / 'modal' / 'raw', with id?, access key) */
		array(NDPHP_LANG_MOD_OP_CREATE,			'C', 'create',		NULL, 'ajax',   false,	NDPHP_LANG_MOD_OP_ACCESS_KEY_CREATE	),
		array(NDPHP_LANG_MOD_OP_LIST,			'R', 'list',		NULL, 'ajax',   false,	NDPHP_LANG_MOD_OP_ACCESS_KEY_LIST	),
		array(NDPHP_LANG_MOD_OP_SEARCH,			'R', 'search',		NULL, 'ajax',   false,	NDPHP_LANG_MOD_OP_ACCESS_KEY_SEARCH	)
	);

	/* If set to true, uploaded files will be stored encrypted */
	protected $_upload_file_encryption = true;

	/* Upload max file size */
	protected $_upload_file_max_size = 10485760; /* 10MiB by default */

	/* Regex to filter uploaded file name. All the characters not matching the following pattern will be replaced with '_' */
	protected $_upload_file_name_filter = 'a-zA-Z0-9_\.';

	/* Session data buffer (will be populated with construct) */
	protected $_session_data = array();

	/* If enabled, instead of loading views, the reply will be in JSON */
	protected $_json_replies = false;

	/* Scheduler settings */
	protected $_scheduler = array(
		'type' => 'request', /* By default, scheduled entries will be evaluated and processed on every request.
							  * If set to 'external', scheduled entries will only be processed when public scheduler_external method is invoked.
							  * If set to 'threaded' will behave as 'request', but execution of scheduled entries are performed in a separate thread (requires PHP threading support).
							  */
	);

	/* Caching */
	protected $_cache_tables = array(); /* This array will be populated by _get_tables() method. */
	protected $_cache_table_desc = array(); /* This array will be populated by _get_table_desc() method. */
	protected $_cache_table_fields = array(); /* This array will be populated by _get_table_fields() method. */
	protected $_cache_help = array(); /* This array will be populated by _get_help() method */


	/* Security */
	protected $_security_safe_chars = "a-zA-Z0-9_"; /* Mainly used to validate table names */
	protected $_security_perms = array();			/* Will be populated by $this->security->perm_get() */


	/** Hooks - Construct **/

	protected function _hook_construct() {
		/* Triggers right before the ::__construct() method returns. */

		return;
	}

	/** Hooks - Charts **/
	
	protected function _hook_charts() {
		/* NOTE: This hook should only be used for raw charts. */

		return;
	}

	/** Hooks - Views **/

	protected function _hook_list_generic_enter(&$data, &$field, &$order, &$page) {
		$hook_enter_return = NULL;

		return $hook_enter_return;
	}

	protected function _hook_list_generic_filter(&$data, &$field, &$order, &$page, $hook_enter_return) {
		return $hook_enter_return;
	}

	protected function _hook_list_generic_leave(&$data, &$field, &$order, &$page, $hook_enter_return) {
		return;
	}

	protected function _hook_search_generic_enter(&$data, &$advanced) {
		$hook_enter_return = NULL;

		return $hook_enter_return;
	}

	protected function _hook_search_generic_leave(&$data, &$advanced, $hook_enter_return) {
		return;
	}

	protected function _hook_result_generic_enter(&$data, &$type, &$result_query, &$order_field, &$order_type, &$page) {
		$hook_enter_return = NULL;

		return $hook_enter_return;
	}

	protected function _hook_result_generic_filter(&$data, &$type, &$result_query, &$order_field, &$order_type, &$page, $hook_enter_return) {
		return $hook_enter_return;
	}

	protected function _hook_result_generic_leave(&$data, &$type, &$result_query, &$order_field, &$order_type, &$page, $hook_enter_return) {
		return;
	}

	protected function _hook_export_enter(&$data, &$export_query, &$type) {
		$hook_enter_return = NULL;

		return $hook_enter_return;
	}

	protected function _hook_export_leave(&$data, &$export_query, &$type, $hook_enter_return) {
		return;
	}

	protected function _hook_create_generic_enter(&$data) {
		$hook_enter_return = NULL;

		return $hook_enter_return;
	}

	protected function _hook_create_generic_leave(&$data, $hook_enter_return) {
		return;
	}

	protected function _hook_edit_generic_enter(&$data, &$id) {
		$hook_enter_return = NULL;

		return $hook_enter_return;
	}

	protected function _hook_edit_generic_leave(&$data, &$id, $hook_enter_return) {
		return;
	}

	protected function _hook_view_generic_enter(&$data, &$id, &$export) {
		$hook_enter_return = NULL;

		return $hook_enter_return;
	}

	protected function _hook_view_generic_leave(&$data, &$id, &$export, $hook_enter_return) {
		return;
	}

	protected function _hook_remove_generic_enter(&$data, &$id) {
		$hook_enter_return = NULL;

		return $hook_enter_return;
	}

	protected function _hook_remove_generic_leave(&$data, &$id, $hook_enter_return) {
		return;
	}

	protected function _hook_groups_generic_enter(&$data) {
		$hook_enter_return = NULL;

		return $hook_enter_return;
	}

	protected function _hook_groups_generic_leave(&$data, $hook_enter_return) {
		return;
	}

	/** Hooks - Operations **/
	protected function _hook_insert_pre(&$POST, &$fields) {
		$hook_pre_return = NULL;

		return $hook_pre_return;
	}

	protected function _hook_insert_post(&$id, &$POST, &$fields, $hook_pre_return) {
		return;
	}

	protected function _hook_update_pre(&$id, &$POST, &$fields) {
		$hook_pre_return = NULL;
		
		return $hook_pre_return;
	}

	protected function _hook_update_post(&$id, &$POST, &$fields, $hook_pre_return) {
		return;
	}

	protected function _hook_delete_pre(&$id, &$POST, &$fields) {
		$hook_pre_return = NULL;
		
		return $hook_pre_return;
	}
	
	protected function _hook_delete_post(&$id, &$POST, &$fields, $hook_pre_return) {
		return;
	}


	/** Custom functions **/



	/** Charts **/

	protected $_charts_enable_list = true;
	protected $_charts_enable_result = true;
	protected $_charts_enable_view = true;

	protected $_charts_types = array('ts', 'foreign_ts', 'rel', 'foreign_rel', 'totals', 'foreign_totals');
	protected $_charts_font_family = 'verdana'; /* See fonts/ directory on pChart library package */
	protected $_charts_axis_font_size = 8;
	protected $_charts_title_font_size = 10;
	protected $_charts_canvas_width = 500;
	protected $_charts_canvas_height = 240;
	protected $_charts_graph_area = array(
		'X1' => 60,
		'Y1' => 40,
		'X2' => 470,
		'Y2' => 190
	);

	protected $_charts = array(
		/*
		array(
			'title'    => 'Chart Title',
			'type'     => 'ts',
			'fields'   => array('field_name1', 'field_name2', ...),
			'absicssa' => 'field_name1',
			'start_ts' => 1460000000,
			'end_ts'   => 1461000000
		),
		...
		*/
	);

	protected $_charts_foreign = array();

	private function _charts_config() {
		/* NOTE: This method shall be invoked on every other method that access $_charts and $_charts_foreign array.
		 *
		 * Currently, only the following methos are invoking it by default: list_generic(), result_generic(), view_generic(), chart_publish_generic().
		 *
		 */

		/* Fetch all charts related to this controller */
		$this->db->select('title,controller,charts_types_id,charts_geometry.chart_geometry AS geometry,fields,abscissa,foreign_table,start_ts,end_ts,field,field_ts,field_legend,field_total,import_ctrl,chartid');
		$this->db->from('charts_config');
		$this->db->join('charts_geometry', 'charts_config.charts_geometry_id = charts_geometry.id', 'left');
		$this->db->where('controller', $this->_name);

		$q = $this->db->get();

		foreach ($q->result_array() as $row) {
			switch (intval($row['charts_types_id'])) {
				case 1: {
					/* TS */
					$this->chart_add_ts($row['title'], strtolower($row['geometry']), explode(',', strtolower(str_replace(' ', '', $row['fields']))), strtolower($row['abscissa']), $row['start_ts'], $row['end_ts']);
				} break;
				case 2: {
					/* REL */
					$this->chart_add_rel($row['title'], strtolower($row['geometry']), strtolower($row['field']), strtolower($row['field_ts']), $row['start_ts'], $row['end_ts']);
				} break;
				case 3: {
					/* TOTALS */
					$this->chart_add_totals($row['title'], strtolower($row['geometry']), explode(',', strtolower(str_replace(' ', '', $row['fields']))), strtolower($row['field_legend']), strtolower($row['field_total']), strtolower($row['field_ts']), $row['start_ts'], $row['end_ts']);
				} break;
				case 4: {
					/* FOREIGN TS */
					$this->chart_add_foreign_ts($row['title'], strtolower($row['geometry']), explode(',', strtolower(str_replace(' ', '', $row['fields']))), strtolower($row['abscissa']), strtolower($row['foreign_table']), $row['start_ts'], $row['end_ts']);
				} break;
				case 5: {
					/* FOREIGN REL */
					$this->chart_add_foreign_rel($row['title'], strtolower($row['geometry']), strtolower($row['field']), strtolower($row['foreign_table']), strtolower($row['field_ts']), $row['start_ts'], $row['end_ts']);
				} break;
				case 6: {
					/* FOREIGN TOTALS */
					$this->chart_add_foreign_totals($row['title'], strtolower($row['geometry']), explode(',', strtolower(str_replace(' ', '', $row['fields']))), strtolower($row['field_legend']), strtolower($row['foreign_table']), strtolower($row['field_total']), strtolower($row['field_ts']), $row['start_ts'], $row['end_ts']);
				} break;
				case 7: {
					/* IMPORT */
					$this->chart_add_import(strtolower($row['import_ctrl']), $row['chartid'], $row['start_ts'], $row['end_ts']);
				} break;
				case 8: {
					$this->chart_add_foreign_import(strtolower($row['import_ctrl']), $row['chartid'], $row['start_ts'], $row['end_ts']);
				} break;
			}
		}
	}

	private function _chart_process_image_map($chart, &$pimage) {
		$chart_imagemap_name = 'chart_name_' . ($chart['foreign'] ? 'foreign' : 'local') . '_ '. $this->_name . '_' . $chart['id'] . '_' . $this->_session_data['user_id'];
		$chart_imagemap_map = 'chart_map_' . ($chart['foreign'] ? 'foreign' : 'local') . '_ '. $this->_name . '_' . $chart['id'] . '_' . $this->_session_data['user_id'];

		/* If this is a imagemap request, dump it.
		 * FIXME: There's no need to deliver the image map at this later stage. A new method should be implemented
		 * such as chart_publish_imagemap() to read the imagemap file directly from the temporary directory and deliver it
		 * without the need to create a new dataset and create a image object just to deliver what is already created.
		 */
		if ($chart['imagemap_request']) {
			$pimage->dumpImageMap(
				$chart_imagemap_name,	/* Image map name */
				IMAGE_MAP_STORAGE_FILE,	/* Storage type */
				$chart_imagemap_map,	/* Unique ID */
				$this->_temp_dir		/* Storage directory */
			);

			/* NOTE: Execution was terminated by the above call */
		}

		/* Initialize imagemap */
		$pimage->initialiseImageMap(
			$chart_imagemap_name,	/* Image map name */
			IMAGE_MAP_STORAGE_FILE,	/* Storage type */
			$chart_imagemap_map,	/* Unique ID */
			$this->_temp_dir		/* Storage directory */
		);
	}

	/* Generates a time-series dataset object for $chart, based on a query object $q, to be used as chart_build_image_ts() */
	protected function chart_build_dataset_ts($chart, $q) {
		$dataset = array();

		/* Prepare dataset */
		$i = 0;
		$ts_first = 0;
		foreach ($q->result_array() as $row) {
			if ($i == 0)
				$ts_first = $row['abscissa'];

			/* Fetch data from all Y axis fields */
			foreach ($chart['fields'] as $field)
				$dataset[$field][] = $row[$field];

			/* Fetch data from X axis field */
			$dataset[$chart['field_ts']][] = $row['abscissa'];

			$i ++;
		}
		$ts_last = $row['abscissa'];

		/* Instantiate a pChart object */
		$pchart = $this->pchart->pData();

		/* Import dataset */
		$i = 0;
		foreach ($dataset as $set => $data) {
			/* Also setup the both axis properties */
			if ($set == $chart['field_ts']) {
				/* This is the X axis */
				$pchart->addPoints($data, $set);

				$pchart->setAbscissa($chart['field_ts']);
				$pchart->setXAxisName(isset($this->_table_field_aliases[$chart['field_ts']]) ? $this->_table_field_aliases[$chart['field_ts']] : ucfirst($chart['field_ts']));

				/* Compute the X Axis Display format */
				$axis_format_id = AXIS_FORMAT_TIME;
				$axis_format_str = "H:i";

				if (($ts_last - $ts_first) < 3600) {
					/* Less than a hour */
					$axis_format_id = AXIS_FORMAT_TIME;
					$axis_format_str = "i:s";
				} else if (($ts_last - $ts_first) < 86400) {
					/* Less than a day */
					$axis_format_id = AXIS_FORMAT_TIME;
					$axis_format_str = "H:i";
				} else {
					/* More than a day */
					$axis_format_id = AXIS_FORMAT_DATE;
					$axis_format_str = "Y-m-d";
				}

				/* Set the X Axis Display format */
				$pchart->setXAxisDisplay($axis_format_id, $axis_format_str);
			} else {
				/* This belongs to Y axis */
				$pchart->addPoints($data, ucfirst($set));
				$pchart->setSerieOnAxis(ucfirst($set), $i);
				$pchart->setAxisName($i, isset($this->_table_field_aliases[$set]) ? $this->_table_field_aliases[$set] : ucfirst($set));

				/* Get field units */
				$this->db->select('field_units');
				$this->db->from('_help_tfhd');
				$this->db->where('table_name', $this->_name);
				$this->db->where('field_name', $set);
				$qu = $this->db->get();

				if ($qu->num_rows()) {
					$u = $qu->row_array();
					$pchart->setAxisUnit($i, $u['field_units']);
				}
			}

			$i ++;
		}

		return $pchart;
	}

	/* Generates a time-series (from a foreign table) dataset object for $chart, based on a query object $q,
	 * to be used as chart_build_image_foreign_ts()
	 */
	protected function chart_build_dataset_foreign_ts($chart, $q) {
		$dataset = array();

		/* Fetch the foreign controller object */
		$fctrl = $this->access->controller($chart['ftable']);

		/* Prepare dataset */
		$i = 0;
		$ts_first = 0;
		foreach ($q->result_array() as $row) {
			if ($i == 0)
				$ts_first = $row['abscissa'];

			/* Fetch data from all Y axis fields */
			foreach ($chart['fields'] as $field)
				$dataset[$field][] = $row[$field];

			/* Fetch data from X axis field */
			$dataset[$chart['field_ts']][] = $row['abscissa'];

			$i ++;
		}
		$ts_last = $row['abscissa'];

		/* Instantiate a pChart object */
		$pchart = $this->pchart->pData();

		/* Import dataset */
		$i = 0;
		foreach ($dataset as $set => $data) {
			/* Also setup the both axis properties */
			if ($set == $chart['field_ts']) {
				/* This is the X axis */
				$pchart->addPoints($data, $set);

				$pchart->setAbscissa($chart['field_ts']);
				$pchart->setXAxisName(isset($fctrl->config['table_field_aliases'][$chart['field_ts']]) ? $fctrl->config['table_field_aliases'][$chart['field_ts']] : ucfirst($chart['field_ts']));

				/* Compute the X Axis Display format */
				$axis_format_id = AXIS_FORMAT_TIME;
				$axis_format_str = "H:i";

				if (($ts_last - $ts_first) < 3600) {
					/* Less than a hour */
					$axis_format_id = AXIS_FORMAT_TIME;
					$axis_format_str = "i:s";
				} else if (($ts_last - $ts_first) < 86400) {
					/* Less than a day */
					$axis_format_id = AXIS_FORMAT_TIME;
					$axis_format_str = "H:i";
				} else {
					/* More than a day */
					$axis_format_id = AXIS_FORMAT_DATE;
					$axis_format_str = "Y-m-d";
				}

				/* Set the X Axis Display format */
				$pchart->setXAxisDisplay($axis_format_id, $axis_format_str);
			} else {
				/* This belongs to Y axis */
				$pchart->addPoints($data, ucfirst($set));
				$pchart->setSerieOnAxis(ucfirst($set), $i);
				$pchart->setAxisName($i, isset($fctrl->config['table_field_aliases'][$set]) ? $fctrl->config['table_field_aliases'][$set] : ucfirst($set));

				/* Get field units */
				$this->db->select('field_units');
				$this->db->from('_help_tfhd');
				$this->db->where('table_name', $fctrl->config['name']);
				$this->db->where('field_name', $set);
				$qu = $this->db->get();

				if ($qu->num_rows()) {
					$u = $qu->row_array();
					$pchart->setAxisUnit($i, $u['field_units']);
				}
			}

			$i ++;
		}

		return $pchart;
	}

	/* Generates a dataset object for $chart, based on a query object $q which refers to a single relationship,
	 * to be used as chart_build_image_rel()
	 */
	protected function chart_build_dataset_rel($chart, $q) {
		$dataset = array();

		/* Compute sum */
		$result_array = array();
		$sum = 0;
		foreach ($q->result_array() as $row) {
			array_push($result_array, $row);
			$sum += $row['total'];
		}

		/* Prepare dataset */
		foreach ($result_array as $row) {
			/* Fetch the set name */
			if ($chart['geometry'] == 'pie') {
				/* If this is a pie chart, add percentages and absolute values to the Labels */
				$dataset[$chart['fields']][] = $row[$chart['foreign_field']] . ' ' . round((($row['total'] * 100.0) / $sum), 2) . '% [' . $row['total'] . ']';
			} else {
				/* .. otherwise, just use the foreign field name as label */
				$dataset[$chart['fields']][] = $row[$chart['foreign_field']];
			}

			/* Fetch the set weight */
			$dataset['total'][] = $row['total'];
		}

		/* Instantiate a pChart object */
		$pchart = $this->pchart->pData();

		/* Import dataset */
		$pchart->addPoints($dataset['total'], $row[$chart['foreign_field']]);
		$pchart->addPoints($dataset[$chart['fields']], 'Labels'); /* Always import abscissa as the last dataset to keep chart color coherency */
		$pchart->setAbscissa('Labels');

		/* All good */
		return $pchart;
	}

	protected function chart_build_dataset_foreign_rel($chart, $q) {
		/* Basically, there's no difference on dataset builder from 'foreign_rel' to 'rel' chart types */
		return $this->chart_build_dataset_rel($chart, $q);
	}

	/* Generates a dataset object for $chart, based on a query object $q which refers one or more fields containing numerical values,
	 * to be used as chart_build_image_totals()
	 */
	protected function chart_build_dataset_totals($chart, $q) {
		$dataset = array();

		/* Compute sum */
		$result_array = array();
		$sum = 0;
		foreach ($q->result_array() as $row) {
			array_push($result_array, $row);
			if (isset($row['total'])) /* Only sum if a total field was defined */
				$sum += $row['total'];
		}

		/* Prepare dataset */
		foreach ($result_array as $row) {
			if ($chart['field_tot']) {
				unset($row[$chart['field_tot']]); /* Remove replicas... */
			}
			/* Fetch the set name */
			if ($chart['geometry'] == 'pie' && $sum) {
				/* If this is a pie chart, add percentages and absolute values to the Labels */
				$dataset[$chart['fields'][0]][] = $row[$chart['fields'][0]] . ' ' . round((($row['total'] * 100.0) / $sum), 2) . '% [' . $row['total'] . ']';
			} else {
				/* .. otherwise, just use the foreign field name as label */
				$dataset[$chart['fields'][0]][] = $row[$chart['fields'][0]];
			}

			/* If a total field was set, fetch that value */
			if (isset($row['total']))
				$dataset['total'][] = $row['total'];

			/* Fetch the remaining fields data */
			foreach ($row as $field => $value) {
				/* We already have fetched this dataset (Label) */
				if ($field == $chart['fields'][0])
					continue;

				/* If a total dataset was defined, it was already fetched */
				if ($field == 'total')
					continue;

				/* Fetch the remaining set values */
				$dataset[$field][] = $value;
			}
		}

		/* Instantiate a pChart object */
		$pchart = $this->pchart->pData();

		/* Import dataset */
		if (isset($dataset['total']))
			$pchart->addPoints($dataset['total'], ucfirst($chart['field_tot']));

		foreach ($dataset as $set => $data) {
			/* Labels and totals already added */
			if ($set == 'total' || $set == $chart['fields'][0])
				continue;

			$pchart->addPoints($dataset[$set], ucfirst($set));
		}

		/* Always import abscissa as the last dataset to keep chart color coherency */
		$pchart->addPoints($dataset[$chart['fields'][0]], 'Labels');
		$pchart->setAbscissa('Labels');

		/* All good */
		return $pchart;
	}

	protected function chart_build_dataset_foreign_totals($chart, $q) {
		return $this->chart_build_dataset_totals($chart, $q);
	}

	/* Generates a time-series image object for $chart, based on $dataset returned from chart_build_dataset_ts() functions family */
	protected function chart_build_image_ts($chart, $dataset) {
		/* Instantiate image object */
		$pimage = $this->pchart->pImage($chart['width'] ? $chart['width'] : $this->_charts_canvas_width, $chart['height'] ? $chart['height'] : $this->_charts_canvas_height, $dataset);

		/* Process imagemap */
		$this->_chart_process_image_map($chart, $pimage);

		/* Set the chart area within canvas */
		$pimage->setGraphArea($this->_charts_graph_area['X1'], $this->_charts_graph_area['Y1'], $this->_charts_graph_area['X2'], $this->_charts_graph_area['Y2']);

		/* Set font for title */
		$pimage->setFontProperties(array(
			'FontName' => $this->pchart->fonts_path() . '/' . $this->_charts_font_family . '.ttf',
			'FontSize' => $this->_charts_title_font_size
		));

		/* Set chart title */
		$pimage->drawText($this->_charts_canvas_width / 2, 10, $chart['title'], array(
			"R"		=> 0,
			"G"		=> 0,
			"B"		=> 0,
			"Align"	=> TEXT_ALIGN_TOPMIDDLE
		));

		/* Set font */
		$pimage->setFontProperties(array(
			'FontName' => $this->pchart->fonts_path() . '/' . $this->_charts_font_family . '.ttf',
			'FontSize' => $this->_charts_axis_font_size
		));

		/* Draw scale... */
		$pimage->drawScale(array(
			'DrawXLines'	=> false,
			'DrawYLines' 	=> ALL,
			'GridTicks'		=> 0,
			'GridR'			=> 200,
			'GridG'			=> 200,
			'GridB'			=> 200
		));

		return $pimage;
	}

	protected function chart_build_image_foreign_ts($chart, $dataset) {
		/* Instantiate image object */
		$pimage = $this->pchart->pImage($chart['width'] ? $chart['width'] : $this->_charts_canvas_width, $chart['height'] ? $chart['height'] : $this->_charts_canvas_height, $dataset);

		/* Process imagemap */
		$this->_chart_process_image_map($chart, $pimage);

		/* Set the chart area within canvas */
		$pimage->setGraphArea($this->_charts_graph_area['X1'], $this->_charts_graph_area['Y1'], $this->_charts_graph_area['X2'], $this->_charts_graph_area['Y2']);

		/* Set font for title */
		$pimage->setFontProperties(array(
			'FontName' => $this->pchart->fonts_path() . '/' . $this->_charts_font_family . '.ttf',
			'FontSize' => $this->_charts_title_font_size
		));

		/* Set chart title */
		$pimage->drawText($this->_charts_canvas_width / 2, 10, $chart['title'], array(
			"R"		=> 0,
			"G"		=> 0,
			"B"		=> 0,
			"Align"	=> TEXT_ALIGN_TOPMIDDLE
		));

		/* Set font */
		$pimage->setFontProperties(array(
			'FontName' => $this->pchart->fonts_path() . '/' . $this->_charts_font_family . '.ttf',
			'FontSize' => $this->_charts_axis_font_size
		));

		/* Draw scale... */
		$pimage->drawScale(array(
			'DrawXLines'	=> false,
			'DrawYLines' 	=> ALL,
			'GridTicks'		=> 0,
			'GridR'			=> 200,
			'GridG'			=> 200,
			'GridB'			=> 200
		));

		return $pimage;
	}

	protected function chart_build_image_rel($chart, $dataset) {
		$pimage = $this->pchart->pImage($chart['width'] ? $chart['width'] : $this->_charts_canvas_width, $chart['height'] ? $chart['height'] : $this->_charts_canvas_height, $dataset);

		/* Process imagemap */
		$this->_chart_process_image_map($chart, $pimage);

		/* Set font for title */
		$pimage->setFontProperties(array(
			'FontName' => $this->pchart->fonts_path() . '/' . $this->_charts_font_family . '.ttf',
			'FontSize' => $this->_charts_title_font_size
		));

		/* Set chart title */
		$pimage->drawText($this->_charts_canvas_width / 2, 10, $chart['title'], array(
			"R"		=> 0,
			"G"		=> 0,
			"B"		=> 0,
			"Align"	=> TEXT_ALIGN_TOPMIDDLE
		));

		/* Set font */
		$pimage->setFontProperties(array(
			'FontName' => $this->pchart->fonts_path() . '/' . $this->_charts_font_family . '.ttf',
			'FontSize' => $this->_charts_axis_font_size
		));

		return $pimage;
	}

	protected function chart_build_image_foreign_rel($chart, $dataset) {
		/* Basically, there's no difference on image builder from 'foreign_rel' to 'rel' chart types */
		return $this->chart_build_image_rel($chart, $dataset);
	}

	protected function chart_build_image_totals($chart, $dataset) {
		$pimage = $this->pchart->pImage($chart['width'] ? $chart['width'] : $this->_charts_canvas_width, $chart['height'] ? $chart['height'] : $this->_charts_canvas_height, $dataset);

		/* Process imagemap */
		$this->_chart_process_image_map($chart, $pimage);

		/* Set font for title */
		$pimage->setFontProperties(array(
			'FontName' => $this->pchart->fonts_path() . '/' . $this->_charts_font_family . '.ttf',
			'FontSize' => $this->_charts_title_font_size
		));

		/* Set chart title */
		$pimage->drawText($this->_charts_canvas_width / 2, 10, $chart['title'], array(
			"R"		=> 0,
			"G"		=> 0,
			"B"		=> 0,
			"Align"	=> TEXT_ALIGN_TOPMIDDLE
		));

		/* Set font */
		$pimage->setFontProperties(array(
			'FontName' => $this->pchart->fonts_path() . '/' . $this->_charts_font_family . '.ttf',
			'FontSize' => $this->_charts_axis_font_size
		));

		return $pimage;
	}

	protected function chart_build_image_foreign_totals($chart, $dataset) {
		return $this->chart_build_image_totals($chart, $dataset);
	}

	protected function chart_add_raw($chart) {
		array_push($this->_charts, $chart);
	}

	protected function chart_add_foreign_raw($chart) {
		array_push($this->_charts_foreign, $chart);
	}

	protected function chart_add_ts($title = 'Title', $geometry = 'line', $fields = array(), $abscissa = 'timestamp_field', $start_ts = 0, $end_ts = 0) {
		array_push($this->_charts, array(
			'title'		=> $title,
			'type'		=> 'ts',
			'geometry'	=> $geometry,
			'fields'	=> $fields,
			'field_ts'	=> $abscissa,
			'start_ts'	=> $start_ts,
			'end_ts'	=> $end_ts,
			'pimage'	=> NULL,
			'imagemap_request' => false,
			'foreign'	=> false,
			'imported'	=> false,
			'imp_ctrl'	=> NULL,
			'imp_id'	=> NULL,
			'height'	=> NULL,
			'width'		=> NULL
		));
	}

	protected function chart_add_foreign_ts($title = 'Title', $geometry = 'line', $ft_fields = array(), $ft_abscissa = 'timestamp_field', $ft_name, $start_ts = 0, $end_ts = 0) {
		array_push($this->_charts_foreign, array(
			'title'		=> $title,
			'type'		=> 'foreign_ts',
			'geometry'	=> $geometry,
			'fields'	=> $ft_fields,
			'field_ts'	=> $ft_abscissa,
			'ftable'	=> $ft_name,
			'entry_id'	=> NULL, /* NOTE: Will be set on chart_foreign_publish() */
			'start_ts'	=> $start_ts,
			'end_ts'	=> $end_ts,
			'pimage'	=> NULL,
			'imagemap_request' => false,
			'foreign'	=> true,
			'imported'	=> false,
			'imp_ctrl'	=> NULL,
			'imp_id'	=> NULL,
			'height'	=> NULL,
			'width'		=> NULL
		));
	}

	protected function chart_add_rel($title = 'Title', $geometry = 'pie', $field, $field_ts = NULL, $start_ts = 0, $end_ts = 0) {
		array_push($this->_charts, array(
			'title'		=> $title,
			'type'		=> 'rel',
			'geometry'	=> $geometry,
			'fields'	=> $field,
			'field_ts'	=> $field_ts,
			'start_ts'	=> $start_ts,
			'end_ts'	=> $end_ts,
			'pimage'	=> NULL,
			'imagemap_request' => false,
			'foreign'	=> false,
			'imported'	=> false,
			'imp_ctrl'	=> NULL,
			'imp_id'	=> NULL,
			'height'	=> NULL,
			'width'		=> NULL
		));
	}

	protected function chart_add_foreign_rel($title = 'Title', $geometry = 'pie', $ft_field, $ft_name, $field_ts = NULL, $start_ts = 0, $end_ts = 0) {
		array_push($this->_charts_foreign, array(
			'title'		=> $title,
			'type'		=> 'foreign_rel',
			'geometry'	=> $geometry,
			'fields'	=> $ft_field,
			'ftable'	=> $ft_name,
			'entry_id'	=> NULL, /* NOTE: Will be set on chart_foreign_publish() */
			'field_ts'	=> $field_ts,
			'start_ts'	=> $start_ts,
			'end_ts'	=> $end_ts,
			'pimage'	=> NULL,
			'imagemap_request' => false,
			'foreign'	=> true,
			'imported'	=> false,
			'imp_ctrl'	=> NULL,
			'imp_id'	=> NULL,
			'height'	=> NULL,
			'width'		=> NULL
		));
	}

	protected function chart_add_totals($title = 'Title', $geometry = 'bar', $fields = array(), $field_legend, $field_total = NULL, $field_ts = NULL, $start_ts = 0, $end_ts = 0) {
		/* NOTE: $field_total only makes sense for pie charts (which will allow to compute and print the absolute and percentage values on legend) */

		$fields_all = array($field_legend);
		$fields_all = array_merge($fields_all, $fields);

		array_push($this->_charts, array(
			'imported'	=> false,
			'title'		=> $title,
			'type'		=> 'totals',
			'geometry'	=> $geometry,
			'fields'	=> $fields_all,
			'field_tot' => $field_total,
			'field_ts'	=> $field_ts,
			'start_ts'	=> $start_ts,
			'end_ts'	=> $end_ts,
			'pimage'	=> NULL,
			'imagemap_request' => false,
			'foreign'	=> false,
			'imported'	=> false,
			'imp_ctrl'	=> NULL,
			'imp_id'	=> NULL,
			'height'	=> NULL,
			'width'		=> NULL
		));
	}

	protected function chart_add_foreign_totals($title = 'Title', $geometry = 'bar', $ft_fields = array(), $ft_field_legend, $ft_name, $field_total = NULL, $field_ts = NULL, $start_ts = 0, $end_ts = 0) {
		/* NOTE: $field_total only makes sense for pie charts (which will allow to compute and print the absolute and percentage values on legend) */

		$fields_all = array($ft_field_legend);
		$fields_all = array_merge($fields_all, $ft_fields);

		array_push($this->_charts_foreign, array(
			'imported'	=> false,
			'title'		=> $title,
			'type'		=> 'foreign_totals',
			'geometry'	=> $geometry,
			'fields'	=> $fields_all,
			'field_tot' => $field_total,
			'ftable'	=> $ft_name,
			'entry_id'	=> NULL,
			'field_ts'	=> $field_ts,
			'start_ts'	=> $start_ts,
			'end_ts'	=> $end_ts,
			'pimage'	=> NULL,
			'imagemap_request' => false,
			'foreign'	=> true,
			'imported'	=> false,
			'imp_ctrl'	=> NULL,
			'imp_id'	=> NULL,
			'height'	=> NULL,
			'width'		=> NULL
		));
	}

	protected function chart_add_import($controller, $chart_id, $start_ts = 0, $end_ts = 0) {
		array_push($this->_charts, array(
			'start_ts'	=> $start_ts,
			'end_ts'	=> $end_ts,
			'imagemap_request' => false,
			'foreign'	=> false,
			'imported'	=> true,
			'imp_ctrl'	=> $controller,
			'imp_id'	=> $chart_id
		));
	}

	protected function chart_add_foreign_import($controller, $chart_id, $start_ts = 0, $end_ts = 0) {
		array_push($this->_charts_foreign, array(
			'start_ts'	=> $start_ts,
			'end_ts'	=> $end_ts,
			'imagemap_request' => false,
			'foreign'	=> true,
			'imported'	=> true,
			'imp_ctrl'	=> $controller,
			'imp_id'	=> $chart_id
		));
	}

	private function _chart_generate_ts(&$chart) {
		/* Check if there is READ permission for this controller table */
		if (!$this->security->perm_check($this->_security_perms, $this->security->perm_read, $this->_name))
			return false;

		/* Check if there is READ permission for abscissa (in ts type charts, this is the field_ts) */
		if (!$this->security->perm_check($this->_security_perms, $this->security->perm_read, $this->_name, $chart['field_ts']))
			return false;

		/* Also grant that there is READ permissions for the y axis fields */
		$fields_filtered = array();
		foreach ($chart['fields'] as $field) {
			if ($this->security->perm_check($this->_security_perms, $this->security->perm_read, $this->_name, $field))
				array_push($fields_filtered, $field);
		}
		$chart['fields'] = $fields_filtered;

		/* Check if there's anything to plot */
		if (!count($chart['fields']))
			return false;

		/* Fetch data from database. NOTE: UNIX_TIMESTAMP() will always return UTC timestamps (converted from time_zone=SYSTEM, which should match the $this->_default_timezone variable), so we need always to convert from Etc/UTC (and not from the $this->_default_timezone) */
		$this->db->select('`' . implode('`,`', $chart['fields']) . '`,UNIX_TIMESTAMP(CONVERT_TZ(`' . $chart['field_ts'] . '`, \'Etc/UTC\', \'' . $this->_session_data['timezone'] . '\')) AS abscissa', false);
		$this->db->from($this->_name);

		/* Check if we need to narrow our result based on start timestamp value */
		if ($chart['start_ts']) {
			if (strstr($chart['start_ts'], ' ')) {
				/* If there's a space in the time value, then we'll search for interval formats */
				$interval_fields = $this->_get_interval_fields($chart['start_ts']);

				if (!$interval_fields)
					$this->response->code('500', NDPHP_LANG_MOD_INVALID_SEARCH_INTERVAL_FMT, $this->_default_charset, !$this->request->is_ajax());

				$this->db->where($chart['field_ts'] . ' >=', 'NOW() ' . $interval_fields[0] . ' INTERVAL ' . $interval_fields[1] . ' ' . $interval_fields[2]);
			} else {
				/* Otherwise, it is expected that the value is numeric and represent a timestamp since epoch (1970-01-01 00:00:00 UTC) */
				$this->db->where($chart['field_ts'] . ' >=', $chart['start_ts']);
			}
		}

		/* Check if we need to narrow our result based on end timestamp value */
		if ($chart['end_ts']) {
			if (strstr($chart['end_ts'], ' ')) {
				/* If there's a space in the time value, then we'll search for interval formats */
				$interval_fields = $this->_get_interval_fields($chart['end_ts']);

				if (!$interval_fields)
					$this->response->code('500', NDPHP_LANG_MOD_INVALID_SEARCH_INTERVAL_FMT, $this->_default_charset, !$this->request->is_ajax());

				$this->db->where($chart['field_ts'] . ' <=', 'NOW() ' . $interval_fields[0] . ' INTERVAL ' . $interval_fields[1] . ' ' . $interval_fields[2]);
			} else {
				/* Otherwise, it is expected that the value is numeric and represent a timestamp since epoch (1970-01-01 00:00:00 UTC) */
				$this->db->where($chart['field_ts'] . ' <=', $chart['end_ts']);
			}
		}

		/* Also filter rows based on $_table_row_filtering_config */
		if ($this->_table_row_filtering) {
			$table_fields = $this->_get_table_fields($this->_name);

			foreach ($this->_table_row_filtering_config as $field => $svar) {
				if (in_array($field, $table_fields))
					$this->db->where($field, $this->_session_data[$svar]);
			}
		}

		/* Check if there are additional filters to append to the query WHERE component */
		if ($chart['result_query']) {
			/* Decode and decipher the query */
			$result_query = gzuncompress($this->encrypt->decode($this->ndphp->safe_b64decode(rawurldecode($chart['result_query']))));

			$matches = NULL;

			/* Get the WHERE component of the query */
			if (preg_match('/^.+\s+WHERE\s+(.+)\s+GROUP BY.+$/', $result_query, $matches)) {
				/* Append the WHERE component to the current query. */
				/* NOTE: The 1 = 1 is a fail safe, in case the WHERE wasn't initialized yet */
				$this->db->where('1 =', '1', false);
				$this->db->where_append(' AND (' . $matches[1] . ')');
			}
		}

		/* Fetch data (effectively) */
		$q = $this->db->get();

		/* Check if there's anything to generate */
		if (!$q->num_rows())
			return false;

		/* Build chart dataset object */
		$dataset = $this->chart_build_dataset_ts($chart, $q);

		/* Build chart image object */
		$pimage = $this->chart_build_image_ts($chart, $dataset);

		/* Set chart type */
		switch ($chart['geometry']) {
			case 'line'    : $pimage->drawLineChart(array('RecordImageMap' => true));        break;
			case 'spline'  : $pimage->drawSplineChart(array('RecordImageMap' => true));      break;
			case 'area'    : $pimage->drawAreaChart(array('RecordImageMap' => true));        break;
			case 'stacked' : $pimage->drawStackedAreaChart(array('RecordImageMap' => true)); break;
			default: {
				$this->response->code('500', NDPHP_LANG_MOD_INVALID_CHART_GEOMETRY . ': ' . $chart['geometry'], $this->_default_charset, !$this->request->is_ajax());
			}
		}

		/* Draw Legend */
		$pimage->drawLegend($this->_charts_canvas_width - 80, 20, array(
			'R'		=> 255,
			'G'		=> 255,
			'B'		=> 255,
			'Alpha'	=> 0
		));

		/* All good... return the image object */
		return $pimage;
	}

	private function _chart_generate_foreign_ts(&$chart) {
		/* Check if there is READ permission for this controller table */
		if (!$this->security->perm_check($this->_security_perms, $this->security->perm_read, $this->_name))
			return false;

		if (substr($chart['ftable'], 0, 6) != 'mixed_') {
			/* Check if there is READ permissions on the foreign table that will feed the data */
			if (!$this->security->perm_check($this->_security_perms, $this->security->perm_read, $chart['ftable']))
				return false;

			/* Check if there is READ permission for abscissa (in ts type charts, this is the field_ts) */
			if (!$this->security->perm_check($this->_security_perms, $this->security->perm_read, $chart['ftable'], $chart['field_ts']))
				return false;
		}

		/* Grant that the user requesting this chart has permissions to read $entry_id on $this->_name table. */
		$this->db->select('id');
		$this->db->from($this->_name);
		$this->db->where('id', $chart['entry_id']);

		if ($this->_table_row_filtering) {
			$table_fields = $this->_get_table_fields($this->_name);

			foreach ($this->_table_row_filtering_config as $field => $svar) {
				if (in_array($field, $table_fields))
					$this->db->where($field, $this->_session_data[$svar]);
			}
		}

		$qfilter = $this->db->get();

		if (!$qfilter->num_rows())
			return false;

		/* Also grant that there is READ permissions for the y axis fields (which belong to the foreign table) */
		if (substr($chart['ftable'], 0, 6) != 'mixed_') {
			$fields_filtered = array();

			foreach ($chart['fields'] as $field) {
				if ($this->security->perm_check($this->_security_perms, $this->security->perm_read, $chart['ftable'], $field))
					array_push($fields_filtered, $field);
			}

			$chart['fields'] = $fields_filtered;
		}

		/* Check if there's anything to plot */
		if (!count($chart['fields']))
			return false;

		/* Fetch data from database. NOTE: UNIX_TIMESTAMP() will always return UTC timestamps (converted from time_zone=SYSTEM, which should match the $this->_default_timezone variable), so we need always to convert from Etc/UTC (and not from the $this->_default_timezone) */
		$this->db->select('`' . $chart['ftable'] . '`.`' . implode('`,`' . $chart['ftable'] . '`.`', $chart['fields']) . '`,UNIX_TIMESTAMP(CONVERT_TZ(`' . $chart['ftable'] . '`.`' . $chart['field_ts'] . '`, \'Etc/UTC\', \'' . $this->_session_data['timezone'] . '\')) AS abscissa', false);
		$this->db->from($chart['ftable']);
		$this->db->join($this->_name, $chart['ftable'] . '.' . $this->_name . '_id = ' . $this->_name . '.id', 'left');
		$this->db->where($this->_name . '.id', $chart['entry_id']);

		/* Check if we need to narrow our result based on start timestamp value */
		if ($chart['start_ts']) {
			if (strstr($chart['start_ts'], ' ')) {
				/* If there's a space in the time value, then we'll search for interval formats */
				$interval_fields = $this->_get_interval_fields($chart['start_ts']);

				if (!$interval_fields)
					$this->response->code('500', NDPHP_LANG_MOD_INVALID_SEARCH_INTERVAL_FMT, $this->_default_charset, !$this->request->is_ajax());

				$this->db->where($chart['field_ts'] . ' >=', 'NOW() ' . $interval_fields[0] . ' INTERVAL ' . $interval_fields[1] . ' ' . $interval_fields[2]);
			} else {
				/* Otherwise, it is expected that the value is numeric and represent a timestamp since epoch (1970-01-01 00:00:00 UTC) */
				$this->db->where($chart['field_ts'] . ' >=', $chart['start_ts']);
			}
		}

		/* Check if we need to narrow our result based on end timestamp value */
		if ($chart['end_ts']) {
			if (strstr($chart['end_ts'], ' ')) {
				/* If there's a space in the time value, then we'll search for interval formats */
				$interval_fields = $this->_get_interval_fields($chart['end_ts']);

				if (!$interval_fields)
					$this->response->code('500', NDPHP_LANG_MOD_INVALID_SEARCH_INTERVAL_FMT, $this->_default_charset, !$this->request->is_ajax());

				$this->db->where($chart['field_ts'] . ' <=', 'NOW() ' . $interval_fields[0] . ' INTERVAL ' . $interval_fields[1] . ' ' . $interval_fields[2]);
			} else {
				/* Otherwise, it is expected that the value is numeric and represent a timestamp since epoch (1970-01-01 00:00:00 UTC) */
				$this->db->where($chart['field_ts'] . ' <=', $chart['end_ts']);
			}
		}

		/* Also filter rows based on foreign controller $_table_row_filtering_config if this isn't a mixed table */
		if (substr($chart['ftable'], 0, 6) != 'mixed_') {
			$fctrl = $this->access->controller($chart['ftable']);

			if ($fctrl->config['table_row_filtering']) {
				$table_fields = $this->_get_table_fields($chart['ftable']);

				/* NOTE: here, we fetch the configuration of the foreign controller, not the filtering config of $this controller  */
				foreach ($fctrl->config['table_row_filtering_config'] as $field => $svar) {
					if (in_array($field, $table_fields))
						$this->db->where($chart['ftable'] . '.' . $field, $this->_session_data[$svar]);
				}
			}
		}

		/* Fetch data (effectively) */
		$q = $this->db->get();

		/* Check if there's anything to generate */
		if (!$q->num_rows())
			return false;

		/* Build chart dataset object */
		$dataset = $this->chart_build_dataset_foreign_ts($chart, $q);

		/* Build chart image object */
		$pimage = $this->chart_build_image_foreign_ts($chart, $dataset);

		/* Set chart type */
		switch ($chart['geometry']) {
			case 'line'    : $pimage->drawLineChart(array('RecordImageMap' => true));        break;
			case 'spline'  : $pimage->drawSplineChart(array('RecordImageMap' => true));      break;
			case 'area'    : $pimage->drawAreaChart(array('RecordImageMap' => true));        break;
			case 'stacked' : $pimage->drawStackedAreaChart(array('RecordImageMap' => true)); break;
			default: {
				$this->response->code('500', NDPHP_LANG_MOD_INVALID_CHART_GEOMETRY . ': ' . $chart['geometry'], $this->_default_charset, !$this->request->is_ajax());
			}
		}

		/* Draw Legend */
		$pimage->drawLegend($this->_charts_canvas_width - 80, 20, array(
			'R'		=> 255,
			'G'		=> 255,
			'B'		=> 255,
			'Alpha'	=> 0
		));

		/* All good... return the image object */
		return $pimage;
	}

	private function _chart_generate_rel(&$chart) {
		/* Check if there is READ permission for this controller table */
		if (!$this->security->perm_check($this->_security_perms, $this->security->perm_read, $this->_name))
			return false;

		/* NOTE: We're not checking 'field_ts' permissions. This is a feature: You can still filter by time range,
		 * even if the user cannot read the time field.
		 */

		/* Get foreign table and fields information */
		$foreign_table = substr($chart['fields'], 0, -3);

		/* Check the READ permission for the foreign table */
		if (!$this->security->perm_check($this->_security_perms, $this->security->perm_read, $foreign_table))
			return false;

		$foreign_fields = $this->_get_table_fields($foreign_table);
		$chart['foreign_field'] = $foreign_fields[1];

		/* Check the READ permissions for the foreign field */
		if (!$this->security->perm_check($this->_security_perms, $this->security->perm_read, $foreign_table, $chart['foreign_field']))
			return false;

		/* Fetch data from database */
		/* TODO: FIXME: Check $_rel_table_fields_aliases for field concatenations... */
		/* TODO: FIXME: Also add table prefixes on select() to avoid ambiguous field names */
		$this->db->select('`' . $foreign_table . '`.`' . $foreign_fields[1] . '`,COUNT(`' . $foreign_table . '`.`'. $foreign_fields[1] . '`) AS `total`', false);
		$this->db->from($this->_name);
		$this->db->join($foreign_table, '`' . $foreign_table . '`.`id` = `' . $this->_name . '`.`' . $chart['fields'] . '`', 'left');
		$this->db->group_by($foreign_fields[1]);

		/* Check if we need to narrow our result based on start timestamp value */
		if ($chart['start_ts']) {
			if (strstr($chart['start_ts'], ' ')) {
				/* If there's a space in the time value, then we'll search for interval formats */
				$interval_fields = $this->_get_interval_fields($chart['start_ts']);

				if (!$interval_fields)
					$this->response->code('500', NDPHP_LANG_MOD_INVALID_SEARCH_INTERVAL_FMT, $this->_default_charset, !$this->request->is_ajax());

				$this->db->where($chart['field_ts'] . ' >=', 'NOW() ' . $interval_fields[0] . ' INTERVAL ' . $interval_fields[1] . ' ' . $interval_fields[2]);
			} else {
				/* Otherwise, it is expected that the value is numeric and represent a timestamp since epoch (1970-01-01 00:00:00 UTC) */
				$this->db->where($chart['field_ts'] . ' >=', $chart['start_ts']);
			}
		}

		/* Check if we need to narrow our result based on end timestamp value */
		if ($chart['end_ts']) {
			if (strstr($chart['end_ts'], ' ')) {
				/* If there's a space in the time value, then we'll search for interval formats */
				$interval_fields = $this->_get_interval_fields($chart['end_ts']);

				if (!$interval_fields)
					$this->response->code('500', NDPHP_LANG_MOD_INVALID_SEARCH_INTERVAL_FMT, $this->_default_charset, !$this->request->is_ajax());

				$this->db->where($chart['field_ts'] . ' <=', 'NOW() ' . $interval_fields[0] . ' INTERVAL ' . $interval_fields[1] . ' ' . $interval_fields[2]);
			} else {
				/* Otherwise, it is expected that the value is numeric and represent a timestamp since epoch (1970-01-01 00:00:00 UTC) */
				$this->db->where($chart['field_ts'] . ' <=', $chart['end_ts']);
			}
		}

		/* Also filter rows based on $_table_row_filtering_config */
		if ($this->_table_row_filtering) {
			$table_fields = $this->_get_table_fields($this->_name);

			foreach ($this->_table_row_filtering_config as $field => $svar) {
				if (in_array($field, $table_fields))
					$this->db->where($field, $this->_session_data[$svar]);
			}
		}

		/* Check if there are additional filters to append to the query WHERE component */
		if ($chart['result_query']) {
			/* Decode and decipher the query */
			$result_query = gzuncompress($this->encrypt->decode($this->ndphp->safe_b64decode(rawurldecode($chart['result_query']))));

			$matches = NULL;

			/* Get the WHERE component of the query */
			if (preg_match('/^.+\s+WHERE\s+(.+)\s+GROUP BY.+$/', $result_query, $matches)) {
				/* Append the WHERE component to the current query. */
				/* NOTE: The 1 = 1 is a fail safe, in case the WHERE wasn't initialized yet */
				$this->db->where('1 =', '1', false);
				$this->db->where_append(' AND (' . $matches[1] . ')');
			}
		}

		/* Fetch data (effectively) */
		$q = $this->db->get();

		/* Check if there's anything to generate */
		if (!$q->num_rows())
			return false;

		/* Build chart dataset object */
		$dataset = $this->chart_build_dataset_rel($chart, $q);

		/* Build chart image object */
		$pimage = $this->chart_build_image_rel($chart, $dataset);

		switch ($chart['geometry']) {
			case 'pie': {
				/* Pie charts require a specific pPie class instantiated */
				$pie = new pPie($pimage, $dataset);

				/* Draw the pie */
				$pie->draw2DPie($this->_charts_canvas_width / 2, $this->_charts_canvas_height / 2, array(
					'DrawLabels'	 => true,
					'Border'		 => true,
					'RecordImageMap' => true
				));
			} break;

			case 'bar': {
				/* Set the chart area within canvas */
				$pimage->setGraphArea($this->_charts_graph_area['X1'], $this->_charts_graph_area['Y1'], $this->_charts_graph_area['X2'], $this->_charts_graph_area['Y2']);

				/* Draw scale... */
				$pimage->drawScale(array(
					'DrawXLines'	=> false,
					'DrawYLines' 	=> ALL,
					'GridTicks'		=> 0,
					'GridR'			=> 200,
					'GridG'			=> 200,
					'GridB'			=> 200
				));

				/* Dar the bar chart */
				$pimage->drawBarChart(array(
					'RecordImageMap' => true
				));
			} break;

			default: {
				$this->response->code('500', NDPHP_LANG_MOD_INVALID_CHART_GEOMETRY . ': ' . $chart['geometry'], $this->_default_charset, !$this->request->is_ajax());
			}
		}

		/* All good... return the image object */
		return $pimage;
	}

	private function _chart_generate_foreign_rel(&$chart) {
		/* Check if there is READ permission for this controller table */
		if (!$this->security->perm_check($this->_security_perms, $this->security->perm_read, $this->_name))
			return false;

		/* Check if there is READ permission for foreign table */
		if (substr($chart['ftable'], 0, 6) != 'mixed_') {
			if (!$this->security->perm_check($this->_security_perms, $this->security->perm_read, $chart['ftable']))
				return false;
		}

		/* NOTE: We're not checking 'field_ts' permissions. This is a feature: You can still filter by time range,
		 * even if the user cannot read the time field.
		 */

		$foreign_table = substr($chart['fields'], 0, -3);

		$foreign_fields = $this->_get_table_fields($foreign_table);
		$chart['foreign_field'] = $foreign_fields[1];

		/* Check the READ permissions for the foreign field */
		if (!$this->security->perm_check($this->_security_perms, $this->security->perm_read, $foreign_table, $chart['foreign_field']))
			return false;

		/* Grant that the user requesting this chart has permissions to read $entry_id on $this->_name table. */
		$this->db->select('id');
		$this->db->from($this->_name);
		$this->db->where('id', $chart['entry_id']);

		if ($this->_table_row_filtering) {
			$table_fields = $this->_get_table_fields($this->_name);

			foreach ($this->_table_row_filtering_config as $field => $svar) {
				if (in_array($field, $table_fields))
					$this->db->where($field, $this->_session_data[$svar]);
			}
		}

		$qfilter = $this->db->get();

		if (!$qfilter->num_rows())
			return false;

		/* Fetch data from database */
		/* TODO: FIXME: Check $_rel_table_fields_aliases for field concatenations... */
		/* TODO: FIXME: Also add table prefixes on select() to avoid ambiguous field names */
		$this->db->select('`' . $foreign_table . '`.`' . $foreign_fields[1] . '`,COUNT(`' . $foreign_table . '`.`' . $foreign_fields[1] . '`) AS `total`', false);
		$this->db->from($chart['ftable']);
		$this->db->join($foreign_table, '`' . $foreign_table . '`.`id` = `' . $chart['ftable'] . '`.`' . $chart['fields'] . '`', 'left');
		//$this->db->join($this->_name, '`' . $this->_name . '`.`id` = `' . $chart['ftable'] . '`.`' . $this->_name . '_id`', 'left');
		$this->db->group_by($foreign_fields[1]);
		$this->db->where($chart['ftable'] . '`.`' . $this->_name . '_id`', $chart['entry_id']);

		/* Check if we need to narrow our result based on start timestamp value */
		if ($chart['start_ts']) {
			if (strstr($chart['start_ts'], ' ')) {
				/* If there's a space in the time value, then we'll search for interval formats */
				$interval_fields = $this->_get_interval_fields($chart['start_ts']);

				if (!$interval_fields)
					$this->response->code('500', NDPHP_LANG_MOD_INVALID_SEARCH_INTERVAL_FMT, $this->_default_charset, !$this->request->is_ajax());

				$this->db->where($chart['field_ts'] . ' >=', 'NOW() ' . $interval_fields[0] . ' INTERVAL ' . $interval_fields[1] . ' ' . $interval_fields[2]);
			} else {
				/* Otherwise, it is expected that the value is numeric and represent a timestamp since epoch (1970-01-01 00:00:00 UTC) */
				$this->db->where($chart['field_ts'] . ' >=', $chart['start_ts']);
			}
		}

		/* Check if we need to narrow our result based on end timestamp value */
		if ($chart['end_ts']) {
			if (strstr($chart['end_ts'], ' ')) {
				/* If there's a space in the time value, then we'll search for interval formats */
				$interval_fields = $this->_get_interval_fields($chart['end_ts']);

				if (!$interval_fields)
					$this->response->code('500', NDPHP_LANG_MOD_INVALID_SEARCH_INTERVAL_FMT, $this->_default_charset, !$this->request->is_ajax());

				$this->db->where($chart['field_ts'] . ' <=', 'NOW() ' . $interval_fields[0] . ' INTERVAL ' . $interval_fields[1] . ' ' . $interval_fields[2]);
			} else {
				/* Otherwise, it is expected that the value is numeric and represent a timestamp since epoch (1970-01-01 00:00:00 UTC) */
				$this->db->where($chart['field_ts'] . ' <=', $chart['end_ts']);
			}
		}

		/* Also filter rows based on foreign controller $_table_row_filtering_config if ftable isn't mixed */
		if (substr($chart['ftable'], 0, 6) != 'mixed_') {
			$fctrl = $this->access->controller($chart['ftable']);

			if ($fctrl->config['table_row_filtering']) {
				$table_fields = $this->_get_table_fields($chart['ftable']);

				/* NOTE: here, we fetch the configuration of the foreign controller, not the filtering config of $this controller  */
				foreach ($fctrl->config['table_row_filtering_config'] as $field => $svar) {
					if (in_array($field, $table_fields))
						$this->db->where($chart['ftable'] . '.' . $field, $this->_session_data[$svar]);
				}
			}
		}

		/* Fetch data (effectively) */
		$q = $this->db->get();

		/* Check if there's anything to generate */
		if (!$q->num_rows())
			return false;

		/* Build chart dataset object */
		$dataset = $this->chart_build_dataset_foreign_rel($chart, $q);

		/* Build chart image object */
		$pimage = $this->chart_build_image_foreign_rel($chart, $dataset);

		switch ($chart['geometry']) {
			case 'pie': {
				/* Pie charts require a specific pPie class instantiated */
				$pie = new pPie($pimage, $dataset);

				/* Draw the pie */
				$pie->draw2DPie($this->_charts_canvas_width / 2, $this->_charts_canvas_height / 2, array(
					'DrawLabels'	=> true,
					'Border'		=> true,
					'RecordImageMap' => true
				));
			} break;

			case 'bar': {
				/* Set the chart area within canvas */
				$pimage->setGraphArea($this->_charts_graph_area['X1'], $this->_charts_graph_area['Y1'], $this->_charts_graph_area['X2'], $this->_charts_graph_area['Y2']);

				/* Draw scale... */
				$pimage->drawScale(array(
					'DrawXLines'	=> false,
					'DrawYLines' 	=> ALL,
					'GridTicks'		=> 0,
					'GridR'			=> 200,
					'GridG'			=> 200,
					'GridB'			=> 200
				));

				/* Dar the bar chart */
				$pimage->drawBarChart(array(
					'RecordImageMap' => true
				));
			} break;

			default: {
				$this->response->code('500', NDPHP_LANG_MOD_INVALID_CHART_GEOMETRY . ': ' . $chart['geometry'], $this->_default_charset, !$this->request->is_ajax());
			}
		}

		/* All good... return the image object */
		return $pimage;
	}

	private function _chart_generate_totals(&$chart) {
		/* Check if there is READ permission for this controller table */
		if (!$this->security->perm_check($this->_security_perms, $this->security->perm_read, $this->_name))
			return false;

		/* NOTE: We're not checking 'field_ts' permissions. This is a feature: You can still filter by time range,
		 * even if the user cannot read the time field.
		 */

		/* Check if user has READ access to total field */
		if ($chart['field_tot'] && !$this->security->perm_check($this->_security_perms, $this->security->perm_read, $this->_name, $chart['field_tot']))
			$chart['field_tot'] = NULL; /* Remove the total field */

		/* Check permissions for abscissa */
		if (!$this->security->perm_check($this->_security_perms, $this->security->perm_read, $this->_name, $chart['fields'][0]))
			return false; /* If there are no permissions to read abcissa field, the chart cannot be displayed for this user */

		/* Check permissions for the remaining fields */
		$fields_filtered = array($chart['fields'][0]);
		foreach (array_slice($chart['fields'], 1) as $field) {
			if ($this->security->perm_check($this->_security_perms, $this->security->perm_read, $this->_name, $field))
				array_push($fields_filtered, $field);
		}
		$chart['fields'] = $fields_filtered;

		/* Check if there's anything to plot */
		if (!count($chart['fields']))
			return false;

		/* Check if there's a total field set */
		if ($chart['field_tot']) {
			$this->db->select(implode(',', $chart['fields']) . ',' . $chart['field_tot'] . ' AS total');
		} else {
			$this->db->select(implode(',', $chart['fields']));
		}
		$this->db->from($this->_name);

		/* Check if we need to narrow our result based on start timestamp value */
		if ($chart['start_ts']) {
			if (strstr($chart['start_ts'], ' ')) {
				/* If there's a space in the time value, then we'll search for interval formats */
				$interval_fields = $this->_get_interval_fields($chart['start_ts']);

				if (!$interval_fields)
					$this->response->code('500', NDPHP_LANG_MOD_INVALID_SEARCH_INTERVAL_FMT, $this->_default_charset, !$this->request->is_ajax());

				$this->db->where($chart['field_ts'] . ' >=', 'NOW() ' . $interval_fields[0] . ' INTERVAL ' . $interval_fields[1] . ' ' . $interval_fields[2]);
			} else {
				/* Otherwise, it is expected that the value is numeric and represent a timestamp since epoch (1970-01-01 00:00:00 UTC) */
				$this->db->where($chart['field_ts'] . ' >=', $chart['start_ts']);
			}
		}

		/* Check if we need to narrow our result based on end timestamp value */
		if ($chart['end_ts']) {
			if (strstr($chart['end_ts'], ' ')) {
				/* If there's a space in the time value, then we'll search for interval formats */
				$interval_fields = $this->_get_interval_fields($chart['end_ts']);

				if (!$interval_fields)
					$this->response->code('500', NDPHP_LANG_MOD_INVALID_SEARCH_INTERVAL_FMT, $this->_default_charset, !$this->request->is_ajax());

				$this->db->where($chart['field_ts'] . ' <=', 'NOW() ' . $interval_fields[0] . ' INTERVAL ' . $interval_fields[1] . ' ' . $interval_fields[2]);
			} else {
				/* Otherwise, it is expected that the value is numeric and represent a timestamp since epoch (1970-01-01 00:00:00 UTC) */
				$this->db->where($chart['field_ts'] . ' <=', $chart['end_ts']);
			}
		}

		/* Also filter rows based on $_table_row_filtering_config */
		if ($this->_table_row_filtering) {
			$table_fields = $this->_get_table_fields($this->_name);

			foreach ($this->_table_row_filtering_config as $field => $svar) {
				if (in_array($field, $table_fields))
					$this->db->where($field, $this->_session_data[$svar]);
			}
		}

		/* Check if there are additional filters to append to the query WHERE component */
		if ($chart['result_query']) {
			/* Decode and decipher the query */
			$result_query = gzuncompress($this->encrypt->decode($this->ndphp->safe_b64decode(rawurldecode($chart['result_query']))));

			$matches = NULL;

			/* Get the WHERE component of the query */
			if (preg_match('/^.+\s+WHERE\s+(.+)\s+GROUP BY.+$/', $result_query, $matches)) {
				/* Append the WHERE component to the current query. */
				/* NOTE: The 1 = 1 is a fail safe, in case the WHERE wasn't initialized yet */
				$this->db->where('1 =', '1', false);
				$this->db->where_append(' AND (' . $matches[1] . ')');
			}
		}

		/* Fetch data (effectively) */
		$q = $this->db->get();

		/* Check if there's anything to generate */
		if (!$q->num_rows())
			return false;

		/* Build chart dataset object */
		$dataset = $this->chart_build_dataset_totals($chart, $q);

		/* Build chart image object */
		$pimage = $this->chart_build_image_totals($chart, $dataset);

		switch ($chart['geometry']) {
			case 'pie': {
				/* Pie charts require a specific pPie class instantiated */
				$pie = new pPie($pimage, $dataset);

				/* Draw the pie */
				$pie->draw2DPie($this->_charts_canvas_width / 2, $this->_charts_canvas_height / 2, array(
					'DrawLabels'	=> true,
					'Border'		=> true,
					'RecordImageMap' => true
				));
			} break;

			case 'bar': {
				/* Set the chart area within canvas */
				$pimage->setGraphArea($this->_charts_graph_area['X1'], $this->_charts_graph_area['Y1'], $this->_charts_graph_area['X2'], $this->_charts_graph_area['Y2']);

				/* Draw scale... */
				$pimage->drawScale(array(
					'DrawXLines'	=> false,
					'DrawYLines' 	=> ALL,
					'GridTicks'		=> 0,
					'GridR'			=> 200,
					'GridG'			=> 200,
					'GridB'			=> 200
				));

				/* Dar the bar chart */
				$pimage->drawBarChart(array(
					'RecordImageMap' => true
				));

				/* Draw Legend */
				$pimage->drawLegend($this->_charts_canvas_width - 80, 20, array(
					'R'		=> 255,
					'G'		=> 255,
					'B'		=> 255,
					'Alpha'	=> 0
				));
			} break;

			default: {
				$this->response->code('500', NDPHP_LANG_MOD_INVALID_CHART_GEOMETRY . ': ' . $chart['geometry'], $this->_default_charset, !$this->request->is_ajax());
			}
		}

		/* All good... return the image object */
		return $pimage;
	}

	private function _chart_generate_foreign_totals(&$chart) {
		/* Check if there is READ permission for this controller table */
		if (!$this->security->perm_check($this->_security_perms, $this->security->perm_read, $this->_name))
			return false;

		/* Check if there is READ permission on foreign table */
		if (substr($chart['ftable'], 0, 6) != 'mixed_') {
			if (!$this->security->perm_check($this->_security_perms, $this->security->perm_read, $chart['ftable']))
				return false;
		}

		/* NOTE: We're not checking 'field_ts' permissions. This is a feature: You can still filter by time range,
		 * even if the user cannot read the time field.
		 */

		/* Check if user has READ access to total field */
		if ($chart['field_tot'] && !$this->security->perm_check($this->_security_perms, $this->security->perm_read, $chart['ftable'], $chart['field_tot']))
			$chart['field_tot'] = NULL; /* Remove the total field */

		/* Check permissions for abscissa */
		if (substr($chart['ftable'], 0, 6) != 'mixed_') {
			if (!$this->security->perm_check($this->_security_perms, $this->security->perm_read, $chart['ftable'], $chart['fields'][0]))
				return false; /* If there are no permissions to read abcissa field, the chart cannot be displayed for this user */
		}

		/* Grant that the user requesting this chart has permissions to read $entry_id on $this->_name table. */
		$this->db->select('id');
		$this->db->from($this->_name);
		$this->db->where('id', $chart['entry_id']);

		if ($this->_table_row_filtering) {
			$table_fields = $this->_get_table_fields($this->_name);

			foreach ($this->_table_row_filtering_config as $field => $svar) {
				if (in_array($field, $table_fields))
					$this->db->where($field, $this->_session_data[$svar]);
			}
		}

		$qfilter = $this->db->get();

		if (!$qfilter->num_rows())
			return false;

		/* Check permissions for the remaining fields if this isn't a mixed table */
		if (substr($chart['ftable'], 0, 6) != 'mixed_') {
			$fields_filtered = array($chart['fields'][0]);

			foreach (array_slice($chart['fields'], 1) as $field) {
				if ($this->security->perm_check($this->_security_perms, $this->security->perm_read, $chart['ftable'], $field))
					array_push($fields_filtered, $field);
			}

			$chart['fields'] = $fields_filtered;
		}

		/* Check if there's anything to plot */
		if (!count($chart['fields']))
			return false;

		/* Check if there's a total field set */
		if ($chart['field_tot']) {
			$this->db->select(implode(',', $chart['fields']) . ',' . $chart['field_tot'] . ' AS total');
		} else {
			$this->db->select(implode(',', $chart['fields']));
		}
		$this->db->from($chart['ftable']);
		$this->db->where($this->_name . '_id', $chart['entry_id']);

		/* Check if we need to narrow our result based on start timestamp value */
		if ($chart['start_ts']) {
			if (strstr($chart['start_ts'], ' ')) {
				/* If there's a space in the time value, then we'll search for interval formats */
				$interval_fields = $this->_get_interval_fields($chart['start_ts']);

				if (!$interval_fields)
					$this->response->code('500', NDPHP_LANG_MOD_INVALID_SEARCH_INTERVAL_FMT, $this->_default_charset, !$this->request->is_ajax());

				$this->db->where($chart['field_ts'] . ' >=', 'NOW() ' . $interval_fields[0] . ' INTERVAL ' . $interval_fields[1] . ' ' . $interval_fields[2]);
			} else {
				/* Otherwise, it is expected that the value is numeric and represent a timestamp since epoch (1970-01-01 00:00:00 UTC) */
				$this->db->where($chart['field_ts'] . ' >=', $chart['start_ts']);
			}
		}

		/* Check if we need to narrow our result based on end timestamp value */
		if ($chart['end_ts']) {
			if (strstr($chart['end_ts'], ' ')) {
				/* If there's a space in the time value, then we'll search for interval formats */
				$interval_fields = $this->_get_interval_fields($chart['end_ts']);

				if (!$interval_fields)
					$this->response->code('500', NDPHP_LANG_MOD_INVALID_SEARCH_INTERVAL_FMT, $this->_default_charset, !$this->request->is_ajax());

				$this->db->where($chart['field_ts'] . ' <=', 'NOW() ' . $interval_fields[0] . ' INTERVAL ' . $interval_fields[1] . ' ' . $interval_fields[2]);
			} else {
				/* Otherwise, it is expected that the value is numeric and represent a timestamp since epoch (1970-01-01 00:00:00 UTC) */
				$this->db->where($chart['field_ts'] . ' <=', $chart['end_ts']);
			}
		}

		/* Also filter rows based on foreign controller $_table_row_filtering_config if table isn't mixed */
		if (substr($chart['ftable'], 0, 6) != 'mixed_') {
			$fctrl = $this->access->controller($chart['ftable']);

			if ($fctrl->config['table_row_filtering']) {
				$table_fields = $this->_get_table_fields($chart['ftable']);

				/* NOTE: here, we fetch the configuration of the foreign controller, not the filtering config of $this controller  */
				foreach ($fctrl->config['table_row_filtering_config'] as $field => $svar) {
					if (in_array($field, $table_fields))
						$this->db->where($chart['ftable'] . '.' . $field, $this->_session_data[$svar]);
				}
			}
		}

		/* Fetch data (effectively) */
		$q = $this->db->get();

		/* Check if there's anything to generate */
		if (!$q->num_rows())
			return false;

		/* Build chart dataset object */
		$dataset = $this->chart_build_dataset_foreign_totals($chart, $q);

		/* Build chart image object */
		$pimage = $this->chart_build_image_foreign_totals($chart, $dataset);

		switch ($chart['geometry']) {
			case 'pie': {
				/* Pie charts require a specific pPie class instantiated */
				$pie = new pPie($pimage, $dataset);

				/* Draw the pie */
				$pie->draw2DPie($this->_charts_canvas_width / 2, $this->_charts_canvas_height / 2, array(
					'DrawLabels'	=> true,
					'Border'		=> true,
					'RecordImageMap' => true
				));
			} break;

			case 'bar': {
				/* Set the chart area within canvas */
				$pimage->setGraphArea($this->_charts_graph_area['X1'], $this->_charts_graph_area['Y1'], $this->_charts_graph_area['X2'], $this->_charts_graph_area['Y2']);

				/* Draw scale... */
				$pimage->drawScale(array(
					'DrawXLines'	=> false,
					'DrawYLines' 	=> ALL,
					'GridTicks'		=> 0,
					'GridR'			=> 200,
					'GridG'			=> 200,
					'GridB'			=> 200
				));

				/* Dar the bar chart */
				$pimage->drawBarChart(array(
					'RecordImageMap' => true
				));

				/* Draw Legend */
				$pimage->drawLegend($this->_charts_canvas_width - 80, 20, array(
					'R'		=> 255,
					'G'		=> 255,
					'B'		=> 255,
					'Alpha'	=> 0
				));
			} break;

			default: {
				$this->response->code('500', NDPHP_LANG_MOD_INVALID_CHART_GEOMETRY . ': ' . $chart['geometry'], $this->_default_charset, !$this->request->is_ajax());
			}
		}

		/* All good... return the image object */
		return $pimage;
	}

	protected function _chart_render_image($chart_array, $chart_id) {
		$chart_array[$chart_id]['pimage']->Stroke();
	}

	protected function _chart_generate_generic(&$chart_array, $chart_id) {
		$chart = $chart_array[$chart_id];

		/* Check if there are any custom chart types defined */
		if (in_array($chart['type'], $this->_charts_types)) {
			/* Grant that the required handlers to generate the chart exist */
			if (!method_exists($this, '_chart_generate_' . $chart['type']))
				$this->response->code('500', NDPHP_LANG_MOD_UNDEFINED_METHOD . ': _chart_generate_' . $chart['type'] . '().', $this->_default_charset, !$this->request->is_ajax());

			/* Grant that the chart type only contains alphanumeric and underscore characters */
			if (!preg_match('/^[A-Za-z0-9\_]+$/', $chart['type']))
				$this->response->code('500', NDPHP_LANG_MOD_INVALID_CHART_TYPE_NAME, $this->_default_charset, !$this->request->is_ajax());

			/* Generate the chart based on the custom handlers */
			eval('$chart_array[$chart_id][\'pimage\'] = $this->_chart_generate_' . $chart['type'] . '($chart);');

			/* Check if the chart was generated */
			if ($chart_array[$chart_id]['pimage'] === false)
				return false;
		} else {
			/* The requested chart type is undefined or invalid */
			$this->response->code('500', NDPHP_LANG_MOD_INVALID_CHART_TYPE . ': ' . $chart['type'], $this->_default_charset, !$this->request->is_ajax());
		}

		/* All good */
		return true;
	}

	protected function _chart_publish_generic(&$chart_array, $chart_id = 0, $entry_id = NULL, $refresh_rand = NULL, $result_query = NULL, $imagemap = NULL, $start_ts = NULL, $end_ts = NULL) {
		/* NOTE: The $refresh_rand argument should be a random value in order to force browsers to reload the image */

		/* Setup charts */
		$this->_charts_config();

		/* Check if the requested chart id is defined */
		if ($chart_id >= count($chart_array))
			$this->response->code('404', NDPHP_LANG_MOD_UNDEFINED_CHART_ID, $this->_default_charset, !$this->request->is_ajax());

		/* Set chart id to chart object */
		$chart_array[$chart_id]['id'] = $chart_id;

		/* Is this a imagemap request? */
		if ($imagemap !== NULL)
			$chart_array[$chart_id]['imagemap_request'] = true;

		/* Check if there's a result query that will apply additional filters on the chart (not available for imported nor foreign charts) */
		if ($result_query !== NULL && !$chart_array[$chart_id]['imported'])
			$chart_array[$chart_id]['result_query'] = $result_query;

		/* Check if we need to override the default start timestamp */
		if ($start_ts !== NULL)
			$chart_array[$chart_id]['start_ts'] = $start_ts;

		/* Check if we need to override the default end timestamp */
		if ($end_ts !== NULL)
			$chart_array[$chart_id]['end_ts'] = $end_ts;

		if ($entry_id !== NULL)
			$chart_array[$chart_id]['entry_id'] = $entry_id;

		/* If the chart is imported, fetch it from the foreign controller */
		if ($chart_array[$chart_id]['imported']) {
			/* TODO: FIXME: Currently, we need to perform a REST API JSON call to import the chart... This is likely to be improved in the future */
			$this->db->select('apikey AS _apikey,id AS _userid');
			$this->db->from('users');
			$this->db->where('id', $this->_session_data['user_id']);
			$q = $this->db->get();
			$rest_auth = json_encode($q->row_array());

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, base_url() . 'index.php/' . $chart_array[$chart_id]['imp_ctrl'] . '/chart_publish/' . $chart_array[$chart_id]['imp_id'] . '/' . mt_rand(100000, 999999));
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $rest_auth);
			curl_exec($ch);
			curl_close($ch);
		} else {
			/* Otherwise... Process all data and generate all the objects for $chart_id */
			if ($this->_chart_generate_generic($chart_array, $chart_id) === false)
				return false;

			/* Render the chart and deliver it to the client */
			$this->_chart_render_image($chart_array, $chart_id);
		}
	}

	protected function _chart_render_nodata($title) {
		/* FIXME: This method shall receive the entire $chart array, not just the title.
		 * Settings such as non-default chart width and height should be retrieve from chart array.
		 */

		/* Set font sizes */
		$font_size_title  = 3;
		$font_size_nodata = 10;

		/* Set the no data warning message */
		$nodata_msg = NDPHP_LANG_MOD_EMPTY_DATA;

		/* Set content type header */
		$this->response->header('Content-Type', 'image/png');

		/* Create canvas */
		$nodata_img = imagecreate($this->_charts_canvas_width, $this->_charts_canvas_height);

		/* Set colors */
		$background_color = imagecolorallocate($nodata_img, 250, 250, 250);
		$color = imagecolorallocate($nodata_img, 180, 180, 180);

		/* Plot strings */
		imagestring($nodata_img, $font_size_title,  ($this->_charts_canvas_width / 2) - ((imagefontwidth($font_size_title)  * strlen($title)) / 2), 30, $title, $color);
		imagestring($nodata_img, $font_size_nodata, ($this->_charts_canvas_width / 2) - ((imagefontwidth($font_size_nodata) * strlen($nodata_msg)) / 2), ($this->_charts_canvas_height / 2) - (imagefontheight($font_size_nodata) / 2), $nodata_msg, $color);

		/* Render image */
		imagepng($nodata_img);

		/* Release resources */
		imagecolordeallocate($nodata_img, $color);
		imagecolordeallocate($nodata_img, $background_color);
		imagedestroy($nodata_img);
	}

	public function chart_publish($chart_id = 0, $refresh_rand = NULL, $result_query = NULL, $imagemap = NULL, $start_ts = NULL, $end_ts = NULL) {
		if ($result_query == 'NULL')
			$result_query = NULL;

		if ($imagemap == 'NULL')
			$imagemap = NULL;

		if ($this->_chart_publish_generic($this->_charts, $chart_id, NULL, $refresh_rand, $result_query, $imagemap, $start_ts, $end_ts) === false) {
			/* No data */
			$this->_chart_render_nodata($this->_charts[$chart_id]['title']);
		}
	}

	public function chart_foreign_publish($chart_id = 0, $entry_id = NULL, $refresh_rand = NULL, $imagemap = NULL, $start_ts = NULL, $end_ts = NULL) {
		if ($imagemap == 'NULL')
			$imagemap = NULL;

		if ($this->_chart_publish_generic($this->_charts_foreign, $chart_id, $entry_id, $refresh_rand, NULL, $imagemap, $start_ts, $end_ts) === false) {
			/* No data */
			$this->_chart_render_nodata($this->_charts_foreign[$chart_id]['title']);
		}
	}

	/** JSON **/

	public function json_doc() {
		if (!$this->security->perm_check($this->_security_perms, $this->security->perm_read, $this->_name))
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_FORBIDDEN, $this->_default_charset, !$this->request->is_ajax());

		/* Setup basic view data */
		$data = $this->_get_view_data_generic('JSON REST API', 'JSON REST API');

		$data['view']['data_fields'] = $this->_get_fields();

		/* Get user api key */
		$this->db->select('apikey');
		$this->db->from('users');
		$this->db->where('id', $this->_session_data['user_id']);
		$q = $this->db->get();
		$userinfo = $q->row_array();

		/* Setup specific view data */
		$data['view']['user_id'] = $this->_session_data['user_id'];
		$data['view']['apikey'] = $userinfo['apikey'];

		/* TODO: Load a table entry (if exists) in order to create valid (and real) cURL examples for insert/update calls */


		/* TODO: FIXME: Missing multiple and mixed relationship documentation */
		$this->load->view('documentation/json', $data);
	}

	protected function json_view($data) {
		$json_res['status'] = true;
		$json_res['data']['fields'] = $data['view']['result_array'];
		$json_res['data']['rel'] = $data['view']['rel'];
		$json_res['data']['mixed'] = array();

		/* Fetch mixed relationship tables */
		$mixed_rels = $this->_get_relative_tables($this->_name, 'mixed');
		
		/* Populate 'mixed' object with all mixed entries found per table */
		foreach ($mixed_rels as $mixed) {
			/* Fetch all entries related to this item from the mixed table */
			$this->db->from($mixed);
			$this->db->where($this->_name . '_id', $data['view']['result_array'][0]['id']); /* Id must always be present */
			$q = $this->db->get();

			$json_res['data']['mixed'][$mixed] = array();

			foreach ($q->result_array() as $row) {
				array_push($json_res['data']['mixed'][$mixed], $row);
			}
		}

		/* Update accounting counters if accounting is enabled */
		if ($this->_accounting)
			$this->accounting->user_counter_increment($this->_session_data['user_id'], 'acct_rest_view');

		/* All good */
		return json_encode($json_res);
	}

	protected function json_list($data) {
		$json_res['status'] = true;
		$json_res['data'] = $data['view']['result_array'];

		/* Update accounting counters if accounting is enabled */
		if ($this->_accounting)
			$this->accounting->user_counter_increment($this->_session_data['user_id'], 'acct_rest_list');

		return json_encode($json_res);
	}

	protected function json_result($data) {
		$json_res['status'] = true;
		$json_res['data'] = $data['view']['result_array'];

		/* Update accounting counters if accounting is enabled */
		if ($this->_accounting)
			$this->accounting->user_counter_increment($this->_session_data['user_id'], 'acct_rest_result');

		return json_encode($json_res);
	}

	protected function json_insert($insert_id) {
		$json_res['status'] = true;
		$json_res['data']['insert_id'] = $insert_id;

		/* Update accounting counters if accounting is enabled */
		if ($this->_accounting)
			$this->accounting->user_counter_increment($this->_session_data['user_id'], 'acct_rest_insert');

		return json_encode($json_res);
	}

	protected function json_update() {
		$json_res['status'] = true;

		/* Update accounting counters if accounting is enabled */
		if ($this->_accounting)
			$this->accounting->user_counter_increment($this->_session_data['user_id'], 'acct_rest_update');

		return json_encode($json_res);
	}

	protected function json_delete($data) {
		$json_res['status'] = true;

		/* Update accounting counters if accounting is enabled */
		if ($this->_accounting)
			$this->accounting->user_counter_increment($this->_session_data['user_id'], 'acct_rest_delete');

		return json_encode($json_res);
	}


	/** View Data **/

	private function _get_views_base_dir($theme) {
		/* Any attempt to manipulate the views path to access parent directories should be blocked */
		if (strpos($theme, '..')) {
			/* We need to fail hard here... */
			$this->response->code('500', NDPHP_LANG_MOD_INVALID_VIEW_BASE_DIR_CHARS, $this->_default_charset, !$this->request->is_ajax());
		}

		/* Craft the views base directory path */
		$views_base_dir = SYSTEM_BASE_DIR . '/application/views/themes/' . $theme;

		/* Grant that the path is valid */
		if (file_exists($views_base_dir))
			return $views_base_dir;

		/* We need to fail hard here... */
		$this->response->code('500', NDPHP_LANG_MOD_UNABLE_VIEW_BASE_DIR, $this->_default_charset, !$this->request->is_ajax());
	}

	protected function _get_view_data_generic($title = 'NO_TITLE', $description = "NO_DESCRIPTION") {
		$data = array();

		/* Configuration data - Used for configuration and control. It should not be used as 'printable' data */
		$data['config'] = array();
		$data['config']['theme'] = $this->_get_theme();
		$data['config']['charset'] = $this->_default_charset;
		$data['config']['features'] = $this->_get_features();
		$data['config']['fk_linking'] = $this->_table_fk_linking;
		$data['config']['truncate'] = array();
		$data['config']['truncate']['length'] = $this->_string_truncate_len;
		$data['config']['truncate']['trail'] = $this->_string_truncate_trail;
		$data['config']['truncate']['separator'] = $this->_string_truncate_sep;
		$data['config']['rich_text'] = $this->_table_field_text_rich;
		$data['config']['charts'] = array();
		$data['config']['charts']['enable_list'] = $this->_charts_enable_list;
		$data['config']['charts']['enable_result'] = $this->_charts_enable_result;
		$data['config']['charts']['enable_view'] = $this->_charts_enable_result;

		/* View data - Data that is intended to be 'printed' on the view */
		$data['view'] = array();
		$data['view']['ctrl'] = $this->_name;
		$data['view']['title'] = $title;
		$data['view']['description'] = $description;
		$data['view']['hname'] = isset($this->_menu_entries_aliases[$this->_name]) ? $this->_menu_entries_aliases[$this->_name] : $this->_viewhname;
		$data['view']['mainmenu'] = $this->_get_menu_entries();
		$data['view']['crud_main_tab_name'] = $this->_view_crud_main_tab_name;
		$data['view']['crud_charts_tab_name'] = $this->_view_crud_charts_tab_name;
		$data['view']['base_dir'] = $this->_get_views_base_dir($data['config']['theme']['name']);

		/* Project data - Aditional project information. May be used as 'printable' data */
		$data['project'] = array();
		$data['project']['author'] = $this->_project_author;
		$data['project']['name'] = $this->_project_name;
		$data['project']['tagline'] = $this->_project_tagline;
		$data['project']['description'] = $this->_project_description;
		$data['project']['build'] = $this->_get_build();
		$data['project']['ndphp_version'] = $this->_ndphp_version;

		/* Session Data - Control data. May be used for 'printing' and for control. */
		$data['session'] = $this->_session_data;

		/* Security Data - Security assessment and control. Not supposed to be used as 'printable' data. */
		$data['security'] = array();
		$data['security']['perms'] = $this->_security_perms;
		$data['security']['im_admin'] = $this->security->im_admin();

		return $data;
	}

	/** User input (POST) **/

	protected function post_changed_fields_list($table, $id, $POST) {
		/* Returns a list of fields whose values differ from the $POST data to the database (stored) data */

		/* Fetch the stored data */
		$this->db->from($table);
		$this->db->where('id', $id);
		$q = $this->db->get();

		/* Check if there are any results */
		if (!$q->num_rows())
			return array();

		$row = $q->row_array();

		$changed_list = array();

		/* Compare the stored data with the $POST data */
		foreach ($POST as $key => $value) {
			if (!isset($row[$key]))
				continue;

			/* Check if there was a change for this field... */
			if ($row[$key] != $value) {
				/* ... and if so, add it to the result. TODO: FIXME: mixed and multiple relationships not yet supported  */
				array_push($changed_list, $key);
			}
		}

		return $changed_list;
	}

	protected function post_changed_fields_data($table, $id, $POST) {
		/* Returns a list of fields, including the changed data, whose values differ,
		 * from the $POST data to the database (stored) data
		 */

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
			if (!isset($row[$key]))
				continue;

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

		return $changed_data;
	}


	/** Interval processing API **/

	protected function _get_interval_fields($input_raw_string) {
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
		switch (iconv($this->_default_charset, 'ASCII//TRANSLIT', $interval_fields[0])) { /* iconv() is used to get rid of accents */
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


	/** Row filtering **/

	protected function _table_row_filter_apply($table = NULL) {
		if ($this->security->im_admin())
			return;

		$field_list = $this->_get_table_fields($table ? $table : $this->_name);

		if ($this->_table_row_filtering === true) {
			foreach ($this->_table_row_filtering_config as $key => $value) {
				if (in_array($key, $field_list))
					$this->db->where(($table ? $table : $this->_name) . '.' . $key, $this->_session_data[$this->_table_row_filtering_config[$key]]);
			}
		}
	}

	protected function _table_row_filter_perm($id = false, $table = NULL, $id_field = 'id') {
		if ($this->security->im_admin())
			return true;

		if ($id === false)
			return false;

		$field_list = $this->_get_table_fields($table ? $table : $this->_name);

		if ($this->_table_row_filtering === true) {
			foreach ($this->_table_row_filtering_config as $key => $value) {
				if (in_array($key, $field_list)) {
					$this->db->select($key);
					$this->db->from($table ? $table : $this->_name);
					$this->db->where(($table ? $table : $this->_name) . '.' . $key, $this->_session_data[$this->_table_row_filtering_config[$key]]);
					$this->db->where(($table ? $table : $this->_name) . '.' . $id_field, $id);
					$query = $this->db->get();

					if (!$query->num_rows())
						return false;

					return true;
				}
			}
		}

		return true;
	}

	protected function _table_row_filter_get($table = NULL) {
		/* Evaluates if there's a table row filter to be applied. If so, an assoc array is created for each configured filtering key */
		if ($this->security->im_admin())
			return array();

		$res = array();

		$field_list = $this->_get_table_fields($table ? $table : $this->_name);

		if ($this->_table_row_filtering === true) {
			foreach ($this->_table_row_filtering_config as $key => $value) {
				if (in_array($key, $field_list)) {
					$res[$key] = $this->_session_data[$value];
				}
			}
		}

		return $res;
	}

	protected function _filter_fields($sec_perms, $req_perm, $fields_array) {
		/* Filter fields from $field_array based on the requested permission ($req_perm) */
		$fields_array_filtered = array();

		foreach ($fields_array as $field => $meta) {
			if ($meta['type'] == 'rel') {
				if (!$this->security->perm_check($sec_perms, $this->security->perm_read, $meta['table'])) /* FIXME: Multiple and mixed use different assignments to $meta['table'] ?? */
					continue;

				if (!$this->security->perm_check($sec_perms, $req_perm, $meta['base_table'], $field))
					continue;
			} else if ($meta['type'] == 'mixed') {
				if (!$this->security->perm_check($sec_perms, $this->security->perm_read, $meta['rel_table']))
					continue;

				if (!$this->security->perm_check($sec_perms, $req_perm, $meta['base_table'], $field))
					continue;
			} else if ($meta['input_type'] == 'select') {
				/* If we don't have permissions to read the foreign table, we won't have permissions to do anything else on this field */
				if (!$this->security->perm_check($sec_perms, $this->security->perm_read, $meta['table']))
					continue;

				/* If we have read permissions on the foreign table, we still need to grant that we have the requested permission on the field */
				if (!$this->security->perm_check($sec_perms, $req_perm, $this->_name, $field))
					continue;
			} else {
				if (!$this->security->perm_check($sec_perms, $req_perm, $this->_name, $field))
					continue;
			}

			$fields_array_filtered[$field] = $meta;
		}

		return $fields_array_filtered;
	}

	protected function _filter_selected_fields($fields_array, $where_array = array(), $hidden_filter = array()) {
		/* Selects (query) the table columns based on $fields_array and applies the $where_array clauses. */
		$selected = '';

		foreach ($fields_array as $field => $meta) {
			/* Multiple and Mixed relationships are not database columns, so skip them */
			if ($meta['type'] == 'rel' || $meta['type'] == 'mixed')
				continue;

			$selected .= $field . ',';
		}

		$selected = rtrim($selected, ',');

		/* Select only the available fields */
		$this->db->select($selected);

		/* Apply the query clauses */
		foreach ($where_array as $field => $value) {
			$this->db->where($field, $value);
		}
	}


	/** Mixed handlers **/

	protected function _mixed_process_post_field($field) {
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

		foreach ($this->_get_relative_tables($this->_name, 'mixed') as $mixed_rel_table) {
			$mixed_rel_foreign_table = array_pop(array_diff($this->_get_mixed_rel_table_names($mixed_rel_table, $this->_name), array($this->_name)));

			/* Check if the field prefix matches the foreign table name */
			if (('mixed_' . $mixed_rel_foreign_table . '_') == substr($field, 0, 7 + strlen($mixed_rel_foreign_table))) {
				$mixed_foreign_table = $mixed_rel_foreign_table;
				break;
			}
		}

		/* If the table cannot be found, we cannot proceed */
		if ($mixed_foreign_table === NULL)
			$this->response->code('500', NDPHP_LANG_MOD_UNABLE_FIND_MIXED_REL_FIELD . ': ' . $field, $this->_default_charset, !$this->request->is_ajax());

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

	protected function _mixed_process_post_data($mixed_rels, $last_id, $ftypes, $remove_existing = false) {
		if (count($mixed_rels) /* If $mixed_rels array is empty, do not insert, remove nor process updates on mixed fields */) {
			if ($remove_existing) {
				/* Remove old mixed relationship entries */
				foreach ($ftypes as $fname => $fmeta) {
					if ($fmeta['type'] == 'mixed') {
						/* Security Permissions Check */
						if (!$this->security->perm_check($this->_security_perms, $this->security->perm_read, $fmeta['rel_table'])) {
							$this->db->trans_rollback();
							$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->_default_charset, !$this->request->is_ajax());
						}
	        	
						$this->db->where($this->_name . '_id', $last_id);
						$this->db->delete($fmeta['table']);
					}
				}
			}
        	
			/* Update mixed relationships */
			$mixed_foreign_value_id = array(); /* Will contain the entry id of foreign single relationship tables... (used by $_mixed_table_add_missing) */

			foreach ($mixed_rels as $mixed_table => $mixed_table_value) {
				foreach ($mixed_table_value as $mixed_id => $mixed_id_value) {
					foreach ($mixed_id_value as $mixed_field => $mixed_field_value) {
						if (($mixed_field_value == '') || ($mixed_field_value == NULL) || isset($mixed_insert_values[$mixed_field]))
							continue;
        	
						/* Check for exceptions, for example, datetime fields are split into _time and _date suffixes */
						if (isset($mixed_rels[$mixed_table][$mixed_id][$mixed_field . '_time'])) {
							$mixed_insert_values[$mixed_field] = $this->timezone->convert($mixed_field_value . ' ' . $mixed_rels[$mixed_table][$mixed_id][$mixed_field . '_time'], $this->_session_data['timezone'], $this->_default_timezone);
						} else if ((substr($mixed_field, -5) == '_time')) {
							$mixed_insert_values[substr($mixed_field, 0, -5)] = $this->timezone->convert($mixed_rels[$mixed_table][$mixed_id][substr($mixed_field, 0, -5)] . ' ' . $mixed_rels[$mixed_table][$mixed_id][$mixed_field], $this->_session_data['timezone'], $this->_default_timezone);
						} else if ((substr($mixed_field, -3) == '_id') && (strpos($mixed_field_value, '_'))) {
							/* Single relationship fields' identifiers on mixed relationships use the <id>_<value> format */
							$mixed_field_val_raw = explode('_', $mixed_field_value);
							$mixed_field_val_id = $mixed_field_val_raw[0];
							$mixed_field_val_value = $mixed_field_val_raw[1];
							$mixed_field_table_name = substr($mixed_field, 0, -3);
        	
							/* Grant that the user has privileges to access the foreign table item */
							if (!$this->_table_row_filter_perm($mixed_field_val_id, $mixed_field_table_name)) {
								$this->db->trans_rollback();
								$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->_default_charset, !$this->request->is_ajax());
							}
        	
							/* Exclude the ID portion of the mixed_field_value and get only corresponding value */
							/* Note that the format of relational mixed fields is: '<id>_<value>' */
							$mixed_insert_values[$mixed_field] = $mixed_field_val_value;

							/* Also store the entry id... this may be required for later use if we need to add a missing entry */
							$mixed_foreign_value_id[$mixed_field_table_name] = $mixed_field_val_id;
						} else {
							$mixed_insert_values[$mixed_field] = $mixed_field_value;
						}
					}
        	
					/* Get optional fields and retrieve the respective values, if any */
					$mixed_table_fields = $this->_get_table_fields('mixed_' . $this->_name . '_' . $mixed_table);
					foreach ($mixed_table_fields as $mixed_field) {
						/* Check if this is a private field */
						if (substr($mixed_field, 0, 2) != '__')
							continue;
        	
						$pmfield = explode('_tc_', substr($mixed_field, 2));
        	
						$ftname = $pmfield[0];	/* Foreign Table name */
						$ftcname = $pmfield[1];	/* Foreign Table Column name */
						
						$this->db->select($ftcname);
						$this->db->distinct();	/* NOTE: Not required since the key we're looking for must be UNIQUE. */
						$this->db->from($ftname);
						$this->db->where('id', strstr($mixed_rels[$mixed_table][$mixed_id][$ftname . '_id'], '_', true));
						$this->_table_row_filter_apply($ftname);
        	
						$query_mixed = $this->db->get();
        	
						/* If empty, there's no permissions */
						if (!$query_mixed->num_rows()) {
							$this->db->trans_rollback();
							$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->_default_charset, !$this->request->is_ajax());
						}
        	
						$row_mixed = $query_mixed->row_array();
						$mixed_insert_values[$mixed_field] = $row_mixed[$ftcname];
					}
        	
					/* Check if all secondary relational table foreign keys exist */
					$srtf_field_found = false;
					foreach ($mixed_insert_values as $field => $value) {
						if ($field == $mixed_table_fields[3]) {
							$srtf_field_found = true;
							break;
						}
					}

					if ($srtf_field_found === false) {
						/* Before checking for a matching key, validate if there's any value to search for... */
						if (!isset($mixed_insert_values[$mixed_table_fields[1]]) || $mixed_insert_values[$mixed_table_fields[1]] === NULL || $mixed_insert_values[$mixed_table_fields[1]] == '')
							continue; /* Nothing to be done with this entry as the key identifier isn't set or it's empty. */

						/* Update mixed_insert_values with missing relational field */
						$this->db->select('id');
						$this->db->from(substr($mixed_table_fields[3], 0, -3));
						$this->db->where($mixed_table_fields[1], $mixed_insert_values[$mixed_table_fields[1]]);

						$srtf_query = $this->db->get();

						if (!$srtf_query->num_rows()) {
							/* If $_mixed_table_add_missing is true, insert the element on the foreign table */
							if ($this->_mixed_table_add_missing === true) {
								/* Clone the values to be inserted on mixed table */
								$secondary_insert_values = $mixed_insert_values;

								/* Unset relationships */
								unset($secondary_insert_values[$mixed_table_fields[2]]);
								unset($secondary_insert_values[$mixed_table_fields[3]]);

								/* Clear any _tc_ fields */
								foreach ($secondary_insert_values as $field => $value) {
									if (count(explode('_tc_', $field)) > 1) {
										unset($secondary_insert_values[$field]);
									}
								}

								/* Resolve single relationships back to their original entry id */
								foreach ($secondary_insert_values as $field => $value) {
									/* Ignore fields that are not single relationships */
									if (substr($field, -3) != '_id')
										continue;

									/* Fetch the previously stored entry id related to this single relationship */
									$secondary_insert_values[$field] = $mixed_foreign_value_id[substr($field, -3)];
								}

								/* Set any existing filtering fields */
								$secondary_insert_values = array_merge($secondary_insert_values, $this->_table_row_filter_get($mixed_table));

								/* Insert data into the secondary table */
								$this->db->insert($mixed_table, $secondary_insert_values);

								/* Set the newly inserted id as the mixed relationship id */
								$mixed_insert_values[$mixed_table_fields[3]] = $this->db->last_insert_id();
							} else if (isset($this->_mixed_table_set_missing[$mixed_table])) {
								/* There's a default id to be used associated to this mixed table */
								$mixed_insert_values[$mixed_table_fields[3]] = $this->_mixed_table_set_missing[$mixed_table];
							} else {
								$this->db->trans_rollback();
								$this->response->code('403', NDPHP_LANG_MOD_INVALID_MIXED_VALUE, $this->_default_charset, !$this->request->is_ajax());
							}
						} else {
							$row = $srtf_query->row_array();

							$mixed_insert_values[$mixed_table_fields[3]] = $row['id'];
						}
					}

					/* If there's anything to be inserted, do it */
					if (isset($mixed_insert_values)) {
						/* Security Permissions Check */
						if (!$this->security->perm_check($this->_security_perms, $this->security->perm_read, $mixed_table)) {
							$this->db->trans_rollback();
							$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->_default_charset, !$this->request->is_ajax());
						}

						$mixed_insert_values[$this->_name . '_id'] = $last_id;
						$this->db->insert('mixed_' . $this->_name . '_' . $mixed_table, $mixed_insert_values);
						unset($mixed_insert_values);
					}
				}
			}
		}
	}


	/** Scheduler **/

	private function _scheduler_exec_queued_entries() {
		/* Execute scheduled entries */
		foreach ($this->_scheduler['queue'] as $entry) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $entry['url']);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$ret = curl_exec($ch);
			curl_close($ch);

			/* Check if $row['next_run_val'] date is in the past... if so, we need to keep adding period until we
			 * get a date pointing to the future.
			 */
			if ($entry['next_run_val'] !== NULL && $entry['next_run_val'] > 1451606400) { /* next run must be at least close to this epoch to be considered valid */
				while ($entry['next_run_val'] < time())
					$entry['next_run_val'] += $entry['period'];
			} else {
				/* Othersite, set it to the current time */
				$entry['next_run_val'] = time();
			}

			/* Initialize transaction */
			$this->db->trans_begin();

			$this->db->where('id', $entry['id']);
			$this->db->update('scheduler', array(
				'last_run' => date('Y-m-d H:i:s'),
				'next_run' => date('Y-m-d H:i:s', $entry['next_run_val']),
				'output' => strip_tags($ret),
				'queued' => false
			));

			/* Check if transaction succeeded */
			if ($this->db->trans_status() === false) {
				$this->db->trans_rollback();

				error_log('_scheduler_exec_queued_entries(): Failed to execute scheduled entry: ' . $entry['id']);
			}

			/* Commit transaction */
			$this->db->trans_commit();
		}

		/* Reset scheduled entries */
		$this->_sched_entries = array();
	}

	private function _scheduler_process() {
		/* Initialize transaction */
		$this->db->trans_begin();

		/* Fetch scheduler entries requiring immediate processing */
		$q = $this->db->query('SELECT *,UNIX_TIMESTAMP(DATE_ADD(next_run, INTERVAL period SECOND)) AS next_run_val FROM scheduler WHERE active = 1 AND queued = 0 AND (next_run <= NOW() OR next_run IS NULL)');

		/* Nothing to process */
		if (!$q->num_rows()) {
			$this->db->trans_commit();
			return;
		}

		/* Re-Initialize scheduler entries array */
		$this->_scheduler['queue'] = array();

		/* Populate scheduler entries array */
		foreach ($q->result_array() as $row) {
			array_push($this->_scheduler['queue'], $row);

			/* Set the 'queued' flag to avoid concurrent proccessing for the same entry */
			$this->db->where('id', $row['id']);
			$this->db->update('scheduler', array(
				'queued' => true
			));
		}

		/* Check if transaction succeeded */
		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();

			error_log('_scheduler_process(): Failed to process scheduled entry: ' . $row['id']);
		}

		/* Commit transaction */
		$this->db->trans_commit();

		if ($this->_threading && $this->_scheduler['type'] == 'threaded') {
			/* TODO: Not implemented */
		} else {
			// if ($this->_scheduler['type'] == 'request' || $this->_scheduler['type'] == 'external') ...
			$this->_scheduler_exec_queued_entries();
		}
	}

	public function scheduler_external() {
		/* Grant that only ROLE_ADMIN is able to execute this method */
		if (!$this->security->im_admin())
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_ONLY_ADMIN, $this->_default_charset, !$this->request->is_ajax());

		/* If the scheduler configuration type isn't set as 'external', deny this request */
		if ($this->_scheduler['type'] != 'external')
			$this->response->code('403', NDPHP_LANG_MOD_ATTN_SCHED_NOT_EXTERNAL, $this->_default_charset, !$this->request->is_ajax());

		/* Process and execute scheduled entries */
		$this->_scheduler_process();
	}


	/** Upload handlers **/

	private function _process_file_upload($table, $id, $field) {
		if (!isset($_FILES[$field]['error']) || is_array($_FILES[$field]['error']))
			$this->response->code('500', NDPHP_LANG_MOD_UNABLE_FILE_UPLOAD . ' "' . $_FILES[$field]['name'] . '": ' . NDPHP_LANG_MOD_INVALID_PARAMETERS, $this->_default_charset, !$this->request->is_ajax());

		/* Grant that there are no errors */
		if ($_FILES[$field]['error'] > 0)
			$this->response->code('403', NDPHP_LANG_MOD_UNABLE_FILE_UPLOAD . ' "' . $_FILES[$field]['name'] . '": ' . error_upload_file($_FILES[$field]['error']), $this->_default_charset, !$this->request->is_ajax());

		/* Validate file size (This is a fallback for php settings) */
		if ($_FILES[$field]['size'] > $this->_upload_file_max_size)
			$this->response->code('403', NDPHP_LANG_MOD_UNABLE_FILE_UPLOAD . ' "' . $_FILES[$field]['name'] . '": ' . NDPHP_LANG_MOD_INVALID_SIZE_TOO_BIG, $this->_default_charset, !$this->request->is_ajax());

		/* Craft destination path */
		$dest_path = SYSTEM_BASE_DIR . '/uploads/' . $this->_session_data['user_id'] . '/' . $table . '/' . $id . '/' . $field;

		/* Create directory if it doesn't exist */
		if (mkdir($dest_path, 0750, true) === false)
			$this->response->code('500', NDPHP_LANG_MOD_UNABLE_FILE_UPLOAD . ' "' . $_FILES[$field]['name'] . '": ' . NDPHP_LANG_MOD_UNABLE_CREATE_DIRECTORY, $this->_default_charset, !$this->request->is_ajax());

		/* Compute file hash */
		$file_hash = openssl_digest($_FILES[$field]['name'], 'sha256');

		if (move_uploaded_file($_FILES[$field]['tmp_name'], $dest_path . '/' . $file_hash) === false)
			$this->response->code('403', NDPHP_LANG_MOD_UNABLE_FILE_COPY . ' "' . $_FILES[$field]['name'] . '"', $this->_default_charset, !$this->request->is_ajax());

		/* Encrypt file, if required */
		if ($this->_upload_file_encryption === true) {
			/* FIXME: TODO: For limited type tables, we should use the user's private encryption key here */
			$content_ciphered = $this->encrypt->encode(file_get_contents($dest_path . '/' . $file_hash));
			if (($fp = fopen($dest_path . '/' . $file_hash, 'w')) === false)
				$this->response->code('403', NDPHP_LANG_MOD_UNABLE_FILE_OPEN_WRITE . ' "' . $dest_path . '/' . $file_hash . '"', $this->_default_charset, !$this->request->is_ajax());

			if (fwrite($fp, $content_ciphered) === false)
				$this->response->code('500', NDPHP_LANG_MOD_UNABLE_FILE_WRITE . ' "' . $dest_path . '/' . $file_hash . '"', $this->_default_charset, !$this->request->is_ajax());

			fclose($fp);
		}
	}

	private function _remove_file_upload($table, $id, $field) {
		$dest_path = SYSTEM_BASE_DIR . '/uploads/' . $this->_session_data['user_id'] . '/' . $table . '/' . $id . '/' . $field;

		$this->_rrmdir($dest_path);
	}

	private function _delete_entry_uploads($table, $id) {
		$dest_path = SYSTEM_BASE_DIR . '/uploads/' . $this->_session_data['user_id'] . '/' . $table . '/' . $id;

		$this->_rrmdir($dest_path);
	}

	private function _rrmdir($dir) {
		/* Sanity checks */
		if (strpos($dir, '/..') || substr($dir, 0, 2) == '..')
			return;

		/* Recursively deletes a directory and all its contents */
		if (is_dir($dir)) {
			$objects = scandir($dir);

			foreach ($objects as $object) {
				if (($object != ".") && ($object != "..")) {
					if (filetype($dir . "/" . $object) == "dir") {
						$this->_rrmdir($dir . "/" . $object);
					} else {
						unlink($dir . "/" . $object);
					}
				}
			}

			reset($objects);
			rmdir($dir);
		}
	}

	public function config_populate() {
		/* Populate public configuration ($config) */
		$this->config['ndphp_version']							= $this->_ndphp_version;

		$this->config['name']									= $this->_name;
		$this->config['viewhname']								= $this->_viewhname;
		$this->config['word_true']								= $this->_word_true;
		$this->config['word_false']								= $this->_word_false;

		$this->config['logging']								= $this->_logging;
		$this->config['accounting']								= $this->_accounting;

		$this->config['base_url']								= $this->_base_url;
		$this->config['temp_dir']								= $this->_temp_dir;

		$this->config['default_charset']						= $this->_default_charset;
		$this->config['default_timezone']						= $this->_default_timezone;
		$this->config['default_locale']							= $this->_default_locale;
		$this->config['default_database']						= $this->_default_database;
		$this->config['default_theme']							= $this->_default_theme;

		$this->config['project_author']							= $this->_project_author;
		$this->config['project_name']							= $this->_project_name;
		$this->config['project_tagline']						= $this->_project_tagline;
		$this->config['project_description']					= $this->_project_description;

		$this->config['string_truncate_len']					= $this->_string_truncate_len;
		$this->config['string_truncate_trail']					= $this->_string_truncate_trail;
		$this->config['string_truncate_sep']					= $this->_string_truncate_sep;

		$this->config['menu_entries_aliases']					= $this->_menu_entries_aliases;
		$this->config['menu_entries_order']						= $this->_menu_entries_order;

		$this->config['hide_fields_create']						= $this->_hide_fields_create;
		$this->config['hide_fields_edit']						= $this->_hide_fields_edit;
		$this->config['hide_fields_view']						= $this->_hide_fields_view;
		$this->config['hide_fields_remove']						= $this->_hide_fields_remove;
		$this->config['hide_fields_list']						= $this->_hide_fields_list;
		$this->config['hide_fields_result']						= $this->_hide_fields_result;
		$this->config['hide_fields_search']						= $this->_hide_fields_search;
		$this->config['hide_fields_export']						= $this->_hide_fields_export;
		$this->conifg['hide_fields_groups']						= $this->_hide_fields_groups;
		$this->config['hide_global_search_controllers']			= $this->_hide_global_search_controllers;
		$this->config['hide_groups']							= $this->_hide_groups;
		$this->config['hide_menu_entries']						= $this->_hide_menu_entries;

		$this->config['table_field_text_rich']					= $this->_table_field_text_rich;
		$this->config['table_field_order_list']					= $this->_table_field_order_list;
		$this->config['table_field_order_result']				= $this->_table_field_order_result;
		$this->config['table_field_order_list_modifier']		= $this->_table_field_order_list_modifier;
		$this->config['table_field_order_result_modifier']		= $this->_table_field_order_result_modifier;
		$this->config['table_field_aliases']					= $this->_table_field_aliases;
		$this->config['table_fk_linking']						= $this->_table_fk_linking;
		$this->config['table_pagination_rpp_list']				= $this->_table_pagination_rpp_list;
		$this->config['table_pagination_rpp_result']			= $this->_table_pagination_rpp_result;
		$this->config['table_row_filtering']					= $this->_table_row_filtering;
		$this->config['table_row_filtering_config']				= $this->_table_row_filtering_config;
		$this->config['table_type_view']						= $this->_table_type_view;
		$this->config['table_type_view_query']					= $this->_table_type_view_query;

		$this->config['rel_choice_hide_fields']					= $this->_rel_choice_hide_fields;
		$this->config['rel_choice_hide_fields_create']			= $this->_rel_choice_hide_fields_create;
		$this->config['rel_choice_hide_fields_edit']			= $this->_rel_choice_hide_fields_edit;
		$this->config['rel_choice_hide_fields_view']			= $this->_rel_choice_hide_fields_view;
		$this->config['rel_choice_hide_fields_remove']			= $this->_rel_choice_hide_fields_remove;
		$this->config['rel_choice_table_row_class']				= $this->_rel_choice_table_row_class;
		$this->config['rel_group_concat_sep']					= $this->_rel_group_concat_sep;
		$this->config['rel_table_fields_config']				= $this->_rel_table_fields_config;

		$this->config['mixed_fieldset_legend_config']			= $this->_mixed_fieldset_legend_config;
		$this->config['mixed_hide_fields_create']				= $this->_mixed_hide_fields_create;
		$this->config['mixed_hide_fields_edit']					= $this->_mixed_hide_fields_edit;
		$this->config['mixed_hide_fields_view']					= $this->_mixed_hide_fields_view;
		$this->config['mixed_hide_fields_remove']				= $this->_mixed_hide_fields_remove;
		$this->config['mixed_table_fields_config']				= $this->_mixed_table_fields_config;
		$this->config['mixed_table_add_missing']				= $this->_mixed_table_add_missing;
		$this->config['mixed_table_set_missing']				= $this->_mixed_table_set_missing;
		$this->config['mixed_table_fields_width']				= $this->_mixed_table_fields_width;
		$this->config['mixed_views_autocomplete']				= $this->_mixed_views_autocomplete;

		$this->config['csv_sep']								= $this->_csv_sep;
		$this->config['csv_delim']								= $this->_csv_delim;
		$this->config['csv_from_encoding']						= $this->_csv_from_encoding;
		$this->config['csv_to_encoding']						= $this->_csv_to_encoding;

		$this->config['view_crud_main_tab_name']				= $this->_view_crud_main_tab_name;
		$this->config['view_crud_charts_tab_name']				= $this->_view_crud_charts_tab_name;
		$this->config['view_title_sep']							= $this->_view_title_sep;
		$this->config['view_breadcrumb_sep']					= $this->_view_breadcrumb_sep;
		$this->config['view_image_file_rendering']				= $this->_view_image_file_rendering;
		$this->config['view_image_file_rendering_ext']			= $this->_view_image_file_rendering_ext;
		$this->config['view_image_file_rendering_size_list']	= $this->_view_image_file_rendering_size_list;
		$this->config['view_image_file_rendering_size_view']	= $this->_view_image_file_rendering_size_view;

		$this->config['security_perms']							= $this->_security_perms;
		$this->config['security_safe_chars']					= $this->_security_safe_chars;

		$this->config['links_quick_modal_list']					= $this->_links_quick_modal_list;
		$this->config['links_quick_modal_result']				= $this->_links_quick_modal_result;
		$this->config['links_submenu_body_create']				= $this->_links_submenu_body_create;
		$this->config['links_submenu_body_edit']				= $this->_links_submenu_body_edit;
		$this->config['links_submenu_body_remove']				= $this->_links_submenu_body_remove;
		$this->config['links_submenu_body_search']				= $this->_links_submenu_body_search;
		$this->config['links_submenu_body_view']				= $this->_links_submenu_body_view;
		$this->config['links_submenu_body_list']				= $this->_links_submenu_body_list;
		$this->config['links_submenu_body_result']				= $this->_links_submenu_body_result;
		$this->config['links_submenu_body_groups']				= $this->_links_submenu_body_groups;

		$this->config['upload_file_encryption']					= $this->_upload_file_encryption;
		$this->config['upload_file_name_filter']				= $this->_upload_file_name_filter;
		$this->config['upload_file_max_size']					= $this->_upload_file_max_size;

		$this->config['charts_enable_list']						= $this->_charts_enable_list;
		$this->config['charts_enable_result']					= $this->_charts_enable_result;
		$this->config['charts_enable_view']						= $this->_charts_enable_view;
		$this->config['charts_types']							= $this->_charts_types;
		$this->config['charts_font_family']						= $this->_charts_font_family;
		$this->config['charts_axis_font_size']					= $this->_charts_axis_font_size;
		$this->config['charts_title_font_size']					= $this->_charts_title_font_size;
		$this->config['charts_canvas_width']					= $this->_charts_canvas_width;
		$this->config['charts_canvas_height']					= $this->_charts_canvas_height;
		$this->config['charts_graph_area']						= $this->_charts_graph_area;
		$this->config['charts']									= $this->_charts;
		$this->config['charts_foreign']							= $this->_charts_foreign;

		$this->config['session_data']							= $this->_session_data;
		$this->config['json_replies']							= $this->_json_replies;

		$this->config['scheduler']								= $this->_scheduler;
	}


	/** Constructor **/

	public function __construct($session_enable = true, $json_replies = false) {
		parent::__construct();

		/* Load pre plugins */
		foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/construct_pre.php') as $plugin)
			include($plugin);

		/* Check if JSON replies should be enabled */
		if ($json_replies)
			$this->_json_replies = true;

		/* If we need to instantiate this controller to fetch controller specific configurtions, we should do so
		 * by skipping session validation.
		 *
		 * NOTE:
		 * - The core layer of ND PHP Framework, the uWeb framework, is responsible for not allowing __construct() methods
		 *   to be publicly called from HTTP requests, so it is safe to allow $session_enable to be manipulated since
		 *   it's only possible to do so under a controlled environment.
		 *
		 */
		if ($session_enable === false)
			return;

		/* Fetch all userdata to a private buffer */
		$this->_session_data = $this->session->all_userdata();

		/* Evaluate Session Status */
		if (!isset($this->_session_data['logged_in']) || $this->_session_data['logged_in'] != true) {
			/* Fetch a possible JSON request */
			$json_req = $this->request->json();
			
			/* Check if the request is of type JSON and if this is a valid API call */
			if ($this->request->is_json() && isset($json_req['_apikey']) && isset($json_req['_userid'])) {
				/* Enable JSON replies. This is the default for REST API */
				$this->_json_replies = true;

				/* Set the json data as $_POST */
				$_POST = $json_req['data'];

				/* TODO: FIXME: Pre-validate _apikey and _userid format before querying the database */

				/* TODO: FIXME: Authentication procedures should be migrated into a model and used both in the
				 * ND_Controller __construct() and Login controller authenticate()
				 */

				/* Query the database */
				$this->db->select('users.id AS user_id,users.username AS username,users.first_name AS first_name,users.email AS email,users.privenckey AS privenckey, rel_users_roles.roles_id AS roles_id,timezones.timezone AS timezone,dbms.alias AS default_database');
				$this->db->from('users');
				$this->db->join('rel_users_roles', 'rel_users_roles.users_id = users.id', 'left');
				$this->db->join('timezones', 'users.timezones_id = timezones.id', 'left');
				$this->db->join('dbms', 'users.dbms_id = dbms.id', 'left');
				$this->db->where('users.id', $json_req['_userid']);
				$this->db->where('users.apikey', $json_req['_apikey']);

				$query = $this->db->get();

				if (!$query->num_rows())
					$this->response->code('403', '{"status":false,"reason":"' . NDPHP_LANG_MOD_INVALID_CREDENTIALS . '"}', $this->_default_charset, !$this->request->is_ajax());

				/* Setup user roles */
				$user_roles = array();

				foreach ($query->result_array() as $row) {
					array_push($user_roles, $row['roles_id']);
				}
				
				/* Setup the user private encryption key */
				$privenckey = NULL;

				/* Check if user password is set... if so, we'll descrypt the private encryption key */
				if (isset($json_req['_password'])) {
					/* Decrypt the stored key with the user's plain password */
					$privenckey = $this->encrypt->decrypt($row['privenckey'], $json_req['_password'], false);

					if (strlen($privenckey) != 256)
						$this->response->code('500', '{"status":false,"reason":"' . NDPHP_LANG_MOD_INVALID_PRIV_ENC_KEY . '"}', $this->_default_charset, !$this->request->is_ajax());
				}

				/* Get user id */
				$user_id = $row['user_id'];

				$this->_session_data = array(
						'username' => $row['username'],
						'user_id' => $user_id,
						'email' => $row['email'],
						'first_name' => $row['first_name'],
						'photo' => NULL,
						'timezone' => $row['timezone'],
						'database' => $row['default_database'],
						'roles' => $user_roles,
						'privenckey' => base64_encode($privenckey),
						'logged_in' => true,
						'sessions_id' => 0,
						'_apicall' => true
				);

				/* Setup session */
				$this->session->set_userdata($this->_session_data);

				/* Update last login */
				$userdata['last_login'] = date('Y-m-d H:i:s');

				$this->db->trans_begin();

				$this->db->where('id', $user_id);
				$this->db->update('users', $userdata);

				/* Check if this session already exists on sessions table */
				$this->db->select('id,session');
				$this->db->from('sessions');
				$this->db->where('session', session_id());
				$q = $this->db->get();

				$sessions_id = NULL; /* The'id' field value on sessions table... will be populated if there are results */

				if ($q->num_rows()) {
					/* Session already exists, so we just need to update it */
					$this->db->where('session', session_id());
					$this->db->update('sessions', array(
						'ip_address' => $this->request->remote_addr(),
						'user_agent' => $this->request->header('User-Agent') ? $this->request->header('User-Agent') : 'Unspecified',
						'last_login' => date('Y-m-d H:i:s'),
						'users_id' => $user_id
					));

					/* Update $sessions_id */
					$row = $q->row_array();
					$sessions_id = $row['id'];
				} else {
					/* The session doesn't exist... Unauthorized */
					$this->response->code('403', NDPHP_LANG_MOD_ATTN_NO_SESSION_FOUND, $this->_default_charset, !$this->request->is_ajax());
				}

				/* Commit transaction if everything is fine. */
				if ($this->db->trans_status() === false) {
					$this->db->trans_rollback();
				} else {
					$this->db->trans_commit();
				}

				/* Update sessions_id key on session data. We must do this after transaction completes, otherwise
				 * we'll cause a deadlock if transactional sessions based on dbms are being used (which is the default
				 * setting in ND PHP Framework).
				 */
				$this->_session_data['sessions_id'] = $sessions_id;
				$this->session->set('sessions_id', $sessions_id);
			} else {
				/* User not logged in or session expired... we need to force re-authentication */

				/* If this is an AJAX call, redirect to /login/ ... Otherwise set the referer URL */
				if ($this->request->is_ajax()) {
					die('<meta http-equiv="refresh" content="0; url=' . base_url() . 'index.php/login"><script type="text/javascript">window.location = "' . base_url() . 'index.php/login";</script>');
				} else {
					die('<meta http-equiv="refresh" content="0; url=' . base_url() . 'index.php/login/login/' . $this->ndphp->safe_b64encode(current_url()) . '"><script type="text/javascript">window.location = "' . base_url() . 'index.php/login/login/' . $this->ndphp->safe_b64encode(current_url()) . '";</script>');
				}
			}
		}

		/* Load configuration */
		$config = $this->configuration->get();

		$this->_base_url = $config['base_url'];
		$this->_project_author = $config['author'];
		$this->_project_name = $config['project_name'];
		$this->_project_tagline = $config['tagline'];
		$this->_project_description = $config['description'];
		$this->_default_timezone = $config['timezone'];
		$this->_default_theme = $config['theme'];
		$this->_table_pagination_rpp_list = $config['page_rows'];
		$this->_table_pagination_rpp_result = $config['page_rows'];
		$this->_temp_dir = $config['temporary_directory'];

		/* Set default database */
		$this->_default_database = $this->_session_data['database'];
		$this->load->database($this->_default_database);

		/* Set default locale (in the format xx_XX.CHARSET (eg: en_US.UTF-8) */
		setlocale(LC_ALL, $this->_default_locale . '.' . $this->_default_charset);

		/* Set the default timezone */
		date_default_timezone_set($this->_default_timezone);

		/* Check if we're under maintenance mode */
		if ($config['maintenance'] && !$this->security->im_admin())
			$this->response->code('403', NDPHP_LANG_MOD_MGMT_UNDER_MAINTENANCE, $this->_default_charset, !$this->request->is_ajax());

		/* Setup security settings for this user */
		$this->_security_perms = $this->security->perm_get($this->_session_data['user_id']);

		/* Setup VIEW table type if required */
		if ($this->_table_type_view === true) {
			if (!$this->_table_type_view_query)
				$this->response->code('500', NDPHP_LANG_MOD_UNDEFINED_CTRL_VIEW_QUERY, $this->_default_charset, !$this->request->is_ajax());

			$this->db->query('CREATE OR REPLACE VIEW ' . $GLOBALS['__controller'] . ' AS ' . $this->_table_type_view_query);
		}

		/* Process charts */

		/* Load charts plugins */
		foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/charts.php') as $plugin)
			include($plugin);

		/* Charts hook */
		$this->_hook_charts();

		/* Load post plugins */
		foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/construct_post.php') as $plugin)
			include($plugin);

		/* Process scheduler entries if the scheduler is not set as external (which, in that case, will require a cron job) */
		if ($this->_scheduler['type'] != 'external')
			$this->_scheduler_process();
	}


	/** Threading / Worker handlers (Will only be used if PHP has threading enabled.) **/

	public function worker($thread) {
		/* Grant that the supplied parameter is an object. This will also filter remote calls to this method. */
		if (!is_object($thread))
			$this->response->code('500', NDPHP_LANG_MOD_INVALID_TYPE_NOT_OBJ, $this->_default_charset, !$this->request->is_ajax());

		/** BEGIN OF WORKER MAIN **/

		/** END OF WORKER MAIN **/
	}


	/** Fetchers **/

	protected function _get_tables() {
		/* If we already have a populated table list, just return it... */
		if (count($this->_cache_tables))
			return $this->_cache_tables; /* All good */

		/* Fetch the tables from the database */
		$query = $this->db->query("SHOW TABLES");

		/* If we're unable to retrieve the database tables, we can't proceed */
		if (!$query)
			$this->response->code('500', NDPHP_LANG_MOD_UNABLE_FETCH_CRIT_DATA_DBMS, $this->_default_charset, !$this->request->is_ajax());

		/* Populate the tables list */
		foreach ($query->result_array() as $field => $value) {
			foreach ($value as $header => $table) {
				array_push($this->_cache_tables, $table);
			}
		}

		/* All good */
		return $this->_cache_tables;
	}

	protected function _get_table_desc($table = NULL) {
		/* If no table was specified, used this controller table */
		if ($table === NULL)
			$table = $this->_name;

		/* If we already have data cached for the $table, just return it... */
		if (isset($this->_cache_table_desc[$table]))
			return $this->_cache_table_desc[$table];

		/* Otherwise, fetch it... */
		$this->_cache_table_desc[$table] = $this->db->describe_table($table);

		/* All good */
		return $this->_cache_table_desc[$table];
	}

	protected function _get_table_fields($table = NULL) {
		/* If no table was specified, used this controller table */
		if ($table === NULL)
			$table = $this->_name;

		/* If we already have data cached for the $table, just return it... */
		if (isset($this->_cache_table_fields[$table]))
			return $this->_cache_table_fields[$table];

		/* Otherwise, fetch it... */
		$this->_cache_table_fields[$table] = $this->db->list_fields($table);

		/* All good */
		return $this->_cache_table_fields[$table];
	}

	protected function _get_help($table = NULL) {
		/* If no table was specified, used this controller table */
		if ($table === NULL)
			$table = $this->_name;

		/* If we already have a populated the help data cache, just return it... */
		if (isset($this->_cache_help[$table]))
			return $this->_cache_help[$table];

		/* Fetch help data from the database */
		$this->db->select('field_name, placeholder,field_units,units_on_left,input_pattern,help_description,help_url');
		$this->db->from('_help_tfhd');
		$this->db->where('table_name', $table);

		$query = $this->db->get();

		/* If there's no help data, nullify the entry and return it */
		if (!$query->num_rows()) {
			$this->_cache_help[$table] = NULL;
			return NULL;
		}

		/* Initialize help data entry for $table */
		$this->_cache_help[$table] = array();

		/* Populate help data */
		foreach ($query->result_array() as $row) {
			/* If there is no field assigned to this row, this help entry is related to the table, not the field... */
			if (!$row['field_name']) {
				/* ... So we use a special entry _self to store it */
				$this->_cache_help[$table]['_self'] = $row;
				continue;
			}

			$this->_cache_help[$table][$row['field_name']] = $row;
		}

		/* All good */
		return $this->_cache_help[$table];
	}

	protected function _get_build() {
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

		return $build;
	}

	protected function _get_theme() {
		$this->db->select(
			'themes.theme AS name,'.
			'themes.animation_default_delay AS animation_default_delay,themes.animation_ordering_delay AS animation_ordering_delay,'.
			'themes_animations_default.animation AS animation_default_type,themes_animations_ordering.animation AS animation_ordering_type'
		);
		$this->db->from('themes');
		$this->db->join('themes_animations_default', 'themes_animations_default.id = themes.themes_animations_default_id', 'left');
		$this->db->join('themes_animations_ordering', 'themes_animations_ordering.id = themes.themes_animations_ordering_id', 'left');
		$this->db->where('theme', $this->_default_theme);
		$q = $this->db->get();

		return $q->row_array();
	}

	protected function _get_features() {
		return $this->features->get_features();
	}

	protected function _get_breadcrumb($method, $second_level = NULL, $id = NULL, $third_level = NULL) {
		/* NOTE: Currently, breadcrumbs won't contain more than 3 levels */

		/* Re-initialize breadcrumb */
		$this->breadcrumb->set('levels', array());
		$this->breadcrumb->set('charset', $this->_default_charset);
		$this->breadcrumb->set('separator', $this->_view_breadcrumb_sep);

		/* Add first level */
		$this->breadcrumb->add(
			isset($this->_menu_entries_aliases[$this->_name]) ? $this->_menu_entries_aliases[$this->_name] :  $this->_viewhname,
			isset($this->_menu_entries_aliases[$this->_name]) ? $this->_menu_entries_aliases[$this->_name] :  $this->_viewhname,
			base_url() . 'index.php/' . $this->_name,
			'ndphp.ajax.load_body_menu(event, \'' . $this->_name . '\', \'' . (isset($this->_menu_entries_aliases[$this->_name]) ? $this->_menu_entries_aliases[$this->_name] :  $this->_viewhname) . '\');'
		);

		/* Add second level, if exists */
		if ($second_level !== NULL) {
			$this->breadcrumb->add(
				$second_level,
				$second_level,
				base_url() . 'index.php/' . $this->_name . '/' . $method,
				'ndphp.ajax.load_body_op(event, \'' . filter_html_js_str($this->_name, $this->_default_charset) . '\', \'' . filter_html_js_str($method, $this->_default_charset) . '\');'
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
				$url = base_url() . 'index.php/' . $this->_name . '/' . $method . '/' . $params;
				$onclick = 'ndphp.ajax.load_body_url(event, \'' . base_url() . 'index.php/' . $this->_name . '/' . $method . '_body_ajax/' . $params . '\');';
				$id = $method; /* Will be used as name and title value if $third_level is NULL */
			} else {
				/* $id is a single identifier for the second level method */
				$params = $id;
				$url = base_url() . 'index.php/' . $this->_name . '/' . $method . '/' . $params;
				$onclick = 'ndphp.ajax.load_body_op_id(event, \'' . filter_html_js_str($this->_name, $this->_default_charset) . '\', \'' . filter_html_js_str($method, $this->_default_charset) . '\', \'' . filter_html_js_str($id, $this->_default_charset) . '\');';
			}

			$this->breadcrumb->add(
				($third_level !== NULL) ? $third_level : $id,
				($third_level !== NULL) ? $third_level : $id,
				$url,
				$onclick
			);
		}

		/* Create breadcrumb HTML and return the result */
		return $this->breadcrumb->create();
	}

	private function _get_rel_table_names($rel, $target = NULL, $mixed = false) {
		if ($target === NULL)
			$target = $this->_name;

		if (!strpos($rel, $target))
			return array();

		$foreign_table_raw = str_replace($target, '', substr($rel, $mixed ? 6 : 4));

		/* After removing the $target table name from the string, the remaining starts with '_', then
		 * that foreign table was positioned at the end of the relationship table name.
		 */
		return ($foreign_table_raw[0] == '_') ? array($target, trim($foreign_table_raw, '_')) : array(trim($foreign_table_raw, '_'), $target);
	}

	private function _get_multiple_rel_table_names($rel, $target = NULL) {
		return $this->_get_rel_table_names($rel, $target, false);
	}

	private function _get_mixed_rel_table_names($rel, $target = NULL) {
		return $this->_get_rel_table_names($rel, $target, true);
	}

	protected function _get_relative_tables($target = NULL, $type = 'multiple') {
		/* If no target was set, assume this controller table as default */
		if (!$target)
			$target = $this->_name;

		/* Setup prefix */
		if ($type == 'multiple') {
			$prefix = 'rel_';
		} else if ($type == 'mixed') {
			$prefix = 'mixed_';
		}

		$relative = array();

		/* Build a list of tables that are related to $target, based on $type prefix */
		foreach ($this->_get_tables() as $table) {
			if (substr($table, 0, strlen($prefix)) == $prefix) {
				/* If $type is multiple, any of the slices that match $target is a relationship */
				if ($type == 'multiple') {
					$slices = $this->_get_multiple_rel_table_names($table, $target);

					if ($slices[0] == $target || $slices[1] == $target)
						array_push($relative, $table);
				} else if ($type == 'mixed') {
					$slices = $this->_get_mixed_rel_table_names($table, $target);

					/* If the $type is mixed, only $slice[1] matches is considered a relationship */
					if ($slices[0] == $target)
						array_push($relative, $table);
				}
			}
		}

		return $relative;
	}

	protected function _get_controller_list() {
		$controllers = array();

		foreach ($this->_get_tables() as $table) {
			/* Validate table names */
			if (!$this->security->safe_names($table, $this->_security_safe_chars)) {
				error_log($this->_name . '::_get_controller_list(): Table `' . $table . '` contains unsafe characters on its name. Skipping...');
				continue;
			}

			/* Security check */
			if (!$this->security->perm_check($this->_security_perms, $this->security->perm_read, $table))
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

	protected function _get_menu_entries() {
		$entries = array();

		foreach ($this->_get_tables() as $table) {
			/* Validate table names */
			if (!$this->security->safe_names($table, $this->_security_safe_chars)) {
				error_log($this->_name . '::_get_menu_entries(): Table `' . $table . '` contains unsafe characters on its name. Skipping...');
				continue;
			}

			/* Security check */
			if (!$this->security->perm_check($this->_security_perms, $this->security->perm_read, $table))
				continue;

			/* Ignore hidden menu entries */
			if (in_array($table, $this->_hide_menu_entries))
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
			$help_data = $this->_get_help($table);
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
			array_push($entries, array($table, isset($this->_menu_entries_aliases[$table]) ? $this->_menu_entries_aliases[$table] : $table, $help_description));
		}


		/* Re-order $entries based on $this->_menu_entries_order */
		if (count($this->_menu_entries_order)) {
			$entries_ordered = array();

			foreach ($this->_menu_entries_order as $entry_name) {
				/* Ignore hidden menu entries (this is not really required, but *may* speed up a little) */
				if (in_array($entry_name, $this->_hide_menu_entries))
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

	protected function _get_field_help_desc($table, $field) {
		$help_data = $this->_get_help($table);

		if ($help_data === NULL || !isset($help_data[$field]))
			return NULL;

		return $help_data[$field];
	}

	protected function _get_fields_basic_types($target = NULL, $hide_filter = array()) {
		$fields = NULL;

		$fields_raw = $this->_get_table_desc($target != NULL ? $target : $this->_name);

		if (!$fields_raw)
			return NULL;

		foreach ($fields_raw as $field) {
			/* Filter hidden fields */
			if (in_array($field['name'], $hide_filter))
				continue;

			/* NOTE: Security check: If we cannot read the field, then it won't be shown in any request regardless of its nature */
			if (!$this->security->perm_check($this->_security_perms, $this->security->perm_read, $target !== NULL ? $target : $this->_name, $field['name']))
				continue;

			$fields[$field['name']]['type'] = $field['type'];
			$fields[$field['name']]['max_length'] = $field['max_length'];
			$fields[$field['name']]['primary_key'] = $field['primary_key'];
			$fields[$field['name']]['table'] = $this->_name;
			$fields[$field['name']]['altname'] = $field['name'];
		}

		return $fields;
	}

	protected function _get_fields($target = NULL, $hide_filter = array(), $skip_perm_check = false) {
		$fields = NULL;

		if ($target === NULL)
			$target = $this->_name;

		$fields_raw = $this->_get_table_desc($target);

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
			if (!$skip_perm_check && !$this->security->perm_check($this->_security_perms, $this->security->perm_read, $target, $field['name']))
				continue;

			/* Populate field properties */
			$fields[$field['name']]['type'] = $field['type'];
			$fields[$field['name']]['max_length'] = $field['max_length'];
			$fields[$field['name']]['primary_key'] = $field['primary_key'];
			$fields[$field['name']]['table'] = $target; 
			$fields[$field['name']]['rel_table'] = NULL;
			$fields[$field['name']]['options'] = array();
			$fields[$field['name']]['altname'] = $field['name'];
			$fields[$field['name']]['viewname'] = isset($this->_table_field_aliases[$field['name']]) ? $this->_table_field_aliases[$field['name']] : $field['name'];
			$fields[$field['name']]['input_name'] = NULL; 

			/* Get field help, if exists */
			$help_data = $this->_get_field_help_desc($target, $field['name']);
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
				$table_fields = $this->_get_table_fields($table);

				/* Check how many fields are required to be concatenated to craft the options
				 * values.
				 */
				if (isset($this->_rel_table_fields_config[$table])) {
					/* Setup the amount of concatenated fields required for the options */
					$rel_fields = '';
					foreach ($this->_rel_table_fields_config[$table][2] as $rel_field)
						$rel_fields .= $table_fields[$rel_field] . ',';
					$rel_fields = rtrim($rel_fields, ',');
				} else {
					/* If no concatenated fields were configured, use the default value.
					 * (The default value is always the second field from the rel table)
					 */
					$rel_fields = $table_fields[1];
				}

				//$this->db->select('id,' . $table_fields[1]);
				$this->db->select('id,' . $rel_fields);
				$this->db->from($table);

				if (isset($this->_rel_table_fields_config[$table]) && ($this->_rel_table_fields_config[$table][3] != NULL)) {
					$this->db->order_by($this->_rel_table_fields_config[$table][3][0], $this->_rel_table_fields_config[$table][3][1]);
				}

				$this->_table_row_filter_apply($table);

				/* We need to use _field_value_mangle() here to grant that options values are mangled
				 *
				 * We also use the _get_fields_basic_types() to reduce the overhead that would be caused if
				 * we used recursion here with _get_fields()
				 */
				$result_array = $this->_field_value_mangle($this->_get_fields_basic_types($table), $this->db->get());

				/* Set the altname and viewname */
				if (isset($this->_rel_table_fields_config[$table]) && ($this->_rel_table_fields_config[$table][2] != NULL)) {
					$fields[$field['name']]['altname'] = $table_fields[$this->_rel_table_fields_config[$table][2][0]];
					$fields[$field['name']]['viewname'] = $this->_rel_table_fields_config[$table][0];
				} else {
					$fields[$field['name']]['altname'] = $table_fields[1];
					$fields[$field['name']]['viewname'] = $table_fields[1];
				}

				/* Get field help, if exists */
				$help_data = $this->_get_field_help_desc($target, $field['name']);
				$fields[$field['name']]['units'] = $help_data['field_units'];
				$fields[$field['name']]['help_desc'] = $help_data['help_description'];
				$fields[$field['name']]['help_url'] = $help_data['help_url'];

				/* Craft options values based on concatenations' configuration (if any).
				 * If no configuration is set for this particular relationship, the default
				 * option value is used (this is, the values of the second field of the
				 * relationship table).
				 */
				foreach ($result_array as $row) {
					if (isset($this->_rel_table_fields_config[$table]) && ($this->_rel_table_fields_config[$table][2] != NULL)) {
						/* Setup the amount of concatenated fields required for the options */
						$fields[$field['name']]['options'][$row['id']] = '';
						foreach ($this->_rel_table_fields_config[$table][2] as $rel_field)
							$fields[$field['name']]['options'][$row['id']] .= $row[$table_fields[$rel_field]] . (($this->_rel_table_fields_config[$table][1] != NULL) ? $this->_rel_table_fields_config[$table][1] : ' '); 
                            /* ^^^ -> Field Options Array                     ^^^ -> Resolved Option Field      ^^^ -> Separator   */

						/* Remove trailing separator */
						$fields[$field['name']]['options'][$row['id']] = trim($fields[$field['name']]['options'][$row['id']], (($this->_rel_table_fields_config[$table][1] != NULL) ? $this->_rel_table_fields_config[$table][1] : ' '));
					} else {
						/* If no concatenated fields were configured, use the default value.
					 	 * (The default value is always the second field from the rel table)
					 	 */
						$fields[$field['name']]['options'][$row['id']] = $row[$table_fields[1]];
					}
				}
				
				$fields[$field['name']]['input_type'] = 'select';
			} else if ($field['type'] == 'varchar') {
				$fields[$field['name']]['input_type'] = 'text';
			} else if ($field['type'] == 'text') {
				$fields[$field['name']]['input_type'] = 'textarea';
			} else if (($field['type'] == 'int') || ($field['type'] == 'bigint') || ($field['type'] == 'timestamp')) {
				$fields[$field['name']]['input_type'] = 'number';
			} else if (($field['type'] == 'tinyint') || ($field['type'] == 'bool')) {
				$fields[$field['name']]['input_type'] = 'checkbox';
			} else {
				/* By default, we assume all unknown types as 'text' */
				$fields[$field['name']]['input_type'] = 'text';
			}
		}
		
		/* Check for multiple relationships */
		foreach ($this->_get_tables() as $table) {
			/* Ignore all non-relationship tables */
			if ((substr($table, 0, 4) != 'rel_'))
				continue;

			$rel_tables = array_diff($this->_get_multiple_rel_table_names($table, $target), array($target));

			foreach ($rel_tables as $rel) {
				/* Security check */
				if (!$skip_perm_check && !$this->security->perm_check($this->_security_perms, $this->security->perm_read, $rel))
					continue;

				if (!$skip_perm_check && !$this->security->perm_check($this->_security_perms, $this->security->perm_read, $target, $table /* NOTE: $table is the field name: rel_<t1>_<ft2> */))
					continue;

				$table_fields = $this->_get_table_fields($rel);
				$rel_field = isset($this->_rel_table_fields_config[$rel][2][0]) ? $this->_rel_table_fields_config[$rel][2][0] : 1;

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
				if (isset($this->_rel_table_fields_config[$rel]) && ($this->_rel_table_fields_config[$rel][2] != NULL)) {
					/* Setup the amount of concatenated fields required for the options */
					$rel_fields = '';
					foreach ($this->_rel_table_fields_config[$rel][2] as $rel_field)
						$rel_fields .= $table_fields[$rel_field] . ',';
					$rel_fields = rtrim($rel_fields, ',');
				} else {
					/* If no concatenated fields were configured, use the default value.
					 * (The default value is always the second field from the rel table)
					 */
					$rel_fields = $table_fields[1];
				}

				/* Get foreign table contents */
				$this->db->select('id,' . $rel_fields);
				$this->db->from($rel);

				if (isset($this->_rel_table_fields_config[$rel]) && ($this->_rel_table_fields_config[$rel][3] != NULL)) {
					$this->db->order_by($this->_rel_table_fields_config[$rel][3][0], $this->_rel_table_fields_config[$rel][3][1]);
				}

				$this->_table_row_filter_apply($rel);


				/* We need to use _field_value_mangle() here to grant that relationship values are mangled
				 *
				 * We also use the _get_fields_basic_types() to reduce the overhead that would be caused if
				 * we used recursion here with _get_fields()
				 */
				$result_array = $this->_field_value_mangle($this->_get_fields_basic_types($rel), $this->db->get());

				/* Set the altname */
				if (isset($this->_rel_table_fields_config[$rel])) {
					$fields[$table]['altname'] = $table_fields[$this->_rel_table_fields_config[$rel][2][0]];
					$fields[$table]['viewname'] = $this->_rel_table_fields_config[$rel][0];
				} else {
					$fields[$table]['altname'] = $table_fields[1];
					$fields[$table]['viewname'] = $table_fields[1];
				}

				/* Get field help, if exists */
				$help_data = $this->_get_field_help_desc($target, $table);
				$fields[$table]['units'] = $help_data['field_units'];
				$fields[$table]['help_desc'] = $help_data['help_description'];
				$fields[$table]['help_url'] = $help_data['help_url'];

				/* Craft options values based on concatenations' configuration (if any).
				 * If no configuration is set for this particular relationship, the default
				 * option value is used (this is, the values of the second field of the
				 * relationship table).
				 */
				foreach ($result_array as $row) {
					if (isset($this->_rel_table_fields_config[$rel]) && ($this->_rel_table_fields_config[$rel][2] != NULL)) {
						/* Setup the amount of concatenated fields required for the options */
						$fields[$table]['options'][$row['id']] = '';
						foreach ($this->_rel_table_fields_config[$rel][2] as $rel_field)
							$fields[$table]['options'][$row['id']] .= $row[$table_fields[$rel_field]] . (($this->_rel_table_fields_config[$rel][1] != NULL) ? $this->_rel_table_fields_config[$rel][1] : ' ');
	                        /* ^^^ -> Field Options Array                                                                   ^^^ -> Resolved Option Field      ^^^ -> Separator   */

						/* Remove trailing separator */
						$fields[$table]['options'][$row['id']] = trim($fields[$table]['options'][$row['id']], (($this->_rel_table_fields_config[$rel][1] != NULL) ? $this->_rel_table_fields_config[$rel][1] : ' '));
					} else {
						/* If no concatenated fields were configured, use the default value.
					 	 * (The default value is always the second field from the rel table)
					 	 */
						$fields[$table]['options'][$row['id']] = $row[$table_fields[1]];
					}
				}
			}
		}

		/* Check for mixed relationships (table prefix mixed_*) */
		foreach ($this->_get_tables() as $table) {
			/* Ignore all non-relationship tables */
			if (substr($table, 0, 6) != 'mixed_')
				continue;

			$rel_tables = $this->_get_mixed_rel_table_names($table, $target);
			
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
			if (!$skip_perm_check && !$this->security->perm_check($this->_security_perms, $this->security->perm_read, $rel))
				continue;

			if (!$skip_perm_check && !$this->security->perm_check($this->_security_perms, $this->security->perm_read, $target, $table /* NOTE: $table is the field name: mixed_<t1>_<ft2> */))
				continue;

			/* Filter hidden fields */
			if (in_array($table, $hide_filter))
				continue;

			$table_fields_all = $this->_get_table_fields($table);
			$table_fields = array_merge(array('id'), $this->_get_mixed_table_fields($rel, $target));
			$rel_field = isset($this->_rel_table_fields_config[$rel][2][0]) ? $this->_rel_table_fields_config[$rel][2][0] : 1;

			/* Check if this is a single mixed relationship */
			$schema = $this->load->database($this->_default_database . '_schema', true);
			$schema->select('column_key')->from('columns')->where('table_schema', $this->db->database)->where('table_name', $table)->where('column_name', $table_fields_all[2]);
			$query = $schema->get();
			$query_row = $query->row_array();

			if ($query_row['column_key'] == 'UNI') {
				$fields[$table]['mixed_type'] = 'single';
			} else {
				$fields[$table]['mixed_type'] = 'multi';
			}
			$this->load->database($this->_default_database);

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
			$mixed_hide_fields = $this->access->controller($rel)['mixed_hide_fields_view'];

			/* Resolve foreign table field names aliases */
			$table_fields_aliases = array();
			
			foreach ($table_fields as $tfid => $tfname) {
				$tfname = $table_fields[$tfid];

				/* Ignore hidden fields */
				if (in_array($tfname, $mixed_hide_fields))
					continue;

				$help_data = $this->_get_field_help_desc($table, $tfname);
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

				foreach ($this->_mixed_table_fields_config as $rtname => $rtvalue) {
					if ($rtname != $rel)
						continue;

					/* Set mixed field aliases */
					if (isset($this->_mixed_table_fields_config[$rtname][$tfid])) {
						$tfdata['alias'] = $this->_mixed_table_fields_config[$rtname][$tfid];
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

			$this->db->select('id,' . $rel_fields);
			$this->db->from($rel);

			/* Filter the rows based on access configuration parameters */
			$this->_table_row_filter_apply($rel);

			/* We need to use _field_value_mangle() here to grant that relationship values are mangled
			 *
			 * We also use the _get_fields_basic_types() to avoid the overhead that would be caused if
			 * recursion of _get_fields() was used here.
			 */
			$result_array = $this->_field_value_mangle($this->_get_fields_basic_types($rel), $this->db->get());

			/* Set the altname and viewname */
			$fields[$table]['altname'] = $rel;
			$fields[$table]['viewname'] = isset($this->_mixed_fieldset_legend_config[$rel]) ? $this->_mixed_fieldset_legend_config[$rel] : $rel;
			
			/* Craft options values */
			foreach ($result_array as $row) {
				$fields[$table]['options'][$row['id']] = $row[$table_fields[1]];
			}
		}
		
		return $fields;
	}

	protected function _get_mixed_table_fields($mixed_table, $origin) {
		$result_mixed_fields = array();

		foreach ($this->_get_tables() as $table) {
			if (substr($table, 0, 6) != 'mixed_')
				continue;

			$slices = $this->_get_mixed_rel_table_names($table, $origin);

			if (($slices[0] != $origin) || ($slices[1] != $mixed_table))
				continue;

			$mixed_table_fields_raw = $this->_get_table_fields($table);
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

	protected function _get_saved_searches() {
		/* Get only the searches matching the current user_id */
		$this->db->select('id,search_name,description,result_query');
		$this->db->from('_saved_searches');
		$this->db->where('controller', $this->_name);
		$this->db->where('users_id', $this->_session_data['user_id']);
		$q = $this->db->get();

		$saved_searches = array();

		if (!$q->num_rows())
			return $saved_searches;
		
		foreach ($q->result_array() as $row)
			array_push($saved_searches, $row);

		return $saved_searches;
	}


	/** Custom loaders **/
	protected function _load_view($view_name, $data = NULL, $customizable = false, $return_data = false, $ctrl_override = NULL) {
		if ($customizable) {
			if (file_exists('application/views/themes/' . $this->_default_theme . '/' . ($ctrl_override ? $ctrl_override : $this->_name) . '/' . $view_name . '.php')) {
				return $this->load->view('themes/' . $this->_default_theme . '/' . ($ctrl_override ? $ctrl_override : $this->_name) . '/' . $view_name, $data, $return_data);
			} else {
				return $this->load->view('themes/' . $this->_default_theme . '/' . '_default/' . $view_name, $data, $return_data);
			}
		} else {
			return $this->load->view('themes/' . $this->_default_theme . '/' . $view_name, $data, $return_data);
		}
	}

	protected function _load_method_views($method, $data = NULL, $body_only = false, $body_header = true, $body_footer = true, $ctrl_override = NULL) {
		if (!$body_only)
			$this->_load_view('header', $data, false, false, $ctrl_override);

		if ($body_header)
			$this->_load_view($method . '_header', $data, true, false, $ctrl_override);

		$this->_load_view($method . '_data', $data, true, false, $ctrl_override);

		if ($body_footer)
			$this->_load_view($method . '_footer', $data, true, false, $ctrl_override);

		if (!$body_only)
			$this->_load_view('footer', $data, false, false, $ctrl_override);
	}


	/** Field analysis and mangling **/
	protected function _field_value_mangle($fields, $query) {
		$result_mangled = array();

		foreach ($query->result_array() as $data) {
			/* Reset row data */
			$row = array();

			foreach ($data as $field => $value) {
				if ($fields[$field]['type'] == 'datetime' && $value) {
					/* NOTE: Currently, we only need to mangle datetime fields (to support user timezone) */
					/* Convert data from database default timezone to user timezone */
					$row[$field] = $this->timezone->convert($value, $this->_default_timezone, $this->_session_data['timezone']);
				} else {
					/* Just push the value without modification */
					$row[$field] = $value;
				}
			}

			/* Push row into result */
			array_push($result_mangled, $row);
		}

		return $result_mangled;
	}

	protected function _field_resolve($fields, $selected = NULL) {
		$select = array();

		/* Check for single relationships */
		foreach ($fields as $field => $meta) {
			if ($meta['type'] == 'mixed')
				continue;

			/* Check if we already have a set of previously selected fields */
			if ($selected) {
				/* If we already have a set of selected fields, do not resolve fields that
				 * do not belong to this set.
				 */
				if (!in_array($field, $selected))
					continue;
			}

			/* If field suffix is '_id', join to the related table (prefix name) and
			 * select its second field by default, unless a rel_table_fields_config entry
			 * is set for this particular table. In this last case, we need to determine
			 * which field(s) is(are) to be selected.
			 * 
			 * If the field is of type 'rel', then process it as a multiple relationship.
			 * 
			 * If none of the above, process it as a normal field.
			 * 
			 */
			if (substr($field, -3, 3) == '_id') {
				$table = substr($field, 0, -3);
				$table_fields = $this->_get_table_fields($table);
				$this->db->join($table, $table . '.id = ' . $this->_name . '.' . $field, 'left');

				/* Concatenate configured fields on _rel_table_fields_config in the results
				 * for this table, if more than one field was configured.
				 */
				$cc_fields = '';
				if (isset($this->_rel_table_fields_config[$table]) &&
						($this->_rel_table_fields_config[$table][2] != NULL) &&
						(count($this->_rel_table_fields_config[$table][2]) > 1)) {
					/* Initialize concat field separator */
					$cc_fields = 'CONCAT_WS(\'' . $this->_rel_table_fields_config[$table][1] . '\',';
					foreach ($this->_rel_table_fields_config[$table][2] as $cc_field) {
						$cc_fields .= '`' . $table . '`.' . '`' . $table_fields[$cc_field] . '`,';
					}

					$cc_fields = trim($cc_fields, ',') . ')';
				} else {
					/* No specific configuration was set for this table through
					 * _rel_table_fields_config, or only one field was registered in the
					 * fields array.
					 */
					$rel_field = isset($this->_rel_table_fields_config[$table][2][0]) ? $this->_rel_table_fields_config[$table][2][0] : 1;
					$cc_fields = '`' . $table . '`.`' . $table_fields[$rel_field] . '`'; 
				}

				array_push($select, $cc_fields . ' AS `' . $field . '`');
			} else if ($meta['type'] == 'rel') {
				/* If this is a multiple relationship field */
				$table_fields = $this->_get_table_fields($meta['table']);

				$this->db->join($meta['rel_table'], $this->_name . '.id = ' . $meta['rel_table'] . '.' . $this->_name . '_id', 'left');
				$this->db->join($meta['table'], $meta['table'] . '.id = ' . $meta['rel_table'] . '.' . $meta['table'] . '_id', 'left');

				/* Concatenate configured fields on _rel_table_fields_config in the results
				 * for this table, if more than one field was configured.
				 */
				$cc_fields = '';
				if (isset($this->_rel_table_fields_config[$meta['table']]) &&
						($this->_rel_table_fields_config[$meta['table']][2] != NULL) &&
						(count($this->_rel_table_fields_config[$meta['table']][2]) > 1)) {
					/* Initialize concat field separator */
					$cc_fields = 'CONCAT_WS(\'' . $this->_rel_table_fields_config[$meta['table']][1] . '\',';
					foreach ($this->_rel_table_fields_config[$meta['table']][2] as $cc_field) {
						$cc_fields .= '`' . $meta['table'] . '`.' . '`' . $table_fields[$cc_field] . '`,';
					}
					
					$cc_fields = trim($cc_fields, ',') . ')';
				} else {
					/* No specific configuration was set for this table through
					 * _rel_table_fields_config, or only one field was registered in the
					 * fields array.
					 */
					$cc_fields = '`' . $meta['table'] . '`.`' . $meta['rel_field'] . '`';
				}

				array_push($select, 'GROUP_CONCAT(DISTINCT ' . $cc_fields . ' SEPARATOR \'' . $this->_rel_group_concat_sep . '\') AS `' . $field . '`');
			} else {
				/* Otherwise, just select the current table field */
				array_push($select, '`' . $this->_name . '`.`' . $field . '`');
			}
		}
		
		/* Build select statement */
		$select_str = '';
		foreach ($select as $field)
			$select_str = $select_str . ',' . $field;

		$select_str = ltrim($select_str, ',');
		
		$this->db->group_by($this->_name . '.' . 'id');
		
		/* NOTE: We cannot use enforce here due to SELECT functions used (GROUP_CONCAT() and CONCAT_WS()).
		 * We need to grant that everything is checked before passing it to select() to avoid SQLi
		 */
		$this->db->select($select_str, false);
	}

	protected function _field_unambig($field, $types) {
		if (isset($types[$field]['table'])) {
			return '`' . $types[$field]['table'] . '`.`' . $field . '`';
		} else {
			return '`' . $this->_name . '`.`' . $field . '`';
		}
	}


	/** The main and public stuff **/

	public function index_ajax() {
		$this->list_body_ajax();
	}

	public function index() {
		redirect($this->_name . '/list_default');
	}

	public function rel_get_options($field, $relationship, $selected_id) {
		$fields = $this->_get_fields();

		$data = array();

		$data['config'] = array();
		$data['config']['charset'] = $this->_default_charset;

		$data['view'] = array();
		$data['view']['field'] = $fields[$field];
		$data['view']['selected_id'] = $selected_id;
		$data['view']['relationship'] = $relationship;

		$this->_load_view('options', $data, true);
	}
	
	protected function groups_generic() {
		/* Security Permissions Check */
		if (!$this->security->perm_check($this->_security_perms, $this->security->perm_read, $this->_name))
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->_default_charset, !$this->request->is_ajax());

		/* Initialize $data variable */
		$data = array();

		/* Load enter plugins */
		foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/groups_generic_enter.php') as $plugin)
			include($plugin);

		/* Groups hook enter */
		$hook_enter_return = $this->_hook_groups_generic_enter($data);

		/* If logging is enabled, log this listing request */
		if ($this->_logging === true) {
			$log_transaction_id = openssl_digest('VIEW' . $this->_name . $this->_session_data['sessions_id'] . date('Y-m-d H:i:s') . mt_rand(1000000, 9999999), 'sha1');

			$this->db->insert('logging', array(
				'operation' => 'VIEW',
				'_table' => $this->_name,
				'_field' => 'GROUPS',
				'transaction' => $log_transaction_id,
				'registered' => date('Y-m-d H:i:s'),
				'sessions_id' => $this->_session_data['sessions_id'],
				'users_id' => $this->_session_data['user_id']
			));
		}

		/* Get view title value */
		$title = NULL;

		if (isset($this->_menu_entries_aliases[$this->_name])) {
			$title = $this->_menu_entries_aliases[$this->_name] . $this->_view_title_sep . NDPHP_LANG_MOD_OP_GROUPS;
		} else {
			$title = $this->_viewhname . $this->_view_title_sep . NDPHP_LANG_MOD_OP_GROUPS;
		}

		/* Get view description value */
		$description = NDPHP_LANG_MOD_OP_GROUPS . " " . $this->_viewhname;

		/* Setup basic view data */
		$data = array_merge($data, $this->_get_view_data_generic($title, $description));

		/* Setup specific view data */
		$data['view']['fields'] = $this->_get_fields(NULL, $this->_hide_fields_groups); /* _get_fields() uses a perm_read filter by default */
		$data['view']['links']['quick'] = $this->_links_quick_modal_list;
		$data['view']['links']['submenu'] = $this->_links_submenu_body_groups;
		$data['view']['links']['breadcrumb'] = $this->_get_breadcrumb('groups', NDPHP_LANG_MOD_OP_GROUPS);

		/* Create groups list */
		$groups = array();

		/* Fetch table fields to look for single relationships */
		$table_fields = $this->_get_table_fields($this->_name);

		foreach ($table_fields as $field) {
			if (in_array($field, $this->_hide_groups))
				continue;

			$group = array();

			if (substr($field, -3) == '_id') {
				/* This is a single relationship group */

				/* Get foreign table name */
				$group['table_name'] = substr($field, 0, -3);

				/* Ignore self-relationships */
				if ($group['table_name'] == $this->_name)
					continue;

				/* Check if we've perms to read the field */
				if (!$this->security->perm_check($this->_security_perms, $this->security->perm_read, $this->_name, $field))
					continue;

				/* Check if we've perms to read the foreign table */
				if (!$this->security->perm_check($this->_security_perms, $this->security->perm_read, $group['table_name']))
					continue;

				/* Check if there's a viewname alias set */
				if (isset($this->_menu_entries_aliases[$group['table_name']])) {
					$group['name'] = $this->_menu_entries_aliases[$group['table_name']];
				} else {
					$group['name'] = ucfirst($group['table_name']);
				}

				/* Set table field name to be used as grouping field identifier */
				$group['table_field'] = $field;

				/* Add group to the groups array */
				array_push($groups, $group);
			}
		}

		/* Fetch tables to look for multiple relationships */
		foreach ($this->_get_tables() as $table) {
			if (substr($table, 0, 4) != 'rel_')
				continue;

			$rel_tables = $this->_get_multiple_rel_table_names($table, $this->_name);

			$group = array();

			if (in_array($this->_name, $rel_tables)) {
				/* This is a multiple relationship */

				/* Get foreign table name */
				$group['table_name'] = array_pop(array_diff($rel_tables, array($this->_name)));

				/* Check if we've perms to read the foreign table */
				if (!$this->security->perm_check($this->_security_perms, $this->security->perm_read, $group['table_name']))
					continue;

				/* Check if there's a viewname alias set */
				if (isset($this->_menu_entries_aliases[$group['table_name']])) {
					$group['name'] = $this->_menu_entries_aliases[$group['table_name']];
				} else {
					$group['name'] = ucfirst($group['table_name']);
				}

				/* Set table field name to be used as grouping field identifier */
				$group['table_field'] = $table;

				/* Add group to the groups array */
				array_push($groups, $group);
			}
		}

		/* Assign groups array to view data */
		$data['view']['groups'] = $groups;

		/* Groups hook leave */
		$this->_hook_groups_generic_leave($data, $hook_enter_return);

		/* Load leave plugins */
		foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/groups_generic_leave.php') as $plugin)
			include($plugin);

		/* All good */
		return $data;
	}

	public function groups($body_only = false, $body_header = true, $body_footer = true, $modalbox = false) {
		$data = $this->groups_generic();

		if ($modalbox)
			$data['config']['modalbox'] = true;

		/* If this is an AJAX call, then we should only render the page body */
		if ($this->request->is_ajax())
			$body_only = true;

		/* If this is an JSON API request, just reply the data (do not load the views) */
		if ($this->_json_replies === true) {
			echo($this->json_list($data));
			return;
		}

		/* Load Views */
		$this->_load_method_views(__FUNCTION__, $data, $body_only, $body_header, $body_footer);
	}

	public function groups_body_ajax() {
		$this->groups(true);
	}

	public function groups_data_ajax() {
		$this->groups(true, false);
	}

	public function groups_data_modalbox() {
		$this->groups(true, false, true, true);
	}

	protected function list_generic($field = NULL, $order = NULL, $page = 0) {
		/* Security Permissions Check */
		if (!$this->security->perm_check($this->_security_perms, $this->security->perm_read, $this->_name))
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->_default_charset, !$this->request->is_ajax());

		/* Setup ordering field if none was specified */
		if ($field == NULL)
			$field = $this->_table_field_order_list;

		/* Grant that field contains only safe characters */
		if (!$this->security->safe_names($field, $this->_security_safe_chars))
			$this->response->code('403', NDPHP_LANG_MOD_INVALID_CHARS_FIELD, $this->_default_charset, !$this->request->is_ajax());

		/* Setup ordering */
		if ($order == NULL)
			$order = $this->_table_field_order_list_modifier;

		/* Initialize $data */
		$data = array();

		/* Load enter plugins */
		foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/list_generic_enter.php') as $plugin)
			include($plugin);

		/* Hook handler (enter) */
		$hook_enter_return = $this->_hook_list_generic_enter($data, $field, $order, $page);

		/* If logging is enabled, log this listing request */
		if ($this->_logging === true) {
			$log_transaction_id = openssl_digest('LIST' . $this->_name . $this->_session_data['sessions_id'] . date('Y-m-d H:i:s') . mt_rand(1000000, 9999999), 'sha1');

			$this->db->insert('logging', array(
				'operation' => 'LIST',
				'_table' => $this->_name,
				'_field' => 'PAGE / FIELD / ORDER',
				'entryid' => ($page >= 0) ? ((($page / $this->_table_pagination_rpp_list) + 1) . ' / ' . ($field ? $field : $this->_table_field_order_list) . ' / ' . ($order ? $order : $this->_table_field_order_list_modifier)) : ('0 / ' . ($field ? $field : $this->_table_field_order_list) . ' / ' . ($order ? $order : $this->_table_field_order_list_modifier)),
				'transaction' => $log_transaction_id,
				'registered' => date('Y-m-d H:i:s'),
				'sessions_id' => $this->_session_data['sessions_id'],
				'users_id' => $this->_session_data['user_id']
			));
		}

		/* Setup charts */
		$this->_charts_config();

		/* Get view title value */
		$title = NULL;

		if (isset($this->_menu_entries_aliases[$this->_name])) {
			$title = $this->_menu_entries_aliases[$this->_name] . $this->_view_title_sep . NDPHP_LANG_MOD_OP_LIST;
		} else {
			$title = $this->_viewhname . $this->_view_title_sep . NDPHP_LANG_MOD_OP_LIST;
		}

		/* Get view description value */
		$description = NDPHP_LANG_MOD_OP_LIST . " " . $this->_viewhname;

		/* Setup basic view data */
		$data = array_merge($data, $this->_get_view_data_generic($title, $description));

		/* Setup specific view data */
		$data['view']['links']['quick'] = $this->_links_quick_modal_list;
		$data['view']['links']['submenu'] = $this->_links_submenu_body_list;
		$data['view']['links']['breadcrumb'] = $this->_get_breadcrumb('list', NDPHP_LANG_MOD_OP_LIST);

		$data['config']['charts']['total'] = count($this->_charts);

		$data['config']['render']['images'] = $this->_view_image_file_rendering;
		$data['config']['render']['size'] = $this->_view_image_file_rendering_size_list;
		$data['config']['render']['ext'] = $this->_view_image_file_rendering_ext;

		$data['config']['choices_class'] = $this->_rel_choice_table_row_class;

		$data['view']['fields'] = $this->_get_fields(NULL, $this->_hide_fields_list); /* _get_fields() uses a perm_read filter by default */

		/* Resolve fields */
		$this->_field_resolve($data['view']['fields']);

		/* Switch order on each call */
		if ($order == "asc")
			$data['config']['order'] = "desc";
		else
			$data['config']['order'] = "asc";
		
		$data['config']['order_by'] = $field;

		/* Order the results as requested from the url segment ('asc' or 'desc') */
		$this->db->order_by($field, $order);

		/* Set FROM in order to get a clean get_compiled_select_str() */
		$this->db->from($this->_name);

		/* Filter table rows, if applicable */
		$this->_table_row_filter_apply();

		/* 
		 * Compress, encrypt and encode (base64) the last query to be passed to export
		 * controller.
		 * Also encode the result with rawurlencode()
		 * 
		 * The export query value is passed to the results view so it can be used by
		 * other requests performed in the view, such as exports (to PDF, CSV, etc).
		 *
		 * Note that we need to get the export_query _BEFORE_ setting the LIMIT
		 *
		 * FIXME: TODO: export query should be stored in user session and shall no be passed via URL
		 *
		 */
		$data['view']['export_query'] = rawurlencode($this->ndphp->safe_b64encode($this->encrypt->encode(gzcompress($this->db->get_compiled_select_str(NULL, true, false), 9))));

		/* If this is a REST call, do not limit the results (as in, display all) */
		if ($this->_json_replies !== true && $page >= 0) {
			/* Limit results to the number of rows per page (pagination) */
			$this->db->limit($this->_table_pagination_rpp_list, $page);
		}

		/* Hook filter: Apply filters, if any */
		$hook_enter_return = $this->_hook_list_generic_filter($data, $field, $order, $page, $hook_enter_return);

		/* Store result array under view data array */
		$data['view']['result_array'] = $this->_field_value_mangle($data['view']['fields'], $this->db->get());

		/* Pagination */
		if ($page >= 0) {
			$pagcfg['page'] = ($page / $this->_table_pagination_rpp_list) + 1; // $page is actually the number of the first row of the page
			$pagcfg['base_url'] = base_url() . 'index.php/' . $this->_name . '/list_default/' . $field . '/' . $order . '/@ROW_NR@';
			$pagcfg['onclick'] = 'ndphp.ajax.load_data_ordered_list(event, \'' . $this->_name . '\', \'' . $field . '\', \'' . $order . '\', \'@ROW_NR@\');';
			$this->db->from($this->_name);							/* FIXME:																*/
			$this->_table_row_filter_apply();						/*   - A better approach to retrieve total rows should be implemented	*/
			$pagcfg['total_rows'] = $this->db->count_all_results(); /*   - Consider SQL_CALC_FOUND_ROWS?									*/
			$pagcfg['per_page'] = $this->_table_pagination_rpp_list;
			
			$this->pagination->initialize($pagcfg);
			$data['view']['links']['pagination'] = $this->pagination->create_links();
			$data['view']['page'] = $page;
		}

		/* Hidden fields */
		$data['config']['hidden_fields'] = $this->_hide_fields_list;

		/* Hook handler (leave) */
		$this->_hook_list_generic_leave($data, $field, $order, $page, $hook_enter_return);

		/* Load leave plugins */
		foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/list_generic_leave.php') as $plugin)
			include($plugin);

		/* Add pagination, if required */
		if ($page >= 0) {
			$total_items_from = ($pagcfg['per_page'] * ($page / $pagcfg['per_page']));
			$total_items_from += $pagcfg['total_rows'] ? 1 : 0;
			$total_items_to = (($pagcfg['per_page'] * ($page / $pagcfg['per_page'])) + $pagcfg['per_page']);
			$total_items_to = ($total_items_to <= $pagcfg['total_rows'] ? $total_items_to : $pagcfg['total_rows']);
			$data['view']['total_items_from'] = $total_items_from;
			$data['view']['total_items_to'] = $total_items_to;
			$data['view']['total_items'] = $pagcfg['total_rows'];
		}

		/* All good */
		return $data;
	}

	public function list_default($field = NULL, $order = NULL, $page = 0,
		$body_only = false, $body_header = true, $body_footer = true, $modalbox = false)
	{
		$data = $this->list_generic($field, $order, $page);

		if ($modalbox)
			$data['config']['modalbox'] = true;

		/* If this is an AJAX call, then we should only render the page body */
		if ($this->request->is_ajax())
			$body_only = true;

		/* If this is an JSON API request, just reply the data (do not load the views) */
		if ($this->_json_replies === true) {
			echo($this->json_list($data));
			return;
		}

		/* Load Views */
		$this->_load_method_views('list', $data, $body_only, $body_header, $body_footer);
	}

	public function list_body_ajax($field = NULL, $order = NULL, $page = 0) {
		$this->list_default($field, $order, $page, true);
	}

	public function list_data_ajax($field = NULL, $order = NULL, $page = 0) {
		$this->list_default($field, $order, $page, true, false);
	}

	public function list_data_modalbox($field = NULL, $order = NULL, $page = 0) {
		$this->list_default($field, $order, $page, true, false, true, true);
	}

	protected function list_group_generic($grouping_field, $field = NULL, $order = NULL, $page = 0) {
		/* TODO: FIXME:
		 *
		 * There's a significant performance impact on this approach and no pagination support is currently
		 * implemented for grouping. The redesign of this feature is required.
		 *
		 */
		/* If logging is enabled, log this search request */
		if ($this->_logging === true) {
			$log_transaction_id = openssl_digest('LIST' . $this->_name . $this->_session_data['sessions_id'] . date('Y-m-d H:i:s') . mt_rand(1000000, 9999999), 'sha1');

			$this->db->insert('logging', array(
				'operation' => 'LIST',
				'_table' => $this->_name,
				'_field' => 'GROUPS',
				'entryid' => $grouping_field,
				'transaction' => $log_transaction_id,
				'registered' => date('Y-m-d H:i:s'),
				'sessions_id' => $this->_session_data['sessions_id'],
				'users_id' => $this->_session_data['user_id']
			));
		}

		$data = $this->list_generic($field, $order, -1 /* $page */); /* FIXME: We should not rely on list_generic(). A specific implementation for grouping is required. */

		$group_result_array = array();
		$group_hash = array();

		$result_array_iter = $data['view']['result_array'];

		/* Create a set of grouped results (grouped by the existing entries on the foreign table identified by grouping_field relationship) */
		foreach ($data['view']['fields'][$grouping_field]['options'] as $opt_id => $opt_val) {
			$result_array_remain = array(); /* Will be used to store the non-matching entries (remaining entries) */

			/* Iterate the (remaining) results... */
			foreach ($result_array_iter as $row) {
				if (substr($grouping_field, -3) == '_id') {
					if ($row[$grouping_field] == $opt_val) {
						/* This row belongs to this group... */
						$group_result_array[$opt_val][] = $row;
						$group_hash[$opt_val] = openssl_digest($opt_val, 'sha1');
					} else {
						/* If it does not belong to this group, add it to the remaining entries array */
						$result_array_remain[] = $row;
					}
				} else if (substr($grouping_field, 0, 4) == 'rel_') {
					if (in_array($opt_val, explode($this->_rel_group_concat_sep, $row[$grouping_field]))) {
						/* This row belongs to this group... */
						$group_result_array[$opt_val][] = $row;
						$group_hash[$opt_val] = openssl_digest($opt_val, 'sha1');
					}

					/* This row can belong to multiple groups, so the remaining will always be the full list */
					$result_array_remain[] = $row;
				}
			}

			/* Update iterator with the remaining data */
			$result_array_iter = $result_array_remain;
		}

		/* Update view data */
		$data['view']['grouping_field'] = $grouping_field;
		$data['view']['grouping_result_array'] = $group_result_array;
		$data['view']['grouping_hashes'] = $group_hash;

		/* All good */
		return $data;
	}

	public function list_group($grouping_field, $field = NULL, $order = NULL, $page = 0,
		$body_only = false, $body_header = true, $body_footer = true, $modalbox = false)
	{
		$data = $this->list_group_generic($grouping_field, $field, $order, $page);

		if ($modalbox)
			$data['config']['modalbox'] = true;

		/* If this is an AJAX call, then we should only render the page body */
		if ($this->request->is_ajax())
			$body_only = true;

		/* If this is an JSON API request, just reply the data (do not load the views) */
		if ($this->_json_replies === true) {
			echo($this->json_list($data));
			return;
		}

		/* Load Views */
		$this->_load_method_views(__FUNCTION__, $data, $body_only, $body_header, $body_footer);
	}

	public function list_group_body_ajax($grouping_field, $field = NULL, $order = NULL, $page = 0) {
		$this->list_group($grouping_field, $field, $order, $page, true);
	}

	public function list_group_data_ajax($grouping_field, $field = NULL, $order = NULL, $page = 0) {
		$this->list_group($grouping_field, $field, $order, $page, true, false);
	}

	public function list_group_data_modalbox($grouping_field, $field = NULL, $order = NULL, $page = 0) {
		$this->list_group($grouping_field, $field, $order, $page, true, false, true, true);
	}

	public function import_csv() {
		$data = $this->_get_view_data_generic();

		$data['view']['hname'] = NDPHP_LANG_MOD_OP_IMPORT_CSV;
		$data['config']['modalbox'] = true;

		/* Load Views */
		$this->_load_view('import_csv', $data, true);
	}

	public function import($type = 'csv') {
		/* Currently, only csv imports are supported */
		if ($type != 'csv')
			$this->response->code('403', NDPHP_LANG_MOD_INVALID_REQUEST, $this->_default_charset, !$this->request->is_ajax());

		/* Grant that $_POST keys are safe */
		if (!$this->security->safe_keys($_POST, $this->_security_safe_chars))
			$this->response->code('403', NDPHP_LANG_MOD_INVALID_POST_KEYS, $this->_default_charset, !$this->request->is_ajax());

		/* Some sanity checks first */
		if (!in_array($_POST['import_csv_sep'], array(',', ';')))
			$this->response->code('500', NDPHP_LANG_MOD_INVALID_POST_DATA, $this->_default_charset, !$this->request->is_ajax());

		if (!in_array($_POST['import_csv_delim'], array('"', '\'')))
			$this->response->code('500', NDPHP_LANG_MOD_INVALID_POST_DATA, $this->_default_charset, !$this->request->is_ajax());

		if (!in_array($_POST['import_csv_esc'], array('\\')))
			$this->response->code('500', NDPHP_LANG_MOD_INVALID_POST_DATA, $this->_default_charset, !$this->request->is_ajax());

		if (!in_array($_POST['import_csv_rel_type'], array('value', 'id')))
			$this->response->code('500', NDPHP_LANG_MOD_INVALID_POST_DATA, $this->_default_charset, !$this->request->is_ajax());

		/* Craft CSV file destination path */
		$dest_path = SYSTEM_BASE_DIR . '/uploads/import/' . $this->_session_data['user_id'] . '/' . $this->_name;

		/* Create directory if it doesn't exist */
		if (!file_exists($dest_path) && mkdir($dest_path, 0750, true) === false)
			$this->response->code('500', NDPHP_LANG_MOD_UNABLE_FILE_UPLOAD . ' "' . $_FILES[$field]['name'] . '": ' . NDPHP_LANG_MOD_UNABLE_CREATE_DIRECTORY, $this->_default_charset, !$this->request->is_ajax());

		/* Pre-process the CSV file (or direct input), creating a local CSV file */
		if (isset($_FILES['import_csv_file'])) {
			$field = 'import_csv_file';

			if (!isset($_FILES[$field]['error']) || is_array($_FILES[$field]['error']))
				$this->response->code('500', NDPHP_LANG_MOD_UNABLE_FILE_UPLOAD . ' "' . $_FILES[$field]['name'] . '": ' . NDPHP_LANG_MOD_INVALID_PARAMETERS, $this->_default_charset, !$this->request->is_ajax());

			/* Grant that there are no errors */
			if ($_FILES[$field]['error'] > 0)
				$this->response->code('403', NDPHP_LANG_MOD_UNABLE_FILE_UPLOAD . ' "' . $_FILES[$field]['name'] . '": ' . error_upload_file($_FILES[$field]['error']), $this->_default_charset, !$this->request->is_ajax());

			/* Validate file size (This is a fallback for php settings) */
			if ($_FILES[$field]['size'] > $this->_upload_file_max_size)
				$this->response->code('403', NDPHP_LANG_MOD_UNABLE_FILE_UPLOAD . ' "' . $_FILES[$field]['name'] . '": ' . NDPHP_LANG_MOD_INVALID_SIZE_TOO_BIG, $this->_default_charset, !$this->request->is_ajax());

			/* Compute file hash */
			$file_hash = openssl_digest($_FILES[$field]['name'], 'sha256');

			if (move_uploaded_file($_FILES[$field]['tmp_name'], $dest_path . '/' . $file_hash) === false)
				$this->response->code('403', NDPHP_LANG_MOD_UNABLE_FILE_COPY . ' "' . $_FILES[$field]['name'] . '"', $this->_default_charset, !$this->request->is_ajax());

			/* Open CSV file */
			if (($fp_csv = fopen($dest_path . '/' . $file_hash, 'r')) === false)
				$this->response->code('500', NDPHP_LANG_MOD_UNABLE_FILE_OPEN_READ . ' "' . $dest_path . '/' . $file_hash . '"', $this->_default_charset, !$this->request->is_ajax());
		} else if ($_POST['import_csv_text']) {
			/* Create a temporary file hash */
			$file_hash = openssl_digest($dest_path . mt_rand(1000000, 9999999), 'sha256');

			/* Create a temporary CSV file */
			if (($fp_csv = fopen($dest_path . '/' . $file_hash, 'w')) === false)
				$this->response->code('500', NDPHP_LANG_MOD_UNABLE_FILE_OPEN_WRITE . ' "' . $dest_path . '/' . $file_hash . '"', $this->_default_charset, !$this->request->is_ajax());

			/* Write CSV contents to file */
			if (fwrite($fp_csv, $_POST['import_csv_text']) === false)
				$this->response->code('500', NDPHP_LANG_MOD_UNABLE_FILE_WRITE . ' "' . $dest_path . '/' . $file_hash . '"', $this->_default_charset, !$this->request->is_ajax());

			/* Close the CSV file so we can re-open it as read-only */
			fclose($fp_csv);

			/* Re-open CSV file as read-only */
			if (($fp_csv = fopen($dest_path . '/' . $file_hash, 'r')) === false)
				$this->response->code('500', NDPHP_LANG_MOD_UNABLE_FILE_OPEN_READ . ' "' . $dest_path . '/' . $file_hash . '"', $this->_default_charset, !$this->request->is_ajax());
		} else {
			$this->response->code('500', NDPHP_LANG_MOD_INVALID_DATA_FOUND, $this->_default_charset, !$this->request->is_ajax());
		}

		
		/** Process the CSV stream **/

		/* Fetch the CSV header */
		$header = fgetcsv($fp_csv, 0, $_POST['import_csv_sep'], $_POST['import_csv_delim'], $_POST['import_csv_esc']);

		/* Set the current line of file */
		$line = 1;

		/* Grant that CSV entry is valid and EOF was not reached */
		if ($header === false || $header === NULL)
			$this->response->code('500', NDPHP_LANG_MOD_INVALID_DATA_FOUND . '. #2', $this->_default_charset, !$this->request->is_ajax());

		/* Convert CSV headers to controller field names */
		$fields = $this->_get_fields();
		$header_resolve = array();

		foreach ($header as $header_field) {
			$field_found = false;

			foreach ($fields as $field => $meta) {
				/* First, check if the header name matches the controller field system name */
				if (strtolower($field) == strtolower($header_field)) {
					array_push($header_resolve, $field);
					$field_found = true;
					break;
				} else if (strtolower($fields[$field]['viewname']) == strtolower($header_field)) { /* Second, check if the header name matches the controller field view name */
					array_push($header_resolve, $field);
					$field_found = true;
					break;
				}

				/* FIXME: What else? */
			}

			if (!$field_found)
				$this->response->code('500', NDPHP_LANG_MOD_UNABLE_MATCH_CSV_FIELD_CTRL, $this->_default_charset, !$this->request->is_ajax());
		}

		/* Assign the resolved fields array to $header */
		$header = $header_resolve;

		/* Initialize transaction */
		$this->db->trans_begin();

		/* Fetch CSV rows */
		while (true) {
			$row = fgetcsv($fp_csv, 0, $_POST['import_csv_sep'], $_POST['import_csv_delim'], $_POST['import_csv_esc']);

			/* Increment the current line number */
			$line ++;

			/* Grant that CSV entry is valid and EOF was not reached */
			if ($row === false || $row === NULL)
				break;

			/* Reset data variables */
			$entry = array();
			$rel = array();

			/* Populate entry data */
			for ($i = 0; $i < count($header); $i ++) {
				/* FIXME: TODO: Missing mixed relationships */

				if (substr($header[$i], 0, 4) == 'rel_') {
					/* Split $row[$i], fetch id's from foreign table, and populate $rel array */
					
					/* Split $row[$i] values by group concatenation separator */
					$rel_values = explode($this->_rel_group_concat_sep, $row[$i]);

					/* Get foreign table name */
					$foreign_table = array_pop(array_diff($this->_get_multiple_rel_table_names($header[$i], $this->_name), array($this->_name)));

					/* Get foreign table fields list */
					$ftable_fields = $this->_get_table_fields($foreign_table);

					/* Initialize multiple relationship values array */
					$rel[$header[$i]] = array();

					foreach ($rel_values as $rel_value) {
						$rel_value_id = NULL;

						/* If there is a separator set, we need to compare the CSV value with the resulting value of concatenated fields */
						if (isset($this->_rel_table_fields_config[$foreign_table])) {
							if ($this->_rel_table_fields_config[$foreign_table][1] !== NULL) {
								$ft_fields = array();

								/* Gather the name of the fields to be concatenated */
								foreach ($this->_rel_table_fields_config[$foreign_table][2] as $field_nr)
									array_push($ft_fields, $ftable_fields[$field_nr]);

								/* Fetch the id value */
								$this->db->select('id');
								$this->db->from($foreign_table);
								$this->db->where('CONCAT_WS(\'' . $this->_rel_table_fields_config[$foreign_table][1] . '\', `' . implode('`,`', $ft_fields) . '`)', $rel_value, false);
								$q = $this->db->get();
							} else {
								/* There is no field concatenation for this relationship.
								 * However, we need to check which field is used by default to compare its value
								 */

								/* Fetch the id value */
								$this->db->select('id');
								$this->db->from($foreign_table);
								$this->db->where($ftable_fields[$this->_rel_table_fields_config[$foreign_table][2][0]], $rel_value);
								$q = $this->db->get();
							}
						} else {
							/* Since there's no special configuration set to the foreign table, use the default settings.
							 * (That is, compare the column value to the second field of the foreign table)
							 */
							$this->db->select('id');
							$this->db->from($foreign_table);
							$this->db->where($ftable_fields[1], $rel_value);
							$q = $this->db->get();
						}

						/* Check if there is a match ... */
						if ($q->num_rows()) {
							/* The foreign key was found */
							$frow = $q->row_array();

							/* Reassign $row[$i] value with the integer id value (FOREIGN KEY) */
							$rel_value_id = $frow['id'];
						} else {
							/* The foreign key wasn't found */
							$this->db->trans_rollback();

							$this->response->code('500', NDPHP_LANG_MOD_UNABLE_MATCH_REL_VALUE_FK . ' (CSV ' . NDPHP_LANG_MOD_WORD_LINE . ': ' . $line . ', ' . NDPHP_LANG_MOD_WORD_COLUMN . ': ' . $i . ')', $this->_default_charset, !$this->request->is_ajax());
						}

						array_push($rel[$header[$i]], $rel_value_id);
					}

					/* Multiple relationships aren't regular fields, so they won't be placed on $entry array */
					continue;
				} else if (substr($header[$i], -3) == '_id' && $_POST['import_csv_rel_type'] == 'value') {
					/* Resolve $row[$i] value to integer id by fetching it from foreign table */

					/* Get foreign table name */
					$foreign_table = substr($header[$i], 0, -3);

					/* Get foreign table fields list */
					$ftable_fields = $this->_get_table_fields($foreign_table);
					
					/* If there is a separator set, we need to compare the CSV value with the resulting value of concatenated fields */
					if (isset($this->_rel_table_fields_config[$foreign_table])) {
						if ($this->_rel_table_fields_config[$foreign_table][1] !== NULL) {
							$ft_fields = array();

							/* Gather the name of the fields to be concatenated */
							foreach ($this->_rel_table_fields_config[$foreign_table][2] as $field_nr)
								array_push($ft_fields, $ftable_fields[$field_nr]);

							/* Fetch the id value */
							$this->db->select('id');
							$this->db->from($foreign_table);
							$this->db->where('CONCAT_WS(\'' . $this->_rel_table_fields_config[$foreign_table][1] . '\', `' . implode('`,`', $ft_fields) . '`)', $row[$i], false);
							$q = $this->db->get();
						} else {
							/* There is no field concatenation for this relationship.
							 * However, we need to check which field is used by default to compare its value
							 */

							/* Fetch the id value */
							$this->db->select('id');
							$this->db->from($foreign_table);
							$this->db->where($ftable_fields[$this->_rel_table_fields_config[$foreign_table][2][0]], $row[$i]);
							$q = $this->db->get();
						}
					} else {
						/* Since there's no special configuration set to the foreign table, use the default settings.
						 * (That is, compare the column value to the second field of the foreign table)
						 */
						$this->db->select('id');
						$this->db->from($foreign_table);
						$this->db->where($ftable_fields[1], $row[$i]);
						$q = $this->db->get();
					}

					/* Check if there is a match ... */
					if ($q->num_rows()) {
						/* The foreign key was found */
						$frow = $q->row_array();

						/* Reassign $row[$i] value with the integer id value (FOREIGN KEY) */
						$row[$i] = $frow['id'];
					} else {
						/* The foreign key wasn't found */
						$this->db->trans_rollback();

						$this->response->code('500', NDPHP_LANG_MOD_UNABLE_MATCH_REL_VALUE_FK . ' (CSV ' . NDPHP_LANG_MOD_WORD_LINE . ': ' . $line . ', ' . NDPHP_LANG_MOD_WORD_COLUMN . ': ' . $i . ')', $this->_default_charset, !$this->request->is_ajax());
					}
				}

				/* NOTE: If $_POST['import_csv_rel_type'] == 'id', then it is expected that $row[$i] already contains the ID value */

				/* Assign K/V pair to entry array */
				$entry[$header[$i]] = $row[$i];
			}

			/* Insert the entry into database */
			$this->db->insert($this->_name, $entry);

			/* Fetch the last inserted id */
			$last_id = $this->db->last_insert_id();

			/* Insert multiple relationships */
			foreach ($rel as $rel_table => $values) {
				foreach ($rel[$rel_table] as $fid) {
					$this->db->insert($rel_table, array(
						$this->_name . '_id' => $last_id,
						array_pop(array_diff($this->_get_multiple_rel_table_names($header[$i], $this->_name), array($this->_name))) . '_id' => $fid
					));
				}
			}
		}

		/* Close the CSV file handler */
		fclose($fp_csv);

		/* Check if transaction was successful */
		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			$this->response->code('500', NDPHP_LANG_MOD_FAILED_TRANSACTION, $this->_default_charset, !$this->request->is_ajax());
		}

		/* Commit transaction */
		$this->db->trans_commit();

		/* Unlink the csv file */
		unlink($dest_path . '/' . $file_hash);
	}

	public function search_save() {
		$data = $this->_get_view_data_generic();

		$data['view']['hname'] = NDPHP_LANG_MOD_OP_SAVE_SEARCH;
		$data['config']['modalbox'] = true;

		/* Load Views */
		$this->_load_view('search_save', $data, true);
	}

	public function search_save_insert() {
		/* Grant that $_POST keys are safe */
		if (!$this->security->safe_keys($_POST, $this->_security_safe_chars))
			$this->response->code('403', NDPHP_LANG_MOD_INVALID_POST_KEYS, $this->_default_charset, !$this->request->is_ajax());

		$this->db->trans_begin();

		$this->db->insert('_saved_searches', array(
			'search_name'	=> $_POST['search_save_name'],
			'description'	=> $_POST['search_save_description'],
			'controller'	=> $this->_name,
			'result_query'	=> $_POST['search_save_result_query'],
			'users_id'		=> $this->_session_data['user_id']
		));

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			$this->response->code('500', NDPHP_LANG_MOD_UNABLE_STORE_SAVED_SEARCH, $this->_default_charset, !$this->request->is_ajax());
		} else {
			$this->db->trans_commit();
		}
	}

	public function search_save_delete($search_saved_id) {
		$this->db->trans_begin();

		/* Check if the saved search exists and/or we've permissions to delete the saved search */
		$this->db->select('users_id');
		$this->db->from('_saved_searches');
		$this->db->where('controller', $this->_name);
		$this->db->where('users_id', $this->_session_data['user_id']);
		$q = $this->db->get();

		if (!$q->num_rows())
			$this->response->code('500', NDPHP_LANG_MOD_ACCESS_SAVED_SEARCH_DELETE, $this->_default_charset, !$this->request->is_ajax()); /* Keep the reason ambiguous */

		$this->db->delete('_saved_searches', array('id' => $search_saved_id));

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			$this->response->code('500', NDPHP_LANG_MOD_UNABLE_DELETE_SAVED_SEARCH, $this->_default_charset, !$this->request->is_ajax());
		} else {
			$this->db->trans_commit();
		}
	}

	protected function search_generic($advanced = true) {
		/* Security Permissions Check */
		if (!$this->security->perm_check($this->_security_perms, $this->security->perm_read, $this->_name))
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->_default_charset, !$this->request->is_ajax());

		/* If logging is enabled, log this search request */
		if ($this->_logging === true) {
			$log_transaction_id = openssl_digest('SEARCH' . $this->_name . $this->_session_data['sessions_id'] . date('Y-m-d H:i:s') . mt_rand(1000000, 9999999), 'sha1');

			$this->db->insert('logging', array(
				'operation' => 'SEARCH',
				'_table' => $this->_name,
				'transaction' => $log_transaction_id,
				'registered' => date('Y-m-d H:i:s'),
				'sessions_id' => $this->_session_data['sessions_id'],
				'users_id' => $this->_session_data['user_id']
			));
		}

		/* Initialize $data */
		$data = array();

		/* Load enter plugins */
		foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/search_generic_enter.php') as $plugin)
			include($plugin);

		/* Hook handler (enter) */
		$hook_enter_return = $this->_hook_search_generic_enter($data, $advanced);

		/* Get view title */
		$title = NULL;

		if (isset($this->_menu_entries_aliases[$this->_name])) {
			$title = $this->_menu_entries_aliases[$this->_name] . $this->_view_title_sep . NDPHP_LANG_MOD_OP_SEARCH;
		} else {
			$title = $this->_viewhname . $this->_view_title_sep . NDPHP_LANG_MOD_OP_SEARCH;
		}

		/* Get view description value */
		$description = NDPHP_LANG_MOD_OP_SEARCH . " " . $this->_viewhname;

		/* Setup basic view data */
		$data = array_merge($data, $this->_get_view_data_generic($title, $description));

		/* Setup specific view data */
		$data['view']['fields'] = $this->_filter_fields($this->_security_perms, $this->security->perm_search, $this->_get_fields(NULL, $this->_hide_fields_search)); /* _get_fields() uses a perm_read filter by default */
		$data['view']['links']['submenu'] = $this->_links_submenu_body_search;
		$data['view']['links']['breadcrumb'] = $this->_get_breadcrumb('search', NDPHP_LANG_MOD_OP_SEARCH);
		$data['view']['saved_searches'] = $this->_get_saved_searches();
		
		/* Hidden fields */
		$data['config']['hidden_fields'] = $this->_hide_fields_search;

		/* Hook handler (leave) */
		$this->_hook_search_generic_leave($data, $advanced, $hook_enter_return);

		/* Load leave plugins */
		foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/search_generic_leave.php') as $plugin)
			include($plugin);

		/* All good */
		return $data;
	}

	public function search($advanced = true, $body_only = false, $body_header = true, $body_footer = true, $modalbox = false) {
		$data = $this->search_generic($advanced);

		if ($modalbox)
			$data['config']['modalbox'] = true;

		/* If this is an AJAX call, then we should only render the page body */
		if ($this->request->is_ajax())
			$body_only = true;

		/* Load Views */
		$this->_load_method_views(__FUNCTION__, $data, $body_only, $body_header, $body_footer);
	}

	public function search_body_ajax($advanced = true) {
		$this->search($advanced, true);
	}

	public function search_data_ajax($advanced = true) {
		$this->search($advanced, true, false);
	}

	public function search_data_modalbox($advanced = true) {
		$this->search($advanced, true, false, true, true);
	}

	protected function result_generic($type = 'advanced', $result_query = NULL,
							$order_field = NULL, $order_type = NULL, $page = 0) {
		/* Grant that $_POST keys are safe */
		if (!$this->security->safe_keys($_POST, $this->_security_safe_chars))
			$this->response->code('403', NDPHP_LANG_MOD_INVALID_POST_KEYS, $this->_default_charset, !$this->request->is_ajax());

		/* Security Permissions Check */
		if (!$this->security->perm_check($this->_security_perms, $this->security->perm_read, $this->_name))
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->_default_charset, !$this->request->is_ajax());

		if ($order_field == NULL)
			$order_field = $this->_table_field_order_result;

		if (!$this->security->safe_names($order_field, $this->_security_safe_chars))
			$this->response->code('403', NDPHP_LANG_MOD_INVALID_CHARS_FIELD_ORDER, $this->_default_charset, !$this->request->is_ajax());

		if ($order_type == NULL)
			$order_type = $this->_table_field_order_result_modifier;

		/* Initialize $data */
		$data = array();

		/* Load enter plugins */
		foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/result_generic_enter.php') as $plugin)
			include($plugin);

		/* Hook handler (enter) */
		$hook_enter_return = $this->_hook_result_generic_enter($data, $type, $result_query, $order_field, $order_type, $page);

		/* If logging is enabled, log this search result request */
		if ($this->_logging === true) {
			$log_transaction_id = openssl_digest('RESULT' . $this->_name . $this->_session_data['sessions_id'] . date('Y-m-d H:i:s') . mt_rand(1000000, 9999999), 'sha1');

			$this->db->insert('logging', array(
				'operation' => 'RESULT',
				'_table' => $this->_name,
				'_field' => 'PAGE / FIELD / ORDER',
				'entryid' => ($page >= 0) ? ((($page / $this->_table_pagination_rpp_result) + 1) . ' / ' . ($order_field ? $order_field : $this->_table_field_order_result) . ' / ' . ($order_type ? $order_type : $this->_table_field_order_result_modifier)) : ('0 / ' . ($order_field ? $order_field : $this->_table_field_order_result) . ' / ' . ($order_type ? $order_type : $this->_table_field_order_result_modifier)),
				'value_new' => (($type == "basic") ? $_POST['search_value'] : (($type == "query") ? $result_query : json_encode($_POST, JSON_PRETTY_PRINT))),
				'transaction' => $log_transaction_id,
				'registered' => date('Y-m-d H:i:s'),
				'sessions_id' => $this->_session_data['sessions_id'],
				'users_id' => $this->_session_data['user_id']
			));
		}

		/* Setup charts */
		$this->_charts_config();

		/* Get view title */
		$title = NULL;

		if (isset($this->_menu_entries_aliases[$this->_name])) {
			$title = $this->_menu_entries_aliases[$this->_name] . $this->_view_title_sep . NDPHP_LANG_MOD_OP_RESULT;
		} else {
			$title = $this->_viewhname . $this->_view_title_sep . NDPHP_LANG_MOD_OP_RESULT;
		}

		/* Get view description value */
		$description = NDPHP_LANG_MOD_OP_RESULT . " " . $this->_viewhname;

		/* Setup basic view data */
		$data = array_merge($data, $this->_get_view_data_generic($title, $description));

		/* Setup specific view data */
		$data['view']['links'] = array();
		$data['view']['links']['quick'] = $this->_links_quick_modal_result;
		$data['view']['links']['submenu'] = $this->_links_submenu_body_result;

		$data['config']['charts']['total'] = count($this->_charts);

		$data['config']['render'] = array();
		$data['config']['render']['images'] = $this->_view_image_file_rendering;
		$data['config']['render']['size'] = $this->_view_image_file_rendering_size_list;
		$data['config']['render']['ext'] = $this->_view_image_file_rendering_ext;

		$data['config']['choices_class'] = $this->_rel_choice_table_row_class;

		/* FIXME: Avoid using 2 calls to _get_fields() ... Use an unfiltered _get_fields() and generate two filtered lists from it */
		$ftypes = $this->_get_fields(NULL, $this->_hide_fields_search); /* _get_fields() uses a perm_read filter by default */
		$ftypes_result = $this->_get_fields(NULL, $this->_hide_fields_result);

		/* Validate search type */
		if ($type == 'basic') {
			/* If we're comming from a basic search ... */
			foreach ($ftypes as $field => $ftype) {
				/* Ignore separators */
				if ($ftype[$field]['type'] == 'separator')
					continue;

				/* Check permissions */
				if (!$this->security->perm_check($this->_security_perms, $this->security->perm_search, $this->_name, $field))
					continue;

				/* Search mixed relationships */
				if ($ftypes[$field]['type'] == 'mixed') {
					/* Fetch mixed table fields */
					$mt_fields = $this->_get_table_fields($field);

					/* Join the mixed table to the query */
					$this->db->join($field, '`' . $this->_name . '`.`id` = `' . $field . '`.`' . $this->_name . '_id`', 'left');

					/* Search for mixed table content matches */
					foreach ($mt_fields as $mixed_field) {
						/* Ignore mixed id */
						if ($mixed_field == 'id')
							continue;

						/* Ignore single relationship field referencing to this table */
						if ($mixed_field == ($this->_name . '_id'))
							continue;

						/* Get the mixed foreign table */
						$mixed_foreign_table = array_pop(array_diff($this->_get_mixed_rel_table_names($field, $this->_name), array($this->_name)));

						/* Ignore single relationship field referencing to foreign table */
						if ($mixed_field == ($mixed_foreign_table . '_id'))
							continue;

						/* Check if we've permissions to read the foreign field on the foreign table */
						if (!$this->security->perm_check($this->_security_perms, $this->security->perm_read, $mixed_foreign_table, $mixed_field))
							continue;

						/* Check if we've permissions to search the foreign field on the foreign table */
						if (!$this->security->perm_check($this->_security_perms, $this->security->perm_search, $mixed_foreign_table, $mixed_field))
							continue;

						/* FIXME: Currently we only support string matching (partial date and datetime values are not yet implemented) */
						$this->db->or_like($field . '.' . $mixed_field, '%' . $_POST['search_value'] . '%');
					}
				}

				if (($ftype['input_type'] == 'text') || ($ftype['input_type'] == 'textarea') || 
						($ftype['input_type'] == 'timer') || ($ftype['input_type'] == 'file')) {
					if ($ftype['type'] == 'date') {
						/* If field type is date, compare as it was an integer.
						 * Also use YEAR(), MONTH() and DAY() functions to search for each
						 * date field independently.
						 */
						if (is_numeric($_POST['search_value'])) {
							$this->db->or_like($this->_field_unambig($field, $ftypes), '%' . $_POST['search_value'] . '%');
							$this->db->or_where('YEAR(' . $this->_field_unambig($field, $ftypes) . ')', $_POST['search_value'], false);
							$this->db->or_where('MONTH(' . $this->_field_unambig($field, $ftypes) . ')', $_POST['search_value'], false);
							$this->db->or_where('DAY(' . $this->_field_unambig($field, $ftypes) . ')', $_POST['search_value'], false);
						} else {
							$this->db->or_like($this->_field_unambig($field, $ftypes), '%' . $_POST['search_value'] . '%');
							$this->db->or_where($this->_field_unambig($field, $ftypes), $_POST['search_value']);
						}
					} else if ($ftype['type'] == 'time') {
						if (is_numeric($_POST['search_value'])) {
							$this->db->or_like($this->_field_unambig($field, $ftypes), '%' . $_POST['search_value'] . '%');
							$this->db->or_where('HOUR(' . $this->_field_unambig($field, $ftypes) . ')', $_POST['search_value'], false);
							$this->db->or_where('MINUTE(' . $this->_field_unambig($field, $ftypes) . ')', $_POST['search_value'], false);
							$this->db->or_where('SECOND(' . $this->_field_unambig($field, $ftypes) . ')', $_POST['search_value'], false);
						} else {
							$this->db->or_like($this->_field_unambig($field, $ftypes), '%' . $_POST['search_value'] . '%');
							$this->db->or_where($this->_field_unambig($field, $ftypes), $_POST['search_value']);
						}
					} else if ($ftype['type'] == 'datetime') {
						/* Use CONVERT_TZ() SQL function on WHERE clause to correctly setup the user timezone */
						if (is_numeric($_POST['search_value'])) {
							$this->db->or_like('CONVERT_TZ(' . $this->_field_unambig($field, $ftypes) . ', \'' . $this->_default_timezone . '\', \'' . $this->_session_data['timezone'] . '\')', '%' . $_POST['search_value'] . '%', false);
							$this->db->or_where('HOUR(CONVERT_TZ(' . $this->_field_unambig($field, $ftypes) . ', \'' . $this->_default_timezone . '\', \'' . $this->_session_data['timezone'] . '\'))', $_POST['search_value'], false);
							$this->db->or_where('MINUTE(CONVERT_TZ(' . $this->_field_unambig($field, $ftypes) . ', \'' . $this->_default_timezone . '\', \'' . $this->_session_data['timezone'] . '\'))', $_POST['search_value'], false);
							$this->db->or_where('SECOND(CONVERT_TZ(' . $this->_field_unambig($field, $ftypes) . ', \'' . $this->_default_timezone . '\', \'' . $this->_session_data['timezone'] . '\'))', $_POST['search_value'], false);
							$this->db->or_where('YEAR(CONVERT_TZ(' . $this->_field_unambig($field, $ftypes) . ', \'' . $this->_default_timezone . '\', \'' . $this->_session_data['timezone'] . '\'))', $_POST['search_value'], false);
							$this->db->or_where('MONTH(CONVERT_TZ(' . $this->_field_unambig($field, $ftypes) . ', \'' . $this->_default_timezone . '\', \'' . $this->_session_data['timezone'] . '\'))', $_POST['search_value'], false);
							$this->db->or_where('DAY(CONVERT_TZ(' . $this->_field_unambig($field, $ftypes) . ', \'' . $this->_default_timezone . '\', \'' . $this->_session_data['timezone'] . '\'))', $_POST['search_value'], false);
						} else {
							$this->db->or_like('CONVERT_TZ(' . $this->_field_unambig($field, $ftypes) . ', \'' . $this->_default_timezone . '\', \'' . $this->_session_data['timezone'] . '\')', '%' . $_POST['search_value'] . '%', false);
							$this->db->or_where('DATE(CONVERT_TZ('. $this->_field_unambig($field, $ftypes) . ', \'' . $this->_default_timezone . '\', \'' . $this->_session_data['timezone'] . '\'))', $_POST['search_value'], false);
							$this->db->or_where('TIME(CONVERT_TZ('. $this->_field_unambig($field, $ftypes) . ', \'' . $this->_default_timezone . '\', \'' . $this->_session_data['timezone'] . '\'))', $_POST['search_value'], false);
						}
					} else {
						$this->db->or_like($this->_field_unambig($field, $ftypes), '%' . $_POST['search_value'] . '%');
					}
				} else if ($ftype['input_type'] == 'checkbox') {
					/* Override 'true' / 'false' searches to match boolean values on DB */
					if (strtolower($_POST['search_value']) == $this->_word_true) {
						/* Only fields with value 1 are considered True */
						$this->db->or_where($this->_field_unambig($field, $ftypes), 1);
					} else if (strtolower($_POST['search_value']) == $this->_word_false) {
						/* If the value is NULL or 0, it is considered False */
						$this->db->or_is_null($this->_field_unambig($field, $ftypes));
						$this->db->or_where($this->_field_unambig($field, $ftypes), 0);
					}
				} else if ($ftype['input_type'] == 'select') {
					/* Search as many fields as the ones configured through _rel_table_fields_config,
					 * if more than one was set on _rel_table_fields_config[table][2].
					 */
					if (isset($this->_rel_table_fields_config[$ftype['table']]) && 
							($this->_rel_table_fields_config[$ftype['table']][2] != NULL) &&
							(count($this->_rel_table_fields_config[$ftype['table']][2]) > 1)) {
						$table_fields = $this->_get_table_fields($ftype['table']);
							
						foreach ($this->_rel_table_fields_config[$ftype['table']][2] as $rel_field) {
							$this->db->or_like($this->_field_unambig($table_fields[$rel_field], $ftype), '%' . $_POST['search_value'] . '%');
						}
					} else {
						/* Default altname for single relationships is always the first field name of the foreign table */
						$this->db->or_like('`' . $ftype['table'] . '`.`' . $ftype['altname'] . '`', '%' . $_POST['search_value'] . '%');
					}
				} else {
					if (is_numeric($_POST['search_value']))
						$this->db->or_where($this->_field_unambig($field, $ftypes), $_POST['search_value']);
				}
			}

			$data['view']['search_value'] = $_POST['search_value'];
		} else if ($type == 'advanced') {
			/* We're comming from an advanced search */

			/* Convert checkbox input names to field arrays */
			$_POST['fields_result'] = NULL;
			$_POST['fields_criteria'] = NULL;

			foreach ($_POST as $post_field => $post_value) {
				if (substr($post_field, 0, 9) == 'criteria_') {
					/* POST variable matches the criteria_<field name> format */
					if ($_POST['fields_criteria'] == NULL)
						$_POST['fields_criteria'] = array();
					
					array_push($_POST['fields_criteria'], substr($post_field, 9));
					unset($_POST[$post_field]);
				} else if (substr($post_field, 0, 7) == 'result_') {
					/* POST variable matches the result_<field name> format */
					if ($_POST['fields_result'] == NULL)
						$_POST['fields_result'] = array();

					array_push($_POST['fields_result'], substr($post_field, 7));
					unset($_POST[$post_field]);
				}
			}

			/* Grant that Id field is selected on result fields */
			if (!in_array('id', $_POST['fields_result']))
				$this->response->code('403', NDPHP_LANG_MOD_UNSUPPORTED_RESULT_NO_ID, $this->_default_charset, !$this->request->is_ajax());

			/* Grant that at least one search criteria field is selected */
			if (!count($_POST['fields_criteria']))
				$this->response->code('403', NDPHP_LANG_MOD_MISSING_SEARCH_CRITERIA, $this->_default_charset, !$this->request->is_ajax());

			/* TODO:
			 * 
			 * datetime types are increasing the view and controller complexity due to the
			 * current implementation that is based on two separate fields (date and time)
			 * which requires the concatenation in the controller of these two fields before
			 * we can operate with that value on the database.
			 * 
			 * This may be simplified by implementing a javascript date+time picker for
			 * datetime fields.
			 * 
			 */
			if (($_POST['fields_result'] == NULL) || ($_POST['fields_criteria'] == NULL))
				redirect($this->_name . '/search');

			/* Create criteria by processing each field type and respective selected options */
			foreach ($_POST['fields_criteria'] as $field) {
				/* Check if there are missing fields */
				if (!isset($_POST[$field]) || !$_POST[$field])
					$_POST[$field] = NULL; /* In case it is not set, set is as NULL */

				/* Check permissions */
				if (!$this->security->perm_check($this->_security_perms, $this->security->perm_search, $this->_name, $field))
					continue;

				/* Check for NULL comparisions... but don't compare NULL datetime fields that contain a custom interval set (in this case only the custom interval field will be processed) */
				if ((($_POST[$field] === NULL || $_POST[$field] == '') && $ftypes[$field]['type'] != 'datetime' && $ftypes[$field]['type'] != 'date' && $ftypes[$field]['type'] != 'time') ||
							(($_POST[$field] === NULL || $_POST[$field] == '') && $ftypes[$field]['type'] == 'datetime' && !$_POST[$field . '_custom']) ||
							(($_POST[$field] === NULL || $_POST[$field] == '') && $ftypes[$field]['type'] == 'date' && !$_POST[$field . '_custom']) ||
							(($_POST[$field] === NULL || $_POST[$field] == '') && $ftypes[$field]['type'] == 'time' && !$_POST[$field . '_custom']))
				{
					$negate = false;
					
					if (isset($_POST[$field . '_cond']) && ($_POST[$field . '_cond'] == '!=')) {
						$negate = true;
					} else if (isset($_POST[$field . '_diff']) && ($_POST[$field . '_diff'])) {
						$negate = true;
					}

					if ($negate) {
						$this->db->is_not_null($this->_field_unambig($field, $ftypes));
					} else {
						$this->db->is_null($this->_field_unambig($field, $ftypes));
					}

					continue;
				}

				/* Although date, time and datetime fields have the text input type, for
				 * the database operations they are treated as numbers (integers).
				 */
				if ($ftypes[$field]['type'] == 'mixed') {
					/* Search all fields in mixed_* table for matching entries based on criteria */

					/* Fetch mixed table fields */
					$mt_fields = $this->_get_table_fields($field);

					/* Join the mixed table to the query */
					$this->db->join($field, '`' . $this->_name . '`.`id` = `' . $field . '`.`' . $this->_name . '_id`', 'left');

					/* NOTE: This is a hack to enclose the OR clauses togheter, avoiding AND clause interference */
					$this->db->where('1 =', '1', false); /* Grant that WHERE clause is initiated */
					$this->db->where_append(' AND (1=0 ');

					/* Search for mixed table content matches */
					foreach ($mt_fields as $mixed_field) {
						/* Ignore mixed id */
						if ($mixed_field == 'id')
							continue;

						/* Ignore single relationship field referencing to this table */
						if ($mixed_field == ($this->_name . '_id'))
							continue;

						/* Get the mixed foreign table */
						$mixed_foreign_table = array_pop(array_diff($this->_get_mixed_rel_table_names($field, $this->_name), array($this->_name)));

						/* Ignore single relationship field referencing to foreign table */
						if ($mixed_field == ($mixed_foreign_table . '_id'))
							continue;

						/* FIXME: #1: Currently we only support string matching (partial date and datetime values are not yet implemented) */
						/* FIXME: #2: "Different than" option for mixed searches is is bugged (due to the enclosure hack) and shall not be used as it'll return incorrect results */

						/* Check if we've permissions to read the foreign field on the foreign table */
						if (!$this->security->perm_check($this->_security_perms, $this->security->perm_read, $mixed_foreign_table, $mixed_field))
							continue;

						/* Check if we've permissions to search the foreign field on the foreign table */
						if (!$this->security->perm_check($this->_security_perms, $this->security->perm_search, $mixed_foreign_table, $mixed_field))
							continue;

						/* Determine if this is 'pattern', 'exact' or 'different than' match */
						if (isset($_POST[$field . '_exact']) && $_POST[$field . '_exact']) {
							if (isset($_POST[$field . '_diff']) && ($_POST[$field . '_diff'])) {
								$this->db->where($field . '.' . $mixed_field . ' !=', $_POST[$field]);
							} else {
								$this->db->or_where($field . '.' . $mixed_field, $_POST[$field]);
							}
						} else {
							if (isset($_POST[$field . '_diff']) && ($_POST[$field . '_diff'])) {
								$this->db->not_like($field . '.' . $mixed_field, '%' . $_POST[$field] . '%');
							} else {
								$this->db->or_like($field . '.' . $mixed_field, '%' . $_POST[$field] . '%');
							}
						}
					}

					/* NOTE: This will close the OR clause enclosure... */
					$this->db->where_append(' ) ');
				} else if ((($ftypes[$field]['input_type'] == 'text') ||
							($ftypes[$field]['input_type'] == 'textarea') ||
							($ftypes[$field]['input_type'] == 'file')) &&
							($ftypes[$field]['type'] != 'date') &&
							($ftypes[$field]['type'] != 'time') &&
							($ftypes[$field]['type'] != 'timer') &&
							($ftypes[$field]['type'] != 'datetime')) {
					if (isset($_POST[$field . '_exact']) && $_POST[$field . '_exact']) {
						/* Exact match */
						if (isset($_POST[$field . '_diff']) && ($_POST[$field . '_diff'])) {
							/* Different than */
							$this->db->where($this->_field_unambig($field, $ftypes) . ' !=', $_POST[$field]);
						} else {
							/* Equal to */
							$this->db->where($this->_field_unambig($field, $ftypes), $_POST[$field]);
						}
					} else {
						/* Pattern matching */
						if (isset($_POST[$field . '_diff']) && ($_POST[$field . '_diff'])) {
							/* Not like */
							$this->db->not_like($this->_field_unambig($field, $ftypes), '%' . $_POST[$field] . '%');
						} else {
							/* Like */
							$this->db->like($this->_field_unambig($field, $ftypes), '%' . $_POST[$field] . '%');
						}
					}
				} else if (($ftypes[$field]['input_type'] == 'number') ||
							($ftypes[$field]['type'] == 'date') ||
							($ftypes[$field]['type'] == 'time') ||
							($ftypes[$field]['type'] == 'timer') ||
							($ftypes[$field]['type'] == 'datetime')) {
					/* Datetime fields required special processing to concatenate $field _time
					 * string to the field value.
					 */
					$where_clause_enforce = true;

					if ($ftypes[$field]['type'] == 'time') {
						if (isset($_POST[$field . '_custom']) && $_POST[$field . '_custom']) {
							/* Compute the SQL interval string parameters based on the supplied interval value */
							$interval_fields = $this->_get_interval_fields($_POST[$field . '_custom']);

							/* Check if the supplied interval value is valid */
							if ($interval_fields === false)
								$this->response->code('500', NDPHP_LANG_MOD_INVALID_SEARCH_INTERVAL_FMT . ' { + | - } <digit> { SECOND | MINUTE | HOUR | DAY | WEEK | MONTH | YEAR }. Example: - 10 DAY', $this->_default_charset, !$this->request->is_ajax());

							/* Craft the custom where clause value */
							$_POST[$field] = 'NOW() ' . $interval_fields[0] . ' INTERVAL ' . $interval_fields[1] . ' ' . $interval_fields[2];

							/* Do not enforce value component of the where clause... we need it raw */
							$where_clause_enforce = 'name_only';
						}

						unset($_POST[$field . '_custom']);
					} else if ($ftypes[$field]['type'] == 'date') {
						if (isset($_POST[$field . '_custom']) && $_POST[$field . '_custom']) {
							/* Compute the SQL interval string parameters based on the supplied interval value */
							$interval_fields = $this->_get_interval_fields($_POST[$field . '_custom']);

							/* Check if the supplied interval value is valid */
							if ($interval_fields === false)
								$this->response->code('500', NDPHP_LANG_MOD_INVALID_SEARCH_INTERVAL_FMT . ' { + | - } <digit> { SECOND | MINUTE | HOUR | DAY | WEEK | MONTH | YEAR }. Example: - 10 DAY', $this->_default_charset, !$this->request->is_ajax());

							/* Craft the custom where clause value */
							$_POST[$field] = 'NOW() ' . $interval_fields[0] . ' INTERVAL ' . $interval_fields[1] . ' ' . $interval_fields[2];

							/* Do not enforce value component of the where clause... we need it raw */
							$where_clause_enforce = 'name_only';
						}

						unset($_POST[$field . '_custom']);
					} else if ($ftypes[$field]['type'] == 'datetime') {
						/* Check if custom interval was set */
						if (isset($_POST[$field . '_custom']) && $_POST[$field . '_custom']) {
							/* Compute the SQL interval string parameters based on the supplied interval value */
							$interval_fields = $this->_get_interval_fields($_POST[$field . '_custom']);

							/* Check if the supplied interval value is valid */
							if ($interval_fields === false)
								$this->response->code('500', NDPHP_LANG_MOD_INVALID_SEARCH_INTERVAL_FMT . ' { + | - } <digit> { SECOND | MINUTE | HOUR | DAY | WEEK | MONTH | YEAR }. Example: - 10 DAY', $this->_default_charset, !$this->request->is_ajax());

							/* Craft the custom where clause value */
							$_POST[$field] = 'NOW() ' . $interval_fields[0] . ' INTERVAL ' . $interval_fields[1] . ' ' . $interval_fields[2];

							/* Do not enforce value component of the where clause... we need it raw */
							$where_clause_enforce = 'name_only';
						} else {
							/* If the time field is empty, assume 00:00:00 */
							if (!$_POST[$field . '_time'])
								$_POST[$field . '_time'] = '00:00:00';

							/* Otherwise just merge date and time fields */
							$_POST[$field] = $this->timezone->convert($_POST[$field] . ' ' . $_POST[$field . '_time'], $this->_session_data['timezone'], $this->_default_timezone);
						}

						unset($_POST[$field . '_time']);
						unset($_POST[$field . '_custom']);
					}

					/* Numbers, Times and Dates are processed with the same comparators */
					if (($_POST[$field . '_cond'] != '><') && ($_POST[$field . '_cond'] != '=')) {
						/* Lesser, Greater, Different */
						$this->db->where($this->_field_unambig($field, $ftypes) . ' ' . $_POST[$field . '_cond'], $_POST[$field], $where_clause_enforce);
					} else if ($_POST[$field . '_cond'] == '><') {
						/* Between */
						/* FIXME: TODO: Use between() function here */
						$this->db->where($this->_field_unambig($field, $ftypes) . ' >=', $_POST[$field], $where_clause_enforce);
						
						/* Field _to requires special processing */
						if ($ftypes[$field]['type'] == 'time') {
							if (isset($_POST[$field . '_to_custom']) && $_POST[$field . '_to_custom']) {
								/* Compute the SQL interval string parameters based on the supplied interval value */
								$interval_fields = $this->_get_interval_fields($_POST[$field . '_to_custom']);

								/* Check if the supplied interval value is valid */
								if ($interval_fields === false)
									$this->response->code('500', NDPHP_LANG_MOD_INVALID_SEARCH_INTERVAL_FMT . ' { + | - } <digit> { SECOND | MINUTE | HOUR | DAY | WEEK | MONTH | YEAR }. Example: - 10 DAY', $this->_default_charset, !$this->request->is_ajax());

								/* Craft the custom where clause value */
								$_POST[$field] = 'NOW() ' . $interval_fields[0] . ' INTERVAL ' . $interval_fields[1] . ' ' . $interval_fields[2];

								/* Do not enforce value component of the where clause... we need it raw */
								$where_clause_enforce = 'name_only';
							}

							unset($_POST[$field . '_to_custom']);
						} else if ($ftypes[$field]['type'] == 'date') {
							if (isset($_POST[$field . '_to_custom']) && $_POST[$field . '_to_custom']) {
								/* Compute the SQL interval string parameters based on the supplied interval value */
								$interval_fields = $this->_get_interval_fields($_POST[$field . '_to_custom']);

								/* Check if the supplied interval value is valid */
								if ($interval_fields === false)
									$this->response->code('500', NDPHP_LANG_MOD_INVALID_SEARCH_INTERVAL_FMT . ' { + | - } <digit> { SECOND | MINUTE | HOUR | DAY | WEEK | MONTH | YEAR }. Example: - 10 DAY', $this->_default_charset, !$this->request->is_ajax());

								/* Craft the custom where clause value */
								$_POST[$field] = 'NOW() ' . $interval_fields[0] . ' INTERVAL ' . $interval_fields[1] . ' ' . $interval_fields[2];

								/* Do not enforce value component of the where clause... we need it raw */
								$where_clause_enforce = 'name_only';
							}

							unset($_POST[$field . '_to_custom']);
						} else if ($ftypes[$field]['type'] == 'datetime') {
							/* Check if custom interval was set */
							if (isset($_POST[$field . '_to_custom']) && $_POST[$field . '_to_custom']) {
								/* Compute the SQL interval string parameters based on the supplied interval value */
								$interval_fields = $this->_get_interval_fields($_POST[$field . '_to_custom']);

								/* Check if the supplied interval value is valid */
								if ($interval_fields === false)
									$this->response->code('500', NDPHP_LANG_MOD_INVALID_SEARCH_INTERVAL_FMT . ' { + | - } <digit> { SECOND | MINUTE | HOUR | DAY | WEEK | MONTH | YEAR }. Example: - 10 DAY', $this->_default_charset, !$this->request->is_ajax());

								/* Craft the custom where clause value */
								$_POST[$field . '_to'] = 'NOW() ' . $interval_fields[0] . ' INTERVAL ' . $interval_fields[1] . ' ' . $interval_fields[2];

								/* Do not enforce value component of the where clause... we need it raw */
								$where_clause_enforce = 'name_only';
							} else {
								/* If the time field is empty, assume 00:00:00 */
								if (!$_POST[$field . '_to_time'])
									$_POST[$field . '_to_time'] = '00:00:00';

								$_POST[$field . '_to'] = $this->timezone->convert($_POST[$field . '_to'] . ' ' . $_POST[$field . '_to_time'], $this->_session_data['timezone'], $this->_default_timezone);
							}

							unset($_POST[$field . '_to_time']);
							unset($_POST[$field . '_to_custom']);
						}

						$this->db->where($this->_field_unambig($field, $ftypes) . ' <=', $_POST[$field . '_to'], $where_clause_enforce);
					} else {
						/* The condition is '=' (equal) */
						$this->db->where($this->_field_unambig($field, $ftypes), $_POST[$field], $where_clause_enforce);
					}
				} else if (($ftypes[$field]['input_type'] == 'select') && ($ftypes[$field]['type'] != 'rel')) {
					$this->db->where_in($this->_name . '.' . $field, $_POST[$field]);
				} else if (($ftypes[$field]['input_type'] == 'select') && ($ftypes[$field]['type'] == 'rel')) {
					$this->db->where_in($ftypes[$field]['table'] . '.id', $_POST[$field]);
				} else {
					/* FIXME: TODO: Choose between like() or where(), not both. */
					/* FIXME: TODO: What exactly falls here ? */
					$this->db->like($this->_field_unambig($field, $ftypes), $_POST[$field]);
					$this->db->where($this->_field_unambig($field, $ftypes), $_POST[$field]);
				}
			}
		}

		/* NOTE: If none of the above types matched, we've a default 'query' type here */

		/* Switch order on each call */
		if ($order_type == "asc")
			$data['config']['order'] = "desc";
		else
			$data['config']['order'] = "asc";

		/* FIXME:
		 * By default, field 'id' is set as the ordering field. This will cause an error
		 * on advanced searches if the Result array doesn't contain the 'id' field selected.
		 */
		$data['config']['order_by'] = $order_field;

		/* Reset total rows count */
		$total_rows = 0;

		/* Check whether this is a recall to result controller from the result view or not */
		if ($result_query) {
			/* This is a recall */
			$data['view']['result_query'] = $result_query;
			
			/* Decode last result query */
			$result_query = gzuncompress($this->encrypt->decode($this->ndphp->safe_b64decode(rawurldecode($result_query))));
			
			/* Reorder */
			$result_query = $result_query . ' ORDER BY `' . str_replace('`', '', $order_field) . '` ' . (strtoupper($order_type) == 'DESC' ? 'DESC' : 'ASC');

			/* Set export query before limiting results through LIMIT clause */
			/* 
		 	 * Compress, encrypt and encode (base64) the last query to be passed to export
		 	 * controller.
		 	 * Also encode the result with rawurlencode()
		 	 * 
		 	 * The export query value is passed to the results view so it can be used by
		 	 * other requests performed in the view, such as exports (to PDF, CSV, etc).
		 	 *
		 	 * FIXME: TODO: export query should be stored in user session and shall no be passed via URL
		 	 *
		 	 */
			$data['view']['export_query'] = rawurlencode($this->ndphp->safe_b64encode($this->encrypt->encode(gzcompress($result_query, 9))));

			/* Pagination */
			if ($page >= 0)
				$result_query = $result_query . ' LIMIT ' . intval($page) . ', ' . $this->_table_pagination_rpp_result;
			
			/* Force MySQL to count the total number of rows despite the LIMIT clause */
			$result_query = 'SELECT SQL_CALC_FOUND_ROWS ' . substr($result_query, 7);
			
			$data['view']['result_array'] = $this->_field_value_mangle($ftypes, $this->db->query($result_query));
			
			/* Get total rows count */
			$total_rows_query = $this->db->query('SELECT FOUND_ROWS() AS nr_rows');
			$total_rows = $total_rows_query->row_array()['nr_rows'];
		} else {
			/* This is not a recall */

			/* Filter table rows, if applicable */
			$this->_table_row_filter_apply();

			/* Resolve and select the fields to be displayed in the result */		
			$this->_field_resolve($ftypes, $_POST['fields_result']);
			$this->db->from($this->_name); // from() method is needed here for get_compiled_select_str() call

			/* Apply result filter hook */
			$hook_enter_return = $this->_hook_result_generic_filter($data, $type, $result_query, $order_field, $order_type, $page, $hook_enter_return);

			$data['view']['result_query'] = rawurlencode($this->ndphp->safe_b64encode($this->encrypt->encode(gzcompress($this->db->get_compiled_select_str(NULL, true, false), 9))));

			/* Set the ordering */
			$this->db->order_by($order_field, $order_type);

			/* Set export query before limiting results through LIMIT clause */
			/* 
		 	 * Compress, encrypt and encode (base64) the last query to be passed to export
		 	 * controller.
		 	 * Also encode the result with rawurlencode()
		 	 * 
		 	 * The export query value is passed to the results view so it can be used by
		 	 * other requests performed in the view, such as exports (to PDF, CSV, etc).
		 	 * 
		 	 * FIXME: TODO: export query should be stored in user session and shall no be passed via URL
		 	 *
		 	 */
			$data['view']['export_query'] = rawurlencode($this->ndphp->safe_b64encode($this->encrypt->encode(gzcompress($this->db->get_compiled_select_str(NULL, true, false), 9))));


			/* If this is a REST call, do not limit the results (as in, display all) */
			if ($this->_json_replies !== true && $page >= 0) {
				/* Limit results to the number of rows per page (pagination) */
				$this->db->limit($this->_table_pagination_rpp_result, $page);
			}
			
			/* Force MySQL to count the total number of rows despite the LIMIT clause */
			$result_query = $this->db->get_compiled_select_str(NULL, true, false);
			$result_query = 'SELECT SQL_CALC_FOUND_ROWS ' . substr($result_query, 7);
			
			$data['view']['result_array'] = $this->_field_value_mangle($ftypes, $this->db->query($result_query));
			
			/* Get total rows count */
			$total_rows_query = $this->db->query('SELECT FOUND_ROWS() AS `nr_rows`');
			$total_rows = $total_rows_query->row_array()['nr_rows'];
		}

		$data['view']['fields'] = $ftypes_result;

		/* Pagination */
		if ($page >= 0) {
			$pagcfg['page'] = ($page / $this->_table_pagination_rpp_result) + 1;
			$pagcfg['base_url'] = base_url() . 'index.php/' . $this->_name . '/result/query/' . $data['view']['result_query'] . '/' . $order_field . '/' . $order_type . '/@ROW_NR@';
			$pagcfg['onclick'] = 'ndphp.ajax.load_data_ordered_result(event, \'' . $this->_name . '\', \'' . $data['view']['result_query'] . '\', \'' . $order_field . '\', \'' . $order_type . '\', \'@ROW_NR@\');';
			$pagcfg['total_rows'] = $total_rows; 
			$pagcfg['per_page'] = $this->_table_pagination_rpp_result;
			
			$this->pagination->initialize($pagcfg);
			$data['view']['links']['pagination'] = $this->pagination->create_links();
			$data['view']['page'] = $page;
		}

		/* Hidden fields */
		$data['config']['hidden_fields'] = $this->_hide_fields_result;

		/* Setup breadcrumb */
		$data['view']['links']['breadcrumb'] = $this->_get_breadcrumb('search', NDPHP_LANG_MOD_OP_SEARCH, array('result', 'query', $data['view']['result_query']), NDPHP_LANG_MOD_OP_RESULT);

		/* Hook handler (leave) */
		$this->_hook_result_generic_leave($data, $type, $result_query, $order_field, $order_type, $page, $hook_enter_return);

		/* Load leave plugins */
		foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/result_generic_leave.php') as $plugin)
			include($plugin);

		/* Setup pagination, if required */
		if ($page >= 0) {
			$total_items_from = ($pagcfg['per_page'] * ($page / $pagcfg['per_page']));
			$total_items_from += $pagcfg['total_rows'] ? 1 : 0;
			$total_items_to = (($pagcfg['per_page'] * ($page / $pagcfg['per_page'])) + $pagcfg['per_page']);
			$total_items_to = ($total_items_to <= $pagcfg['total_rows'] ? $total_items_to : $pagcfg['total_rows']);
			$data['view']['total_items_from'] = $total_items_from;
			$data['view']['total_items_to'] = $total_items_to;
			$data['view']['total_items'] = $pagcfg['total_rows'];
		}

		/* All good */
		return $data;
	}

	public function result($type = 'advanced', $result_query = NULL, $order_field = NULL, $order_type = NULL, $page = 0,
		$body_only = false, $body_header = true, $body_footer = true, $modalbox = false)
	{
		$data = $this->result_generic($type, $result_query, $order_field, $order_type, $page);

		if ($modalbox)
			$data['config']['modalbox'] = true;

		/* If this is an AJAX call, then we should only render the page body */
		if ($this->request->is_ajax())
			$body_only = true;

		/* If this is an JSON API request, just reply the data (do not load the views) */
		if ($this->_json_replies === true) {
			echo($this->json_result($data));
			return;
		}

		/* Load Views */
		$this->_load_method_views(__FUNCTION__, $data, $body_only, $body_header, $body_footer);
	}

	public function result_body_ajax($type = 'advanced', $result_query = NULL, $order_field = NULL, $order_type = NULL, $page = 0) {
		$this->result($type, $result_query, $order_field, $order_type, $page, true);
	}

	public function result_data_ajax($type = 'advanced', $result_query = NULL, $order_field = NULL, $order_type = NULL, $page = 0) {
		$this->result($type, $result_query, $order_field, $order_type, $page, true, false);
	}

	public function result_data_modalbox($type = 'advanced', $result_query = NULL, $order_field = NULL, $order_type = NULL, $page = 0) {
		$this->result($type, $result_query, $order_field, $order_type, $page, true, false, true, true);
	}

	protected function result_group_generic($grouping_field, $type = 'advanced', $result_query = NULL,
		$order_field = NULL, $order_type = NULL, $page = 0)
	{
		/* TODO: FIXME:
		 *
		 * There's a significant performance impact on this approach and no pagination support is currently
		 * implemented for grouping. Redesign of this feature is required.
		 *
		 */

		/* If logging is enabled, log this search request */
		if ($this->_logging === true) {
			$log_transaction_id = openssl_digest('RESULT' . $this->_name . $this->_session_data['sessions_id'] . date('Y-m-d H:i:s') . mt_rand(1000000, 9999999), 'sha1');

			$this->db->insert('logging', array(
				'operation' => 'RESULT',
				'_table' => $this->_name,
				'_field' => 'GROUPS',
				'entryid' => $grouping_field,
				'transaction' => $log_transaction_id,
				'registered' => date('Y-m-d H:i:s'),
				'sessions_id' => $this->_session_data['sessions_id'],
				'users_id' => $this->_session_data['user_id']
			));
		}

		$data = $this->result_generic($type, $result_query, $order_field, $order_type, -1 /* $page */); /* FIXME: We should not rely on result_generic(). A specific implementation for grouping is required. */

		$group_result_array = array();
		$group_hash = array();

		$result_array_iter = $data['view']['result_array'];

		/* Create a set of grouped results (grouped by the existing entries on the foreign table identified by grouping_field relationship) */
		foreach ($data['view']['fields'][$grouping_field]['options'] as $opt_id => $opt_val) {
			$result_array_remain = array(); /* Will be used to store the non-matching entries (remaining entries) */

			/* Iterate the (remaining) results... */
			foreach ($result_array_iter as $row) {
				if (substr($grouping_field, -3) == '_id') {
					if ($row[$grouping_field] == $opt_val) {
						/* This row belongs to this group... */
						$group_result_array[$opt_val][] = $row;
						$group_hash[$opt_val] = openssl_digest($opt_val, 'sha1');
					} else {
						/* If it does not belong to this group, add it to the remaining entries array */
						$result_array_remain[] = $row;
					}
				} else if (substr($grouping_field, 0, 4) == 'rel_') {
					if (in_array($opt_val, explode($this->_rel_group_concat_sep, $row[$grouping_field]))) {
						/* This row belongs to this group... */
						$group_result_array[$opt_val][] = $row;
						$group_hash[$opt_val] = openssl_digest($opt_val, 'sha1');
					}

					/* This row can belong to multiple groups, so the remaining will always be the full list */
					$result_array_remain[] = $row;
				}
			}

			/* Update iterator with the remaining data */
			$result_array_iter = $result_array_remain;
		}

		/* Update view data */
		$data['view']['grouping_field'] = $grouping_field;
		$data['view']['grouping_result_array'] = $group_result_array;
		$data['view']['grouping_hashes'] = $group_hash;

		/* All good */
		return $data;
	}

	public function result_group($grouping_field, $type = 'advanced', $result_query = NULL, $order_field = NULL, $order_type = NULL, $page = 0,
		$body_only = false, $body_header = true, $body_footer = true, $modalbox = false)
	{
		$data = $this->result_group_generic($grouping_field, $type, $result_query, $order_field, $order_type, $page);

		if ($modalbox)
			$data['config']['modalbox'] = true;

		/* If this is an AJAX call, then we should only render the page body */
		if ($this->request->is_ajax())
			$body_only = true;

		/* If this is an JSON API request, just reply the data (do not load the views) */
		if ($this->_json_replies === true) {
			echo($this->json_result($data));
			return;
		}

		/* Load Views */
		$this->_load_method_views(__FUNCTION__, $data, $body_only, $body_header, $body_footer);
	}

	public function result_group_body_ajax($grouping_field, $type = 'advanced', $result_query = NULL, $order_field = NULL, $order_type = NULL, $page = 0) {
		$this->result_group($grouping_field, $type, $result_query, $order_field, $order_type, $page, true);
	}

	public function result_group_data_ajax($grouping_field, $type = 'advanced', $result_query = NULL, $order_field = NULL, $order_type = NULL, $page = 0) {
		$this->result_group($grouping_field, $type, $result_query, $order_field, $order_type, $page, true, false);
	}

	public function result_group_data_modalbox($grouping_field, $type = 'advanced', $result_query = NULL, $order_field = NULL, $order_type = NULL, $page = 0) {
		$this->result_group($grouping_field, $type, $result_query, $order_field, $order_type, $page, true, false, true, true);
	}

	public function export($export_query = NULL, $type = 'pdf') {
		/* Security Permissions Check */
		if (!$this->security->perm_check($this->_security_perms, $this->security->perm_read, $this->_name))
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->_default_charset, !$this->request->is_ajax());

		/* Initialize $data */
		$data = array();

		/* Load enter plugins */
		foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/export_enter.php') as $plugin)
			include($plugin);

		/* Hook handler (enter) */
		$hook_enter_return = $this->_hook_export_enter($data, $export_query, $type);

		/* If logging is enabled, check for changed fields and log them */
		if ($this->_logging === true) {
			$log_transaction_id = openssl_digest('EXPORT' . $this->_name . $this->_session_data['sessions_id'] . date('Y-m-d H:i:s') . mt_rand(1000000, 9999999), 'sha1');

			$this->db->insert('logging', array(
				'operation' => 'EXPORT',
				'_table' => $this->_name,
				'_field' => strtoupper($type),
				'transaction' => $log_transaction_id,
				'registered' => date('Y-m-d H:i:s'),
				'sessions_id' => $this->_session_data['sessions_id'],
				'users_id' => $this->_session_data['user_id']
			));
		}

		/* TODO: $export_query shall be passed in a POST method in the future as it can easily
		 * reach more than 2083 characters, which is the IE limit for URL size.
		 * 
		 *  -- Pedro A. Hortas (pah@ucodev.org)
		 */

		/* Get view title */
		$title = NULL;

		if (isset($this->_menu_entries_aliases[$this->_name])) {
			$title = $this->_menu_entries_aliases[$this->_name] . $this->_view_title_sep . NDPHP_LANG_MOD_OP_EXPORT;
		} else {
			$title = $this->_viewhname . $this->_view_title_sep . NDPHP_LANG_MOD_OP_EXPORT;
		}

		/* Get view description value */
		$description = NDPHP_LANG_MOD_OP_EXPORT . " " . $this->_viewhname;

		/* Setup basic view data */
		$data = array_merge($data, $this->_get_view_data_generic($title, $description));

		/* Setup specific view data */
		$data['view']['fields'] = $this->_get_fields(NULL, $this->_hide_fields_export); /* _get_fields() uses a perm_read filter by default */
		
		if ($export_query) {
			$data['view']['result_array'] = $this->_field_value_mangle($data['view']['fields'], $this->db->query(gzuncompress($this->encrypt->decode($this->ndphp->safe_b64decode(rawurldecode($export_query))))));
		} else {
			$this->db->from($this->_name);

			/* Select only the fields that were returned by _get_fields() */
			$this->_filter_selected_fields($data['view']['fields']);
			$data['view']['result_array'] = $this->_field_value_mangle($data['view']['fields'], $this->db->get());
		}

		/* Hidden fields */
		$data['config']['hidden_fields'] = $this->_hide_fields_export;

		/* Setup choices class */
		$data['config']['choices_class'] = $this->_rel_choice_table_row_class;

		/* Load leave plugins */
		foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/export_leave.php') as $plugin)
			include($plugin);

		/* Hook handler (leave) */
		$this->_hook_export_leave($data, $export_query, $type, $hook_enter_return);

		/* Export format is based on type */
		if ($type == 'pdf') {
			/* Load view data */
			$view_data = $this->_load_view('export', $data, true, true);

			/* Create PDF */		
			$this->mpdf->WriteHTML($view_data);
			/*	mPDF Output() options:
			 * 
			 * 	I: send the file inline to the browser. The plug-in is used if available. The name given by filename is used when one selects the "Save as" option on the link generating the PDF.
			 *	D: send to the browser and force a file download with the name given by filename.
			 *	F: save to a local file with the name given by filename (may include a path).
			 *	S: return the document as a string. filename is ignored.
			 */
			$this->mpdf->Output($this->_name . '.pdf', 'D');
		} else if ($type == 'csv') {
			/* Create a unique filename */
			$csv_filename = tempnam($this->_temp_dir, $this->_name . '_');

			/* FIXME: Check if temporary file already exists and generate a new one if so. */

			/* Open CSV file */
			$csv_fp = fopen($csv_filename, 'a+');
			
			/* Write field names on csv header */
			$row = array_values($data['view']['result_array'])[0];
			foreach ($row as $field => $value):
				if (in_array($field, $this->_hide_fields_export))
					continue;

				fwrite($csv_fp, $this->_csv_delim . ucfirst(mb_convert_encoding($data['view']['fields'][$field]['viewname'], $this->_csv_to_encoding, $this->_csv_from_encoding)) . $this->_csv_delim . $this->_csv_sep);
			endforeach;
			
			/* Add new line for csv body */
			fwrite($csv_fp, "\n");

			/* Write field values */
			foreach ($data['view']['result_array'] as $row):
				foreach ($row as $field => $value):
					if (in_array($field, $this->_hide_fields_export))
						continue;

					if ($data['view']['fields'][$field]['input_type'] == 'checkbox') {
						fwrite($csv_fp, $this->_csv_delim . ($value == 1 ? (NDPHP_LANG_MOD_STATUS_CHECKBOX_CHECKED . $this->_csv_sep) : (NDPHP_LANG_MOD_STATUS_CHECKBOX_UNCHECKED) . $this->_csv_delim . $this->_csv_sep));
					} else {
						fwrite($csv_fp, $this->_csv_delim . mb_convert_encoding($value, $this->_csv_to_encoding, $this->_csv_from_encoding) . $this->_csv_delim . $this->_csv_sep);
					}
				endforeach;
				
				/* Add new line for next element */
				fwrite($csv_fp, "\n");
			endforeach;
			
			/* Close CSV file */
			fclose($csv_fp);

			/* Force CSV download */
			$download_csv = end(explode('/', $csv_filename));

			$this->response->header('Content-Encoding', $this->_csv_to_encoding);
			$this->response->header('Content-Type', 'text/csv; charset=' . $this->_csv_to_encoding);
			$this->response->header('Content-Disposition', 'attachment; filename=' . $download_csv . '.csv');

			readfile($csv_filename);
			
			unlink($csv_filename);
		}
	}

	protected function create_generic($autocomplete = NULL) {
		/* Check if this is a view table type */
		if ($this->_table_type_view)
			$this->response->code('403', NDPHP_LANG_MOD_CANNOT_OP_VIEW_TYPE_CTRL . ' CREATE.', $this->_default_charset, !$this->request->is_ajax());

		/* Security Permissions Check */
		if (!$this->security->perm_check($this->_security_perms, $this->security->perm_create, $this->_name))
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->_default_charset, !$this->request->is_ajax());

		/* Initialize $data */
		$data = array();

		/* Load enter plugins */
		foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/create_generic_enter.php') as $plugin)
			include($plugin);

		/* Hook handler (enter) */
		$hook_enter_return = $this->_hook_create_generic_enter($data);

		/* Get view title */
		$title = NULL;

		if (isset($this->_menu_entries_aliases[$this->_name])) {
			$title = $this->_menu_entries_aliases[$this->_name] . $this->_view_title_sep . NDPHP_LANG_MOD_OP_CREATE;
		} else {
			$title = $this->_viewhname . $this->_view_title_sep . NDPHP_LANG_MOD_OP_CREATE;
		}

		/* Get view description value */
		$description = NDPHP_LANG_MOD_OP_CREATE . " " . $this->_viewhname;

		/* Setup basic view data */
		$data = array_merge($data, $this->_get_view_data_generic($title, $description));

		/* Setup specific view data */
		$data['config']['choices'] = count($this->_rel_choice_hide_fields_create) ? $this->_rel_choice_hide_fields_create : $this->_rel_choice_hide_fields;
		$data['config']['mixed'] = array();
		$data['config']['mixed']['autocomplete'] = $this->_mixed_views_autocomplete;

		$data['view']['fields'] = $this->_filter_fields($this->_security_perms, $this->security->perm_create, $this->_get_fields(NULL, $this->_hide_fields_create)); /* Filter fields (The perm_read permission is already being validated on $this->_get_fields() */
		$data['view']['links']['submenu'] = $this->_links_submenu_body_create;
		$data['view']['links']['breadcrumb'] = $this->_get_breadcrumb('create', NDPHP_LANG_MOD_OP_CREATE);

		/* Check if there is any autocomplete data set in a POST */
		if (isset($_POST['autocomplete'])) {
			/* If so... the POST data always have precedence over the URL data */
			$data['view']['autocomplete'] = json_decode($_POST['autocomplete'], true);
		} else if ($autocomplete !== NULL) {
			/* URL parameters always come encoded in safe base64 format */
			$data['view']['autocomplete'] = json_decode($this->ndphp->safe_b64decode($autocomplete), true);
		}

		/* Required fields are extracted from information schema
		 *
		 * NOTE: This requires DBMS to operate in 'strict' mode.
		 * Make sure that 'strict' config parameter is set to true in user/config/database.php
		 *  
		 */
		$schema = $this->load->database($this->_default_database . '_schema', true);

		$schema->select('COLUMN_NAME');
		$schema->from('COLUMNS');
		$schema->where('TABLE_SCHEMA', $this->db->database);
		$schema->where('TABLE_NAME', $this->_name);
		$schema->where('IS_NULLABLE', 'NO');
		$query_required = $schema->get();

		$schema->select('COLUMN_NAME,COLUMN_DEFAULT');
		$schema->from('COLUMNS');
		$schema->where('TABLE_SCHEMA', $this->db->database);
		$schema->where('TABLE_NAME', $this->_name);
		$query_default = $schema->get();

		$schema->close();

		/* Select the default database */
		$this->load->database($this->_default_database);

		/* Set required fields array */
		$data['view']['required'] = array();
		foreach ($query_required->result_array() as $row) {
			array_push($data['view']['required'], $row['COLUMN_NAME']);
		}

		/* Set default values array */
		$data['view']['default'] = array();
		foreach ($query_default->result_array() as $row) {
			$data['view']['default'][$row['COLUMN_NAME']] = $row['COLUMN_DEFAULT'];
		}

		/* Hidden fields */
		$data['config']['hidden_fields'] = $this->_hide_fields_create;

		/* Load leave plugins */
		foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/create_generic_leave.php') as $plugin)
			include($plugin);

		/* Hook handler (leave) */
		$this->_hook_create_generic_leave($data, $hook_enter_return);

		/* All good */
		return $data;
	}
	
	public function create_mixed_rel($mid, $foreign_table = '', $field_name = '', $field_data = '') {
		/* This data was encoded with Javascript */
		$field_data = $this->ndphp->safe_b64decode(rawurldecode($field_data));

		$data = $this->create_generic();

		$data['config']['hidden_fields'] = $this->_mixed_hide_fields_create;
		$data['config']['mixed']['table_field_width'] = $this->_mixed_table_fields_width;

		$data['view']['mixed_id'] = $mid;
		$data['view']['field_data'] = $field_data;
		$data['view']['values'] = array();

		if ($field_data != '') {
			$this->load->database($this->_default_database);
			$this->db->where($field_name, $field_data);

			if (!$this->_table_row_filter_perm($field_data, $this->_name, $field_name))
				$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->_default_charset, !$this->request->is_ajax());

			$this->db->from($this->_name);

			/* We need to mangle the relationship data. We also use _get_fields_basic_types() to avoid the
			 * overhead of calling _get_fields()
			 */
			$result_array = $this->_field_value_mangle($this->_get_fields_basic_types($this->_name), $this->db->get());
			
			/* NOTE: $field_name must have a UNIQUE constraint in order to trigger the following validation */
			if (count($result_array) == 1) {
				$row = array_values($result_array)[0];
				$data['view']['values'] = $row;
			}
		}
		
		/* Remove the fields that are not present in the mixed relational table */
		$data['view']['present_fields'] = $this->_get_mixed_table_fields($this->_name, $foreign_table);
		
		/* Load mixed view */
		$this->_load_view('create_mixed', $data, true);
	}

	public function create($autocomplete = NULL, $body_only = false, $body_header = true, $body_footer = true, $modalbox = false) {
		$data = $this->create_generic($autocomplete);

		if ($modalbox)
			$data['config']['modalbox'] = true;

		/* If this is an AJAX call, then we should only render the page body */
		if ($this->request->is_ajax())
			$body_only = true;

		/* Load Views */
		$this->_load_method_views(__FUNCTION__, $data, $body_only, $body_header, $body_footer);
	}

	public function create_body_ajax($autocomplete = NULL) {
		$this->create($autocomplete, true);
	}

	public function create_data_ajax($autocomplete = NULL) {
		$this->create($autocomplete, true, false);
	}

	public function create_data_modalbox($autocomplete = NULL) {
		$this->create($autocomplete, true, false, true, true);
	}

	public function insert($retid = false) {
		/* NOTE: If $retid is true, an integer value is returned on success (on failure, die() will always be called) */

		/* Grant that $_POST keys are safe */
		if (!$this->security->safe_keys($_POST, $this->_security_safe_chars))
			$this->response->code('403', NDPHP_LANG_MOD_INVALID_POST_KEYS, $this->_default_charset, !$this->request->is_ajax());

		/* Check if this is a view table type */
		if ($this->_table_type_view)
			$this->response->code('403', NDPHP_LANG_MOD_CANNOT_OP_VIEW_TYPE_CTRL . ' INSERT.', $this->_default_charset, !$this->request->is_ajax());

		/* Security Permissions Check */
		if (!$this->security->perm_check($this->_security_perms, $this->security->perm_create, $this->_name))
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED . ' #1', $this->_default_charset, !$this->request->is_ajax());

		$ftypes = $this->_get_fields();

		/* Assume no relationships by default */
		$rel = NULL;
		$mixed_rels = array();

		$file_uploads = array();

		/* Load pre plugins */
		foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/insert_pre.php') as $plugin)
			include($plugin);

		/* Pre-Insert hook */
		$hook_pre_return = $this->_hook_insert_pre($_POST, $ftypes);

		/* Pre-process file uploads */
		foreach ($_FILES as $k => $v) {
			if (!$_FILES[$k]['name'])
				continue;

			/* Filter filename */
			$_FILES[$k]['name'] = preg_replace('/[^' . $this->_upload_file_name_filter . ']+/', '_', $_FILES[$k]['name']);

			switch ($_FILES[$k]['error']) {
				case UPLOAD_ERR_NO_FILE:
				case UPLOAD_ERR_PARTIAL: continue;
			}

			array_push($file_uploads, $k);

			/* Set the POST variable value */
			$_POST[$k] = $_FILES[$k]['name'];
		}

		/* Pre-process $_POST array */
		foreach ($_POST as $field => $value) {
			/* Extract mixed relationships, if any */
			if (substr($field, 0, 6) == 'mixed_') {
				$mixed_field = $this->_mixed_process_post_field($field);

				/* 
				 * Description:
				 * 
				 * $mixed_field[0] --> table name
				 * $mixed_field[1] --> field name
				 * $mixed_field[2] --> mixed field id
				 * 
				 */

				/* Security Check: Check CREATE permissions for this particular entry (table:mixed_<t1>_<ft2>) */
				if (!$this->security->perm_check($this->_security_perms, $this->security->perm_create, $this->_name, 'mixed_' . $this->_name . '_' . $mixed_field[0])) {
					unset($_POST[$field]);
					continue;
				}

				/* Assign mixed rel value */
				$mixed_rels[$mixed_field[0]][$mixed_field[2]][$mixed_field[1]] = $value;

				unset($_POST[$field]);
				continue;
			}

			/* Security check */
			if (!$this->security->perm_check($this->_security_perms, $this->security->perm_create, $this->_name, $field)) {
				unset($_POST[$field]);
				continue;
			}

			/* Get any possible multiple relationships, removing them from $_POST array. */
			if (substr($field, 0, 4) == 'rel_') {
				$table = $field;
				$rel[$table] = $value;
				unset($_POST[$field]);
			} else if ($ftypes[$field]['type'] == 'datetime') {
				/* Datetime field types requires special processing in order to append
				 * the 'time' component to the 'date'.
				 */
				$_POST[$field] = $this->timezone->convert($value . ' ' . $_POST[$field . '_time'], $this->_session_data['timezone'], $this->_default_timezone);
				unset($_POST[$field . '_time']);
			}

			/* Check if fields are empty */
			if (($_POST[$field] == NULL) || (trim($_POST[$field], ' \t') == '')) {
				/* 
				 * Boolean fields (checkboxes) are set as 0 by default, using a hidden
				 * field in the create view.
				 */

				/* Remove empty fields */
				unset($_POST[$field]);
			}

			/* Grant that foreign table id is eligible to be inserted */
			if (substr($field, -3) == '_id') {
				if (!$this->_table_row_filter_perm($value, substr($field, 0, -3)))
					$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED . ' #2', $this->_default_charset, !$this->request->is_ajax());
			}

			/* If an input pattern was defined for this field, grant that it matches the field value */
			if ($ftypes[$field]['input_pattern']) {
				if (!preg_match('/^' . $ftypes[$field]['input_pattern'] . '$/', $_POST[$field]))
					$this->response->code('403', NDPHP_LANG_MOD_INVALID_FIELD_DATA_PATTERN . ' \'' . $field . '\'', $this->_default_charset, !$this->request->is_ajax());
			}
		}

		/* We need to merge any table row filtering fields (such as users_id) with the POST data
		 * in order to correctly set the table row filtering permissions (if any was configured on $_table_row_filter_config).
		 */
		$_POST = array_merge($_POST, $this->_table_row_filter_get());
 
		/* Initialize transaction */
		$this->db->trans_begin();

		/* Insert data into database */
		$this->db->insert($this->_name, $_POST);

		$last_id = $this->db->last_insert_id();
		
		if (!$last_id) {
			$this->db->trans_rollback();
			$this->response->code('500', NDPHP_LANG_MOD_UNABLE_INSERT_ENTRY, $this->_default_charset, !$this->request->is_ajax());
		}

		/* If logging is enabled, check for changed fields and log them */
		if ($this->_logging === true) {
			/* Temporarily add the 'id' field to POST data */
			$_POST['id'] = $last_id;

			$log_transaction_id = openssl_digest('INSERT' . $this->_name . $this->_session_data['sessions_id'] . date('Y-m-d H:i:s') . mt_rand(1000000, 9999999), 'sha1');

			foreach ($_POST as $pfield => $pvalue) {
				$this->db->insert('logging', array(
					'operation' => 'INSERT',
					'_table' => $this->_name,
					'_field' => $pfield,
					'entryid' => $last_id,
					'value_new' => $pvalue,
					'transaction' => $log_transaction_id,
					'registered' => date('Y-m-d H:i:s'),
					'sessions_id' => $this->_session_data['sessions_id'],
					'users_id' => $this->_session_data['user_id']
				));
			}

			/* Remove the previously added 'id' field from POST data */
			unset($_POST['id']);
		}

		/* Process file uploads */
		foreach ($file_uploads as $file) {
			$this->_process_file_upload($this->_name, $last_id, $file);
		}

		/* Insert relationships, if any */
		if ($rel) {
			foreach ($rel as $table => $value) {
				/* Retrieve the relationship table */
				$rel_table = array_pop(array_diff($this->_get_multiple_rel_table_names($table, $this->_name), array($this->_name)));

				/* Security Permissions Check */
				if (!$this->security->perm_check($this->_security_perms, $this->security->perm_read, $rel_table))
					continue;

				/* TODO: FIXME: Already checked earlier on multiple relationship pre-processing... Does it make sense to keep this here? */
				if (!$this->security->perm_check($this->_security_perms, $this->security->perm_create, $this->_name, $table))
					continue;

				/* TODO: FIXME: Check CREATE permissions for this particular entry (table:rel_<t1>_<ft2>)
				 * [This is already checked on the pre-processing routines... Do we need to recheck here?]
				 */

				/* Remove all related entries from relational table */
				$this->db->delete($table, array($this->_name . '_id' => $last_id));
			
				/* Insert new relationships */
				foreach ($value as $rel_id) {
					if (!$rel_id) /* Ignore the None (hidden) value */
						continue;

					if (!$this->_table_row_filter_perm($rel_id, $rel_table)) {
						$this->db->trans_rollback();
						$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED . ' #3', $this->_default_charset, !$this->request->is_ajax());
					}

					$this->db->insert($table, array($this->_name . '_id' => $last_id, $rel_table . '_id' => $rel_id));
				}
			}
		}

		/* Insert mixed relationships. */
		$this->_mixed_process_post_data($mixed_rels, $last_id, $ftypes);

		/* Commit transaction */
		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			$this->response->code('500', NDPHP_LANG_MOD_FAILED_INSERT, $this->_default_charset, !$this->request->is_ajax());
		} else {
			$this->db->trans_commit();
		}

		/* Load post plugins */
		foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/insert_post.php') as $plugin)
			include($plugin);

		/* Post-Insert hook */
		$this->_hook_insert_post($last_id, $_POST, $ftypes, $hook_pre_return);

		if ($retid) {
			return $last_id;
		} else {
			/* Echo the $last_id so it can be read by the ajax call in create_data.php.
			 * This value will be used to asynchronously load the /load_body_view/<id>
			 * in the success handler of the ajax call.
			 */
			if ($this->_json_replies === true) {
				echo($this->json_insert($last_id));
				return;
			} else if ($this->request->is_ajax()) {
				echo($last_id);
			} else {
				redirect($this->_name . "/view/" . $last_id);
			}
		}
	}
	
	protected function edit_generic($id = 0) {
		/* Check if this is a view table type */
		if ($this->_table_type_view)
			$this->response->code('403', NDPHP_LANG_MOD_CANNOT_OP_VIEW_TYPE_CTRL . ' EDIT.', $this->_default_charset, !$this->request->is_ajax());

		/* Security Permissions Check */
		if (!$this->security->perm_check($this->_security_perms, $this->security->perm_update, $this->_name))
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->_default_charset, !$this->request->is_ajax());

		if (!$this->_table_row_filter_perm($id))
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->_default_charset, !$this->request->is_ajax());

		/* Initialize $data */
		$data = array();

		/* Load enter plugins */
		foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/edit_generic_enter.php') as $plugin)
			include($plugin);

		/* Hook handler (enter) */
		$hook_enter_return = $this->_hook_edit_generic_enter($data, $id);

		/* Get view title */
		$title = NULL;

		if (isset($this->_menu_entries_aliases[$this->_name])) {
			$title = $this->_menu_entries_aliases[$this->_name] . $this->_view_title_sep . NDPHP_LANG_MOD_OP_EDIT;
		} else {
			$title = $this->_viewhname . $this->_view_title_sep . NDPHP_LANG_MOD_OP_EDIT;
		}

		/* Get view description value */
		$description = NDPHP_LANG_MOD_OP_EDIT . " " . $this->_viewhname;

		/* Setup basic view data */
		$data = array_merge($data, $this->_get_view_data_generic($title, $description));

		/* Setup specific view data */
		$data['config']['choices'] = count($this->_rel_choice_hide_fields_edit) ? $this->_rel_choice_hide_fields_edit : $this->_rel_choice_hide_fields;
		$data['config']['render'] = array();
		$data['config']['render']['images'] = $this->_view_image_file_rendering;
		$data['config']['render']['size'] = $this->_view_image_file_rendering_size_view;
		$data['config']['render']['ext'] = $this->_view_image_file_rendering_ext;
		$data['config']['mixed'] = array();
		$data['config']['mixed']['autocomplete'] = $this->_mixed_views_autocomplete;

		$data['view']['fields'] = $this->_get_fields(NULL, $this->_hide_fields_edit); /* _get_fields() uses a perm_read filter by default */
		$data['view']['links']['submenu'] = $this->_links_submenu_body_edit;
		$data['view']['id'] = $id;

		/* If logging is enabled, check for changed fields and log them */
		if ($this->_logging === true) {
			$log_transaction_id = openssl_digest('READ' . $this->_name . $this->_session_data['sessions_id'] . date('Y-m-d H:i:s') . mt_rand(1000000, 9999999), 'sha1');

			$this->db->insert('logging', array(
				'operation' => 'READ',
				'_table' => $this->_name,
				'_field' => 'id',
				'entryid' => $id,
				'transaction' => $log_transaction_id,
				'registered' => date('Y-m-d H:i:s'),
				'sessions_id' => $this->_session_data['sessions_id'],
				'users_id' => $this->_session_data['user_id']
			));
		}

		/* Select only the fields that were returned by _get_fields() */
		$this->_filter_selected_fields($data['view']['fields'], array($this->_name . '.id' => $id));
		$data['view']['result_array'] = $this->_field_value_mangle($data['view']['fields'], $this->db->get($this->_name));

		$data['view']['rel'] = array();

		/* Process multiple relationships */
		foreach ($data['view']['fields'] as $field => $meta) {
			if ($meta['type'] == 'rel') {
				/* Query the database to retrieve the selected elements for this ID */
				$this->db->select($meta['table'] . '.id AS id,' . $meta['table'] . '.' . $meta['rel_field'] . ' AS item');
				$this->db->from($this->_name);
				$this->db->join($meta['rel_table'], $this->_name . '.id = ' . $meta['rel_table'] . '.' . $this->_name . '_id', 'left');
				$this->db->join($meta['table'], $meta['table'] . '.id = ' . $meta['rel_table'] . '.' . $meta['table'] . '_id', 'left');
				$this->db->where($this->_name . '.id', $id);
				$this->db->having('`item` IS NOT NULL');

				$query = $this->db->get();
				
				if (!$query->num_rows()) {
					/* Even if there are no results, create a NULL entry to force
					 * relationship rendering on the loaded view.
					 * 
					 * Note that ID 0 will never be used, as the primary keys start
					 * at ID 1.
					 * 
					 */
					 $data['view']['rel'][$field][0] = NULL;

					 continue;
				}

				foreach ($query->result_array() as $row) {
					/* If any of the fields are NULL, skip the row. (FIXME: This is not required because we're using having() */
					if (!$row['id'] || !$row['item'])
						continue;

					$data['view']['rel'][$field][$row['id']] = $row['item'];
				}
			}
		}

		/* Required fields are extracted from information schema
		 *
		 * NOTE: This requires DBMS to operate in 'strict' mode.
		 * Make sure that 'strict' config parameter is set to TRUE in user/config/database.php
		 *  
		 */
		$schema = $this->load->database($this->_default_database . '_schema', true);

		$schema->select('COLUMN_NAME');
		$schema->from('COLUMNS');
		$schema->where('TABLE_SCHEMA', $this->db->database);
		$schema->where('TABLE_NAME', $this->_name);
		$schema->where('IS_NULLABLE', 'NO');
		$query = $schema->get();
		$schema->close();
		
		$data['view']['required'] = array();
		foreach ($query->result_array() as $row) {
			array_push($data['view']['required'], $row['COLUMN_NAME']);
		}

		/* Hidden fields */
		$data['config']['hidden_fields'] = $this->_hide_fields_edit;

		/* Check if there are any entry fields to be appended to the view title */
		$title_suffix = '';

		if (count($this->_view_title_append_fields)) {
			foreach ($this->_view_title_append_fields as $title_append) {
				/* There's a minor exception for the edit view: The 'id' field isn't part of the result array */
				if ($title_append == 'id') {
					$title_suffix .= $this->_view_title_append_sep . $id;
				} else {
					$title_suffix .= $this->_view_title_append_sep . $data['view']['result_array'][0][$title_append];
				}
			}
		}

		/* Update title and breadcrumb */
		$data['view']['title'] .= $title_suffix;
		$data['view']['links']['breadcrumb'] = $this->_get_breadcrumb('edit', NDPHP_LANG_MOD_OP_EDIT, $id, ltrim($title_suffix, $this->_view_title_append_sep));

		/* Load leave plugins */
		foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/edit_generic_leave.php') as $plugin)
			include($plugin);

		/* Hook handler (leave) */
		$this->_hook_edit_generic_leave($data, $id, $hook_enter_return);

		/* All good */
		return $data;
	}

	public function edit_mixed_rel_count($foreign_table = '', $foreign_id) {
		$this->load->database($this->_default_database);

		if (!$this->_table_row_filter_perm($foreign_id, $foreign_table))
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->_default_charset, !$this->request->is_ajax());

		$this->db->select('COUNT(`' . str_replace('`', '', $foreign_table) . '_id`) AS `total`', false);
		$this->db->from('mixed_' . $foreign_table . '_' . $this->_name);
		$this->db->where($foreign_table . '_id', $foreign_id);

		$query = $this->db->get();
		
		$row = $query->row_array();
		
		/* Return total rows to ajax request */
		echo($row['total']);
	}

	public function edit_mixed_rel($mid, $foreign_table = '', $foreign_id = 0) {
		$data = $this->edit_generic();
		$data['config']['hidden_fields'] = $this->_mixed_hide_fields_edit;
		$data['config']['mixed']['table_field_width'] = $this->_mixed_table_fields_width;

		$data['view']['mixed_id'] = $mid;
		$data['view']['foreign_id'] = $foreign_id;
		$data['view']['foreign_table'] = $foreign_table;
		$data['view']['values'] = array();

		if ($foreign_table != '') {
			$this->load->database($this->_default_database);

			if (!$this->_table_row_filter_perm($foreign_id, $foreign_table))
				$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->_default_charset, !$this->request->is_ajax());

			$this->db->from('mixed_' . $foreign_table . '_' . $this->_name);
			$this->db->where($foreign_table . '_id', $foreign_id);
			$this->db->order_by('id', 'asc');
			$this->db->limit(1, $mid - 1);

			/* We need to mangle the relationship data. We also use _get_fields_basic_types() to avoid the
			 * overhead of calling _get_fields()
			 */
			$result_array = $this->_field_value_mangle($this->_get_fields_basic_types('mixed_' . $foreign_table . '_' . $this->_name), $this->db->get());
			
			/* NOTE: $field_name must have a UNIQUE constraint in order to trigger the following validation */
			if (count($result_array) == 1) {
				$row = array_values($result_array)[0];
				$data['view']['values'] = $row;
			}
		}

		/* Remove the fields that are not present in the mixed relational table */
		$data['view']['present_fields'] = $this->_get_mixed_table_fields($this->_name, $foreign_table);

		/* Load view */
		$this->_load_view('edit_mixed', $data, true);
	}

	public function edit($id = 0, $body_only = false, $body_header = true, $body_footer = true, $modalbox = false) {
		$data = $this->edit_generic($id);

		if ($modalbox)
			$data['config']['modalbox'] = true;

		/* If this is an AJAX call, then we should only render the page body */
		if ($this->request->is_ajax())
			$body_only = true;

		/* Load Views */
		$this->_load_method_views(__FUNCTION__, $data, $body_only, $body_header, $body_footer);
	}

	public function edit_body_ajax($id = 0) {
		$this->edit($id, true);
	}

	public function edit_data_ajax($id = 0) {
		$this->edit($id, true, false);
	}

	public function edit_data_modalbox($id = 0) {
		$this->edit($id, true, false, true, true);
	}

	public function update($id = 0, $field = NULL, $field_value = NULL, $retbool = false) {
		/* NOTE: If $retbool is true, a boolean true value is returned on success (on failure, die() will always be called) */

		/* If an 'id' value was passed as function parameter, use it to replace/assign the actual $_POST['id'] (Used by JSON REST API) */
		if ($id)
			$_POST['id'] = $id;

		/* Grant that $_POST keys are safe */
		if (!$this->security->safe_keys($_POST, $this->_security_safe_chars))
			$this->response->code('403', NDPHP_LANG_MOD_INVALID_POST_KEYS, $this->_default_charset, !$this->request->is_ajax());

		/* Check if this is a view table type */
		if ($this->_table_type_view)
			$this->response->code('403', NDPHP_LANG_MOD_CANNOT_OP_VIEW_TYPE_CTRL . ' UPDATE.', $this->_default_charset, !$this->request->is_ajax());

		/* Security Permissions Check */
		if (!$this->security->perm_check($this->_security_perms, $this->security->perm_update, $this->_name))
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->_default_charset, !$this->request->is_ajax());

		if (!$this->_table_row_filter_perm($_POST['id']))
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->_default_charset, !$this->request->is_ajax());

		/* Set/Update the value of the specified field. (JSON REST API) */
		if ($field !== NULL && $value !== NULL)
			$_POST[$field] = $field_value;

		/* Retrieve fields meta data */
		$ftypes = $this->_get_fields();
		$mixed_rels = array();

		/* Array containing file names to be uploaded */
		$file_uploads = array();

		/* Load pre plugins */
		foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/update_pre.php') as $plugin)
			include($plugin);

		/* Pre-Update hook */
		$hook_pre_return = $this->_hook_update_pre($_POST['id'], $_POST, $ftypes);

		/* Initialize transaction */
		$this->db->trans_begin();

		/* Process file uploads */
		foreach ($_FILES as $k => $v) {
			if (!$_FILES[$k]['name'])
				continue;

			/* Filter filename */
			$_FILES[$k]['name'] = preg_replace('/[^' . $this->_upload_file_name_filter . ']+/', '_', $_FILES[$k]['name']);

			switch ($_FILES[$k]['error']) {
				case UPLOAD_ERR_NO_FILE:
				case UPLOAD_ERR_PARTIAL: continue;
			}

			array_push($file_uploads, $k);

			/* Set the POST value */
			$_POST[$k] = $_FILES[$k]['name'];
		}

		/* Process multiple relationships and special fields first */
		foreach ($_POST as $field => $value) {
			/* Extract mixed relationships, if any */
			if (substr($field, 0, 6) == 'mixed_') {
				$mixed_field = $this->_mixed_process_post_field($field);

				/* 
				 * Description:
				 * 
				 * $mixed_field[0] --> table name
				 * $mixed_field[1] --> field name
				 * $mixed_field[2] --> mixed field id
				 * 
				 */

				/* Security Check: Check UPDATE permissions for this particular entry (table:mixed_<t1>_<ft2>) */
				if (!$this->security->perm_check($this->_security_perms, $this->security->perm_update, $this->_name, 'mixed_' . $this->_name . '_' . $mixed_field[0])) {
					unset($_POST[$field]);
					continue;
				}

				/* Assign mixed rel value */
				$mixed_rels[$mixed_field[0]][$mixed_field[2]][$mixed_field[1]] = $value;

				unset($_POST[$field]);
				continue;
			}

			/* Datetime field types requires special processing in order to append
			 * the 'time' component to the 'date'.
			 */
			if ($ftypes[$field]['type'] == 'datetime') {
				$_POST[$field] = $this->timezone->convert($value . ' ' . $_POST[$field . '_time'], $this->_session_data['timezone'], $this->_default_timezone);
				unset($_POST[$field . '_time']);
				
				continue;
			}

			/* Check if this is a multiple realtionship field */
			if (substr($field, 0, 4) != 'rel_')
				continue; /* If not, skip multiple relationship processing */

			/* Set the table name */
			$table = $field;

			/* Retrieve the relationship table */
			$rel_table = array_pop(array_diff($this->_get_multiple_rel_table_names($table, $this->_name), array($this->_name)));

			/* Security Permissions Check (READ) -- We must be able to read the foreign table... */
			if (!$this->security->perm_check($this->_security_perms, $this->security->perm_read, $rel_table))
				continue;

			/* Security Permissions Check (UPDATE) -- We must be able to update the multiple relationship table */
			if (!$this->security->perm_check($this->_security_perms, $this->security->perm_update, $this->_name, $field))
				continue;

			/* Remove all related entries from relational table */
			$this->db->delete($table, array($this->_name . '_id' => $_POST['id']));
			
			/* Insert new relationships */
			foreach ($value as $rel_id) {
				if (!$rel_id) /* Ignore the None (hidden) value */
					continue;

				if (!$this->_table_row_filter_perm($rel_id, $rel_table)) {
					$this->db->trans_rollback();
					$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->_default_charset, !$this->request->is_ajax());
				}

				$this->db->insert($table, array($this->_name . '_id' => $_POST['id'], $rel_table . '_id' => $rel_id));
			}

			/* Remove relational field from $_POST */
			unset($_POST[$field]);
		}

		/* Set all empty fields ('') to NULL and evaluate column permissions. Also grant input pattern matching. */
		foreach ($_POST as $field => $value) {
			/* Security check */
			if (!$this->security->perm_check($this->_security_perms, $this->security->perm_update, $this->_name, $field)) {
				/* We cannot unset the 'id' field for obvious reasons (reasons: see all the code below belonging to update()) */
				if ($field != 'id')
					unset($_POST[$field]);

				continue;
			}

			/* Check if this is a file field that was requested to be removed */
			if (substr($field, 0, 6) == '_file_') {
				if (isset($_POST[$field . '_remove']) && $_POST[$field . '_remove']) {
					/* Remove the file from filesystem */
					$this->_remove_file_upload($this->_name, $_POST['id'], $field);

					/* Reset database field value */
					$_POST[$field] = NULL;
				} else if (!in_array($field, $file_uploads)) {
					/* Otherwise, if the file was not requested to be removed and no file was uploaded, prevent any update to this field */
					unset($_POST[$field]);
				}

				continue;
			}

			/* Grant that foreign table id is eligible to be updated */
			if (substr($field, -3) == '_id') {
				if (!$this->_table_row_filter_perm($value, substr($field, 0, -3))) {
					$this->db->trans_rollback();
					$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->_default_charset, !$this->request->is_ajax());
				}
			}

			/* Set to NULL if empty */
			if (trim($_POST[$field], ' \t') == '') {
				$_POST[$field] = NULL;
			}

			/* If an input pattern was defined for this field, grant that it matches the field value */
			if ($ftypes[$field]['input_pattern']) {
				if (!preg_match('/^' . $ftypes[$field]['input_pattern'] . '$/', $_POST[$field])) {
					$this->response->code('403', NDPHP_LANG_MOD_INVALID_FIELD_DATA_PATTERN . ' \'' . $field . '\'', $this->_default_charset, !$this->request->is_ajax());
				}
			}
		}

		/* If logging is enabled, check for changed fields and log them */
		if ($this->_logging === true) {
			$changed_fields = $this->post_changed_fields_data($this->_name, $_POST['id'], $_POST);

			$log_transaction_id = openssl_digest('UPDATE' . $this->_name . $this->_session_data['sessions_id'] . date('Y-m-d H:i:s') . mt_rand(1000000, 9999999), 'sha1');

			foreach ($changed_fields as $cfield) {
				$this->db->insert('logging', array(
					'operation' => 'UPDATE',
					'_table' => $this->_name,
					'_field' => $cfield['field'],
					'entryid' => $_POST['id'],
					'value_old' => $cfield['value_old'],
					'value_new' => $cfield['value_new'],
					'transaction' => $log_transaction_id,
					'registered' => date('Y-m-d H:i:s'),
					'sessions_id' => $this->_session_data['sessions_id'],
					'users_id' => $this->_session_data['user_id']
				));
			}
		}

		/* Update entry data */
		$this->db->where('id', $_POST['id']);
		$this->db->update($this->_name, $_POST);

		/* Process file uploads */
		foreach ($file_uploads as $file) {
			$this->_remove_file_upload($this->_name, $_POST['id'], $file);
			$this->_process_file_upload($this->_name, $_POST['id'], $file);
		}

		/* Set the last inserted ID variable */
		$last_id = $_POST['id'];

		/* Process mixed relationships if there are any to be updated */
		$this->_mixed_process_post_data($mixed_rels, $last_id, $ftypes, true);

		/* Commit transaction */
		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			$this->response->code('500', NDPHP_LANG_MOD_FAILED_UPDATE, $this->_default_charset, !$this->request->is_ajax());
		} else {
			$this->db->trans_commit();
		}

		/* Load post plugins */
		foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/update_post.php') as $plugin)
			include($plugin);

		/* Post-Update hook */
		$this->_hook_update_post($_POST['id'], $_POST, $ftypes, $hook_pre_return);

		if ($retbool) {
			return true;
		} else {
			if ($this->_json_replies === true) {
				echo($this->json_update());
				return;
			} else if ($this->request->is_ajax()) {
				echo($_POST['id']);
			} else {
				redirect($this->_name . '/view/' . $_POST['id']);
			}
		}
	}

	protected function remove_generic($id = 0) {
		/* Check if this is a view table type */
		if ($this->_table_type_view)
			$this->response->code('403', NDPHP_LANG_MOD_CANNOT_OP_VIEW_TYPE_CTRL . ' REMOVE.', $this->_default_charset, !$this->request->is_ajax());

		/* Security Permissions Check */
		if (!$this->security->perm_check($this->_security_perms, $this->security->perm_read, $this->_name))
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->_default_charset, !$this->request->is_ajax());

		if (!$this->security->perm_check($this->_security_perms, $this->security->perm_delete, $this->_name))
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->_default_charset, !$this->request->is_ajax());

		if (!$this->_table_row_filter_perm($id))
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->_default_charset, !$this->request->is_ajax());

		/* Initialize $data */
		$data = array();

		/* Load enter plugins */
		foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/remove_generic_enter.php') as $plugin)
			include($plugin);

		/* Hook handler (enter) */
		$hook_enter_return = $this->_hook_remove_generic_enter($data, $id);

		/* Get view title */
		$title = NULL;

		if (isset($this->_menu_entries_aliases[$this->_name])) {
			$title = $this->_menu_entries_aliases[$this->_name] . $this->_view_title_sep . NDPHP_LANG_MOD_OP_REMOVE;
		} else {
			$title = $this->_viewhname . $this->_view_title_sep . NDPHP_LANG_MOD_OP_REMOVE;
		}

		/* Get view description value */
		$description = NDPHP_LANG_MOD_OP_REMOVE . " " . $this->_viewhname;

		/* Setup basic view data */
		$data = array_merge($data, $this->_get_view_data_generic($title, $description));

		/* Setup specific view data */
		$data['view']['fields'] = $this->_get_fields(NULL, $this->_hide_fields_remove); /* _get_fields() uses a perm_read filter by default */
		$data['view']['links'] = array();
		$data['view']['links']['submenu'] = $this->_links_submenu_body_remove;

		$data['config']['choices'] = count($this->_rel_choice_hide_fields_remove) ? $this->_rel_choice_hide_fields_remove : $this->_rel_choice_hide_fields;
		$data['config']['render']['images'] = $this->_view_image_file_rendering;
		$data['config']['render']['size'] = $this->_view_image_file_rendering_size_view;
		$data['config']['render']['ext'] = $this->_view_image_file_rendering_ext;

		$data['view']['id'] = $id;

		/* If logging is enabled, check for changed fields and log them */
		if ($this->_logging === true) {
			$log_transaction_id = openssl_digest('READ' . $this->_name . $this->_session_data['sessions_id'] . date('Y-m-d H:i:s') . mt_rand(1000000, 9999999), 'sha1');

			$this->db->insert('logging', array(
				'operation' => 'READ',
				'_table' => $this->_name,
				'_field' => 'id',
				'entryid' => $id,
				'transaction' => $log_transaction_id,
				'registered' => date('Y-m-d H:i:s'),
				'sessions_id' => $this->_session_data['sessions_id'],
				'users_id' => $this->_session_data['user_id']
			));
		}

		/* Select only the fields that were returned by _get_fields() */
		$this->_filter_selected_fields($data['view']['fields'], array($this->_name . '.id' => $id));
		$data['view']['result_array'] = $this->_field_value_mangle($data['view']['fields'], $this->db->get($this->_name));

		$data['view']['rel'] = array();

		/* Process multiple relationships */
		foreach ($data['view']['fields'] as $field => $meta) {
			if ($meta['type'] == 'rel') {
				/* Query the database to retrieve the selected elements for this ID */
				$this->db->select($meta['table'] . '.id AS id,' . $meta['table'] . '.' . $meta['rel_field'] . ' AS item');
				$this->db->from($this->_name);
				$this->db->join($meta['rel_table'], $this->_name . '.id = ' . $meta['rel_table'] . '.' . $this->_name . '_id', 'left');
				$this->db->join($meta['table'], $meta['table'] . '.id = ' . $meta['rel_table'] . '.' . $meta['table'] . '_id', 'left');
				$this->db->where($this->_name . '.id', $id);
				$this->db->having('`item` IS NOT NULL');

				$query = $this->db->get();
				
				if (!$query->num_rows())
					continue;
				
				foreach ($query->result_array() as $row) {
					/* If any of the fields are NULL, skip the row. (FIXME: This is not required because we're using having() */
					if (!$row['id'] || !$row['item'])
						continue;

					$data['view']['rel'][$field][$row['id']] = $row['item'];
				}
			}
		}

		/* Hidden fields */
		$data['config']['hidden_fields'] = $this->_hide_fields_remove;

		/* Check if there are any entry fields to be appended to the view title */
		$title_suffix = '';

		if (count($this->_view_title_append_fields)) {
			foreach ($this->_view_title_append_fields as $title_append)
				$title_suffix .= $this->_view_title_append_sep . $data['view']['result_array'][0][$title_append];
		}

		/* Update title and breadcrumb */
		$data['view']['title'] .= $title_suffix;
		$data['view']['links']['breadcrumb'] = $this->_get_breadcrumb('remove', NDPHP_LANG_MOD_OP_REMOVE, $id, ltrim($title_suffix, $this->_view_title_append_sep));

		/* Load leave plugins */
		foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/remove_generic_leave.php') as $plugin)
			include($plugin);

		/* Hook handler (leave) */
		$this->_hook_remove_generic_leave($data, $id, $hook_enter_return);

		return $data;
	}

	public function remove_mixed_rel_count($foreign_table = '', $foreign_id) {
		$this->load->database($this->_default_database);

		if (!$this->_table_row_filter_perm($foreign_id, $foreign_table))
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->_default_charset, !$this->request->is_ajax());

		$this->db->select('COUNT(`' . str_replace('`', '', $foreign_table) . '_id`) AS `total`', false);
		$this->db->from('mixed_' . $foreign_table . '_' . $this->_name);
		$this->db->where($foreign_table . '_id', $foreign_id);

		$query = $this->db->get();
		
		$row = $query->row_array();
		
		/* Return total rows to ajax request */
		echo($row['total']);
	}

	public function remove_mixed_rel($mid, $foreign_table = '', $foreign_id = 0) {
		$data = $this->remove_generic();

		$data['config']['hidden_fields'] = $this->_mixed_hide_fields_remove;

		$data['view']['mixed_id'] = $mid;
		$data['view']['foreign_id'] = $foreign_id;
		$data['view']['foreign_table'] = $foreign_table;
		$data['view']['values'] = array();

		if ($foreign_table != '') {
			$this->load->database($this->_default_database);

			if (!$this->_table_row_filter_perm($foreign_id, $foreign_table))
				$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->_default_charset, !$this->request->is_ajax());

			$this->db->from('mixed_' . $foreign_table . '_' . $this->_name);
			$this->db->where($foreign_table . '_id', $foreign_id);
			$this->db->order_by('id', 'asc');
			$this->db->limit(1, $mid - 1);

			/* We need to mangle the relationship data. We also use _get_fields_basic_types() to avoid the
			 * overhead of calling _get_fields()
			 */
			$result_array = $this->_field_value_mangle($this->_get_fields_basic_types('mixed_' . $foreign_table . '_' . $this->_name), $this->db->get());
			
			/* NOTE: $field_name must have a UNIQUE constraint in order to trigger the following validation */
			if (count($result_array) == 1) {
				$row = array_values($result_array)[0];
				$data['view']['values'] = $row;
			}
		}

		/* Remove the fields that are not present in the mixed relational table */
		$data['view']['present_fields'] = $this->_get_mixed_table_fields($this->_name, $foreign_table);

		/* Load view */
		$this->_load_view('remove_mixed', $data, true);
	}

	public function remove($id = 0, $body_only = false, $body_header = true, $body_footer = true, $modalbox = false) {
		$data = $this->remove_generic($id);

		if ($modalbox)
			$data['config']['modalbox'] = true;

		/* If this is an AJAX call, then we should only render the page body */
		if ($this->request->is_ajax())
			$body_only = true;

		/* Load Views */
		$this->_load_method_views(__FUNCTION__, $data, $body_only, $body_header, $body_footer);
	}

	public function remove_body_ajax($id = 0) {
		$this->remove($id, true);
	}

	public function remove_data_ajax($id = 0) {
		$this->remove($id, true, false);
	}

	public function remove_data_modalbox($id = 0) {
		$this->remove($id, true, false, true, true);
	}

	public function delete($id = 0, $retbool = false) {
		/* NOTE: If $retbool is true, a boolean true value is returned on success (on failure, die() will always be called) */

		/* Grant that $_POST keys are safe */
		if (!$this->security->safe_keys($_POST, $this->_security_safe_chars))
			$this->response->code('403', NDPHP_LANG_MOD_INVALID_POST_KEYS, $this->_default_charset, !$this->request->is_ajax());

		/* Check if this is a view table type */
		if ($this->_table_type_view)
			$this->response->code('403', NDPHP_LANG_MOD_CANNOT_OP_VIEW_TYPE_CTRL . ' DELETE.', $this->_default_charset, !$this->request->is_ajax());

		/* Security Permissions Check */
		if (!$this->security->perm_check($this->_security_perms, $this->security->perm_delete, $this->_name))
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->_default_charset, !$this->request->is_ajax());

		if (!$this->_table_row_filter_perm($_POST['id']))
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->_default_charset, !$this->request->is_ajax());

		$ftypes = $this->_get_fields();

		/* Set/Update $_POST['id'] if $id is different than 0 (usually used by JSON REST API) */
		if ($id)
			$_POST['id'] = $id;

		/* Load pre plugins */
		foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/delete_pre.php') as $plugin)
			include($plugin);

		/* Pre-Delete hook */
		$hook_pre_return = $this->_hook_delete_pre($_POST['id'], $_POST, $ftypes);

		/* Init transaction */
		$this->db->trans_begin();

		/* If logging is enabled, check for changed fields and log them */
		if ($this->_logging === true) {
			$log_transaction_id = openssl_digest('DELETE' . $this->_name . $this->_session_data['sessions_id'] . date('Y-m-d H:i:s') . mt_rand(1000000, 9999999), 'sha1');

			$this->db->insert('logging', array(
				'operation' => 'DELETE',
				'_table' => $this->_name,
				'_field' => 'id',
				'entryid' => $_POST['id'],
				'transaction' => $log_transaction_id,
				'registered' => date('Y-m-d H:i:s'),
				'sessions_id' => $this->_session_data['sessions_id'],
				'users_id' => $this->_session_data['user_id']
			));
		}

		/* We don't need to follow relationships as the foreign keys must be configured
		 * as CASCADE ON DELETE on the relational table.
		 */
		$this->db->where('id', $_POST['id']);
		$this->db->delete($this->_name);

		/* Commit transaction */
		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			$this->response->code('500', NDPHP_LANG_MOD_UNABLE_DELETE_ENTRY, $this->_default_charset, !$this->request->is_ajax());
		} else {
			$this->db->trans_commit();
		}

		/* Delete uploaded files, if any */
		$this->_delete_entry_uploads($this->_name, $_POST['id']);

		/* NOTE: All relationships (including mixed relationships) shall be deleted through CASCADE events defined on the
		 * DBMS data model.
		 */

		/* Load post plugins */
		foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/delete_post.php') as $plugin)
			include($plugin);

		/* Post-Delete hook */
		$this->_hook_delete_post($_POST['id'], $_POST, $ftypes, $hook_pre_return);

		if ($retbool) {
			return true;
		} else {
			if ($this->_json_replies === true) {
				echo($this->json_delete());
				return;
			} else if ($this->request->is_ajax()) {
				echo('OK'); /* FIXME: What should be replied? */
			} else {
				redirect($this->_name);
			}
		}
	}

	protected function view_generic($id = 0, $export = NULL) {
		/* Security Permissions Check */
		if (!$this->security->perm_check($this->_security_perms, $this->security->perm_read, $this->_name))
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->_default_charset, !$this->request->is_ajax());

		if (!$this->_table_row_filter_perm($id))
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->_default_charset, !$this->request->is_ajax());

		/* Initialize $data */
		$data = array();

		/* Load enter plugins */
		foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/view_generic_enter.php') as $plugin)
			include($plugin);

		/* Hook handler (enter) */
		$hook_enter_return = $this->_hook_view_generic_enter($data, $id, $export);

		/* Setup charts */
		$this->_charts_config();

		/* Get view title */
		$title = NULL;

		if (isset($this->_menu_entries_aliases[$this->_name])) {
			$title = $this->_menu_entries_aliases[$this->_name] . $this->_view_title_sep . NDPHP_LANG_MOD_OP_VIEW;
		} else {
			$title = $this->_viewhname . $this->_view_title_sep . NDPHP_LANG_MOD_OP_VIEW;
		}

		/* Get view description value */
		$description = NDPHP_LANG_MOD_OP_VIEW . " " . $this->_viewhname;

		/* Setup basic view data */
		$data = array_merge($data, $this->_get_view_data_generic($title, $description));

		/* Setup specific View data */
		$data['view']['fields'] = $this->_get_fields(NULL, $this->_hide_fields_view); /* _get_fields() uses a perm_read filter by default */
		$data['view']['links']['submenu'] = $this->_links_submenu_body_view;

		$data['config']['charts']['total'] = count($this->_charts_foreign);
		$data['config']['choices'] = count($this->_rel_choice_hide_fields_view) ? $this->_rel_choice_hide_fields_view : $this->_rel_choice_hide_fields;
		$data['config']['render']['images'] = $this->_view_image_file_rendering;
		$data['config']['render']['size'] = $this->_view_image_file_rendering_size_view;
		$data['config']['render']['ext'] = $this->_view_image_file_rendering_ext;

		$data['view']['id'] = $id;

		/* If logging is enabled, check for changed fields and log them */
		if ($this->_logging === true && $export === NULL) {
			$log_transaction_id = openssl_digest('READ' . $this->_name . $this->_session_data['sessions_id'] . date('Y-m-d H:i:s') . mt_rand(1000000, 9999999), 'sha1');

			$this->db->insert('logging', array(
				'operation' => 'READ',
				'_table' => $this->_name,
				'_field' => 'id',
				'entryid' => $id,
				'transaction' => $log_transaction_id,
				'registered' => date('Y-m-d H:i:s'),
				'sessions_id' => $this->_session_data['sessions_id'],
				'users_id' => $this->_session_data['user_id']
			));
		} else if ($this->_logging === true && $export !== NULL) {
			$log_transaction_id = openssl_digest('EXPORT' . $this->_name . $this->_session_data['sessions_id'] . date('Y-m-d H:i:s') . mt_rand(1000000, 9999999), 'sha1');

			$this->db->insert('logging', array(
				'operation' => 'EXPORT',
				'_table' => $this->_name,
				'_field' => 'id (' . strtoupper($export) . ')',
				'entryid' => $id,
				'transaction' => $log_transaction_id,
				'registered' => date('Y-m-d H:i:s'),
				'sessions_id' => $this->_session_data['sessions_id'],
				'users_id' => $this->_session_data['user_id']
			));
		}

		/* Select only the fields that were returned by _get_fields() */
		$this->_filter_selected_fields($data['view']['fields'], array($this->_name . '.id' => $id));
		$data['view']['result_array'] = $this->_field_value_mangle($data['view']['fields'], $this->db->get($this->_name));

		$data['view']['rel'] = array();

		/* Process multiple relationships */
		foreach ($data['view']['fields'] as $field => $meta) {
			if ($meta['type'] == 'rel') {
				/* Query the database to retrieve the selected elements for this ID */
				$this->db->select($meta['table'] . '.id AS id,' . $meta['table'] . '.' . $meta['rel_field'] . ' AS item');
				$this->db->from($this->_name);
				$this->db->join($meta['rel_table'], $this->_name . '.id = ' . $meta['rel_table'] . '.' . $this->_name . '_id', 'left');
				$this->db->join($meta['table'], $meta['table'] . '.id = ' . $meta['rel_table'] . '.' . $meta['table'] . '_id', 'left');
				$this->db->where($this->_name . '.id', $id);
				$this->db->having('`item` IS NOT NULL');

				$query = $this->db->get();

				if (!$query->num_rows())
					continue;

				foreach ($query->result_array() as $row) {
					/* If any of the fields are NULL, skip the row. (FIXME: This is not required because we're using having() */
					if (!$row['id'] || !$row['item'])
						continue;

					$data['view']['rel'][$field][$row['id']] = $row['item'];
				}
			}
		}

		/* Hidden fields */
		$data['config']['hidden_fields'] = $this->_hide_fields_view;

		/* Check if there are any entry fields to be appended to the view title */
		$title_suffix = '';

		if (count($this->_view_title_append_fields)) {
			foreach ($this->_view_title_append_fields as $title_append)
				$title_suffix .= $this->_view_title_append_sep . $data['view']['result_array'][0][$title_append];
		}

		/* Update title and breadcrumb */
		$data['view']['title'] .= $title_suffix;
		$data['view']['links']['breadcrumb'] = $this->_get_breadcrumb('view', NDPHP_LANG_MOD_OP_VIEW, $id, ltrim($title_suffix, $this->_view_title_append_sep));

		/* Load leave plugins */
		foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/view_generic_leave.php') as $plugin)
			include($plugin);

		/* Hook handler (leave) */
		$this->_hook_view_generic_leave($data, $id, $export, $hook_enter_return);

		/* All good */
		return $data;
	}

	public function view_mixed_rel_count($foreign_table = '', $foreign_id) {
		$this->load->database($this->_default_database);

		if (!$this->_table_row_filter_perm($foreign_id, $foreign_table))
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->_default_charset, !$this->request->is_ajax());

		$this->db->select('COUNT(`' . str_replace('`', '', $foreign_table) . '_id`) AS `total`', false);
		$this->db->from('mixed_' . $foreign_table . '_' . $this->_name);
		$this->db->where($foreign_table . '_id', $foreign_id);

		$query = $this->db->get();
		
		$row = $query->row_array();
		
		/* Return total rows to ajax request */
		echo($row['total']);
	}

	public function view_mixed_rel($mid, $foreign_table = '', $foreign_id = 0) {
		$data = $this->view_generic();

		$data['config']['hidden_fields'] = $this->_mixed_hide_fields_view;

		$data['view']['mixed_id'] = $mid;
		$data['view']['foreign_id'] = $foreign_id;
		$data['view']['foreign_table'] = $foreign_table;

		$data['view']['values'] = array();

		if ($foreign_table != '') {
			$this->load->database($this->_default_database);

			if (!$this->_table_row_filter_perm($foreign_id, $foreign_table))
				$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->_default_charset, !$this->request->is_ajax());

			$this->db->from('mixed_' . $foreign_table . '_' . $this->_name);
			$this->db->where($foreign_table . '_id', $foreign_id);
			$this->db->order_by('id', 'asc');
			$this->db->limit(1, $mid - 1);
			
			/* We need to mangle the relationship data. We also use _get_fields_basic_types() to avoid the
			 * overhead of calling _get_fields()
			 */
			$result_array = $this->_field_value_mangle($this->_get_fields_basic_types('mixed_' . $foreign_table . '_' . $this->_name), $this->db->get());
			
			/* NOTE: $field_name must have a UNIQUE constraint in order to trigger the following validation */
			if (count($result_array) == 1) {
				$row = array_values($result_array)[0];
				$data['view']['values'] = $row;
			}
		}

		/* Remove the fields that are not present in the mixed relational table */
		$data['view']['present_fields'] = $this->_get_mixed_table_fields($this->_name, $foreign_table);

		/* Load view */
		$this->_load_view('view_mixed', $data, true);
	}

	public function view($id = 0, $export = NULL, $body_only = false, $body_header = true, $body_footer = true, $modalbox = false) {
		$data = $this->view_generic($id, $export);

		if ($modalbox)
			$data['config']['modalbox'] = true;

		/* If this is an AJAX call, then we should only render the page body */
		if ($this->request->is_ajax())
			$body_only = true;

		/* If this is an JSON API request, just reply the data (do not load the views) */
		if ($this->_json_replies === true) {
			echo($this->json_view($data));
			return;
		}

		if ($export) {
			if ($export == 'pdf') {
				/* Pre-process any exceptions for pdf export */
				foreach ($data['view']['fields'] as $field => $meta) {
					/* In order to allow the pdf export library to render image files stored in _file_* fields,
					 * we need to provide the image file contents in base64 format.
					 * This is required because the export library will act as a different client, thus will have no valid
					 * session that will allow it to fetch files from the files.php controller.
					 */
					if ($meta['input_type'] == 'file') {
						/* Get file extension */
						$file_type = end(explode('.', $data['view']['result_array'][0][$field]));

						/* Check if this file type is configured to be rendered as an image */
						if (!in_array($file_type, $this->_view_image_file_rendering_ext))
							continue;

						/* Craft the file location */
						$file_path = SYSTEM_BASE_DIR . '/uploads/' . $this->_session_data['user_id'] . '/' . $this->_name . '/' . $id . '/' . $field . '/' . openssl_digest($data['view']['result_array'][0][$field], 'sha256');
						
						/* Get file contents */
						if (($file_contents = file_get_contents($file_path)) === false)
							continue;

						/* If the contents of the file are encrypted ... */
						if ($this->_upload_file_encryption === true)
							$file_contents = $this->encrypt->decode($file_contents);

						/* Convert file content to base64 format */
						$data['view']['fields'][$field]['base64_format'] = 'data:image/' . $file_type . ';base64,' . base64_encode($file_contents);
					}
				}

				/* Load view data */
				$view_data = $this->_load_view('exportview', $data, true, true);

				/* Create PDF */
				$this->mpdf->WriteHTML($view_data);
				/*	mPDF Output() options:
				 * 
				 * 	I: send the file inline to the browser. The plug-in is used if available. The name given by filename is used when one selects the "Save as" option on the link generating the PDF.
				 *	D: send to the browser and force a file download with the name given by filename.
				 *	F: save to a local file with the name given by filename (may include a path).
				 *	S: return the document as a string. filename is ignored.
				 */
				$this->mpdf->Output($this->_name . '_' . $id . '.pdf', 'D');
			} else if ($export == 'csv') {
				// TODO: Implement CSV export here
			}
		} else {
			/* Load Views */
			$this->_load_method_views(__FUNCTION__, $data, $body_only, $body_header, $body_footer);
		}		
	}
	
	public function view_body_ajax($id = 0, $export = NULL) {
		$this->view($id, $export, true);
	}

	public function view_data_ajax($id = 0, $export = NULL) {
		$this->view($id, $export, true, false);
	}

	public function view_data_modalbox($id = 0, $export = NULL) {
		$this->view($id, $export, true, false, true, true);
	}

	public function entry($id, $field = NULL, $value = NULL) {
		/* TODO: Add REST JSON API support for this method */

		/* Fetch data for entry $id */
		$data = $this->view_generic($id);
		$row = array_values($data['view']['result_array'])[0];

		/* If $field is not set, this is a getter for the full row */
		if (!$field)
			return $row;

		/* If $field is set and $value is not set, this is a getter for $field value */
		if ($field && !$value) {
			if (isset($row[$field]))
				return $row[$field];

			/* Either we've no permissions to access that $field, or that $field does not exist (FIXME: What if $row[$field] is NULL?) */
			return NULL;
		}

		/* If both $field and $value are set, this is a setter for the $field belonging to entry $id */
		if ($field && $value)
			return $this->update($id, $field, $value, true);

		/* We should never reach this point */
		return false;
	}
}
