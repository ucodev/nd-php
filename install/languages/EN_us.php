<?php

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


define('NDPHP_LANG_MOD_ACCESS_FORBIDDEN',			'Forbidden.');
define('NDPHP_LANG_MOD_ACCESS_PERMISSION_DENIED',	'Permission Denied.');
define('NDPHP_LANG_MOD_ACCESS_ONLY_ADMIN',			'Only ROLE_ADMIN is allowed to access this controller.');
define('NDPHP_LANG_MOD_ACCESS_ACCT_INACTIVE',		'Account is not active.');
define('NDPHP_LANG_MOD_ACCESS_ACCT_LOCKED',			'Account is locked.');
define('NDPHP_LANG_MOD_ACCESS_ACCT_EXPIRED',		'Account is expired.');
define('NDPHP_LANG_MOD_ACCESS_ACCT_INVALID_EMAIL',	'Your account email is not valid.');
define('NDPHP_LANG_MOD_ACCESS_FILE_ACCESS_OR_PERM',	'Either the requested file does not exist or access to the file was denied.');
define('NDPHP_LANG_MOD_ACCESS_SAVED_SEARCH_DELETE',	'Either the requested search does not exist or there are no permissions to delete it.');

define('NDPHP_LANG_MOD_ACTION_FORWARD',				'Forward');
define('NDPHP_LANG_MOD_ACTION_REFRESH',				'Refresh');
define('NDPHP_LANG_MOD_ACTION_BACK',				'Back');

define('NDPHP_LANG_MOD_ATTN_VALIDATE_VALUES',		'Please validate the inserted values and try again.');
define('NDPHP_LANG_MOD_ATTN_SUBMIT_REQUIRED_FIELDS','Please fill the required fields before submit.');
define('NDPHP_LANG_MOD_ATTN_VIEW_RETRY_RELOAD',		'Please try to reload the view.');
define('NDPHP_LANG_MOD_ATTN_TRY_AGAIN',				'Please try again.');
define('NDPHP_LANG_MOD_ATTN_PERM_CHECK',			'Please check your permissions and try again.');
define('NDPHP_LANG_MOD_ATTN_CONTACT_SUPPORT',		'Please contact the technical support.');
define('NDPHP_LANG_MOD_ATTN_ADD_FUNDS',				'Please add funds to your account.');
define('NDPHP_LANG_MOD_ATTN_LOGOUT_LOGIN',			'Please Logout and Login again.');
define('NDPHP_LANG_MOD_ATTN_READ_ACCEPT_TERMS',		'You haven\'t read or accepted the Terms and Conditions and/or the Privacy Policy');
define('NDPHP_LANG_MOD_ATTN_ROLLBACK_CONFIRM',		'Do you confirm this rollback?');
define('NDPHP_LANG_MOD_ATTN_VALUE_LESS_THAN',		'Value must have less than');
define('NDPHP_LANG_MOD_ATTN_VALUE_MORE_THAN',		'Value must have more than');
define('NDPHP_LANG_MOD_ATTN_RELOAD_PAGE',			'Please press the reload button on your browser to refresh the page (or press the F5 key).');
define('NDPHP_LANG_MOD_ATTN_REVIEW_ISSUES',			'Please review the following issues');
define('NDPHP_LANG_MOD_ATTN_ONLY_DIGITS',			'Please insert only digits');
define('NDPHP_LANG_MOD_ATTN_NEED_VALID_EMAIL',		'Please insert a valid email address');
define('NDPHP_LANG_MOD_ATTN_SELECT_COUNTRY',		'Please select a country');
define('NDPHP_LANG_MOD_ATTN_INSUFFICIENT_CREDS',	'Please provide additional authorization mechanisms to perform that action.');

define('NDPHP_LANG_MOD_CANNOT_LOAD_PREV_VIEW',		'Cannot load the previous view.');
define('NDPHP_LANG_MOD_CANNOT_VERIFY_PASSWORD',		'Cannot verify password.');
define('NDPHP_LANG_MOD_CANNOT_SEARCH',				'Cannot perform search.');
define('NDPHP_LANG_MOD_CANNOT_UPDATE_DATA',			'Cannot update information.');
define('NDPHP_LANG_MOD_CANNOT_LOAD_DATA',			'Cannot retrieve information.');
define('NDPHP_LANG_MOD_CANNOT_LOAD_VIEW',			'Cannot load view.');
define('NDPHP_LANG_MOD_CANNOT_SUBMIT_REQUEST',		'Cannot submit request.');
define('NDPHP_LANG_MOD_CANNOT_EDIT_ITEM',			'Cannot edit item.');
define('NDPHP_LANG_MOD_CANNOT_OPERATION',			'Cannot perform operation.');
define('NDPHP_LANG_MOD_CANNOT_LOAD_MENU',			'Cannot load menu.');
define('NDPHP_LANG_MOD_CANNOT_CLONE_ITEM',			'Cannot clone item.');
define('NDPHP_LANG_MOD_CANNOT_INSERT_ITEM',			'Cannot insert item.');
define('NDPHP_LANG_MOD_CANNOT_ROLLBACK_NON_UPDATE', 'Cannot rollback transactions from operations other than UPDATE.');
define('NDPHP_LANG_MOD_CANNOT_DOWNGRADE_SUBSCRIPTION','You cannot downgrade your current subscription through this menu.');
define('NDPHP_LANG_MOD_CANNOT_UPGRADE_SUBSCRIPTION','Cannot perform Subscription Upgrade.');
define('NDPHP_LANG_MOD_CANNOT_UPGRADE_SUBSCR_CREDIT','Insufficient credits to perform Subscription Upgrade.');
define('NDPHP_LANG_MOD_CANNOT_DELETE_ADMIN_USER',	'Cannot delete the admin user.');
define('NDPHP_LANG_MOD_CANNOT_OP_VIEW_TYPE_CTRL',	'VIEW table type controllers cannot perform the operation');
define('NDPHP_LANG_MOD_CANNOT_DISPLAY_LIST',		'Cannot display list.');
define('NDPHP_LANG_MOD_CANNOT_FIND_ACTIVE_CONFIG',	'Cannot find an active configuration.');

define('NDPHP_LANG_MOD_DISABLED_MULTI_USER',		'Multi-user support is not enabled.');
define('NDPHP_LANG_MOD_DISABLED_USER_REGISTER',		'User registration disabled.');
define('NDPHP_LANG_MOD_DISABLED_USER_PASS_RECOVER',	'User password recovery disabled.');

define('NDPHP_LANG_MOD_FAILED_INSERT',				'Failed to perform insert.');
define('NDPHP_LANG_MOD_FAILED_UPDATE',				'Failed to perform update.');
define('NDPHP_LANG_MOD_FAILED_VERIFY_PASSWORD',		'Password verification failed. Please re-enter the password.');
define('NDPHP_LANG_MOD_FAILED_UPDATE_APP_MODEL',	'Failed to update application model.');
define('NDPHP_LANG_MOD_FAILED_TRANSACTION',			'Transaction failed.');
define('NDPHP_LANG_MOD_FAILED_VERIFY_TRANSACTION',	'Error ocurred while validating the transaction.');
define('NDPHP_LANG_MOD_FAILED_UPDATE_USER_DATA',	'Failed to update user data.');
define('NDPHP_LANG_MOD_FAILED_SEND_SMS',			'Error while sending SMS.');
define('NDPHP_LANG_MOD_FAILED_UPDATE_USER_ROLES',	'Failed to update user roles.');
define('NDPHP_LANG_MOD_FAILED_ROLE_UPDATE',			'Failed to update role.');

define('NDPHP_LANG_MOD_INVALID_ANIMATION_DEFAULT',	'Invalid default animation defined.');
define('NDPHP_LANG_MOD_INVALID_ANIMATION_ORDERING',	'Invalid ordering animation defined.');
define('NDPHP_LANG_MOD_INVALID_CHARS_FIELD',		'Invalid charaters on field name.');
define('NDPHP_LANG_MOD_INVALID_CHARS_FIELD_ORDER',	'Invalid charaters on ordering field name.');
define('NDPHP_LANG_MOD_INVALID_MIXED_VALUE',		'Invalid value inserted on mixed relationship');
define('NDPHP_LANG_MOD_INVALID_USER_OR_API_KEY',	'Invalid User ID or API Key.');
define('NDPHP_LANG_MOD_INVALID_USER_ID',			'Invalid User ID');
define('NDPHP_LANG_MOD_INVALID_USER_OR_PASSWORD',	'Invalid username or password.');
define('NDPHP_LANG_MOD_INVALID_POST_DATA',			'Invalid POST data.');
define('NDPHP_LANG_MOD_INVALID_DATA_FOUND',			'Found invalid data');
define('NDPHP_LANG_MOD_INVALID_SUBSCRIPTION_TYPE',	'Invalid subscription type.');
define('NDPHP_LANG_MOD_INVALID_RECAPTCHA_VALUE',	'Invalid reCAPTCHA value.');
define('NDPHP_LANG_MOD_INVALID_FIRST_NAME',			'Invalid First Name.');
define('NDPHP_LANG_MOD_INVALID_LAST_NAME',			'Invalid Last Name.');
define('NDPHP_LANG_MOD_INVALID_USERNAME',			'Invalid username.');
define('NDPHP_LANG_MOD_INVALID_COUNTRY',			'Invalid country.');
define('NDPHP_LANG_MOD_INVALID_VAT_EU',				'Invalid EU VAT number.');
define('NDPHP_LANG_MOD_INVALID_EMAIL',				'Invalid email address.');
define('NDPHP_LANG_MOD_INVALID_PHONE',				'Invalid phone number.');
define('NDPHP_LANG_MOD_INVALID_PHONE_PREFIX',		'Phone prefix is invalid or doesn\'t match the selected country.');
define('NDPHP_LANG_MOD_INVALID_CHART_TYPE',			'Invalid chart type');
define('NDPHP_LANG_MOD_INVALID_CHART_TYPE_NAME',	'Invalid chart type name.');
define('NDPHP_LANG_MOD_INVALID_CHART_GEOMETRY',		'Invalid chart geometry');
define('NDPHP_LANG_MOD_INVALID_SMS_TOKEN',			'Invalid SMS Token.');
define('NDPHP_LANG_MOD_INVALID_EMAIL_HASH',			'Invalid email hash.');
define('NDPHP_LANG_MOD_INVALID_CTRL_NAME',			'Invalid controller name');
define('NDPHP_LANG_MOD_INVALID_PARAMETERS',			'Invalid parameters.');
define('NDPHP_LANG_MOD_INVALID_SIZE_TOO_BIG',		'Invalid size. Too big.');
define('NDPHP_LANG_MOD_INVALID_REQUEST',			'Invalid request.');
define('NDPHP_LANG_MOD_INVALID_PRIV_ENC_KEY',		'Invalid private encryption key.');
define('NDPHP_LANG_MOD_INVALID CREDENTIALS',		'Invalid credentials.');
define('NDPHP_LANG_MOD_INVALID_SEARCH_INTERVAL_FMT','Incorrect custom interval. Intervals format is:');

define('NDPHP_LANG_MOD_INFO_LOADING',				'Loading...');
define('NDPHP_LANG_MOD_INFO_AUTHENTICATING',		'Authenticating...');
define('NDPHP_LANG_MOD_INFO_CONFIG_INACTIVE',		'You are disabling the only currently active configuration. Please enable some other configuration before disabling this one.');
define('NDPHP_LANG_MOD_INFO_CONFIG_DELETE_ACTIVE',	'You are trying to delete an active configuration, which is not allowed.');
define('NDPHP_LANG_MOD_INFO_ENTRY_CHANGED',			'The entry contents have been changed. You need to force the operation to override this check.');
define('NDPHP_LANG_MOD_INFO_ROLLBACK_ALREADY',		'The transaction is already marked as rolled back');
define('NDPHP_LANG_MOD_INFO_USER_CREDIT_UPDATE',	'User credit update');
define('NDPHP_LANG_MOD_INFO_PAYMENT_UPDATE',		'Payment update');
define('NDPHP_LANG_MOD_INFO_PASSWORD_NO_MATCH',		'Passwords don\'t match.');
define('NDPHP_LANG_MOD_INFO_TAKEN_USERNAME',		'Username already taken.');
define('NDPHP_LANG_MOD_INFO_INCOMPLETE_VAT_EU',		'Incomplete EU VAT number. If you want to leave VAT blank, you also need to leave the Company field blank.');
define('NDPHP_LANG_MOD_INFO_EMAIL_REGISTERED',		'Email already registered.');
define('NDPHP_LANG_MOD_INFO_PHONE_REGISTERED',		'Phone already registered.');
define('NDPHP_LANG_MOD_INFO_ROLLBACK_TRANSACTION',	'This operation will rollback the Transaction');
define('NDPHP_LANG_MOD_INFO_ROLLBACK_CHANGES',		'Transaction Changes Summary');
define('NDPHP_LANG_MOD_INFO_ROLLBACK_NOTE',			'NOTE: New Values will be replaced by Old Values');
define('NDPHP_LANG_MOD_INFO_READ_ACCEPT_THE',		'I\'ve read and accept the');
define('NDPHP_LANG_MOD_INFO_SYSTEM_UP_TO_DATE',		'System is up to date.');
define('NDPHP_LANG_MOD_INFO_INSTALL_ALREADY_DONE',	'Installation already complete.');

