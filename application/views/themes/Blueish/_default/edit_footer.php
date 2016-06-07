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

<?php $row = array_values($view['result_array'])[0]; ?>
<?php $textarea_ids = array(); ?>

<script type="text/javascript">
	/* BEGIN OF jQuery(document). ... */
	jQuery(document).ready(function() {
		/* Enable form validation */
		jQuery("#editform").validate({
			errorElement: "span",
			wrapper: "span"
		});

		<?php if (!isset($config['modalbox'])): ?>
			/* Set the origin controller */
			ndphp.origin_controller = '<?=filter_js_str($view['ctrl'], $config['charset'])?>';

		<?php else: ?>
			/* Set modal box content to 100% (fixing some issues with some content) */
			jQuery('#MB_content div.edit').css('width', '100%');
		<?php endif; ?>

		/* Field specific handlers and modifiers */
		<?php $tab_index = 0; foreach ($view['fields'] as $field => $meta): ?>
			<?php if ($meta['type'] == 'separator') { $tab_index ++; continue; }?>
			<?php if ($meta['type'] == 'rel') continue; ?>
			<?php if (in_array($field, $config['hidden_fields'])) continue; ?>
			<?php if (in_array($field, $view['required'])): ?>
					/* Update fields tab map */
					if ( typeof required_fields_tab_map[<?=$tab_index?>] == 'undefined')
						required_fields_tab_map[<?=$tab_index?>] = [];

					required_fields_tab_map[<?=$tab_index?>].push('<?=filter_js_str($field, $config['charset'])?>');
			<?php endif; ?>
			<?php if (in_array($field, $config['rich_text'])): array_push($textarea_ids, $field); ?>
					tinyMCE.init({
						selector: '#<?=filter_js_special($field, $config['charset'])?>',
						mode : "textareas",
						theme : "advanced",
						plugins: "wordcount,fullscreen,lists,table,print",
						theme_advanced_buttons1: "newdocument,print,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
						theme_advanced_buttons2: "bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code",
						theme_advanced_buttons3: "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,|,fullscreen",
						theme_advanced_toolbar_location : "top",
						theme_advanced_toolbar_align : "left",
						theme_advanced_statusbar_location : "bottom",
						theme_advanced_resizing : true
					});
			<?php endif; ?>
			<?php if ($meta['type'] == 'datetime'): $_datetime = explode(' ', $row[$field]); ?>
					jQuery("#<?=filter_js_special($field, $config['charset'])?>").datepicker();
					jQuery("#<?=filter_js_special($field, $config['charset'])?>").datepicker('option', 'dateFormat', 'yy-mm-dd');
					jQuery("#<?=filter_js_special($field, $config['charset'])?>").datepicker('setDate', '<?=filter_html_js_str($_datetime[0], $config['charset'])?>');
					jQuery("#<?=filter_js_special($field, $config['charset'])?>_time").timepicker();
					jQuery("#<?=filter_js_special($field, $config['charset'])?>").addClass('input_date');
					jQuery("#<?=filter_js_special($field, $config['charset'])?>_time").addClass('input_time');
			<?php endif; ?>
			<?php if ($meta['type'] == 'date'): ?>
					jQuery("#<?=filter_js_special($field, $config['charset'])?>").datepicker();
					jQuery("#<?=filter_js_special($field, $config['charset'])?>").datepicker('option', 'dateFormat', 'yy-mm-dd');
					jQuery("#<?=filter_js_special($field, $config['charset'])?>").datepicker('setDate', '<?=filter_html_js_str($row[$field], $config['charset'])?>');
					jQuery("#<?=filter_js_special($field, $config['charset'])?>").addClass('input_date');
			<?php endif; ?>
			<?php if ($meta['type'] == 'time'): ?>
					jQuery("#<?=filter_js_special($field, $config['charset'])?>").timepicker();
					jQuery("#<?=filter_js_special($field, $config['charset'])?>").addClass('input_time');
			<?php endif; ?>
			<?php if ($meta['input_type'] == 'timer'): ?>
					var tval_raw = '00:00:00'.split(':');

					if (jQuery('#<?=filter_html_special($field, $config['charset'])?>').val())
						tval_raw = String(jQuery('#<?=filter_html_special($field, $config['charset'])?>').val()).split(':');

					jQuery('#<?=filter_js_special($field, $config['charset'])?>').timer({ seconds: parseInt(tval_raw[0]) * 3600 + parseInt(tval_raw[1]) * 60 + parseInt(tval_raw[2]), format: "%H:%M:%S" });
					jQuery('#<?=filter_js_special($field, $config['charset'])?>').timer('pause');
					jQuery("#<?=filter_js_special($field, $config['charset'])?>").timepicker();
			<?php endif; ?>
		<?php endforeach; ?>

		/* Switch to last seen tab, if any */
		if (ndphp.current.tab_index != null) {
			jQuery('#entry_tabs').tabs('option', 'active', ndphp.current.tab_index);
			ndphp.current.tab_index = null;
		}

	}); /* END OF jQuery(document). ... */

	/* Submit Wrapper */
	ndphp.form.submit_edit_wrapper = function(e, ctrl, form_id, from_modal, id) {
		e.preventDefault();

		<?php foreach ($textarea_ids as $ta_id): ?>
			/* Force textareas handled by tinyMCE to be saved before submit */
			<?php if (!isset($config['modalbox'])): ?>
				tinyMCE.get("<?=filter_js_special($ta_id, $config['charset'])?>").save();
			<?php endif; ?>
		<?php endforeach; ?>

		/* If any required field is empty, switch to the corresponding tab */
		for (i = 0; i < required_fields_tab_map.length; i ++) {
			for (j = 0; j < required_fields_tab_map[i].length; j ++) {
				if (jQuery('input[name="' + required_fields_tab_map[i][j] + '"]').val() == '') {
					/* Switch to the field's tab */
					jQuery('#entry_tabs').tabs('option', 'active', i);

					/* Perform form validation */
					if (!jQuery("#" + form_id).valid()) {
						jQuery("#ajax_error_dialog").html('<?=filter_html_js_str(NDPHP_LANG_MOD_ATTN_SUBMIT_REQUIRED_FIELDS, $config['charset'])?>');
						jQuery("#ajax_error_dialog").dialog({ modal: true, title: '<?=filter_html_js_str(NDPHP_LANG_MOD_MISSING_REQUIRED_FIELDS, $config['charset'])?>' });
						return;
					}

					/* TODO: Although password match verification is also performed in ndphp.form.submit_create(),
					 *       it should be also verified here in order to, on error, switch to the correct tab.
					 */

					return false;
				}
			}
		}

		/* Multiple select boxes (multiple relationships) must have all options selected before submiting */
		jQuery('select[id$=_selected]').append('<option value="0" style="visibility: hidden;">None</option>');
		jQuery('select[multiple] option').prop('selected', true);

		ndphp.form.submit_edit(e, ctrl, form_id, from_modal, id);
	};
</script>
