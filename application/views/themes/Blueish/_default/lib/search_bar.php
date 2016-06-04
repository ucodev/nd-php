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

<!-- Search Bar -->
<div id="searchbar">
	<form action="<?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($view['ctrl'], $config['charset'])?>/result/basic" name="searchform" id="searchform" method="post">
		<div id="searchbar_components">
			<input id="searchbar_input" class="searchbar" type="text" name="search_value" <?=isset($view['search_value']) ? ('value="' . filter_html($view['search_value'], $config['charset']) . '"') : ''?> placeholder="<?=filter_html(NDPHP_LANG_MOD_OP_SEARCH, $config['charset'])?>..." accesskey="<?=filter_html(NDPHP_LANG_MOD_OP_ACCESS_KEY_SEARCH_BASIC, $config['charset'])?>" />
			<a href="javascript:void(0);" onclick="ndphp.form.search_submitform(event, '<?=filter_html_js_str($view['ctrl'], $config['charset'])?>');">
				<img width="18" height="18" class="searchbutton" alt="<?=filter_html(NDPHP_LANG_MOD_BUTTON_SEARCH, $config['charset'])?>" src="<?=filter_html(static_images_url(), $config['charset'])?>/themes/<?=filter_html($config['theme']['name'], $config['charset'])?>/icons/search_button.png" />
			</a>
		</div>
		<script type="text/javascript">
			/* TODO: Move this script elsewhere... it should not be here. */
			jQuery('#searchform').submit(function(e) {
				if (ndphp.grouping.enabled) {
					ndphp.ajax.url = "<?=filter_js_str(base_url(), $config['charset'])?>index.php/<?=filter_js_str($view['ctrl'], $config['charset'])?>/result_group_body_ajax/<?=filter_js_str($view['grouping_field'], $config['charset'])?>/basic";
				} else {
					ndphp.ajax.url = "<?=filter_js_str(base_url(), $config['charset'])?>index.php/<?=filter_js_str($view['ctrl'], $config['charset'])?>/result_body_ajax/basic";
				}

				jQuery("#body").nd_animate_hide(ndphp.animation.delay, function() {
					jQuery.ajax({
						type: "POST",
						url: ndphp.ajax.url,
						data: jQuery("#searchform").serialize(),
						success: function(data) {
							var html = jQuery(data);
							ndphp.nav.back_store('body', jQuery('#body').html());
							jQuery("#body").html(html).nd_animate_show(ndphp.animation.delay);
						},
						error: function(xhr, ajaxOptions, thrownError) {
							jQuery("#ajax_error_dialog").html('<?=filter_html_js_str(NDPHP_LANG_MOD_UNABLE_SEARCH, $config['charset'])?> <?=filter_html_js_str(NDPHP_LANG_MOD_ATTN_VALIDATE_VALUES, $config['charset'])?> (' + xhr.responseText + ')');
							jQuery("#ajax_error_dialog").dialog({ modal: true, title: '<?=filter_html_js_str(NDPHP_LANG_MOD_CANNOT_SEARCH, $config['charset'])?>' });
						}
					})
				});

				ndphp.ajax.url = '';

				e.preventDefault();
				return false;
			});
		</script>
	</form>
</div>