define('NDPHP_LANG_MOD_LINK_ADD_FUNDS',				'Add funds');
define('NDPHP_LANG_MOD_LINK_HOME',					'Home');
define('NDPHP_LANG_MOD_LINK_UPGRADE',				'Upgrade');
define('NDPHP_LANG_MOD_LINK_USER_SETTINGS',			'User settings');
define('NDPHP_LANG_MOD_LINK_RETURN_HOME',			'Return to Home');

define('NDPHP_LANG_MOD_MGMT_UNDER_MAINTENANCE',		'We\'re under Maintenance. Please wait a few moments and try again.');

define('NDPHP_LANG_MOD_MISSING_SEARCH_CRITERIA',	'No search criteria was selected.');
define('NDPHP_LANG_MOD_MISSING_SEARCH_FIELD', 		'One or more search fields are empty.');
define('NDPHP_LANG_MOD_MISSING_REQUIRED_FIELDS',	'Required fields missing.');
define('NDPHP_LANG_MOD_MISSING_REMOTE_ADDRESS',		'No remote address specified.');
define('NDPHP_LANG_MOD_MISSING_REQUIRED_ARGS',		'Missing required arguments.');
define('NDPHP_LANG_MOD_MISSING_AUTH_METHOD',		'No authentication method provided.');
define('NDPHP_LANG_MOD_MISSING_EMAIL',				'You must supply an email address.');
define('NDPHP_LANG_MOD_MISSING_PHONE',				'You must supply a phone number.');
define('NDPHP_LANG_MOD_MISSING_FIRST_NAME',			'You must supply your first name.');
define('NDPHP_LANG_MOD_MISSING_LAST_NAME',			'You must supply your last name.');
define('NDPHP_LANG_MOD_MISSING_VALID_COUNTRY',		'You must supply a valid country.');

define('NDPHP_LAND_MOD_NOTE_ITEM_INSERTED',			'Note that the submited item was successfully inserted.');

define('NDPHP_LANG_MOD_EMPTY_RESULTS',				'No results found.');
define('NDPHP_LANG_MOD_EMPTY_CHARTS',				'No charts available.');
define('NDPHP_LANG_MOD_EMPTY_SEARCHES',				'No saved searches found.');
define('NDPHP_LANG_MOD_EMPTY_GROUPS',				'No groups available.');
define('NDPHP_LANG_MOD_EMPTY_DATA',					'No data available.');
define('NDPHP_LANG_MOD_EMPTY_MAINMENU',				'Nothing to show here. Prehaps you want to open the IDE menu and do some magic :)');

define('NDPHP_LANG_MOD_OP_CREATE',					'Create');
define('NDPHP_LANG_MOD_OP_EDIT',					'Edit');
define('NDPHP_LANG_MOD_OP_EXPORT_PDF',				'Export PDF');
define('NDPHP_LANG_MOD_OP_EXPORT_CSV',				'Export CSV');
define('NDPHP_LANG_MOD_OP_GROUPS',					'Groups');
define('NDPHP_LANG_MOD_OP_LIST',					'List');
define('NDPHP_LANG_MOD_OP_REMOVE',					'Remove');
define('NDPHP_LANG_MOD_OP_RESULT',					'Result');
define('NDPHP_LANG_MOD_OP_SEARCH',					'Search');
define('NDPHP_LANG_MOD_OP_GLOBAL_SEARCH',			'Global Search');
define('NDPHP_LANG_MOD_OP_VIEW',					'View');
define('NDPHP_LANG_MOD_OP_BACKUP',					'Backup');
define('NDPHP_LANG_MOD_OP_CHARTS',					'Charts');
define('NDPHP_LANG_MOD_OP_SCHEDULER',				'Scheduler');
define('NDPHP_LANG_MOD_OP_CACHE_CLEAR',				'Clear Cache');
define('NDPHP_LANG_MOD_OP_SAVE_SEARCH',				'Save Search');
define('NDPHP_LANG_MOD_OP_IMPORT_CSV',				'Import CSV');
define('NDPHP_LANG_MOD_OP_LOGOUT',					'Logout');

define('NDPHP_LANG_MOD_OP_QUICK_CREATE',			'Quick Create');
define('NDPHP_LANG_MOD_OP_QUICK_EDIT',				'Quick Edit');
define('NDPHP_LANG_MOD_OP_QUICK_LIST',				'Quick List');
define('NDPHP_LANG_MOD_OP_QUICK_REMOVE',			'Quick Remove');
define('NDPHP_LANG_MOD_OP_QUICK_RESULT',			'Quick Result');
define('NDPHP_LANG_MOD_OP_QUICK_SEARCH',			'Quick Search');
define('NDPHP_LANG_MOD_OP_QUICK_VIEW',				'Quick View');

define('NDPHP_LANG_MOD_OP_MIXED_MOVE_ITEM_UP',		'Move Item Up');
define('NDPHP_LANG_MOD_OP_MIXED_MOVE_ITEM_DOWN',	'Move Item Down');
define('NDPHP_LANG_MOD_OP_MIXED_CLONE_ITEM',		'Clone Item');
define('NDPHP_LANG_MOD_OP_MIXED_DELETE_ITEM',		'Delete Item');

define('NDPHP_LANG_MOD_OP_CONTEXT_CONFIRM',			'Confirm');
define('NDPHP_LANG_MOD_OP_CONTEXT_CANCEL',			'Cancel');
define('NDPHP_LANG_MOD_OP_CONTEXT_EXPAND',			'Expand');
define('NDPHP_LANG_MOD_OP_CONTEXT_DELETE',			'Delete');
define('NDPHP_LANG_MOD_OP_CONTEXT_UPDATE',			'Update');
define('NDPHP_LANG_MOD_OP_CONTEXT_CREATE',			'Create');
define('NDPHP_LANG_MOD_OP_CONTEXT_EDIT',			'Edit');
define('NDPHP_LANG_MOD_OP_CONTEXT_VIEW',			'View');
define('NDPHP_LANG_MOD_OP_CONTEXT_SEARCH',			'Search');
define('NDPHP_LANG_MOD_OP_CONTEXT_EXPORT_PDF',		'Export PDF');
define('NDPHP_LANG_MOD_OP_CONTEXT_EXPORT_CSV',		'Export CSV');
define('NDPHP_LANG_MOD_OP_CONTEXT_REGISTER',		'Register');
define('NDPHP_LANG_MOD_OP_CONTEXT_LOGIN',			'Login');
define('NDPHP_LANG_MOD_OP_CONTEXT_NEW',				'New'); /* More OP */

define('NDPHP_LANG_MOD_OP_LIST_ORDER_BY',			'Order by');
define('NDPHP_LANG_MOD_OP_LIST_VIEW_ITEM',			'View item');

define('NDPHP_LANG_MOD_OP_TIMER_START',				'Start');
define('NDPHP_LANG_MOD_OP_TIMER_STOP',				'Stop');

define('NDPHP_LANG_MOD_STATUS_DISABLED',			'Disabled');
define('NDPHP_LANG_MOD_STATUS_CHECKBOX_CHECKED',	'True');
define('NDPHP_LANG_MOD_STATUS_CHECKBOX_UNCHECKED',	'False');
define('NDPHP_LANG_MOD_STATUS_ON',					'ON');
define('NDPHP_LANG_MOD_STATUS_OFF',					'OFF');

define('NDPHP_LANG_MOD_SUCCESS_LOAD_APP_MODEL',		'Application model successfully loaded.');
define('NDPHP_LANG_MOD_SUCCESS_DEPLOY_ON',			'successfully deployed on');
define('NDPHP_LANG_MOD_SUCCESS_ROLLBACK_TRANSACTION','Rollback operation successfully completed for Transaction');
define('NDPHP_LANG_MOD_SUCCESS_ROLE_UPDATE',		'Role updated successfully.');
define('NDPHP_LANG_MOD_SUCCESS_PHONE_VERIFICATION',	'Phone successfully verified.');
define('NDPHP_LANG_MOD_SUCCESS_EMAIL_VERIFICATION',	'Email successfully verified.');

define('NDPHP_LANG_MOD_UNABLE_AUTHENTICATE',		'Unable to authenticate.');
define('NDPHP_LANG_MOD_UNABLE_DELETE_ENTRY',		'Unable to delete entry.');
define('NDPHP_LANG_MOD_UNABLE_FILE_UPLOAD',			'Unable to upload file');
define('NDPHP_LANG_MOD_UNABLE_FILE_COPY',			'Unable to copy file');
define('NDPHP_LANG_MOD_UNABLE_FILE_OPEN_WRITE',		'Unable to open file for writing');
define('NDPHP_LANG_MOD_UNABLE_FILE_OPEN_READ',		'Unable to open file for reading');
define('NDPHP_LANG_MOD_UNABLE_FILE_WRITE',			'Unable to write to file');
define('NDPHP_LANG_MOD_UNABLE_INSERT_ENTRY',		'Unable to insert register into database.');
define('NDPHP_LANG_MOD_UNABLE_OPERATION',			'Unable to perform the requested operation.');
define('NDPHP_LANG_MOD_UNABLE_SEARCH',				'Unable to perform the requested search.');
define('NDPHP_LANG_MOD_UNABLE_RETURN_PREV_VIEW',	'Unable to return to the previous view.');
define('NDPHP_LANG_MOD_UNABLE_UPDATE_ITEM_SELECT',	'Unable to update the newly inserted item in the current select box.');
define('NDPHP_LANG_MOD_UNABLE_VIEW_ITEM_SELECT',	'Unable to load view for the selected item.');
define('NDPHP_LANG_MOD_UNABLE_EDIT_ITEM_SELECT',	'Unable to load the edit form for the selected item.');
define('NDPHP_LANG_MOD_UNABLE_LOAD_VIEW_ITEM_NEW',	'Unable to load view for the newly submitted item.');
define('NDPHP_LANG_MOD_UNABLE_SUBMIT_REQUEST',		'Unable to submit the request.');
define('NDPHP_LANG_MOD_UNABLE_LOAD_VIEW_LIST',		'Unable to load the list view for the current context.');
define('NDPHP_LANG_MOD_UNABLE_LOAD_VIEW_LIST_ORDER','Unable to retrieve the requested ordered list.');
define('NDPHP_LANG_MOD_UNABLE_LOAD_MENU',			'Unable to load menu');
define('NDPHP_LANG_MOD_UNABLE_CLONE_ITEM',			'Unable to clone the item.');
define('NDPHP_LANG_MOD_UNABLE_MIXED_LOAD_VALUES',	'Unable to retrieve values from the selected element.');
define('NDPHP_LANG_MOD_UNABLE_MIXED_INSERT_NEW',	'Unable to insert a new item.');
define('NDPHP_LANG_MOD_UNABLE_MIXED_LOAD_ASSOC_LIST','Unable to retrieve associated items list.');
define('NDPHP_LANG_MOD_UNABLE_DECODE_DATA_JSON',	'Unable to decode JSON data.');
define('NDPHP_LANG_MOD_UNABLE_PROCESS_APP_MODEL',	'Unable to process application model.');
define('NDPHP_LANG_MOD_UNABLE_FIND_TRANSACTION',	'Unable to find the requested transaction');
define('NDPHP_LANG_MOD_UNABLE_ROLLBACK_TRANSACTION','Unable to rollback the requested transaction');
define('NDPHP_LANG_MOD_UNABLE_USER_CREDIT_INFO',	'Unable to retrieve the current credit information from the user.');
define('NDPHP_LANG_MOD_UNABLE_UPDATE_SESSION_DATA',	'Unable to update session settings.');
define('NDPHP_LANG_MOD_UNABLE_REGISTER_NEW_USERS',	'Currently we\'re unable to register new users. Please wait a few minutes and try again.');
define('NDPHP_LANG_MOD_UNABLE_CONFIRM_VAT_EU',		'Unable to confirm EU VAT number. Try again in a few moments.');
define('NDPHP_LANG_MOD_UNABLE_SEND_CONFIRM_EMAIL',	'Unable to send confirmation email.');
define('NDPHP_LANG_MOD_UNABLE_SEND_CONFIRM_SMS',	'Unable to send the SMS confirmation token');
define('NDPHP_LANG_MOD_UNABLE_ACTIVATED_ACCOUNT',	'Unable to activate account.');
define('NDPHP_LANG_MOD_UNABLE_BACKUP_PROJECT_DIR',	'Unable to perform project directory backup.');
define('NDPHP_LANG_MOD_UNABLE_STORE_SAVED_SEARCH',	'Unable to store the saved search into the database.');
define('NDPHP_LANG_MOD_UNABLE_STORE_DELETE_SEARCH',	'Unable to delete the saved search from the database.');
define('NDPHP_LANG_MOD_UNABLE_RECOVER_CREDENTIALS',	'Currently we\'re unable to perform credentials recovery. Please wait a few minutes and try again.');
define('NDPHP_LANG_MOD_UNABLE_UPDATE_TABLE_USERS',	'Unable to update users table.');
define('NDPHP_LANG_MOD_UNABLE_RETRIEVE_RELATED_ITEM','Unable to retrieve related items for');
define('NDPHP_LANG_MOD_UNABLE_CREATE_DIRECTORY',	'Unable to create directory');
define('NDPHP_LANG_MOD_UNABLE_MATCH_REL_VALUE_FK',	'Unable match a relationship value to its foreign key.');
define('NDPHP_LANG_MOD_UNABLE_MATCH_CSV_FIELD_CTRL','Unable to match CSV header field name with any of the controller fields.');
define('NDPHP_LANG_MOD_UNABLE_UPGRADE_SUBSCRIPTION','Unable to perform Subscription Upgrade.');
define('NDPHP_LANG_MOD_UNABLE_FETCH_CRIT_DATA_DBMS','Unable to fetch critical data from the database.');

