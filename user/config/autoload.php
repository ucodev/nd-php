<?php if (!defined('FROM_BASE')) { header($_SERVER['SERVER_PROTOCOL'] . ' 403'); die('Invalid requested path.'); }

/* Autoload settings */
$autoload['libraries'] = array('mpdf', 'phpmailer', 'pchart');
$autoload['extensions'] = array('image', 'pagination', 'timezone');
$autoload['models'] = array('ndphp', 'security', 'features', 'access', 'configuration', 'accounting');
$autoload['modules'] = array('request', 'response');
