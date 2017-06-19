<?php if (!defined('FROM_BASE')) { header('HTTP/1.1 403 Forbidden'); die('Invalid requested path.'); }

/* Amazon S3 settings */
$aws['key'] = '';
$aws['secret'] = '';
$aws['version'] = 'latest'; /* '2012-10-17' */
$aws['region'] = 'ap-southeast-1';
$aws['default_bucket'] = 'default'; /* TODO: rename this key to bucket_default */
$aws['use_accelerate_endpoint'] = false;
$aws['bucket_base_dir'] = 'content';
$aws['bucket_img_resize'] = false;
$aws['bucket_img_resize_mode'] = 'sinc';
$aws['bucket_img_resize_quality'] = 86;
$aws['bucket_img_resize_subdir'] = 'resized'; /* Created under $aws['bucket_base_dir'] */
$aws['bucket_img_resize_xxsmall_dir'] = 'xxsmall';
$aws['bucket_img_resize_xxsmall_width'] = 60;
$aws['bucket_img_resize_xsmall_dir'] = 'xsmall';
$aws['bucket_img_resize_xsmall_width'] = 120;
$aws['bucket_img_resize_small_dir'] = 'small';
$aws['bucket_img_resize_small_width'] = 240;
$aws['bucket_img_resize_medium_dir'] = 'medium';
$aws['bucket_img_resize_medium_width'] = 480;
$aws['bucket_img_resize_large_dir'] = 'large';
$aws['bucket_img_resize_large_width'] = 960;
$aws['bucket_img_resize_xlarge_dir'] = 'xlarge';
$aws['bucket_img_resize_xlarge_width'] = 1920;
$aws['bucket_img_resize_xxlarge_dir'] = 'xxlarge';
$aws['bucket_img_resize_xxlarge_width'] = 3840;