define('NDPHP_LANG_MOD_UNSUPPORTED_RESULT_NO_ID',	'Currently there is no support for results without Id field. Please enable the Id field in the results section and try again.');
define('NDPHP_LANG_MOD_UNSUPPORTED_EXPORT_VIEW_CSV','CSV export on views is currently unsupported.');

define('NDPHP_LANG_MOD_UNDEFINED_CTRL_VIEW_QUERY',	'This controller is defined as a VIEW table type, but no VIEW QUERY was defined.');
define('NDPHP_LANG_MOD_UNDEFINED_METHOD',			'Undefined method');
define('NDPHP_LANG_MOD_UNDEFINED_CHART_ID',			'The requested Chart ID does not exist.');

define('NDPHP_LANG_MOD_WORD_FALSE',					'false');
define('NDPHP_LANG_MOD_WORD_NO',					'no');
define('NDPHP_LANG_MOD_WORD_TRUE',					'true');
define('NDPHP_LANG_MOD_WORD_YES',					'yes');
define('NDPHP_LANG_MOD_WORD_REASON',				'reason'); /* Error reason, cause, ... */
define('NDPHP_LANG_MOD_WORD_BUILD',					'build'); /* IDE Builder */
define('NDPHP_LANG_MOD_WORD_OF',					'of'); /* Total (of) rows */
define('NDPHP_LANG_MOD_WORD_ROW',					'row'); /* row, entry, ... */
define('NDPHP_LANG_MOD_WORD_ROWS',					'rows'); /* rows, entries, ... */
define('NDPHP_LANG_MOD_WORD_DEFAULT',				'default');
define('NDPHP_LANG_MOD_WORD_REQUIRED',				'required'); /* field required */
define('NDPHP_LANG_MOD_WORD_CHARACTERS',			'characters'); /* number of characters */
define('NDPHP_LANG_MOD_WORD_ACTIVE_M',				'active'); /* Active [male gender] */
define('NDPHP_LANG_MOD_WORD_ACTIVE_F',				'active'); /* Active [female gender] */
define('NDPHP_LANG_MOD_WORD_ACCESSIBILITY',			'accessibility');
define('NDPHP_LANG_MOD_WORD_LINE',					'line'); /* line of file */
define('NDPHP_LANG_MOD_WORD_COLUMN',				'column'); /* row column */
define('NDPHP_LANG_MOD_WORD_MONTH',					'month'); /* every month */
define('NDPHP_LANG_MOD_WORD_SELECT',				'select'); /* select from a select box */
define('NDPHP_LANG_MOD_WORD_RECOVER',				'recover'); /* recover credentials */

define('NDPHP_LANG_MOD_CONJ_AND_THE',				'and the');
define('NDPHP_LANG_MOD_CONJ_OR_THE',				'or the');
define('NDPHP_LANG_MOD_CONJ_AND',					'and');
define('NDPHP_LANG_MOD_CONJ_OR',					'or');

define('NDPHP_LANG_MOD_COMMON_BASE_URL',			'Base URL');
define('NDPHP_LANG_MOD_COMMON_RPP',					'Rows per Page');
define('NDPHP_LANG_MOD_COMMON_TEMP_DIR',			'Temporary Directory');
define('NDPHP_LANG_MOD_COMMON_SMTP_HOST',			'SMTP Host');
define('NDPHP_LANG_MOD_COMMON_SMTP_PORT',			'SMTP Port');
define('NDPHP_LANG_MOD_COMMON_SMTP_USER',			'SMTP User');
define('NDPHP_LANG_MOD_COMMON_SMTP_PASSWORD',		'SMTP Password');
define('NDPHP_LANG_MOD_COMMON_PRIV_KEY',			'Private Key');
define('NDPHP_LANG_MOD_COMMON_PUB_KEY',				'Public Key');
define('NDPHP_LANG_MOD_COMMON_PROJECT_NAME',		'Project Name');
define('NDPHP_LANG_MOD_COMMON_VERSION',				'Version');
define('NDPHP_LANG_MOD_COMMON_LAST_UPDATE',			'Last Update');
define('NDPHP_LANG_MOD_COMMON_REGULAR_USER_ROLE',	'Regular User Role');
define('NDPHP_LANG_MOD_COMMON_TABLE',				'Table');
define('NDPHP_LANG_MOD_COMMON_FIELD',				'Field');
define('NDPHP_LANG_MOD_COMMON_ENTRY_ID',			'Entry ID');
define('NDPHP_LANG_MOD_COMMON_VALUE_NEW',			'New Value');
define('NDPHP_LANG_MOD_COMMON_VALUE_OLD',			'Old Value');
define('NDPHP_LANG_MOD_COMMON_ROLLBACK',			'Rollback');
define('NDPHP_LANG_MOD_COMMON_IP_ADDRESS',			'IP Address');
define('NDPHP_LANG_MOD_COMMON_USER_AGENT',			'User Agent');
define('NDPHP_LANG_MOD_COMMON_START_TIME',			'Start Time');
define('NDPHP_LANG_MOD_COMMON_LAST_LOGIN',			'Last Login');
define('NDPHP_LANG_MOD_COMMON_SUBSCRIPTION_TYPE',	'Subscription Type');
define('NDPHP_LANG_MOD_COMMON_DEFAULT_DELAY',		'Default Delay');
define('NDPHP_LANG_MOD_COMMON_ORDERING_DELAY',		'Ordering Delay');
define('NDPHP_LANG_MOD_COMMON_DEFAULT_ANIMATION',	'Default Animation');
define('NDPHP_LANG_MOD_COMMON_ORDERING_ANIMATION',	'Ordering Animation');
define('NDPHP_LANG_MOD_COMMON_TRANSACTION_DATE',	'Transaction Date');
define('NDPHP_LANG_MOD_COMMON_TRANSACTION_TYPE',	'Transaction Type');
define('NDPHP_LANG_MOD_COMMON_FIRST_NAME',			'First Name');
define('NDPHP_LANG_MOD_COMMON_LAST_NAME',			'Last Name');
define('NDPHP_LANG_MOD_COMMON_COMPANY_NAME',		'Company');
define('NDPHP_LANG_MOD_COMMON_USERNAME',			'Username');
define('NDPHP_LANG_MOD_COMMON_PASSWORD',			'Password');
define('NDPHP_LANG_MOD_COMMON_PASSWORD_REPEAT',		'Repeat Password');
define('NDPHP_LANG_MOD_COMMON_COUNTRY',				'Country');
define('NDPHP_LANG_MOD_COMMON_COUNTRY_CODE',		'Code');
define('NDPHP_LANG_MOD_COMMON_COUNTRY_PREFIX',		'Prefix');
define('NDPHP_LANG_MOD_COMMON_COUNTRY_EU_STATE',	'EU State');
define('NDPHP_LANG_MOD_COMMON_COUNTRY_VAT_RATE',	'VAT Rate');
define('NDPHP_LANG_MOD_COMMON_EMAIL',				'Email');
define('NDPHP_LANG_MOD_COMMON_PHONE',				'Mobile');
define('NDPHP_LANG_MOD_COMMON_VAT_NUMBER_EU',		'EU VAT');
define('NDPHP_LANG_MOD_COMMON_VAT_NUMBER',			'VAT Number');
define('NDPHP_LANG_MOD_COMMON_POSTCODE',			'Post / ZIP');
define('NDPHP_LANG_MOD_COMMON_ADDR_LINE1',			'Address Line 1');
define('NDPHP_LANG_MOD_COMMON_ADDR_LINE2',			'Address Line 2');
define('NDPHP_LANG_MOD_COMMON_SUBSCR_CHANGE_DATE',	'Subscription Change Date');
define('NDPHP_LANG_MOD_COMMON_SUBSCR_RENEW_DATE',	'Subscription Renew Date');
define('NDPHP_LANG_MOD_COMMON_EMAIL_CONFIRMED',		'Email Confirmed');
define('NDPHP_LANG_MOD_COMMON_PHONE_CONFIRMED',		'Phone Confirmed');
define('NDPHP_LANG_MOD_COMMON_DATE_CONFIRMED',		'Date Confirmed');
define('NDPHP_LANG_MOD_COMMON_ALLOW_NEG_CREDIT',	'Allow Negative Credit');
define('NDPHP_LANG_MOD_COMMON_CONFIRM_EMAIL_HASH',	'Confirm Email Hash');
define('NDPHP_LANG_MOD_COMMON_CONFIRM_PHONE_TOKEN',	'Confirm Phone Token');
define('NDPHP_LANG_MOD_COMMON_ACCT_LAST_RESET',		'Acct Last Reset');
define('NDPHP_LANG_MOD_COMMON_ACCT_REST_LIST_CNTR',	'Acct REST LIST Counter');
define('NDPHP_LANG_MOD_COMMON_ACCT_REST_RESULT_CNTR','Acct REST RESULT Counter');
define('NDPHP_LANG_MOD_COMMON_ACCT_REST_VIEW_CNTR',	'Acct REST VIEW Counter');
define('NDPHP_LANG_MOD_COMMON_ACCT_REST_DELETE_CNTR','Acct REST DELETE Counter');
define('NDPHP_LANG_MOD_COMMON_ACCT_REST_UPDATE_CNTR','Acct REST UPDATE Counter');
define('NDPHP_LANG_MOD_COMMON_ACCT_REST_INSERT_CNTR','Acct REST INSERT Counter');
define('NDPHP_LANG_MOD_COMMON_SUBSCRIPTION',		'Subscription');
define('NDPHP_LANG_MOD_COMMON_DESCRIPTION',			'Description');
define('NDPHP_LANG_MOD_COMMON_SEARCH_NAME',			'Search Name');
define('NDPHP_LANG_MOD_COMMON_CRUD_TITLE_FIELD_NAME','Field');
define('NDPHP_LANG_MOD_COMMON_CRUD_TITLE_FIELD_VALUE','Value');
define('NDPHP_LANG_MOD_COMMON_REPEAT',				'Repeat');
define('NDPHP_LANG_MOD_COMMON_REGISTERED',			'Registered');
define('NDPHP_LANG_MOD_COMMON_TERMS_AND_CONDITIONS','Terms and Conditions');
define('NDPHP_LANG_MOD_COMMON_PRIVACY_POLICY',		'Privacy Policy');
define('NDPHP_LANG_MOD_COMMON_CONFIGURATION',		'Configuration');
define('NDPHP_LANG_MOD_COMMON_TAGLINE',				'Tagline');
define('NDPHP_LANG_MOD_COMMON_AUTHOR',				'Author');
define('NDPHP_LANG_MOD_COMMON_MAINTENANCE',			'Maintenance');
define('NDPHP_LANG_MOD_COMMON_ACTIVE',				'Active');
define('NDPHP_LANG_MOD_COMMON_MODEL',				'Model');
define('NDPHP_LANG_MOD_COMMON_THEME',				'Theme');
define('NDPHP_LANG_MOD_COMMON_TIMEZONE',			'Timezone');
define('NDPHP_LANG_MOD_COMMON_FEATURE',				'Feature');
define('NDPHP_LANG_MOD_COMMON_ITEM',				'Item');
define('NDPHP_LANG_MOD_COMMON_PRICE',				'Price');
define('NDPHP_LANG_MOD_COMMON_OPERATION',			'Operation');
define('NDPHP_LANG_MOD_COMMON_TRANSACTION',			'Transaction');
define('NDPHP_LANG_MOD_COMMON_ROLLED_BACK',			'Rolled Back');
define('NDPHP_LANG_MOD_COMMON_TXNID',				'TXNID');
define('NDPHP_LANG_MOD_COMMON_AMOUNT',				'Amount');
define('NDPHP_LANG_MOD_COMMON_CREATED',				'Created');
define('NDPHP_LANG_MOD_COMMON_UPDATED',				'Updated');
define('NDPHP_LANG_MOD_COMMON_ROLE',				'Role');
define('NDPHP_LANG_MOD_COMMON_SESSION',				'Session');
define('NDPHP_LANG_MOD_COMMON_API_EXTENDED',		'Extended API');
define('NDPHP_LANG_MOD_COMMON_ANIMATION',			'Animation');
define('NDPHP_LANG_MOD_COMMON_COORDINATES',			'Coordinates');
define('NDPHP_LANG_MOD_COMMON_CITY',				'City');
define('NDPHP_LANG_MOD_COMMON_LOCKED',				'Locked');
define('NDPHP_LANG_MOD_COMMON_EXPIRE',				'Expire');
define('NDPHP_LANG_MOD_COMMON_CREDIT',				'Credit');
define('NDPHP_LANG_MOD_COMMON_API_KEY',				'API Key');
define('NDPHP_LANG_MOD_COMMON_MONTH',				'Month');
define('NDPHP_LANG_MOD_COMMON_WEEKDAY',				'Weekday');
define('NDPHP_LANG_MOD_COMMON_NUMBER',				'Number');
define('NDPHP_LANG_MOD_COMMON_TITLE',				'Title');
define('NDPHP_LANG_MOD_COMMON_FIELDS',				'Fields');
define('NDPHP_LANG_MOD_COMMON_ABSCISSA',			'Abscissa');
define('NDPHP_LANG_MOD_COMMON_FOREIGN_TABLE',		'Foreign Table');
define('NDPHP_LANG_MOD_COMMON_START_TS',			'Start TS');
define('NDPHP_LANG_MOD_COMMON_END_TS',				'End TS');
define('NDPHP_LANG_MOD_COMMON_FIELD_TS',			'Field TS');
define('NDPHP_LANG_MOD_COMMON_FIELD_LEGEND',		'Field Legend');
define('NDPHP_LANG_MOD_COMMON_FIELD_TOTAL',			'Field Total');
define('NDPHP_LANG_MOD_COMMON_CHART_TYPE',			'Chart Type');
define('NDPHP_LANG_MOD_COMMON_CHART_GEOMETRY',		'Chart Geometry');
define('NDPHP_LANG_MOD_COMMON_CONTROLLER',			'Controller');
define('NDPHP_LANG_MOD_COMMON_IMPORT_CTRL',			'Import Controller');
define('NDPHP_LANG_MOD_COMMON_CHARTID',				'Chart ID');
define('NDPHP_LANG_MOD_COMMON_PHOTO',				'Photo');
define('NDPHP_LANG_MOD_COMMON_MEMCACHED_SERVER',	'Memcached Server');
define('NDPHP_LANG_MOD_COMMON_MEMCACHED_PORT',		'Memcached Port');
define('NDPHP_LANG_MOD_COMMON_ENTRY_NAME',			'Entry Name');
define('NDPHP_LANG_MOD_COMMON_PERIOD',				'Period');
define('NDPHP_LANG_MOD_COMMON_LAST_RUN',			'Last Run');
define('NDPHP_LANG_MOD_COMMON_NEXT_RUN',			'Next Run');
define('NDPHP_LANG_MOD_COMMON_OUTPUT',				'Output');
define('NDPHP_LANG_MOD_COMMON_IMPORT_CSV_FILE',		'CSV File');
define('NDPHP_LANG_MOD_COMMON_IMPORT_CSV_TEXT',		'CSV Text');
define('NDPHP_LANG_MOD_COMMON_IMPORT_CSV_SEP',		'Field Separator');
define('NDPHP_LANG_MOD_COMMON_IMPORT_CSV_DELIM',	'Text Delimiter');
define('NDPHP_LANG_MOD_COMMON_IMPORT_CSV_ESC',		'Escape Character');
define('NDPHP_LANG_MOD_COMMON_SHARDING',			'Sharding');
define('NDPHP_LANG_MOD_COMMON_DATABASE_ALIAS',		'Database');
define('NDPHP_LANG_MOD_COMMON_DATABASE_NAME',		'Name');
define('NDPHP_LANG_MOD_COMMON_DATABASE_HOST',		'Host');
define('NDPHP_LANG_MOD_COMMON_DATABASE_PORT',		'Port');
define('NDPHP_LANG_MOD_COMMON_DATABASE_USERNAME',	'Username');
define('NDPHP_LANG_MOD_COMMON_DATABASE_PASSWORD',	'Password');
define('NDPHP_LANG_MOD_COMMON_DATABASE_CHARSET',	'Charset');
define('NDPHP_LANG_MOD_COMMON_DATABASE_STRICT_MODE','Strict Mode');
define('NDPHP_LANG_MOD_COMMON_SETUP_ROLE',			'Setup Role');
define('NDPHP_LANG_MOD_COMMON_HOME',				'Home');
define('NDPHP_LANG_MOD_COMMON_DASHBOARD',			'Dashboard');
define('NDPHP_LANG_MOD_COMMON_NOTIFICATION',		'Notification');
define('NDPHP_LANG_MOD_COMMON_URL',					'URL');
define('NDPHP_LANG_MOD_COMMON_SEEN',				'Seen');
define('NDPHP_LANG_MOD_COMMON_NOTIFY_ALL',			'Notify All');
define('NDPHP_LANG_MOD_COMMON_NOTIFY_WHEN',			'Notify When');

