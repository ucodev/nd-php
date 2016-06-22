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
<div id="search" class="search">
	<?php $tabs_searches = true; include($view['base_dir'] . '/_default/lib/tabs_header.php'); ?>

	<!-- Begin of Advanced Search -->
	<div id="search_advanced">
		<form action="<?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($view['ctrl'], $config['charset'])?>/result" name="advsearchform" id="advsearchform" method="post">
			<div class="search_criteria_fields">
				<fieldset class="search_criteria_fieldset">
				<legend class="search_criteria_legend"><?=filter_html(NDPHP_LANG_MOD_BLOCK_SEARCH_ADV_CRITERIA, $config['charset'])?></legend>
				<div class="search_criteria_fields_inner">
				<?php foreach ($view['fields'] as $field => $meta): ?>
					<?php
						/* Ignore fields without meta data */
						if (!isset($view['fields'][$field]))
							continue;

						/* Ignore hidden fields */
						if (in_array($field, $config['hidden_fields']))
							continue;

						/* Ignore separators */
						if ($view['fields'][$field]['type'] == 'separator')
							continue;
					?>
						<div class="search_criteria_field">
							<table class="search_criteria_field_table">
								<tr class="search_criteria_field_table">
									<td class="search_criteria_field_table">
										<div class="search_criteria_label">
											<?=filter_html(ucfirst($meta['viewname']), $config['charset'])?>
										</div>
									</td>
									<td class="search_criteria_field_table">
										<div class="search_criteria_checkbox_<?=filter_html_special($field, $config['charset'])?>">
											<input id="search_criteria_checkbox_<?=filter_html_special($field, $config['charset'])?>" name="criteria_<?=filter_html($field, $config['charset'])?>" alt="<?=filter_html(ucfirst($meta['viewname']), $config['charset'])?>" type="checkbox" value="1">
											<label for="search_criteria_checkbox_<?=filter_html_special($field, $config['charset'])?>"></label>
										</div>
									</td>
								</tr>
							</table>
						</div>
				<?php endforeach; ?>
				</div>
				</fieldset>		
			</div>
			<div class="search_fields">
				<fieldset class="search_fields_fieldset">
				<legend class="search_fields_legend"><?=filter_html(NDPHP_LANG_MOD_BLOCK_SEARCH_ADV_CONDITIONS, $config['charset'])?></legend>
				<div class="search_fields_inner">
				<?php $i = 1; foreach ($view['fields'] as $field => $meta): ?>
					<?php
						/* Ignore fields without meta data */
						if (!isset($view['fields'][$field]))
							continue;

						/* Ignore hidden fields */
						if (in_array($field, $config['hidden_fields']))
							continue;

						/* Ignore separators */
						if ($view['fields'][$field]['type'] == 'separator')
							continue;
					?>

					<?php if ($meta['input_type'] == 'checkbox'): ?>
							<div id="search_field_<?=filter_html_special($field, $config['charset'])?>" style="display: inline-block; margin: 20px;">
								<table class="search_field">
									<tr class="search_field">
										<td class="search_field_name"><?=filter_html(ucfirst($meta['viewname']), $config['charset'])?></td>
										<td class="search_field_value">
											<input name="<?=filter_html($field, $config['charset'])?>" alt="<?=filter_html(ucfirst($meta['viewname']), $config['charset'])?>" type="checkbox" value="1" />
										</td>
									</tr>
								</table>
							</div>
					<?php elseif ((($meta['input_type'] == 'text') || ($meta['input_type'] == 'file') || ($meta['type'] == 'mixed')) && ($meta['type'] != 'date') && ($meta['type'] != 'time') && ($meta['type'] != 'datetime') && ($meta['type'] != 'timer')): ?>
						<div id="search_field_<?=filter_html_special($field, $config['charset'])?>" style="display: inline-block; margin: 20px;">
							<table class="search_field">
								<tr class="search_field">
									<td class="search_field_name"><?=filter_html(ucfirst($meta['viewname']), $config['charset'])?></td>
									<td class="search_field_value">
										<input name="<?=filter_html($field, $config['charset'])?>" alt="<?=filter_html(ucfirst($meta['viewname']), $config['charset'])?>" type="text" />
										<div id="search_cond_button_field_<?=filter_html_special($field, $config['charset'])?>">
											<a href="javascript:search_expand_options('search_cond_field_<?=filter_html_js_special($field, $config['charset'])?>', 'search_cond_button_field_<?=filter_html_js_str($field, $config['charset'])?>');" title="<?=filter_html(NDPHP_LANG_MOD_SEARCH_OPT_MORE, $config['charset'])?>..." class="search_options_link">
												+
											</a>
										</div>
										<div id="search_cond_field_<?=filter_html_special($field, $config['charset'])?>">
											<input name="<?=filter_html($field, $config['charset'])?>_exact" alt="<?=filter_html(NDPHP_LANG_MOD_SEARCH_OPT_EXACT, $config['charset'])?>" type="checkbox" value="1" /> <?=filter_html(NDPHP_LANG_MOD_SEARCH_OPT_EXACT, $config['charset'])?>
											<input name="<?=filter_html($field, $config['charset'])?>_diff" alt="<?=filter_html(NDPHP_LANG_MOD_SEARCH_OPT_DIFF, $config['charset'])?>" type="checkbox" value="1" /> <?=filter_html(NDPHP_LANG_MOD_SEARCH_OPT_DIFF, $config['charset'])?>
										</div>
									</td>
								</tr>
							</table>
						</div>
					<?php elseif ($meta['input_type'] == 'textarea'): ?>
						<div id="search_field_<?=filter_html_special($field, $config['charset'])?>" style="display: inline-block; margin: 20px;">
							<table class="search_field">
								<tr class="search_field">
									<td class="search_field_name"><?=filter_html(ucfirst($meta['viewname']), $config['charset'])?></td>
									<td class="search_field_value">
										<textarea rows="6" cols="28" name="<?=filter_html($field, $config['charset'])?>" alt="<?=filter_html(ucfirst($meta['viewname']), $config['charset'])?>"></textarea>
										<div id="search_cond_button_field_<?=filter_html_special($field, $config['charset'])?>">
											<a href="javascript:search_expand_options('search_cond_field_<?=filter_html_js_special($field, $config['charset'])?>', 'search_cond_button_field_<?=filter_html_js_special($field, $config['charset'])?>');" title="<?=filter_html(NDPHP_LANG_MOD_SEARCH_OPT_MORE, $config['charset'])?>..." class="search_options_link">
												+
											</a>
										</div>
										<div id="search_cond_field_<?=filter_html_special($field, $config['charset'])?>">
											<input name="<?=filter_html($field, $config['charset'])?>_exact" alt="<?=filter_html(NDPHP_LANG_MOD_SEARCH_OPT_EXACT, $config['charset'])?>" type="checkbox" value="1" /> <?=filter_html(NDPHP_LANG_MOD_SEARCH_OPT_EXACT, $config['charset'])?>
											<input name="<?=filter_html($field, $config['charset'])?>_diff" alt="<?=filter_html(NDPHP_LANG_MOD_SEARCH_OPT_DIFF, $config['charset'])?>" type="checkbox" value="1" /> <?=filter_html(NDPHP_LANG_MOD_SEARCH_OPT_DIFF, $config['charset'])?>
										</div>
									</td>
								</tr>
							</table>
						</div>
					<?php elseif (($meta['input_type'] == 'number') || ($meta['type'] == 'date') || ($meta['type'] == 'time') || ($meta['type'] == 'datetime')): ?>
						<div id="search_field_<?=filter_html_special($field, $config['charset'])?>" style="display: inline-block; margin: 20px;">
							<table class="search_field">
								<tr class="search_field">
									<td class="search_field_name"><?=filter_html(ucfirst($meta['viewname']), $config['charset'])?></td>
									<td class="search_field_value">
										<input id="<?=filter_html_special($field, $config['charset'])?>_from" alt="<?=filter_html(ucfirst($meta['viewname']), $config['charset'])?>" name="<?=filter_html($field, $config['charset'])?>" type="<?php echo($meta['input_type'] == 'timer' ? 'text' : filter_html($meta['input_type'], $config['charset'])); ?>" <?php if (($meta['type'] == 'datetime') || ($meta['type'] == 'date')) { echo('placeholder="YYYY-MM-DD"'); } else if ($meta['type'] == 'time') { echo('placeholder="HH:MM:SS"'); } ?> />
										<?php if ($meta['type'] == 'datetime'): ?>
											<input id="<?=filter_html_special($field, $config['charset'])?>_from_time" alt="<?=filter_html(ucfirst($meta['viewname']), $config['charset'])?>" name="<?=filter_html($field, $config['charset'])?>_time" type="<?=filter_html($meta['input_type'], $config['charset'])?>" placeholder="HH:MM:SS" />
											<input name="<?=filter_html($field, $config['charset'])?>_custom" type="text" placeholder="<?=filter_html(NDPHP_LANG_MOD_SEARCH_OPT_CUSTOM, $config['charset'])?>" />
										<?php elseif ($meta['type'] == 'date'): ?>
											<input name="<?=filter_html($field, $config['charset'])?>_custom" type="text" placeholder="<?=filter_html(NDPHP_LANG_MOD_SEARCH_OPT_CUSTOM, $config['charset'])?>" />
										<?php elseif ($meta['type'] == 'time'): ?>
											<input name="<?=filter_html($field, $config['charset'])?>_custom" type="text" placeholder="<?=filter_html(NDPHP_LANG_MOD_SEARCH_OPT_CUSTOM, $config['charset'])?>" />
										<?php endif; ?>
										<div id="search_cond_button_field_<?=filter_html_special($field, $config['charset'])?>"> 
											<a href="javascript:search_expand_options('search_cond_field_<?=filter_html_js_special($field, $config['charset'])?>', 'search_cond_button_field_<?=filter_html_js_special($field, $config['charset'])?>');" title="<?=filter_html(NDPHP_LANG_MOD_SEARCH_OPT_MORE, $config['charset'])?>..." class="search_options_link">
												+
											</a>
										</div>
										<div id="search_cond_field_<?=filter_html_special($field, $config['charset'])?>">
											<br />
											<input name="<?=filter_html($field, $config['charset'])?>_cond" alt="<?=filter_html(NDPHP_LANG_MOD_SEARCH_OPT_DIFF, $config['charset'])?>" type="radio" value="!=" /> <?=filter_html(NDPHP_LANG_MOD_SEARCH_OPT_DIFF, $config['charset'])?>
											<input name="<?=filter_html($field, $config['charset'])?>_cond" alt="<?=filter_html(NDPHP_LANG_MOD_SEARCH_OPT_LESSER, $config['charset'])?>" type="radio" value="&lt;" /> <?=filter_html(NDPHP_LANG_MOD_SEARCH_OPT_LESSER, $config['charset'])?>
											<input name="<?=filter_html($field, $config['charset'])?>_cond" alt="<?=filter_html(NDPHP_LANG_MOD_SEARCH_OPT_EQUAL, $config['charset'])?>" type="radio" value="=" checked="checked" /> <?=filter_html(NDPHP_LANG_MOD_SEARCH_OPT_EQUAL, $config['charset'])?>
											<input name="<?=filter_html($field, $config['charset'])?>_cond" alt="<?=filter_html(NDPHP_LANG_MOD_SEARCH_OPT_GREATER, $config['charset'])?>" type="radio" value="&gt;" /> <?=filter_html(NDPHP_LANG_MOD_SEARCH_OPT_GREATER, $config['charset'])?>
											<input name="<?=filter_html($field, $config['charset'])?>_cond" alt="<?=filter_html(NDPHP_LANG_MOD_SEARCH_OPT_BETWEEN, $config['charset'])?>" type="radio" value="&gt;&lt;" /> <?=filter_html(NDPHP_LANG_MOD_SEARCH_OPT_BETWEEN, $config['charset'])?>
											<br />
											<br />
											<input id="<?=filter_html_special($field, $config['charset'])?>_to" alt="<?=filter_html(ucfirst($meta['viewname']), $config['charset'])?>" name="<?=filter_html($field, $config['charset'])?>_to" type="<?php echo((($meta['type'] == 'date') || ($meta['type'] == 'time') || ($meta['type'] == 'datetime')) ? "text" : "number"); ?>" <?php if (($meta['type'] == 'datetime') || ($meta['type'] == 'date')) { echo('placeholder="YYYY-MM-DD"'); } else if ($meta['type'] == 'time') { echo('placeholder="HH:MM:SS"'); } ?> />
											<?php if ($meta['type'] == 'datetime'): ?>
												<input id="<?=filter_html_special($field, $config['charset'])?>_to_time" name="<?=filter_html($field, $config['charset'])?>_to_time" type="<?=filter_html($meta['input_type'], $config['charset'])?>" placeholder="HH:MM:SS" />
												<input name="<?=filter_html($field, $config['charset'])?>_to_custom" type="text" placeholder="<?=filter_html(NDPHP_LANG_MOD_SEARCH_OPT_CUSTOM, $config['charset'])?>" />
											<?php endif;?>
											<?php if ($meta['type'] == 'date'): ?>
												<input name="<?=filter_html($field, $config['charset'])?>_to_custom" type="text" placeholder="<?=filter_html(NDPHP_LANG_MOD_SEARCH_OPT_CUSTOM, $config['charset'])?>" />
											<?php endif; ?>
											<?php if ($meta['type'] == 'time'): ?>
												<input name="<?=filter_html($field, $config['charset'])?>_to_custom" type="text" placeholder="<?=filter_html(NDPHP_LANG_MOD_SEARCH_OPT_CUSTOM, $config['charset'])?>" />
											<?php endif; ?>
										</div>
									</td>
								</tr>
							</table>
						</div>
					<?php elseif ($meta['input_type'] == 'select'): ?>
						<div id="search_field_<?=filter_html_special($field, $config['charset'])?>" style="display: inline-block; margin: 20px;">
							<table class="search_field">
								<tr class="search_field">
									<td class="search_field_name"><?=filter_html(ucfirst($meta['viewname']), $config['charset'])?></td>
									<td class="search_field_value">
										<select name="<?=filter_html($field, $config['charset'])?>[]" multiple="multiple">
											<?php foreach ($meta['options'] as $opt_id => $opt_value): ?>
												<option value="<?=filter_html($opt_id, $config['charset'])?>"><?=filter_html($opt_value, $config['charset'])?></option>
											<?php endforeach; ?>
										</select>
									</td>
								</tr>
							</table>
						</div>
					<?php else: ?>
						<div id="search_field_<?=filter_html_special($field, $config['charset'])?>" style="display: inline-block; margin: 20px;">
							<table class="search_field">
								<tr class="search_field">
									<td class="search_field_name"><?=filter_html(ucfirst($meta['viewname']), $config['charset'])?></td>
									<td class="search_field_value">
										<input name="<?=filter_html($field, $config['charset'])?>" alt="<?=filter_html(ucfirst($meta['viewname']), $config['charset'])?>" type="<?=filter_html($meta['input_type'], $config['charset'])?>" />
									</td>
								</tr>
							</table>
						</div>
					<?php endif; ?>
				<?php $i ++; endforeach; ?>
				</div>
				</fieldset>
			</div>
			<div class="search_result_fields">
				<fieldset class="search_result_fieldset">
				<legend class="search_result_legend"><?=filter_html(NDPHP_LANG_MOD_BLOCK_SEARCH_ADV_RESULT, $config['charset'])?></legend>
				<div class="search_result_fields_inner">
				<?php foreach ($view['fields'] as $field => $meta): ?>
					<?php
						/* Ignore fields without meta data */
						if (!isset($view['fields'][$field]))
							continue;

						/* Ignore hidden fields */
						if (in_array($field, $config['hidden_fields']))
							continue;

						/* Ignore separators */
						if ($view['fields'][$field]['type'] == 'separator')
							continue;
					?>
						<div class="search_result_field">
							<table class="search_result_field_table">
								<tr class="search_result_field_table">
									<td class="search_result_field_table">
										<div class="search_result_label">
											<?=filter_html(ucfirst($meta['viewname']), $config['charset'])?>
										</div>
									</td>
									<td class="search_result_field_table">
										<div class="search_result_checkbox_<?=filter_html_special($field, $config['charset'])?>">
											<input id="search_result_checkbox_<?=filter_html_special($field, $config['charset'])?>" alt="<?=filter_html(ucfirst($meta['viewname']), $config['charset'])?>" name="result_<?=filter_html($field, $config['charset'])?>" type="checkbox" value="1" checked="checked">
											<label for="search_result_checkbox_<?=filter_html_special($field, $config['charset'])?>"></label>
										</div>
									</td>
								</tr>
							</table>
						</div>
				<?php endforeach; ?>
				</div>
				</fieldset>
			</div>
			<div class="search_ops">
				<button id="op_submit" type="submit" value="<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_SEARCH, $config['charset'])?>" title="<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_SEARCH, $config['charset'])?>" class="context_menu_button">
					<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_SEARCH, $config['charset'])?>
				</button>
				<a href="<?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($view['ctrl'], $config['charset'])?>/list_default" onclick="ndphp.form.cancel_adv_search(event, '<?=filter_html_js_str($view['ctrl'], $config['charset'])?>');" title="<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_CANCEL, $config['charset'])?>" class="context_menu_link">
					<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_CANCEL, $config['charset'])?>
				</a>
			</div>
		</form>
	</div> <!-- End of Advanced Search -->
	<!-- Begin of Saved Searches -->
	<div id="search_saved">
		<?php if (count($view['saved_searches'])): ?>
			<?php foreach ($view['saved_searches'] as $search): ?>
				<div id="search_saved_<?=filter_html_special($search['id'], $config['charset'])?>" class="search_saved_block">
					<span class="search_saved_link">
						<a href="javascript:void(0);" onclick="ndphp.ajax.load_body_search_saved_result_query_uri(event, '<?=filter_html_js_str($view['ctrl'], $config['charset'])?>', '<?=filter_html_js_str($search['search_name'], $config['charset'])?> - <?=filter_html_js_str($search['description'], $config['charset'])?>', '<?=filter_html_js_str($search['result_query'], $config['charset'])?>');" title="<?=filter_html($search['search_name'], $config['charset'])?>" class="search_saved_link">
							<?=filter_html($search['search_name'], $config['charset'])?> - <?=filter_html($search['description'], $config['charset'])?>
						</a>
					</span>
					<span class="saved_search_ops">
						<a href="javascript:void(0);" onclick="ndphp.form.remove_search_save(event, '<?=filter_html_js_str($view['ctrl'], $config['charset'])?>', '<?=filter_html_js_str($search['id'], $config['charset'])?>');" class="saved_search_op_remove" title="<?=filter_html(NDPHP_LANG_MOD_OP_REMOVE, $config['charset'])?>">
							<img height="20" width="20" class="search_saved_op_icon" alt="<?=filter_html(NDPHP_LANG_MOD_OP_REMOVE, $config['charset'])?>" src="<?=filter_html(static_images_url(), $config['charset'])?>/themes/<?=filter_html($config['theme']['name'], $config['charset'])?>/icons/quick_remove.png" />
						</a>
					</span>
				</div>
			<?php endforeach; ?>
		<?php else: ?>
			<p class="no_searches"><?=filter_html(NDPHP_LANG_MOD_EMPTY_SEARCHES, $config['charset'])?></p>
		<?php endif; ?>
	</div>

	<?php include($view['base_dir'] . '/_default/lib/tabs_footer.php'); ?>
</div>