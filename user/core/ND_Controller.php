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
 * * Single and multiple relationship [select] fields shall change behaviour when the number of entries are greater than 500 or so (use a search based autocomplete listing).
 * * Default action for item selection (currently hardcoded as View, but one should be able to change it to Edit).
 * * Multi Dropdown filters, allowing a selection of an item from a dropdown to filter the contents of another dropdown (or more)
 * * Mixed relationship autocomplete feature shall support user-defined filters.
 * * Add support for hooks on static file requests.
 * * IDE application model should validate everything that was previously validated by ide.js.
 * * [IN_PROGRESS] Database Sharding (per user).
 * * Add support for memcached to lower database overhead.
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
 * * Implement guest user support. Authentication for guest user is done automatically for controllers allowing it. This user must be disabled by default.
 * * Total mixed entries as a list_default() / result() field.
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
 * - Rollback operation on Logging controller should be able to also rollback multiple and mixed relationship values.
 *
 * FIXME:
 *
 * + (bootstrap rev) On input error, input boxes shall receive the bootstrap class "has-error".
 * + (bootstrap rev) Tags should be used on multiple relationship fields.
 * + (bootstrap rev) jquery-ui tooltips still being used instead of native bootstrap tooltips.
 * + (bootstrap rev) Input patterns not working with the new bootstap theme.
 * + (bootstrap rev) PDF export has no CSS associated (either for listing and item view).
 * + Export CSV is exporing separators as fields. This should be disabled by default.
 * + Mixed relationships auto add feature is not assigning single relationships to the foreign table when entry is created.
 * + Do not allow 0000-00-00 date (or date component of datetime) values.
 * + Chart generators should attempt to trigger both list and result filter hook.
 + + Chart imagemaps display incorrect captions on bar charts.
 * + On REST insert/update functions, when processing single, multiple and mixed relationships, evaluate if the value(s) are integers.. if not, translate the string value based on foreign table contents (useful for REST API calls).
 * + Browsing history (from browsing actions) should be cleaned up from time to time (eg, store only the last 20 or so entries)
 * * When a separator is hidden, all associated fields shall also be hidden by default.
 * * Framework core tables should be prefixed with nd_*
 * * Grouping features (Group views) requires a huge refactoring (current performance is very poor).
 * * Mixed and multiple relationships must work properly when javascript is disabled.
 * * Advanced search must work properly when javascript is disabled.
 * * Saved searches results do not have full breadcrumb support.
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
 * DEBUG:
 *
 * + Something went wrong with remove view / delete (more than one id was being passed in the POST request comming from remove view).
 *
 */

class ND_Controller extends UW_Controller {
	/** Configuration **/
	public $config = array(); /* Will be populated in constructor */

	/* Framework version */
	protected $_ndphp_version = '0.5a7';

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

	/* The main support email address */
	protected $_support_email = 'no-support@nd-php.org';

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
	protected $_table_pagination_rpp_list_inherit_config = true; /* If set to false, the value of $_table_pagination_rpp_list will be used for this controller */
	protected $_table_pagination_rpp_list = 10; /* Maximum rows per page for listings */

	protected $_table_pagination_rpp_result_inherit_config = true; /* If set to false, the value of $_table_pagination_rpp_result will be used for this controller */
	protected $_table_pagination_rpp_result = 10; /* Maximum rows per page for results */

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

	/* Extra queries (or VIEW creation) for this custom controller */
	protected $_table_type_view_query_extra = array(); /* Eg: array('CREATE OR REPLACE VIEW rel_vi_table1_table2 AS SELECT ...'); */

	/* The fields to be concatenated as the options of the relationship table. Also the place to set relational field name aliases. */
	protected $_rel_table_fields_config = array(
		/* 'table' => array('ViewName', 'separator', array(field_nr_1, field_nr_2, ...), array('order_by field', 'asc or desc'), $limit), */
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

    /* Table relational choices options filters */
    protected $_rel_choice_filter_fields_options = array(
    	/*
    	'field_id' => array(
    		'filter_field1_id',
    		'filter_field2_id',
    		...
    	)
    	*/
    );

	/* Set a custom class for table row based on single relationship field values.
	 * Any class specified here must exist in a loaded CSS.
	 *
	 * Example:
	 *
	 *   .purple   { ... }
	 *   .brown    { ... }
	 *   .orange   { ... }
	 *
	 * There are already some predefined classes: 'danger', 'warning', 'success' and 'info'.
	 *
	 */
	protected $_rel_choice_table_row_class = array(
		/*
		'rel_field' => 'field_id',
		'values' => array(
			'CRITICAL' => 'danger',
			'WARNING' => 'warning',
			'OK' => 'success'
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
		'width'  => 'auto',
		'height' => '32px'
	);

	/* The image scaling for the embedded images under the entry views */
	protected $_view_image_file_rendering_size_view = array(
		'width'  => 'auto',
		'height' => '256px'
	);

	/* Quick Operations Links (Listing and Result views) */
	protected $_links_quick_modal_list = array(
		/* array('Description', $sec_perm, method, 'image/path/img.png', $modal_width) */
		array(NDPHP_LANG_MOD_OP_QUICK_VIEW,		'R', 'view_data_modalbox',   'icons/quick_view.png'),
		array(NDPHP_LANG_MOD_OP_QUICK_EDIT,		'U', 'edit_data_modalbox',   'icons/quick_edit.png'),
		array(NDPHP_LANG_MOD_OP_QUICK_REMOVE,	'D', 'remove_data_modalbox', 'icons/quick_remove.png')
	);

	protected $_links_quick_modal_result = array(
		/* array('Description', $sec_perm, method, 'image/path/img.png', $modal_width) */
		array(NDPHP_LANG_MOD_OP_QUICK_VIEW,		'R', 'view_data_modalbox',   'icons/quick_view.png'),
		array(NDPHP_LANG_MOD_OP_QUICK_EDIT,		'U', 'edit_data_modalbox',   'icons/quick_edit.png'),
		array(NDPHP_LANG_MOD_OP_QUICK_REMOVE,	'D', 'remove_data_modalbox', 'icons/quick_remove.png')
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

	/* If set to true, uploaded files will be stored encrypted (only supported on 'local' driver) */
	protected $_upload_file_encryption = true;

	/* Upload max file size */
	protected $_upload_file_max_size = 10485760; /* 10MiB by default */

	/* Upload file image min width */
	protected $_upload_file_image_width_min = 16;

	/* Upload file image min height */
	protected $_upload_file_image_height_min = 16;

	/* Upload file image max width */
	protected $_upload_file_image_width_max = 7680;

	/* Upload file image max height */
	protected $_upload_file_image_height_max = 7680;

	/* Upload file image accepted extensions */
	protected $_upload_file_image_extensions = array('jpg', 'jpeg', 'gif', 'png', 'ico', 'bmp', 'svg');

	/* Regex to filter uploaded file name. All the characters not matching the following pattern will be replaced with '_' */
	protected $_upload_file_name_filter = 'a-zA-Z0-9_\.';

	/* The upload driver to be used (supported drivers: local, s3) [If left blank, see user/config/base.php settings] */
	protected $_upload_file_driver = '';

	/* The base URL for the uploaded images. Leave blank if 'local' driver is being used. [If left blank, see user/config/base.php settings] */
	protected $_upload_file_base_url = '';

	/* Session data buffer (will be populated with construct) */
	protected $_session_data = array();

	/* If enabled, instead of loading views, the reply will be in JSON */
	protected $_json_replies = false;

	/* JSON REST API listing / result hard limits */
	protected $_json_result_hard_limit = 1000;

	/* Scheduler settings */
	protected $_scheduler = array(
		'type' => 'external', /* By default ('external'), scheduled entries will only be processed when public scheduler_external method is invoked.
							   * If set to 'request', scheduled entries will be evaluated and processed on every request.
							   * If set to 'threaded' will behave as 'request', but execution of scheduled entries are performed in a separate thread (requires PHP threading support).
							   */
	);

	/* Threading */
	protected $_threading = false;

	/* Plugins */
	protected $_plugins_enabled = false; /* Set to true if there are ND-PHP plugins installed */

	/* Caching */
	protected $_cache_tables = array(); /* This array will be populated by _get_tables() method. */
	protected $_cache_table_desc = array(); /* This array will be populated by _get_table_desc() method. */
	protected $_cache_table_fields = array(); /* This array will be populated by _get_table_fields() method. */
	protected $_cache_help = array(); /* This array will be populated by _get_help() method */


	/* Security */
	protected $_security_safe_chars = "a-zA-Z0-9_-"; /* Mainly used to validate names of tables, fields and keys */
	protected $_security_perms = array();			 /* Will be populated by $this->security->perm_get() */


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

	public function config_populate() {
		global $config;

		/* Populate public configuration ($config) */
		$this->config['ndphp_version']							= $this->_ndphp_version;

		$this->config['name']									= $this->_name;
		$this->config['viewhname']								= $this->_viewhname;
		$this->config['word_true']								= $this->_word_true;
		$this->config['word_false']								= $this->_word_false;

		$this->config['logging']								= $this->_logging;
		$this->config['accounting']								= $this->_accounting;

		$this->config['base_url']								= $this->_base_url;
		$this->config['support_email']							= $this->_support_email;
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
		$this->config['hide_fields_groups']						= $this->_hide_fields_groups;
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
		$this->config['table_pagination_rpp_list_inherit_config'] = $this->_table_pagination_rpp_list_inherit_config;
		$this->config['table_pagination_rpp_list']				= $this->_table_pagination_rpp_list;
		$this->config['table_pagination_rpp_result_inherit_config'] = $this->_table_pagination_rpp_result_inherit_config;
		$this->config['table_pagination_rpp_result']			= $this->_table_pagination_rpp_result;
		$this->config['table_row_filtering']					= $this->_table_row_filtering;
		$this->config['table_row_filtering_config']				= $this->_table_row_filtering_config;
		$this->config['table_type_view']						= $this->_table_type_view;
		$this->config['table_type_view_query']					= $this->_table_type_view_query;
		$this->config['table_type_view_query_extra']			= $this->_table_type_view_query_extra;

		$this->config['rel_choice_hide_fields']					= $this->_rel_choice_hide_fields;
		$this->config['rel_choice_hide_fields_create']			= $this->_rel_choice_hide_fields_create;
		$this->config['rel_choice_hide_fields_edit']			= $this->_rel_choice_hide_fields_edit;
		$this->config['rel_choice_hide_fields_view']			= $this->_rel_choice_hide_fields_view;
		$this->config['rel_choice_hide_fields_remove']			= $this->_rel_choice_hide_fields_remove;
		$this->config['rel_choice_filter_fields_options']		= $this->_rel_choice_filter_fields_options;
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
		$this->config['view_title_append_fields']				= $this->_view_title_append_fields;
		$this->config['view_title_append_sep']					= $this->_view_title_append_sep;

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
		$this->config['upload_file_image_width_min'] 			= $this->_upload_file_image_width_min;
		$this->config['upload_file_image_height_min'] 			= $this->_upload_file_image_height_min;
		$this->config['upload_file_image_width_max'] 			= $this->_upload_file_image_width_max;
		$this->config['upload_file_image_height_max'] 			= $this->_upload_file_image_height_max;
		$this->config['upload_file_image_extensions'] 			= $this->_upload_file_image_extensions;
		$this->config['upload_file_driver']						= $this->_upload_file_driver;

		if (!$this->config['upload_file_driver']) {
			if (isset($config['base']['default_upload_file_driver']) && isset($config['base']['default_upload_file_base_url'])) {
				$this->_upload_file_driver = $config['base']['default_upload_file_driver'];
				$this->_upload_file_base_url = $config['base']['default_upload_file_base_url'];
				$this->config['upload_file_driver'] = $config['base']['default_upload_file_driver'];
				$this->config['upload_file_base_url'] = $config['base']['default_upload_file_base_url'];
			} else {
				$this->config['upload_file_driver'] = 'local';
			}
		}

		if ($this->config['upload_file_driver'] == 'local') {
			$this->config['upload_file_base_url']				= base_url() . 'index.php/files/access/' . $this->config['name'];
		} else {
			$this->config['upload_file_base_url']				= $this->_upload_file_base_url;
		}

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
		$this->config['json_result_hard_limit']					= $this->_json_result_hard_limit;

		$this->config['scheduler']								= $this->_scheduler;

		$this->config['cache_tables']							= $this->_cache_tables;
		$this->config['cache_table_desc']						= $This->_cache_table_desc;
		$this->config['cache_table_fields']						= $this->_cache_table_fields;
		$this->config['cache_help']								= $this->_cache_help;
		/* TODO: FIXME: Missing some explicit cache_ key declarations here that are used around the code */

		$this->config['scheduler']								= $this->_scheduler;

		$this->config['threading']								= $this->_threading;

		$this->config['plugins_enabled'] 						= $this->_plugins_enabled;

		/* Context */
		$this->config['context'] = array();
		$this->config['context']['db'] = $this->db;

		/* Set core configuration */
		$this->configuration->core_context_set($this->config['name']);
		$this->configuration->core_set($this->config);
	}

	protected function _load_module($name, $share_base = false) {
		$this->load->module($name);

		/* Grant that there are no special characters on module name */
		if (!preg_match('/^[a-zA-Z0-9\_]+$/i', $name)) {
			header('HTTP/1.1 500 Internal Server Error');
			die(NDPHP_LANG_MOD_INVALID_CTRL_NAME . ': ' . $name);
		}

		/* Share the underlying models? */
		if ($share_base === true) {
			eval('$this->' . $name . '->db = &$this->db;');
			eval('$this->' . $name . '->session = &$this->session;');
			eval('$this->' . $name . '->encrypt = &$this->encrypt;');
		}
	}

	protected function _core_modules() {
		/* TODO: FIXME: Not all of the following modules are required to be loaded by default */
		$this->_load_module('get', true);
		$this->_load_module('filter', true);
		$this->_load_module('field', true);
		$this->_load_module('table', true);
	}

	protected function _init($class_name, $is_core = false) {
		$this->_viewhname = $class_name;
		$this->_name = strtolower($this->_viewhname);

		if ($is_core) {
			/* Include any setup procedures from ide builder. */
			include(SYSTEM_BASE_DIR . '/application/controllers/lib/ide_setup.php');
		} else {
			$this->_hide_global_search_controllers = $this->_hide_menu_entries;
		}

		/* Populate controller configuration */
		$this->config_populate();

		/* Load core modules */
		$this->_core_modules();

		/* Call construct hook */
		$this->_hook_construct();
	}

	/** Constructor **/

	public function __construct($session_enable = true, $json_replies = false) {
		parent::__construct();

		/* Grant that the configured cookie domain matches the server name */
		$cookie_domain = current_config()['session']['cookie_domain'];
		if (substr($_SERVER['SERVER_NAME'], -strlen($cookie_domain)) !== $cookie_domain)
			$this->response->code('500', NDPHP_LANG_MOD_INVALID_SERVER_NAME, $this->_default_charset, !$this->request->is_ajax());

		/* Load pre plugins */
		if ($this->config['plugins_enabled']) {
			foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/construct_pre.php') as $plugin)
				include($plugin);
		}

		/* Check if JSON replies should be enabled */
		if ($json_replies || $this->request->is_json()) {
			/* Enable JSON replies */
			$this->_json_replies = true;

			/* If JSON replies are enabled, load rest module */
			$this->_load_module('rest', true);

			/* Check if this is a POST */
			if ($this->request->is_post()) {
				/* Fetch JSON data, if any */
				$json_req = $this->request->json();

				/* If there is JSON data set, map it to POST data */
				if (isset($json_req['data'])) {
					$this->request->post_set_all($json_req['data']);
				} else {
					/* We need to cleanup the POST data in order to pass the POST keys validation */
					/* Note that POST data is supposed to be empty from now on, since no $json_req['data'] is set,
					 * causing the effective POST data array to be empty.
					 */
					$this->request->post_set_all(array());
				}
			}
		}

		/* POST data handlers */
		if ($this->request->is_post() && count($this->request->post())) {
			/* Set all $_POST keys to lowercase */
			foreach ($this->request->post() as $key => $value) {
				$this->request->post_unset($key);
				$this->request->post_set(strtolower($key), $value);
			}

			/* Grant that $_POST keys are safe, if any */
			if (count($this->request->post()) && !$this->security->safe_keys($this->request->post(), $this->_security_safe_chars))
				$this->response->code('400', NDPHP_LANG_MOD_INVALID_POST_KEYS, $this->_default_charset, !$this->request->is_ajax());
		}

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
				/* TODO: FIXME: Pre-validate _apikey and _userid format before querying the database */

				/* TODO: FIXME: Authentication procedures should be migrated into a model and used both in the
				 * ND_Controller __construct() and Login controller authenticate()
				 */

				/* Query the database */
				$this->db->select('users.id AS user_id,users.username AS username,users.first_name AS first_name,users.email AS email,users.privenckey AS privenckey, rel_users_roles.roles_id AS roles_id,timezones.timezone AS timezone,roles.is_superuser,roles.is_admin');
				$this->db->from('users');
				$this->db->join('rel_users_roles', 'rel_users_roles.users_id = users.id', 'left');
				$this->db->join('roles', 'rel_users_roles.roles_id = roles.id', 'left');
				$this->db->join('timezones', 'users.timezones_id = timezones.id', 'left');
				$this->db->where('users.id', $json_req['_userid']);
				$this->db->where('users.apikey', $json_req['_apikey']);

				$query = $this->db->get();

				if (!$query->num_rows())
					$this->response->code('403', NDPHP_LANG_MOD_INVALID_CREDENTIALS, $this->_default_charset, !$this->request->is_ajax());

				/* Setup user roles */
				$user_roles = array();
				$user_superuser = false;
				$user_admin = false;

				foreach ($query->result_array() as $row) {
					array_push($user_roles, $row['roles_id']);

					/* Check if this is an admin role */
					if ($row['is_admin'])
						$user_admin = true;

					/* Check if this is a superuser role */
					if ($row['is_superuser'])
						$user_superuser = true;
				}
				
				/* Setup the user private encryption key */
				$privenckey = NULL;

				/* Check if user password is set... if so, we'll descrypt the private encryption key */
				if (isset($json_req['_password'])) {
					/* Decrypt the stored key with the user's plain password */
					$privenckey = $this->encrypt->decrypt($row['privenckey'], $json_req['_password'], false);

					if (strlen($privenckey) != 256)
						$this->response->code('500', NDPHP_LANG_MOD_INVALID_PRIV_ENC_KEY, $this->_default_charset, !$this->request->is_ajax());
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
						'database' => $this->_default_database,
						'roles' => $user_roles,
						'is_admin' => $user_admin,
						'is_superuser' => $user_superuser,
						'privenckey' => bin2hex($privenckey),
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

				if ($this->request->is_json()) { /* If this is a JSON REST call, reply unauthorized */
					$this->response->code('401', NDPHP_LANG_MOD_ATTN_REAUTH_REQUIRED, $this->_default_charset, !$this->request->is_ajax());
				} else if ($this->request->is_ajax()) { /* If this is an AJAX call, redirect to /login/ ... Otherwise set the referer URL */
					die('<meta http-equiv="refresh" content="0; url=' . base_url() . 'index.php/login"><script type="text/javascript">window.location = "' . base_url() . 'index.php/login";</script>');
				} else {
					die('<meta http-equiv="refresh" content="0; url=' . base_url() . 'index.php/login/login/' . $this->ndphp->safe_b64encode(current_url()) . '"><script type="text/javascript">window.location = "' . base_url() . 'index.php/login/login/' . $this->ndphp->safe_b64encode(current_url()) . '";</script>');
				}
			}
		}

		/* Load configuration */
		$config = $this->configuration->get();

		$this->_base_url = base_url();
		$this->_support_email = $config['support_email'];
		$this->_project_author = $config['author'];
		$this->_project_name = $config['project_name'];
		$this->_project_tagline = $config['tagline'];
		$this->_project_description = $config['description'];
		$this->_default_timezone = $config['timezone'];
		$this->_default_theme = $config['theme'];
		$this->_temp_dir = $config['temporary_directory'];

		/* Only set list rpp value if inherit config is set to true */
		if ($this->_table_pagination_rpp_list_inherit_config === true)
			$this->_table_pagination_rpp_list = $config['page_rows'];

		if ($this->_table_pagination_rpp_result_inherit_config === true)
			$this->_table_pagination_rpp_result = $config['page_rows'];


		/* Set default database */
		$this->load->database($this->_default_database);

		/* Set default locale (in the format xx_XX.CHARSET (eg: en_US.UTF-8) */
		setlocale(LC_ALL, $this->_default_locale . '.' . $this->_default_charset);

		/* Set the default timezone */
		date_default_timezone_set($this->_default_timezone);

		/* Check if we're under maintenance mode */
		if ($config['maintenance'] && !$this->security->im_admin())
			$this->response->code('503', NDPHP_LANG_MOD_MGMT_UNDER_MAINTENANCE, $this->_default_charset, !$this->request->is_ajax());

		/* Setup security settings for this user */
		$this->_security_perms = $this->security->perm_get($this->_session_data['user_id']);

		/* Setup VIEW table type if required */
		if ($this->_table_type_view === true) {
			if (!$this->_table_type_view_query)
				$this->response->code('500', NDPHP_LANG_MOD_UNDEFINED_CTRL_VIEW_QUERY, $this->_default_charset, !$this->request->is_ajax());

			/* Do not trust uWeb (keep a untrust level for the underlying layers): Re-check the safe chars for $GLOBALS['__controller'] */
			if (!$this->security->safe_names($GLOBALS['__controller'], $this->_security_safe_chars))
				$this->response->code('500', NDPHP_LANG_MOD_INVALID_CHARS_CTRL, $this->_default_charset, !$this->request->is_ajax());

			/* Check if any required views for this controller were already created. If not, create them and
			 * store this indication in the cache (if available)
			 */
			if (!$this->cache->get('s_cache_' . $GLOBALS['__controller'] . '_views_created')) {
				/* Disable prepared statements */
				$this->db->stmt_disable();

				/* Create database view */
				$this->db->query('CREATE OR REPLACE VIEW ' . $GLOBALS['__controller'] . ' AS ' . $this->_table_type_view_query);

				/* Check if there are any extra queries to be executed for this custom controller */
				if (count($this->_table_type_view_query_extra)) {
					foreach ($this->_table_type_view_query_extra as $tv_extra) {
						$this->db->query($tv_extra);
					}
				}

				/* Re-enable prepared statements */
				$this->db->stmt_enable();

				/* Cache view creation status */
				$this->cache->set('s_cache_' . $GLOBALS['__controller'] . '_views_created', true);
			}
		}

		/* Process charts */

		/* Load charts plugins */
		if ($this->config['plugins_enabled']) {
			foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/charts.php') as $plugin)
				include($plugin);
		}

		/* Charts hook */
		$this->_hook_charts();

		/* Load post plugins */
		if ($this->config['plugins_enabled']) {
			foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/construct_post.php') as $plugin)
				include($plugin);
		}

		/* Process scheduler entries if the scheduler is not set as external (which, in that case, will require a cron job) */
		if ($this->_scheduler['type'] != 'external') {
			$this->_load_module('scheduler', true);
			$this->scheduler->process();
		}
	}


	/** Threading / Worker handlers (Will only be used if PHP has threading enabled.) **/

	public function worker($thread) {
		/* Grant that the supplied parameter is an object. This will also filter remote calls to this method. */
		if (!is_object($thread))
			$this->response->code('500', NDPHP_LANG_MOD_INVALID_TYPE_NOT_OBJ, $this->config['default_charset'], !$this->request->is_ajax());

		/** BEGIN OF WORKER MAIN **/

		/** END OF WORKER MAIN **/
	}


	/** Custom loaders **/

	protected function _load_view($view_name, $data = NULL, $customizable = false, $return_data = false, $ctrl_override = NULL) {
		if ($customizable) {
			if (file_exists('application/views/themes/' . $this->config['default_theme'] . '/' . ($ctrl_override ? $ctrl_override : $this->config['name']) . '/' . $view_name . '.php')) {
				return $this->load->view('themes/' . $this->config['default_theme'] . '/' . ($ctrl_override ? $ctrl_override : $this->config['name']) . '/' . $view_name, $data, $return_data);
			} else {
				return $this->load->view('themes/' . $this->config['default_theme'] . '/' . '_default/' . $view_name, $data, $return_data);
			}
		} else {
			return $this->load->view('themes/' . $this->config['default_theme'] . '/' . $view_name, $data, $return_data);
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


	/** The main and public stuff **/

	public function index_ajax() {
		$this->list_body_ajax();
	}

	public function index() {
		redirect($this->config['name'] . '/list_default');
	}

	public function rel_get_options_filtered($selected_id, $selected_field, $target_field) {
		$this->db->from(substr($target_field, -3)); /* Selected field format is <table>_id, so we're removing the trailling _id */
		$this->db->where($selected_field, $selected_id); /* The single relationship shall exist in the target table */

		/* TODO: Call $this->get->fields() and filter $fields[$target]['options'] */

		/* TODO: Load the options.php view with the filtered options list ($fields[$target]['options']) */
	}

	public function rel_get_options($field, $relationship, $selected_id) {
		$fields = $this->get->fields();

		$data = array();

		$data['config'] = array();
		$data['config']['charset'] = $this->config['default_charset'];

		$data['view'] = array();
		$data['view']['field'] = $fields[$field];
		$data['view']['selected_id'] = $selected_id;
		$data['view']['relationship'] = $relationship;

		$this->_load_view('options', $data, true);
	}
	
	protected function groups_generic() {
		/* Security Permissions Check */
		if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_read, $this->config['name']))
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->config['default_charset'], !$this->request->is_ajax());

		/* Initialize $data variable */
		$data = array();

		/* Load enter plugins */
		if ($this->config['plugins_enabled']) {
			foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/groups_generic_enter.php') as $plugin)
				include($plugin);
		}

		/* Groups hook enter */
		$hook_enter_return = $this->_hook_groups_generic_enter($data);

		/* If logging is enabled, log this group read access */
		$this->logging->log(
			/* op         */ 'VIEW',
			/* table      */ $this->config['name'],
			/* field      */ 'GROUPS',
			/* entry_id   */ NULL,
			/* value_new  */ NULL,
			/* value_old  */ NULL,
			/* session_id */ $this->config['session_data']['sessions_id'],
			/* user_id    */ $this->config['session_data']['user_id'],
			/* log it?    */ $this->config['logging']
		);


		/* Get view title value */
		$title = NULL;

		if (isset($this->config['menu_entries_aliases'][$this->config['name']])) {
			$title = $this->config['menu_entries_aliases'][$this->config['name']] . $this->config['view_title_sep'] . NDPHP_LANG_MOD_OP_GROUPS;
		} else {
			$title = $this->config['viewhname'] . $this->config['view_title_sep'] . NDPHP_LANG_MOD_OP_GROUPS;
		}

		/* Get view description value */
		$description = NDPHP_LANG_MOD_OP_GROUPS . " " . $this->config['viewhname'];

		/* Setup basic view data */
		$data = array_merge_recursive($data, $this->get->view_data_generic($title, $description));

		/* Setup specific view data */
		$data['view']['fields'] = $this->get->fields(NULL, $this->config['hide_fields_groups']); /* _get_fields() uses a perm_read filter by default */
		$data['view']['links']['quick'] = $this->config['links_quick_modal_list'];
		$data['view']['links']['submenu'] = $this->config['links_submenu_body_groups'];
		$data['view']['links']['breadcrumb'] = $this->get->view_breadcrumb('groups', NDPHP_LANG_MOD_OP_GROUPS);

		/* Create groups list */
		$groups = array();

		/* Fetch table fields to look for single relationships */
		$table_fields = $this->get->table_fields($this->config['name']);

		foreach ($table_fields as $field) {
			if (in_array($field, $this->config['hide_groups']))
				continue;

			$group = array();

			if (substr($field, -3) == '_id') {
				/* This is a single relationship group */

				/* Get foreign table name */
				$group['table_name'] = substr($field, 0, -3);

				/* Ignore self-relationships */
				if ($group['table_name'] == $this->config['name'])
					continue;

				/* Check if we've perms to read the field */
				if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_read, $this->config['name'], $field))
					continue;

				/* Check if we've perms to read the foreign table */
				if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_read, $group['table_name']))
					continue;

