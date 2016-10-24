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
<div id="edit" class="edit">
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
							<a class="btn btn-primary" href="javascript:void(0);" onclick="ndphp.ajax.load_body_op_id(event, '<?=filter_html_js_str($view['ctrl'], $config['charset'])?>', 'edit', jQuery('#entry_id').val());" title="<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_EDIT, $config['charset'])?>">
								<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_EDIT, $config['charset'])?>
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

		<?php $choices_edit = true; include($view['base_dir'] . '/_default/lib/choices.php'); ?>

		<form class="form-horizontal" action="<?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($view['ctrl'], $config['charset'])?>/update" id="editform-<?=filter_html($view['unique_id'], $config['charset'])?>" name="editform-<?=filter_html($view['unique_id'], $config['charset'])?>" enctype="multipart/form-data" method="post">

			<?php if (file_exists($view['base_dir'] . '/' . $view['ctrl'] . '/edit_data_custom_header.php')) { include($view['base_dir'] . '/' . $view['ctrl'] . '/edit_data_custom_header.php'); } ?>

			<input type="hidden" name="id" value="<?=filter_html($view['id'], $config['charset'])?>" />
			<div class="tab-content">
				<!-- Begin of basic fields -->
				<div class="tab-pane fade active in" id="fields_basic">
					<fieldset class="form_fieldset">
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
					</fieldset>
				</div>
				<div class="tab-pane fade" id="fields_<?=filter_html_special($field, $config['charset'])?>_container">
					<fieldset class="form_fieldset">
							<?php
									$i = 0; /* Reset the row index... we're entering a new tab */
									continue;
								endif; // $view['fields'][$field]['type'] == 'separator'
							?>

							<?php if ($view['fields'][$field]['input_type'] == 'checkbox'): ?>
									<div class="form-group <?=in_array($field, $view['required']) ? 'required' : ''?>" id="<?=filter_html($field, $config['charset'])?>_row">
										<label for="<?=filter_html($field, $config['charset'])?>" class="col-sm-3 control-label">
											<?=filter_html(ucfirst($view['fields'][$field]['viewname']), $config['charset'])?>
											<?php if ($view['fields'][$field]['help_desc'] != NULL): ?>
												<a href="<?=filter_html($view['fields'][$field]['help_url'], $config['charset'])?>" title="<?=filter_html($view['fields'][$field]['help_desc'], $config['charset'])?>">
													<img src="<?=filter_html(static_images_url(), $config['charset'])?>/themes/<?=filter_html($config['theme']['name'], $config['charset'])?>/icons/help_small.png" alt="<?=filter_html($view['fields'][$field]['help_desc'], $config['charset'])?>" />
												</a>
											<?php endif; ?>
										</label>
										<div class="col-sm-6">
											<input name="<?=filter_html($field, $config['charset'])?>" type="hidden" value="0" />
											<input
												id="<?=filter_html($field, $config['charset'])?>"
												alt="<?=filter_html(ucfirst($view['fields'][$field]['viewname']), $config['charset'])?> <?=in_array($field, $view['required']) ? '(' . filter_html(NDPHP_LANG_MOD_WORD_REQUIRED, $config['charset']) . ')' : ''?>"
												name="<?=filter_html($field, $config['charset'])?>" type="checkbox" value="1"
												<?php echo($value ? 'checked="checked"' : NULL); ?>
												<?php if (!security_perm_check($security['perms'], 'U', $view['ctrl'], $field)) { echo('disabled'); }?>
												<?=$i ? '' : 'autofocus accesskey="' . filter_html(NDPHP_LANG_MOD_OP_ACCESS_KEY_AUTOFOCUS, $config['charset']) . '"'?>
											/>
										</div>
									</div>
							<?php elseif ($view['fields'][$field]['input_type'] == 'select'): ?>
									<div class="form-group <?=in_array($field, $view['required']) ? 'required' : ''?>" id="<?=filter_html($field, $config['charset'])?>_row">
										<label for="<?=filter_html_special($field, $config['charset'])?>" class="col-sm-3 control-label">
											<?=filter_html(ucfirst($view['fields'][$field]['viewname']), $config['charset'])?>
											<?php if ($view['fields'][$field]['help_desc'] != NULL): ?>
												<a href="<?=filter_html($view['fields'][$field]['help_url'], $config['charset'])?>" title="<?=filter_html($view['fields'][$field]['help_desc'], $config['charset'])?>">
													<img src="<?=filter_html(static_images_url(), $config['charset'])?>/themes/<?=filter_html($config['theme']['name'], $config['charset'])?>/icons/help_small.png" alt="<?=filter_html($view['fields'][$field]['help_desc'], $config['charset'])?>" />
												</a>
											<?php endif; ?>
										</label>
										<div class="col-sm-6">
											<select
												class="form-control"
												id="<?=filter_html_special($field, $config['charset'])?>"
												name="<?=filter_html($field, $config['charset'])?>"
												<?php if ($config['choices'] && in_array($field, $choice_select)) { echo('onchange="selected_' . filter_html_js_special($view['ctrl'], $config['charset']) . '_edit_choice_' . filter_html_js_special($field, $config['charset']) . '();"'); }?>
												<?php if (!security_perm_check($security['perms'], 'U', $view['ctrl'], $field)) { echo('disabled'); }?>
												<?=$i ? '' : 'autofocus accesskey="' . filter_html(NDPHP_LANG_MOD_OP_ACCESS_KEY_AUTOFOCUS, $config['charset']) . '"'?>
											>
												<?php foreach ($view['fields'][$field]['options'] as $opt_id => $opt_value): ?>
													<option value="<?=filter_html($opt_id, $config['charset'])?>" <?php echo ($opt_id == $value ? 'selected="selected"' : NULL); ?> >
														<?=filter_html($opt_value, $config['charset'])?>
													</option>
												<?php endforeach; ?>
											</select>
										</div>
										<div class="col-sm-1">
											<?php if (!isset($config['modalbox'])): ?>
												<a
													class="btn btn-primary btn-sm"
													href="<?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($view['fields'][$field]['table'], $config['charset'])?>/create_data_modalbox"
													title="<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_NEW, $config['charset'])?> <?=filter_html(ucfirst($view['fields'][$field]['table']), $config['charset'])?>"
													onclick="ndphp.modal.show(this.href, '<?=filter_html_js_special(NDPHP_LANG_MOD_OP_CONTEXT_NEW, $config['charset'])?> <?=filter_html(ucfirst($view['fields'][$field]['table']), $config['charset'])?>'); return false;"
												>
													+
												</a>
											<?php endif; ?>
										</div>
									</div>
							<?php elseif ($view['fields'][$field]['input_type'] == 'timer'): ?>
									<div class="form-group <?=in_array($field, $view['required']) ? 'required' : ''?>" id="<?=filter_html($field, $config['charset'])?>_row">
										<label for="<?=filter_html_special($field, $config['charset'])?>" class="col-sm-3 control-label">
											<?=filter_html(ucfirst($view['fields'][$field]['viewname']), $config['charset'])?>
											<?php if ($view['fields'][$field]['help_desc'] != NULL): ?>
												<a href="<?=filter_html($view['fields'][$field]['help_url'], $config['charset'])?>" title="<?=filter_html($view['fields'][$field]['help_desc'], $config['charset'])?>">
													<img src="<?=filter_html(static_images_url(), $config['charset'])?>/themes/<?=filter_html($config['theme']['name'], $config['charset'])?>/icons/help_small.png" alt="<?=filter_html($view['fields'][$field]['help_desc'], $config['charset'])?>" />
												</a>
											<?php endif; ?>
										</label>
										<div class="col-sm-6">
											<input
												class="form-control"
												id="<?=filter_html_special($field, $config['charset'])?>"
												name="<?=filter_html($field, $config['charset'])?>"
												type="text" alt="<?=filter_html(ucfirst($view['fields'][$field]['viewname']), $config['charset'])?> <?=in_array($field, $view['required']) ? '(' . filter_html(NDPHP_LANG_MOD_WORD_REQUIRED, $config['charset']) . ')' : ''?>"
												value="<?=$value ? filter_html($value, $config['charset']) : '00:00:00'?>"
												<?php echo(in_array($field, $view['required']) ? 'required="required"' : NULL); ?>
												<?php if (!security_perm_check($security['perms'], 'U', $view['ctrl'], $field)) { echo('disabled'); }?>
												<?=$i ? '' : 'autofocus accesskey="' . filter_html(NDPHP_LANG_MOD_OP_ACCESS_KEY_AUTOFOCUS, $config['charset']) . '"'?>
											/>
											<input
												class="btn btn-primary btn-sm"
												type="button"
												name="button_<?=filter_html($field, $config['charset'])?>"
												value="<?=filter_html(NDPHP_LANG_MOD_OP_TIMER_START, $config['charset'])?>"
												onmouseup="jQuery('#<?=filter_html_special($field, $config['charset'])?>').timer('resume');"
												<?php if (!security_perm_check($security['perms'], 'U', $view['ctrl'], $field)) { echo('disabled'); }?>
											/>
											<input
												class="btn btn-cancel btn-sm"
												type="button"
												name="button_<?=filter_html($field, $config['charset'])?>"
												value="<?=filter_html(NDPHP_LANG_MOD_OP_TIMER_STOP, $config['charset'])?>"
												onmouseup="jQuery('#<?=filter_html_special($field, $config['charset'])?>').timer('pause');"
												<?php if (!security_perm_check($security['perms'], 'U', $view['ctrl'], $field)) { echo('disabled'); }?>
											/>
											<script type="text/javascript">
												jQuery("#<?=filter_js_special($field, $config['charset'])?>").timepicker();
											</script>
										</div>
									</div>
							<?php elseif ($view['fields'][$field]['input_type'] == 'file'): ?>
									<div class="form-group <?=in_array($field, $view['required']) ? 'required' : ''?>" id="<?=filter_html($field, $config['charset'])?>_row">
										<label for="<?=filter_html_special($field, $config['charset'])?>" class="col-sm-3 control-label">
											<?=filter_html(ucfirst($view['fields'][$field]['viewname']), $config['charset'])?>
											<?php if ($view['fields'][$field]['help_desc'] != NULL): ?>
												<a href="<?=filter_html($view['fields'][$field]['help_url'], $config['charset'])?>" title="<?=filter_html($view['fields'][$field]['help_desc'], $config['charset'])?>">
													<img src="<?=filter_html(static_images_url(), $config['charset'])?>/themes/<?=filter_html($config['theme']['name'], $config['charset'])?>/icons/help_small.png" alt="<?=filter_html($view['fields'][$field]['help_desc'], $config['charset'])?>" />
												</a>
											<?php endif; ?>
										</label>
										<div class="col-sm-6">
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
													<?=filter_html($value['name'], $config['charset'])?>
												<?php endif; ?>
											</a>
											<?php if ($value): ?>
												<br />
											<?php endif; ?>
											<input
												class="filestyle"
												data-buttonName="btn-primary"
												id="<?=filter_html_special($field, $config['charset'])?>"
												name="<?=filter_html($field, $config['charset'])?>"
												type="file"
												alt="<?=filter_html(ucfirst($view['fields'][$field]['viewname']), $config['charset'])?> <?=in_array($field, $view['required']) ? '(' . filter_html(NDPHP_LANG_MOD_WORD_REQUIRED, $config['charset']) . ')' : ''?>"
												<?php echo(in_array($field, $view['required']) ? 'required="required"' : NULL); ?>
												<?php if (!security_perm_check($security['perms'], 'U', $view['ctrl'], $field)) { echo('disabled'); }?>
												<?=$i ? '' : 'autofocus accesskey="' . filter_html(NDPHP_LANG_MOD_OP_ACCESS_KEY_AUTOFOCUS, $config['charset']) . '"'?>
											/>
											<?php if ($value): ?>
												<br />
												<input type="hidden" name="<?=filter_html($field, $config['charset'])?>_remove" value="0" />
												<input type="checkbox" name="<?=filter_html($field, $config['charset'])?>_remove" /> <span class="edit_remove_file"><?=filter_html(NDPHP_LANG_MOD_OP_REMOVE, $config['charset'])?></span>
											<?php endif; ?>
										</div>
									</div>
							<?php elseif ($view['fields'][$field]['input_type'] == 'textarea'): ?>
									<div class="form-group <?=in_array($field, $view['required']) ? 'required' : ''?>" id="<?=filter_html($field, $config['charset'])?>_row">
										<label for="<?=filter_html_special($field, $config['charset'])?>" class="col-sm-3 control-label">
											<?=filter_html(ucfirst($view['fields'][$field]['viewname']), $config['charset'])?>
											<?php if ($view['fields'][$field]['help_desc'] != NULL): ?>
												<a href="<?=filter_html($view['fields'][$field]['help_url'], $config['charset'])?>" title="<?=filter_html($view['fields'][$field]['help_desc'], $config['charset'])?>">
													<img src="<?=filter_html(static_images_url(), $config['charset'])?>/themes/<?=filter_html($config['theme']['name'], $config['charset'])?>/icons/help_small.png" alt="<?=filter_html($view['fields'][$field]['help_desc'], $config['charset'])?>" />
												</a>
											<?php endif; ?>
										</label>
										<div class="col-sm-6">
											<?php if (isset($config['modalbox']) && in_array($field, $config['rich_text'])): ?>
												<a
													class="btn btn-primary"
													href="javascript:void(0);"
													onclick="ndphp.ajax.load_body_edit_frommodal(event, '<?=filter_html_js_str($view['ctrl'], $config['charset'])?>', <?=filter_html_js_special($view['id'], $config['charset'])?>);"
													title="<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_EDIT, $config['charset'])?> <?=filter_html(ucfirst($view['fields'][$field]['viewname']), $config['charset'])?>"
												>
													<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_EDIT, $config['charset'])?> <?=filter_html(ucfirst($view['fields'][$field]['viewname']), $config['charset'])?>
												</a>
											<?php else: ?>
												<textarea
													class="form-control"
													id="<?=filter_html_special($field, $config['charset'])?>"
													name="<?=filter_html($field, $config['charset'])?>"
													alt="<?=filter_html(ucfirst($view['fields'][$field]['viewname']), $config['charset'])?> <?=in_array($field, $view['required']) ? '(' . filter_html(NDPHP_LANG_MOD_WORD_REQUIRED, $config['charset']) . ')' : ''?>"
													placeholder="<?=$view['fields'][$field]['placeholder'] ? filter_html($view['fields'][$field]['placeholder'], $config['charset']) : filter_html($view['default'][$field], $config['charset'])?>"
													<?php echo(in_array($field, $view['required']) ? 'required="required"' : NULL); ?>
													<?php if (!security_perm_check($security['perms'], 'U', $view['ctrl'], $field)) { echo('disabled'); }?>
													<?=$i ? '' : 'autofocus accesskey="' . filter_html(NDPHP_LANG_MOD_OP_ACCESS_KEY_AUTOFOCUS, $config['charset']) . '"'?>
												><?=filter_html($value, $config['charset'])?></textarea>
											<?php endif; ?>
										</div>
									</div>
							<?php else: ?>
									<div class="form-group <?=in_array($field, $view['required']) ? 'required' : ''?>" id="<?=filter_html($field, $config['charset'])?>_row">
										<label for="<?=filter_html_special($field, $config['charset'])?>" class="col-sm-3 control-label">
											<?=filter_html(ucfirst($view['fields'][$field]['viewname']), $config['charset'])?>
											<?php if ($view['fields'][$field]['help_desc'] != NULL): ?>
												<a href="<?=filter_html($view['fields'][$field]['help_url'], $config['charset'])?>" title="<?=filter_html($view['fields'][$field]['help_desc'], $config['charset'])?>">
													<img src="<?=filter_html(static_images_url(), $config['charset'])?>/themes/<?=filter_html($config['theme']['name'], $config['charset'])?>/icons/help_small.png" alt="<?=filter_html($view['fields'][$field]['help_desc'], $config['charset'])?>" />
												</a>
											<?php endif; ?>
										</label>
										<div class="col-sm-6">
											<?php if ($view['fields'][$field]['type'] == 'datetime') $_datetime = explode(' ', $value);?>
											<?php if ($view['fields'][$field]['units']['unit']): ?>
												<div class="input-group">
												<?php if ($view['fields'][$field]['units']['left']): ?>
													<span class="input-group-addon"><?=filter_html($view['fields'][$field]['units']['unit'], $config['charset'])?></span>
												<?php endif; ?>
											<?php endif; ?>
											<input
												class="form-control"
												id="<?=filter_html_special($field, $config['charset'])?>"
												name="<?=filter_html($field, $config['charset'])?>"
												alt="<?=filter_html(ucfirst($view['fields'][$field]['viewname']), $config['charset'])?> <?=in_array($field, $view['required']) ? '(' . filter_html(NDPHP_LANG_MOD_WORD_REQUIRED, $config['charset']) . ')' : ''?>"
												<?php if ($view['fields'][$field]['type'] == 'varchar') { echo('maxlength="' . filter_html($view['fields'][$field]['max_length'], $config['charset']) . '"'); } ?>
												type="<?php if (strstr($field, 'password')) { echo('password'); } else { echo(filter_html($view['fields'][$field]['input_type'], $config['charset'])); } ?>"
												value="<?=($view['fields'][$field]['type'] == 'datetime') ? filter_html($_datetime[0], $config['charset']) : filter_html($value, $config['charset'])?>"
												placeholder=
													<?php if (($view['fields'][$field]['type'] == 'datetime') || ($view['fields'][$field]['type'] == 'date')): ?>
														"YYYY-MM-DD"
													<?php elseif ($view['fields'][$field]['type'] == 'time'): ?>
														"HH:MM:SS"
													<?php elseif ($view['fields'][$field]['placeholder']): ?>
														"<?=filter_html($view['fields'][$field]['placeholder'], $config['charset'])?>"
													<?php else: ?>
														"<?=filter_html($view['default'][$field], $config['charset'])?>"
													<?php endif; ?>
												<?php echo(in_array($field, $view['required']) ? 'required="required"' : NULL); ?>
												<?php if (!security_perm_check($security['perms'], 'U', $view['ctrl'], $field)) { echo('disabled'); }?>
												<?=$view['fields'][$field]['input_pattern'] ? 'pattern="' . filter_html($view['fields'][$field]['input_pattern'], $config['charset']) . '"' : ''?>
												<?=$i ? '' : 'autofocus accesskey="' . filter_html(NDPHP_LANG_MOD_OP_ACCESS_KEY_AUTOFOCUS, $config['charset']) . '"'?>
											/>
											<?php if ($view['fields'][$field]['units']['unit']): ?>
												<?php if(!$view['fields'][$field]['units']['left']): ?>
													<span class="input-group-addon"><?=filter_html($view['fields'][$field]['units']['unit'], $config['charset'])?></span>
												<?php endif; ?>
												</div>
											<?php endif; ?>
											<?php if (strstr($field, 'password')): ?>
												<input
													class="form-control"
													id="password_verification_<?=filter_html_special($field, $config['charset'])?>"
													alt="<?=filter_html(NDPHP_LANG_MOD_COMMON_PASSWORD_REPEAT, $config['charset'])?> (<?=filter_html(NDPHP_LANG_MOD_WORD_REQUIRED, $config['charset'])?>)"
													type="password"
													value="<?=filter_html($value, $config['charset'])?>"
													required="required"
													<?php if (!security_perm_check($security['perms'], 'U', $view['ctrl'], $field)) { echo('disabled'); }?>
													placeholder="<?=filter_html(NDPHP_LANG_MOD_COMMON_PLACEHOLDER_REPEAT_PASSWORD, $config['charset'])?>"
												/>
											<?php endif; ?>
											<?php if ($view['fields'][$field]['type'] == 'datetime'): ?>
												<input
													class="form-control"
													id="<?=filter_html_special($field, $config['charset'])?>_time"
													name="<?=filter_html($field, $config['charset'])?>_time"
													alt="<?=filter_html(ucfirst($view['fields'][$field]['viewname']), $config['charset'])?> <?=in_array($field, $view['required']) ? '(' . filter_html(NDPHP_LANG_MOD_WORD_REQUIRED, $config['charset']) . ')' : ''?>"
													type="<?=filter_html($view['fields'][$field]['input_type'], $config['charset'])?>"
													value="<?=filter_html($_datetime[1], $config['charset'])?>"
													placeholder="HH:MM:SS"
													<?php echo(in_array($field, $view['required']) ? 'required="required"' : NULL); ?>
													<?php if (!security_perm_check($security['perms'], 'U', $view['ctrl'], $field)) { echo('disabled'); }?>
												/>
											<?php endif; ?>
										</div>
									</div>
							<?php endif; ?>
						<?php $i ++; endforeach; ?>
					</fieldset>
				</div>
				<!-- End of basic fields -->
				<!-- Begin of Multiple relationships -->
				<?php include($view['base_dir'] . '/_default/lib/multiple_edit.php'); ?>
				<!-- End of Multiple relationships -->
				<!-- Begin of Mixed relationships -->
				<?php include($view['base_dir'] . '/_default/lib/mixed_edit.php'); ?>
				<!-- End of Mixed relationships -->
			</div>

			<?php if (file_exists($view['base_dir'] . '/' . $view['ctrl'] . '/edit_data_custom_footer.php')) { include($view['base_dir'] . '/' . $view['ctrl'] . '/edit_data_custom_footer.php'); } ?>

			<div class="form-group">
				<div class="col-sm-5 col-sm-offset-<?=isset($config['modalbox']) ? '4' : '5'?>" style="padding-top: 25px;">
					<button
						class="btn btn-primary"
						id="op_submit"
						type="submit"
						value="<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_UPDATE, $config['charset'])?>"
						title="<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_UPDATE, $config['charset'])?>"
					>
						<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_UPDATE, $config['charset'])?>
					</button>
					<a
						class="btn btn-default"
						href="<?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($view['ctrl'], $config['charset'])?>/view/<?=filter_html($view['id'], $config['charset'])?>"
						onclick="ndphp.form.cancel_edit(event, '<?=filter_html_js_str($view['ctrl'], $config['charset'])?>', <?=isset($config['modalbox']) ? 1 : 0?>, '<?=filter_html_js_str($view['id'], $config['charset'])?>');"
						title="<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_CANCEL, $config['charset'])?>"
					>
						<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_CANCEL, $config['charset'])?>
					</a>
					<?php if (isset($config['modalbox'])): ?>
						<a
							class="btn btn-default"
							href="<?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($view['ctrl'], $config['charset'])?>/view/<?=filter_html($view['id'], $config['charset'])?>"
							onclick="ndphp.ajax.load_body_edit_frommodal(event, '<?=filter_html_js_str($view['ctrl'], $config['charset'])?>', <?=filter_html_js_special($view['id'], $config['charset'])?>);"
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
