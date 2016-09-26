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
		/* Field specific handlers and modifiers */
		<?php foreach ($view['fields'] as $field => $meta): ?>
			<?php if ($meta['type'] == 'separator') continue; ?>
			<?php if (in_array($field, $config['hidden_fields'])) continue; ?>

			/* Hide conditions divs */
			jQuery("#search_cond_field_<?=filter_js_special($field, $config['charset'])?>").hide();

			var checkbox_<?=filter_js_special($field, $config['charset'])?> = false;

			/* FIXME 1: Not the best approach... To be redesigned */
			/* FIXME 2: Browsing actions back_store() should account for full modifications on the advanced search form.
			 *          If the user goes back after a search, the previously inserted data on the form should be visible.
			 *          Currently the form is being reset after a browsing action back is performed.
			 */
			jQuery("#search_criteria_checkbox_<?=filter_js_special($field, $config['charset'])?>").click(function() {
				if (checkbox_<?=filter_js_special($field, $config['charset'])?>) {
					checkbox_<?=filter_js_special($field, $config['charset'])?> = false;
					jQuery("#search_field_<?=filter_js_special($field, $config['charset'])?>").nd_animate_hide(800);
				} else {
					checkbox_<?=filter_js_special($field, $config['charset'])?> = true;
					jQuery("#search_field_<?=filter_js_special($field, $config['charset'])?>").nd_animate_show(800);
				}
			});

			/* Datetime, date and time fields require special handlers/modifiers */
			<?php if ($meta['type'] == 'datetime'): ?>
				jQuery("#<?=filter_js_special($field, $config['charset'])?>_from").datetimepicker({
					format: 'yyyy/mm/dd',
					startView: 2,
					minView: 2,
					maxView: 4
				});
				jQuery("#<?=filter_js_special($field, $config['charset'])?>_from_time").datetimepicker({
					format: 'hh:ii:ss',
					startView: 0,
					maxView: 1
				});

				jQuery("#<?=filter_js_special($field, $config['charset'])?>_to").datetimepicker({
					format: 'yyyy/mm/dd',
					startView: 2,
					minView: 2,
					maxView: 4
				});
				jQuery("#<?=filter_js_special($field, $config['charset'])?>_to_time").datetimepicker({
					format: 'hh:ii:ss',
					startView: 0,
					maxView: 1
				});
			<?php elseif ($meta['type'] == 'date'): ?>
				jQuery("#<?=filter_js_special($field, $config['charset'])?>_from").datetimepicker({
					format: 'yyyy/mm/dd',
					startView: 2,
					minView: 2,
					maxView: 4
				});

				jQuery("#<?=filter_js_special($field, $config['charset'])?>_to").datetimepicker({
					format: 'yyyy/mm/dd',
					startView: 2,
					minView: 2,
					maxView: 4
				});
			<?php elseif ($meta['type'] == 'time'): ?>
				jQuery("#<?=filter_js_special($field, $config['charset'])?>_from").datetimepicker({
					format: 'hh:ii:ss',
					startView: 0,
					maxView: 1
				});

				jQuery("#<?=filter_js_special($field, $config['charset'])?>_to").datetimepicker({
					format: 'hh:ii:ss',
					startView: 0,
					maxView: 1
				});
			<?php endif; ?>

			/* Search fields are hidden by default */
			jQuery("#search_field_<?=filter_js_special($field, $config['charset'])?>").hide();
		<?php endforeach; ?>

		/* Reset current tab index */
		ndphp.current.tab_index = null;

		/* On submit ... */
		jQuery('#advsearchform').submit(function(e) {
			ndphp.form.submit_adv_search(e, '<?=filter_html_js_str($view['ctrl'], $config['charset'])?>');
		});

	});

	function search_expand_options(opt_div, btn_div) {
		jQuery("#" + btn_div).hide();
		jQuery("#" + opt_div).show();
	}
</script>
