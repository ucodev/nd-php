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
		<title><?=htmlentities(NDPHP_LANG_MOD_INSTALL_TITLE_NDPHP_NAME, ENT_QUOTES, $charset)?> - <?=htmlentities(NDPHP_LANG_MOD_INSTALL_TITLE_INSTALL_OK, ENT_QUOTES, $charset)?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta http-equiv="cache-control" content="no-cache" />
		<link rel="stylesheet" type="text/css" href="<?=htmlentities(static_css_url(), ENT_QUOTES, $charset)?>/install/install.css" />
		<script type="text/javascript" src="<?=htmlentities(static_js_url(), ENT_QUOTES, $charset)?>/lib/jquery/1.12.4/jquery.js"></script>
	</head>
	<body>
		<fieldset>
			<legend><?=htmlentities(NDPHP_LANG_MOD_INSTALL_TITLE_NDPHP_NAME, ENT_QUOTES, $charset)?> - <?=htmlentities(NDPHP_LANG_MOD_INSTALL_TITLE_INSTALL_OK, ENT_QUOTES, $charset)?></legend>
			<br />
			<table>
				<tr><td><strong><?=htmlentities(NDPHP_LANG_MOD_INSTALL_FIELD_STATUS, ENT_QUOTES, $charset)?></strong></td><td><?=htmlentities(NDPHP_LANG_MOD_INSTALL_INFO_SUCCESSFUL, ENT_QUOTES, $charset)?></td></tr>
				<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
				<tr><td><strong><?=htmlentities(NDPHP_LANG_MOD_INSTALL_FIELD_NEXT_STEPS, ENT_QUOTES, $charset)?></strong></td><td><?=htmlentities(NDPHP_LANG_MOD_INSTALL_HELP_LOGIN_AS, ENT_QUOTES, $charset)?> <b>admin</b>. (<a href="#"><?=htmlentities(NDPHP_LANG_MOD_INSTALL_HELP_NEED_QUESTION, ENT_QUOTES, $charset)?></a>)</td></tr>
				<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
				<tr>
					<td>
						<strong><?=htmlentities(NDPHP_LANG_MOD_INSTALL_FIELD_ACTIONS, ENT_QUOTES, $charset)?></strong>
					</td>
					<td>
						<input type="button" id="continue_btn" value="<?=htmlentities(NDPHP_LANG_MOD_INSTALL_OP_CONTINUE, ENT_QUOTES, $charset)?>" onClick="jQuery('body').css('cursor', 'wait'); location.href='<?=filter_html_js_str(base_url(), $charset)?>'" />
					</td>
				</tr>
			</table>
			<br />
		</fieldset>
	</body>
</html>
