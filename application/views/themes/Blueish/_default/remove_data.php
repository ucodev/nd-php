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
<div id="remove" class="remove">
	<?php $row = array_values($view['result_array'])[0]; ?>

	<?php if (!count($row)): ?>

		<form class="form-horizontal" onsubmit="return false;">
			<div>
				<fieldset class="form_fieldset">
					<div class="form-group">
						<label for="entry_id" class="col-sm-3 control-label">
							<?=filter_html(NDPHP_LANG_MOD_COMMON_ENTRY_ID, $config['charset'])?>
						</label>
						<div class="col-sm-6">
							<input class="form-control" id="entry_id" alt="<?=filter_html(NDPHP_LANG_MOD_COMMON_ENTRY_ID, $config['charset'])?>" name="entry_id" type="text" autofocus accesskey="<?=filter_html(NDPHP_LANG_MOD_OP_ACCESS_KEY_AUTOFOCUS, $config['charset'])?>" />
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-6 col-sm-offset-3">
							<a class="btn btn-primary" href="javascript:void(0);" onclick="ndphp.ajax.load_body_op_id(event, '<?=filter_html_js_str($view['ctrl'], $config['charset'])?>', 'remove', jQuery('#entry_id').val());" title="<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_DELETE, $config['charset'])?>">
								<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_DELETE, $config['charset'])?>
							</a>
							<a class="btn btn-default" href="javascript:void(0);" onclick="ndphp.ajax.load_body_op(event, '<?=filter_html_js_str($view['ctrl'], $config['charset'])?>', 'list');" title="<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_CANCEL, $config['charset'])?>">
								<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_CANCEL, $config['charset'])?>
							</a>
						</div>
					</div>
				</fieldset>
			</div>
		</form>

	<?php else: ?>

		<?php include($view['base_dir'] . '/_default/lib/tabs_header.php'); ?>

		<?php $choices_remove = true; include($view['base_dir'] . '/_default/lib/choices.php'); ?>

		<form class="form-horizontal" action="<?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($view['ctrl'], $config['charset'])?>/delete" name="removeform" id="removeform" method="post">

			<?php if (file_exists($view['base_dir'] . '/' . $view['ctrl'] . '/remove_data_custom_header.php')) { include($view['base_dir'] . '/' . $view['ctrl'] . '/remove_data_custom_header.php'); } ?>

			<div class="tab-content">
				<div class="alert alert-danger text-center">
					Are you sure you want to remove this entry?
				</div>
				<!-- <h2 class="crud_warning col-lg-offset-3">Are you sure you want to remove this entry?</h2>-->
				<input type="hidden" name="id" value="<?=filter_html($view['id'], $config['charset'])?>" />
				<!-- Begin of basic fields -->
				<div class="tab-pane fade active in" id="fields_basic">
					<fieldset class="form_fieldset">
						<table class="table table-striped table-hover">
						<?php $i = 0; foreach ($row as $field => $value): ?>
							<?php
								/* Ignore fields without meta data */
								if (!isset($view['fields'][$field]))
									continue;

								/* Ignore hidden fields */
								if (in_array($field, $config['hidden_fields']))
									continue;

								/* If this is a separator, we need to close the current table, fieldset and div and create new ones */
								if ($view['fields'][$field]['type'] == 'separator'):
							?>
						</table>
					</fieldset>
				</div>
				<div class="tab-pane fade" id="fields_<?=filter_html_special($field, $config['charset'])?>_container">
					<fieldset class="form_fieldset">
						<table class="table table-striped table-hover">
							<?php
									$i = 0;
									continue;
								endif;
							?>
								<tr id="<?=filter_html($field, $config['charset'])?>_row">
									<td class="col-md-3 text-right">
										<strong><?=filter_html(ucfirst($view['fields'][$field]['viewname']), $config['charset'])?></strong>
									</td>
									<td class="col-md-6 text-left">
									<?php if ($view['fields'][$field]['input_type'] == 'checkbox'): ?>
											<?=($value == 1 ? filter_html(NDPHP_LANG_MOD_STATUS_CHECKBOX_CHECKED, $config['charset']) : filter_html(NDPHP_LANG_MOD_STATUS_CHECKBOX_UNCHECKED, $config['charset']))?>
									<?php elseif ($view['fields'][$field]['input_type'] == 'textarea'): ?>
										<?php if (isset($config['modalbox']) && in_array($field, $config['rich_text'])): ?>
												<a
													class="btn btn-primary"
													href="<?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($view['ctrl'], $config['charset'])?>/view/<?=filter_html($view['id'], $config['charset'])?>"
													onclick="ndphp.ajax.load_body_view_frommodal(event, '<?=filter_html_js_str($view['ctrl'], $config['charset'])?>', <?=filter_html_js_special($view['id'], $config['charset'])?>);"
													title="<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_VIEW, $config['charset'])?> <?=filter_html(ucfirst($view['fields'][$field]['viewname']), $config['charset'])?>"
												>
													<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_VIEW, $config['charset'])?> <?=filter_html(ucfirst($view['fields'][$field]['viewname']), $config['charset'])?>
												</a>
										<?php else: ?>
												<textarea id="<?=filter_html_special($field, $config['charset'])?>" name="<?=filter_html($field, $config['charset'])?>"><?=filter_html($value, $config['charset'])?></textarea>
										<?php endif; ?>
									<?php elseif ($view['fields'][$field]['input_type'] == 'select'): ?>
										<?php foreach ($view['fields'][$field]['options'] as $opt_id => $opt_value): ?>
											<?php if ($opt_id == $value): ?>
													<a
														href="<?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($view['fields'][$field]['table'], $config['charset'])?>/view_data_modalbox/<?=filter_html($opt_id, $config['charset'])?>"
														title="<?=filter_html(NDPHP_LANG_MOD_OP_QUICK_VIEW, $config['charset'])?>"
														onclick="ndphp.modal.show(this.href, '<?=filter_html_js_special(NDPHP_LANG_MOD_OP_QUICK_VIEW, $config['charset'])?>'); return false;"
													>
														<?=filter_html($opt_value, $config['charset'])?>
													</a>
											<?php
													/* The following hidden input is used to retrieve the current field opt id in order to correctly process
													 * conditional choices on selected_<?=$view['ctrl']?>_remove_choice_<?=$rel_field?>() JavaScript Function.
													 */ 
											?>
													<input type="hidden" id="<?=filter_html_special($field, $config['charset'])?>" value="<?=filter_html($opt_id, $config['charset'])?>" />
											<?php endif; ?>
										<?php endforeach; ?>
									<?php elseif ($view['fields'][$field]['input_type'] == 'file'): ?>
											<a
												target="_blank"
												title="<?=filter_html($value['name'], $config['charset'])?>"
												href="<?=filter_html($value['url'], $config['charset'])?>"
											>
												<?php if ($config['render']['images'] && in_array(end(explode('.', $value['name'])), $config['render']['ext'])): ?>
													<img
														alt="<?=filter_html($value['name'], $config['charset'])?>"
														style="width: <?=filter_html($config['render']['size']['width'], $config['charset'])?>; height: <?=filter_html($config['render']['size']['height'], $config['charset'])?>;"
														src="<?=filter_html($value['url'], $config['charset'])?>"
													/>
												<?php else: ?>
													<?=filter_html($value, $config['charset'])?>
												<?php endif; ?>
											</a>
									<?php else: ?>
										<?php if ($field == 'id'): ?>
											<?php if (isset($config['modalbox'])): ?>
													<a
														href="<?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($view['ctrl'], $config['charset'])?>/view/<?=filter_html($value, $config['charset'])?>"
														onclick="ndphp.ajax.load_body_view_frommodal(event, '<?=filter_html_js_str($view['ctrl'], $config['charset'])?>', <?=filter_html_js_special($value, $config['charset'])?>);"
														title="<?=filter_html(NDPHP_LANG_MOD_OP_VIEW, $config['charset'])?> <?=filter_html($value, $config['charset'])?>"
													>
														<?=filter_html($value, $config['charset'])?>
													</a>
											<?php else: ?>
													<a
														href="<?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($view['ctrl'], $config['charset'])?>/view/<?=filter_html($value, $config['charset'])?>"
														onclick="ndphp.ajax.load_body_view(event, '<?=filter_html_js_str($view['ctrl'], $config['charset'])?>', <?=filter_html_js_special($value, $config['charset'])?>);"
														title="<?=filter_html(NDPHP_LANG_MOD_OP_VIEW, $config['charset'])?> <?=filter_html($value, $config['charset'])?>"
													>
														<?=filter_html($value, $config['charset'])?>
													</a>
											<?php endif; ?>
										<?php else: ?>
											<?php if ($view['fields'][$field]['units']['unit'] && $view['fields'][$field]['units']['left']): ?>
												<?=filter_html($view['fields'][$field]['units']['unit'], $config['charset'])?>
											<?php endif; ?>
											<?=truncate_str($value, $config['truncate']['length'], $config['charset'], $config['truncate']['trail'], $config['truncate']['separator'])?>
											<?php if ($view['fields'][$field]['units']['unit'] && !$view['fields'][$field]['units']['left']): ?>
												<?=filter_html($view['fields'][$field]['units']['unit'], $config['charset'])?>
											<?php endif; ?>
										<?php endif; ?>
									<?php endif; ?>
									</td>
								</tr>
						<?php $i ++; endforeach; ?>
						</table>
					</fieldset>
				</div>
				<!-- End of basic fields -->
				<!-- Begin of Multiple relationships -->
				<?php include($view['base_dir'] . '/_default/lib/multiple_remove.php'); ?>
				<!-- End of Multiple relationships -->
				<!-- Begin of Mixed relationships -->
				<?php include($view['base_dir'] . '/_default/lib/mixed_remove.php'); ?>
				<!-- End of Mixed relationships -->
			</div>

			<?php if (file_exists($view['base_dir'] . '/' . $view['ctrl'] . '/remove_data_custom_footer.php')) { include($view['base_dir'] . '/' . $view['ctrl'] . '/remove_data_custom_footer.php'); } ?>

			<div class="form-group">
				<div class="col-sm-5 col-sm-offset-<?=isset($config['modalbox']) ? '4' : '5'?>" style="padding-top: 25px;">
					<button
						class="btn btn-danger"
						id="op_submit"
						type="submit"
						value="<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_DELETE, $config['charset'])?>"
						title="<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_DELETE, $config['charset'])?>"
					>
						<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_DELETE, $config['charset'])?>
					</button>
					<a
						class="btn btn-default"
						href="<?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($view['ctrl'], $config['charset'])?>/view/<?=filter_html($view['id'], $config['charset'])?>"
						onclick="ndphp.form.cancel_remove(event, '<?=filter_html_js_str($view['ctrl'], $config['charset'])?>', <?=isset($config['modalbox']) ? 1 : 0?>, '<?=filter_html_js_str($view['id'], $config['charset'])?>');"
						title="<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_CANCEL, $config['charset'])?>"
					>
						<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_CANCEL, $config['charset'])?>
					</a>
					<?php if (isset($config['modalbox'])): ?>
						<a
							class="btn btn-default"
							href="<?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($view['ctrl'], $config['charset'])?>/view/<?=filter_html($view['id'], $config['charset'])?>"
							onclick="ndphp.ajax.load_body_view_frommodal(event, '<?=filter_html_js_str($view['ctrl'], $config['charset'])?>', <?=filter_html_js_special($view['id'], $config['charset'])?>);"
							title="<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_EXPAND, $config['charset'])?>"
						>
							<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_EXPAND, $config['charset'])?>
						</a>
					<?php endif; ?>
				</div>
			</div>
		</form>

		<?php include($view['base_dir'] . '/_default/lib/tabs_footer.php'); ?>

	<?php endif; ?>
</div>
