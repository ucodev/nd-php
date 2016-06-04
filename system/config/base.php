<?php if (!defined('FROM_BASE')) { header('HTTP/1.1 403 Forbidden'); die('Invalid requested path.'); }

/* Base settings */
$base['controller'] = 'test'; /* Default controller */
$base['acceptable_uri_regex'] = '/^[a-zA-Z0-9\ \~\%\.\:\_\\-\+\=\/]+$/'; /* Acceptable URI charaters */
