<?php if (!defined('FROM_BASE')) { header('HTTP/1.1 403 Forbidden'); die('Invalid requested path.'); }

/* Autoload settings */
$autoload['libraries'] = array('mpdf', 'phpmailer', 'pchart');
$autoload['extensions'] = array('pagination', 'timezone', 'breadcrumb');
$autoload['models'] = array('ndphp', 'security', 'features', 'access', 'configuration', 'application', 'accounting', 'request', 'response');