define('NDPHP_LANG_MOD_MENU_BUILDER_NAME',			'Builder');
define('NDPHP_LANG_MOD_MENU_BUILDER_DESC',			'Builder');
define('NDPHP_LANG_MOD_MENU_CHARTS_CONFIG_NAME',	'Charts Configuration');
define('NDPHP_LANG_MOD_MENU_CHARTS_CONFIG_DESC',	'Charts Configuration');
define('NDPHP_LANG_MOD_MENU_CHARTS_GEOMETRY_NAME',	'Charts Geometry');
define('NDPHP_LANG_MOD_MENU_CHARTS_GEOMETRY_DESC',	'Charts Geometry');
define('NDPHP_LANG_MOD_MENU_CHARTS_TYPES_NAME',		'Charts Types');
define('NDPHP_LANG_MOD_MENU_CHARTS_TYPES_DESC',		'Charts Types');
define('NDPHP_LANG_MOD_MENU_CONFIGURATION_NAME',	'Configuration');
define('NDPHP_LANG_MOD_MENU_CONFIGURATION_DESC',	'Configuration');
define('NDPHP_LANG_MOD_MENU_COUNTRIES_NAME',		'Countries');
define('NDPHP_LANG_MOD_MENU_COUNTRIES_DESC',		'Countries');
define('NDPHP_LANG_MOD_MENU_DBMS_NAME',				'Databases');
define('NDPHP_LANG_MOD_MENU_DBMS_DESC',				'Databases');
define('NDPHP_LANG_MOD_MENU_DOCUMENTATION_NAME',	'Documentation');
define('NDPHP_LANG_MOD_MENU_DOCUMENTATION_DESC',	'Documentation');
define('NDPHP_LANG_MOD_MENU_FEATURES_NAME',			'Features');
define('NDPHP_LANG_MOD_MENU_FEATURES_DESC',			'Features');
define('NDPHP_LANG_MOD_MENU_ITEMS_NAME',			'Items');
define('NDPHP_LANG_MOD_MENU_ITEMS_DESC',			'Items');
define('NDPHP_LANG_MOD_MENU_LOGGING_NAME',			'Logging');
define('NDPHP_LANG_MOD_MENU_LOGGING_DESC',			'Logging');
define('NDPHP_LANG_MOD_MENU_MONTHS_NAME',			'Months');
define('NDPHP_LANG_MOD_MENU_MONTHS_DESC',			'Months');
define('NDPHP_LANG_MOD_MENU_NOTIFICATIONS_NAME',	'Notifications');
define('NDPHP_LANG_MOD_MENU_NOTIFICATIONS_DESC',	'Notifications');
define('NDPHP_LANG_MOD_MENU_PAYMENT_ACTIONS_NAME',	'Payment Actions');
define('NDPHP_LANG_MOD_MENU_PAYMENT_ACTIONS_DESC',	'Payment Actions');
define('NDPHP_LANG_MOD_MENU_PAYMENT_STATUS_NAME',	'Payment Status');
define('NDPHP_LANG_MOD_MENU_PAYMENT_STATUS_DESC',	'Payment Status');
define('NDPHP_LANG_MOD_MENU_PAYMENT_TYPES_NAME',	'Payment Types');
define('NDPHP_LANG_MOD_MENU_PAYMENT_TYPES_DESC',	'Payment Types');
define('NDPHP_LANG_MOD_MENU_PAYMENTS_NAME',			'Payments');
define('NDPHP_LANG_MOD_MENU_PAYMENTS_DESC',			'Payments');
define('NDPHP_LANG_MOD_MENU_ROLES_NAME',			'Roles');
define('NDPHP_LANG_MOD_MENU_ROLES_DESC',			'Roles');
define('NDPHP_LANG_MOD_MENU_SCHEDULER_NAME',		'Scheduler');
define('NDPHP_LANG_MOD_MENU_SCHEDULER_DESC',		'Scheduler');
define('NDPHP_LANG_MOD_MENU_SESSIONS_NAME',			'Sessions');
define('NDPHP_LANG_MOD_MENU_SESSIONS_DESC',			'Sessions');
define('NDPHP_LANG_MOD_MENU_SUBSCRIPTION_TYPES_NAME','Subscription Types');
define('NDPHP_LANG_MOD_MENU_SUBSCRIPTION_TYPES_DESC','Subscription Types');
define('NDPHP_LANG_MOD_MENU_THEMES_NAME',			'Themes');
define('NDPHP_LANG_MOD_MENU_THEMES_DESC',			'Themes');
define('NDPHP_LANG_MOD_MENU_THEMES_ANIM_DEFAULT_NAME','Default Animations');
define('NDPHP_LANG_MOD_MENU_THEMES_ANIM_DEFAULT_DESC','Default Animations');
define('NDPHP_LANG_MOD_MENU_THEMES_ANIM_ORDERING_NAME','Ordering Animations');
define('NDPHP_LANG_MOD_MENU_THEMES_ANIM_ORDERING_DESC','Ordering Animations');
define('NDPHP_LANG_MOD_MENU_TIMEZONES_NAME',		'Timezones');
define('NDPHP_LANG_MOD_MENU_TIMEZONES_DESC',		'Timezones');
define('NDPHP_LANG_MOD_MENU_TRANSACTION_HISTORY_NAME','Transaction History');
define('NDPHP_LANG_MOD_MENU_TRANSACTION_HISTORY_DESC','Transaction History');
define('NDPHP_LANG_MOD_MENU_TRANSACTION_TYPES_NAME','Transaction Types');
define('NDPHP_LANG_MOD_MENU_TRANSACTION_TYPES_DESC','Transaction Types');
define('NDPHP_LANG_MOD_MENU_USERS_NAME',			'Users');
define('NDPHP_LANG_MOD_MENU_USERS_DESC',			'Users');
define('NDPHP_LANG_MOD_MENU_WEEKDAYS_NAME',			'Weekdays');
define('NDPHP_LANG_MOD_MENU_WEEKDAYS_DESC',			'Weekdays');
define('NDPHP_LANG_MOD_MENU_SUPPORT_NAME',			'Support');
define('NDPHP_LANG_MOD_MENU_SUPPORT_DESC',			'Email Support');
define('NDPHP_LANG_MOD_MENU_IDE_NAME',				'IDE');
define('NDPHP_LANG_MOD_MENU_IDE_DESC',				'IDE Builder');

