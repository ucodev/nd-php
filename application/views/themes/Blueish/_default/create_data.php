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
<div id="create" class="create">
	<?php include($view['base_dir'] . '/_default/lib/tabs_header.php'); ?>

	<?php $choices_create = true; include($view['base_dir'] . '/_default/lib/choices.php'); ?>

	<?php $rel = array(); /* FIXME: We're populating the $rel in this view. This should be done by the controller. */ ?>

	<form class="form-horizontal" action="<?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($view['ctrl'], $config['charset'])?>/insert" id="createform-<?=filter_html($view['unique_id'], $config['charset'])?>" name="createform" enctype="multipart/form-data" method="post">

		<?php if (file_exists($view['base_dir'] . '/' . $view['ctrl'] . '/create_data_custom_header.php')) { include($view['base_dir'] . '/' . $view['ctrl'] . '/create_data_custom_header.php'); } ?>

		<div class="tab-content">
			<!-- Begin of basic fields -->
			<div class="tab-pane fade active in" id="fields_basic">
				<fieldset class="form_fieldset">
					<?php $i = 0; foreach ($view['fields'] as $field => $meta): ?>
						<?php
							/* Ignore fields without meta data */
							if (!isset($view['fields'][$field]))
								continue;

							/* Ignore hidden fields */
							if (in_array($field, $config['hidden_fields']))
								continue;

							/* If this is a separator, we need to close the current table, fieldset and div and create new ones */
							if ($meta['type'] == 'separator'):
						?>
				</fieldset>
			</div>
			<div class="tab-pane fade" id="fields_<?=filter_html_special($field, $config['charset'])?>_container">
				<fieldset class="form_fieldset">
						<?php
								$i = 0; /* Reset the row index... we're entering a new tab */
								continue;
							endif; /* $meta['type'] == 'separator' */
						?>

						<?php if ($meta['input_type'] == 'checkbox'): ?>
								<div class="form-group <?=in_array($field, $view['required']) ? 'required' : ''?>" id="<?=filter_html($field, $config['charset'])?>_row">
									<label for="<?=filter_html($field, $config['charset'])?>" class="col-sm-3 control-label">
										<?=filter_html(ucfirst($meta['viewname']), $config['charset'])?>
										<?php if ($meta['help_desc'] != NULL): ?>
											<a href="<?=filter_html($meta['help_url'], $config['charset'])?>" title="<?=filter_html($meta['help_desc'], $config['charset'])?>">
												<img src="<?=filter_html(static_images_url(), $config['charset'])?>/themes/<?=filter_html($config['theme']['name'], $config['charset'])?>/icons/help_small.png" alt="<?=filter_html($meta['help_desc'], $config['charset'])?>" />
											</a>
										<?php endif; ?>
									</label>
									<div class="col-sm-6">
										<input name="<?=filter_html($field, $config['charset'])?>" type="hidden" value="0" />
										<input
											id="<?=filter_html($field, $config['charset'])?>"
											alt="<?=filter_html(ucfirst($meta['viewname']), $config['charset'])?> <?=in_array($field, $view['required']) ? '(' . filter_html(NDPHP_LANG_MOD_WORD_REQUIRED, $config['charset']) . ')' : ''?>"
											name="<?=filter_html($field, $config['charset'])?>"
											type="checkbox"
											value="1"
											<?=$i ? '' : 'autofocus accesskey="' . filter_html(NDPHP_LANG_MOD_OP_ACCESS_KEY_AUTOFOCUS, $config['charset']) . '"'?>
											<?=isset($view['autocomplete'][$field]) && $view['autocomplete'][$field] == '1' ? 'checked' : ''?>
										/>
									</div>
								</div>
						<?php elseif ($meta['input_type'] == 'select'): ?>
							<?php
								if ($meta['type'] == 'rel') {
									/* Store the multiple relationship for later use on a separate div */
									array_push($rel, array(
										'field' => $field,
										'meta' => $meta,
									));

									continue;
								}
							?>
								<div class="form-group <?=in_array($field, $view['required']) ? 'required' : ''?>" id="<?=filter_html($field, $config['charset'])?>_row">
									<label for="<?=filter_html_special($field, $config['charset'])?>" class="col-sm-3 control-label">
										<?=filter_html(ucfirst($meta['viewname']), $config['charset'])?>
										<?php if ($meta['help_desc'] != NULL): ?>
											<a href="<?=filter_html($meta['help_url'], $config['charset'])?>" title="<?=filter_html($meta['help_desc'], $config['charset'])?>">
												<img src="<?=filter_html(static_images_url(), $config['charset'])?>/themes/<?=filter_html($config['theme']['name'], $config['charset'])?>/icons/help_small.png" alt="<?=filter_html($meta['help_desc'], $config['charset'])?>" />
											</a>
										<?php endif; ?>
									</label>
									<div class="col-sm-6">
										<select
											class="form-control"
											id="<?=filter_html_special($field, $config['charset'])?>"
											name="<?=filter_html($field, $config['charset'])?>"
											<?php if ($config['choices'] && in_array($field, $choice_select)) { echo('onchange="selected_' . filter_html_js_special($view['ctrl'], $config['charset']) . '_create_choice_' . filter_html_js_special($field, $config['charset']) . '();"'); }?>
											<?=$i ? '' : 'autofocus accesskey="' . filter_html(NDPHP_LANG_MOD_OP_ACCESS_KEY_AUTOFOCUS, $config['charset']) . '"'?>
										>
											<?php foreach ($meta['options'] as $opt_id => $opt_value): ?>
													<option
														<?=((isset($view['default'][$field]) && $view['default'][$field] == $opt_id) || (isset($view['autocomplete'][$field]) && $view['autocomplete'][$field] == $opt_id)) ? 'selected="selected"' : ''?>
														value="<?=filter_html($opt_id, $config['charset'])?>"
													>
														<?=filter_html($opt_value, $config['charset'])?>
													</option>
											<?php endforeach; ?>
										</select>
									</div>
									<div class="col-sm-1">
										<?php if (!isset($config['modalbox'])): ?>
											<a
												class="btn btn-primary btn-sm"
												href="<?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($meta['table'], $config['charset'])?>/create_data_modalbox"
												title="<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_NEW, $config['charset'])?> <?=filter_html(ucfirst($meta['table']), $config['charset'])?>"
												onclick="ndphp.modal.show(this.href, '<?=filter_html_js_special(NDPHP_LANG_MOD_OP_CONTEXT_NEW, $config['charset'])?> <?=filter_html(ucfirst($meta['table']), $config['charset'])?>'); return false;"
											>
												+
											</a>
										<?php endif; ?>
									</div>
								</div>
						<?php elseif ($meta['type'] == 'mixed'): continue; ?>
						<?php elseif ($meta['input_type'] == 'timer'): ?>
								<div class="form-group <?=in_array($field, $view['required']) ? 'required' : ''?>" id="<?=filter_html($field, $config['charset'])?>_row">
									<label for="<?=filter_html_special($field, $config['charset'])?>" class="col-sm-3 control-label">
										<?=filter_html(ucfirst($meta['viewname']), $config['charset'])?>
										<?php if ($meta['help_desc'] != NULL): ?>
											<a href="<?=filter_html($meta['help_url'], $config['charset'])?>" title="<?=filter_html($meta['help_desc'], $config['charset'])?>">
												<img src="<?=filter_html(static_images_url(), $config['charset'])?>/themes/<?=filter_html($config['theme']['name'], $config['charset'])?>/icons/help_small.png" alt="<?=filter_html($meta['help_desc'], $config['charset'])?>" />
											</a>
										<?php endif; ?>
									</label>
									<div class="col-sm-6">
										<input
											class="form-control"
											id="<?=filter_html_special($field, $config['charset'])?>"
											name="<?=filter_html($field, $config['charset'])?>"
											type="text"
											alt="<?=filter_html(ucfirst($meta['viewname']), $config['charset'])?> <?=in_array($field, $view['required']) ? '(' . filter_html(NDPHP_LANG_MOD_WORD_REQUIRED, $config['charset']) . ')' : ''?>"
											value="<?=isset($view['autocomplete'][$field]) ? filter_html($view['autocomplete'][$field], $config['charset']) : '00:00:00'?>"
											<?php echo(in_array($field, $view['required']) ? 'required="required"' : NULL); ?>
											<?=$i ? '' : 'autofocus accesskey="' . filter_html(NDPHP_LANG_MOD_OP_ACCESS_KEY_AUTOFOCUS, $config['charset']) . '"'?>
										/>
										<input
											class="btn btn-primary btn-sm"
											type="button"
											name="button_<?=filter_html($field, $config['charset'])?>"
											value="<?=filter_html(NDPHP_LANG_MOD_OP_TIMER_START, $config['charset'])?>"
											onmouseup="jQuery('#<?=filter_html_special($field, $config['charset'])?>').timer('resume');"
										/>
										<input
											class="btn btn-cancel btn-sm"
											type="button"
											name="button_<?=filter_html($field, $config['charset'])?>"
											value="<?=filter_html(NDPHP_LANG_MOD_OP_TIMER_STOP, $config['charset'])?>"
											onmouseup="jQuery('#<?=filter_html_special($field, $config['charset'])?>').timer('pause');"
										/>
									</div>
								</div>
						<?php elseif ($view['fields'][$field]['input_type'] == 'file'): ?>
								<div class="form-group <?=in_array($field, $view['required']) ? 'required' : ''?>" id="<?=filter_html($field, $config['charset'])?>_row">
									<label for="<?=filter_html_special($field, $config['charset'])?>" class="col-sm-3 control-label">
										<?=filter_html(ucfirst($view['fields'][$field]['viewname']), $config['charset'])?>
										<?php if ($meta['help_desc'] != NULL): ?>
											<a href="<?=filter_html($meta['help_url'], $config['charset'])?>" title="<?=filter_html($meta['help_desc'], $config['charset'])?>">
												<img src="<?=filter_html(static_images_url(), $config['charset'])?>/themes/<?=filter_html($config['theme']['name'], $config['charset'])?>/icons/help_small.png" alt="<?=filter_html($meta['help_desc'], $config['charset'])?>" />
											</a>
										<?php endif; ?>
									</label>
									<div class="col-sm-6">
										<input
											class="filestyle"
											data-buttonName="btn-primary"
											id="<?=filter_html_special($field, $config['charset'])?>"
											name="<?=filter_html($field, $config['charset'])?>"
											alt="<?=filter_html(ucfirst($meta['viewname']), $config['charset'])?> <?=in_array($field, $view['required']) ? '(' . filter_html(NDPHP_LANG_MOD_WORD_REQUIRED, $config['charset']) . ')' : ''?>"
											type="file"
											<?php echo(in_array($field, $view['required']) ? 'required="required"' : NULL); ?>
											<?=$i ? '' : 'autofocus accesskey="' . filter_html(NDPHP_LANG_MOD_OP_ACCESS_KEY_AUTOFOCUS, $config['charset']) . '"'?>
										/>
									</div>
								</div>
						<?php elseif ($meta['input_type'] == 'textarea'): ?>
								<div class="form-group <?=in_array($field, $view['required']) ? 'required' : ''?>" id="<?=filter_html($field, $config['charset'])?>_row">
									<label for="<?=filter_html_special($field, $config['charset'])?>" class="col-sm-3 control-label">
										<?=filter_html(ucfirst($meta['viewname']), $config['charset'])?>
										<?php if ($meta['help_desc'] != NULL): ?>
											<a href="<?=filter_html($meta['help_url'], $config['charset'])?>" title="<?=filter_html($meta['help_desc'], $config['charset'])?>">
												<img src="<?=filter_html(static_images_url(), $config['charset'])?>/themes/<?=filter_html($config['theme']['name'], $config['charset'])?>/icons/help_small.png" alt="<?=filter_html($meta['help_desc'], $config['charset'])?>" />
											</a>
										<?php endif; ?>
									</label>
									<div class="col-sm-6">
										<textarea
											class="form-control"
											id="<?=filter_html_special($field, $config['charset'])?>"
											name="<?=filter_html($field, $config['charset'])?>"
											alt="<?=filter_html(ucfirst($meta['viewname']), $config['charset'])?> <?=in_array($field, $view['required']) ? '(' . filter_html(NDPHP_LANG_MOD_WORD_REQUIRED, $config['charset']) . ')' : ''?>"
											placeholder="<?=$meta['placeholder'] ? filter_html($meta['placeholder'], $config['charset']) : filter_html($view['default'][$field], $config['charset'])?>"
											<?php echo(in_array($field, $view['required']) ? 'required="required"' : NULL); ?>
											<?=$i ? '' : 'autofocus accesskey="' . filter_html(NDPHP_LANG_MOD_OP_ACCESS_KEY_AUTOFOCUS, $config['charset']) . '"'?>
										><?=isset($view['autocomplete'][$field]) ? filter_html($view['autocomplete'][$field], $config['charset']) : ''?></textarea>
									</div>
								</div>
						<?php else: ?>
								<div class="form-group <?=in_array($field, $view['required']) ? 'required' : ''?>" id="<?=filter_html($field, $config['charset'])?>_row">
									<label for="<?=filter_html($field, $config['charset'])?>" class="col-sm-3 control-label">
										<?=filter_html(ucfirst($meta['viewname']), $config['charset'])?>
										<?php if ($meta['help_desc'] != NULL): ?>
											<a href="<?=filter_html($meta['help_url'], $config['charset'])?>" title="<?=filter_html($meta['help_desc'], $config['charset'])?>">
												<img src="<?=filter_html(static_images_url(), $config['charset'])?>/themes/<?=filter_html($config['theme']['name'], $config['charset'])?>/icons/help_small.png" alt="<?=filter_html($meta['help_desc'], $config['charset'])?>" />
											</a>
										<?php endif; ?>
									</label>
									<div class="col-sm-6">
										<?php if ($view['fields'][$field]['units']['unit']): ?>
											<div class="input-group">
											<?php if ($view['fields'][$field]['units']['left']): ?>
												<span class="input-group-addon"><?=filter_html($view['fields'][$field]['units']['unit'], $config['charset'])?></span>
											<?php endif; ?>
										<?php endif; ?>
										<input
											class="form-control"
											id="<?=filter_html($field, $config['charset'])?>"
											alt="<?=filter_html(ucfirst($meta['viewname']), $config['charset'])?> <?=in_array($field, $view['required']) ? '(' . filter_html(NDPHP_LANG_MOD_WORD_REQUIRED, $config['charset']) . ')' : ''?>"
											name="<?=filter_html($field, $config['charset'])?>"
											<?php if ($meta['type'] == 'varchar') { echo('maxlength="' . filter_html($meta['max_length'], $config['charset']) . '"'); } ?>
											type="<?php if (strstr($field, 'password')) { echo('password'); } else { echo(filter_html($meta['input_type'], $config['charset'])); } ?>"
											placeholder=
												<?php if (($meta['type'] == 'datetime') || ($meta['type'] == 'date')): ?>
													"YYYY-MM-DD" 
												<?php elseif ($meta['type'] == 'time'): ?>
													"HH:MM:SS" 
												<?php elseif ($meta['placeholder']): ?>
													"<?=filter_html($meta['placeholder'], $config['charset'])?>" 
												<?php else: ?>
													"<?=filter_html($view['default'][$field], $config['charset'])?>" 
												<?php endif; ?>
											<?php echo(in_array($field, $view['required']) ? 'required="required"' : NULL); ?>
											<?=$meta['input_pattern'] ? 'pattern="' . filter_html($meta['input_pattern'], $config['charset']) . '"' : ''?>
											<?=isset($view['autocomplete'][$field]) ? ('value="' . filter_html($view['autocomplete'][$field], $config['charset']) . '"') : ''?>
											<?=$i ? '' : 'autofocus accesskey="' . filter_html(NDPHP_LANG_MOD_OP_ACCESS_KEY_AUTOFOCUS, $config['charset']) . '"'?>
										/>
										<?php if ($view['fields'][$field]['units']['unit']): ?>
											<?php if(!$view['fields'][$field]['units']['left']): ?>
												<span class="input-group-addon"><?=filter_html($view['fields'][$field]['units']['unit'], $config['charset'])?></span>
											<?php endif; ?>
											</div>
										<?php endif; ?>
										<?php if ($meta['type'] == 'datetime'): ?>
											<input
												class="form-control"
												id="<?=filter_html_special($field, $config['charset'])?>_time"
												alt="<?=filter_html(ucfirst($meta['viewname']), $config['charset'])?> <?=in_array($field, $view['required']) ? '(' . filter_html(NDPHP_LANG_MOD_WORD_REQUIRED, $config['charset']) . ')' : ''?>"
												name="<?=filter_html($field, $config['charset'])?>_time"
												type="<?=filter_html($meta['input_type'], $config['charset'])?>"
												placeholder="HH:MM:SS" <?php echo(in_array($field, $view['required']) ? 'required="required"' : NULL); ?>
												<?=isset($view['autocomplete'][$field . '_time']) ? ('value="' . filter_html($view['autocomplete'][$field], $config['charset']) . '"') : ''?>
											/>
										<?php endif;?>
										<?php if (strstr($field, 'password')): ?>
											<input
												class="form-control"
												id="password_verification_<?=filter_html_special($field, $config['charset'])?>"
												alt="<?=filter_html(NDPHP_LANG_MOD_COMMON_PASSWORD_REPEAT, $config['charset'])?> (<?=filter_html(NDPHP_LANG_MOD_WORD_REQUIRED, $config['charset'])?>)"
												type="password"
												required="required"
												placeholder="<?=filter_html(NDPHP_LANG_MOD_COMMON_PLACEHOLDER_REPEAT_PASSWORD, $config['charset'])?>"
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
			<?php include($view['base_dir'] . '/_default/lib/multiple_create.php'); ?>
			<!-- End of Multiple relationships -->
			<!-- Begin of Mixed relationships -->
			<?php include($view['base_dir'] . '/_default/lib/mixed_create.php'); ?>
			<!-- End of Mixed relationships -->
		</div>

		<?php if (file_exists($view['base_dir'] . '/' . $view['ctrl'] . '/create_data_custom_footer.php')) { include($view['base_dir'] . '/' . $view['ctrl'] . '/create_data_custom_footer.php'); } ?>

		<div class="form-group">
			<div class="col-sm-5 col-sm-offset-<?=isset($config['modalbox']) ? '4' : '5'?>" style="padding-top: 25px;">
				<button
					class="btn btn-primary"
					id="op_submit"
					type="submit"
					value="<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_CREATE, $config['charset'])?>"
					title="<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_CREATE, $config['charset'])?>"
				>
					<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_CREATE, $config['charset'])?>
				</button>
				<a
					class="btn btn-default"
					href="<?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($view['ctrl'], $config['charset'])?>/list_default"
					onclick="ndphp.form.cancel_create(event, '<?=filter_html_js_str($view['ctrl'], $config['charset'])?>', <?=isset($config['modalbox']) ? 1 : 0?>);"
					title="<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_CANCEL, $config['charset'])?>"
				>
					<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_CANCEL, $config['charset'])?>
				</a>
			</div>
		</div>
	</form>

	<?php include($view['base_dir'] . '/_default/lib/tabs_footer.php'); ?>

</div>
