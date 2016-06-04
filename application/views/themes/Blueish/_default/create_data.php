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

	<?php if (!isset($config['modalbox'])): ?>
			<script type="text/javascript">
				/* Set the origin controller */
				ndphp.origin_controller = '<?=filter_js_str($view['ctrl'], $config['charset'])?>';
			</script>
	<?php endif; ?>

	<?php $choices_create = true; include('lib/choices.php'); ?>

	<?php $rel = array(); /* FIXME: We're populating the $rel in this view. This should be done by the controller. */ ?>

	<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery("#createform").validate({
				errorElement: "span",
				wrapper: "span"
			});
		});
	</script>
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
					<?php
						$i = 0; $tab_index = 0;
						$textarea_ids = array();
						foreach ($view['fields'] as $field => $meta):
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
								$tab_index ++;
								$i = 0;
								continue;
							endif; /* $meta['type'] == 'separator' */

						 	/* Build an array of textareas ID's to be used on create/update ajax post functions
						 	 * so the contents of tinyMCE can be saved before submitted.
						 	 */
							if ($meta['input_type'] == 'textarea' && in_array($field, $config['rich_text']))
								array_push($textarea_ids, $field);
			
							if (in_array($field, $config['hidden_fields']))
								continue;
					?>

					<?php if (in_array($field, $view['required'])): /* Populate the fields/tab javascript map */ ?>
							<script type="text/javascript">
								if ( typeof required_fields_tab_map[<?=$tab_index?>] == 'undefined')
									required_fields_tab_map[<?=$tab_index?>] = [];

								required_fields_tab_map[<?=$tab_index?>].push('<?=filter_js_str($field, $config['charset'])?>');
							</script>
					<?php endif; ?>

					<?php
			
							if ($meta['input_type'] == 'checkbox') {
					?>
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
					<?php
							} else if ($meta['input_type'] == 'select') {
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
					<?php
							} else if ($meta['type'] == 'mixed') {
								continue;
					?>
			
					<?php
							} else if ($meta['input_type'] == 'timer') {
					?>
								<tr id="<?=filter_html_special($field, $config['charset'])?>_row" class="field_<?php echo($i % 2 ? 'even' : 'odd'); ?>">
									<td class="field_name"><?=filter_html(ucfirst($meta['viewname']), $config['charset'])?>  <?php echo(in_array($field, $view['required']) ? '*' : NULL); ?></td>
									<td class="field_value">
										<input id="<?=filter_html_special($field, $config['charset'])?>" name="<?=filter_html($field, $config['charset'])?>" type="text" alt="<?=filter_html(ucfirst($meta['viewname']), $config['charset'])?> <?=in_array($field, $view['required']) ? '(' . filter_html(NDPHP_LANG_MOD_WORD_REQUIRED, $config['charset']) . ')' : ''?>" value="<?=$value ? filter_html($value, $config['charset']) : '00:00:00'?>" <?php echo(in_array($field, $view['required']) ? 'required="required"' : NULL); ?> <?=$i ? '' : 'autofocus accesskey="' . filter_html(NDPHP_LANG_MOD_OP_ACCESS_KEY_AUTOFOCUS, $config['charset']) . '"'?> />
										<script type="text/javascript">
											jQuery('#<?=filter_html_special($field, $config['charset'])?>').timer({ format: "%H:%M:%S" });
											jQuery('#<?=filter_html_special($field, $config['charset'])?>').timer('pause');
										</script>
										<input type="button" name="button_<?=filter_html($field, $config['charset'])?>" value="<?=filter_html(NDPHP_LANG_MOD_OP_TIMER_START, $config['charset'])?>" onmouseup="jQuery('#<?=filter_html_special($field, $config['charset'])?>').timer('resume');" />
										<input type="button" name="button_<?=filter_html($field, $config['charset'])?>" value="<?=filter_html(NDPHP_LANG_MOD_OP_TIMER_STOP, $config['charset'])?>" onmouseup="jQuery('#<?=filter_html_special($field, $config['charset'])?>').timer('pause');" />
										<script type="text/javascript">
											jQuery("#<?=filter_js_special($field, $config['charset'])?>").timepicker();
										</script>
										<?php if ($meta['help_desc'] != NULL): ?>
											<a href="<?=filter_html($meta['help_url'], $config['charset'])?>" title="<?=filter_html($meta['help_desc'], $config['charset'])?>">
												<img src="<?=filter_html(static_images_url(), $config['charset'])?>/themes/<?=filter_html($config['theme']['name'], $config['charset'])?>/icons/help_small.png" alt="<?=filter_html($meta['help_desc'], $config['charset'])?>" />
											</a>
										<?php endif; ?>
									</td>
								</tr>
					<?php
							} else if ($view['fields'][$field]['input_type'] == 'file') {
					?>
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
					<?php
							} else if ($meta['input_type'] == 'textarea') {
					?>
								<tr id="<?=filter_html_special($field, $config['charset'])?>_row" class="field_<?php echo($i % 2 ? 'even' : 'odd'); ?>">
									<td class="field_name"><?=filter_html(ucfirst($meta['viewname']), $config['charset'])?>  <?php echo(in_array($field, $view['required']) ? '*' : NULL); ?></td>
									<td class="field_value">
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
										<textarea id="<?=filter_html_special($field, $config['charset'])?>" name="<?=filter_html($field, $config['charset'])?>" alt="<?=filter_html(ucfirst($meta['viewname']), $config['charset'])?> <?=in_array($field, $view['required']) ? '(' . filter_html(NDPHP_LANG_MOD_WORD_REQUIRED, $config['charset']) . ')' : ''?>" placeholder="<?=filter_html($view['default'][$field], $config['charset'])?>" <?php echo(in_array($field, $view['required']) ? 'required="required"' : NULL); ?> <?=$i ? '' : 'autofocus accesskey="' . filter_html(NDPHP_LANG_MOD_OP_ACCESS_KEY_AUTOFOCUS, $config['charset']) . '"'?> ><?=filter_html($value, $config['charset'])?></textarea>
										<?php if ($meta['help_desc'] != NULL): ?>
											<a href="<?=filter_html($meta['help_url'], $config['charset'])?>" title="<?=filter_html($meta['help_desc'], $config['charset'])?>">
												<img src="<?=filter_html(static_images_url(), $config['charset'])?>/themes/<?=filter_html($config['theme']['name'], $config['charset'])?>/icons/help_small.png" alt="<?=filter_html($meta['help_desc'], $config['charset'])?>" />
											</a>
										<?php endif; ?>
									</td>
								</tr>
					<?php
							} else {
					?>
								<tr id="<?=filter_html_special($field, $config['charset'])?>_row" class="field_<?php echo($i % 2 ? 'even' : 'odd'); ?>">
									<td class="field_name"><?=filter_html(ucfirst($meta['viewname']), $config['charset'])?>  <?php echo(in_array($field, $view['required']) ? '*' : NULL); ?></td>
									<td class="field_value">
										<input id="<?=filter_html($field, $config['charset'])?>" alt="<?=filter_html(ucfirst($meta['viewname']), $config['charset'])?> <?=in_array($field, $view['required']) ? '(' . filter_html(NDPHP_LANG_MOD_WORD_REQUIRED, $config['charset']) . ')' : ''?>" name="<?=filter_html($field, $config['charset'])?>" <?php if ($meta['type'] == 'varchar') { echo('maxlength="' . filter_html($meta['max_length'], $config['charset']) . '"'); } ?> type="<?php if (strstr($field, 'password')) { echo('password'); } else { echo(filter_html($meta['input_type'], $config['charset'])); } ?>" <?php if (($meta['type'] == 'datetime') || ($meta['type'] == 'date')) { echo('placeholder="YYYY-MM-DD"'); } else if ($meta['type'] == 'time') { echo('placeholder="HH:MM:SS"'); } else { echo('placeholder="' . filter_html($view['default'][$field], $config['charset']) . '"'); } ?> <?php echo(in_array($field, $view['required']) ? 'required="required"' : NULL); ?> <?=$i ? '' : 'autofocus accesskey="' . filter_html(NDPHP_LANG_MOD_OP_ACCESS_KEY_AUTOFOCUS, $config['charset']) . '"'?> />
										<?php if (strstr($field, 'password')): ?>
											<?=filter_html(NDPHP_LANG_MOD_COMMON_REPEAT, $config['charset'])?>: <input id="password_verification_<?=filter_html_special($field, $config['charset'])?>" alt="<?=filter_html(NDPHP_LANG_MOD_COMMON_PASSWORD_REPEAT, $config['charset'])?> (<?=filter_html(NDPHP_LANG_MOD_WORD_REQUIRED, $config['charset'])?>)" type="password" required="required" />
										<?php endif; ?>
										<?php if ($meta['type'] == 'datetime'): ?>
											<input id="<?=filter_html_special($field, $config['charset'])?>_time" alt="<?=filter_html(ucfirst($meta['viewname']), $config['charset'])?> <?=in_array($field, $view['required']) ? '(' . filter_html(NDPHP_LANG_MOD_WORD_REQUIRED, $config['charset']) . ')' : ''?>" name="<?=filter_html($field, $config['charset'])?>_time" type="<?=filter_html($meta['input_type'], $config['charset'])?>" placeholder="HH:MM:SS" <?php echo(in_array($field, $view['required']) ? 'required="required"' : NULL); ?> />
											<script type="text/javascript">
												jQuery("#<?=filter_js_special($field, $config['charset'])?>").datepicker();
												jQuery("#<?=filter_js_special($field, $config['charset'])?>").datepicker('option', 'dateFormat', 'yy-mm-dd');
												jQuery("#<?=filter_js_special($field, $config['charset'])?>_time").timepicker();
												jQuery("#<?=filter_js_special($field, $config['charset'])?>").addClass('input_date');
												jQuery("#<?=filter_js_special($field, $config['charset'])?>_time").addClass('input_time');
											</script>
										<?php endif;?>
										<?php if ($meta['type'] == 'date'): ?>
											<script type="text/javascript">
												jQuery("#<?=filter_js_special($field, $config['charset'])?>").datepicker();
												jQuery("#<?=filter_js_special($field, $config['charset'])?>").datepicker('option', 'dateFormat', 'yy-mm-dd');
												jQuery("#<?=filter_js_special($field, $config['charset'])?>").addClass('input_date');
											</script>
										<?php endif; ?>
										<?php if ($meta['type'] == 'time'): ?>
											<script type="text/javascript">
												jQuery("#<?=filter_js_special($field, $config['charset'])?>").timepicker();
												jQuery("#<?=filter_js_special($field, $config['charset'])?>").addClass('input_time');
											</script>
										<?php endif; ?>
										<?php if ($meta['help_desc'] != null): ?>
											<a href="<?=filter_html($meta['help_url'], $config['charset'])?>" title="<?=filter_html($meta['help_desc'], $config['charset'])?>">
												<img src="<?=filter_html(static_images_url(), $config['charset'])?>/themes/<?=filter_html($config['theme']['name'], $config['charset'])?>/icons/help_small.png" alt="<?=filter_html($meta['help_desc'], $config['charset'])?>" />
											</a>
										<?php endif; ?>
									</td>
								</tr>
					<?php
							}
			
							$i ++;
						endforeach;
					?>
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
			<script type="text/javascript">
				ndphp.form.submit_create_wrapper = function(e, ctrl, form_id, from_modal) {
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

					ndphp.form.submit_create(e, ctrl, form_id, from_modal);
				};
			</script>
		</div>
	</form>
	<?php include('lib/tabs_footer.php'); ?>
</div>
