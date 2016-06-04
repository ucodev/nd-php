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
		<title>ND PHP - Documentation Introduction</title>
		<link rel="stylesheet" type="text/css" href="<?=filter_html(static_css_url(), $config['charset'])?>/documentation/intro.css" />
	</head>
	<body>
		<?php include('navigation.php'); ?>
		<fieldset>
			<legend>INDEX</legend>
			<table>
				<tr>
					<td><strong>Scope</strong></td>
					<td>
						<a href="#scope_api">API</a>&nbsp;
						<a href="#scope_hooks">Hooks</a>&nbsp;
						<a href="#scope_overloads">Overloads</a>&nbsp;
						<a href="#scope_internals">Internals</a>&nbsp;
						<a href="#scope_rest">REST</a>&nbsp;
						<a href="#scope_ide">IDE</a>&nbsp;
					</td>
				</tr>
				<tr>
					<td><strong>Conventions</strong></td>
					<td>
						<a href="#conventions_italic_dotted_underline">Italic Dotted Underline</a>&nbsp;
						<a href="#conventions_orange_box">Orange Box</a>&nbsp;
					</td>
				</tr>
				<tr>
					<td><strong>Other Resources</strong></td>
					<td>
						<a href="#resources_the_irc_channel">The Forum</a>&nbsp;
						<a href="#resources_the_irc_channel">The IRC Channel</a>&nbsp;
						<a href="#resources_the_code">The Code</a>&nbsp;
					</td>
				</tr>
			</table>
		</fieldset>
		<br />
		<fieldset>
			<legend>UNIMPLEMENTED</legend>
			<span style="padding-left: 20px;" class="console"><strong>Not available yet. Sorry.</strong> :(</span>
		</fieldset>
	</body>
</html>