				/* Check if there's a viewname alias set */
				if (isset($this->config['menu_entries_aliases'][$group['table_name']])) {
					$group['name'] = $this->config['menu_entries_aliases'][$group['table_name']];
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
		foreach ($this->get->tables() as $table) {
			if (substr($table, 0, 4) != 'rel_')
				continue;

			$rel_tables = $this->get->multiple_rel_table_names($table, $this->config['name']);

			$group = array();

			if (in_array($this->config['name'], $rel_tables)) {
				/* This is a multiple relationship */

				/* Get foreign table name */
				$group['table_name'] = array_pop(array_diff($rel_tables, array($this->config['name'])));

				/* Check if we've perms to read the foreign table */
				if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_read, $group['table_name']))
					continue;

				/* Check if there's a viewname alias set */
				if (isset($this->config['menu_entries_aliases'][$group['table_name']])) {
					$group['name'] = $this->config['menu_entries_aliases'][$group['table_name']];
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
		if ($this->config['plugins_enabled']) {
			foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/groups_generic_leave.php') as $plugin)
				include($plugin);
		}

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
		if ($this->config['json_replies'] === true) {
			$this->response->output($this->rest->json_list($data));
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
		/* NOTE:
		 *
		 * - For REST JSON calls, only $data['view']['result_array'] and $data['view']['total_items'] are required to be filled-
		 *
		 */

		/* Sanity checks */
		/* TODO: FIXME: NOTE: Negative $page values are being used by groups to fetch the full list of records...
		 * Before we grant that $page is equal or greater than 0, we need to redesigned the group handlers.
		 */
		//if ($page < 0)
		//	$page = 0; /* TODO: FIXME: Shall we send an error response instead of silentely change $page value? */

		/* Security Permissions Check */
		if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_read, $this->config['name']))
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->config['default_charset'], !$this->request->is_ajax());

		/* Setup ordering field if none was specified */
		if ($field == NULL) {
			if ($this->request->is_json() && $this->request->post_isset('_orderby') && $this->request->post('_orderby')) {
				$field = $this->request->post('_orderby');
			} else {
				$field = $this->config['table_field_order_list'];
			}
		}

		/* Grant that field contains only safe characters */
		if (!$this->security->safe_names($field, $this->config['security_safe_chars']))
			$this->response->code('400', NDPHP_LANG_MOD_INVALID_CHARS_FIELD, $this->config['default_charset'], !$this->request->is_ajax());

		/* Setup ordering */
		if ($order == NULL) {
			if ($this->request->is_json() && $this->request->post_isset('_ordering') && $this->request->post('_ordering')) {
				$order = $this->request->post('_ordering');
			} else {
				$order = $this->config['table_field_order_list_modifier'];
			}
		}

		/* Initialize $data */
		$data = array();

		/* Load enter plugins */
		if ($this->config['plugins_enabled']) {
			foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/list_generic_enter.php') as $plugin)
				include($plugin);
		}

		/* Hook handler (enter) */
		$hook_enter_return = $this->_hook_list_generic_enter($data, $field, $order, $page);

		/* Get view rendering data for non-JSON calls */
		if (!$this->request->is_json()) {
			/* Setup charts */
			$this->_load_module('charts', true);

			/* Get view title value */
			$title = NULL;

			if (isset($this->config['menu_entries_aliases'][$this->config['name']])) {
				$title = $this->config['menu_entries_aliases'][$this->config['name']] . $this->config['view_title_sep'] . NDPHP_LANG_MOD_OP_LIST;
			} else {
				$title = $this->config['viewhname'] . $this->config['view_title_sep'] . NDPHP_LANG_MOD_OP_LIST;
			}

			/* Get view description value */
			$description = NDPHP_LANG_MOD_OP_LIST . " " . $this->config['viewhname'];

			/* Setup basic view data */
			$data = array_merge_recursive($data, $this->get->view_data_generic($title, $description));

			/* Setup specific view data */
			$data['view']['links']['quick'] = $this->config['links_quick_modal_list'];
			$data['view']['links']['submenu'] = $this->config['links_submenu_body_list'];
			$data['view']['links']['breadcrumb'] = $this->get->view_breadcrumb('list', NDPHP_LANG_MOD_OP_LIST);

			$data['config']['charts']['total'] = $this->charts->count_charts();

			$data['config']['render']['images'] = $this->config['view_image_file_rendering'];
			$data['config']['render']['size'] = $this->config['view_image_file_rendering_size_list'];
			$data['config']['render']['ext'] = $this->config['view_image_file_rendering_ext'];

			$data['config']['choices_class'] = $this->config['rel_choice_table_row_class'];

			$data['view']['fields'] = $this->get->fields(NULL, $this->config['hide_fields_list']); /* $this->get->fields() uses a perm_read filter by default */

			/* Resolve fields */
			$this->field->resolve($data['view']['fields']);

			/* Hidden fields */
			$data['config']['hidden_fields'] = $this->config['hide_fields_list'];

			/* Switch order on each call */
			if ($order == "asc")
				$data['config']['order'] = "desc";
			else
				$data['config']['order'] = "asc";
			
			$data['config']['order_by'] = $field;
		} else {
			/* REST calls using that set '_show' key are able to determine which fields will be present in the list results */
			if (is_array($this->request->post('_show')) && count($this->request->post('_show'))) {
				/* Set the selected fields */
				$selected = $this->request->post('_show');

				/* 'id' must always be present */
				if (!in_array($selected, 'id'))
					array_push($selected, 'id');

				/* Fetch fields */
				$data['view']['fields'] = $this->get->fields(NULL);

				/* Resolve fields */
				$this->field->resolve($data['view']['fields'], $selected);
			} else {
				/* Fetch fields */
				$data['view']['fields'] = $this->get->fields(NULL, $this->config['hide_fields_list']);

				/* Use default controller settings for field resolve */
				$this->field->resolve($data['view']['fields']);
			}
		}

		/* Order the results as requested from the url segment ('asc' or 'desc') */
		$this->db->order_by($field, $order);

		/* Set FROM in order to get a clean get_compiled_select_str() */
		$this->db->from($this->config['name']);

		/* Filter table rows, if applicable */
		$this->filter->table_row_apply($this->config['name'], $this->security->perm_read);

		/* If this is a REST call, do not limit the results unless it is explicitly requested (default is to display all) */
		if ($this->request->is_json()) {
			/* Check if there is a limit and/or offset defined */
			if ($this->request->post_isset('_limit')) {
				/* Validate limit value (must be greater than zero */
				if ($this->request->post('_limit') <= 0)
					$this->response->code('400', NDPHP_LANG_MOD_INVALID_LIMIT_VALUE, $this->config['default_charset'], !$this->request->is_ajax());

				/* If set, validate offset value (must be zero or greater) */
				if ($this->request->post_isset('_offset') && ($this->request->post('_offset') < 0))
					$this->response->code('400', NDPHP_LANG_MOD_INVALID_OFFSET_VALUE, $this->config['default_charset'], !$this->request->is_ajax());

				/* Set limit (and, if set, the offset) */
				$this->db->limit($this->request->post('_limit') > $this->config['json_result_hard_limit'] ? $this->config['json_result_hard_limit'] : $this->request->post('_limit'), $this->request->post_isset('_offset') ? $this->request->post('_offset') : 0);
			} else {
				/* If no limit was explicitly set, use the default */
				$this->db->limit($this->config['json_result_hard_limit']);
			}
		} else {
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

			if ($page >= 0) {
				/* Limit results to the number of rows per page (pagination) */
				$this->db->limit($this->config['table_pagination_rpp_list'], $page);
			}
		}

		/* Hook filter: Apply filters, if any */
		$hook_enter_return = $this->_hook_list_generic_filter($data, $field, $order, $page, $hook_enter_return);

		/* Check if total number of matches is required to be computed.
		 * If this isn't a REST JSON request, then total number of matches is always computed (for pagination),
		 * otherwise, calculated total number of matches only if the REST JSON request explicitly asks for it.
		 */
		if (!$this->request->is_json()) {
			$totals = true;
		} else {
			/* Check if the REST JSON request explicitly requests total number of matches */
			if ($this->request->post_isset('_totals') && $this->request->post('_totals')) {
				$totals = true;
			} else {
				$totals = false;
			}
		}

		/* Calculate found rows, if required */
		if ($totals)
			$this->db->calc_found_rows();

		/* Store result array under view data array */
		$data['view']['result_array'] = $this->field->value_mangle($data['view']['fields'], $this->db->get());

		/* Pagination */
		if ($page >= 0) {
			if ($this->request->is_json()) {
				/* REST JSON calls only require total_items to be set */
				$data['view']['total_items'] = $totals ? $this->db->found_rows() : 0;
			} else {
				$pagcfg['page'] = ($page / $this->config['table_pagination_rpp_list']) + 1; // $page is actually the number of the first row of the page
				$pagcfg['base_url'] = base_url() . 'index.php/' . $this->config['name'] . '/list_default/' . $field . '/' . $order . '/@ROW_NR@';
				$pagcfg['onclick'] = 'ndphp.ajax.load_data_ordered_list(event, \'' . $this->config['name'] . '\', \'' . $field . '\', \'' . $order . '\', \'@ROW_NR@\');';
				/* Fetch found rows, if required */
				if ($totals) {
					$pagcfg['total_rows'] = $this->db->found_rows();
				} else {
					$pagcfg['total_rows'] = 0;
				}
				$pagcfg['per_page'] = $this->config['table_pagination_rpp_list'];

				/* Initialize pagination */
				$this->pagination->initialize($pagcfg);
				$data['view']['links']['pagination'] = $this->pagination->create_links();
				$data['view']['page'] = $page;

				/* Set view data for pagination offsets and total items*/
				$total_items_from = ($pagcfg['per_page'] * ($page / $pagcfg['per_page']));
				$total_items_from += $pagcfg['total_rows'] ? 1 : 0;
				$total_items_to = (($pagcfg['per_page'] * ($page / $pagcfg['per_page'])) + $pagcfg['per_page']);
				$total_items_to = ($total_items_to <= $pagcfg['total_rows'] ? $total_items_to : $pagcfg['total_rows']);
				$data['view']['total_items_from'] = $total_items_from;
				$data['view']['total_items_to'] = $total_items_to;
				$data['view']['total_items'] = $pagcfg['total_rows'];
			}
		}

		/* Hook handler (leave) */
		$this->_hook_list_generic_leave($data, $field, $order, $page, $hook_enter_return);

		/* Load leave plugins */
		if ($this->config['plugins_enabled']) {
			foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/list_generic_leave.php') as $plugin)
				include($plugin);
		}

