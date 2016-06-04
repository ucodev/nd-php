<?php if (!defined('FROM_BASE')) { header('HTTP/1.1 403 Forbidden'); die('Invalid requested path.'); }

/* Encryption settings */
$encrypt['cipher']	= MCRYPT_RIJNDAEL_256;
$encrypt['mode']	= MCRYPT_MODE_CBC;
$encrypt['key']		= 'StrongPassword';

