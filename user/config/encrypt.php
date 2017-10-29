<?php if (!defined('FROM_BASE')) { header($_SERVER['SERVER_PROTOCOL'] . ' 403'); die('Invalid requested path.'); }

/* Encryption settings */
$encrypt['cipher']	= MCRYPT_RIJNDAEL_256;
$encrypt['mode']	= MCRYPT_MODE_CBC;
$encrypt['key']		= 'StrongPassword';