		/* If logging is enabled, log this listing request */
		if ($this->request->is_json()) {
			$this->logging->log(
				/* op         */ 'LIST',
				/* table      */ $this->config['name'],
				/* field      */ 'LIMIT / OFFSET / ORDERBY / ORDERING',
				/* entry_id   */ ($this->request->post_isset('_limit') ? $this->request->post('_limit') : $this->config['json_result_hard_limit']) . ' / ' .
								 ($this->request->post_isset('_offset') ? $this->request->post('_offset') : '0') . ' / ' .
								 $field . ' / ' .
								 $order,
				/* value_new  */ NULL,
				/* value_old  */ NULL,
				/* session_id */ $this->config['session_data']['sessions_id'],
				/* user_id    */ $this->config['session_data']['user_id'],
				/* log it?    */ $this->config['logging']
			);
		} else {
			$this->logging->log(
				/* op         */ 'LIST',
				/* table      */ $this->config['name'],
				/* field      */ 'LIMIT / OFFSET / ORDERBY / ORDERING',
				/* entry_id   */ $page . ' / ' .
								 $this->config['table_pagination_rpp_list'] . ' / ' .
								 $field . ' / ' .
								 $order,
				/* value_new  */ NULL,
				/* value_old  */ NULL,
				/* session_id */ $this->config['session_data']['sessions_id'],
				/* user_id    */ $this->config['session_data']['user_id'],
				/* log it?    */ $this->config['logging']
			);
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
		if ($this->config['json_replies'] === true) {
			$this->response->output($this->rest->json_list($data));
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

		/* If logging is enabled, log this group listing request */
		$this->logging->log(
			/* op         */ 'LIST',
			/* table      */ $this->config['name'],
			/* field      */ 'GROUPS',
			/* entry_id   */ $grouping_field,
			/* value_new  */ NULL,
			/* value_old  */ NULL,
			/* session_id */ $this->config['session_data']['sessions_id'],
			/* user_id    */ $this->config['session_data']['user_id'],
			/* log it?    */ $this->config['logging']
		);


		/* Retrieve the list generic data */
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
					if (in_array($opt_val, explode($this->config['rel_group_concat_sep'], $row[$grouping_field]))) {
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
		if ($this->config['json_replies'] === true) {
			$this->response->output($this->rest->json_list($data));
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
		$data = $this->get->view_data_generic();

		$data['view']['hname'] = NDPHP_LANG_MOD_OP_IMPORT_CSV;
		$data['config']['modalbox'] = true;

		/* Load Views */
		$this->_load_view('import_csv', $data, true);
	}

	public function import($type = 'csv') {
		/* Currently, only csv imports are supported */
		if ($type != 'csv')
			$this->response->code('403', NDPHP_LANG_MOD_INVALID_REQUEST, $this->config['default_charset'], !$this->request->is_ajax());

		/* Some sanity checks first */
		if (!in_array($this->request->post('import_csv_sep'), array(',', ';')))
			$this->response->code('400', NDPHP_LANG_MOD_INVALID_POST_DATA, $this->config['default_charset'], !$this->request->is_ajax());

		if (!in_array($this->request->post('import_csv_delim'), array('"', '\'')))
			$this->response->code('400', NDPHP_LANG_MOD_INVALID_POST_DATA, $this->config['default_charset'], !$this->request->is_ajax());

		if (!in_array($this->request->post('import_csv_esc'), array('\\')))
			$this->response->code('400', NDPHP_LANG_MOD_INVALID_POST_DATA, $this->config['default_charset'], !$this->request->is_ajax());

		if (!in_array($this->request->post('import_csv_rel_type'), array('value', 'id')))
			$this->response->code('400', NDPHP_LANG_MOD_INVALID_POST_DATA, $this->config['default_charset'], !$this->request->is_ajax());

		/* Craft CSV file destination path */
		$dest_path = SYSTEM_BASE_DIR . '/uploads/import/' . $this->config['session_data']['user_id'] . '/' . $this->config['name'];

		/* Create directory if it doesn't exist */
		if (!file_exists($dest_path) && mkdir($dest_path, 0750, true) === false)
			$this->response->code('500', NDPHP_LANG_MOD_UNABLE_FILE_UPLOAD . ' "' . $_FILES[$field]['name'] . '": ' . NDPHP_LANG_MOD_UNABLE_CREATE_DIRECTORY, $this->config['default_charset'], !$this->request->is_ajax());

		/* Pre-process the CSV file (or direct input), creating a local CSV file */
		if (isset($_FILES['import_csv_file'])) {
			$field = 'import_csv_file';

			if (!isset($_FILES[$field]['error']) || is_array($_FILES[$field]['error']))
				$this->response->code('500', NDPHP_LANG_MOD_UNABLE_FILE_UPLOAD . ' "' . $_FILES[$field]['name'] . '": ' . NDPHP_LANG_MOD_INVALID_PARAMETERS, $this->config['default_charset'], !$this->request->is_ajax());

			/* Grant that there are no errors */
			if ($_FILES[$field]['error'] > 0)
				$this->response->code('403', NDPHP_LANG_MOD_UNABLE_FILE_UPLOAD . ' "' . $_FILES[$field]['name'] . '": ' . error_upload_file($_FILES[$field]['error']), $this->config['default_charset'], !$this->request->is_ajax());

			/* Validate file size (This is a fallback for php settings) */
			if ($_FILES[$field]['size'] > $this->config['upload_file_max_size'])
				$this->response->code('403', NDPHP_LANG_MOD_UNABLE_FILE_UPLOAD . ' "' . $_FILES[$field]['name'] . '": ' . NDPHP_LANG_MOD_INVALID_SIZE_TOO_BIG, $this->config['default_charset'], !$this->request->is_ajax());

			/* Compute file hash */
			$file_hash = openssl_digest($_FILES[$field]['name'], 'sha256');

			if (move_uploaded_file($_FILES[$field]['tmp_name'], $dest_path . '/' . $file_hash) === false)
				$this->response->code('403', NDPHP_LANG_MOD_UNABLE_FILE_COPY . ' "' . $_FILES[$field]['name'] . '"', $this->config['default_charset'], !$this->request->is_ajax());

			/* Open CSV file */
			if (($fp_csv = fopen($dest_path . '/' . $file_hash, 'r')) === false)
				$this->response->code('500', NDPHP_LANG_MOD_UNABLE_FILE_OPEN_READ . ' "' . $dest_path . '/' . $file_hash . '"', $this->config['default_charset'], !$this->request->is_ajax());
		} else if ($this->request->post('import_csv_text')) {
			/* Create a temporary file hash */
			$file_hash = openssl_digest($dest_path . mt_rand(1000000, 9999999), 'sha256');

			/* Create a temporary CSV file */
			if (($fp_csv = fopen($dest_path . '/' . $file_hash, 'w')) === false)
				$this->response->code('500', NDPHP_LANG_MOD_UNABLE_FILE_OPEN_WRITE . ' "' . $dest_path . '/' . $file_hash . '"', $this->config['default_charset'], !$this->request->is_ajax());

			/* Write CSV contents to file */
			if (fwrite($fp_csv, $this->request->post('import_csv_text')) === false)
				$this->response->code('500', NDPHP_LANG_MOD_UNABLE_FILE_WRITE . ' "' . $dest_path . '/' . $file_hash . '"', $this->config['default_charset'], !$this->request->is_ajax());

			/* Close the CSV file so we can re-open it as read-only */
			fclose($fp_csv);

			/* Re-open CSV file as read-only */
			if (($fp_csv = fopen($dest_path . '/' . $file_hash, 'r')) === false)
				$this->response->code('500', NDPHP_LANG_MOD_UNABLE_FILE_OPEN_READ . ' "' . $dest_path . '/' . $file_hash . '"', $this->config['default_charset'], !$this->request->is_ajax());
		} else {
			$this->response->code('500', NDPHP_LANG_MOD_INVALID_DATA_FOUND, $this->config['default_charset'], !$this->request->is_ajax());
		}

		
		/** Process the CSV stream **/

		/* Fetch the CSV header */
		$header = fgetcsv($fp_csv, 0, $this->request->post('import_csv_sep'), $this->request->post('import_csv_delim'), $this->request->post('import_csv_esc'));

		/* Set the current line of file */
		$line = 1;

		/* Grant that CSV entry is valid and EOF was not reached */
		if ($header === false || $header === NULL)
			$this->response->code('500', NDPHP_LANG_MOD_INVALID_DATA_FOUND . '. #2', $this->config['default_charset'], !$this->request->is_ajax());

		/* Convert CSV headers to controller field names */
		$fields = $this->get->fields();
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
				$this->response->code('400', NDPHP_LANG_MOD_UNABLE_MATCH_CSV_FIELD_CTRL, $this->config['default_charset'], !$this->request->is_ajax());
		}

		/* Assign the resolved fields array to $header */
		$header = $header_resolve;

		/* Initialize transaction */
		$this->db->trans_begin();

		/* Fetch CSV rows */
		while (true) {
			$row = fgetcsv($fp_csv, 0, $this->request->post('import_csv_sep'), $this->request->post('import_csv_delim'), $this->request->post('import_csv_esc'));

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
					$rel_values = explode($this->config['rel_group_concat_sep'], $row[$i]);

					/* Get foreign table name */
					$foreign_table = array_pop(array_diff($this->get->multiple_rel_table_names($header[$i], $this->config['name']), array($this->config['name'])));

					/* Get foreign table fields list */
					$ftable_fields = $this->get->table_fields($foreign_table);

					/* Initialize multiple relationship values array */
					$rel[$header[$i]] = array();

					foreach ($rel_values as $rel_value) {
						$rel_value_id = NULL;

						/* If there is a separator set, we need to compare the CSV value with the resulting value of concatenated fields */
						if (isset($this->config['rel_table_fields_config'][$foreign_table])) {
							if ($this->config['rel_table_fields_config'][$foreign_table][1] !== NULL) {
								$ft_fields = array();

								/* Gather the name of the fields to be concatenated */
								foreach ($this->config['rel_table_fields_config'][$foreign_table][2] as $field_nr)
									array_push($ft_fields, $ftable_fields[$field_nr]);

								/* Fetch the id value */
								$this->db->select('id');
								$this->db->from($foreign_table);
								$this->db->where('CONCAT_WS(\'' . $this->config['rel_table_fields_config'][$foreign_table][1] . '\', `' . implode('`,`', $ft_fields) . '`)', $rel_value, false);
								$q = $this->db->get();
							} else {
								/* There is no field concatenation for this relationship.
								 * However, we need to check which field is used by default to compare its value
								 */

								/* Fetch the id value */
								$this->db->select('id');
								$this->db->from($foreign_table);
								$this->db->where($ftable_fields[$this->config['rel_table_fields_config'][$foreign_table][2][0]], $rel_value);
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

							$this->response->code('400', NDPHP_LANG_MOD_UNABLE_MATCH_REL_VALUE_FK . ' (CSV ' . NDPHP_LANG_MOD_WORD_LINE . ': ' . $line . ', ' . NDPHP_LANG_MOD_WORD_COLUMN . ': ' . $i . ')', $this->config['default_charset'], !$this->request->is_ajax());
						}

						array_push($rel[$header[$i]], $rel_value_id);
					}

					/* Multiple relationships aren't regular fields, so they won't be placed on $entry array */
					continue;
				} else if (substr($header[$i], -3) == '_id' && $this->request->post('import_csv_rel_type') == 'value') {
					/* Resolve $row[$i] value to integer id by fetching it from foreign table */

					/* Get foreign table name */
					$foreign_table = substr($header[$i], 0, -3);

					/* Get foreign table fields list */
					$ftable_fields = $this->get->table_fields($foreign_table);
					
					/* If there is a separator set, we need to compare the CSV value with the resulting value of concatenated fields */
					if (isset($this->config['rel_table_fields_config'][$foreign_table])) {
						if ($this->config['rel_table_fields_config'][$foreign_table][1] !== NULL) {
							$ft_fields = array();

							/* Gather the name of the fields to be concatenated */
							foreach ($this->config['rel_table_fields_config'][$foreign_table][2] as $field_nr)
								array_push($ft_fields, $ftable_fields[$field_nr]);

							/* Fetch the id value */
							$this->db->select('id');
							$this->db->from($foreign_table);
							$this->db->where('CONCAT_WS(\'' . $this->config['rel_table_fields_config'][$foreign_table][1] . '\', `' . implode('`,`', $ft_fields) . '`)', $row[$i], false);
							$q = $this->db->get();
						} else {
							/* There is no field concatenation for this relationship.
							 * However, we need to check which field is used by default to compare its value
							 */

							/* Fetch the id value */
							$this->db->select('id');
							$this->db->from($foreign_table);
							$this->db->where($ftable_fields[$this->config['rel_table_fields_config'][$foreign_table][2][0]], $row[$i]);
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

						$this->response->code('400', NDPHP_LANG_MOD_UNABLE_MATCH_REL_VALUE_FK . ' (CSV ' . NDPHP_LANG_MOD_WORD_LINE . ': ' . $line . ', ' . NDPHP_LANG_MOD_WORD_COLUMN . ': ' . $i . ')', $this->config['default_charset'], !$this->request->is_ajax());
					}
				}

				/* NOTE: If $_POST['import_csv_rel_type'] == 'id', then it is expected that $row[$i] already contains the ID value */

				/* Assign K/V pair to entry array */
				$entry[$header[$i]] = $row[$i];
			}

			/* Insert the entry into database */
			$this->db->insert($this->config['name'], $entry);

			/* Fetch the last inserted id */
			$last_id = $this->db->last_insert_id();

			/* Insert multiple relationships */
			foreach ($rel as $rel_table => $values) {
				foreach ($rel[$rel_table] as $fid) {
					$this->db->insert($rel_table, array(
						$this->config['name'] . '_id' => $last_id,
						array_pop(array_diff($this->get->multiple_rel_table_names($header[$i], $this->config['name']), array($this->config['name']))) . '_id' => $fid
					));
				}
			}
		}

		/* Close the CSV file handler */
		fclose($fp_csv);

		/* Check if transaction was successful */
		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			$this->response->code('500', NDPHP_LANG_MOD_FAILED_TRANSACTION, $this->config['default_charset'], !$this->request->is_ajax());
		}

		/* Commit transaction */
		$this->db->trans_commit();

		/* Unlink the csv file */
		unlink($dest_path . '/' . $file_hash);
	}

	public function search_save() {
		$data = $this->get->view_data_generic();

		$data['view']['hname'] = NDPHP_LANG_MOD_OP_SAVE_SEARCH;
		$data['config']['modalbox'] = true;

		/* Load Views */
		$this->_load_view('search_save', $data, true);
	}

	public function search_save_insert() {
		$this->db->trans_begin();

		$this->db->insert('_saved_searches', array(
			'search_name'	=> $this->request->post('search_save_name'),
			'description'	=> $this->request->post('search_save_description'),
			'controller'	=> $this->config['name'],
			'result_query'	=> $this->request->post('search_save_result_query'),
			'users_id'		=> $this->config['session_data']['user_id']
		));

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			$this->response->code('500', NDPHP_LANG_MOD_UNABLE_STORE_SAVED_SEARCH, $this->config['default_charset'], !$this->request->is_ajax());
		} else {
			$this->db->trans_commit();
		}
	}

