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
<td class="field_name">
	#<?=filter_html($view['mixed_id'], $config['charset'])?>
</td>
<?php
	$i = 0;
	$textarea_ids = array();
	foreach ($view['fields'] as $field => $meta):
		if ($meta['type'] == 'separator')
			continue;

		/* Ignore mixed relationship fields to avoid double recursion */
		if ($meta['type'] == 'mixed')
			continue;

	 	/* Build an array of textareas ID's to be used on create/update ajax post functions
	 	 * so the contents of tinyMCE can be saved before submitted.
	 	 */
		if ($meta['input_type'] == 'textarea' && in_array($field, $config['rich_text']))
			array_push($textarea_ids, $field);

		if ($field == 'id'):
?>
			<input name="mixed_<?=filter_html($view['ctrl'], $config['charset'])?>_<?=filter_html($view['ctrl'], $config['charset'])?>_id_<?=filter_html($view['mixed_id'], $config['charset'])?>" type="hidden" value="<?=filter_html($view['values']['id'], $config['charset'])?>">
<?php
			continue;
		endif;

		if (!in_array($field, $view['present_fields']))
			continue;

		if (in_array($field, $config['hidden_fields']))
			continue;
?>
		<td class="field_value">
<?php
		if ($meta['input_type'] == 'checkbox') {
?>
			<input name="mixed_<?=filter_html($view['ctrl'], $config['charset'])?>_<?=filter_html($field, $config['charset'])?>_<?=filter_html($view['mixed_id'], $config['charset'])?>" type="hidden" value="0" />
			<input alt="<?=filter_html(ucfirst($meta['viewname']), $config['charset'])?> <?=in_array($field, $view['required']) ? '(' . filter_html(NDPHP_LANG_MOD_WORD_REQUIRED, $config['charset']) . ')' : ''?>" name="mixed_<?=filter_html($view['ctrl'], $config['charset'])?>_<?=filter_html($field, $config['charset'])?>_<?=filter_html($view['mixed_id'], $config['charset'])?>" type="checkbox" value="1" <?php if (isset($view['values'][$field]) && ($view['values'][$field])) { echo('checked="checked"'); }?> />
			<?=in_array($field, $view['required']) ? '*' : ''?>
<?php
		} else if ($meta['input_type'] == 'select') {
?>
			<select name="mixed_<?=filter_html($view['ctrl'], $config['charset'])?>_<?=filter_html($field, $config['charset'])?>_<?=filter_html($view['mixed_id'], $config['charset'])?>" <?php if (isset($config['mixed']['table_field_width'][$field])) echo('style="width: ' . filter_html($config['mixed']['table_field_width'][$field], $config['charset']) . ';"'); ?> >
				<?php
				foreach ($meta['options'] as $opt_id => $opt_value):
				?>
					<option value="<?=filter_html($opt_id, $config['charset'])?>_<?=filter_html($opt_value, $config['charset'])?>" <?php if ((isset($view['values'][$field]) && ($view['values'][$field] == $opt_id)) || ($default[$field] == $opt_id)) { echo('selected="selected"'); } ?> >
						<?=filter_html($opt_value, $config['charset'])?>
					</option>
				<?php
				endforeach;
				?>
			</select>
			<?php echo(in_array($field, $view['required']) ? '*' : NULL); ?>
<?php
		} else if ($meta['input_type'] == 'timer') {
?>
			<input id="mixed_<?=filter_html_special($view['ctrl'], $config['charset'])?>_<?=filter_html_special($field, $config['charset'])?>_<?=filter_html_special($view['mixed_id'], $config['charset'])?>" alt="<?=filter_html(ucfirst($meta['viewname']), $config['charset'])?> <?=in_array($field, $view['required']) ? '(' . filter_html(NDPHP_LANG_MOD_WORD_REQUIRED, $config['charset']) . ')' : ''?>" name="mixed_<?=filter_html($view['ctrl'], $config['charset'])?>_<?=filter_html($field, $config['charset'])?>_<?=filter_html($view['mixed_id'], $config['charset'])?>" type="text" value="<?php if (isset($view['values'][$field])) { echo(filter_html($view['values'][$field], $config['charset'])); } else { echo('00:00:00'); }?>" <?php echo(in_array($field, $view['required']) ? 'required="required"' : NULL); ?> <?php if (isset($config['mixed']['table_field_width'][$field])) echo('style="width: ' . filter_html($config['mixed']['table_field_width'][$field], $config['charset']) . ';"'); ?> />
			<script type="text/javascript">
				jQuery("#mixed_<?=filter_js_special($view['ctrl'], $config['charset'])?>_<?=filter_js_special($field, $config['charset'])?>_<?=filter_js_special($view['mixed_id'], $config['charset'])?>").timepicker();
			</script>
			<?php echo(in_array($field, $view['required']) ? '*' : NULL); ?>
<?php
		} else if ($meta['input_type'] == 'file') {
?>
			<input id="mixed_<?=filter_html_special($view['ctrl'], $config['charset'])?>_<?=filter_html_special($field, $config['charset'])?>_<?=filter_html_special($view['mixed_id'], $config['charset'])?>" alt="<?=filter_html(ucfirst($meta['viewname']), $config['charset'])?> <?=in_array($field, $view['required']) ? '(' . filter_html(NDPHP_LANG_MOD_WORD_REQUIRED, $config['charset']) . ')' : ''?>" name="mixed_<?=filter_html($view['ctrl'], $config['charset'])?>_<?=filter_html($field, $config['charset'])?>_<?=filter_html($view['mixed_id'], $config['charset'])?>" type="file" value="<?php if (isset($view['values'][$field])) { echo(filter_html($view['values'][$field], $config['charset'])); } ?>" <?php echo(in_array($field, $view['required']) ? 'required="required"' : NULL); ?> <?php if (isset($config['mixed']['table_field_width'][$field])) echo('style="width: ' . filter_html($config['mixed']['table_field_width'][$field], $config['charset']) . ';"'); ?> />
			<?php echo(in_array($field, $view['required']) ? '*' : NULL); ?>
<?php
		} else if ($meta['input_type'] == 'textarea') {
?>
			<?php if (in_array($field, $config['rich_text'])): ?>
				<script type="text/javascript">
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
				</script>
			<?php endif; ?>
			<textarea id="<?=filter_html_special($field, $config['charset'])?>" name="mixed_<?=filter_html($view['ctrl'], $config['charset'])?>_<?=filter_html($field, $config['charset'])?>_<?=filter_html($view['mixed_id'], $config['charset'])?>" alt="<?=filter_html(ucfirst($meta['viewname']), $config['charset'])?> <?=in_array($field, $view['required']) ? '(' . filter_html(NDPHP_LANG_MOD_WORD_REQUIRED, $config['charset']) . ')' : ''?>" placeholder="<?=filter_html($defaults[$field], $config['charset'])?>" <?php echo(in_array($field, $view['required']) ? 'required="required"' : NULL); ?> <?php if (isset($config['mixed']['table_field_width'][$field])) echo('style="width: ' . filter_html($config['mixed']['table_field_width'][$field], $config['charset']) . ';"'); ?> ><?php if (isset($view['values'][$field])) { echo(filter_html($view['values'][$field], $config['charset'])); } ?></textarea>
			<?php echo(in_array($field, $view['required']) ? '*' : NULL); ?>
<?php
		} else {
?>
			<?php if ($meta['type'] == 'datetime') {
					  if (isset($view['values'][$field])) {
					  	$datetime_parse = explode(' ', $view['values'][$field]);
						$dt_date = $datetime_parse[0];
						$dt_time = $datetime_parse[1];
					  }
				  }
			?>
			<input id="mixed_<?=filter_html_special($view['ctrl'], $config['charset'])?>_<?=filter_html_special($field, $config['charset'])?>_<?=filter_html_special($view['mixed_id'], $config['charset'])?>" alt="<?=filter_html(ucfirst($meta['viewname']), $config['charset'])?> <?=in_array($field, $view['required']) ? '(' . filter_html(NDPHP_LANG_MOD_WORD_REQUIRED, $config['charset']) . ')' : ''?>" <?php if (isset($view['values'][$field])) { echo('value="' . ($meta['type'] == 'datetime' ? filter_html($dt_date, $config['charset']) : filter_html($view['values'][$field], $config['charset'])) . '"'); } ?> name="mixed_<?=filter_html($view['ctrl'], $config['charset'])?>_<?=filter_html($field, $config['charset'])?>_<?=filter_html($view['mixed_id'], $config['charset'])?>" type="<?=filter_html($meta['input_type'], $config['charset'])?>" <?php if (($meta['type'] == 'datetime') || ($meta['type'] == 'date')) { echo('placeholder="YYYY-MM-DD"'); } else if ($meta['type'] == 'time') { echo('placeholder="HH:MM:SS"'); } else { echo('placeholder="' . filter_html($default[$field], $config['charset']) . '"'); } ?> <?php echo(in_array($field, $view['required']) ? 'required="required"' : NULL); ?> <?php if (isset($config['mixed']['table_field_width'][$field])) echo('style="width: ' . filter_html($config['mixed']['table_field_width'][$field], $config['charset']) . ';"'); ?> />
			<?php if ($meta['type'] == 'datetime'): ?>
				<input id="mixed_<?=filter_html_special($view['ctrl'], $config['charset'])?>_<?=filter_html_special($field, $config['charset'])?>_<?=filter_html_special($view['mixed_id'], $config['charset'])?>_time" alt="<?=filter_html(ucfirst($meta['viewname']), $config['charset'])?> <?=in_array($field, $view['required']) ? '(' . filter_html(NDPHP_LANG_MOD_WORD_REQUIRED, $config['charset']) . ')' : ''?>" name="mixed_<?=filter_html($view['ctrl'], $config['charset'])?>_<?=filter_html($field, $config['charset'])?>_<?=filter_html($view['mixed_id'], $config['charset'])?>_time" <?php if (isset($view['values'][$field])) { echo('value="' . filter_html($dt_time, $config['charset']) . '"'); }?> type="<?=filter_html($meta['input_type'], $config['charset'])?>" placeholder="HH:MM:SS" <?php echo(in_array($field, $view['required']) ? 'required="required"' : NULL); ?> <?php if (isset($config['mixed']['table_field_width'][$field])) echo('style="width: ' . filter_html($config['mixed']['table_field_width'][$field], $config['charset']) . ';"'); ?> />
				<script type="text/javascript">
					jQuery("#mixed_<?=filter_js_special($view['ctrl'], $config['charset'])?>_<?=filter_js_special($field, $config['charset'])?>_<?=filter_js_special($view['mixed_id'], $config['charset'])?>").datepicker();
					jQuery("#mixed_<?=filter_js_special($view['ctrl'], $config['charset'])?>_<?=filter_js_special($field, $config['charset'])?>_<?=filter_js_special($view['mixed_id'], $config['charset'])?>").datepicker('option', 'dateFormat', 'yy-mm-dd');
					jQuery("#mixed_<?=filter_js_special($view['ctrl'], $config['charset'])?>_<?=filter_js_special($field, $config['charset'])?>_<?=filter_js_special($view['mixed_id'], $config['charset'])?>_time").timepicker();
					jQuery("#mixed_<?=filter_js_special($view['ctrl'], $config['charset'])?>_<?=filter_js_special($field, $config['charset'])?>_<?=filter_js_special($view['mixed_id'], $config['charset'])?>").addClass('input_date');
					jQuery("#mixed_<?=filter_js_special($view['ctrl'], $config['charset'])?>_<?=filter_js_special($field, $config['charset'])?>_<?=filter_js_special($view['mixed_id'], $config['charset'])?>_time").addClass('input_time');
				</script>
			<?php endif;?>
			<?php if ($meta['type'] == 'date'): ?>
				<script type="text/javascript">
					jQuery("#mixed_<?=filter_js_special($view['ctrl'], $config['charset'])?>_<?=filter_js_special($field, $config['charset'])?>_<?=filter_js_special($view['mixed_id'], $config['charset'])?>").datepicker();
					jQuery("#mixed_<?=filter_js_special($view['ctrl'], $config['charset'])?>_<?=filter_js_special($field, $config['charset'])?>_<?=filter_js_special($view['mixed_id'], $config['charset'])?>").datepicker('option', 'dateFormat', 'yy-mm-dd');
					jQuery("#mixed_<?=filter_js_special($view['ctrl'], $config['charset'])?>_<?=filter_js_special($field, $config['charset'])?>_<?=filter_js_special($view['mixed_id'], $config['charset'])?>").addClass('input_date');
				</script>
			<?php endif; ?>
			<?php if ($meta['type'] == 'time'): ?>
				<script type="text/javascript">
					jQuery("#mixed_<?=filter_js_special($view['ctrl'], $config['charset'])?>_<?=filter_js_special($field, $config['charset'])?>_<?=filter_js_special($view['mixed_id'], $config['charset'])?>").timepicker();
					jQuery("#mixed_<?=filter_js_special($view['ctrl'], $config['charset'])?>_<?=filter_js_special($field, $config['charset'])?>_<?=filter_js_special($view['mixed_id'], $config['charset'])?>").addClass('input_time');
				</script>
			<?php endif; ?>
			<?php echo(in_array($field, $view['required']) ? '*' : NULL); ?>
<?php
		}

?>
		</td>
<?php
		$i ++;
	endforeach;
?>
<td class="fields_mixed_ops">
	<!-- TODO: Remove the following div and set its ID to the <td> tag above -->
	<div id="mixed_<?=filter_html_special($view['ctrl'], $config['charset'])?>_ops_<?=filter_html_special($view['mixed_id'], $config['charset'])?>" style="display: inline-block">
		
	</div>
</td>
