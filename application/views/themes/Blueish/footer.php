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
 		<div style="clear: both;"></div>
    </div> <!-- End of div #body -->
</div> <!-- End of div #container -->
<div id="footer">
	Copyright &copy; <?=date('Y')?> <?=filter_html($project['author'], $config['charset'])?> (<?=filter_html(ucfirst(NDPHP_LANG_MOD_WORD_BUILD), $config['charset'])?>: <?=filter_html($project['build']['number'] . '.' . str_replace('-', '', explode(' ', $project['build']['date'])[0]), $config['charset'])?>)
</div>
<!-- TODO: Under development...
<div id="sitemap">
	<div id="sitemap_block_left">
		<h3 style="text-align: center;">Documentation</h3>
		<ul style="margin: 0 auto; display: table;">
			<li><a href="#">Licensing</a></li>
			<li><a href="#">Configuration</a></li>
			<li><a href="#">Development</a></li>
			<li><a href="#">Logging</a></li>
			<li><a href="#">Accounting</a></li>
			<li><a href="#">User Management</a></li>
		</ul>
	</div>
	<div id="sitemap_block_center_left">
		<h3 style="text-align: center;">Internal Controllers</h3>
		<ul style="margin: 0 auto; display: table;">
			<li><a href="#">Countries</a></li>
			<li><a href="#">Features</a></li>
			<li><a href="#">Roles</a></li>
			<li><a href="#">Themes</a></li>
			<li><a href="#">Timezones</a></li>
		</ul>
	</div>
	<div id="sitemap_block_center_right">
		<h3 style="text-align: center;">Utilities</h3>
		<ul style="margin: 0 auto; display: table;">
			<li><a href="#">Class Caption</a></li>
			<li><a href="#">Performance Testing</a></li>
		</ul>
	</div>
	<div id="sitemap_block_right">
		<h3 style="text-align: center;">About</h3>
		<ul style="margin: 0 auto; display: table;">
			<li><a href="#">Home Page</a></li>
			<li><a href="#">Community Forum</a></li>
		</ul>
	</div>
</div>
-->
<div id="powered_by">
	Powered by ND PHP Framework - <a class="powered_by_link" href="https://www.nd-php.org">www.nd-php.org</a>&nbsp;
</div>
<div id="ajax_error_dialog">
</div>
</body> <!-- End of BODY -->
</html>
