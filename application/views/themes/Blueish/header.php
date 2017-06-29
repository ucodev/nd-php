<?php
/*
 * This file is part of ND PHP Framework.
 *
 * ND PHP Framework - An handy PHP Framework (www.nd-php.org)
 * Copyright (C) 2015-2016  Pedro A. Hortas (pah@ucodev.org)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

/*
 * ND PHP Framework (www.nd-php.org) - Contributor Agreement
 *
 * When contributing material to this project, please read, accept, sign
 * and send us the Contributor Agreement located in the file NDCA.pdf
 * inside the documentation/ directory.
 *
 */

/*
 *
 *
 *  +----------------------+
 *  | Variable information |
 *  +----------------------+
 *
 *
 *  +----------------+---------------+-------------------------------------------------------+
 *  | Variable Name  | Type          | Description                                           |
 *  +----------------+---------------+-------------------------------------------------------+
 *  | $config        | array() assoc | Configuration data: Charset, theme, features, ...     |
 *  | $view          | array() assoc | View data: Field meta data, values, ...               |
 *  | $project       | array() assoc | Project information: Name, Tagline, Description, ...  |
 *  | $session       | array() assoc | Session data: Contains all session K/V pairs, ...     |
 *  | $security      | array() assoc | Security information: Role access, user info, ...     |
 *  +----------------+---------------+-------------------------------------------------------+
 *
 *  - Use the browser's 'D' access key in any ND PHP Framework page to access extended documentation.
 *  
 */

 ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="<?=filter_html($config['charset'], $config['charset'])?>" />
	<title><?=filter_html(strip_tags($view['title']), $config['charset'])?></title>
	<meta name="author" content="<?=filter_html($project['author'], $config['charset'])?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="description" content="<?=filter_html($project['description'], $config['charset'])?>" />

	<link rel="stylesheet" type="text/css" href="<?=filter_html(static_js_url(), $config['charset'])?>/lib/jquery-ui/1.10.4/css/jquery-ui.css" />
	<link rel="stylesheet" type="text/css" href="<?=filter_html(static_js_url(), $config['charset'])?>/lib/jquery-ui-timepicker/jquery.ui.timepicker.css" />
	<link rel="stylesheet" type="text/css" href="<?=filter_html(static_js_url(), $config['charset'])?>/lib/uwat/css/accessibility.css" />
	<link rel="stylesheet" type="text/css" href="<?=filter_html(static_css_url(), $config['charset'])?>/themes/<?=filter_html($config['theme']['name'], $config['charset'])?>/bootstrap.css" />
	<link rel="stylesheet" type="text/css" href="<?=filter_html(static_css_url(), $config['charset'])?>/themes/<?=filter_html($config['theme']['name'], $config['charset'])?>/bootstrap-datetimepicker.min.css" />
	<link rel="stylesheet" type="text/css" href="<?=filter_html(static_css_url(), $config['charset'])?>/themes/<?=filter_html($config['theme']['name'], $config['charset'])?>/main.css.php" />

	<script type="text/javascript" src="<?=filter_html(static_js_url(), $config['charset'])?>/lib/jquery/1.12.4/jquery.js"></script>
	<script type="text/javascript" src="<?=filter_html(static_js_url(), $config['charset'])?>/themes/<?=filter_html($config['theme']['name'], $config['charset'])?>/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?=filter_html(static_js_url(), $config['charset'])?>/themes/<?=filter_html($config['theme']['name'], $config['charset'])?>/bootstrap-filestyle.min.js"></script>
	<script type="text/javascript" src="<?=filter_html(static_js_url(), $config['charset'])?>/themes/<?=filter_html($config['theme']['name'], $config['charset'])?>/bootstrap-datetimepicker.min.js"></script>
	<script type="text/javascript" src="<?=filter_html(static_js_url(), $config['charset'])?>/lib/jquery-ui/1.10.4/jquery-ui.js"></script>
	<script type="text/javascript">jQuery.noConflict();</script>

	<script type="text/javascript" src="<?=filter_html(static_js_url(), $config['charset'])?>/lib/jquery/validate/jquery.validate.js"></script>
	<script type="text/javascript" src="<?=filter_html(static_js_url(), $config['charset'])?>/lib/jquery/validate/pattern.js"></script>
	<script type="text/javascript" src="<?=filter_html(static_js_url(), $config['charset'])?>/lib/jquery/blockui/blockui.js"></script>
	<script type="text/javascript" src="<?=filter_html(static_js_url(), $config['charset'])?>/lib/jquery-timer/dist/timer.jquery.js"></script>

	<script type="text/javascript" src="<?=filter_html(static_js_url(), $config['charset'])?>/lib/tinymce/3.5.8/jscripts/tiny_mce/tiny_mce.js"></script>
	<script type="text/javascript" src="<?=filter_html(static_js_url(), $config['charset'])?>/lib/pchart/imagemap.js"></script>
	<script type="text/javascript" src="<?=filter_html(static_js_url(), $config['charset'])?>/lib/base64/base64.js"></script>
	<script type="text/javascript" src="<?=filter_html(static_js_url(), $config['charset'])?>/lib/charset/utf8.js"></script>
	<script type="text/javascript" src="<?=filter_html(static_js_url(), $config['charset'])?>/lib/validate/codes.js"></script>

	<script type="text/javascript" src="<?=filter_html(static_js_url(), $config['charset'])?>/lib/ndphp/ndphp.js.php"></script>


	<script type="text/javascript">
		/* Set current context */
		ndphp.current.controller = '<?=filter_js_str($view['ctrl'], $config['charset'])?>';
		ndphp.current.charset = '<?=filter_js_str($config['charset'], $config['charset'])?>';

		/* Set theme */
		ndphp.theme.set("<?=filter_js_str($config['theme']['name'], $config['charset'])?>");

		/* Set animation types */
		ndphp.animation.set_default_delay(<?=filter_js_special($config['theme']['animation_default_delay'], $config['charset'])?>);
		ndphp.animation.set_default_type("<?=filter_js_str($config['theme']['animation_default_type'], $config['charset'])?>");

		ndphp.animation.set_ordering_delay(<?=filter_js_special($config['theme']['animation_ordering_delay'], $config['charset'])?>);
		ndphp.animation.set_ordering_type("<?=filter_js_str($config['theme']['animation_ordering_type'], $config['charset'])?>");

		jQuery.extend(jQuery.validator.messages, {
    		required: "*"
   		});

   		jQuery(function() {
   			jQuery(document).tooltip();
   		});
	</script>