	public function search_save_delete($search_saved_id) {
		$this->db->trans_begin();

		/* Check if the saved search exists and/or we've permissions to delete the saved search */
		$this->db->select('users_id');
		$this->db->from('_saved_searches');
		$this->db->where('controller', $this->config['name']);
		$this->db->where('users_id', $this->config['session_data']['user_id']);
		$q = $this->db->get();

		if (!$q->num_rows()) {
			$this->db->trans_rollback();
			$this->response->code('400', NDPHP_LANG_MOD_ACCESS_SAVED_SEARCH_DELETE, $this->config['default_charset'], !$this->request->is_ajax()); /* Keep the reason ambiguous */
		}

		$this->db->delete('_saved_searches', array('id' => $search_saved_id));

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			$this->response->code('500', NDPHP_LANG_MOD_UNABLE_DELETE_SAVED_SEARCH, $this->config['default_charset'], !$this->request->is_ajax());
		} else {
			$this->db->trans_commit();
		}
	}

	protected function search_generic($advanced = true) {
		/* Security Permissions Check */
		if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_read, $this->config['name']))
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->config['default_charset'], !$this->request->is_ajax());

		/* If logging is enabled, log this search request */
		$this->logging->log(
			/* op         */ 'SEARCH',
			/* table      */ $this->config['name'],
			/* field      */ NULL,
			/* entry_id   */ NULL,
			/* value_new  */ NULL,
			/* value_old  */ NULL,
			/* session_id */ $this->config['session_data']['sessions_id'],
			/* user_id    */ $this->config['session_data']['user_id'],
			/* log it?    */ $this->config['logging']
		);


		/* Initialize $data */
		$data = array();

		/* Load enter plugins */
		if ($this->config['plugins_enabled']) {
			foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/search_generic_enter.php') as $plugin)
				include($plugin);
		}

		/* Hook handler (enter) */
		$hook_enter_return = $this->_hook_search_generic_enter($data, $advanced);

		/* Get view title */
		$title = NULL;

		if (isset($this->config['menu_entries_aliases'][$this->config['name']])) {
			$title = $this->config['menu_entries_aliases'][$this->config['name']] . $this->config['view_title_sep'] . NDPHP_LANG_MOD_OP_SEARCH;
		} else {
			$title = $this->config['viewhname'] . $this->config['view_title_sep'] . NDPHP_LANG_MOD_OP_SEARCH;
		}

		/* Get view description value */
		$description = NDPHP_LANG_MOD_OP_SEARCH . " " . $this->config['viewhname'];

		/* Setup basic view data */
		$data = array_merge_recursive($data, $this->get->view_data_generic($title, $description));

		/* Setup specific view data */
		$data['view']['fields'] = $this->filter->fields($this->config['security_perms'], $this->security->perm_search, $this->get->fields(NULL, $this->config['hide_fields_search'])); /* $this->get->fields() uses a perm_read filter by default */
		$data['view']['links']['submenu'] = $this->config['links_submenu_body_search'];
		$data['view']['links']['breadcrumb'] = $this->get->view_breadcrumb('search', NDPHP_LANG_MOD_OP_SEARCH);
		$data['view']['saved_searches'] = $this->get->saved_searches();
		
		/* Hidden fields */
		$data['config']['hidden_fields'] = $this->config['hide_fields_search'];

		/* Hook handler (leave) */
		$this->_hook_search_generic_leave($data, $advanced, $hook_enter_return);

		/* Load leave plugins */
		if ($this->config['plugins_enabled']) {
			foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/search_generic_leave.php') as $plugin)
				include($plugin);
		}

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
		/* Sanity checks */
		/* TODO: FIXME: NOTE: Negative $page values are being used by groups to fetch the full list of records...
		 * Before we grant that $page is equal or greater than 0, we need to redesigned the group handlers.
		 */
		//if ($page < 0)
		//	$page = 0; /* TODO: FIXME: Shall we send an error response instead of silentely change $page value? */

		/* Security Permissions Check */
		if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_read, $this->config['name']))
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->config['default_charset'], !$this->request->is_ajax());

		if ($order_field == NULL) {
			if ($this->request->is_json() && $this->request->post_isset('_orderby') && $this->request->post('_orderby')) {
				$order_field = $this->request->post('_orderby');
			} else {
				$order_field = $this->config['table_field_order_result'];
			}
		}

		if (!$this->security->safe_names($order_field, $this->config['security_safe_chars']))
			$this->response->code('400', NDPHP_LANG_MOD_INVALID_CHARS_FIELD_ORDER, $this->config['default_charset'], !$this->request->is_ajax());

		if ($order_type == NULL) {
			if ($this->request->is_json() && $this->request->post_isset('_ordering') && $this->request->post('_ordering')) {
				$order_type = $this->request->post('_ordering');
			} else {
				$order_type = $this->config['table_field_order_result_modifier'];
			}
		}

		/* Initialize $data */
		$data = array();

		/* Load enter plugins */
		if ($this->config['plugins_enabled']) {
			foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/result_generic_enter.php') as $plugin)
				include($plugin);
		}

		/* Hook handler (enter) */
		$hook_enter_return = $this->_hook_result_generic_enter($data, $type, $result_query, $order_field, $order_type, $page);

		/* Setup charts, if this isn't a REST JSON request */
		if (!$this->request->is_json())
			$this->_load_module('charts', true);

		/* Get view title */
		$title = NULL;

		if (isset($this->config['menu_entries_aliases'][$this->config['name']])) {
			$title = $this->config['menu_entries_aliases'][$this->config['name']] . $this->config['view_title_sep'] . NDPHP_LANG_MOD_OP_RESULT;
		} else {
			$title = $this->config['viewhname'] . $this->config['view_title_sep'] . NDPHP_LANG_MOD_OP_RESULT;
		}

		/* Get view description value */
		$description = NDPHP_LANG_MOD_OP_RESULT . " " . $this->config['viewhname'];

		/* Setup basic view data */
		$data = array_merge_recursive($data, $this->get->view_data_generic($title, $description));

		/* Setup specific view data */
		$data['view']['links'] = array();
		$data['view']['links']['quick'] = $this->config['links_quick_modal_result'];
		$data['view']['links']['submenu'] = $this->config['links_submenu_body_result'];

		if (!$this->request->is_json())
			$data['config']['charts']['total'] = $this->charts->count_charts();

		$data['config']['render'] = array();
		$data['config']['render']['images'] = $this->config['view_image_file_rendering'];
		$data['config']['render']['size'] = $this->config['view_image_file_rendering_size_list'];
		$data['config']['render']['ext'] = $this->config['view_image_file_rendering_ext'];

		$data['config']['choices_class'] = $this->config['rel_choice_table_row_class'];

		/* Check if the search value is a NDSL query and convert the result parameters accordingly */
		if ($this->request->post_isset('search_value') && $this->search->is_ndsl($this->request->post('search_value'))) {
			/* Convert the NDSL query to an advanced search context */
			$nadv = $this->search->ndsl_to_advsearch($this->request->post('search_value'));

			/* Check if conversion was successful by checking if $nadv !== false. If it failed, throw some error */
			if ($nadv === false)
				$this->response->code('400', $this->search->get_result_error(), $this->config['default_charset'], !$this->request->is_ajax());

			/* Updadte the view search_value context */
			$data['view']['search_value'] = $this->request->post('search_value');

			/* Unset POST data */
			$this->request->post_unset('search_value');

			/* If the distinct property was set in the request data, add it to $nadv */
			if ($this->request->post_isset('_distinct'))
				$nadv['_distinct'] = $this->request->post('_distinct');

			/* If a limit was set in the request data, add it to $nadv */
			if ($this->request->post_isset('_limit'))
				$nadv['_limit'] = $this->request->post('_limit');

			/* If an offset was set in the request data, add it to $nadv */
			if ($this->request->post_isset('_offset'))
				$nadv['_offset'] = $this->request->post('_offset');

			/* If a total match count was set in the request data, add it to $nadv */
			if ($this->request->post_isset('_totals'))
				$nadv['_totals'] = $this->request->post('_totals');

			/* Set the new POST data with the $nadv context */
			$this->request->post_set_all($nadv);

			/* Change search type to advanced */
			$type = 'advanced';

			/** NOTE: When using NDSL, hidden fields shall not be enforced and should be controlled by the request **/

			/* FIXME: Avoid using 2 calls to $this->get->fields() ... Use an unfiltered $this->get->fields() and generate two filtered lists from it */
			$ftypes = $this->get->fields(NULL);
			$ftypes_result = $this->get->fields(NULL);

			/* Hidden fields */
			$data['config']['hidden_fields'] = array();
		} else if (($this->config['json_replies'] === true) && is_array($this->request->post('_show')) && count($this->request->post('_show'))) {
			/* REST calls with '_show' modifier set are allowed to set the fields to be shown in the result */

			/* FIXME: Avoid using 2 calls to $this->get->fields() ... Use an unfiltered $this->get->fields() and generate two filtered lists from it */
			$ftypes = $this->get->fields(NULL);
			$ftypes_result = $this->get->fields(NULL);

			/* Hidden fields */
			$data['config']['hidden_fields'] = array();
		} else {
			/* FIXME: Avoid using 2 calls to $this->get->fields() ... Use an unfiltered $this->get->fields() and generate two filtered lists from it */
			$ftypes = $this->get->fields(NULL, $this->config['hide_fields_search']); /* $this->get->fields() uses a perm_read filter by default */
			$ftypes_result = $this->get->fields(NULL, $this->config['hide_fields_result']);

			/* Hidden fields */
			$data['config']['hidden_fields'] = $this->config['hide_fields_result'];
		}

		/* Validate search type */
		if ($type == 'basic') {
			/* If we're comming from a basic search ... */
			foreach ($ftypes as $field => $ftype) {
				/* Ignore separators */
				if ($ftype[$field]['type'] == 'separator')
					continue;

				/* Check permissions */
				if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_search, $this->config['name'], $field))
					continue;

				/* Search mixed relationships */
				if ($ftypes[$field]['type'] == 'mixed') {
					/* Fetch mixed table fields */
					$mt_fields = $this->get->table_fields($field);

					/* Join the mixed table to the query */
					$this->db->join($field, '`' . $this->config['name'] . '`.`id` = `' . $field . '`.`' . $this->config['name'] . '_id`', 'left');

					/* Search for mixed table content matches */
					foreach ($mt_fields as $mixed_field) {
						/* Ignore mixed id */
						if ($mixed_field == 'id')
							continue;

						/* Ignore single relationship field referencing to this table */
						if ($mixed_field == ($this->config['name'] . '_id'))
							continue;

						/* Get the mixed foreign table */
						$mixed_foreign_table = array_pop(array_diff($this->get->mixed_rel_table_names($field, $this->config['name']), array($this->config['name'])));

						/* Ignore single relationship field referencing to foreign table */
						if ($mixed_field == ($mixed_foreign_table . '_id'))
							continue;

						/* Check if we've permissions to read the foreign field on the foreign table */
						if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_read, $mixed_foreign_table, $mixed_field))
							continue;

						/* Check if we've permissions to search the foreign field on the foreign table */
						if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_search, $mixed_foreign_table, $mixed_field))
							continue;

						/* FIXME: Currently we only support string matching (partial date and datetime values are not yet implemented) */
						$this->db->or_like($field . '.' . $mixed_field, '%' . $this->request->post('search_value') . '%');
					}
				} else if (($ftype['input_type'] == 'text') || ($ftype['input_type'] == 'textarea') || 
						($ftype['input_type'] == 'timer') || ($ftype['input_type'] == 'file')) {
					if ($ftype['type'] == 'date') {
						/* If field type is date, compare as it was an integer.
						 * Also use YEAR(), MONTH() and DAY() functions to search for each
						 * date field independently.
						 */
						if (is_numeric($this->request->post('search_value'))) {
							$this->db->or_like($this->field->unambig($field, $ftypes), '%' . $this->request->post('search_value') . '%');
							$this->db->or_where('YEAR(' . $this->field->unambig($field, $ftypes) . ')', $this->request->post('search_value'), false);
							$this->db->or_where('MONTH(' . $this->field->unambig($field, $ftypes) . ')', $this->request->post('search_value'), false);
							$this->db->or_where('DAY(' . $this->field->unambig($field, $ftypes) . ')', $this->request->post('search_value'), false);
						} else {
							$this->db->or_like($this->field->unambig($field, $ftypes), '%' . $this->request->post('search_value') . '%');
							$this->db->or_where($this->field->unambig($field, $ftypes), $this->request->post('search_value'));
						}
					} else if ($ftype['type'] == 'time') {
						if (is_numeric($this->request->post('search_value'))) {
							$this->db->or_like($this->field->unambig($field, $ftypes), '%' . $this->request->post('search_value') . '%');
							$this->db->or_where('HOUR(' . $this->field->unambig($field, $ftypes) . ')', $this->request->post('search_value'), false);
							$this->db->or_where('MINUTE(' . $this->field->unambig($field, $ftypes) . ')', $this->request->post('search_value'), false);
							$this->db->or_where('SECOND(' . $this->field->unambig($field, $ftypes) . ')', $this->request->post('search_value'), false);
						} else {
							$this->db->or_like($this->field->unambig($field, $ftypes), '%' . $this->request->post('search_value') . '%');
							$this->db->or_where($this->field->unambig($field, $ftypes), $this->request->post('search_value'));
						}
					} else if ($ftype['type'] == 'datetime') {
						/* Use CONVERT_TZ() SQL function on WHERE clause to correctly setup the user timezone */
						if (is_numeric($this->request->post('search_value'))) {
							$this->db->or_like('CONVERT_TZ(' . $this->field->unambig($field, $ftypes) . ', \'' . $this->config['default_timezone'] . '\', \'' . $this->config['session_data']['timezone'] . '\')', '%' . $this->request->post('search_value') . '%', false);
							$this->db->or_where('HOUR(CONVERT_TZ(' . $this->field->unambig($field, $ftypes) . ', \'' . $this->config['default_timezone'] . '\', \'' . $this->config['session_data']['timezone'] . '\'))', $this->request->post('search_value'), false);
							$this->db->or_where('MINUTE(CONVERT_TZ(' . $this->field->unambig($field, $ftypes) . ', \'' . $this->config['default_timezone'] . '\', \'' . $this->config['session_data']['timezone'] . '\'))', $this->request->post('search_value'), false);
							$this->db->or_where('SECOND(CONVERT_TZ(' . $this->field->unambig($field, $ftypes) . ', \'' . $this->config['default_timezone'] . '\', \'' . $this->config['session_data']['timezone'] . '\'))', $this->request->post('search_value'), false);
							$this->db->or_where('YEAR(CONVERT_TZ(' . $this->field->unambig($field, $ftypes) . ', \'' . $this->config['default_timezone'] . '\', \'' . $this->config['session_data']['timezone'] . '\'))', $this->request->post('search_value'), false);
							$this->db->or_where('MONTH(CONVERT_TZ(' . $this->field->unambig($field, $ftypes) . ', \'' . $this->config['default_timezone'] . '\', \'' . $this->config['session_data']['timezone'] . '\'))', $this->request->post('search_value'), false);
							$this->db->or_where('DAY(CONVERT_TZ(' . $this->field->unambig($field, $ftypes) . ', \'' . $this->config['default_timezone'] . '\', \'' . $this->config['session_data']['timezone'] . '\'))', $this->request->post('search_value'), false);
						} else {
							$this->db->or_like('CONVERT_TZ(' . $this->field->unambig($field, $ftypes) . ', \'' . $this->config['default_timezone'] . '\', \'' . $this->config['session_data']['timezone'] . '\')', '%' . $this->request->post('search_value') . '%', false);
							$this->db->or_where('DATE(CONVERT_TZ('. $this->field->unambig($field, $ftypes) . ', \'' . $this->config['default_timezone'] . '\', \'' . $this->config['session_data']['timezone'] . '\'))', $this->request->post('search_value'), false);
							$this->db->or_where('TIME(CONVERT_TZ('. $this->field->unambig($field, $ftypes) . ', \'' . $this->config['default_timezone'] . '\', \'' . $this->config['session_data']['timezone'] . '\'))', $this->request->post('search_value'), false);
						}
					} else {
						/* If anything else matched, treat this as a regular string */
						$this->db->or_like($this->field->unambig($field, $ftypes), '%' . $this->request->post('search_value') . '%');
					}
				} else if ($ftype['input_type'] == 'checkbox') {
					/* Override 'true' / 'false' searches to match boolean values on DB */
					if (strtolower($this->request->post('search_value')) == $this->config['word_true']) {
						/* Only fields with value 1 are considered True */
						$this->db->or_where($this->field->unambig($field, $ftypes), 1);
					} else if (strtolower($this->request->post('search_value')) == $this->config['word_false']) {
						/* If the value is NULL or 0, it is considered False */
						$this->db->or_is_null($this->field->unambig($field, $ftypes));
						$this->db->or_where($this->field->unambig($field, $ftypes), 0);
					}
				} else if ($ftype['input_type'] == 'select') {
					/* Search as many fields as the ones configured through _rel_table_fields_config,
					 * if more than one was set on _rel_table_fields_config[table][2].
					 */
					if (isset($this->config['rel_table_fields_config'][$ftype['table']]) && 
							($this->config['rel_table_fields_config'][$ftype['table']][2] != NULL) &&
							(count($this->config['rel_table_fields_config'][$ftype['table']][2]) > 1)) {
						$table_fields = $this->get->table_fields($ftype['table']);
							
						foreach ($this->config['rel_table_fields_config'][$ftype['table']][2] as $rel_field) {
							$this->db->or_like($this->field->unambig($table_fields[$rel_field], $ftype), '%' . $this->request->post('search_value') . '%');
						}
					} else {
						/* Default altname for single relationships is always the first field name of the foreign table */
						$this->db->or_like('`' . $ftype['table'] . '`.`' . $ftype['altname'] . '`', '%' . $this->request->post('search_value') . '%');
					}
				} else {
					if (is_numeric($this->request->post('search_value')))
						$this->db->or_where($this->field->unambig($field, $ftypes), $this->request->post('search_value'));
				}
			}

			$data['view']['search_value'] = $this->request->post('search_value');
		} else if ($type == 'advanced') {
			/* We're comming from an advanced search */

			/* Convert checkbox input names to field arrays */

			$fields_criteria = array();
			$fields_result = array();

			foreach ($this->request->post() as $post_field => $post_value) {
				if (substr($post_field, 0, 11) == '__criteria_') {
					array_push($fields_criteria, substr($post_field, 11));
					$this->request->post_unset($post_field);
				} else if (substr($post_field, 0, 9) == '__result_') {
					array_push($fields_result, substr($post_field, 9));
					$this->request->post_unset($post_field);
				}
			}

			$this->request->post_set('fields_criteria', $fields_criteria);
			$this->request->post_set('fields_result', $fields_result);

			/* Grant that Id field is selected on result fields, unless _distinct is set to true */
			if (!in_array('id', $this->request->post('fields_result'))) {
				/* If distinct is not set to true, we cannot accept a search query without the 'id' being shown by default */
				if (!($this->request->post_isset('_distinct') && $this->request->post('_distinct')))
					$this->response->code('400', NDPHP_LANG_MOD_UNSUPPORTED_RESULT_NO_ID, $this->config['default_charset'], !$this->request->is_ajax());
			}

			/* Grant that at least one search criteria field is selected */
			if (!count($this->request->post('fields_criteria')))
				$this->response->code('400', NDPHP_LANG_MOD_MISSING_SEARCH_CRITERIA, $this->config['default_charset'], !$this->request->is_ajax());


			/* Check if result and criteria contain at least one field */
			if (!$this->request->post('fields_result') || !$this->request->post('fields_criteria')) {
				/* FIXME: TODO: a redirect() isn't a good approach here ... better to raise some error */
				redirect($this->config['name'] . '/search');
			}

			/* Check if DISTINCT is to be used */
			if ($this->request->post_isset('_distinct') && ($this->request->post('_distinct') == true))
				$this->db->distinct();

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

			/* Create criteria by processing each field type and respective selected options */
			foreach ($this->request->post('fields_criteria') as $field) {
				/* Check if there are missing fields */
				if (!$this->request->post_isset($field))
					$this->request->post_set($field, NULL); /* In case it is not set, set is as NULL */

				/* Check permissions */
				if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_search, $this->config['name'], $field))
					$this->response->code('403', NDPHP_LANG_MOD_ACCESS_SEARCH_FIELD . $field, $this->config['default_charset'], !$this->request->is_ajax());

				/* Check if this is a logical OR condition */
				if ($this->request->post_isset($field . '_or') && $this->request->post($field . '_or')) {
					$or_cond = true; /* Mark this condition as a logical OR */
				} else {
					$or_cond = false;
				}

				/* Assume non-negative logic by default */
				$negate = false;
				
				if ($this->request->post_isset($field . '_cond') && ($this->request->post($field . '_cond') == '!=')) {
					$negate = true;
				} else if ($this->request->post_isset($field . '_diff') && $this->request->post($field . '_diff')) {
					$negate = true;
				} else if ($this->request->post_isset($field . '_not') && $this->request->post($field . '_not')) {
					$negate = true;
				}

				/* Check for NULL comparisions... but don't compare NULL datetime fields that contain a custom interval set (in this case only the custom interval field will be processed) */
				if ((($this->request->post($field) === NULL || $this->request->post($field) === '') && $ftypes[$field]['type'] != 'datetime' && $ftypes[$field]['type'] != 'date' && $ftypes[$field]['type'] != 'time') ||
							(($this->request->post($field) === NULL || $this->request->post($field) === '') && $ftypes[$field]['type'] == 'datetime' && !$this->request->post($field . '_custom')) ||
							(($this->request->post($field) === NULL || $this->request->post($field) === '') && $ftypes[$field]['type'] == 'date' && !$this->request->post($field . '_custom')) ||
							(($this->request->post($field) === NULL || $this->request->post($field) === '') && $ftypes[$field]['type'] == 'time' && !$this->request->post($field . '_custom')))
				{
					if (($ftypes[$field]['input_type'] == 'select') && ($ftypes[$field]['type'] == 'rel'))
						$this->response->code('400', NDPHP_LANG_MOD_INVALID_NULL_COMPARISION_REL, $this->config['default_charset'], !$this->request->is_ajax());

					if ($negate) {
						if ($or_cond) {
							$this->db->or_is_not_null($ftypes[$field]['input_type'] == 'select' ? $field : $this->field->unambig($field, $ftypes));
						} else {
							$this->db->is_not_null($ftypes[$field]['input_type'] == 'select' ? $field : $this->field->unambig($field, $ftypes));
						}
					} else {
						if ($or_cond) {
							$this->db->or_is_null($ftypes[$field]['input_type'] == 'select' ? $field : $this->field->unambig($field, $ftypes));
						} else {
							$this->db->is_null($ftypes[$field]['input_type'] == 'select' ? $field : $this->field->unambig($field, $ftypes));
						}
					}

					continue;
				}

				/* Although date, time and datetime fields have the text input type, for
				 * the database operations they are treated as numbers (integers).
				 */
				if ($ftypes[$field]['type'] == 'mixed') {
					/* TODO: FIXME: We do not support logical OR's on mixed type fields yet... */


					/* Search all fields in mixed_* table for matching entries based on criteria */

					/* Fetch mixed table fields */
					$mt_fields = $this->get->table_fields($field);

					/* Join the mixed table to the query */
					$this->db->join($field, '`' . $this->config['name'] . '`.`id` = `' . $field . '`.`' . $this->config['name'] . '_id`', 'left');

					/* NOTE: This is a hack to enclose the OR clauses togheter, avoiding AND clause interference */
					$this->db->where('1 =', '1', false); /* Grant that WHERE clause is initiated */
					$this->db->where_append(' AND (1=0 ');

					/* Search for mixed table content matches */
					foreach ($mt_fields as $mixed_field) {
						/* Ignore mixed id */
						if ($mixed_field == 'id')
							continue;

						/* Ignore single relationship field referencing to this table */
						if ($mixed_field == ($this->config['name'] . '_id'))
							continue;

						/* Get the mixed foreign table */
						$mixed_foreign_table = array_pop(array_diff($this->get->mixed_rel_table_names($field, $this->config['name']), array($this->config['name'])));

						/* Ignore single relationship field referencing to foreign table */
						if ($mixed_field == ($mixed_foreign_table . '_id'))
							continue;

						/* FIXME: #1: Currently we only support string matching (partial date and datetime values are not yet implemented) */
						/* FIXME: #2: "Different than" option for mixed searches is is bugged (due to the enclosure hack) and shall not be used as it'll return incorrect results */

						/* Check if we've permissions to read the foreign field on the foreign table */
						if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_read, $mixed_foreign_table, $mixed_field))
							continue;

						/* Check if we've permissions to search the foreign field on the foreign table */
						if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_search, $mixed_foreign_table, $mixed_field))
							continue;

						/* Determine if this is 'pattern', 'exact' or 'different than' match */
						if ($this->request->post_isset($field . '_exact') && $this->request->post($field . '_exact')) {
							if ($this->request->post_isset($field . '_diff') && $this->request->post($field . '_diff')) {
								$this->db->where($field . '.' . $mixed_field . ' !=', $this->request->post($field));
							} else {
								$this->db->or_where($field . '.' . $mixed_field, $this->request->post($field));
							}
						} else {
							if ($this->request->post_isset($field . '_diff') && $this->request->post($field . '_diff')) {
								$this->db->not_like($field . '.' . $mixed_field, '%' . $this->request->post($field) . '%');
							} else {
								$this->db->or_like($field . '.' . $mixed_field, '%' . $this->request->post($field) . '%');
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
							($ftypes[$field]['type'] != 'datetime') &&
							($ftypes[$field]['type'] != 'decimal')) {
					if ($this->request->post_isset($field . '_exact') && $this->request->post($field . '_exact')) {
						/* Exact match */
						if ($this->request->post_isset($field . '_diff') && $this->request->post($field . '_diff')) {
							/* Different than */
							if ($or_cond) {
								if (gettype($this->request->post($field)) == 'array') {
									$this->db->or_where_not_in($this->field->unambig($field, $ftypes), $this->request->post($field));
								} else {
									$this->db->or_where($this->field->unambig($field, $ftypes) . ' !=', $this->request->post($field));
								}
							} else {
								if (gettype($this->request->post($field)) == 'array') {
									$this->db->where_not_in($this->field->unambig($field, $ftypes), $this->request->post($field));
								} else {
									$this->db->where($this->field->unambig($field, $ftypes) . ' !=', $this->request->post($field));
								}
							}
						} else {
							/* Equal to */
							if ($or_cond) {
								if (gettype($this->request->post($field)) == 'array') {
									$this->db->or_where_in($this->field->unambig($field, $ftypes), $this->request->post($field));
								} else {
									$this->db->or_where($this->field->unambig($field, $ftypes), $this->request->post($field));
								}
							} else {
								if (gettype($this->request->post($field)) == 'array') {
									$this->db->where_in($this->field->unambig($field, $ftypes), $this->request->post($field));
								} else {
									$this->db->where($this->field->unambig($field, $ftypes), $this->request->post($field));
								}
							}
						}
					} else {
						/* Pattern matching */
						if ($this->request->post_isset($field . '_diff') && $this->request->post($field . '_diff')) {
							/* Not like */
							if ($or_cond) {
								$this->db->or_not_like($this->field->unambig($field, $ftypes), '%' . $this->request->post($field) . '%');
							} else {
								$this->db->not_like($this->field->unambig($field, $ftypes), '%' . $this->request->post($field) . '%');
							}
						} else {
							/* Like */
							if ($or_cond) {
								$this->db->or_like($this->field->unambig($field, $ftypes), '%' . $this->request->post($field) . '%');
							} else {
								$this->db->like($this->field->unambig($field, $ftypes), '%' . $this->request->post($field) . '%');
							}
						}
					}
				} else if (($ftypes[$field]['input_type'] == 'number') ||
							($ftypes[$field]['type'] == 'date') ||
							($ftypes[$field]['type'] == 'time') ||
							($ftypes[$field]['type'] == 'timer') ||
							($ftypes[$field]['type'] == 'datetime') ||
							($ftypes[$field]['type'] == 'decimal')) {
					/* Datetime fields required special processing to concatenate $field _time
					 * string to the field value.
					 */
					$where_clause_enforce = true;

					if ($ftypes[$field]['type'] == 'time') {
						if ($this->request->post_isset($field . '_custom') && $this->request->post($field . '_custom')) {
							/* Compute the SQL interval string parameters based on the supplied interval value */
							$interval_fields = $this->get->interval_fields($this->request->post($field . '_custom'));

							/* Check if the supplied interval value is valid */
							if ($interval_fields === false)
								$this->response->code('400', NDPHP_LANG_MOD_INVALID_SEARCH_INTERVAL_FMT . ' { + | - } <digit> { SECOND | MINUTE | HOUR | DAY | WEEK | MONTH | YEAR }. Example: - 10 DAY', $this->config['default_charset'], !$this->request->is_ajax());

							/* Craft the custom where clause value */
							$this->request->post_set($field, 'NOW() ' . $interval_fields[0] . ' INTERVAL ' . $interval_fields[1] . ' ' . $interval_fields[2]);

							/* Do not enforce value component of the where clause... we need it raw */
							$where_clause_enforce = 'name_only';
						}

						$this->request->post_unset($field . '_custom');
					} else if ($ftypes[$field]['type'] == 'date') {
						if ($this->request->post_isset($field . '_custom') && $this->request->post($field . '_custom')) {
							/* Compute the SQL interval string parameters based on the supplied interval value */
							$interval_fields = $this->get->interval_fields($this->request->post($field . '_custom'));

							/* Check if the supplied interval value is valid */
							if ($interval_fields === false)
								$this->response->code('400', NDPHP_LANG_MOD_INVALID_SEARCH_INTERVAL_FMT . ' { + | - } <digit> { SECOND | MINUTE | HOUR | DAY | WEEK | MONTH | YEAR }. Example: - 10 DAY', $this->config['default_charset'], !$this->request->is_ajax());

							/* Craft the custom where clause value */
							$this->request->post_set($field, 'NOW() ' . $interval_fields[0] . ' INTERVAL ' . $interval_fields[1] . ' ' . $interval_fields[2]);

							/* Do not enforce value component of the where clause... we need it raw */
							$where_clause_enforce = 'name_only';
						}

						$this->request->post_unset($field . '_custom');
					} else if ($ftypes[$field]['type'] == 'datetime') {
						/* Check if custom interval was set */
						if ($this->request->post_isset($field . '_custom') && $this->request->post($field . '_custom')) {
							/* Compute the SQL interval string parameters based on the supplied interval value */
							$interval_fields = $this->get->interval_fields($this->request->post($field . '_custom'));

							/* Check if the supplied interval value is valid */
							if ($interval_fields === false)
								$this->response->code('400', NDPHP_LANG_MOD_INVALID_SEARCH_INTERVAL_FMT . ' { + | - } <digit> { SECOND | MINUTE | HOUR | DAY | WEEK | MONTH | YEAR }. Example: - 10 DAY', $this->config['default_charset'], !$this->request->is_ajax());

							/* Craft the custom where clause value */
							$this->request->post_set($field, 'NOW() ' . $interval_fields[0] . ' INTERVAL ' . $interval_fields[1] . ' ' . $interval_fields[2]);

							/* Do not enforce value component of the where clause... we need it raw */
							$where_clause_enforce = 'name_only';
						} else {
							/* NOTE: Even if a time component isn't set (<field>_time), the timezone convertion will assume 00:00:00 for the time */

							if ($this->request->post_isset($field . '_time')) {
								$dt_value = $this->request->post($field) . ' ' . $this->request->post($field . '_time');
							} else {
								$dt_value = $this->request->post($field);
							}

							/* Otherwise just merge date and time fields */
							$this->request->post_set($field, $this->timezone->convert($dt_value, $this->config['session_data']['timezone'], $this->config['default_timezone']));
						}

						$this->request->post_unset($field . '_custom');
					}

					/* Numbers, Times and Dates are processed with the same comparators */
					if (!$this->request->post_isset($field . '_cond') && is_array($this->request->post($field))) {
						/* If the search value is of type array, we shall use a WHERE IN clause */
						if ($or_cond) {
							if ($negate) {
								$this->db->or_where_not_in($this->field->unambig($field, $ftypes), $this->request->post($field));
							} else {
								$this->db->or_where_in($this->field->unambig($field, $ftypes), $this->request->post($field));
							}
						} else {
							if ($negate) {
								$this->db->where_not_in($this->field->unambig($field, $ftypes), $this->request->post($field));
							} else {
								$this->db->where_in($this->field->unambig($field, $ftypes), $this->request->post($field));
							}
						}
					} else if (($this->request->post($field . '_cond') != '><') && ($this->request->post($field . '_cond') != '=')) {
						if (($this->request->post($field . '_cond') != '>') && ($this->request->post($field . '_cond') != '<') &&
								($this->request->post($field . '_cond') != '>=') && ($this->request->post($field . '_cond') != '<='))
						{
							$this->response->code('400', NDPHP_LANG_MOD_INVALID_SEARCH_CONDITION . ': ' . $this->request->post($field . '_cond'));
						}

						/* Lesser, Greater, Different */
						if ($or_cond) {
							$this->db->or_where($this->field->unambig($field, $ftypes) . ' ' . $this->request->post($field . '_cond'), $this->request->post($field), $where_clause_enforce);
						} else {
							$this->db->where($this->field->unambig($field, $ftypes) . ' ' . $this->request->post($field . '_cond'), $this->request->post($field), $where_clause_enforce);
						}
					} else if ($this->request->post($field . '_cond') == '><') {
						/* Between */
						/* FIXME: TODO: Use between() function here */
						if ($or_cond) {
							$this->db->or_where($this->field->unambig($field, $ftypes) . ' >=', $this->request->post($field), $where_clause_enforce);
						} else {
							$this->db->where($this->field->unambig($field, $ftypes) . ' >=', $this->request->post($field), $where_clause_enforce);
						}
						
						/* Field _to requires special processing */
						if ($ftypes[$field]['type'] == 'time') {
							if ($this->request->post_isset($field . '_to_custom') && $this->request->post($field . '_to_custom')) {
								/* Compute the SQL interval string parameters based on the supplied interval value */
								$interval_fields = $this->get->interval_fields($this->request->post($field . '_to_custom'));

								/* Check if the supplied interval value is valid */
								if ($interval_fields === false)
									$this->response->code('400', NDPHP_LANG_MOD_INVALID_SEARCH_INTERVAL_FMT . ' { + | - } <digit> { SECOND | MINUTE | HOUR | DAY | WEEK | MONTH | YEAR }. Example: - 10 DAY', $this->config['default_charset'], !$this->request->is_ajax());

								/* Craft the custom where clause value */
								$this->request->post_set($field, 'NOW() ' . $interval_fields[0] . ' INTERVAL ' . $interval_fields[1] . ' ' . $interval_fields[2]);

								/* Do not enforce value component of the where clause... we need it raw */
								$where_clause_enforce = 'name_only';
							}

							$this->request->post_unset($field . '_to_custom');
						} else if ($ftypes[$field]['type'] == 'date') {
							if ($this->request->post_isset($field . '_to_custom') && $this->request->post($field . '_to_custom')) {
								/* Compute the SQL interval string parameters based on the supplied interval value */
								$interval_fields = $this->get->interval_fields($this->request->post($field . '_to_custom'));

								/* Check if the supplied interval value is valid */
								if ($interval_fields === false)
									$this->response->code('400', NDPHP_LANG_MOD_INVALID_SEARCH_INTERVAL_FMT . ' { + | - } <digit> { SECOND | MINUTE | HOUR | DAY | WEEK | MONTH | YEAR }. Example: - 10 DAY', $this->config['default_charset'], !$this->request->is_ajax());

								/* Craft the custom where clause value */
								$this->request->post_set($field, 'NOW() ' . $interval_fields[0] . ' INTERVAL ' . $interval_fields[1] . ' ' . $interval_fields[2]);

								/* Do not enforce value component of the where clause... we need it raw */
								$where_clause_enforce = 'name_only';
							}

							$this->request->post_unset($field . '_to_custom');
						} else if ($ftypes[$field]['type'] == 'datetime') {
							/* Check if custom interval was set */
							if ($this->request->post_isset($field . '_to_custom') && $this->request->post($field . '_to_custom')) {
								/* Compute the SQL interval string parameters based on the supplied interval value */
								$interval_fields = $this->get->interval_fields($this->request->post($field . '_to_custom'));

								/* Check if the supplied interval value is valid */
								if ($interval_fields === false)
									$this->response->code('400', NDPHP_LANG_MOD_INVALID_SEARCH_INTERVAL_FMT . ' { + | - } <digit> { SECOND | MINUTE | HOUR | DAY | WEEK | MONTH | YEAR }. Example: - 10 DAY', $this->config['default_charset'], !$this->request->is_ajax());

								/* Craft the custom where clause value */
								$this->request->post_set($field . '_to', 'NOW() ' . $interval_fields[0] . ' INTERVAL ' . $interval_fields[1] . ' ' . $interval_fields[2]);

								/* Do not enforce value component of the where clause... we need it raw */
								$where_clause_enforce = 'name_only';
							} else {
								/* NOTE: Even if a time component isn't set (<field>_time), the timezone convertion will assume 00:00:00 for the time */

								if ($this->request->post_isset($field . '_to_time')) {
									$dt_value_to = $this->request->post($field . '_to') . ' ' . $this->request->post($field . '_to_time');
								} else {
									$dt_value_to = $this->request->post($field . '_to');
								}

								$this->request->post_set($field . '_to', $this->timezone->convert($dt_value_to, $this->config['session_data']['timezone'], $this->config['default_timezone']));
							}

							$this->request->post_unset($field . '_to_custom');
						}

						if ($or_cond) {
							$this->db->or_where($this->field->unambig($field, $ftypes) . ' <=', $this->request->post($field . '_to'), $where_clause_enforce);
						} else {
							$this->db->where($this->field->unambig($field, $ftypes) . ' <=', $this->request->post($field . '_to'), $where_clause_enforce);
						}
					} else {
						/* The condition is '=' (equal) */
						if ($or_cond) {
							$this->db->or_where($this->field->unambig($field, $ftypes), $this->request->post($field), $where_clause_enforce);
						} else {
							$this->db->where($this->field->unambig($field, $ftypes), $this->request->post($field), $where_clause_enforce);
						}
					}
				} else if (($ftypes[$field]['input_type'] == 'select') && ($ftypes[$field]['type'] != 'rel')) {
					if (is_array($this->request->post($field))) {
						if ($or_cond) {
							if ($negate) {
								$this->db->or_where_not_in($this->config['name'] . '.' . $field, $this->request->post($field));
							} else {
								$this->db->or_where_in($this->config['name'] . '.' . $field, $this->request->post($field));
							}
						} else {
							if ($negate) {
								$this->db->where_not_in($this->config['name'] . '.' . $field, $this->request->post($field));
							} else {
								$this->db->where_in($this->config['name'] . '.' . $field, $this->request->post($field));
							}
						}
					} else {
						/* Set search condition for this single relationship search */
						if (!$this->request->post_isset($field . '_cond') || $this->request->post($field . '_cond') == '=') {
							$rel_cond = '=';
						} else if ($this->request->post($field . '_cond') == '!=') {
							$rel_cond = '!=';
						} else if (($this->request->post($field . '_cond') != '>') && ($this->request->post($field . '_cond') != '<') &&
								($this->request->post($field . '_cond') != '>=') && ($this->request->post($field . '_cond') != '<='))
						{
							$this->response->code('400', NDPHP_LANG_MOD_INVALID_SEARCH_CONDITION . ': ' . $this->request->post($field . '_cond'));
						} else {
							$rel_cond = $this->request->post($field . '_cond');
						}

						if ($or_cond) {
							$this->db->or_where($this->config['name'] . '.' . $field . ' ' . $rel_cond, $this->request->post($field));
						} else {
							$this->db->where($this->config['name'] . '.' . $field . ' ' . $rel_cond, $this->request->post($field));
						}
					}
				} else if (($ftypes[$field]['input_type'] == 'select') && ($ftypes[$field]['type'] == 'rel')) {
					if (is_array($this->request->post($field))) {
						if ($or_cond) {
							if ($negate) {
								$this->db->or_where_not_in($ftypes[$field]['rel_table'] . '.' . $ftypes[$field]['table'] . '_id', $this->request->post($field));
							} else {
								$this->db->or_where_in($ftypes[$field]['rel_table'] . '.' . $ftypes[$field]['table'] . '_id', $this->request->post($field));
							}
						} else {
							if ($negate) {
								$this->db->where_not_in($ftypes[$field]['rel_table'] . '.' . $ftypes[$field]['table'] . '_id', $this->request->post($field));
							} else {
								$this->db->where_in($ftypes[$field]['rel_table'] . '.' . $ftypes[$field]['table'] . '_id', $this->request->post($field));
							}
						}
					} else {
						/* Set search condition for this multiple relationship search */
						if (!$this->request->post_isset($field . '_cond') || $this->request->post($field . '_cond') == '=') {
							$rel_cond = '=';
						} else if ($this->request->post($field . '_cond') == '!=') {
							$rel_cond = '!=';
						} else if (($this->request->post($field . '_cond') != '>') && ($this->request->post($field . '_cond') != '<') &&
								($this->request->post($field . '_cond') != '>=') && ($this->request->post($field . '_cond') != '<='))
						{
							$this->response->code('400', NDPHP_LANG_MOD_INVALID_SEARCH_CONDITION . ': ' . $this->request->post($field . '_cond'));
						} else {
							$rel_cond = $this->request->post($field . '_cond');
						}

						if ($or_cond) {
							$this->db->or_where($ftypes[$field]['rel_table'] . '.' . $ftypes[$field]['table'] . '_id ' . $rel_cond, $this->request->post($field));
						} else {
							$this->db->where($ftypes[$field]['rel_table'] . '.' . $ftypes[$field]['table'] . '_id ' . $rel_cond, $this->request->post($field));
						}
					}
				} else {
					/* FIXME: TODO: Choose between like() or where(), not both. */
					/* FIXME: TODO: What exactly falls here ? */
					if ($or_cond) {
						$this->db->or_like($this->field->unambig($field, $ftypes), $this->request->post($field));
						$this->db->or_where($this->field->unambig($field, $ftypes), $this->request->post($field));
					} else {
						$this->db->like($this->field->unambig($field, $ftypes), $this->request->post($field));
						$this->db->where($this->field->unambig($field, $ftypes), $this->request->post($field));
					}
				}
			}
		}

		/* NOTE: If none of the above types matched, we've a default 'query' type here */

		/* Switch order on each call */
		if ($order_type == "asc") {
			$data['config']['order'] = "desc";
		} else {
			$data['config']['order'] = "asc";
		}

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
			/* If this is a REST call, do not limit the results unless it is explicitly requested (default is to display all) */
			if ($this->config['json_replies'] === true) {
				/* Check if there is a limit and/or offset defined */
				if ($this->request->post_isset('_limit')) {
					/* Validate limit value (must be greater than zero */
					if ($this->request->post('_limit') <= 0)
						$this->response->code('400', NDPHP_LANG_MOD_INVALID_LIMIT_VALUE, $this->config['default_charset'], !$this->request->is_ajax());

					/* If set, validate offset value (must be zero or greater) */
					if ($this->request->post_isset('_offset') && ($this->request->post('_offset') < 0))
						$this->response->code('400', NDPHP_LANG_MOD_INVALID_OFFSET_VALUE, $this->config['default_charset'], !$this->request->is_ajax());

					/* Set limit (and, if set, the offset) */
					$result_query = $result_query . ' LIMIT ' . ($this->request->post_isset('_offset') ? intval($this->request->post('_offset')) : '0') . ', ' . ($this->request->post('_limit') > $this->config['json_result_hard_limit'] ? intval($this->config['json_result_hard_limit']) : intval($this->request->post('_limit')));
				} else {
					/* If no limit was explicitly set, use the default */
					$result_query = $result_query . ' LIMIT ' . intval($this->config['json_result_hard_limit']);
				}
			} else if ($page >= 0) {
				/* Limit results to the number of rows per page (pagination) */
				$result_query = $result_query . ' LIMIT ' . intval($page) . ', ' . intval($this->config['table_pagination_rpp_result']);
			}

			/* Check if total number of matches is required to be computed.
			 * If this isn't a REST JSON request, then total number of matches is always computed (for pagination),
			 * otherwise, calculated total number of matches only if the REST JSON request explicitly asks for it.
			 */
			if (!$this->request->is_json()) {
				$totals = true;
			} else {
				/* Check if the REST JSON request explicitly requests total number of matches */
				if ($this->request->post_isset('_totals') && $this->request->post('_totals')) {
					$totals = true;
				} else {
					$totals = false;
				}
			}

			/* If required, force MySQL to count the total number of rows despite the LIMIT clause */
			if ($totals)
				$result_query = 'SELECT SQL_CALC_FOUND_ROWS ' . substr($result_query, 7);
			
			$data['view']['result_array'] = $this->field->value_mangle($ftypes, $this->db->query($result_query));
			
			/* Get total rows count, if required */
			if ($totals) {
				$total_rows_query = $this->db->query('SELECT FOUND_ROWS() AS nr_rows');
				$total_rows = $total_rows_query->row_array()['nr_rows'];
			} else {
				$total_rows = 0;
			}
		} else {
			/* This is not a recall */

			/* Filter table rows, if applicable */
			$this->filter->table_row_apply($this->config['name'], $this->security->perm_read);

			/* REST calls using that set '_show' key are able to determine which fields will be present in the list results */
			if (($this->config['json_replies'] === true)) { // && is_array($this->request->post('_show')) && count($this->request->post('_show'))) {
				/* TODO: FIXME: This needs to be extensively tested */

				/* Set the selected fields */
				$selected = $this->request->post('fields_result');

				/* Grant that even if there are no fields set, $selected is still an array */
				if (!$selected)
					$selected = array();

				/* 'id' must always be present, unless this is a distinct call */
				if ($this->request->post_isset('_distinct') && $this->request->post('_distinct')) {
					/* Remove the 'id' field from $selected, otherwise distinct won't make sense */
					$selected = array_diff($selected, array('id'));
				} else if (!in_array('id', $selected)) {
					/* If distinct is not used, 'id' should always be shown */
					array_push($selected, 'id');
				}

				/* Resolve fields */
				$this->field->resolve($ftypes, $selected, $this->request->post('fields_criteria'));
			} else {
				/* Resolve and select the fields to be displayed in the result */		
				$this->field->resolve($ftypes, $this->request->post('fields_result'), $this->request->post('fields_criteria'));
			}

			$this->db->from($this->config['name']); // from() method is needed here for get_compiled_select_str() call

			/* Apply result filter hook */
			$hook_enter_return = $this->_hook_result_generic_filter($data, $type, $result_query, $order_field, $order_type, $page, $hook_enter_return);

			/* Only create a result_query view value if this isn't a JSON request */
			if (!$this->request->is_json())
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
			 * NOTE 2: Only create an export_query view value if this isn't a JSON request
		 	 */
			if (!$this->request->is_json())
				$data['view']['export_query'] = rawurlencode($this->ndphp->safe_b64encode($this->encrypt->encode(gzcompress($this->db->get_compiled_select_str(NULL, true, false), 9))));


			/* If this is a REST call, do not limit the results unless it is explicitly requested (default is to display all) */
			if ($this->request->is_json() === true) {
				/* Check if there is a limit and/or offset defined */
				if ($this->request->post_isset('_limit')) {
					/* Validate limit value (must be greater than zero */
					if ($this->request->post('_limit') <= 0)
						$this->response->code('400', NDPHP_LANG_MOD_INVALID_LIMIT_VALUE, $this->config['default_charset'], !$this->request->is_ajax());

					/* If set, validate offset value (must be zero or greater) */
					if ($this->request->post_isset('_offset') && ($this->request->post('_offset') < 0))
						$this->response->code('400', NDPHP_LANG_MOD_INVALID_OFFSET_VALUE, $this->config['default_charset'], !$this->request->is_ajax());

					/* Set limit (and, if set, the offset) */
					$this->db->limit($this->request->post('_limit') > $this->config['json_result_hard_limit'] ? $this->config['json_result_hard_limit'] : $this->request->post('_limit'), $this->request->post_isset('_offset') ? $this->request->post('_offset') : 0);
				} else {
					/* If no limit was explicitly set, use the default */
					$this->db->limit($this->config['json_result_hard_limit']);
				}
			} else if ($page >= 0) {
				/* Limit results to the number of rows per page (pagination) */
				$this->db->limit($this->config['table_pagination_rpp_list'], $page);
			}
			
			/* Check if total number of matches is required to be computed.
			 * If this isn't a REST JSON request, then total number of matches is always computed (for pagination),
			 * otherwise, calculated total number of matches only if the REST JSON request explicitly asks for it.
			 */
			if (!$this->request->is_json()) {
				$totals = true;
			} else {
				/* Check if the REST JSON request explicitly requests total number of matches */
				if ($this->request->post_isset('_totals') && $this->request->post('_totals')) {
					$totals = true;
				} else {
					$totals = false;
				}
			}

			/* If this isn't a JSON request, use the compiled select statement...
			 * TODO: FIXME: A little bit messy here... But view data will be removed in future versions, so no real need to refactor atm.
			 */
			if (!$this->request->is_json()) {
				/* Get compiled select statement */
				$result_query = $this->db->get_compiled_select_str(NULL, true, false);

				if ($totals)
					$result_query = 'SELECT SQL_CALC_FOUND_ROWS ' . substr($result_query, 7);
			
				$data['view']['result_array'] = $this->field->value_mangle($ftypes, $this->db->query($result_query));
			} else {
				/* If this is a JSON request, do not use the compiled query. Force prepared statments by default */

				if ($totals)
					$this->db->calc_found_rows();

				$data['view']['result_array'] = $this->field->value_mangle($ftypes, $this->db->get());
			}
			
			/* Get total rows count, if required. */
			if ($totals) {
				$total_rows_query = $this->db->query('SELECT FOUND_ROWS() AS `nr_rows`');
				$total_rows = $total_rows_query->row_array()['nr_rows'];
			} else {
				$total_rows = 0;
			}
		}

		$data['view']['fields'] = $ftypes_result;

		/* Pagination */
		if ($page >= 0) {
			$pagcfg['page'] = ($page / $this->config['table_pagination_rpp_result']) + 1;
			$pagcfg['base_url'] = base_url() . 'index.php/' . $this->config['name'] . '/result/query/' . $data['view']['result_query'] . '/' . $order_field . '/' . $order_type . '/@ROW_NR@';
			$pagcfg['onclick'] = 'ndphp.ajax.load_data_ordered_result(event, \'' . $this->config['name'] . '\', \'' . $data['view']['result_query'] . '\', \'' . $order_field . '\', \'' . $order_type . '\', \'@ROW_NR@\');';
			$pagcfg['total_rows'] = $total_rows; 
			$pagcfg['per_page'] = $this->config['table_pagination_rpp_result'];
			
			$this->pagination->initialize($pagcfg);
			$data['view']['links']['pagination'] = $this->pagination->create_links();
			$data['view']['page'] = $page;
		}

		/* Setup breadcrumb */
		$data['view']['links']['breadcrumb'] = $this->get->view_breadcrumb('search', NDPHP_LANG_MOD_OP_SEARCH, array('result', 'query', $data['view']['result_query']), NDPHP_LANG_MOD_OP_RESULT);

		/* Hook handler (leave) */
		$this->_hook_result_generic_leave($data, $type, $result_query, $order_field, $order_type, $page, $hook_enter_return);

		/* Load leave plugins */
		if ($this->config['plugins_enabled']) {
			foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/result_generic_leave.php') as $plugin)
				include($plugin);
		}

		/* If logging is enabled, log this search result request */
		if ($this->request->is_json()) {
			$this->logging->log(
				/* op         */ 'RESULT',
				/* table      */ $this->config['name'],
				/* field      */ 'LIMIT / OFFSET / ORDERBY / ORDERING',
				/* entry_id   */ ($this->request->post_isset('_limit') ? $this->request->post('_limit') : $this->config['json_result_hard_limit']) . ' / ' .
								 ($this->request->post_isset('_offset') ? $this->request->post('_offset') : '0') . ' / ' .
								 $order_field . ' / ' .
								 $order_type,
				/* value_new  */ (($type == "basic") ? $this->request->post('search_value') : (($type == "query") ? $result_query : json_encode($this->request->post(), JSON_PRETTY_PRINT))),
				/* value_old  */ NULL,
				/* session_id */ $this->config['session_data']['sessions_id'],
				/* user_id    */ $this->config['session_data']['user_id'],
				/* log it?    */ $this->config['logging']
			);
		} else {
			$this->logging->log(
				/* op         */ 'RESULT',
				/* table      */ $this->config['name'],
				/* field      */ 'LIMIT / OFFSET / ORDERBY / ORDERING',
				/* entry_id   */ $page . ' / ' .
								 $this->config['table_pagination_rpp_result'] . ' / ' .
								 $order_field . ' / ' .
								 $order_type,
				/* value_new  */ (($type == "basic") ? $this->request->post('search_value') : (($type == "query") ? $result_query : json_encode($this->request->post(), JSON_PRETTY_PRINT))),
				/* value_old  */ NULL,
				/* session_id */ $this->config['session_data']['sessions_id'],
				/* user_id    */ $this->config['session_data']['user_id'],
				/* log it?    */ $this->config['logging']
			);
		}

		/* Setup total items information, if required */
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
		if ($this->config['json_replies'] === true) {
			$this->response->output($this->rest->json_result($data));
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
		$this->logging->log(
			/* op         */ 'RESULT',
			/* table      */ $this->config['name'],
			/* field      */ 'GROUPS',
			/* entry_id   */ $grouping_field,
			/* value_new  */ NULL,
			/* value_old  */ NULL,
			/* session_id */ $this->config['session_data']['sessions_id'],
			/* user_id    */ $this->config['session_data']['user_id'],
			/* log it?    */ $this->config['logging']
		);

		/* Load result generic data */
		$data = $this->result_generic($type, $result_query, $order_field, $order_type, -1 /* $page */); /* FIXME: We should not rely on (). A specific implementation for grouping is required. */

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
					if (in_array($opt_val, explode($this->config['rel_group_concat_sep'], $row[$grouping_field]))) {
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
		if ($this->config['json_replies'] === true) {
			$this->response->output($this->rest->json_result($data));
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
		if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_read, $this->config['name']))
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->config['default_charset'], !$this->request->is_ajax());

		/* Initialize $data */
		$data = array();

		/* Load enter plugins */
		if ($this->config['plugins_enabled']) {
			foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/export_enter.php') as $plugin)
				include($plugin);
		}

		/* Hook handler (enter) */
		$hook_enter_return = $this->_hook_export_enter($data, $export_query, $type);

		/* If logging is enabled, log this export request */
		$this->logging->log(
			/* op         */ 'EXPORT',
			/* table      */ $this->config['name'],
			/* field      */ strtoupper($type),
			/* entry_id   */ NULL,
			/* value_new  */ NULL,
			/* value_old  */ NULL,
			/* session_id */ $this->config['session_data']['sessions_id'],
			/* user_id    */ $this->config['session_data']['user_id'],
			/* log it?    */ $this->config['logging']
		);


		/* TODO: $export_query shall be passed in a POST method in the future as it can easily
		 * reach more than 2083 characters, which is the IE limit for URL size.
		 * 
		 *  -- Pedro A. Hortas (pah@ucodev.org)
		 */

		/* Get view title */
		$title = NULL;

		if (isset($this->config['menu_entries_aliases'][$this->config['name']])) {
			$title = $this->config['menu_entries_aliases'][$this->config['name']] . $this->config['view_title_sep'] . NDPHP_LANG_MOD_OP_EXPORT;
		} else {
			$title = $this->config['viewhname'] . $this->config['view_title_sep'] . NDPHP_LANG_MOD_OP_EXPORT;
		}

		/* Get view description value */
		$description = NDPHP_LANG_MOD_OP_EXPORT . " " . $this->config['viewhname'];

		/* Setup basic view data */
		$data = array_merge_recursive($data, $this->get->view_data_generic($title, $description));

		/* Setup specific view data */
		$data['view']['fields'] = $this->get->fields(NULL, $this->config['hide_fields_export']); /* _get_fields() uses a perm_read filter by default */
		
		if ($export_query) {
			$data['view']['result_array'] = $this->field->value_mangle($data['view']['fields'], $this->db->query(gzuncompress($this->encrypt->decode($this->ndphp->safe_b64decode(rawurldecode($export_query))))));
		} else {
			$this->db->from($this->config['name']);

			/* Select only the fields that were returned by _get_fields() */
			$this->filter->selected_fields($data['view']['fields']);
			$data['view']['result_array'] = $this->field->value_mangle($data['view']['fields'], $this->db->get());
		}

		/* Hidden fields */
		$data['config']['hidden_fields'] = $this->config['hide_fields_export'];

		/* Setup choices class */
		$data['config']['choices_class'] = $this->config['rel_choice_table_row_class'];

		/* Load leave plugins */
		if ($this->config['plugins_enabled']) {
			foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/export_leave.php') as $plugin)
				include($plugin);
		}

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
			$this->mpdf->Output($this->config['name'] . '.pdf', 'D');
		} else if ($type == 'csv') {
			/* Create a unique filename */
			$csv_filename = tempnam($this->config['temp_dir'], $this->config['name'] . '_');

			/* FIXME: Check if temporary file already exists and generate a new one if so. */

			/* Open CSV file */
			$csv_fp = fopen($csv_filename, 'a+');
			
			/* Write field names on csv header */
			$row = array_values($data['view']['result_array'])[0];
			foreach ($row as $field => $value):
				if (in_array($field, $this->_hide_fields_export))
					continue;

				fwrite($csv_fp, $this->config['csv_delim'] . ucfirst(mb_convert_encoding($data['view']['fields'][$field]['viewname'], $this->config['csv_to_encoding'], $this->config['csv_from_encoding'])) . $this->config['csv_delim'] . $this->config['csv_sep']);
			endforeach;

			/* Position over the last written char, which is a field separator, and it should be overwritten */
			fseek($csv_fp, -1, SEEK_CUR);

			/* Add new line for csv body */
			fwrite($csv_fp, "\r\n");

			/* Write field values */
			foreach ($data['view']['result_array'] as $row):
				foreach ($row as $field => $value):
					if (in_array($field, $this->config['hide_fields_export']))
						continue;

					if ($data['view']['fields'][$field]['input_type'] == 'checkbox') {
						fwrite($csv_fp, $this->config['csv_delim'] . ($value == 1 ? (NDPHP_LANG_MOD_STATUS_CHECKBOX_CHECKED . $this->config['csv_sep']) : (NDPHP_LANG_MOD_STATUS_CHECKBOX_UNCHECKED) . $this->config['csv_delim'] . $this->config['csv_sep']));
					} else {
						fwrite($csv_fp, $this->config['csv_delim'] . mb_convert_encoding($value, $this->config['csv_to_encoding'], $this->config['csv_from_encoding']) . $this->config['csv_delim'] . $this->config['csv_sep']);
					}
				endforeach;

				/* Position over the last written char, which is a field separator, and it should be overwritten */
				fseek($csv_fp, -1, SEEK_CUR);

				/* Add new line for next element */
				fwrite($csv_fp, "\r\n");
			endforeach;
			
			/* Close CSV file */
			fclose($csv_fp);

			/* Force CSV download */
			$download_csv = end(explode('/', $csv_filename));

			/* Deliver download */
			$this->response->download(
				file_get_contents($csv_filename),
				$download_csv . '.csv',
				'text/csv',
				$this->config['csv_to_encoding'],
				$this->config['csv_to_encoding']
			);

			/* Remove temporary file */
			unlink($csv_filename);
		}
	}

	protected function create_generic($autocomplete = NULL) {
		/* Check if this is a view table type (a.k.a. a database VIEW) */
		if ($this->config['table_type_view'])
			$this->response->code('403', NDPHP_LANG_MOD_CANNOT_OP_VIEW_TYPE_CTRL . ' CREATE.', $this->config['default_charset'], !$this->request->is_ajax());

		/* Security Permissions Check */
		if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_create, $this->config['name']))
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->config['default_charset'], !$this->request->is_ajax());

		/* Initialize $data */
		$data = array();

		/* Load enter plugins */
		if ($this->config['plugins_enabled']) {
			foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/create_generic_enter.php') as $plugin)
				include($plugin);
		}

		/* Hook handler (enter) */
		$hook_enter_return = $this->_hook_create_generic_enter($data);

		/* Get view title */
		$title = NULL;

		if (isset($this->config['menu_entries_aliases'][$this->config['name']])) {
			$title = $this->config['menu_entries_aliases'][$this->config['name']] . $this->config['view_title_sep'] . NDPHP_LANG_MOD_OP_CREATE;
		} else {
			$title = $this->config['viewhname'] . $this->config['view_title_sep'] . NDPHP_LANG_MOD_OP_CREATE;
		}

		/* Get view description value */
		$description = NDPHP_LANG_MOD_OP_CREATE . " " . $this->config['viewhname'];

		/* Setup basic view data */
		$data = array_merge_recursive($data, $this->get->view_data_generic($title, $description));

		/* Setup specific view data */
		$data['config']['choices'] = count($this->config['rel_choice_hide_fields_create']) ? $this->config['rel_choice_hide_fields_create'] : $this->config['rel_choice_hide_fields'];
		$data['config']['choices_filters'] = $this->config['rel_choice_filter_fields_options'];
		$data['config']['mixed'] = array();
		$data['config']['mixed']['autocomplete'] = $this->config['mixed_views_autocomplete'];

		$data['view']['fields'] = $this->filter->fields($this->config['security_perms'], $this->security->perm_create, $this->get->fields(NULL, $this->config['hide_fields_create'])); /* Filter fields (The perm_read permission is already being validated on $this->_get_fields() */
		$data['view']['links']['submenu'] = $this->config['links_submenu_body_create'];
		$data['view']['links']['breadcrumb'] = $this->get->view_breadcrumb('create', NDPHP_LANG_MOD_OP_CREATE);

		/* Check if there is any autocomplete data set in a POST */
		if ($this->request->post_isset('autocomplete')) {
			/* If so... the POST data always have precedence over the URL data */
			$data['view']['autocomplete'] = json_decode($this->request->post('autocomplete'), true);
		} else if ($autocomplete !== NULL) {
			/* URL parameters always come encoded in safe base64 format */
			$data['view']['autocomplete'] = json_decode($this->ndphp->safe_b64decode($autocomplete), true);
		}

		/* Check if we've the required fields and default values cached */
		if ($this->cache->is_active()
				&& $this->cache->get('s_create_required_' . $this->config['name'])
				&& $this->cache->get('s_create_defaults_' . $this->config['name'])) {
			$data['view']['required'] = $this->cache->get('d_create_required_' . $this->config['name']);
			$data['view']['default'] = $this->cache->get('d_create_defaults_' . $this->config['name']);
		} else {
			/* Required fields are extracted from information schema
			 *
			 * NOTE: This requires DBMS to operate in 'strict' mode.
			 * Make sure that 'strict' config parameter is set to true in user/config/database.php
			 *  
			 */
			$schema = $this->load->database($this->config['default_database'] . '_schema', true);

			$schema->select('COLUMN_NAME');
			$schema->from('COLUMNS');
			$schema->where('TABLE_SCHEMA', $this->db->database);
			$schema->where('TABLE_NAME', $this->config['name']);
			$schema->where('IS_NULLABLE', 'NO');
			$query_required = $schema->get();

			$schema->select('COLUMN_NAME,COLUMN_DEFAULT');
			$schema->from('COLUMNS');
			$schema->where('TABLE_SCHEMA', $this->db->database);
			$schema->where('TABLE_NAME', $this->config['name']);
			$query_default = $schema->get();

			$schema->close();

			/* Select the default database */
			$this->load->database($this->config['default_database']);

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

			/* If cache is enabled, cache required fields and default values data */
			if ($this->cache->is_active()) {
				$this->cache->set('s_create_required_' . $this->config['name'], true);
				$this->cache->set('d_create_required_' . $this->config['name'], $data['view']['required']);
				$this->cache->set('s_create_defaults_' . $this->config['name'], true);
				$this->cache->set('d_create_defaults_' . $this->config['name'], $data['view']['default']);
			}
		}

		/* Hidden fields */
		$data['config']['hidden_fields'] = $this->config['hide_fields_create'];

		/* Load leave plugins */
		if ($this->config['plugins_enabled']) {
			foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/create_generic_leave.php') as $plugin)
				include($plugin);
		}

		/* Hook handler (leave) */
		$this->_hook_create_generic_leave($data, $hook_enter_return);

		/* All good */
		return $data;
	}
	
	public function create_mixed_rel($mid, $foreign_table = '', $field_name = '', $field_data = '') {
		/* This data was encoded with Javascript */
		$field_data = $this->ndphp->safe_b64decode(rawurldecode($field_data));

		$data = $this->create_generic();

		$data['config']['hidden_fields'] = $this->config['mixed_hide_fields_create'];
		$data['config']['mixed']['table_field_width'] = $this->config['mixed_table_fields_width'];

		$data['view']['mixed_id'] = $mid;
		$data['view']['field_data'] = $field_data;
		$data['view']['values'] = array();

		if ($field_data != '') {
			$this->load->database($this->config['default_database']);
			$this->db->where($field_name, $field_data);

			if (!$this->filter->table_row_perm($field_data, $this->config['name'], $this->security->perm_create, $field_name))
				$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->config['default_charset'], !$this->request->is_ajax());

			$this->db->from($this->config['name']);

			/* We need to mangle the relationship data. We also use _get_fields_basic_types() to avoid the
			 * overhead of calling _get_fields()
			 */
			$result_array = $this->field->value_mangle($this->get->fields_basic_types($this->config['name']), $this->db->get());
			
			/* NOTE: $field_name must have a UNIQUE constraint in order to trigger the following validation */
			if (count($result_array) == 1) {
				$row = array_values($result_array)[0];
				$data['view']['values'] = $row;
			}
		}
		
		/* Remove the fields that are not present in the mixed relational table */
		$data['view']['present_fields'] = $this->get->mixed_table_fields($this->config['name'], $foreign_table);
		
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
		$this->_load_module('upload', true);

		/* NOTE: If $retid is true, an integer value is returned on success (on failure, die() will always be called) */

		$log_removed_fields = array(); /* Keep track of unset fields from POST data that still need to be logged */

		/* Check if this is a view table type */
		if ($this->config['table_type_view'])
			$this->response->code('403', NDPHP_LANG_MOD_CANNOT_OP_VIEW_TYPE_CTRL . ' INSERT.', $this->config['default_charset'], !$this->request->is_ajax());

		/* Security Permissions Check */
		if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_create, $this->config['name']))
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED . ' #1', $this->config['default_charset'], !$this->request->is_ajax());

		$ftypes = $this->get->fields();

		/* Assume no relationships by default */
		$rel = NULL;
		$mixed_rels = array();

		/* Load pre plugins */
		if ($this->config['plugins_enabled']) {
			foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/insert_pre.php') as $plugin)
				include($plugin);
		}

		/* Pre-Insert hook */
		$hook_pre_return = $this->_hook_insert_pre($this->request->post(), $ftypes);

		/* Pre-process file uploads */
		$pre_process_status = $this->upload->pre_process();

		if ($pre_process_status[0] !== true)
			$this->response->code($pre_process_status[1], $pre_process_status[2], $this->config['default_charset'], !$this->request->is_ajax());

		$file_uploads = $pre_process_status[2];

		/* Pre-process $_POST array */
		foreach ($this->request->post() as $field => $value) {
			/* Extract mixed relationships, if any */
			if (substr($field, 0, 6) == 'mixed_') {
				$mixed_field = $this->get->mixed_crud_field($field);

				/* 
				 * Description:
				 * 
				 * $mixed_field[0] --> table name
				 * $mixed_field[1] --> field name
				 * $mixed_field[2] --> mixed field id
				 * 
				 */

				/* Security Check: Check CREATE permissions for this particular entry (table:mixed_<t1>_<ft2>) */
				if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_create, $this->config['name'], 'mixed_' . $this->config['name'] . '_' . $mixed_field[0])) {
					/* REST JSON requests do not silently ignore unaccessible/non-permitted fields. A forbidden indication must be raised. */
					if ($this->request->is_json())
						$this->response->code('403', NDPHP_LANG_MOD_CANNOT_INSERT_FIELD_NO_PRIV . $field, $this->config['default_charset'], !$this->request->is_ajax());

					/* Other type of requests will unset the unaccessible/non-permitted field and keep processing the remaining */
					$this->request->post_unset($field);
					continue;
				}

				/* Assign mixed rel value */
				$mixed_rels[$mixed_field[0]][$mixed_field[2]][$mixed_field[1]] = $value;

				/* Keep track of the mixed field and remove it from $_POST */
				$log_removed_fields[$field] = $this->request->post($field);
				$this->request->post_unset($field);
				continue;
			}

			/* Security check */
			if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_create, $this->config['name'], $field)) {
				/* REST JSON requests do not silently ignore unaccessible/non-permitted fields. A forbidden indication must be raised. */
				if ($this->request->is_json())
					$this->response->code('403', NDPHP_LANG_MOD_CANNOT_INSERT_FIELD_NO_PRIV . $field, $this->config['default_charset'], !$this->request->is_ajax());

				/* Other type of requests will unset the unaccessible/non-permitted field and keep processing the remaining */
				$this->request->post_unset($field);
				continue;
			}

			/* Get any possible multiple relationships, removing them from $_POST array. */
			if (substr($field, 0, 4) == 'rel_') {
				$table = $field;
				$rel[$table] = $value;
				/* Keep track of the relational field and remove it from $_POST */
				$log_removed_fields[$field] = $this->request->post($field);
				$this->request->post_unset($field);
			} else if ($ftypes[$field]['type'] == 'datetime') {
				/* Datetime field types requires special processing in order to append
				 * the 'time' component to the 'date' if the request contains a <field>_time property.
				 */
				if ($this->request->post_isset($field . '_time')) {
					$dt_value = $value . ' ' . $this->request->post($field . '_time');
					$this->request->post_unset($field . '_time');
				} else {
					$dt_value = $value;
				}

				$this->request->post_set($field, $this->timezone->convert($dt_value, $this->config['session_data']['timezone'], $this->config['default_timezone']));
			}

			/* Check if fields are empty and unset them if so, but only if this isn't a REST call */
			if (!$this->request->is_json() && (($this->request->post($field) == NULL) || (trim($this->request->post($field), ' \t') == ''))) {
				/* 
				 * Boolean fields (checkboxes) are set as 0 by default, using a hidden
				 * field in the create view.
				 */

				/* Remove empty fields */
				$this->request->post_unset($field);
			}

			/* Grant that foreign table id is eligible to be inserted */
			if (substr($field, -3) == '_id') {
				if (!$this->filter->table_row_perm($value, substr($field, 0, -3), $this->security->perm_read))
					$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED . ' #2', $this->config['default_charset'], !$this->request->is_ajax());
			}

			/* If an input pattern was defined for this field, grant that it matches the field value */
			if ($ftypes[$field]['input_pattern']) {
				if (!preg_match('/^' . $ftypes[$field]['input_pattern'] . '$/u', $this->request->post($field)))
					$this->response->code('422', NDPHP_LANG_MOD_INVALID_FIELD_DATA_PATTERN . ' \'' . $field . '\'', $this->config['default_charset'], !$this->request->is_ajax());
			}
		}

		/* We need to merge any table row filtering fields (such as users_id) with the POST data
		 * in order to correctly set the table row filtering permissions (if any was configured on $_table_row_filter_config).
		 */
		$this->request->post_set_all(array_merge($this->request->post(), $this->filter->table_row_get()));
 
		/* Initialize transaction */
		$this->db->trans_begin();

		/* Insert data into database */
		$this->db->insert($this->config['name'], $this->request->post());

		/* Check if there was constraint violations */
		if ($this->db->error_code() == '23000' || $this->db->error_code() == '40002') {
			$this->db->trans_rollback();
			$this->response->code('409', NDPHP_LANG_MOD_UNABLE_INSERT_ENTRY_CONFLICT, $this->config['default_charset'], !$this->request->is_ajax());
		}

		$last_id = $this->db->last_insert_id();
		
		if (!$last_id) {
			$this->db->trans_rollback();
			$this->response->code('500', NDPHP_LANG_MOD_UNABLE_INSERT_ENTRY, $this->config['default_charset'], !$this->request->is_ajax());
		}

		/* If logging is enabled, check for changed fields and log them */
		$this->logging->trans_begin();

		/* Set 'id' key as the last inserted id */
		$this->request->post_set('id', $last_id);

		foreach (array_merge($this->request->post(), $log_removed_fields) as $pfield => $pvalue) {
			/* If $pvalue is of type array and contains a control value (zero value) as it's last element, pop it out */
			if ((gettype($pvalue) == 'array') && !end($pvalue))
				array_pop($pvalue);

			$this->logging->log(
				/* op         */ 'INSERT',
				/* table      */ $this->config['name'],
				/* field      */ $pfield,
				/* entry_id   */ $last_id,
				/* value_new  */ (gettype($pvalue) == 'array') ? implode(',', $pvalue) : $pvalue,
				/* value_old  */ NULL,
				/* session_id */ $this->config['session_data']['sessions_id'],
				/* user_id    */ $this->config['session_data']['user_id'],
				/* log it?    */ $this->config['logging']
			);
		}

		$this->request->post_unset('id');

		$this->logging->trans_end();


		/* Process file uploads */
		foreach ($file_uploads as $file) {
			$upload_status = $this->upload->process_file($this->config['name'], $last_id, $file);

			/* Check if upload succeeded */
			if ($upload_status[0] !== true) {
				$this->db->trans_rollback();

				/* TODO: FIXME: Remove already uploaded files */

				$this->response->code($upload_status[1], $upload_status[2], $this->config['default_charset'], !$this->request->is_ajax());
			}
		}

		/* Insert relationships, if any */
		if ($rel) {
			foreach ($rel as $table => $value) {
				/* Retrieve the relationship table */
				$rel_table = array_pop(array_diff($this->get->multiple_rel_table_names($table, $this->config['name']), array($this->config['name'])));

				/* Security Permissions Check */
				if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_read, $rel_table)) {
					/* REST JSON requests do not silently ignore unaccessible/non-permitted fields. A forbidden indication must be raised. */
					if ($this->request->is_json()) {
						$this->db->trans_rollback();
						$this->response->code('403', NDPHP_LANG_MOD_CANNOT_READ_FOREIGN_NO_PRIV . $field, $this->config['default_charset'], !$this->request->is_ajax());
					}

					/* Other type of requests will unset the unaccessible/non-permitted field and keep processing the remaining */
					continue;
				}

				/* TODO: FIXME: Already checked earlier on multiple relationship pre-processing... Does it make sense to keep this here? */
				if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_create, $this->config['name'], $table)) {
					/* REST JSON requests do not silently ignore unaccessible/non-permitted fields. A forbidden indication must be raised. */
					if ($this->request->is_json()) {
						$this->db->trans_rollback();
						$this->response->code('403', NDPHP_LANG_MOD_CANNOT_INSERT_FIELD_NO_PRIV . $field, $this->config['default_charset'], !$this->request->is_ajax());
					}

					/* Other type of requests will unset the unaccessible/non-permitted field and keep processing the remaining */
					continue;
				}

				/* TODO: FIXME: Check CREATE permissions for this particular entry (table:rel_<t1>_<ft2>)
				 * [This is already checked on the pre-processing routines... Do we need to recheck here?]
				 */

				/* Remove all related entries from relational table */
				$this->db->delete($table, array($this->config['name'] . '_id' => $last_id));
			
				/* Check if $value contains any data */				
				if (!$value)
					continue;

				/* Insert new relationships */
				foreach ($value as $rel_id) {
					if (!$rel_id) /* Ignore the None (hidden) value */
						continue;

					if (!$this->filter->table_row_perm($rel_id, $rel_table, $this->security->perm_read)) {
						$this->db->trans_rollback();
						$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED . ' #3', $this->config['default_charset'], !$this->request->is_ajax());
					}

					$this->db->insert($table, array($this->config['name'] . '_id' => $last_id, $rel_table . '_id' => $rel_id));
				}
			}
		}

		/* Insert mixed relationships. */
		$this->_load_module('process', true);
		$this->process->mixed_post_data($mixed_rels, $last_id, $ftypes);

		/* Commit transaction */
		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			$this->response->code('500', NDPHP_LANG_MOD_FAILED_INSERT, $this->config['default_charset'], !$this->request->is_ajax());
		} else {
			$this->db->trans_commit();
		}

		/* Load post plugins */
		if ($this->config['plugins_enabled']) {
			foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/insert_post.php') as $plugin)
				include($plugin);
		}

		/* Post-Insert hook */
		$this->_hook_insert_post($last_id, $this->request->post(), $ftypes, $hook_pre_return);

		if ($retid) {
			return $last_id;
		} else {
			/* Echo the $last_id so it can be read by the ajax call in create_data.php.
			 * This value will be used to asynchronously load the /load_body_view/<id>
			 * in the success handler of the ajax call.
			 */
			if ($this->config['json_replies'] === true) {
				$this->response->output($this->rest->json_insert($last_id));
				return;
			} else if ($this->request->is_ajax()) {
				$this->response->output($last_id);
			} else {
				redirect($this->config['name'] . "/view/" . $last_id);
			}
		}
	}
	
	protected function edit_generic($id = 0) {
		/* Check if this is a view table type */
		if ($this->config['table_type_view'])
			$this->response->code('403', NDPHP_LANG_MOD_CANNOT_OP_VIEW_TYPE_CTRL . ' EDIT.', $this->config['default_charset'], !$this->request->is_ajax());

		/* Security Permissions Check */
		if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_update, $this->config['name']))
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->config['default_charset'], !$this->request->is_ajax());

		if (!$this->filter->table_row_perm($id, $this->config['name'], $this->security->perm_update))
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->config['default_charset'], !$this->request->is_ajax());

		/* Initialize $data */
		$data = array();

		/* Load enter plugins */
		if ($this->config['plugins_enabled']) {
			foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/edit_generic_enter.php') as $plugin)
				include($plugin);
		}

		/* Hook handler (enter) */
		$hook_enter_return = $this->_hook_edit_generic_enter($data, $id);

		/* Get view title */
		$title = NULL;

		if (isset($this->config['menu_entries_aliases'][$this->config['name']])) {
			$title = $this->config['menu_entries_aliases'][$this->config['name']] . $this->config['view_title_sep'] . NDPHP_LANG_MOD_OP_EDIT;
		} else {
			$title = $this->config['viewhname'] . $this->config['view_title_sep'] . NDPHP_LANG_MOD_OP_EDIT;
		}

		/* Get view description value */
		$description = NDPHP_LANG_MOD_OP_EDIT . " " . $this->config['viewhname'];

		/* Setup basic view data */
		$data = array_merge_recursive($data, $this->get->view_data_generic($title, $description));

		/* Setup specific view data */
		$data['config']['choices'] = count($this->config['rel_choice_hide_fields_edit']) ? $this->config['rel_choice_hide_fields_edit'] : $this->config['rel_choice_hide_fields'];
		$data['config']['choices_filters'] = $this->config['rel_choice_filter_fields_options'];
		$data['config']['render'] = array();
		$data['config']['render']['images'] = $this->config['view_image_file_rendering'];
		$data['config']['render']['size'] = $this->config['view_image_file_rendering_size_view'];
		$data['config']['render']['ext'] = $this->config['view_image_file_rendering_ext'];
		$data['config']['mixed'] = array();
		$data['config']['mixed']['autocomplete'] = $this->config['mixed_views_autocomplete'];

		$data['view']['fields'] = $this->get->fields(NULL, $this->config['hide_fields_edit']); /* _get_fields() uses a perm_read filter by default */
		$data['view']['links']['submenu'] = $this->config['links_submenu_body_edit'];
		$data['view']['id'] = $id;

		/* If logging is enabled, log this read access */
		$this->logging->log(
			/* op         */ 'READ',
			/* table      */ $this->config['name'],
			/* field      */ 'id',
			/* entry_id   */ $id,
			/* value_new  */ NULL,
			/* value_old  */ NULL,
			/* session_id */ $this->config['session_data']['sessions_id'],
			/* user_id    */ $this->config['session_data']['user_id'],
			/* log it?    */ $this->config['logging']
		);

		/* Select only the fields that were returned by _get_fields() */
		$this->filter->selected_fields($data['view']['fields'], array($this->config['name'] . '.id' => $id));
		$data['view']['result_array'] = $this->field->value_mangle($data['view']['fields'], $this->db->get($this->config['name']), $id);

		$data['view']['rel'] = array();

		/* Process multiple relationships */
		foreach ($data['view']['fields'] as $field => $meta) {
			if ($meta['type'] == 'rel') {
				if ($this->request->is_json()) {
					$this->db->select($meta['rel_table'] . '.' . $meta['table'] . '_id AS id,' . $meta['rel_table'] . '.' . $meta['table'] . '_id AS item');
					$this->db->from($meta['rel_table']);
					$this->db->where($meta['rel_table'] . '.' . $this->config['name'] . '_id', $id);
				} else {
					/* Query the database to retrieve the selected elements for this ID */
					$this->db->select($meta['table'] . '.id AS id,' . $meta['table'] . '.' . $meta['rel_field'] . ' AS item');
					//$this->db->from($this->config['name']);
					$this->db->from($meta['rel_table']);
					//$this->db->join($meta['rel_table'], $this->config['name'] . '.id = ' . $meta['rel_table'] . '.' . $this->config['name'] . '_id', 'left');
					$this->db->join($meta['table'], $meta['table'] . '.id = ' . $meta['rel_table'] . '.' . $meta['table'] . '_id', 'left');
					//$this->db->where($this->config['name'] . '.id', $id);
					$this->db->where($meta['rel_table'] . '.' . $this->config['name'] . '_id', $id);
					$this->db->having('`item` IS NOT NULL');
				}

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

		/* Check if we've the required fields and default values cached */
		if ($this->cache->is_active()
				&& $this->cache->get('s_create_required_' . $this->config['name'])) {
			$data['view']['required'] = $this->cache->get('d_create_required_' . $this->config['name']);
		} else {
			/* Required fields are extracted from information schema
			 *
			 * NOTE: This requires DBMS to operate in 'strict' mode.
			 * Make sure that 'strict' config parameter is set to TRUE in user/config/database.php
			 *  
			 */
			$schema = $this->load->database($this->config['default_database'] . '_schema', true);

			$schema->select('COLUMN_NAME');
			$schema->from('COLUMNS');
			$schema->where('TABLE_SCHEMA', $this->db->database);
			$schema->where('TABLE_NAME', $this->config['name']);
			$schema->where('IS_NULLABLE', 'NO');
			$query = $schema->get();
			$schema->close();

			$data['view']['required'] = array();
			foreach ($query->result_array() as $row) {
				array_push($data['view']['required'], $row['COLUMN_NAME']);
			}

			/* If cache is enabled, cache required fields and default values data */
			if ($this->cache->is_active()) {
				$this->cache->set('s_create_required_' . $this->config['name'], true);
				$this->cache->set('d_create_required_' . $this->config['name'], $data['view']['required']);
			}
		}

		/* Hidden fields */
		$data['config']['hidden_fields'] = $this->config['hide_fields_edit'];

		/* Check if there are any entry fields to be appended to the view title */
		$title_suffix = '';

		if (count($this->config['view_title_append_fields'])) {
			foreach ($this->config['view_title_append_fields'] as $title_append) {
				/* There's a minor exception for the edit view: The 'id' field isn't part of the result array */
				if ($title_append == 'id') {
					$title_suffix .= $this->config['view_title_append_sep'] . $id;
				} else {
					$title_suffix .= $this->config['view_title_append_sep'] . $data['view']['result_array'][0][$title_append];
				}
			}
		}

		/* Update title and breadcrumb */
		$data['view']['title'] .= $title_suffix;
		$data['view']['links']['breadcrumb'] = $this->get->view_breadcrumb('edit', NDPHP_LANG_MOD_OP_EDIT, $id, ltrim($title_suffix, $this->config['view_title_append_sep']));

		/* Load leave plugins */
		if ($this->config['plugins_enabled']) {
			foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/edit_generic_leave.php') as $plugin)
				include($plugin);
		}

		/* Hook handler (leave) */
		$this->_hook_edit_generic_leave($data, $id, $hook_enter_return);

		/* All good */
		return $data;
	}

	public function edit_mixed_rel_count($foreign_table = '', $foreign_id) {
		$this->load->database($this->config['default_database']);

		if (!$this->filter->table_row_perm($foreign_id, $foreign_table, $this->security->perm_read))
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->config['default_charset'], !$this->request->is_ajax());

		$this->db->select('COUNT(`' . str_replace('`', '', $foreign_table) . '_id`) AS `total`', false);
		$this->db->from('mixed_' . $foreign_table . '_' . $this->config['name']);
		$this->db->where($foreign_table . '_id', $foreign_id);

		$query = $this->db->get();
		
		$row = $query->row_array();
		
		/* Return total rows to ajax request */
		$this->response->output($row['total']);
	}

	public function edit_mixed_rel($mid, $foreign_table = '', $foreign_id = 0) {
		$data = $this->edit_generic();
		$data['config']['hidden_fields'] = $this->config['mixed_hide_fields_edit'];
		$data['config']['mixed']['table_field_width'] = $this->config['mixed_table_fields_width'];

		$data['view']['mixed_id'] = $mid;
		$data['view']['foreign_id'] = $foreign_id;
		$data['view']['foreign_table'] = $foreign_table;
		$data['view']['values'] = array();

		if ($foreign_table != '') {
			$this->load->database($this->config['default_database']);

			if (!$this->filter->table_row_perm($foreign_id, $foreign_table, $this->security->perm_read))
				$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->config['default_charset'], !$this->request->is_ajax());

			$this->db->from('mixed_' . $foreign_table . '_' . $this->config['name']);
			$this->db->where($foreign_table . '_id', $foreign_id);
			$this->db->order_by('id', 'asc');
			$this->db->limit(1, $mid - 1);

			/* We need to mangle the relationship data. We also use _get_fields_basic_types() to avoid the
			 * overhead of calling _get_fields()
			 */
			$result_array = $this->field->value_mangle($this->get->fields_basic_types('mixed_' . $foreign_table . '_' . $this->config['name']), $this->db->get());
			
			/* NOTE: $field_name must have a UNIQUE constraint in order to trigger the following validation */
			if (count($result_array) == 1) {
				$row = array_values($result_array)[0];
				$data['view']['values'] = $row;
			}
		}

		/* Remove the fields that are not present in the mixed relational table */
		$data['view']['present_fields'] = $this->get->mixed_table_fields($this->config['name'], $foreign_table);

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

	public function update($id = NULL, $field = NULL, $field_value = NULL, $retbool = false) {
		$this->_load_module('upload', true);

		/* NOTE: If $retbool is true, a boolean true value is returned on success (on failure, die() will always be called) */

		$log_removed_fields = array(); /* Keep track of unset fields from POST data that still need to be logged */

		/* If an 'id' value was passed as function parameter, use it to replace/assign the actual $_POST['id'] (Used by JSON REST API) */
		if ($id !== NULL)
			$this->request->post_set('id', $id);

		/* Grant that 'id' field is set */
		if (!$this->request->post_isset('id'))
			$this->response->code('403', NDPHP_LANG_MOD_MISSING_REQUIRED_FIELD . ' id', $this->config['default_charset'], !$this->request->is_ajax());

		/* Check if this is a view table type */
		if ($this->config['table_type_view'])
			$this->response->code('403', NDPHP_LANG_MOD_CANNOT_OP_VIEW_TYPE_CTRL . ' UPDATE.', $this->config['default_charset'], !$this->request->is_ajax());

		/* Security Permissions Check */
		if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_update, $this->config['name']))
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->config['default_charset'], !$this->request->is_ajax());

		if (!$this->filter->table_row_perm($this->request->post('id'), $this->config['name'], $this->security->perm_update))
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->config['default_charset'], !$this->request->is_ajax());

		/* Set/Update the value of the specified field. (JSON REST API) */
		if ($field !== NULL && $value !== NULL)
			$this->request->post_set($field, $field_value);

		/* Retrieve fields meta data */
		$ftypes = $this->get->fields();
		$mixed_rels = array();
		$multiple_rels = array();

		/* Load pre plugins */
		if ($this->config['plugins_enabled']) {
			foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/update_pre.php') as $plugin)
				include($plugin);
		}

		/* Pre-Update hook */
		$hook_pre_return = $this->_hook_update_pre($this->request->post('id'), $this->request->post(), $ftypes);

		/* Pre-process file uploads */
		$pre_process_status = $this->upload->pre_process();

		if ($pre_process_status[0] !== true)
			$this->response->code($pre_process_status[1], $pre_process_status[2], $this->config['default_charset'], !$this->request->is_ajax());

		$file_uploads = $pre_process_status[2];

		/* Initialize transaction */
		$this->db->trans_begin();

		/* Process multiple relationships and special fields first */
		foreach ($this->request->post() as $field => $value) {
			/* Extract mixed relationships, if any */
			if (substr($field, 0, 6) == 'mixed_') {
				$mixed_field = $this->get->mixed_crud_field($field);

				/* 
				 * Description:
				 * 
				 * $mixed_field[0] --> table name
				 * $mixed_field[1] --> field name
				 * $mixed_field[2] --> mixed field id
				 * 
				 */

				/* Security Check: Check UPDATE permissions for this particular entry (table:mixed_<t1>_<ft2>) */
				if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_update, $this->config['name'], 'mixed_' . $this->config['name'] . '_' . $mixed_field[0])) {
					/* REST JSON requests do not silently ignore unaccessible/non-permitted fields. A forbidden indication must be raised. */
					if ($this->request->is_json()) {
						$this->db->trans_rollback();
						$this->response->code('403', NDPHP_LANG_MOD_CANNOT_UPDATE_FIELD_NO_PRIV . $field, $this->config['default_charset'], !$this->request->is_ajax());
					}

					/* Other type of requests will unset the unaccessible/non-permitted field and keep processing the remaining */
					$this->request->post_unset($field);
					continue;
				}

				/* Assign mixed rel value */
				$mixed_rels[$mixed_field[0]][$mixed_field[2]][$mixed_field[1]] = $value;

				/* Keep track of the mixed field and remove it from $_POST */
				$log_removed_fields[$field] = $this->request->post($field);
				$this->request->post_unset($field);

				continue;
			}

			/* Datetime field types requires special processing in order to append
			 * the 'time' component to the 'date'.
			 */
			if ($ftypes[$field]['type'] == 'datetime') {
				/* Datetime field types requires special processing in order to append
				 * the 'time' component to the 'date' if the request contains a <field>_time property.
				 */
				if ($this->request->post_isset($field . '_time')) {
					$dt_value = $value . ' ' . $this->request->post($field . '_time');
					$this->request->post_unset($field . '_time');
				} else {
					$dt_value = $value;
				}

				$this->request->post_set($field, $this->timezone->convert($dt_value, $this->config['session_data']['timezone'], $this->config['default_timezone']));

				continue;
			}

			/* Check if this is a multiple realtionship field */
			if (substr($field, 0, 4) != 'rel_')
				continue; /* If not, skip multiple relationship processing */

			/* Set the table name */
			$table = $field;

			/* Retrieve the relationship table */
			$rel_table = array_pop(array_diff($this->get->multiple_rel_table_names($table, $this->config['name']), array($this->config['name'])));

			/* Security Permissions Check (READ) -- We must be able to read the foreign table... */
			if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_read, $rel_table)) {
				/* REST JSON requests do not silently ignore unaccessible/non-permitted fields. A forbidden indication must be raised. */
				if ($this->request->is_json()) {
					$this->db->trans_rollback();
					$this->response->code('403', NDPHP_LANG_MOD_CANNOT_READ_FOREIGN_NO_PRIV . $rel_table, $this->config['default_charset'], !$this->request->is_ajax());
				}

				/* Other type of requests will unset the unaccessible/non-permitted field and keep processing the remaining */
				continue;
			}

			/* Security Permissions Check (UPDATE) -- We must be able to update the multiple relationship table */
			if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_update, $this->config['name'], $field)) {
				/* REST JSON requests do not silently ignore unaccessible/non-permitted fields. A forbidden indication must be raised. */
				if ($this->request->is_json()) {
					$this->db->trans_rollback();
					$this->response->code('403', NDPHP_LANG_MOD_CANNOT_UPDATE_FIELD_NO_PRIV . $field, $this->config['default_charset'], !$this->request->is_ajax());
				}

				/* Other type of requests will unset the unaccessible/non-permitted field and keep processing the remaining */
				continue;
			}

			/* Add multiple relationship data to $multiple_rels array to be processed later
			 * (TODO: the following procedure should be consolidated into a _rel_process_post_field() method)
			 */
			$multiple_rels[$field] = array(
				'table' => $table,
				'rel_table' => $rel_table,
				'values' => $value
			);

			/* Keep track of the relational field and remove it from $_POST */
			$log_removed_fields[$field] = $this->request->post($field);
			$this->request->post_unset($field);
		}

		/* Set all empty fields ('') to NULL and evaluate column permissions. Also grant input pattern matching. */
		foreach ($this->request->post() as $field => $value) {
			/* Security check */
			if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_update, $this->config['name'], $field)) {
				/* Ignore 'id' field, as it cannot be unset since it will be used during the rest of the update() procedure */
				if ($field == 'id')
					continue;

				/* REST JSON requests do not silently ignore unaccessible/non-permitted fields. A forbidden indication must be raised. */
				if ($this->request->is_json()) {
					$this->db->trans_rollback();
					$this->response->code('403', NDPHP_LANG_MOD_CANNOT_UPDATE_FIELD_NO_PRIV . $field, $this->config['default_charset'], !$this->request->is_ajax());
				}

				/* Other type of requests will unset the unaccessible/non-permitted field and keep processing the remaining */
				$this->request->post_unset($field);

				continue;
			}

			/* Check if this is a file field that was requested to be removed */
			if (substr($field, 0, 6) == '_file_') {
				if (($this->request->post_isset($field . '_remove') && $this->request->post($field . '_remove')) || ($this->request->post($field) === NULL)) {
					/* Get file metadata */
					$this->db->select($field);
					$this->db->from($this->config['name']);
					$this->db->where('id', $this->request->post('id'));
					$q = $this->db->get();
					$row = $q->row_array();

					/* Remove the file from filesystem */
					$remove_status = $this->upload->remove_file($this->config['name'], $this->request->post('id'), array($field, $row[$field]));

					/* Check if file removal has succeeded */
					if ($remove_status[0] !== true) {
						$this->db->trans_rollback();

						$this->response->code($remove_status[1], $remove_status[2], $this->config['default_charset'], !$this->request->is_ajax());
					}

					/* Reset database field value */
					$this->request->post_set($field, NULL);
				} else if (!$this->upload->in_file_uploads($field, $file_uploads)) {
					/* Otherwise, if the file was not requested to be removed and no file was uploaded, prevent any update to this field */
					$this->request->post_unset($field);
				}

				continue;
			}

			/* Grant that foreign table id is eligible to be updated */
			if (substr($field, -3) == '_id') {
				if (!$this->filter->table_row_perm($value, substr($field, 0, -3), $this->security->perm_read)) {
					$this->db->trans_rollback();
					$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->config['default_charset'], !$this->request->is_ajax());
				}
			}

			/* Set to NULL if empty, but only if this isn't a REST call */
			if (!$this->request->is_json() && (trim($this->request->post($field), ' \t') == '')) {
				$this->request->post_set($field, NULL);
			}

			/* If an input pattern was defined for this field, grant that it matches the field value */
			if ($ftypes[$field]['input_pattern']) {
				if (!preg_match('/^' . $ftypes[$field]['input_pattern'] . '$/u', $this->request->post($field))) {
					$this->db->trans_rollback();
					$this->response->code('422', NDPHP_LANG_MOD_INVALID_FIELD_DATA_PATTERN . ' \'' . $field . '\'', $this->config['default_charset'], !$this->request->is_ajax());
				}
			}
		}

		/* If logging is enabled, check for changed fields and log them */
		$this->logging->trans_begin();

		$changed_fields = $this->get->post_changed_fields_data($this->config['name'], $this->request->post('id'), array_merge($this->request->post(), $log_removed_fields));

		foreach ($changed_fields as $cfield) {
			$this->logging->log(
				/* op         */ 'UPDATE',
				/* table      */ $this->config['name'],
				/* field      */ $cfield['field'],
				/* entry_id   */ $this->request->post('id'),
				/* value_new  */ $cfield['value_new'],
				/* value_old  */ $cfield['value_old'],
				/* session_id */ $this->config['session_data']['sessions_id'],
				/* user_id    */ $this->config['session_data']['user_id'],
				/* log it?    */ $this->config['logging']
			);
		}

		$this->logging->trans_end();


		/* Update entry data */
		$this->db->where('id', $this->request->post('id'));
		$qr = $this->db->update($this->config['name'], $this->request->post());

		/* Check if any row was affected */
		if (!$qr->num_rows()) {
			$this->db->trans_rollback();
			$this->response->code('404', NDPHP_LANG_MOD_INFO_ENTRY_NOT_FOUND, $this->config['default_charset'], !$this->request->is_ajax());
		}

		/* Check if there was constraint violations */
		if ($this->db->error_code() == '23000' || $this->db->error_code() == '40002') {
			$this->db->trans_rollback();
			$this->response->code('409', NDPHP_LANG_MOD_UNABLE_UPDATE_ENTRY_CONFLICT, $this->config['default_charset'], !$this->request->is_ajax());
		}

		/* Process file uploads */
		foreach ($file_uploads as $file) {
			/* Upload new file */
			$upload_status = $this->upload->process_file($this->config['name'], $this->request->post('id'), $file);

			/* Check if upload succeeded */
			if ($upload_status[0] !== true) {
				$this->db->trans_rollback();

				/* TODO: FIXME: Already uploaded files cannot be rolled back with this implementation */

				$this->response->code($upload_status[1], $upload_status[2], $this->config['default_charset'], !$this->request->is_ajax());
			}

			/* Get current stored file metadata from database */
			$this->db->select($file[0]);
			$this->db->from($this->config['name']);
			$this->db->where('id', $this->request->post('id'));
			$q = $this->db->get();
			$file_old = $q->row_array();

			/* Attempt to remove old file, only if there's a path set */
			if (isset($file_old[$file[0]]['path'])) {
				/* Remove older file */
				$remove_status = $this->upload->remove_file($this->config['name'], $this->request->post('id'), array($file[0], $file_old[$file[0]]));

				/* Check if file removal has succeeded */
				if ($remove_status[0] !== true) {
					$this->db->trans_rollback();

					$this->response->code($remove_status[1], $remove_status[2], $this->config['default_charset'], !$this->request->is_ajax());
				}
			}
		}

		/* Set the last inserted ID variable */
		$last_id = $this->request->post('id');

		/* Process mixed relationships if there are any to be updated */
		$this->_load_module('process', true);
		$this->process->mixed_post_data($mixed_rels, $last_id, $ftypes, true);

		/* Process multiple relationships (TODO: the following procedures should be consolidated into a _rel_process_post_data() method) */
		foreach ($multiple_rels as $rel_field) {
			/* Remove all related entries from relational table */
			$this->db->delete($rel_field['table'], array($this->config['name'] . '_id' => $this->request->post('id')));

			/* Check if there are any values to be inserted */
			if (!$rel_field['values'])
				continue;

			/* Insert new relationships */
			foreach ($rel_field['values'] as $rel_id) {
				if (!$rel_id) /* Ignore the None (hidden) value */
					continue;

				if (!$this->filter->table_row_perm($rel_id, $rel_field['rel_table'], $this->security->perm_read)) {
					$this->db->trans_rollback();
					$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->config['default_charset'], !$this->request->is_ajax());
				}

				$this->db->insert($rel_field['table'], array($this->config['name'] . '_id' => $this->request->post('id'), $rel_field['rel_table'] . '_id' => $rel_id));
			}
		}

		/* Commit transaction */
		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			$this->response->code('500', NDPHP_LANG_MOD_FAILED_UPDATE, $this->config['default_charset'], !$this->request->is_ajax());
		} else {
			$this->db->trans_commit();
		}

		/* Load post plugins */
		if ($this->config['plugins_enabled']) {
			foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/update_post.php') as $plugin)
				include($plugin);
		}

		/* Post-Update hook */
		$this->_hook_update_post($this->request->post('id'), $this->request->post(), $ftypes, $hook_pre_return);

		if ($retbool) {
			return true;
		} else {
			if ($this->config['json_replies'] === true) {
				$this->response->output($this->rest->json_update());
				return;
			} else if ($this->request->is_ajax()) {
				$this->response->output($this->request->post('id'));
			} else {
				redirect($this->config['name'] . '/view/' . $this->request->post('id'));
			}
		}
	}

	protected function remove_generic($id = 0) {
		/* Check if this is a view table type */
		if ($this->config['table_type_view'])
			$this->response->code('403', NDPHP_LANG_MOD_CANNOT_OP_VIEW_TYPE_CTRL . ' REMOVE.', $this->config['default_charset'], !$this->request->is_ajax());

		/* Security Permissions Check */
		if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_read, $this->config['name']))
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->config['default_charset'], !$this->request->is_ajax());

		if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_delete, $this->config['name']))
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->config['default_charset'], !$this->request->is_ajax());

		if (!$this->filter->table_row_perm($id, $this->config['name'], $this->security->perm_delete))
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->config['default_charset'], !$this->request->is_ajax());

		/* Initialize $data */
		$data = array();

		/* Load enter plugins */
		if ($this->config['plugins_enabled']) {
			foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/remove_generic_enter.php') as $plugin)
				include($plugin);
		}

		/* Hook handler (enter) */
		$hook_enter_return = $this->_hook_remove_generic_enter($data, $id);

		/* Get view title */
		$title = NULL;

		if (isset($this->config['menu_entries_aliases'][$this->config['name']])) {
			$title = $this->config['menu_entries_aliases'][$this->config['name']] . $this->config['view_title_sep'] . NDPHP_LANG_MOD_OP_REMOVE;
		} else {
			$title = $this->config['viewhname'] . $this->config['view_title_sep'] . NDPHP_LANG_MOD_OP_REMOVE;
		}

		/* Get view description value */
		$description = NDPHP_LANG_MOD_OP_REMOVE . " " . $this->config['viewhname'];

		/* Setup basic view data */
		$data = array_merge_recursive($data, $this->get->view_data_generic($title, $description));

		/* Setup specific view data */
		$data['view']['fields'] = $this->get->fields(NULL, $this->config['hide_fields_remove']); /* _get_fields() uses a perm_read filter by default */
		$data['view']['links'] = array();
		$data['view']['links']['submenu'] = $this->config['links_submenu_body_remove'];

		$data['config']['choices'] = count($this->config['rel_choice_hide_fields_remove']) ? $this->config['rel_choice_hide_fields_remove'] : $this->config['rel_choice_hide_fields'];
		$data['config']['choices_filters'] = array();
		$data['config']['render']['images'] = $this->config['view_image_file_rendering'];
		$data['config']['render']['size'] = $this->config['view_image_file_rendering_size_view'];
		$data['config']['render']['ext'] = $this->config['view_image_file_rendering_ext'];

		$data['view']['id'] = $id;

		/* If logging is enabled, log this read access */
		$this->logging->log(
			/* op         */ 'READ',
			/* table      */ $this->config['name'],
			/* field      */ 'id',
			/* entry_id   */ $id,
			/* value_new  */ NULL,
			/* value_old  */ NULL,
			/* session_id */ $this->config['session_data']['sessions_id'],
			/* user_id    */ $this->config['session_data']['user_id'],
			/* log it?    */ $this->config['logging']
		);


		/* Select only the fields that were returned by _get_fields() */
		$this->filter->selected_fields($data['view']['fields'], array($this->config['name'] . '.id' => $id));
		$data['view']['result_array'] = $this->field->value_mangle($data['view']['fields'], $this->db->get($this->config['name']));

		$data['view']['rel'] = array();

		/* Process multiple relationships */
		foreach ($data['view']['fields'] as $field => $meta) {
			if ($meta['type'] == 'rel') {
				if ($this->request->is_json()) {
					$this->db->select($meta['rel_table'] . '.' . $meta['table'] . '_id AS id,' . $meta['rel_table'] . '.' . $meta['table'] . '_id AS item');
					$this->db->from($meta['rel_table']);
					$this->db->where($meta['rel_table'] . '.' . $this->config['name'] . '_id', $id);
				} else {
					/* Query the database to retrieve the selected elements for this ID */
					$this->db->select($meta['table'] . '.id AS id,' . $meta['table'] . '.' . $meta['rel_field'] . ' AS item');
					//$this->db->from($this->config['name']);
					$this->db->from($meta['rel_table']);
					//$this->db->join($meta['rel_table'], $this->config['name'] . '.id = ' . $meta['rel_table'] . '.' . $this->config['name'] . '_id', 'left');
					$this->db->join($meta['table'], $meta['table'] . '.id = ' . $meta['rel_table'] . '.' . $meta['table'] . '_id', 'left');
					//$this->db->where($this->config['name'] . '.id', $id);
					$this->db->where($meta['rel_table'] . '.' . $this->config['name'] . '_id', $id);
					$this->db->having('`item` IS NOT NULL');
				}

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
		$data['config']['hidden_fields'] = $this->config['hide_fields_remove'];

		/* Check if there are any entry fields to be appended to the view title */
		$title_suffix = '';

		if (count($this->config['view_title_append_fields'])) {
			foreach ($this->config['view_title_append_fields'] as $title_append)
				$title_suffix .= $this->config['view_title_append_sep'] . $data['view']['result_array'][0][$title_append];
		}

		/* Update title and breadcrumb */
		$data['view']['title'] .= $title_suffix;
		$data['view']['links']['breadcrumb'] = $this->get->view_breadcrumb('remove', NDPHP_LANG_MOD_OP_REMOVE, $id, ltrim($title_suffix, $this->config['view_title_append_sep']));

		/* Load leave plugins */
		if ($this->config['plugins_enabled']) {
			foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/remove_generic_leave.php') as $plugin)
				include($plugin);
		}

		/* Hook handler (leave) */
		$this->_hook_remove_generic_leave($data, $id, $hook_enter_return);

		return $data;
	}

	public function remove_mixed_rel_count($foreign_table = '', $foreign_id) {
		$this->load->database($this->config['default_database']);

		if (!$this->filter->table_row_perm($foreign_id, $foreign_table, $this->security->perm_read))
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->config['default_charset'], !$this->request->is_ajax());

		$this->db->select('COUNT(`' . str_replace('`', '', $foreign_table) . '_id`) AS `total`', false);
		$this->db->from('mixed_' . $foreign_table . '_' . $this->config['name']);
		$this->db->where($foreign_table . '_id', $foreign_id);

		$query = $this->db->get();
		
		$row = $query->row_array();
		
		/* Return total rows to ajax request */
		$this->response->output($row['total']);
	}

	public function remove_mixed_rel($mid, $foreign_table = '', $foreign_id = 0) {
		$data = $this->remove_generic();

		$data['config']['hidden_fields'] = $this->config['mixed_hide_fields_remove'];

		$data['view']['mixed_id'] = $mid;
		$data['view']['foreign_id'] = $foreign_id;
		$data['view']['foreign_table'] = $foreign_table;
		$data['view']['values'] = array();

		if ($foreign_table != '') {
			$this->load->database($this->config['default_database']);

			if (!$this->filter->table_row_perm($foreign_id, $foreign_table, $this->security->perm_read))
				$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->config['default_charset'], !$this->request->is_ajax());

			$this->db->from('mixed_' . $foreign_table . '_' . $this->config['name']);
			$this->db->where($foreign_table . '_id', $foreign_id);
			$this->db->order_by('id', 'asc');
			$this->db->limit(1, $mid - 1);

			/* We need to mangle the relationship data. We also use _get_fields_basic_types() to avoid the
			 * overhead of calling _get_fields()
			 */
			$result_array = $this->field->value_mangle($this->get->fields_basic_types('mixed_' . $foreign_table . '_' . $this->config['name']), $this->db->get());
			
			/* NOTE: $field_name must have a UNIQUE constraint in order to trigger the following validation */
			if (count($result_array) == 1) {
				$row = array_values($result_array)[0];
				$data['view']['values'] = $row;
			}
		}

		/* Remove the fields that are not present in the mixed relational table */
		$data['view']['present_fields'] = $this->get->mixed_table_fields($this->config['name'], $foreign_table);

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
		$this->_load_module('upload', true);

		/* NOTE: If $retbool is true, a boolean true value is returned on success (on failure, die() will always be called) */

		/* Set/Update $_POST['id'] if $id is different than 0 (usually used by JSON REST API) */
		if ($id)
			$this->request->post_set('id', $id);

		/* Check if this is a view table type */
		if ($this->config['table_type_view'])
			$this->response->code('403', NDPHP_LANG_MOD_CANNOT_OP_VIEW_TYPE_CTRL . ' DELETE.', $this->config['default_charset'], !$this->request->is_ajax());

		/* Security Permissions Check */
		if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_delete, $this->config['name']))
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->config['default_charset'], !$this->request->is_ajax());

		if (!$this->filter->table_row_perm($this->request->post('id'), $this->config['name'], $this->security->perm_delete))
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->config['default_charset'], !$this->request->is_ajax());

		$ftypes = $this->get->fields();

		/* Load pre plugins */
		if ($this->config['plugins_enabled']) {
			foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/delete_pre.php') as $plugin)
				include($plugin);
		}

		/* Pre-Delete hook */
		$hook_pre_return = $this->_hook_delete_pre($this->request->post('id'), $this->request->post(), $ftypes);

		/* Init transaction */
		$this->db->trans_begin();

		/* Fetch the entry */
		$this->db->from($this->config['name']);
		$this->db->where('id', $this->request->post('id'));
		$q = $this->db->get();

		/* Check if any row was affected */
		if (!$q->num_rows()) {
			$this->db->trans_rollback();
			$this->response->code('404', NDPHP_LANG_MOD_INFO_ENTRY_NOT_FOUND, $this->config['default_charset'], !$this->request->is_ajax());
		}

		$row = $q->row_array();

		/* Remove uploaded files */
		foreach ($row as $k => $v) {
			if (substr($k, 0, 6) == '_file_') {
				/* Remove file */
				$remove_status = $this->upload->remove_file($this->config['name'], $this->request->post('id'), array($k, json_decode($row[$k], true)));

				/* Check if file removal has succeeded */
				if ($remove_status[0] !== true) {
					$this->db->trans_rollback();

					$this->response->code($remove_status[1], $remove_status[2], $this->config['default_charset'], !$this->request->is_ajax());
				}
			}
		}

		/* If logging is enabled, log this delete request */
		/* If logging is enabled, log this read access */
		$this->logging->log(
			/* op         */ 'DELETE',
			/* table      */ $this->config['name'],
			/* field      */ 'id',
			/* entry_id   */ $this->request->post('id'),
			/* value_new  */ NULL,
			/* value_old  */ NULL,
			/* session_id */ $this->config['session_data']['sessions_id'],
			/* user_id    */ $this->config['session_data']['user_id'],
			/* log it?    */ $this->config['logging']
		);


		/* We don't need to follow relationships as the foreign keys must be configured
		 * as CASCADE ON DELETE on the relational table.
		 */
		$this->db->where('id', $this->request->post('id'));
		$this->db->delete($this->config['name']);

		/* Commit transaction */
		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			$this->response->code('500', NDPHP_LANG_MOD_UNABLE_DELETE_ENTRY, $this->config['default_charset'], !$this->request->is_ajax());
		} else {
			$this->db->trans_commit();
		}

		/* NOTE: All relationships (including mixed relationships) shall be deleted through CASCADE events defined on the
		 * DBMS data model.
		 */

		/* Load post plugins */
		if ($this->config['plugins_enabled']) {
			foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/delete_post.php') as $plugin)
				include($plugin);
		}

		/* Post-Delete hook */
		$this->_hook_delete_post($this->request->post('id'), $this->request->post(), $ftypes, $hook_pre_return);

		if ($retbool) {
			return true;
		} else {
			if ($this->config['json_replies'] === true) {
				$this->response->output($this->rest->json_delete());
				return;
			} else if ($this->request->is_ajax()) {
				$this->response->output('OK'); /* FIXME: What should be replied? */
			} else {
				redirect($this->config['name']);
			}
		}
	}

	protected function view_generic($id = 0, $export = NULL) {
		/* Security Permissions Check */
		if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_read, $this->config['name']))
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->config['default_charset'], !$this->request->is_ajax());

		if (!$this->filter->table_row_perm($id, $this->config['name'], $this->security->perm_read))
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->config['default_charset'], !$this->request->is_ajax());

		/* Initialize $data */
		$data = array();

		/* Load enter plugins */
		if ($this->config['plugins_enabled']) {
			foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/view_generic_enter.php') as $plugin)
				include($plugin);
		}

		/* Hook handler (enter) */
		$hook_enter_return = $this->_hook_view_generic_enter($data, $id, $export);

		/* Setup charts, if this isn't a REST JSON request */
		if (!$this->request->is_json())
			$this->_load_module('charts', true);

		/* Get view title */
		$title = NULL;

		if (isset($this->config['menu_entries_aliases'][$this->config['name']])) {
			$title = $this->config['menu_entries_aliases'][$this->config['name']] . $this->config['view_title_sep'] . NDPHP_LANG_MOD_OP_VIEW;
		} else {
			$title = $this->config['viewhname'] . $this->config['view_title_sep'] . NDPHP_LANG_MOD_OP_VIEW;
		}

		/* Get view description value */
		$description = NDPHP_LANG_MOD_OP_VIEW . " " . $this->config['viewhname'];

		/* Setup basic view data */
		$data = array_merge_recursive($data, $this->get->view_data_generic($title, $description));

		/* Setup specific View data */
		$data['view']['fields'] = $this->get->fields(NULL, $this->config['hide_fields_view']); /* _get_fields() uses a perm_read filter by default */
		$data['view']['links']['submenu'] = $this->config['links_submenu_body_view'];

		if (!$this->request->is_json())
			$data['config']['charts']['total'] = $this->charts->count_charts_foreign();

		$data['config']['choices'] = count($this->config['rel_choice_hide_fields_view']) ? $this->config['rel_choice_hide_fields_view'] : $this->config['rel_choice_hide_fields'];
		$data['config']['choices_filters'] = array();
		$data['config']['render']['images'] = $this->config['view_image_file_rendering'];
		$data['config']['render']['size'] = $this->config['view_image_file_rendering_size_view'];
		$data['config']['render']['ext'] = $this->config['view_image_file_rendering_ext'];

		$data['view']['id'] = $id;

		/* Log the request type */
		if ($export === NULL) {
			/* If logging is enabled, log this read access */
			$this->logging->log(
				/* op         */ 'READ',
				/* table      */ $this->config['name'],
				/* field      */ 'id',
				/* entry_id   */ $id,
				/* value_new  */ NULL,
				/* value_old  */ NULL,
				/* session_id */ $this->config['session_data']['sessions_id'],
				/* user_id    */ $this->config['session_data']['user_id'],
				/* log it?    */ $this->config['logging']
			);
		} else {
			/* If logging is enabled, log this export request */
			$this->logging->log(
				/* op         */ 'EXPORT',
				/* table      */ $this->config['name'],
				/* field      */ 'id (' . strtoupper($export) . ')',
				/* entry_id   */ $id,
				/* value_new  */ NULL,
				/* value_old  */ NULL,
				/* session_id */ $this->config['session_data']['sessions_id'],
				/* user_id    */ $this->config['session_data']['user_id'],
				/* log it?    */ $this->config['logging']
			);
		}

		/* Select only the fields that were returned by _get_fields() */
		$this->filter->selected_fields($data['view']['fields'], array($this->config['name'] . '.id' => $id));
		$q = $this->db->get($this->config['name']);

		/* If this is a REST JSON request and the entry was not found, return 404 */
		if ($this->request->is_json() && !$q->num_rows())
			$this->response->code('404', NDPHP_LANG_MOD_INFO_ENTRY_NOT_FOUND, $this->config['default_charset'], !$this->request->is_ajax());

		/* Mangle the data received */
		$data['view']['result_array'] = $this->field->value_mangle($data['view']['fields'], $q);

		$data['view']['rel'] = array();

		/* Process multiple relationships */
		
		foreach ($data['view']['fields'] as $field => $meta) {
			if ($meta['type'] == 'rel') {
				if ($this->request->is_json()) {
					$this->db->select($meta['rel_table'] . '.' . $meta['table'] . '_id AS id,' . $meta['rel_table'] . '.' . $meta['table'] . '_id AS item');
					$this->db->from($meta['rel_table']);
					$this->db->where($meta['rel_table'] . '.' . $this->config['name'] . '_id', $id);
				} else {
					/* Query the database to retrieve the selected elements for this ID */
					$this->db->select($meta['table'] . '.id AS id,' . $meta['table'] . '.' . $meta['rel_field'] . ' AS item');
					$this->db->from($meta['rel_table']);
					$this->db->join($meta['table'], $meta['table'] . '.id = ' . $meta['rel_table'] . '.' . $meta['table'] . '_id', 'left');
					$this->db->where($meta['rel_table'] . '.' . $this->config['name'] . '_id', $id);
					$this->db->having('`item` IS NOT NULL');
				}

				$query = $this->db->get();

				/* Check if there are any entries */
				if (!$query->num_rows()) {
					/* Set multiple relationship field as empty array */
					$data['view']['rel'][$field] = array();

					continue;
				}

				foreach ($query->result_array() as $row) {
					/* If any of the fields are NULL, skip the row. (FIXME: This is not required because we're using having()) */
					if (!$row['id'] || !$row['item'])
						continue;

					$data['view']['rel'][$field][$row['id']] = $row['item'];
				}
			}
		}

		/* Hidden fields */
		$data['config']['hidden_fields'] = $this->config['hide_fields_view'];

		/* Check if there are any entry fields to be appended to the view title */
		$title_suffix = '';

		if (count($this->config['view_title_append_fields'])) {
			foreach ($this->config['view_title_append_fields'] as $title_append)
				$title_suffix .= $this->config['view_title_append_sep'] . $data['view']['result_array'][0][$title_append];
		}

		/* Update title and breadcrumb */
		$data['view']['title'] .= $title_suffix;
		$data['view']['links']['breadcrumb'] = $this->get->view_breadcrumb('view', NDPHP_LANG_MOD_OP_VIEW, $id, ltrim($title_suffix, $this->config['view_title_append_sep']));

		/* Load leave plugins */
		if ($this->config['plugins_enabled']) {
			foreach (glob(SYSTEM_BASE_DIR . '/plugins/*/view_generic_leave.php') as $plugin)
				include($plugin);
		}

		/* Hook handler (leave) */
		$this->_hook_view_generic_leave($data, $id, $export, $hook_enter_return);

		/* All good */
		return $data;
	}

	public function view_mixed_rel_count($foreign_table = '', $foreign_id) {
		$this->load->database($this->config['default_database']);

		if (!$this->filter->table_row_perm($foreign_id, $foreign_table, $this->security->perm_read))
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->config['default_charset'], !$this->request->is_ajax());

		$this->db->select('COUNT(`' . str_replace('`', '', $foreign_table) . '_id`) AS `total`', false);
		$this->db->from('mixed_' . $foreign_table . '_' . $this->config['name']);
		$this->db->where($foreign_table . '_id', $foreign_id);

		$query = $this->db->get();
		
		$row = $query->row_array();
		
		/* Return total rows to ajax request */
		$this->response->output($row['total']);
	}

	public function view_mixed_rel($mid, $foreign_table = '', $foreign_id = 0) {
		$data = $this->view_generic();

		$data['config']['hidden_fields'] = $this->config['mixed_hide_fields_view'];

		$data['view']['mixed_id'] = $mid;
		$data['view']['foreign_id'] = $foreign_id;
		$data['view']['foreign_table'] = $foreign_table;

		$data['view']['values'] = array();

		if ($foreign_table != '') {
			$this->load->database($this->config['default_database']);

			if (!$this->filter->table_row_perm($foreign_id, $foreign_table, $this->security->perm_read))
				$this->response->code('403', NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED, $this->config['default_charset'], !$this->request->is_ajax());

			$this->db->from('mixed_' . $foreign_table . '_' . $this->config['name']);
			$this->db->where($foreign_table . '_id', $foreign_id);
			$this->db->order_by('id', 'asc');
			$this->db->limit(1, $mid - 1);

			/* We need to mangle the relationship data. We also use _get_fields_basic_types() to avoid the
			 * overhead of calling _get_fields()
			 */
			$result_array = $this->field->value_mangle($this->get->fields_basic_types('mixed_' . $foreign_table . '_' . $this->config['name']), $this->db->get());
			
			/* NOTE: $field_name must have a UNIQUE constraint in order to trigger the following validation */
			if (count($result_array) == 1) {
				$row = array_values($result_array)[0];
				$data['view']['values'] = $row;
			}
		}

		/* Remove the fields that are not present in the mixed relational table */
		$data['view']['present_fields'] = $this->get->mixed_table_fields($this->config['name'], $foreign_table);

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
		if ($this->config['json_replies'] === true) {
			$this->response->output($this->rest->json_view($data));
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
						if (!in_array($file_type, $this->config['view_image_file_rendering_ext']))
							continue;

						/* Craft the file location */
						$file_path = SYSTEM_BASE_DIR . '/uploads/' . $this->config['session_data']['user_id'] . '/' . $this->config['name'] . '/' . $id . '/' . $field . '/' . openssl_digest($data['view']['result_array'][0][$field], 'sha256');
						
						/* Get file contents */
						if (($file_contents = file_get_contents($file_path)) === false)
							continue;

						/* If the contents of the file are encrypted ... */
						if ($this->config['upload_file_encryption'] === true)
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
				$this->mpdf->Output($this->config['name'] . '_' . $id . '.pdf', 'D');
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


	/** Charts public interface **/

	public function chart_publish($chart_id = 0, $refresh_rand = NULL, $result_query = NULL, $imagemap = NULL, $start_ts = NULL, $end_ts = NULL) {
		$this->_load_module('charts', true);

		$this->charts->chart_publish($chart_id, $refresh_rand, $result_query, $imagemap, $start_ts, $end_ts);
	}

	public function chart_foreign_publish($chart_id = 0, $entry_id = NULL, $refresh_rand = NULL, $imagemap = NULL, $start_ts = NULL, $end_ts = NULL) {
		$this->_load_module('charts', true);

		$this->charts->chart_foreign_publish($chart_id, $entry_id, $refresh_rand, $imagemap, $start_ts, $end_ts);
	}


	/** JSON documentation **/

	public function json_doc() {
		$this->_load_module('rest', true);

		$this->rest->json_doc();
	}


	/** Scheduler **/

	public function scheduler_external() {
		$this->_load_module('scheduler', true);

		/* Grant that only ROLE_ADMIN is able to execute this method */
		if (!$this->security->im_admin())
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_ONLY_ADMIN, $this->config['default_charset'], !$this->request->is_ajax());

		/* If the scheduler configuration type isn't set as 'external', deny this request */
		if ($this->config['scheduler']['type'] != 'external')
			$this->response->code('403', NDPHP_LANG_MOD_ATTN_SCHED_NOT_EXTERNAL, $this->config['default_charset'], !$this->request->is_ajax());

		/* Process and execute scheduled entries */
		$this->scheduler->process();
	}
}
