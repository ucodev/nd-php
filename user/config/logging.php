<?php if (!defined('FROM_BASE')) { header('HTTP/1.1 403 Forbidden'); die('Invalid requested path.'); }

/* Logging settings */
$logging['enabled'] = true;

/* Database based logging */
$logging['driver'] = 'database';
$logging['database']['table'] = 'logging';

/* Syslog based logging */
/*
$logging['driver'] = 'syslog';

$logging['syslog']['ident'] = 'nd-php';
$logging['syslog']['option'] = LOG_ODELAY | LOG_PID;
$logging['syslog']['facility'] = LOG_LOCAL0;
$logging['syslog']['field_delim'] = ';;; ';
$logging['syslog']['field_map'] = array(
        'operation' => 'operation',
        '_table' => 'object',
        '_field' => 'property',
        'entryid' => 'entryid',
        'value_new' => 'value_new',
        'value_old' => 'value_old',
        'transaction' => 'transaction',
        'registered' => 'registered',
        'sessions_id' => 'sessions_id',
        'users_id' => 'users_id'
    );
*/