define('NDPHP_LANG_MOD_LOGIN_LOGIN',				'Login');
define('NDPHP_LANG_MOD_LOGIN_NEW_USER',				'New User?');
define('NDPHP_LANG_MOD_LOGIN_FORGOT_PASSWORD',		'Forgot Password?');
define('NDPHP_LANG_MOD_LOGIN_USERNAME',				'Username');
define('NDPHP_LANG_MOD_LOGIN_PASSWORD',				'Password');

define('NDPHP_LANG_MOD_BLOCK_SEARCH_ADV_CRITERIA',	'Criteria');
define('NDPHP_LANG_MOD_BLOCK_SEARCH_ADV_CONDITIONS','Conditions');
define('NDPHP_LANG_MOD_BLOCK_SEARCH_ADV_RESULT',	'Result');
define('NDPHP_LANG_MOD_BLOCK_ROLLBACK_TRANSACTION',	'Rollback Transaction');

define('NDPHP_LANG_MOD_INSTALL_MISSING_EXTENSIONS',	'Missing extensions');
define('NDPHP_LANG_MOD_INSTALL_SUCCESS_DB_CONFIG',	'Database configuration file successfully created.');
define('NDPHP_LANG_MOD_INSTALL_SUCCESS_SESS_CONFIG','Session configuration file successfully created.');
define('NDPHP_LANG_MOD_INSTALL_SUCCESS_ENC_CONFIG',	'Encryption configuration file successfully created.');
define('NDPHP_LANG_MOD_INSTALL_SUCCESS_BASE_CONFIG','Base configuration file successfully created.');
define('NDPHP_LANG_MOD_INSTALL_SUCCESS_HELP_DATA',	'Help data successfully created.');
define('NDPHP_LANG_MOD_INSTALL_UNABLE_OPEN_WRITE',	'Unable to open file for writing');
define('NDPHP_LANG_MOD_INSTALL_UNABLE_DB_CONNECT',	'Unable to connect to database with the provided settings.');
define('NDPHP_LANG_MOD_INSTALL_UNABLE_DB_OPERATION','Unable to perform all the required operations on database');
define('NDPHP_LANG_MOD_INSTALL_UNABLE_DB_IMPORT',	'Unable to import database dump file');
define('NDPHP_LANG_MOD_INSTALL_UNABLE_DB_ENTRY',	'Unable to create an initial configuration entry on the specified database.');
define('NDPHP_LANG_MOD_INSTALL_UNABLE_UPDATE_ADMIN','Unable to update admin user information on the specified database.');
define('NDPHP_LANG_MOD_INSTALL_UNABLE_UPDATE_APP',	'Unable to update application information on the specified database.');
define('NDPHP_LANG_MOD_INSTALL_UNABLE_FETCH_OK_URL','Unable to fetch an OK state from the URL');
define('NDPHP_LANG_MOD_INSTALL_UNABLE_INSERT_DATA',	'Unable to insert data into the database.');
define('NDPHP_LANG_MOD_INSTALL_WRITE_NO_PRIV',		'No privileges to write on');
define('NDPHP_LANG_MOD_INSTALL_TITLE_NDPHP_NAME',	'ND PHP Installation');
define('NDPHP_LANG_MOD_INSTALL_TITLE_APP_CONFIG',	'Application Configuration');
define('NDPHP_LANG_MOD_INSTALL_TITLE_DB_CONFIG',	'Database Configuration');
define('NDPHP_LANG_MOD_INSTALL_TITLE_INSTALL_OK',	'Installation Completed');
define('NDPHP_LANG_MOD_INSTALL_TITLE_USER_CONFIG',	'User Configuration');
define('NDPHP_LANG_MOD_INSTALL_TITLE_PRE_CHECKS',	'Pre Installation Checks');
define('NDPHP_LANG_MOD_INSTALL_FIELD_NEXT_STEPS',	'Next Steps');
define('NDPHP_LANG_MOD_INSTALL_FIELD_ACTIONS',		'Actions');
define('NDPHP_LANG_MOD_INSTALL_FIELD_PROJECT_NAME',	'Project Name');
define('NDPHP_LANG_MOD_INSTALL_FIELD_TAGLINE',		'Tagline');
define('NDPHP_LANG_MOD_INSTALL_FIELD_DESCRIPTION',	'Description');
define('NDPHP_LANG_MOD_INSTALL_FIELD_AUTHOR',		'Author');
define('NDPHP_LANG_MOD_INSTALL_FIELD_LANGUAGE',		'Language');
define('NDPHP_LANG_MOD_INSTALL_FIELD_TIMEZONE',		'Timezone');
define('NDPHP_LANG_MOD_INSTALL_FIELD_STATUS',		'Status');
define('NDPHP_LANG_MOD_INSTALL_FIELD_DB_DRIVER',	'Database Driver');
define('NDPHP_LANG_MOD_INSTALL_FIELD_DB_HOST',		'Database Host');
define('NDPHP_LANG_MOD_INSTALL_FIELD_DB_PORT',		'Database Port');
define('NDPHP_LANG_MOD_INSTALL_FIELD_DB_NAME',		'Database Name');
define('NDPHP_LANG_MOD_INSTALL_FIELD_DB_USERNAME',	'Database Username');
define('NDPHP_LANG_MOD_INSTALL_FIELD_DB_PASSWORD',	'Database Password');
define('NDPHP_LANG_MOD_INSTALL_FIELD_DB_CHARSET',	'Database Charset');
define('NDPHP_LANG_MOD_INSTALL_FIELD_DB_CONNECTION','Database Connection');
define('NDPHP_LANG_MOD_INSTALL_FIELD_PHP_EXT',		'PHP Extensions');
define('NDPHP_LANG_MOD_INSTALL_FIELD_DIR_PERMS',	'Directory Permissions');
define('NDPHP_LANG_MOD_INSTALL_FIELD_FILE_PERMS',	'File Permissions');
define('NDPHP_LANG_MOD_INSTALL_FIELD_USERNAME',		'Username');
define('NDPHP_LANG_MOD_INSTALL_FIELD_PASSWORD',		'Password');
define('NDPHP_LANG_MOD_INSTALL_FIELD_EMAIL',		'Email');
define('NDPHP_LANG_MOD_INSTALL_OP_CONTINUE',		'Continue');
define('NDPHP_LANG_MOD_INSTALL_OP_BACK',			'Back');
define('NDPHP_LANG_MOD_INSTALL_OP_TEST_DB_CONN',	'Test Connection');
define('NDPHP_LANG_MOD_INSTALL_HELP_CUSTOMIZE',		'Customize your project name, tagline, description and author name.');
define('NDPHP_LANG_MOD_INSTALL_HELP_TEST_DB_CONN',	'Test the connection to unlock the Continue button!');
define('NDPHP_LANG_MOD_INSTALL_HELP_LOGIN_AS',		'Click the Continue button and login as');
define('NDPHP_LANG_MOD_INSTALL_HELP_SET_PASSWORD',	'Set a strong password for user');
define('NDPHP_LANG_MOD_INSTALL_HELP_CREATE_DB',		'You\'ll need to create an empty MySQL or MariaDB database.');
define('NDPHP_LANG_MOD_INSTALL_HELP_NEED_QUESTION',	'Need Help?');
define('NDPHP_LANG_MOD_INSTALL_INFO_SUCCESSFUL',	'ND PHP Successfully Installed.');

define('NDPHP_LANG_MOD_PAYMENT_TAX_RATE',			'Tax Rate');
define('NDPHP_LANG_MOD_PAYMENT_PAYMENT_FEE',		'Payment Fee');
define('NDPHP_LANG_MOD_PAYMENT_TOTAL_TAX',			'Total Tax');
define('NDPHP_LANG_MOD_PAYMENT_STATUS_DESCRIPTION',	'Status Description');
define('NDPHP_LANG_MOD_PAYMENT_ITEM_PRICE',			'Item Price');
define('NDPHP_LANG_MOD_PAYMENT_ITEM_QUANTITY',		'Item Quantity');
define('NDPHP_LANG_MOD_PAYMENT_ITEM_DESCRIPTION',	'Item Description');
define('NDPHP_LANG_MOD_PAYMENT_PAYER_EMAIL',		'Payer Email');
define('NDPHP_LANG_MOD_PAYMENT_PAYER_FIRST_NAME',	'Payer First Name');
define('NDPHP_LANG_MOD_PAYMENT_PAYER_LAST_NAME',	'Payer Last Name');
define('NDPHP_LANG_MOD_PAYMENT_PAYER_ADDR_NAME',	'Payer Address Name');
define('NDPHP_LANG_MOD_PAYMENT_PAYER_ADDR_COUNTRY',	'Payer Address Country');
define('NDPHP_LANG_MOD_PAYMENT_PAYER_ADDR_CITY',	'Payer Address City');
define('NDPHP_LANG_MOD_PAYMENT_PAYER_ADDR_STREET',	'Payer Address Street');
define('NDPHP_LANG_MOD_PAYMENT_PAYER_ADDR_ZIP',		'Payer Address ZIP');
define('NDPHP_LANG_MOD_PAYMENT_PAYER_ADDR_STATE',	'Payer Address State');
define('NDPHP_LANG_MOD_PAYMENT_PAYER_ADDR_STATUS',	'Payer Address Status');
define('NDPHP_LANG_MOD_PAYMENT_PAYER_STATUS',		'Payer Status');
define('NDPHP_LANG_MOD_PAYMENT_PAYER_RESID_COUNTRY','Payer Residence Country');
define('NDPHP_LANG_MOD_PAYMENT_PAYER_PAYMENT_DATE', 'Payer Payment Date');
define('NDPHP_LANG_MOD_PAYMENT_TYPE',				'Payment Type');
define('NDPHP_LANG_MOD_PAYMENT_STATUS',				'Payment Status');
define('NDPHP_LANG_MOD_PAYMENT_ACTION',				'Payment Action');
define('NDPHP_LANG_MOD_PAYMENT_INVALID_AMMOUNT',	'Invalid payment ammount.');
define('NDPHP_LANG_MOD_PAYMENT_UNABLE_ID_USER',		'Unable to identify user from the referred payment.');
define('NDPHP_LANG_MOD_PAYMENT_CREDIT_AMMOUNT',		'Credit Ammount');
define('NDPHP_LANG_MOD_PAYMENT_PAYPAL_TITLE',		'Paypal Payment');
define('NDPHP_LANG_MOD_PAYMENT_PAYPAL_TAX_APPLY',	'Paypal taxes will apply.');
define('NDPHP_LANG_MOD_PAYMENT_NO_VAT_IF',			'VAT will not be charged if');
define('NDPHP_LANG_MOD_PAYMENT_NO_VAT_COND_1',		'Your billing address is outside the EU');
define('NDPHP_LANG_MOD_PAYMENT_NO_VAT_COND_2',		'You\'re a VAT registered company in a EU country.');
define('NDPHP_LANG_MOD_PAYMENT_CONTACT_OTHER_METHOD','For other payment methods, please contact the Billing Dept.');
define('NDPHP_LANG_MOD_PAYMENT_SUBMIT',				'Submit Payment');
define('NDPHP_LANG_MOD_PAYMENT_CANCELLED',			'Your payment was cancelled.');
define('NDPHP_LANG_MOD_PAYMENT_YOUR_OF',			'Your payment of');
define('NDPHP_LANG_MOD_PAYMENT_IDENTIFIED_BY_TXID',	'identified by Transaction ID');
define('NDPHP_LANG_MOD_PAYMENT_SUCCESSFUL_PROCESSED','was successfully processed');
define('NDPHP_LANG_MOD_PAYMENT_HOW_PAYPAL_WORKS',	'How PayPal works');
define('NDPHP_LANG_MOD_PAYMENT_PAYPAL_IPN_SUCCESS',	'Payment successful');
define('NDPHP_LANG_MOD_PAYMENT_PAYPAL_IPN_CANCEL',	'Payment cancelled');

