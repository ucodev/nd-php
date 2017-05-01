<?php if (!defined('FROM_BASE')) { header('HTTP/1.1 403 Forbidden'); die('Invalid requested path.'); }

/* Logging settings */
$logging['enabled'] = true;

/* Database based logging */
$logging['driver']['name'] = 'database';
$logging['driver']['database']['table'] = 'logging';

/* Syslog based logging */
/*
$logging['driver']['name'] = 'syslog';

$logging['driver']['syslog']['ident'] = 'nd-php';
$logging['driver']['syslog']['option'] = LOG_ODELAY | LOG_PID;
$logging['driver']['syslog']['facility'] = LOG_LOCAL0;
$logging['driver']['syslog']['include_ip_address'] = true;
$logging['driver']['syslog']['include_user_agent'] = true;
$logging['driver']['syslog']['field_delim'] = ';;; ';
$logging['driver']['syslog']['field_map'] = array(
        'operation' => 'operation',
        '_table' => 'object',
        '_field' => 'property',
        'entryid' => 'entryid',
        'value_new' => 'value_new',
        'value_old' => 'value_old',
        'transaction' => 'transaction',
        'registered' => 'registered',
        'sessions_id' => 'sessions_id',
        'users_id' => 'users_id',
        'ip_address' => 'ip_address',
        'user_agent' => 'user_agent'
    );
*/
