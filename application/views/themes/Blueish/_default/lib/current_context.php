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

<script type="text/javascript">
	/* Update current context */
	ndphp.current.controller = '<?=filter_js_str($view['ctrl'], $config['charset'])?>';
	ndphp.current.charset = '<?=filter_js_str($config['charset'], $config['charset'])?>';

	/* Mark all menu entries as inactive */
	jQuery("li[id^='menu_entry_']").removeClass('active');

	/* Set the current menu entry as active */
	jQuery('#menu_entry_<?=$view['ctrl']?>').addClass('active');
	<?php if (in_array($view['ctrl'], array('configuration', 'charts_config', 'scheduler'))): ?>
		/* Controllers belonging to System group shall also show the system menu as active */
		jQuery('#menu_entry_system').addClass('active');
	<?php endif; ?>
</script>

<a id="doc_rest_json" target="_blank" href="<?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($view['ctrl'], $config['charset'])?>/json_doc" style="display: none;" accesskey="<?=filter_html(NDPHP_LANG_MOD_OP_ACCESS_KEY_JSON, $config['charset'])?>">REST JSON API</a>
