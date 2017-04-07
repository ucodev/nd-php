<?php if (!defined('FROM_BASE')) { header('HTTP/1.1 403 Forbidden'); die('Invalid requested path.'); }

/* Amazon S3 settings */
$aws['key'] = '';
$aws['secret'] = '';
$aws['version'] = 'latest'; /* '2012-10-17' */
$aws['region'] = 'ap-southeast-1';
$aws['default_bucket'] = 'default';
$aws['bucket_base_dir'] = 'files';
$aws['img_buckets'] = false;
$aws['img_thumbnail_bucket'] = 'thumbnail';
$aws['img_small_bucket'] = 'small';
$aws['img_medium_bucket'] = 'medium';
$aws['img_large_bucket'] = 'large';
