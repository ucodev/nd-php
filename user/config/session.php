<?php if (!defined('FROM_BASE')) { header($_SERVER['SERVER_PROTOCOL'] . ' 403'); die('Invalid requested path.'); }

/* Session settings */
$session['enable']		= false;
$session['name']		= 'ndphp_installation';
$session['encrypt']		= true;
$session['cookie_lifetime']	= 7200;
$session['cookie_path']		= '/ndphp_installation/';
$session['cookie_domain']	= 'localhost';
$session['cookie_secure']	= false;
$session['cookie_httponly']	= true;
$session['sssh_db_enabled'] = false;
$session['sssh_db_alias'] = 'default';
$session['sssh_db_table'] = 'sessions';
$session['sssh_db_field_session_id'] = 'session';
$session['sssh_db_field_session_data'] = 'data';
$session['sssh_db_field_session_valid'] = 'valid';
$session['sssh_db_field_session_start_time'] = 'start_time';
$session['sssh_db_field_session_change_time'] = 'change_time';
$session['sssh_db_field_session_end_time'] = 'end_time';