define('NDPHP_LANG_MOD_REGISTER_USER_ACCT_IS_NOW',	'User account is now');
define('NDPHP_LANG_MOD_REGISTER_CHECK_MOBILE_INBOX','Please check your mobile inbox and confirm your mobile phone number in order to activate your account.');
define('NDPHP_LANG_MOD_REGISTER_CHECK_EMAIL_INBOX',	'Please check your mailbox and confirm your email address in order to activate your account.');
define('NDPHP_LANG_MOD_REGISTER_CHECK_MOBILE_EMAIL','Please check your email and mobile inbox and confirm your mobile phone number in order to activate your account.');
define('NDPHP_LANG_MOD_REGISTER_NO_DATA_MATCH',		'The supplied data do NOT match any records in our database. In order to improve the security and safety of our customers, failed attempts to recover credentials that match partial and meaningful existing data are recorded for further analysis. If you do not remember the correct data of your own account, please contact support.');
define('NDPHP_LANG_MOD_REGISTER_EMAIL_RECOVER_INFO','Please check your email inbox for further information regarding this credentials recovery. You can use the button below to login into your account.');
define('NDPHP_LANG_MOD_REGISTER_PHONE_CONFIRMED',	'Phone already confirmed.');
define('NDPHP_LANG_MOD_REGISTER_EMAIL_CONFIRMED',	'Email already confirmed.');
define('NDPHP_LANG_MOD_REGISTER_USER_REGISTRATION',	'User Registration');
define('NDPHP_LANG_MOD_REGISTER_CONFIRM_SMS_TOKEN',	'Confirm SMS Token');
define('NDPHP_LANG_MOD_REGISTER_CONFIRM_EMAIL_STATUS','Email confirmation status');

define('NDPHP_LANG_MOD_RECOVER_REGISTERED_EMAIL',	'Registered Email');
define('NDPHP_LANG_MOD_RECOVER_REGISTERED_PHONE',	'Registered Phone Number');
define('NDPHP_LANG_MOD_RECOVER_FIRST_NAME',			'First Name');
define('NDPHP_LANG_MOD_RECOVER_LAST_NAME',			'Last Name');
define('NDPHP_LANG_MOD_RECOVER_COUNTRY',			'Country');

define('NDPHP_LANG_MOD_SUBSCRIPTION_UPGRADE',		'Subscription Upgrade');
define('NDPHP_LANG_MOD_SUBSCRIPTION_UPGRADE_SUCCESS','Your account was successfully upgraded to');
define('NDPHP_LANG_MOD_SUBSCRIPTION_CHOOSE_NEW',	'Choose a new subscription');
define('NDPHP_LANG_MOD_SUBSCRIPTION_DEBT_PREFIX',	'An amount of');
define('NDPHP_LANG_MOD_SUBSCRIPTION_DEBT_SUFFIX',	'was subtracted from your account balance.');

define('NDPHP_LANG_MOD_TABS_TITLE_LISTING',			'Listing');
define('NDPHP_LANG_MOD_TABS_TITLE_CHARTS',			'Charts');
define('NDPHP_LANG_MOD_TABS_TITLE_SEARCH_ADVANCED',	'Advanced Search');
define('NDPHP_LANG_MOD_TABS_TITLE_SEARCH_SAVED',	'Saved Searches');
define('NDPHP_LANG_MOD_TABS_TITLE_GROUPS',			'Groups');
define('NDPHP_LANG_MOD_TABS_TITLE_MAIN_GENERIC',	'General');

define('NDPHP_LANG_MOD_SEP_USER_SUBSCRIPTION',		'Subscription');
define('NDPHP_LANG_MOD_SEP_USER_PERSONAL',			'Personal');
define('NDPHP_LANG_MOD_SEP_USER_REGISTER',			'Register');
define('NDPHP_LANG_MOD_SEP_USER_CREDIT',			'Credit');
define('NDPHP_LANG_MOD_SEP_USER_API',				'API');
define('NDPHP_LANG_MOD_SEP_USER_ROLES',				'Roles');
define('NDPHP_LANG_MOD_SEP_USER_ACCOUNTING',		'Accounting');
define('NDPHP_LANG_MOD_SEP_CONFIGURATION_PROJECT',	'Project');
define('NDPHP_LANG_MOD_SEP_CONFIGURATION_FEATURES',	'Features');

define('NDPHP_LANG_MOD_BUTTON_SEARCH',				'Search Button');

define('NDPHP_LANG_MOD_DEFAULT_CURRENCY',			'USD');
define('NDPHP_LANG_MOD_DEFAULT_CURRENCY_SYMBOL',	'$');
define('NDPHP_LANG_MOD_DEFAULT_CHARSET',			'UTF-8');
define('NDPHP_LANG_MOD_DEFAULT_TIMEZONE',			'Etc/UTC');
define('NDPHP_LANG_MOD_DEFAULT_LOCALE',				'en_US');

define('NDPHP_LANG_MOD_SEARCH_OPT_EXACT',			'Exact match');
define('NDPHP_LANG_MOD_SEARCH_OPT_DIFF',			'Different');
define('NDPHP_LANG_MOD_SEARCH_OPT_LESSER',			'Lesser');
define('NDPHP_LANG_MOD_SEARCH_OPT_EQUAL',			'Equal');
define('NDPHP_LANG_MOD_SEARCH_OPT_GREATER',			'Greater');
define('NDPHP_LANG_MOD_SEARCH_OPT_BETWEEN',			'Between');
define('NDPHP_LANG_MOD_SEARCH_OPT_MORE',			'More options');
define('NDPHP_LANG_MOD_SEARCH_OPT_CUSTOM',			'... or custom interval');
define('NDPHP_LANG_MOD_SEARCH_INTERVAL_SECOND',		'second');
define('NDPHP_LANG_MOD_SEARCH_INTERVAL_SECONDS',	'seconds');
define('NDPHP_LANG_MOD_SEARCH_INTERVAL_MINUTE',		'minute');
define('NDPHP_LANG_MOD_SEARCH_INTERVAL_MINUTES',	'minutes');
define('NDPHP_LANG_MOD_SEARCH_INTERVAL_HOUR',		'hour');
define('NDPHP_LANG_MOD_SEARCH_INTERVAL_HOURS',		'hours');
define('NDPHP_LANG_MOD_SEARCH_INTERVAL_DAY',		'day');
define('NDPHP_LANG_MOD_SEARCH_INTERVAL_DAYS',		'days');
define('NDPHP_LANG_MOD_SEARCH_INTERVAL_WEEK',		'week');
define('NDPHP_LANG_MOD_SEARCH_INTERVAL_WEEKS',		'weeks');
define('NDPHP_LANG_MOD_SEARCH_INTERVAL_MONTH',		'month');
define('NDPHP_LANG_MOD_SEARCH_INTERVAL_MONTHS',		'months');
define('NDPHP_LANG_MOD_SEARCH_INTERVAL_YEAR',		'year');
define('NDPHP_LANG_MOD_SEARCH_INTERVAL_YEARS',		'years');
define('NDPHP_LANG_MOD_SEARCH_INTERVAL_PREVIOUS',	'previous'); /* previous x months, years ... (without accents) */
define('NDPHP_LANG_MOD_SEARCH_INTERVAL_LAST',		'last'); /* last x months, years ... (without accents) */
define('NDPHP_LANG_MOD_SEARCH_INTERVAL_NEXT',		'next'); /* next x months, years ... (without accents) */
define('NDPHP_LANG_MOD_SEARCH_INTERVAL_IN',			'in'); /* in x months, years ... (without accents) */


define('NDPHP_LANG_MOD_SEARCH_GLOBAL_NRESULT_PREFIX','Found');
define('NDPHP_LANG_MOD_SEARCH_GLOBAL_NRESULT_SUFFIX','results.');

define('NDPHP_LANG_MOD_ACCESSIBILITY_MENU_BUTTON',	'Menu Button');
define('NDPHP_LANG_MOD_ACCESSIBILITY_TEXT_SIZE',	'Text Size');
define('NDPHP_LANG_MOD_ACCESSIBILITY_OP_ENLARGE',	'Enlarge');
define('NDPHP_LANG_MOD_ACCESSIBILITY_OP_REDUCE',	'Reduce');
define('NDPHP_LANG_MOD_ACCESSIBILITY_OP_CONTRAST',	'High Contrast');
define('NDPHP_LANG_MOD_ACCESSIBILITY_OP_RELOAD',	'Reload');

define('NDPHP_LANG_MOD_OP_ACCESS_KEY_CREATE',		'c');
define('NDPHP_LANG_MOD_OP_ACCESS_KEY_EDIT',			'e');
define('NDPHP_LANG_MOD_OP_ACCESS_KEY_REMOVE',		'r');
define('NDPHP_LANG_MOD_OP_ACCESS_KEY_VIEW',			'v');
define('NDPHP_LANG_MOD_OP_ACCESS_KEY_LIST',			'l');
define('NDPHP_LANG_MOD_OP_ACCESS_KEY_GROUPS',		'g');
define('NDPHP_LANG_MOD_OP_ACCESS_KEY_SEARCH',		's');
define('NDPHP_LANG_MOD_OP_ACCESS_KEY_SEARCH_BASIC',	'f');
define('NDPHP_LANG_MOD_OP_ACCESS_KEY_LOAD_HOME',	'h');
define('NDPHP_LANG_MOD_OP_ACCESS_KEY_AUTOFOCUS',	'a');
define('NDPHP_LANG_MOD_OP_ACCESS_KEY_DOCUMENTATION','d');
define('NDPHP_LANG_MOD_OP_ACCESS_KEY_JSON',			'j');

define('NDPHP_LANG_MOD_HELP_BUILDER_BUILD',						'The build number.');
define('NDPHP_LANG_MOD_HELP_BUILDER_CREATED',					'The date and time when the build was performed.');
define('NDPHP_LANG_MOD_HELP_BUILDER_MODEL',						'The application model used for this build.');

define('NDPHP_LANG_MOD_HELP_CHARTS_CONFIG_TITLE',				'The title that will be used in the chart image.');
define('NDPHP_LANG_MOD_HELP_CHARTS_CONFIG_CONTROLLER',			'The controller name, in lowercase, to which this chart belongs.');
define('NDPHP_LANG_MOD_HELP_CHARTS_CONFIG_CHARTS_TYPES_ID',		'The chart type.');
define('NDPHP_LANG_MOD_HELP_CHARTS_CONFIG_CHARTS_GEOMETRY_ID',	'The chart geometry.');
define('NDPHP_LANG_MOD_HELP_CHARTS_CONFIG_FIELDS',				'A comma separated list of fields, in lowercase, whose values will be plotted in the chart. Do not include field names that would be used as Abscissa or Legend.');
define('NDPHP_LANG_MOD_HELP_CHARTS_CONFIG_ABSCISSA',			'The field name, in lowercase, that will be used as Abscissa.');
define('NDPHP_LANG_MOD_HELP_CHARTS_CONFIG_FOREIGN_TABLE', 		'The foreign table name, in lowercase, containing the foreign fields.');
define('NDPHP_LANG_MOD_HELP_CHARTS_CONFIG_FIELD',				'The relationship field of the current or foreign controller (depending on the chart type) which will be used as aggregator.');
define('NDPHP_LANG_MOD_HELP_CHARTS_CONFIG_FIELD_LEGEND',		'The field whose values will be used as legend (or abscissa) on Pie and Bar charts.');
define('NDPHP_LANG_MOD_HELP_CHARTS_CONFIG_FIELD_TOTAL',			'The field that will be used for total counts (summation).');
define('NDPHP_LANG_MOD_HELP_CHARTS_CONFIG_IMPORT_CTRL',			'The controller from which the Chart ID will be imported from.');
define('NDPHP_LANG_MOD_HELP_CHARTS_CONFIG_CHARTID',				'The Chart ID that will be imported from the specified import controller.');
define('NDPHP_LANG_MOD_HELP_CHARTS_CONFIG_FIELD_TS',			'A field that represent the time series for the data fields. Typically, this field will be the abscissa, but any other datetime field can be specified.');
define('NDPHP_LANG_MOD_HELP_CHARTS_CONFIG_START_TS',			'A timestamp that will be used as a starting point in time to filter data fields values. Used to define time ranges. Leave blank for full data plot.');
define('NDPHP_LANG_MOD_HELP_CHARTS_CONFIG_END_TS',				'A timestamp that will be used as a end point in time to filter data fields values. Used to define time ranges. Leave blank for full data plot.');

define('NDPHP_LANG_MOD_HELP_CHARTS_GEOMETRY_CHART_GEOMETRY',	'The chart geometry.');
define('NDPHP_LANG_MOD_HELP_CHARTS_GEOMETRY_DESCRIPTION',		'The chart geometry description.');

define('NDPHP_LANG_MOD_HELP_CHARTS_TYPES_CHART_TYPE',			'The chart type.');
define('NDPHP_LANG_MOD_HELP_CHARTS_TYPES_DESCRIPTION',			'The chart type description.');

