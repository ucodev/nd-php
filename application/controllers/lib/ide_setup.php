<?php

/* DO NOT MODIFY THIS FILE */

/* This file is included on the __construct() of all controllers that are
 * not managed by the IDE Builder.
 * The IDE Builder creates this file in order gain some control over
 * the unmanaged controllers.
 * 
 * TODO: In the future, all the controllers shall be managed (created)
 * by the IDE Builder
 *
 */

$this->_hide_menu_entries = array_merge($this->_hide_menu_entries, array(
		'builder',
		'charts_config',
		'charts_geometry',
		'charts_types',
		'codes',
		'codes_types',
		'configuration',
		'documentation',
		'timezones',
		'themes',
		'countries',
		'currencies',
		'genders',
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
		'months'
	)
);

$this->_hide_global_search_controllers = $this->_hide_menu_entries;

$this->_menu_entries_order = array();

$this->_menu_entries_aliases = array_merge($this->_menu_entries_aliases, array(
		'builder' => NDPHP_LANG_MOD_MENU_BUILDER_NAME,
		'charts_config' => NDPHP_LANG_MOD_MENU_CHARTS_CONFIG_NAME,
		'charts_geometry' => NDPHP_LANG_MOD_MENU_CHARTS_GEOMETRY_NAME,
		'charts_types' => NDPHP_LANG_MOD_MENU_CHARTS_TYPES_NAME,
		'codes' => NDPHP_LANG_MOD_MENU_CODES_NAME,
		'codes_types' => NDPHP_LANG_MOD_MENU_CODES_TYPES_NAME,
		'configuration' => NDPHP_LANG_MOD_MENU_CONFIGURATION_NAME,
		'countries' => NDPHP_LANG_MOD_MENU_COUNTRIES_NAME,
		'currencies' => NDPHP_LANG_MOD_MENU_CURRENCIES_NAME,
		'documentation' => NDPHP_LANG_MOD_MENU_DOCUMENTATION_NAME,
		'features' => NDPHP_LANG_MOD_MENU_FEATURES_NAME,
		'genders' => NDPHP_LANG_MOD_MENU_GENDERS_NAME,
		'logging' => NDPHP_LANG_MOD_MENU_LOGGING_NAME,
		'months' => NDPHP_LANG_MOD_MENU_MONTHS_NAME,
		'roles' => NDPHP_LANG_MOD_MENU_ROLES_NAME,
		'scheduler' => NDPHP_LANG_MOD_MENU_SCHEDULER_NAME,
		'sessions' => NDPHP_LANG_MOD_MENU_SESSIONS_NAME,
		'themes' => NDPHP_LANG_MOD_MENU_THEMES_NAME,
		'themes_animations_default' => NDPHP_LANG_MOD_MENU_THEMES_ANIM_DEFAULT_NAME,
		'themes_animations_ordering' => NDPHP_LANG_MOD_MENU_THEMES_ANIM_ORDERING_NAME,
		'timezones' => NDPHP_LANG_MOD_MENU_TIMEZONES_NAME,
		'users' => NDPHP_LANG_MOD_MENU_USERS_NAME,
		'weekdays' => NDPHP_LANG_MOD_MENU_WEEKDAYS_NAME
	)
);

