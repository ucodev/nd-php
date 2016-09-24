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
 <div class="home_result">
	 <div class="table-responsive">
	 	<?php if (count($view['result_array'])): ?>
		 	<table class="table table-striped table-hover">
			 	<?php $i = 0; foreach ($view['result_array'] as $row): ?>
			 		<tr>
			 			<td style="vertical-align: middle;">
			 				<form target="_blank" id="<?=filter_html_special($row['controller'], $config['charset'])?>_form" method="post" action="<?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($row['controller'], $config['charset'])?>/result/basic">
			 					<input type="hidden" name="search_value" value="<?=filter_html($view['search_value'], $config['charset'])?>" />
			 					<a href="javascript:void(0);" onclick="document.getElementById('<?=filter_js_special($row['controller'], $config['charset'])?>_form').submit();" title="<?=filter_html($row['controller'], $config['charset'])?>">
			 						<?=filter_html($row['viewname'], $config['charset'])?>
			 					</a>
			 				</form>
			 			</td>
			 			<td style="text-align: right;">
			 				<span class="badge">
			 					<?=count($row['result'])?>
			 				</span>
				 		</td>
			 		</tr>
			 	<?php $i ++; endforeach; ?>
		 	</table>
		 <?php else: ?>
		 	<br />
		 	<br />
		 	<br />
		 	<p class="no_results"><?=filter_html(NDPHP_LANG_MOD_EMPTY_RESULTS, $config['charset'])?></p>
		 	<br />
		 	<br />
		 	<br />
		 <?php endif; ?>
	</div>
</div>
