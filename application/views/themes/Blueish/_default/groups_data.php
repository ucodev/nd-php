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
<div id="groups">
	<?php $tabs_groups = true; include('lib/tabs_header.php'); ?>
		<div id="groups_list">
			<?php if (count($view['groups'])): ?>
				<?php foreach ($view['groups'] as $group): ?>
					<a href="<?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($view['ctrl'], $config['charset'])?>/list_group/<?=filter_html($group['table_field'], $config['charset'])?>" onclick="ndphp.ajax.load_body_group(event, '<?=filter_html_js_str($view['ctrl'], $config['charset'])?>', '<?=filter_html_js_str($group['table_field'], $config['charset'])?>');" class="group_link">
						<?=filter_html($group['name'], $config['charset'])?>
					</a>
				<?php endforeach; ?>
			<?php else: ?>
				<p class="no_groups"><?=filter_html(NDPHP_LANG_MOD_EMPTY_GROUPS, $config['charset'])?></p>
			<?php endif; ?>
		</div>
	<?php include('lib/tabs_footer.php'); ?>
</div>