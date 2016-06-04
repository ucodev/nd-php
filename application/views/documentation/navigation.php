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
<fieldset>
	<legend>NAVIGATION</legend>
	<table>
		<tr><td><strong>Revision</strong></td><td><?=filter_html($view['doc_revision'], $config['charset'])?></td></tr>
		<tr><td><strong>Author</strong></td><td><?=filter_html($project['author'], $config['charset'])?></td></tr>
		<tr>
			<td>
				<strong>Links</strong>
			</td>
			<td>
				<a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/">Intro</a>&nbsp;
				<a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/api">API</a>&nbsp;
				<a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/hooks">Hooks</a>&nbsp;
				<a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/overloads">Overloads</a>&nbsp;
				<a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/internals">Internals</a>&nbsp;
				<a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/rest">REST</a>&nbsp;
				<a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/ide">IDE</a>&nbsp;
			</td>
		</tr>
	</table>
</fieldset>
<br />
