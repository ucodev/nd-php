<?php if (!defined('FROM_BASE')) { header('HTTP/1.1 403 Forbidden'); die('Invalid requested path.'); }

/* Base settings */
$base['type'] = 'master'; /* Set to 'slave' if this is a unauthorative node */
$base['sapi_long_request'] = array('apache2handler'); /* The accepted values for PHP_SAPI for requests that take a long time to process */
$base['controller'] = 'install'; /* Default controller */
$base['acceptable_uri_regex'] = '/^[a-zA-Z0-9\ \~\%\.\:\_\\-\+\=\/\@]+$/'; /* Acceptable URI charaters */
$base['default_upload_file_driver'] = 'local';
$base['default_upload_file_base_url'] = '';
