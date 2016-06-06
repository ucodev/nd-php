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

			/* FIXME: Not the best approach... To be redesigned */
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
				jQuery("#<?=filter_js_special($field, $config['charset'])?>_from").datepicker();
				jQuery("#<?=filter_js_special($field, $config['charset'])?>_from").datepicker('option', 'dateFormat', 'yy-mm-dd');
				jQuery("#<?=filter_js_special($field, $config['charset'])?>_from_time").timepicker();

				jQuery("#<?=filter_js_special($field, $config['charset'])?>_to").datepicker();
				jQuery("#<?=filter_js_special($field, $config['charset'])?>_to").datepicker('option', 'dateFormat', 'yy-mm-dd');
				jQuery("#<?=filter_js_special($field, $config['charset'])?>_to_time").timepicker();
			<?php elseif ($meta['type'] == 'date'): ?>
				jQuery("#<?=filter_js_special($field, $config['charset'])?>_from").datepicker();
				jQuery("#<?=filter_js_special($field, $config['charset'])?>_from").datepicker('option', 'dateFormat', 'yy-mm-dd');

				jQuery("#<?=filter_js_special($field, $config['charset'])?>_to").datepicker();
				jQuery("#<?=filter_js_special($field, $config['charset'])?>_to").datepicker('option', 'dateFormat', 'yy-mm-dd');
			<?php elseif ($meta['type'] == 'time'): ?>
				jQuery("#<?=filter_js_special($field, $config['charset'])?>_from").timepicker();

				jQuery("#<?=filter_js_special($field, $config['charset'])?>_to").timepicker();
			<?php endif; ?>

			/* Search fields are hidden by default */
			jQuery("#search_field_<?=filter_js_special($field, $config['charset'])?>").hide();
		<?php endforeach; ?>
	});

	function search_expand_options(opt_div, btn_div) {
		jQuery("#" + btn_div).hide();
		jQuery("#" + opt_div).show();
	}
</script>