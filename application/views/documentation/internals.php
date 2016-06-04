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
		<title>ND PHP - Internals Documentation</title>
		<link rel="stylesheet" type="text/css" href="<?=filter_html(static_css_url(), $config['charset'])?>/documentation/internals.css" />
	</head>
	<body>
		<?php include('navigation.php'); ?>
		<fieldset>
			<legend>INDEX</legend>
			<table>
				<tr>
					<td><strong>Core</strong></td>
					<td>
						<a href="#core_construct">Construct</a>&nbsp;
						<a href="#construct">Internal API</a>&nbsp;
					</td>
				</tr>
				<tr>
					<td><strong>Controllers</strong></td>
					<td>
						<a href="#controller_construct">Construct</a>&nbsp;
						<a href="#controller_security">Security</a>&nbsp;
						<a href="#controller_custom_controller">Custom Controller</a>&nbsp;
						<a href="#controller_custom_function">Custom Function</a>&nbsp;
						<a href="#controller_integrate_custom_view">Integrate Custom View</a>&nbsp;
					</td>
				</tr>
				<tr>
					<td><strong>Views</strong></td>
					<td>
						<a href="#views_security">Security</a>&nbsp;
						<a href="#views_variables">Variables</a>&nbsp;
						<a href="#views_customization">Customization</a>&nbsp;
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