<?php if (!defined('FROM_BASE')) { header('HTTP/1.1 403 Forbidden'); die('Invalid requested path.'); }

/* Amazon S3 settings */
$aws['key'] = '';
$aws['secret'] = '';
$aws['version'] = 'latest';
$aws['region'] = 'ap-southeast-1';
$aws['default_bucket'] = 'default';
$aws['bucket_base_dir'] = 'files';
