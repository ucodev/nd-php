<?php if (!defined('FROM_BASE')) { header('HTTP/1.1 403 Forbidden'); die('Invalid requested path.'); }

/* Cache settings */
$cache['driver'] = 'memcached';
$cache['host'] = '127.0.0.1';
$cache['port'] = '11211';
$cache['active'] = false;

