<?php if (!defined('FROM_BASE')) { header($_SERVER['SERVER_PROTOCOL'] . ' 403'); die('Invalid requested path.'); }

/* Cache settings */
$cache['driver'] = 'memcached';
$cache['host'] = '127.0.0.1';
$cache['port'] = '11211';
$cache['active'] = false;
