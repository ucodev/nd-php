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
		<title>ND PHP - JSON REST API Documentation</title>
		<link rel="stylesheet" type="text/css" href="<?=filter_html(static_css_url(), $config['charset'])?>/documentation/rest.css" />
	</head>
	<body>
		<?php include('navigation.php'); ?>
		<fieldset>
			<legend>SYNOPSIS</legend>
			<table>
				<tr><td><strong>Generic URL</strong></td><td><?=filter_html(base_url(), $config['charset'])?>index.php/documentation/rest/<i>controller</i></td></tr>
			</table>
		</fieldset>
		<fieldset>
			<legend>CONTROLLERS REST API</legend>
			<table>
				<?php foreach ($view['controllers'] as $controller): ?>
					<tr>
						<td><strong><?=ucfirst($controller)?></strong></td>
						<td><a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/rest/<?=filter_html($controller, $config['charset'])?>"><?=filter_html(base_url(), $config['charset'])?>index.php/documentation/rest/<?=filter_html($controller, $config['charset'])?></a></td>
					</tr>
				<?php endforeach; ?>
			</table>
		</fieldset>
	</body>
</html>