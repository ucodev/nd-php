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

<?php
	foreach ($view['fields'] as $field => $meta):
		if ($meta['type'] == 'mixed'):
?>
			<script type="text/javascript">
				var mixed_item_<?=filter_js_special($meta['rel_table'], $config['charset'])?> = 1;
				var autocomplete_items_<?=filter_js_special($meta['rel_table'], $config['charset'])?> = [
				<?php if ($config['mixed']['autocomplete'] === true): ?>
					<?php
						$opt_count = 0;
						foreach ($meta['options'] as $opt_id => $opt_value): ?>
							<?php if ($opt_count) echo(','); ?>
							"<?=filter_html_js_str($opt_value, $config['charset'])?>"
					<?php
							$opt_count ++;
						endforeach;
					?>
				<?php endif; ?>
				];
				ndphp.mixed.new_item('<?=filter_js_str(base_url(), $config['charset'])?>', '<?=filter_js_str($view['ctrl'], $config['charset'])?>', autocomplete_items_<?=filter_js_special($meta['rel_table'], $config['charset'])?>, 'mixed_<?=filter_js_str($meta['rel_table'], $config['charset'])?>', mixed_item_<?=filter_js_special($meta['rel_table'], $config['charset'])?>, '<?=filter_js_str($meta['rel_table'], $config['charset'])?>', '<?=filter_js_str($meta['mixed_first_field'], $config['charset'])?>', '', <?=(($meta['mixed_type'] != 'single') ? 0 : 1)?>);
			</script>
			<div id="mixed_<?=filter_html_special($meta['rel_table'], $config['charset'])?>_container" style="display: table; margin: 0 auto; padding-top: 34px;">
				<fieldset class="create_mixed_fieldset">
					<legend class="create_mixed_legend">
						<?=filter_html(ucfirst($meta['viewname']), $config['charset'])?>
					</legend>

					<table id="mixed_<?=filter_html_special($meta['rel_table'], $config['charset'])?>" class="fields">
						<tr class="fields">
							<?php foreach ($meta['mixed_fields_alias'] as $mixed_field_alias): ?>
								<th class="fields"><?=filter_html(ucfirst($mixed_field_alias['alias']), $config['charset'])?> <?php if ($mixed_field_alias['help_desc'] != null): ?> <a href="<?=filter_html($mixed_field_alias['help_url'], $config['charset'])?>" title="<?=filter_html($mixed_field_alias['help_desc'], $config['charset'])?>"><img width="12" height="12" src="<?=filter_html(static_images_url(), $config['charset'])?>/themes/<?=filter_html($config['theme']['name'], $config['charset'])?>/icons/help_small.png" alt="<?=filter_html($mixed_field_alias['help_desc'], $config['charset'])?>" /></a><?php endif; ?></th>
							<?php endforeach; ?>
							<th class="fields">&nbsp;</th>
						</tr>
					</table>

					<div id="add_row_button_<?=filter_html_special($meta['rel_table'], $config['charset'])?>" class="more_op">
						<?php if ($meta['mixed_type'] != 'single'): ?>
							<a href="javascript:void(0);" class="more_op" onclick="mixed_item_<?=filter_js_special($meta['rel_table'], $config['charset'])?> ++; ndphp.mixed.new_item('<?=filter_html_js_str(base_url(), $config['charset'])?>', '<?=filter_html_js_str($view['ctrl'], $config['charset'])?>', autocomplete_items_<?=filter_js_special($meta['rel_table'], $config['charset'])?>, 'mixed_<?=filter_html_js_str($meta['rel_table'], $config['charset'])?>', mixed_item_<?=filter_js_special($meta['rel_table'], $config['charset'])?>, '<?=filter_html_js_str($meta['rel_table'], $config['charset'])?>', '<?=filter_html_js_str($meta['mixed_first_field'], $config['charset'])?>', '', 0);">
								<img class="more_op" alt="<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_NEW, $config['charset'])?> <?=filter_html(NDPHP_LANG_MOD_WORD_ROW, $config['charset'])?>" src="<?=filter_html(static_images_url(), $config['charset'])?>/themes/<?=filter_html($config['theme']['name'], $config['charset'])?>/icons/more.png" />
							</a>
						<?php endif; ?>
					</div>
				</fieldset>
			</div>
<?php
		endif;
	endforeach;
?>