define('NDPHP_LANG_MOD_HELP_CONFIGURATION_CONFIGURATION', 		'The configuration name.');
define('NDPHP_LANG_MOD_HELP_CONFIGURATION_BASE_URL',			'The base url of the application.');
define('NDPHP_LANG_MOD_HELP_CONFIGURATION_PAGE_ROWS',			'The total number of rows that will be displayed per page on listing and result views.');
define('NDPHP_LANG_MOD_HELP_CONFIGURATION_TEMPORARY_DIRECTORY',	'A temporary directory in the file system that will be used to store temporary files. Must have write permissions for the web server user.');
define('NDPHP_LANG_MOD_HELP_CONFIGURATION_THEMES_ID',			'The theme that will be used when rendering application views.');
define('NDPHP_LANG_MOD_HELP_CONFIGURATION_TIMEZONES_ID',		'The default timezone used by the application. Must match the system and database timezones.');
define('NDPHP_LANG_MOD_HELP_CONFIGURATION_ROLES_ID',			'The default role that will be assigned to newly registered users.');
define('NDPHP_LANG_MOD_HELP_CONFIGURATION_MAINTENANCE',			'When set to true, the application will enter in maintenance mode.');
define('NDPHP_LANG_MOD_HELP_CONFIGURATION_ACTIVE',				'When set to true, will set this configuration entry as active. Only one active configuration is allowed.');
define('NDPHP_LANG_MOD_HELP_CONFIGURATION_MODEL',				'The application model assigned to this configuration.');
define('NDPHP_LANG_MOD_HELP_CONFIGURATION_PROJECT_NAME',		'The project name. Should be a short name without descriptions nor taglines');
define('NDPHP_LANG_MOD_HELP_CONFIGURATION_PROJECT_VERSION',		'The current project version.');
define('NDPHP_LANG_MOD_HELP_CONFIGURATION_PROJECT_DATE',		'The last time the project was modified (installed/updated)');
define('NDPHP_LANG_MOD_HELP_CONFIGURATION_TAGLINE',				'The project tagline. Should be short.');
define('NDPHP_LANG_MOD_HELP_CONFIGURATION_DESCRIPTION',			'The project description.');
define('NDPHP_LANG_MOD_HELP_CONFIGURATION_AUTHOR',				'The project main author.');
define('NDPHP_LANG_MOD_HELP_CONFIGURATION_SMTP_USERNAME',		'The username that will be used for authentication with the SMTP server.');
define('NDPHP_LANG_MOD_HELP_CONFIGURATION_SMTP_PASSWORD',		'The password that will be used for authentication with the SMTP server.');
define('NDPHP_LANG_MOD_HELP_CONFIGURATION_SMTP_SERVER',			'The IP address or Host name of the SMTP server.');
define('NDPHP_LANG_MOD_HELP_CONFIGURATION_SMTP_PORT',			'The remote SMTP server port.');
define('NDPHP_LANG_MOD_HELP_CONFIGURATION_SMTP_SSL',			'Enable SSL on SMTP communications. Cannot be used along with TLS.');
define('NDPHP_LANG_MOD_HELP_CONFIGURATION_SMTP_TLS',			'Enable TLS on SMTP communications. Cannot be used along with SSL.');
define('NDPHP_LANG_MOD_HELP_CONFIGURATION_MEMCACHED_SERVER',	'The Memcached server IP address or Host name.');
define('NDPHP_LANG_MOD_HELP_CONFIGURATION_MEMCACHED_PORT',		'The remote Memcached server port.');
define('NDPHP_LANG_MOD_HELP_CONFIGURATION_RECAPTCHA_PRIV_KEY',	'The private key of the reCAPTCHA service.');
define('NDPHP_LANG_MOD_HELP_CONFIGURATION_RECAPTCHA_PUB_KEY',	'The public key of the reCAPTCHA service.');

define('NDPHP_LANG_MOD_HELP_COUNTRIES_COUNTRY',					'The country name.');
define('NDPHP_LANG_MOD_HELP_COUNTRIES_CODE',					'The country short code.');
define('NDPHP_LANG_MOD_HELP_COUNTRIES_PREFIX',					'The country international phone prefix');
define('NDPHP_LANG_MOD_HELP_COUNTRIES_EU_STATE',				'Set to true if the country is a EU member.');
define('NDPHP_LANG_MOD_HELP_COUNTRIES_VAT_RATE',				'If the country is a EU member, this field will contain the maximum VAT rate applied in that country.');

define('NDPHP_LANG_MOD_HELP_DBMS_ALIAS',						'The alias for the database name. This is the value used by load routines.');
define('NDPHP_LANG_MOD_HELP_DBMS_NAME',							'The real database name.');
define('NDPHP_LANG_MOD_HELP_DBMS_HOST',							'The database server hostname or IP address. Defaults to 127.0.0.1.');
define('NDPHP_LANG_MOD_HELP_DBMS_PORT',							'The remote database server port. Defaults to 3306.');
define('NDPHP_LANG_MOD_HELP_DBMS_USERNAME',						'The database username.');
define('NDPHP_LANG_MOD_HELP_DBMS_PASSWORD',						'The database password.');
define('NDPHP_LANG_MOD_HELP_DBMS_CHARSET',						'The default database charset. Defaults to utf8.');
define('NDPHP_LANG_MOD_HELP_DBMS_STRICT',						'Whether strict mode should be used.');

define('NDPHP_LANG_MOD_HELP_DOCUMENTATION_REVISION',			'The documentation revision.');
define('NDPHP_LANG_MOD_HELP_DOCUMENTATION_CHANGED',				'Last time the documentation was changed.');
define('NDPHP_LANG_MOD_HELP_DOCUMENTATION_DESCRIPTION',			'The long description for this documentation entry.');

define('NDPHP_LANG_MOD_HELP_FEATURES_FEATURE',					'The feature identifier. Must be capitalized.');
define('NDPHP_LANG_MOD_HELP_FEATURES_DESCRIPTION',				'The long description for this feature.');

define('NDPHP_LANG_MOD_HELP_ITEMS_ITEM',						'The item short name.');
define('NDPHP_LANG_MOD_HELP_ITEMS_DESCRIPTION',					'The long description for this item.');
define('NDPHP_LANG_MOD_HELP_ITEMS_PRICE',						'The item price as a decimal value.');

define('NDPHP_LANG_MOD_HELP_LOGGING_OPERATION',					'The operation type that generated this log entry.');
define('NDPHP_LANG_MOD_HELP_LOGGING_TABLE',						'The table (or controller name) to which this log entry belongs.');
define('NDPHP_LANG_MOD_HELP_LOGGING_FIELD',						'The table column to which this log entry belongs to.');
define('NDPHP_LANG_MOD_HELP_LOGGING_ENTRYID',					'The table row (Entry ID) to which this log entry belongs to.');
define('NDPHP_LANG_MOD_HELP_LOGGING_VALUE_OLD',					'The field value prior to this log entry was generated.');
define('NDPHP_LANG_MOD_HELP_LOGGING_VALUE_NEW',					'The actual field value when this log entry was generated.');
define('NDPHP_LANG_MOD_HELP_LOGGING_TRANSACTION',				'The transaction identifier. This value is common to all log entries referencing the same transaction.');
define('NDPHP_LANG_MOD_HELP_LOGGING_REGISTERED',				'When this log entry was recorded.');
define('NDPHP_LANG_MOD_HELP_LOGGING_ROLLED_BACK',				'When true, it means that all log entries associated to the transaction value of this log entry were rolled back.');
define('NDPHP_LANG_MOD_HELP_LOGGING_SESSIONS_ID',				'The session that generated this log entry.');
define('NDPHP_LANG_MOD_HELP_LOGGING_USERS_ID',					'The user that generated this log entry.');

define('NDPHP_LANG_MOD_HELP_MONTHS_MONTH',						'The month of the year in alphanumeric format.');
define('NDPHP_LANG_MOD_HELP_MONTHS_NUMBER',						'The month of the year in numeric format (1 to 12).');

define('NDPHP_LANG_MOD_HELP_NOTIFICATIONS_NOTIFICATION',		'The notification title.');
define('NDPHP_LANG_MOD_HELP_NOTIFICATIONS_DESCRIPTION',			'The long description of the notification.');
define('NDPHP_LANG_MOD_HELP_NOTIFICATIONS_URL',					'An optional notification URL');
define('NDPHP_LANG_MOD_HELP_NOTIFICATIONS_SEEN',				'True if this notification was seen by the user.');
define('NDPHP_LANG_MOD_HELP_NOTIFICATIONS_ALL',					'Notify all users.');
define('NDPHP_LANG_MOD_HELP_NOTIFICATIONS_WHEN',				'When this notification will take place. A future time value is advised. Using current or past time values will cause the notification to be automatically marked as seen.');
define('NDPHP_LANG_MOD_HELP_NOTIFICATIONS_USERS_ID',			'The user to which this notification belongs.');

define('NDPHP_LANG_MOD_HELP_PAYMENT_ACTIONS_PAYMENT_ACTION',	'Short name for the payment action.');
define('NDPHP_LANG_MOD_HELP_PAYMENT_ACTIONS_DESCRIPTION',		'The long description for the payment action.');

define('NDPHP_LANG_MOD_HELP_PAYMENT_STATUS_PAYMENT_STATUS',		'Short name for the payment status.');
define('NDPHP_LANG_MOD_HELP_PAYMENT_STATUS_DESCRIPTION',		'The long description for the payment status.');

define('NDPHP_LANG_MOD_HELP_PAYMENT_TYPES_PAYMENT_TYPE',		'Short name for the payment type.');
define('NDPHP_LANG_MOD_HELP_PAYMENT_TYPES_TRANSACTION_FEE_PERCENTAGE',	'The percentual value that will be added to the payment net value, as transaction fee.');
define('NDPHP_LANG_MOD_HELP_PAYMENT_TYPES_TRANSACTION_MIN_AMMOUNT',		'The minimum value acceptable to consider a transaction valid.');
define('NDPHP_LANG_MOD_HELP_PAYMENT_TYPES_TRANSACTION_MAX_AMMOUNT',		'The maximum value acceptalbe to consider a transaction valid.');
define('NDPHP_LANG_MOD_HELP_PAYMENT_TYPES_DESCRIPTION',			'The long description for the payment type.');

define('NDPHP_LANG_MOD_HELP_PAYMENTS_TXNID',					'The transaction unique identifier.');
define('NDPHP_LANG_MOD_HELP_PAYMENTS_PAYMENT_TYPES_ID',			'The payment type.');
define('NDPHP_LANG_MOD_HELP_PAYMENTS_AMOUNT',					'The payment net amount.');
define('NDPHP_LANG_MOD_HELP_PAYMENTS_TAX_RATE',					'The base tax rate applied to the net amount.');
define('NDPHP_LANG_MOD_HELP_PAYMENTS_PAYMENT_FEE',				'The fee associated to this payment.');
define('NDPHP_LANG_MOD_HELP_PAYMENTS_TOTAL_TAX',				'The sum of all taxes.');
define('NDPHP_LANG_MOD_HELP_PAYMENTS_PAYMENT_STATUS_ID',		'The status for this payment.');
define('NDPHP_LANG_MOD_HELP_PAYMENTS_STATUS_DESC',				'The long description for the payment status.');
define('NDPHP_LANG_MOD_HELP_PAYMENTS_ITEMS_ID',					'The item associated to this payment.');
define('NDPHP_LANG_MOD_HELP_PAYMENTS_ITEM_PRICE',				'The unit price associated to the item.');
define('NDPHP_LANG_MOD_HELP_PAYMENTS_ITEM_QUANTITY',			'The quatity of items.');
define('NDPHP_LANG_MOD_HELP_PAYMENTS_ITEM_DESCRIPTION',			'The long description for the item.');
define('NDPHP_LANG_MOD_HELP_PAYMENTS_CREATED',					'When this payment entry was generated.');
define('NDPHP_LANG_MOD_HELP_PAYMENTS_UPDATED',					'The last time this payment entry was updated (payment status change).');
define('NDPHP_LANG_MOD_HELP_PAYMENTS_USERS_ID',					'The user that generated this payment entry.');
define('NDPHP_LANG_MOD_HELP_PAYMENTS_PAYMENT_ACTIONS_ID',		'The last payment action taken.');
define('NDPHP_LANG_MOD_HELP_PAYMENTS_PAYER_EMAIL',				'Email address of the payer.');
define('NDPHP_LANG_MOD_HELP_PAYMENTS_PAYER_FIRST_NAME',			'First real name of the payer.');
define('NDPHP_LANG_MOD_HELP_PAYMENTS_PAYER_LAST_NAME',			'Last real name of the payer.');
define('NDPHP_LANG_MOD_HELP_PAYMENTS_PAYER_ADDRESS_NAME',		'Short name for the billing address of the payer.');
define('NDPHP_LANG_MOD_HELP_PAYMENTS_PAYER_ADDRESS_COUNTRY',	'Billing country of the payer.');
define('NDPHP_LANG_MOD_HELP_PAYMENTS_PAYER_ADDRESS_CITY',		'Billing city name of the payer.');
define('NDPHP_LANG_MOD_HELP_PAYMENTS_PAYER_ADDRESS_STREET',		'Billing street name of the payer.');
define('NDPHP_LANG_MOD_HELP_PAYMENTS_PAYER_ADDRESS_ZIP',		'Billing ZIP or Postcode of the payer.');
define('NDPHP_LANG_MOD_HELP_PAYMENTS_PAYER_ADDRESS_STATE',		'Billing state of the payer.');
define('NDPHP_LANG_MOD_HELP_PAYMENTS_PAYER_ADDRESS_STATUS',		'Status of the billing address (Eg: Confirmed).');
define('NDPHP_LANG_MOD_HELP_PAYMENTS_PAYER_STATUS',				'Status of the payer.');
define('NDPHP_LANG_MOD_HELP_PAYMENTS_PAYER_RESIDENCE_COUNTRY',	'Country for the main residence of the payer.');
define('NDPHP_LANG_MOD_HELP_PAYMENTS_PAYER_PAYMENT_DATE',		'When the payer performed the payment.');

