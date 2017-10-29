<?php if (!defined('FROM_BASE')) { header($_SERVER['SERVER_PROTOCOL'] . ' 403'); die('Invalid requested path.'); }

/* MySQL */
$database['default']['driver']   = 'mysql';
$database['default']['host']     = '127.0.0.1';
$database['default']['port']     = '3306';
$database['default']['name']     = 'uweb';
$database['default']['username'] = 'username';
$database['default']['password'] = 'password';
$database['default']['charset']  = 'utf8';
$database['default']['persistent'] = true;
$database['default']['strict']   = true;

/* PgSQL
$database['another_database']['driver']   = 'pgsql';
$database['another_database']['host']     = '127.0.0.1';
$database['another_database']['port']     = '3306';
$database['another_database']['name']     = 'another_name';
$database['another_database']['username'] = 'another_username';
$database['another_database']['password'] = 'another_password';
$database['another_database']['charset']  = 'utf8';
$database['another_database']['strict']   = true;
*/