</head>
<body class="default">
<a id="doc_general" target="_blank" href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation" style="display: none;" accesskey="<?=filter_html(NDPHP_LANG_MOD_OP_ACCESS_KEY_DOCUMENTATION, $config['charset'])?>">Documentation</a>
<?php if ($config['features']['accessibility'] === true): ?>
<?php 	include('_default/lib/accessibility.php'); ?>
<?php endif; ?>
<div id="container"> <!-- Begin of container -->
	<div id="header">
		<!-- Logo -->
		<a href="<?=filter_html(base_url(), $config['charset'])?>index.php/" onclick="ndphp.ajax.load_body_home(event);" title="<?=filter_html(NDPHP_LANG_MOD_LINK_HOME, $config['charset'])?>">
			<img class="logo" src="<?=filter_html(static_images_url(), $config['charset'])?>/logo.png" alt="<?=filter_html($project['name'], $config['charset'])?> Logo" />
		</a>
		<span class="project_name"><?=filter_html($project['name'], $config['charset'])?></span>
		<span class="project_tagline"><?=filter_html($project['tagline'], $config['charset'])?></span>
		<!-- Session info -->
		<div id="session_info">
			<table class="session_info">
				<tr class="session_info">
					<td class="session_info">
						<div id="browsing_actions">
							<div id="browsing_actions_body">
								<div id="ba_back" class="browsing_actions_entry">
									<img alt="<?=filter_html(NDPHP_LANG_MOD_ACTION_BACK, $config['charset'])?> (<?=filter_html(NDPHP_LANG_MOD_STATUS_DISABLED, $config['charset'])?>)" class="browsing_actions_icon" src="<?=filter_html(static_images_url(), $config['charset'])?>/themes/<?=filter_html($config['theme']['name'], $config['charset'])?>/icons/back_disabled.png" />
								</div>
								<div class="browsing_actions_entry">
									<a title="<?=filter_html(NDPHP_LANG_MOD_ACTION_REFRESH, $config['charset'])?>" class="browsing_actions_entry" href="javascript:ndphp.nav.refresh_do()">
										<img alt="<?=filter_html(NDPHP_LANG_MOD_ACTION_REFRESH, $config['charset'])?>" class="browsing_actions_icon" src="<?=filter_html(static_images_url(), $config['charset'])?>/themes/<?=filter_html($config['theme']['name'], $config['charset'])?>/icons/refresh.png" />
									</a>
								</div>
								<div id="ba_forward" class="browsing_actions_entry">
									<img alt="<?=filter_html(NDPHP_LANG_MOD_ACTION_FORWARD, $config['charset'])?> (<?=filter_html(NDPHP_LANG_MOD_STATUS_DISABLED, $config['charset'])?>)" class="browsing_actions_icon" src="<?=filter_html(static_images_url(), $config['charset'])?>/themes/<?=filter_html($config['theme']['name'], $config['charset'])?>/icons/forward_disabled.png" />
								</div>
							</div>
						</div>
					<?php if ($config['features']['user_credit_control'] === true): ?>
						<td class="session_info">
							<strong><span id="user_credit">0</span> <?=filter_html(NDPHP_LANG_MOD_DEFAULT_CURRENCY, $config['charset'])?></strong> [<a href="javascript:void(0);" onclick="ndphp.ajax.load_add_funds(event, '<?=filter_html_js_str(base_url(), $config['charset'])?>index.php/paypal/payment_form_ajax');"><?=filter_html(NDPHP_LANG_MOD_LINK_ADD_FUNDS, $config['charset'])?></a>]&nbsp;&nbsp;
							<script type="text/javascript">
								ndphp.ajax.refresh_user_credit();

								setInterval(ndphp.ajax.refresh_user_credit, 30000);
							</script>
						</td>
					<?php endif; ?>
					<?php if ($config['features']['multi_user'] === true): ?>
						<td class="session_info">
							<span class="session_info_user_name">
								<?=filter_html($session['first_name'], $config['charset'])?>
							</span>
						</td>
						<td class="session_info">
							<span class="session_info_user">
								<a href="javascript:void(0);" title="<?=filter_html($session['username'], $config['charset'])?>" onclick="ndphp.ajax.load_user_settings(event, '<?=filter_html_js_str(base_url(), $config['charset'])?>index.php/users/view_body_ajax/<?=filter_html_js_str($session['user_id'], $config['charset'])?>');">
									<?php if ($session['photo']): ?>
										<img class="session_info_user_photo" alt="<?=filter_html($session['username'], $config['charset'])?>" src="<?=filter_html($session['photo'], $config['charset'])?>" />
									<?php else: ?>
										<img class="session_info_user_photo" alt="<?=filter_html($session['username'], $config['charset'])?>" src="<?=filter_html(static_images_url(), $config['charset'])?>/themes/<?=filter_html($config['theme']['name'], $config['charset'])?>/icons/user.png" />
									<?php endif; ?>
								</a>
							</span>
						</td>
					<?php endif; ?>
				</tr>
			</table>
		</div>
	</div>
	
	<!-- Main Menu / Navigation Bar -->
	<nav class="navbar navbar-default">
		<div class="container-fluid">
		    <div class="navbar-header">
		    	<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar">
		    		<span class="sr-only"><?=filter_html(NDPHP_LANG_MOD_COMMON_TOGGLE_NAVIGATION, $config['charset'])?></span>
		    		<span class="icon-bar"></span>
		    		<span class="icon-bar"></span>
		    		<span class="icon-bar"></span>
		    	</button>
		    	<a class="navbar-brand" title="<?=filter_html(NDPHP_LANG_MOD_LINK_HOME, $config['charset'])?>" href="<?=filter_html(base_url(), $config['charset'])?>index.php/" onclick="ndphp.ajax.load_body_home(event);" accesskey="<?=filter_html(NDPHP_LANG_MOD_OP_ACCESS_KEY_LOAD_HOME, $config['charset'])?>">
		    		<?=filter_html(NDPHP_LANG_MOD_LINK_HOME, $config['charset'])?>
		    	</a>
		    </div>

		    <div id="navbar" class="navbar-collapse collapse">
		    	<ul class="nav navbar-nav">
					<?php $access_key = 1; foreach ($view['mainmenu'] as $entry): ?>
						<li id="menu_entry_<?=$entry[0]?>" class="<?=$view['ctrl'] == $entry[0] ? 'active' : ''?>">
							<a title="<?=$entry[2] ? filter_html($entry[2], $config['charset']) : filter_html(ucfirst($entry[1]), $config['charset'])?>" href="<?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($entry[0], $config['charset'])?>" onclick="ndphp.ajax.load_body_menu(event, '<?=filter_html_js_str($entry[0], $config['charset'])?>', '<?=filter_html_js_str($entry[1], $config['charset'])?>');" accesskey="<?=$access_key?>">
								<?=filter_html(ucfirst($entry[1]), $config['charset'])?>
							</a>
						</li>
					<?php $access_key ++; endforeach; ?>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<?php if ($security['im_admin']): ?>
						<li id="menu_entry_users" class="<?=$view['ctrl'] == 'users' ? 'active' : ''?>">
							<a title="<?=filter_html(NDPHP_LANG_MOD_MENU_USERS_DESC, $config['charset'])?>" href="<?=filter_html(base_url(), $config['charset'])?>index.php/users" onclick="ndphp.ajax.load_body_menu(event, 'users', 'users');">
								<?=filter_html(NDPHP_LANG_MOD_MENU_USERS_NAME, $config['charset'])?>
							</a>
						</li>
						<li id="menu_entry_sessions" class="<?=$view['ctrl'] == 'sessions' ? 'active' : ''?>">
							<a title="<?=filter_html(NDPHP_LANG_MOD_MENU_SESSIONS_DESC, $config['charset'])?>" href="<?=filter_html(base_url(), $config['charset'])?>index.php/sessions" onclick="ndphp.ajax.load_body_menu(event, 'sessions', 'sessions');">
								<?=filter_html(NDPHP_LANG_MOD_MENU_SESSIONS_NAME, $config['charset'])?>
							</a>
						</li>
						<li id="menu_entry_logging" class="<?=$view['ctrl'] == 'logging' ? 'active' : ''?>">
							<a title="<?=filter_html(NDPHP_LANG_MOD_MENU_LOGGING_DESC, $config['charset'])?>" href="<?=filter_html(base_url(), $config['charset'])?>index.php/logging" onclick="ndphp.ajax.load_body_menu(event, 'logging', 'logging');">
								<?=filter_html(NDPHP_LANG_MOD_MENU_LOGGING_NAME, $config['charset'])?>
							</a>
						</li>
						<li id="menu_entry_system" class="dropdown">
							<a href="#" class="dropdown-toggle <?=$view['ctrl'] == 'configuration' ? 'active' : ''?>" data-toggle="dropdown" role="button" aria-expanded="false">
								<?=filter_html(NDPHP_LANG_MOD_MENU_SYSTEM_NAME, $config['charset'])?> <span class="caret"></span>
							</a>
							<ul class="dropdown-menu" role="menu">
								<li id="menu_entry_configuration">
									<a href="<?=filter_html(base_url(), $config['charset'])?>index.php/configuration" onclick="ndphp.ajax.load_body_menu(event, 'configuration', 'configuration');">
										<?=filter_html(NDPHP_LANG_MOD_MENU_CONFIGURATION_NAME, $config['charset'])?>
									</a>
								</li>
								<li id="menu_entry_charts_config">
									<a href="<?=filter_html(base_url(), $config['charset'])?>index.php/charts_config" onclick="ndphp.ajax.load_body_menu(event, 'charts_config', 'charts_config');">
										<?=filter_html(NDPHP_LANG_MOD_OP_CHARTS, $config['charset'])?>
									</a>
								</li>
								<li id="menu_entry_scheduler">
									<a href="<?=filter_html(base_url(), $config['charset'])?>index.php/scheduler" onclick="ndphp.ajax.load_body_menu(event, 'scheduler', 'scheduler');">
										<?=filter_html(NDPHP_LANG_MOD_MENU_SCHEDULER_NAME, $config['charset'])?>
									</a>
								</li>
								<li class="divider"></li>
								<li>
									<a href="<?=filter_html(base_url(), $config['charset'])?>index.php/configuration/backup">
										<?=filter_html(NDPHP_LANG_MOD_OP_BACKUP, $config['charset'])?>
									</a>
								</li>
								<li>
									<a href="<?=filter_html(base_url(), $config['charset'])?>index.php/configuration/cache_clear">
										<?=filter_html(NDPHP_LANG_MOD_OP_CACHE_CLEAR, $config['charset'])?>
									</a>
								</li>
								<li class="divider"></li>
								<li>
									<a href="<?=filter_html(base_url(), $config['charset'])?>index.php/builder/ide" target="_blank">
										<?=filter_html(NDPHP_LANG_MOD_MENU_IDE_NAME, $config['charset'])?>
									</a>
								</li>
								<li>
									<a href="<?=filter_html(base_url(), $config['charset'])?>index.php/update/system_update">
										<?=filter_html(NDPHP_LANG_MOD_OP_UPDATE, $config['charset'])?>
									</a>
								</li>
							</ul>
						</li>
					<?php endif; ?>
					<li>
						<a title="<?=filter_html(NDPHP_LANG_MOD_MENU_SUPPORT_DESC, $config['charset'])?>" href="mailto:<?=filter_html($project['support_email'], $config['charset'])?>">
							<?=filter_html(NDPHP_LANG_MOD_MENU_SUPPORT_NAME, $config['charset'])?>
						</a>
					</li>
				</ul>
			</div>
		</div>
	</nav>

	<!-- Start of BODY -->
	<div id="body" class="panel panel-default">
