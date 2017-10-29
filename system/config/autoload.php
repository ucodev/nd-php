<?php if (!defined('FROM_BASE')) { header($_SERVER['SERVER_PROTOCOL'] . ' 403'); die('Invalid requested path.'); }

/* Autoload settings */
$autoload['libraries'] = array();
$autoload['extensions'] = array('pagination', 'timezone');
$autoload['models'] = array();
$autoload['modules'] = array();
