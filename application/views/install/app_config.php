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

 ?>
<!DOCTYPE html>
<html>
	<head>
		<title><?=htmlentities(NDPHP_LANG_MOD_INSTALL_TITLE_NDPHP_NAME, ENT_QUOTES, $charset)?> - <?=htmlentities(NDPHP_LANG_MOD_INSTALL_TITLE_APP_CONFIG, ENT_QUOTES, $charset)?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta http-equiv="cache-control" content="no-cache" />
		<link rel="stylesheet" type="text/css" href="<?=htmlentities(static_css_url(), ENT_QUOTES, $charset)?>/install/install.css" />
		<link rel="stylesheet" type="text/css" href="<?=htmlentities(static_js_url(), ENT_QUOTES, $charset)?>/lib/jquery-ui/1.10.4/css/jquery-ui.css" />
		<script type="text/javascript" src="<?=htmlentities(static_js_url(), ENT_QUOTES, $charset)?>/lib/jquery/1.12.4/jquery.js"></script>
 		<script type="text/javascript" src="<?=htmlentities(static_js_url(), ENT_QUOTES, $charset)?>/lib/jquery-ui/1.10.4/jquery-ui.js"></script>
 		<script type="text/javascript" src="<?=htmlentities(static_js_url(), ENT_QUOTES, $charset)?>/lib/jquery/blockui/blockui.js"></script>
		<script type="text/javascript" src="<?=htmlentities(static_js_url(), ENT_QUOTES, $charset)?>/lib/base64/base64.js"></script>
		<script type="text/javascript" src="<?=htmlentities(static_js_url(), ENT_QUOTES, $charset)?>/lib/charset/utf8.js"></script>
		<script type="text/javascript" src="<?=htmlentities(static_js_url(), ENT_QUOTES, $charset)?>/lib/ndphp/install.js.php"></script>
	</head>
	<body>
		<fieldset>
			<legend><?=htmlentities(NDPHP_LANG_MOD_INSTALL_TITLE_NDPHP_NAME, ENT_QUOTES, $charset)?> - <?=htmlentities(NDPHP_LANG_MOD_INSTALL_TITLE_APP_CONFIG, ENT_QUOTES, $charset)?></legend>
			<br />
			<table>
				<tr><td><strong><?=htmlentities(NDPHP_LANG_MOD_INSTALL_FIELD_PROJECT_NAME, ENT_QUOTES, $charset)?></strong></td><td><input type="text" id="name" value="ND php" placeholder="ND php" /></td></tr>
				<tr><td><strong><?=htmlentities(NDPHP_LANG_MOD_INSTALL_FIELD_TAGLINE, ENT_QUOTES, $charset)?></strong></td><td><input type="text" id="tagline" value="Framework" placeholder="Framework" /></td></tr>
				<tr><td><strong><?=htmlentities(NDPHP_LANG_MOD_INSTALL_FIELD_DESCRIPTION, ENT_QUOTES, $charset)?></strong></td><td><input type="text" id="description" value="An handy PHP framework" placeholder="An handy PHP framework" /></td></tr>
				<tr><td><strong><?=htmlentities(NDPHP_LANG_MOD_INSTALL_FIELD_AUTHOR, ENT_QUOTES, $charset)?></strong></td><td><input type="text" id="author" value="ND PHP Framework" placeholder="Your Name" /></td></tr>
				<tr><td><strong><?=htmlentities(NDPHP_LANG_MOD_INSTALL_FIELD_LANGUAGE, ENT_QUOTES, $charset)?></strong></td><td><select id="language" disabled><option value="en">English</option></select></td></tr>
				<tr><td><strong><?=htmlentities(NDPHP_LANG_MOD_INSTALL_FIELD_TIMEZONE, ENT_QUOTES, $charset)?></strong></td><td><select id="timezone" disabled><option value="en">Etc/UTC</option></select></td></tr>
				<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
				<tr><td><strong><?=htmlentities(NDPHP_LANG_MOD_INSTALL_FIELD_NEXT_STEPS, ENT_QUOTES, $charset)?></strong></td><td><?=htmlentities(NDPHP_LANG_MOD_INSTALL_HELP_CUSTOMIZE, ENT_QUOTES, $charset)?> (<a href="#"><?=htmlentities(NDPHP_LANG_MOD_INSTALL_HELP_NEED_QUESTION, ENT_QUOTES, $charset)?></a>)</td></tr>
				<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
				<tr>
					<td>
						<strong><?=htmlentities(NDPHP_LANG_MOD_INSTALL_FIELD_ACTIONS, ENT_QUOTES, $charset)?></strong>
					</td>
					<td>
						<input type="button" id="back_btn" value="<?=htmlentities(NDPHP_LANG_MOD_INSTALL_OP_BACK, ENT_QUOTES, $charset)?>" onClick="location.href='<?=filter_html_js_str(base_url(), $charset)?>index.php/install/user_config'" />
						<input type="button" id="continue_btn" value="<?=htmlentities(NDPHP_LANG_MOD_INSTALL_OP_CONTINUE, ENT_QUOTES, $charset)?>" onClick="jQuery('body').css('cursor', 'wait'); jQuery('#continue_btn').val('Configuring...'); jQuery('#continue_btn').prop('disabled', true); jQuery('#back_btn').prop('disabled', true); location.href='<?=filter_html_js_str(base_url(), $charset)?>index.php/install/app_setup/' + encodeURIComponent(ndphp.utils.safe_b64encode(utf8_encode(jQuery('#name').val()))) + '/' + encodeURIComponent(ndphp.utils.safe_b64encode(utf8_encode(jQuery('#tagline').val()))) + '/' + encodeURIComponent(ndphp.utils.safe_b64encode(utf8_encode(jQuery('#description').val()))) + '/' + encodeURIComponent(ndphp.utils.safe_b64encode(utf8_encode(jQuery('#author').val())))" />
					</td>
				</tr>
			</table>
			<br />
		</fieldset>
	</body>
</html>