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

<?php include($view['base_dir'] . '/_default/lib/current_context.php'); ?>

<div id="crud_title">
	<h1 class="crud_header"><?=$view['links']['breadcrumb']?></h1>
	<script type="text/javascript">
		/* Set correct window title (if this view was loaded via an ajax call, the title needs to be changed) */
		window.document.title = '<?=filter_js_str(html_entity_decode(strip_tags($project['name'] . " - " . $view['title']), ENT_QUOTES, $config['charset']), $config['charset'])?>';
	</script>

	<?php include($view['base_dir'] . '/_default/lib/search_bar.php'); ?>
</div>
<div class="submenu">
	<?php foreach ($view['links']['submenu'] as $link):
			/*
			 * $link[0] - Description
			 * $link[1] - Sec perm
			 * $link[2] - Function or raw link
			 * $link[3] - Icon
			 * $link[4] - Callback type
			 * $link[5] - ID related
			 * $link[6] - Access Key
			 */

			if (!security_perm_check($security['perms'], $link[1], $view['ctrl']))
				continue;
	?>
			<?php if ($link[4] == 'ajax' && $link[5] !== true): ?>
				<a href="<?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($view['ctrl'], $config['charset'])?>/<?=filter_html($link[2], $config['charset'])?>" onclick="ndphp.ajax.load_body_op(event, '<?=filter_html_js_str($view['ctrl'], $config['charset'])?>', '<?=filter_html_js_str($link[2], $config['charset'])?>');" title="<?=filter_html($link[0], $config['charset'])?>" class="submenu_link" accesskey="<?=filter_html($link[6], $config['charset'])?>">
			<?php elseif ($link[4] == 'method' && $link[5] !== true): ?>
				<a href="<?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($view['ctrl'], $config['charset'])?>/<?=filter_html($link[2], $config['charset'])?>" title="<?=filter_html($link[0], $config['charset'])?>" class="submenu_link" accesskey="<?=filter_html($link[6], $config['charset'])?>">
			<?php elseif ($link[4] == 'modal' && $link[5] !== true): ?>
				<a href="<?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($view['ctrl'], $config['charset'])?>/<?=filter_html($link[2], $config['charset'])?>" title="<?=filter_html($link[0], $config['charset'])?>" onclick="Modalbox.show(this.href, {title: this.title, width: 600}); return false;" class="submenu_link" accesskey="<?=filter_html($link[6], $config['charset'])?>">
			<?php elseif ($link[4] == 'raw' && $link[5] !== true): ?>
				<a href="<?=filter_html($link[2], $config['charset'])?>" title="<?=filter_html($link[0], $config['charset'])?>" class="submenu_link" accesskey="<?=filter_html($link[6], $config['charset'])?>">
			<?php elseif ($link[4] == 'export'): ?>
				<a href="<?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($view['ctrl'], $config['charset'])?>/export/<?=filter_html($export_query, $config['charset'])?>/<?=filter_html($link[2], $config['charset'])?>" title="<?=filter_html($link[0], $config['charset'])?>" class="submenu_link" accesskey="<?=filter_html($link[6], $config['charset'])?>">
			<?php endif; ?>
					<?php if ($link[3] !== NULL): /* If a icon is set */ ?>
						<img class="submenu_icon" alt="<?=filter_html($link[0], $config['charset'])?>" src="<?=filter_html(static_images_url(), $config['charset'])?>/<?=filter_html($link[3], $config['charset'])?>" />
					<?php else: ?>
						<?=filter_html($link[0], $config['charset'])?>
					<?php endif; ?>
				</a>
	<?php endforeach; ?>
</div>