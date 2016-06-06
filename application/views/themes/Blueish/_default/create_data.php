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
	<?php include('lib/tabs_header.php'); ?>

	<?php $choices_create = true; include('lib/choices.php'); ?>

	<?php $rel = array(); /* FIXME: We're populating the $rel in this view. This should be done by the controller. */ ?>

	<form action="<?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($view['ctrl'], $config['charset'])?>/insert" id="createform" name="createform" enctype="multipart/form-data" method="post">
		<div class="fields">
			<!-- Begin of basic fields -->
			<div id="fields_basic" class="fields_basic">
				<fieldset class="fields_basic_fieldset">
					<legend class="fields_basic_legend">
						<?=filter_html(ucfirst($view['hname']), $config['charset'])?>
					</legend>

					<table class="fields">
						<tr class="fields">
							<th class="fields"><?=filter_html(NDPHP_LANG_MOD_COMMON_CRUD_TITLE_FIELD_NAME, $config['charset'])?></th>
							<th class="fields"><?=filter_html(NDPHP_LANG_MOD_COMMON_CRUD_TITLE_FIELD_VALUE, $config['charset'])?></th>
						</tr>

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

					</table>
				</fieldset>
			</div>
			<div id="fields_<?=filter_html_special($field, $config['charset'])?>_container">
				<fieldset class="fields_basic_fieldset">
					<legend class="fields_basic_legend">
						<?=filter_html(ucfirst($meta['viewname']), $config['charset'])?>
					</legend>

					<table class="fields">
						<tr class="fields">
							<th class="fields"><?=filter_html(NDPHP_LANG_MOD_COMMON_CRUD_TITLE_FIELD_NAME, $config['charset'])?></th>
							<th class="fields"><?=filter_html(NDPHP_LANG_MOD_COMMON_CRUD_TITLE_FIELD_VALUE, $config['charset'])?></th>
						</tr>

						<?php
								$i = 0; /* Reset the row index... we're entering a new tab */
								continue;
							endif; /* $meta['type'] == 'separator' */
						?>

						<?php if ($meta['input_type'] == 'checkbox'): ?>
								<tr id="<?=filter_html_special($field, $config['charset'])?>_row" class="field_<?php echo($i % 2 ? 'even' : 'odd'); ?>">
									<td class="field_name"><?=filter_html(ucfirst($meta['viewname']), $config['charset'])?>  <?=in_array($field, $view['required']) ? '*' : ''?></td>
									<td class="field_value">
										<input name="<?=filter_html($field, $config['charset'])?>" type="hidden" value="0" />
										<input alt="<?=filter_html(ucfirst($meta['viewname']), $config['charset'])?> <?=in_array($field, $view['required']) ? '(' . filter_html(NDPHP_LANG_MOD_WORD_REQUIRED, $config['charset']) . ')' : ''?>" name="<?=filter_html($field, $config['charset'])?>" type="checkbox" value="1" <?=$i ? '' : 'autofocus accesskey="' . filter_html(NDPHP_LANG_MOD_OP_ACCESS_KEY_AUTOFOCUS, $config['charset']) . '"'?> />
										<?php if ($meta['help_desc'] != NULL): ?>
											<a href="<?=filter_html($meta['help_url'], $config['charset'])?>" title="<?=filter_html($meta['help_desc'], $config['charset'])?>">
												<img src="<?=filter_html(static_images_url(), $config['charset'])?>/themes/<?=filter_html($config['theme']['name'], $config['charset'])?>/icons/help_small.png" alt="<?=filter_html($meta['help_desc'], $config['charset'])?>" />
											</a>
										<?php endif; ?>
									</td>
								</tr>
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
								<tr id="<?=filter_html_special($field, $config['charset'])?>_row" class="field_<?php echo($i % 2 ? 'even' : 'odd'); ?>">
									<td class="field_name"><?=filter_html(ucfirst($meta['viewname']), $config['charset'])?>  <?php echo(in_array($field, $view['required']) ? '*' : NULL); ?></td>
									<td class="field_value">
										<select id="<?=filter_html_special($field, $config['charset'])?>" name="<?=filter_html($field, $config['charset'])?>" <?php if ($config['choices'] && in_array($field, $choice_select)) { echo('onchange="selected_' . filter_html_js_special($view['ctrl'], $config['charset']) . '_create_choice_' . filter_html_js_special($field, $config['charset']) . '();"'); }?> <?=$i ? '' : 'autofocus accesskey="' . filter_html(NDPHP_LANG_MOD_OP_ACCESS_KEY_AUTOFOCUS, $config['charset']) . '"'?> >
											<?php foreach ($meta['options'] as $opt_id => $opt_value): ?>
													<option <?php if ($view['default'][$field] == $opt_id) { echo("selected=\"selected\""); } ?> value="<?=filter_html($opt_id, $config['charset'])?>">
														<?=filter_html($opt_value, $config['charset'])?>
													</option>
											<?php endforeach; ?>
										</select>
										<?php if ($meta['help_desc'] != NULL): ?>
											<a href="<?=filter_html($meta['help_url'], $config['charset'])?>" title="<?=filter_html($meta['help_desc'], $config['charset'])?>">
												<img src="<?=filter_html(static_images_url(), $config['charset'])?>/themes/<?=filter_html($config['theme']['name'], $config['charset'])?>/icons/help_small.png" alt="<?=filter_html($meta['help_desc'], $config['charset'])?>" />
											</a>
										<?php endif; ?>
										<?php if (!isset($config['modalbox'])): ?>
											<a href="<?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($meta['table'], $config['charset'])?>/create_data_modalbox" title="<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_NEW, $config['charset'])?> <?=filter_html(ucfirst($meta['table']), $config['charset'])?>" onclick="Modalbox.show(this.href, {title: this.title, width: 800}); return false;">
												<img class="create_op_icon" alt="<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_NEW, $config['charset'])?> <?=filter_html(ucfirst($meta['table']), $config['charset'])?>" src="<?=filter_html(static_images_url(), $config['charset'])?>/themes/<?=filter_html($config['theme']['name'], $config['charset'])?>/icons/more.png" />
											</a>
										<?php endif; ?>
									</td>
								</tr>
						<?php elseif ($meta['type'] == 'mixed'): continue; ?>
						<?php elseif ($meta['input_type'] == 'timer'): ?>
								<tr id="<?=filter_html_special($field, $config['charset'])?>_row" class="field_<?php echo($i % 2 ? 'even' : 'odd'); ?>">
									<td class="field_name"><?=filter_html(ucfirst($meta['viewname']), $config['charset'])?>  <?php echo(in_array($field, $view['required']) ? '*' : NULL); ?></td>
									<td class="field_value">
										<input id="<?=filter_html_special($field, $config['charset'])?>" name="<?=filter_html($field, $config['charset'])?>" type="text" alt="<?=filter_html(ucfirst($meta['viewname']), $config['charset'])?> <?=in_array($field, $view['required']) ? '(' . filter_html(NDPHP_LANG_MOD_WORD_REQUIRED, $config['charset']) . ')' : ''?>" value="<?=$value ? filter_html($value, $config['charset']) : '00:00:00'?>" <?php echo(in_array($field, $view['required']) ? 'required="required"' : NULL); ?> <?=$i ? '' : 'autofocus accesskey="' . filter_html(NDPHP_LANG_MOD_OP_ACCESS_KEY_AUTOFOCUS, $config['charset']) . '"'?> />
										<input type="button" name="button_<?=filter_html($field, $config['charset'])?>" value="<?=filter_html(NDPHP_LANG_MOD_OP_TIMER_START, $config['charset'])?>" onmouseup="jQuery('#<?=filter_html_special($field, $config['charset'])?>').timer('resume');" />
										<input type="button" name="button_<?=filter_html($field, $config['charset'])?>" value="<?=filter_html(NDPHP_LANG_MOD_OP_TIMER_STOP, $config['charset'])?>" onmouseup="jQuery('#<?=filter_html_special($field, $config['charset'])?>').timer('pause');" />
										<?php if ($meta['help_desc'] != NULL): ?>
											<a href="<?=filter_html($meta['help_url'], $config['charset'])?>" title="<?=filter_html($meta['help_desc'], $config['charset'])?>">
												<img src="<?=filter_html(static_images_url(), $config['charset'])?>/themes/<?=filter_html($config['theme']['name'], $config['charset'])?>/icons/help_small.png" alt="<?=filter_html($meta['help_desc'], $config['charset'])?>" />
											</a>
										<?php endif; ?>
									</td>
								</tr>
						<?php elseif ($view['fields'][$field]['input_type'] == 'file'): ?>
								<tr id="<?=filter_html_special($field, $config['charset'])?>_row" class="field_<?php echo($i % 2 ? 'even' : 'odd'); ?>">
									<td class="field_name"><?=filter_html(ucfirst($view['fields'][$field]['viewname']), $config['charset'])?>  <?php echo(in_array($field, $view['required']) ? '*' : NULL); ?></td>
									<td class="field_value">
										<input id="<?=filter_html_special($field, $config['charset'])?>" name="<?=filter_html($field, $config['charset'])?>" alt="<?=filter_html(ucfirst($meta['viewname']), $config['charset'])?> <?=in_array($field, $view['required']) ? '(' . filter_html(NDPHP_LANG_MOD_WORD_REQUIRED, $config['charset']) . ')' : ''?>" type="file" <?php echo(in_array($field, $view['required']) ? 'required="required"' : NULL); ?> <?=$i ? '' : 'autofocus accesskey="' . filter_html(NDPHP_LANG_MOD_OP_ACCESS_KEY_AUTOFOCUS, $config['charset']) . '"'?> />
										<?php if ($meta['help_desc'] != NULL): ?>
											<a href="<?=filter_html($meta['help_url'], $config['charset'])?>" title="<?=filter_html($meta['help_desc'], $config['charset'])?>">
												<img src="<?=filter_html(static_images_url(), $config['charset'])?>/themes/<?=filter_html($config['theme']['name'], $config['charset'])?>/icons/help_small.png" alt="<?=filter_html($meta['help_desc'], $config['charset'])?>" />
											</a>
										<?php endif; ?>
									</td>
								</tr>
						<?php elseif ($meta['input_type'] == 'textarea'): ?>
								<tr id="<?=filter_html_special($field, $config['charset'])?>_row" class="field_<?php echo($i % 2 ? 'even' : 'odd'); ?>">
									<td class="field_name"><?=filter_html(ucfirst($meta['viewname']), $config['charset'])?>  <?php echo(in_array($field, $view['required']) ? '*' : NULL); ?></td>
									<td class="field_value">
										<textarea id="<?=filter_html_special($field, $config['charset'])?>" name="<?=filter_html($field, $config['charset'])?>" alt="<?=filter_html(ucfirst($meta['viewname']), $config['charset'])?> <?=in_array($field, $view['required']) ? '(' . filter_html(NDPHP_LANG_MOD_WORD_REQUIRED, $config['charset']) . ')' : ''?>" placeholder="<?=filter_html($view['default'][$field], $config['charset'])?>" <?php echo(in_array($field, $view['required']) ? 'required="required"' : NULL); ?> <?=$i ? '' : 'autofocus accesskey="' . filter_html(NDPHP_LANG_MOD_OP_ACCESS_KEY_AUTOFOCUS, $config['charset']) . '"'?> ><?=filter_html($value, $config['charset'])?></textarea>
										<?php if ($meta['help_desc'] != NULL): ?>
											<a href="<?=filter_html($meta['help_url'], $config['charset'])?>" title="<?=filter_html($meta['help_desc'], $config['charset'])?>">
												<img src="<?=filter_html(static_images_url(), $config['charset'])?>/themes/<?=filter_html($config['theme']['name'], $config['charset'])?>/icons/help_small.png" alt="<?=filter_html($meta['help_desc'], $config['charset'])?>" />
											</a>
										<?php endif; ?>
									</td>
								</tr>
						<?php else: ?>
								<tr id="<?=filter_html_special($field, $config['charset'])?>_row" class="field_<?php echo($i % 2 ? 'even' : 'odd'); ?>">
									<td class="field_name"><?=filter_html(ucfirst($meta['viewname']), $config['charset'])?>  <?php echo(in_array($field, $view['required']) ? '*' : NULL); ?></td>
									<td class="field_value">
										<input id="<?=filter_html($field, $config['charset'])?>" alt="<?=filter_html(ucfirst($meta['viewname']), $config['charset'])?> <?=in_array($field, $view['required']) ? '(' . filter_html(NDPHP_LANG_MOD_WORD_REQUIRED, $config['charset']) . ')' : ''?>" name="<?=filter_html($field, $config['charset'])?>" <?php if ($meta['type'] == 'varchar') { echo('maxlength="' . filter_html($meta['max_length'], $config['charset']) . '"'); } ?> type="<?php if (strstr($field, 'password')) { echo('password'); } else { echo(filter_html($meta['input_type'], $config['charset'])); } ?>" <?php if (($meta['type'] == 'datetime') || ($meta['type'] == 'date')) { echo('placeholder="YYYY-MM-DD"'); } else if ($meta['type'] == 'time') { echo('placeholder="HH:MM:SS"'); } else { echo('placeholder="' . filter_html($view['default'][$field], $config['charset']) . '"'); } ?> <?php echo(in_array($field, $view['required']) ? 'required="required"' : NULL); ?> <?=$i ? '' : 'autofocus accesskey="' . filter_html(NDPHP_LANG_MOD_OP_ACCESS_KEY_AUTOFOCUS, $config['charset']) . '"'?> />
										<?php if (strstr($field, 'password')): ?>
											<?=filter_html(NDPHP_LANG_MOD_COMMON_REPEAT, $config['charset'])?>: <input id="password_verification_<?=filter_html_special($field, $config['charset'])?>" alt="<?=filter_html(NDPHP_LANG_MOD_COMMON_PASSWORD_REPEAT, $config['charset'])?> (<?=filter_html(NDPHP_LANG_MOD_WORD_REQUIRED, $config['charset'])?>)" type="password" required="required" />
										<?php endif; ?>
										<?php if ($meta['type'] == 'datetime'): ?>
											<input id="<?=filter_html_special($field, $config['charset'])?>_time" alt="<?=filter_html(ucfirst($meta['viewname']), $config['charset'])?> <?=in_array($field, $view['required']) ? '(' . filter_html(NDPHP_LANG_MOD_WORD_REQUIRED, $config['charset']) . ')' : ''?>" name="<?=filter_html($field, $config['charset'])?>_time" type="<?=filter_html($meta['input_type'], $config['charset'])?>" placeholder="HH:MM:SS" <?php echo(in_array($field, $view['required']) ? 'required="required"' : NULL); ?> />
										<?php endif;?>
										<?php if ($meta['help_desc'] != NULL): ?>
											<a href="<?=filter_html($meta['help_url'], $config['charset'])?>" title="<?=filter_html($meta['help_desc'], $config['charset'])?>">
												<img src="<?=filter_html(static_images_url(), $config['charset'])?>/themes/<?=filter_html($config['theme']['name'], $config['charset'])?>/icons/help_small.png" alt="<?=filter_html($meta['help_desc'], $config['charset'])?>" />
											</a>
										<?php endif; ?>
									</td>
								</tr>
						<?php endif; ?>
					<?php $i ++; endforeach; ?>
					</table>
				</fieldset>
			</div>
			<!-- End of basic fields -->
			<!-- Begin of Multiple relationships -->
			<div id="multiple_relationships">
				<?php include('lib/multiple_create.php'); ?>
			</div>
			<!-- End of Multiple relationships -->
			<!-- Begin of Mixed relationships -->
			<div id="mixed_relationships">
				<?php include('lib/mixed_create.php'); ?>
			</div>
			<!-- End of Mixed relationships -->
		</div>
		<div class="create_ops">
			<a href="javascript:void(0);" onclick="ndphp.form.submit_create_wrapper(event, '<?=filter_html_js_str($view['ctrl'], $config['charset'])?>', 'createform', <?=isset($config['modalbox']) ? 1 : 0?>);" title="<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_CREATE, $config['charset'])?>" class="context_menu_link">
				<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_CREATE, $config['charset'])?>
			</a>
			<a href="javascript:void(0);" onclick="ndphp.form.cancel_create(event, '<?=filter_html_js_str($view['ctrl'], $config['charset'])?>', <?=isset($config['modalbox']) ? 1 : 0?>);" title="<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_CANCEL, $config['charset'])?>" class="context_menu_link">
				<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_CANCEL, $config['charset'])?>
			</a>
		</div>
	</form>
	<?php include('lib/tabs_footer.php'); ?>
</div>
