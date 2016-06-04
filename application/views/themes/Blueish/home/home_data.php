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
<div class="home_body">
	<?php
		if (count($view['mainmenu'])):
			$count = 1;
			foreach ($view['mainmenu'] as $entry):
		?>
				<div class="home_menu_entry">
					<table class="home_menu_entry_table">
						<tr>
							<td>
								<a title="<?=filter_html(ucfirst($entry[1]), $config['charset'])?>" href="<?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($entry[0], $config['charset'])?>" onclick="ndphp.ajax.load_body_menu(event, '<?=filter_html_js_str($entry[0], $config['charset'])?>', '<?=filter_html_js_str($entry[1], $config['charset'])?>');" class="home_icon_link">
									<img src="<?=filter_html(static_images_url(), $config['charset'])?>/menu/<?=filter_html($entry[0], $config['charset'])?>.png" alt="<?=filter_html(ucfirst($entry[1]), $config['charset'])?>" />
								</a>
							</td>
						</tr>
						<tr>
							<td>
								<span class="home_menu_entry_legend"><?=filter_html(ucfirst($entry[1]), $config['charset'])?></span>
							</td>
						</tr>
					</table>
				</div>
				<?php if (!($count % 5)): ?>
					<br />
				<?php endif; ?>
	<?php
				$count ++;
			endforeach;
		else:
	?>
		<br /><br /><br /><br /><br />
		<p class="no_mainmenu"><?=filter_html(NDPHP_LANG_MOD_EMPTY_MAINMENU, $config['charset'])?></p>
		<br /><br /><br /><br /><br />
	<?php
		endif;
	?>
</div>
