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
	jQuery(document).ready(function() {
		/* Set listing context parameters */
		ndphp.last_listing_op = 'result';
		ndphp.grouping.enabled = true;
		ndphp.grouping.controller = '<?=filter_js_str($view['ctrl'], $config['charset'])?>';
		ndphp.grouping.field = '<?=filter_js_str($view['grouping_field'], $config['charset'])?>';

		/* Group specific handlers and modifiers */
		<?php foreach ($view['grouping_result_array'] as $group => $result_array): ?>
			ndphp.grouping.group_visibility_eval('<?=filter_js_str($view['ctrl'], $config['charset'])?>', "<?=filter_js_special($view['grouping_hashes'][$group], $config['charset'])?>");
		<?php endforeach; ?>

		/* Field specific handlers and modifiers */
		<?php foreach ($view['fields'] as $field => $meta): ?>
			<?php if ($view['fields'][$field]['input_type'] == 'file'): ?>
				jQuery('[id^=<?=filter_js_special($field, $config['charset'])?>_]').parent().css('width', '<?=filter_js_str($config['render']['size']['width'], $config['charset'])?>');
				jQuery('[id^=<?=filter_js_special($field, $config['charset'])?>_]').parent().css('height', '<?=filter_js_str($config['render']['size']['height'], $config['charset'])?>');
				jQuery('[id^=<?=filter_js_special($field, $config['charset'])?>_]').parent().css('text-align', 'center');
			<?php endif; ?>
		<?php endforeach; ?>

		/* Update export option from submenu whenever this view is loaded */
		jQuery('a[title="<?=filter_js_str(NDPHP_LANG_MOD_OP_EXPORT_PDF, $config['charset'])?>"]').attr('href', '<?=filter_html_js_str(base_url(), $config['charset'])?>index.php/<?=filter_html_js_str($view['ctrl'], $config['charset'])?>/export/<?=filter_html_js_str($view['export_query'], $config['charset'])?>');					
		jQuery('a[title="<?=filter_js_str(NDPHP_LANG_MOD_OP_EXPORT_CSV, $config['charset'])?>"]').attr('href', '<?=filter_html_js_str(base_url(), $config['charset'])?>index.php/<?=filter_html_js_str($view['ctrl'], $config['charset'])?>/export/<?=filter_html_js_str($view['export_query'], $config['charset'])?>/csv');

		/* FIXME: TODO: Currently we need to (re)declare this handler here to keep the workflow context... */
		ndphp.ajax.update_data_result = function() {
			jQuery.ajax({
				type: "POST",
				url: "<?=filter_js_str(base_url(), $config['charset'])?>index.php/<?=filter_js_str($view['ctrl'], $config['charset'])?>/result_group_data_ajax/<?=filter_js_str($view['grouping_field'], $config['charset'])?>/query/<?=filter_js_str($view['result_query'], $config['charset'])?>/<?=filter_js_str($config['order_by'], $config['charset'])?>/<?php echo($config['order'] == 'asc' ? 'desc' : 'asc');?>/<?=filter_js_str($page, $config['charset'])?>",
				success: function(data) {
					var html = jQuery(data);
					ndphp.nav.back_store('result', jQuery('#result').html());
					jQuery("#result").nd_animate_hide(ndphp.animation.ordering_delay, function() {
						jQuery("#result").replaceWith(function() {
							return jQuery(html).nd_animate_show(ndphp.animation.ordering_delay);
						});
						/* NOTE: For some reason, jquery 1.8.3 is loosing the display
						 * element of the div style. We need to force it while the div
						 * is loading in order to be correctly rendered.
						 */
						jQuery('#result').css({"display":"table"});
					});
				},
				error: function(xhr, ajaxOptions, thrownError) {
					jQuery("#ajax_error_dialog").html('<?=filter_html_js_str(NDPHP_LANG_MOD_UNABLE_LOAD_VIEW_LIST, $config['charset'])?><br /><br /><span style="font-weight: bold"><?=filter_html_js_str(ucfirst(NDPHP_LANG_MOD_WORD_REASON), $config['charset'])?>:</span> ' + xhr.responseText);
					jQuery("#ajax_error_dialog").dialog({ modal: true, title: '<?=filter_html_js_str(NDPHP_LANG_MOD_CANNOT_DISPLAY_LIST, $config['charset'])?>' });
				}
			});
		};
	});
</script>
