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
		<title><?=htmlentities(NDPHP_LANG_MOD_INSTALL_TITLE_NDPHP_NAME, ENT_QUOTES, $charset)?> - <?=htmlentities(NDPHP_LANG_MOD_INSTALL_TITLE_DB_CONFIG, ENT_QUOTES, $charset)?></title>
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
			<legend><?=htmlentities(NDPHP_LANG_MOD_INSTALL_TITLE_NDPHP_NAME, ENT_QUOTES, $charset)?> - <?=htmlentities(NDPHP_LANG_MOD_INSTALL_TITLE_DB_CONFIG, ENT_QUOTES, $charset)?></legend>
			<br />
			<table>
				<tr><td><strong><?=htmlentities(NDPHP_LANG_MOD_INSTALL_FIELD_DB_DRIVER, ENT_QUOTES, $charset)?></strong></td><td><input type="text" id="dbdriver" value="MySQL / MariaDB" disabled /></td></tr>
				<tr><td><strong><?=htmlentities(NDPHP_LANG_MOD_INSTALL_FIELD_DB_HOST, ENT_QUOTES, $charset)?></strong></td><td><input type="text" id="dbhost" value="localhost" placeholder="localhost" /></td></tr>
				<tr><td><strong><?=htmlentities(NDPHP_LANG_MOD_INSTALL_FIELD_DB_PORT, ENT_QUOTES, $charset)?></strong></td><td><input type="text" id="dbport" value="3306" placeholder="3306" /></td></tr>
				<tr><td><strong><?=htmlentities(NDPHP_LANG_MOD_INSTALL_FIELD_DB_NAME, ENT_QUOTES, $charset)?></strong></td><td><input type="text" id="dbname" value="ndphp" placeholder="ndphp" /></td></tr>
				<tr><td><strong><?=htmlentities(NDPHP_LANG_MOD_INSTALL_FIELD_DB_USERNAME, ENT_QUOTES, $charset)?></strong></td><td><input type="text" id="dbuser" value="ndphp_user" placeholder="ndphp_user" /></td></tr>
				<tr><td><strong><?=htmlentities(NDPHP_LANG_MOD_INSTALL_FIELD_DB_PASSWORD, ENT_QUOTES, $charset)?></strong></td><td><input type="password" id="dbpass" value="password" /></td></tr>
				<tr><td><strong><?=htmlentities(NDPHP_LANG_MOD_INSTALL_FIELD_DB_CHARSET, ENT_QUOTES, $charset)?></strong></td><td><input type="text" id="dbchar" value="UTF-8" disabled /></td></tr>
				<tr><td><strong><?=htmlentities(NDPHP_LANG_MOD_INSTALL_FIELD_DB_CONNECTION, ENT_QUOTES, $charset)?></strong></td><td><span id="dbconn" style="display: none"></span><input id="dbconn_test_btn" type="button" value="<?=htmlentities(NDPHP_LANG_MOD_INSTALL_OP_TEST_DB_CONN, ENT_QUOTES, $charset)?>" onClick="ndphp.install.db_test();" /></td></tr>
				<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
				<tr><td><strong><?=htmlentities(NDPHP_LANG_MOD_INSTALL_FIELD_NEXT_STEPS, ENT_QUOTES, $charset)?></strong></td><td><?=htmlentities(NDPHP_LANG_MOD_INSTALL_HELP_TEST_DB_CONN, ENT_QUOTES, $charset)?> (<a href="#"><?=htmlentities(NDPHP_LANG_MOD_INSTALL_HELP_NEED_QUESTION, ENT_QUOTES, $charset)?></a>)</td></tr>
				<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
				<tr>
					<td>
						<strong><?=htmlentities(NDPHP_LANG_MOD_INSTALL_FIELD_ACTIONS, ENT_QUOTES, $charset)?></strong>
					</td>
					<td>
						<input type="button" id="back_btn" value="<?=htmlentities(NDPHP_LANG_MOD_INSTALL_OP_BACK, ENT_QUOTES, $charset)?>" onClick="location.href='<?=filter_html_js_str(base_url(), $charset)?>index.php/install/pre_check'" />
						<input type="button" id="continue_btn" value="<?=htmlentities(NDPHP_LANG_MOD_INSTALL_OP_CONTINUE, ENT_QUOTES, $charset)?>" onClick="jQuery('body').css('cursor', 'wait'); jQuery('#continue_btn').val('Installing...'); jQuery('#continue_btn').prop('disabled', true); jQuery('#back_btn').prop('disabled', true); location.href='<?=filter_html_js_str(base_url(), $charset)?>index.php/install/db_setup';" disabled />
					</td>
				</tr>
			</table>
			<br />
		</fieldset>
	</body>
</html>