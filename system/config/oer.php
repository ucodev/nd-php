<?php if (!defined('FROM_BASE')) { header($_SERVER['SERVER_PROTOCOL'] . ' 403'); die('Invalid requested path.'); }

/* Open Exchange Rates settings */
$oer['key'] = '';
$oer['base_url'] = 'https://openexchangerates.org/api';
$oer['version'] = 'latest.json';
