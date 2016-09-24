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

<?php $textarea_ids = array(); /* FIXME: Currently unsupported. */ ?>

<?php $i = 0; foreach ($view['fields'] as $field => $meta): ?>
	<?php
		if ($meta['type'] == 'separator')
			continue;

		/* Ignore mixed relationship fields to avoid double recursion */
		if ($meta['type'] == 'mixed')
			continue;

	 	/* FIXME: Currently unsupported. 
	 	 *
	 	 * Build an array of textareas ID's to be used on create/update ajax post functions
	 	 * so the contents of tinyMCE can be saved before submitted.
	 	 */
		if ($meta['input_type'] == 'textarea' && in_array($field, $config['rich_text']))
			array_push($textarea_ids, $field);

	?>

	<?php if ($field == 'id'): ?>
		<input name="mixed_<?=filter_html($view['ctrl'], $config['charset'])?>_<?=filter_html($view['ctrl'], $config['charset'])?>_id_<?=filter_html($view['mixed_id'], $config['charset'])?>" type="hidden" value="<?=filter_html($view['values'][$view['ctrl'] . '_id'], $config['charset'])?>">
		<?php continue; ?>
	<?php endif; ?>

	<?php
		if (!in_array($field, $view['present_fields']))
			continue;

		if (in_array($field, $config['hidden_fields']))
			continue;
	?>

	<td class="field_value">

	<?php if ($meta['input_type'] == 'checkbox'): ?>
			<?php if (isset($view['values'][$field]) && ($view['values'][$field])) { echo(filter_html(NDPHP_LANG_MOD_STATUS_CHECKBOX_CHECKED, $config['charset'])); } else { echo(filter_html(NDPHP_LANG_MOD_STATUS_CHECKBOX_UNCHECKED, $config['charset'])); } ?>
	<?php elseif ($meta['input_type'] == 'file'): /* FIXME: TODO: Missing render image handler */ ?>
			<a target="_blank" href="<?=filter_html(base_url(), $config['charset'])?>index.php/files/access/<?=filter_html($view['foreign_table'], $config['charset'])?>/<?=filter_html($view['foreign_id'], $config['charset'])?>/mixed_<?=filter_html($view['ctrl'], $config['charset'])?>_<?=filter_html($field, $config['charset'])?>_<?=filter_html($view['mixed_id'], $config['charset'])?>/<?=filter_html($view['values'][$field], $config['charset'])?>"><?=filter_html($view['values'][$field], $config['charset'])?></a>
	<?php elseif ($meta['input_type'] == 'select'): ?>
		<?php
			$val_trigger = false;
			
			foreach ($meta['options'] as $opt_id => $opt_value):
				if ($opt_value == $view['values'][$field]):
					$val_trigger = true;
		?>
					<a href="<?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($meta['table'], $config['charset'])?>/view_data_modalbox/<?=filter_html($opt_id, $config['charset'])?>" title="<?=filter_html(NDPHP_LANG_MOD_OP_QUICK_VIEW, $config['charset'])?>" onclick="ndphp.modal.show(this.href, '<?=filter_html_js_special(NDPHP_LANG_MOD_OP_QUICK_VIEW, $config['charset'])?>'); return false;">
						<?=filter_html($opt_value, $config['charset'])?>
					</a>
		<?php
				endif;
			endforeach;
			
			if ($val_trigger !== true)
				echo(filter_html($view['values'][$field], $config['charset']));
		?>
	<?php elseif ($meta['input_type'] == 'textarea'): ?>
		<?php if (isset($config['modalbox']) && in_array($field, $config['rich_text'])): ?>
				<a href="<?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($view['ctrl'], $config['charset'])?>/view/<?=filter_html($view['values']['id'], $config['charset'])?>" onclick="ndphp.ajax.load_body_view_frommodal(event, '<?=filter_html_js_str($view['ctrl'], $config['charset'])?>', <?=filter_html_js_special($view['values']['id'], $config['charset'])?>);" title="<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_VIEW, $config['charset'])?> <?=filter_html(ucfirst($meta['viewname']), $config['charset'])?>">
					<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_VIEW, $config['charset'])?> <?=filter_html(ucfirst($meta['viewname']), $config['charset'])?>
				</a>
		<?php else: ?>
				<?php if (in_array($field, $config['rich_text'])): ?>
					<script type="text/javascript">
						tinyMCE.init({
							selector: '#<?=filter_js_special($field, $config['charset'])?>',
							mode : "textareas",
							theme : "advanced",
							readonly: true
						});
					</script>
				<?php endif; ?>
				<textarea id="<?=filter_html_special($field, $config['charset'])?>" name="<?=filter_html($field, $config['charset'])?>"><?=filter_html($view['values'][$field], $config['charset'])?></textarea>
		<?php endif; ?>
	<?php else: ?>
		<?php if ($field == 'id'): ?>
				<a href="<?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($view['ctrl'], $config['charset'])?>/view/<?=filter_html($view['values'][$field], $config['charset'])?>" onclick="ndphp.ajax.load_body_view<?=isset($config['modalbox']) ? '_frommodal' : NULL;?>(event, '<?=filter_html_js_str($view['ctrl'], $config['charset'])?>', <?=filter_html_js_special($view['values'][$field], $config['charset'])?>);" title="<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_VIEW, $config['charset'])?> <?=filter_html($view['values'][$field], $config['charset'])?>">
					<?=filter_html($view['values'][$field], $config['charset'])?>
				</a>
		<?php else: ?>
				<?=filter_html($view['values'][$field], $config['charset'])?>
		<?php endif; ?>
	<?php endif; ?>

	</td>

<?php $i ++; endforeach; ?>
