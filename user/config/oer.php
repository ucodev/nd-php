<?php if (!defined('FROM_BASE')) { header('HTTP/1.1 403 Forbidden'); die('Invalid requested path.'); }

/* Open Exchange Rates settings */
$oer['key'] = '';
$oer['base_url'] = 'https://openexchangerates.org/api';
$oer['version'] = 'latest.json';