define('NDPHP_LANG_MOD_HELP_ROLES_ROLE',						'Short name for the role. Must be capitablized.');
define('NDPHP_LANG_MOD_HELP_ROLES_DESCRIPTION',					'The long description for the role.');

define('NDPHP_LANG_MOD_HELP_SCHEDULER_ENTRY_NAME',				'A unique identifier for this scheduler entry.');
define('NDPHP_LANG_MOD_HELP_SCHEDULER_DESCRIPTION',				'The long description for this scheduler entry.');
define('NDPHP_LANG_MOD_HELP_SCHEDULER_URL',						'The URL that will be called when this scheduler entry is triggered.');
define('NDPHP_LANG_MOD_HELP_SCHEDULER_PERIOD',					'The trigger period. In other words, how frequently this scheduler entry will be triggered. This value is in seconds.');
define('NDPHP_LANG_MOD_HELP_SCHEDULER_ACTIVE',					'When set to true, this scheduler entry will be activated.');
define('NDPHP_LANG_MOD_HELP_SCHEDULER_REGISTERED',				'When this scheduler entry was created.');
define('NDPHP_LANG_MOD_HELP_SCHEDULER_LAST_RUN',				'The last time this scheduled entry run.');
define('NDPHP_LANG_MOD_HELP_SCHEDULER_NEXT_RUN',				'The next time that this scheduled entry will run.');
define('NDPHP_LANG_MOD_HELP_SCHEDULER_OUTPUT',					'The last generated output from the URL.');

define('NDPHP_LANG_MOD_HELP_SESSIONS_SESSION',					'The session identifier.');
define('NDPHP_LANG_MOD_HELP_SESSION_IP_ADDRESS',				'The IP Adddress associated to this session.');
define('NDPHP_LANG_MOD_HELP_SESSION_USER_AGENT',				'The user-agent associated to this session.');
define('NDPHP_LANG_MOD_HELP_SESSION_START_TIME',				'When this session come live for the first time.');
define('NDPHP_LANG_MOD_HELP_SESSION_LAST_LOGIN',				'The last time someone logged in into this session.');
define('NDPHP_LANG_MOD_HELP_SESSION_USERS_ID',					'The user associated to this session.');

define('NDPHP_LANG_MOD_HELP_SUBSCRIPTION_TYPES_SUBSCRIPTION_TYPE',	'Short name for the subscription type.');
define('NDPHP_LANG_MOD_HELP_SUBSCRIPTION_TYPES_DESCRIPTION',	'The long description for the subscription type.');
define('NDPHP_LANG_MOD_HELP_SUBSCRIPTION_TYPES_PRICE',			'The periodic cost of this subscription type.');
define('NDPHP_LANG_MOD_HELP_SUBSCRIPTION_TYPES_API_EXTENDED',	'Whether this subscription type gives access to extended API features.');

define('NDPHP_LANG_MOD_HELP_THEMES_THEME',						'Short name for the theme.');
define('NDPHP_LANG_MOD_HELP_THEMES_DESCRIPTION',				'The long description for the theme.');
define('NDPHP_LANG_MOD_HELP_THEMES_ANIMATION_DEFAULT_DELAY',	'The client side delay for default animations.');
define('NDPHP_LANG_MOD_HELP_THEMES_ANIMATION_ORDERING_DELAY',	'The client side delay for ordering operations on listing and result views.');
define('NDPHP_LANG_MOD_HELP_THEMES_ANIMATIONS_DEFAULT_ID',		'The default animation type.');
define('NDPHP_LANG_MOD_HELP_THEMES_ANIMATIONS_ORDERING_ID',		'The ordering animation type.');

define('NDPHP_LANG_MOD_HELP_THEMES_ANIMATIONS_DEFAULT_ANIMATION',	'Short name for the animation.');
define('NDPHP_LANG_MOD_HELP_THEMES_ANIMATIONS_DEFAULT_DESCRIPTION',	'The long description for the animation.');

define('NDPHP_LANG_MOD_HELP_THEMES_ANIMATIONS_ORDERING_ANIMATION',	'Short name for the animation.');
define('NDPHP_LANG_MOD_HELP_THEMES_ANIMATIONS_ORDERING_DESCRIPTION','The long description for the animation.');

define('NDPHP_LANG_MOD_HELP_TIMEZONES_TIMEZONE',				'The standard timezone name.');
define('NDPHP_LANG_MOD_HELP_TIMEZONES_COUNTRIES_ID',			'The country to which the timezone refers to.');
define('NDPHP_LANG_MOD_HELP_TIMEZONES_UTC',						'The base UTC offset in hours. Positive and negative values are accepted.');
define('NDPHP_LANG_MOD_HELP_TIMEZONES_UTC_DST',					'The Day Light Saving UTC offset in hours. Positive and negative values are accepted.');
define('NDPHP_LANG_MOD_HELP_TIMEZONES_COORDINATES',				'The location coordinates to which this timezone refers to.');

define('NDPHP_LANG_MOD_HELP_TRANSACTION_HISTORY_TRANSACTION_DATE',	'When this transaction took place.');
define('NDPHP_LANG_MOD_HELP_TRANSACTION_HISTORY_TRANSACTION_TYPES_ID',	'The type of this transaction.');
define('NDPHP_LANG_MOD_HELP_TRANSACTION_HISTORY_AMOUNT',		'The transaction amount.');
define('NDPHP_LANG_MOD_HELP_TRANSACTION_HISTORY_DESCRIPTION',	'The long description for this transaction.');
define('NDPHP_LANG_MOD_HELP_TRANSACTION_HISTORY_USERS_ID',		'The user that performed this transaction.');

define('NDPHP_LANG_MOD_HELP_TRANSACTION_TYPES_TRANSACTION_TYPE',	'Short name for the transaction type.');
define('NDPHP_LANG_MOD_HELP_TRANSACTION_TYPES_DESCRIPTION',		'The long description for the transaction type.');

define('NDPHP_LANG_MOD_HELP_USERS_USERNAME',					'The username of the user. This is the value used for authentication. This value is not part of REST API authorization.');
define('NDPHP_LANG_MOD_HELP_USERS_PASSWORD',					'The user passsword used for web authentication. This value is not part of REST API authorization.');
define('NDPHP_LANG_MOD_HELP_USERS_FILE_PHOTO',					'The user avatar. File type and extension must be on of PNG, GIF, JPG, BMP or SVG');
define('NDPHP_LANG_MOD_HELP_USERS_EMAIL',						'Primary email address of the user.');
define('NDPHP_LANG_MOD_HELP_USERS_PHONE',						'Primary phone number of the user.');
define('NDPHP_LANG_MOD_HELP_USERS_ACTIVE',						'When set to true, this user account is active.');
define('NDPHP_LANG_MOD_HELP_USERS_LOCKED',						'When set to true, this user account is locked. The user won\'t be able to login even if the account is active.');
define('NDPHP_LANG_MOD_HELP_USERS_SUBSCRIPTION_TYPES_ID',		'The user subscription.');
define('NDPHP_LANG_MOD_HELP_USERS_SUBSCRIPTION_CHANGE_DATE',	'The last time the user changed his/her subscription.');
define('NDPHP_LANG_MOD_HELP_USERS_SUBSCRIPTION_RENEW_DATE',		'The next time that user account will be billed for subscription renewal.');
define('NDPHP_LANG_MOD_HELP_USERS_FIRST_NAME',					'First real name of the user.');
define('NDPHP_LANG_MOD_HELP_USERS_LAST_NAME',					'Last real name of the user.');
define('NDPHP_LANG_MOD_HELP_USERS_COUNTRIES_ID',				'The user\'s country.');
define('NDPHP_LANG_MOD_HELP_USERS_TIMEZONES_ID',				'The primary timezone of the user. Note that this value will be accounted for when showing datetime values.');
define('NDPHP_LANG_MOD_HELP_USERS_COMPANY',						'Company or organization name.');
define('NDPHP_LANG_MOD_HELP_USERS_ADDRESS_LINE1',				'The first line of the user street address. Typically containing the building and street name');
define('NDPHP_LANG_MOD_HELP_USERS_ADDRESS_LINE2',				'The second line of the user street address. Typically containing the street number and story.');
define('NDPHP_LANG_MOD_HELP_USERS_CITY',						'The city name to which the street address refers to.');
define('NDPHP_LANG_MOD_HELP_USERS_POSTCODE',					'The postcode or zip of the address.');
define('NDPHP_LANG_MOD_HELP_USERS_VAT',							'The VAT number of the user or the company. This value will be used for invoices.');
define('NDPHP_LANG_MOD_HELP_USERS_EXPIRE',						'Account will be active until this date is reached.');
define('NDPHP_LANG_MOD_HELP_USERS_REGISTERED',					'Account creation time.');
define('NDPHP_LANG_MOD_HELP_USERS_LAST_LOGIN',					'The last seen login for this user.');
define('NDPHP_LANG_MOD_HELP_USERS_CONFIRM_EMAIL_HASH',			'Internal generated hash used to confirm the email address.');
define('NDPHP_LANG_MOD_HELP_USERS_CONFIRM_PHONE_TOKEN',			'Internal generated token used to confirm the phone number.');
define('NDPHP_LANG_MOD_HELP_USERS_EMAIL_CONFIRMED',				'When true, the email was confirmed.');
define('NDPHP_LANG_MOD_HELP_USERS_PHONE_CONFIRMED',				'When true, the phone number was confirmed.');
define('NDPHP_LANG_MOD_HELP_USERS_DATE_CONFIRMED',				'When the account was confirmed to be valid for the first time.');
define('NDPHP_LANG_MOD_HELP_USERS_CREDIT',						'The current user\'s credit.');
define('NDPHP_LANG_MOD_HELP_USERS_ALLOW_NEGATIVE',				'Whether this account will be allowed to keep a negative credit value.');
define('NDPHP_LANG_MOD_HELP_USERS_APIKEY',						'The REST API key used for REST API authorization.');
define('NDPHP_LANG_MOD_HELP_USERS_ACCT_LAST_REST',				'The last time the accounting counters were reset.');
define('NDPHP_LANG_MOD_HELP_USERS_ACCT_REST_LIST',				'Counter for the number of LIST operations performed via REST API.');
define('NDPHP_LANG_MOD_HELP_USERS_ACCT_REST_RESULT',			'Counter for the number of RESULT operations performed via REST API.');
define('NDPHP_LANG_MOD_HELP_USERS_ACCT_REST_VIEW',				'Counter for the number of VIEW operations performed via REST API.');
define('NDPHP_LANG_MOD_HELP_USERS_ACCT_REST_DELETE',			'Counter for the number of DELETE operations performed via REST API.');
define('NDPHP_LANG_MOD_HELP_USERS_ACCT_REST_UPDATE',			'Counter for the number of UPDATE operations performed via REST API.');
define('NDPHP_LANG_MOD_HELP_USERS_ACCT_REST_INSERT',			'Counter for the number of INSERT operations performed via REST API.');
define('NDPHP_LANG_MOD_HELP_USERS_DBMS_ID',						'When sharding features are enabled, this is the database where the user data will be fetched from and stored to.');

define('NDPHP_LANG_MOD_HELP_WEEKDAYS_WEEKDAY',					'The weekday value in alphanumeric format.');
define('NDPHP_LANG_MOD_HELP_WEEKDAYS_NUMBER',					'The weekday value in numeric format (1 is Sunday, 7 is Saturday).');
