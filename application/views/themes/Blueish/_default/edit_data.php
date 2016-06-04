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
	<?php include('lib/tabs_header.php'); ?>

	<?php if (!isset($config['modalbox'])): ?>
			<script type="text/javascript">
				/* Set the origin controller */
				ndphp.origin_controller = '<?=filter_js_str($view['ctrl'], $config['charset'])?>';
			</script>
	<?php endif; ?>

	<?php $choices_edit = true; include('lib/choices.php'); ?>

	<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery("#editform").validate({
				errorElement: "span",
				wrapper: "span"
			});
		});
	</script>
	<form action="<?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($view['ctrl'], $config['charset'])?>/update" id="editform" name="editform" enctype="multipart/form-data" method="post">
		<input type="hidden" name="id" value="<?=filter_html($view['id'], $config['charset'])?>" />
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
						$row = array_values($view['result_array'])[0];
					?>
					<?php
						$i = 0; $tab_index = 0;
						$textarea_ids = array();
						foreach ($row as $field => $value):
							/* Ignore fields without meta data */
							if (!isset($view['fields'][$field]))
								continue;

							/* If this is a separator, we need to close the current table, fieldset and div and create new ones */
							if ($view['fields'][$field]['type'] == 'separator'):
					?>
					</table>
				</fieldset>
			</div>
			<div id="fields_<?=filter_html_special($field, $config['charset'])?>_container">
				<fieldset class="fields_basic_fieldset">
					<legend class="fields_basic_legend">
						<?=filter_html(ucfirst($view['fields'][$field]['viewname']), $config['charset'])?>
					</legend>

					<table class="fields">
						<tr class="fields">
							<th class="fields"><?=filter_html(NDPHP_LANG_MOD_COMMON_CRUD_TITLE_FIELD_NAME, $config['charset'])?></th>
							<th class="fields"><?=filter_html(NDPHP_LANG_MOD_COMMON_CRUD_TITLE_FIELD_VALUE, $config['charset'])?></th>
						</tr>
					<?php
								$i = 0; $tab_index ++;
								continue;
							endif;

						 	/* Build an array of textareas ID's to be used on create/update ajax post functions
						 	 * so the contents of tinyMCE can be saved before submitted.
						 	 */
							if ($view['fields'][$field]['input_type'] == 'textarea' && in_array($field, $config['rich_text']))
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
							if ($view['fields'][$field]['input_type'] == 'checkbox') {
					?>
								<tr id="<?=filter_html_special($field, $config['charset'])?>_row" class="field_<?php echo($i % 2 ? 'even' : 'odd'); ?>">
									<td class="field_name"><?=filter_html(ucfirst($view['fields'][$field]['viewname']), $config['charset'])?>  <?php echo(in_array($field, $view['required']) ? '*' : NULL); ?></td>
									<td class="field_value">
										<input name="<?=filter_html($field, $config['charset'])?>" type="hidden" value="0" />
										<input alt="<?=filter_html(ucfirst($view['fields'][$field]['viewname']), $config['charset'])?> <?=in_array($field, $view['required']) ? '(' . filter_html(NDPHP_LANG_MOD_WORD_REQUIRED, $config['charset']) . ')' : ''?>" name="<?=filter_html($field, $config['charset'])?>" type="checkbox" value="1" <?php echo($value ? 'checked="checked"' : NULL); ?> <?php if (!security_perm_check($security['perms'], 'U', $view['ctrl'], $field)) { echo('disabled'); }?> <?=$i ? '' : 'autofocus accesskey="' . filter_html(NDPHP_LANG_MOD_OP_ACCESS_KEY_AUTOFOCUS, $config['charset']) . '"'?> />
										<?php if ($view['fields'][$field]['help_desc'] != NULL): ?>
											<a href="<?=filter_html($view['fields'][$field]['help_url'], $config['charset'])?>" title="<?=filter_html($view['fields'][$field]['help_desc'], $config['charset'])?>">
												<img src="<?=filter_html(static_images_url(), $config['charset'])?>/themes/<?=filter_html($config['theme']['name'], $config['charset'])?>/icons/help_small.png" alt="<?=filter_html($view['fields'][$field]['help_desc'], $config['charset'])?>" />
											</a>
										<?php endif; ?>
									</td>
								</tr>
					<?php
							} else if ($view['fields'][$field]['input_type'] == 'select') {
					?>
								<tr id="<?=filter_html_special($field, $config['charset'])?>_row" class="field_<?php echo($i % 2 ? 'even' : 'odd'); ?>">
									<td class="field_name"><?=filter_html(ucfirst($view['fields'][$field]['viewname']), $config['charset'])?>  <?php echo(in_array($field, $view['required']) ? '*' : NULL); ?></td>
									<td class="field_value">
										<select id="<?=filter_html_special($field, $config['charset'])?>" name="<?=filter_html($field, $config['charset'])?>" <?php if ($config['choices'] && in_array($field, $choice_select)) { echo('onchange="selected_' . filter_html_js_special($view['ctrl'], $config['charset']) . '_edit_choice_' . filter_html_js_special($field, $config['charset']) . '();"'); }?> <?php if (!security_perm_check($security['perms'], 'U', $view['ctrl'], $field)) { echo('disabled'); }?> <?=$i ? '' : 'autofocus accesskey="' . filter_html(NDPHP_LANG_MOD_OP_ACCESS_KEY_AUTOFOCUS, $config['charset']) . '"'?> >
											<?php
											foreach ($view['fields'][$field]['options'] as $opt_id => $opt_value):
											?>
												<option value="<?=filter_html($opt_id, $config['charset'])?>" <?php echo ($opt_id == $value ? 'selected="selected"' : NULL); ?> >
													<?=filter_html($opt_value, $config['charset'])?>
												</option>
											<?php
											endforeach;
											?>
										</select>
										<?php if ($view['fields'][$field]['help_desc'] != NULL): ?>
											<a href="<?=filter_html($view['fields'][$field]['help_url'], $config['charset'])?>" title="<?=filter_html($view['fields'][$field]['help_desc'], $config['charset'])?>">
												<img src="<?=filter_html(static_images_url(), $config['charset'])?>/themes/<?=filter_html($config['theme']['name'], $config['charset'])?>/icons/help_small.png" alt="<?=filter_html($view['fields'][$field]['help_desc'], $config['charset'])?>" />
											</a>
										<?php endif; ?>
										<?php if (!isset($config['modalbox'])): ?>
											<a href="<?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($view['fields'][$field]['table'], $config['charset'])?>/create_data_modalbox" title="<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_NEW, $config['charset'])?> <?=filter_html(ucfirst($view['fields'][$field]['table']), $config['charset'])?>" onclick="Modalbox.show(this.href, {title: this.title, width: 600}); return false;">
												<img class="create_op_icon" alt="<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_NEW, $config['charset'])?> <?=filter_html(ucfirst($view['fields'][$field]['table']), $config['charset'])?>" src="<?=filter_html(static_images_url(), $config['charset'])?>/themes/<?=filter_html($config['theme']['name'], $config['charset'])?>/icons/more.png" />
											</a>
										<?php endif; ?>
									</td>
								</tr>
					<?php
							} else if ($view['fields'][$field]['input_type'] == 'timer') {
					?>
								<tr id="<?=filter_html_special($field, $config['charset'])?>_row" class="field_<?php echo($i % 2 ? 'even' : 'odd'); ?>">
									<td class="field_name"><?=filter_html(ucfirst($view['fields'][$field]['viewname']), $config['charset'])?>  <?php echo(in_array($field, $view['required']) ? '*' : NULL); ?></td>
									<td class="field_value">
										<input id="<?=filter_html_special($field, $config['charset'])?>" name="<?=filter_html($field, $config['charset'])?>" type="text" alt="<?=filter_html(ucfirst($view['fields'][$field]['viewname']), $config['charset'])?> <?=in_array($field, $view['required']) ? '(' . filter_html(NDPHP_LANG_MOD_WORD_REQUIRED, $config['charset']) . ')' : ''?>" value="<?=$value ? filter_html($value, $config['charset']) : '00:00:00'?>" <?php echo(in_array($field, $view['required']) ? 'required="required"' : NULL); ?> <?php if (!security_perm_check($security['perms'], 'U', $view['ctrl'], $field)) { echo('disabled'); }?> <?=$i ? '' : 'autofocus accesskey="' . filter_html(NDPHP_LANG_MOD_OP_ACCESS_KEY_AUTOFOCUS, $config['charset']) . '"'?> />
										<script type="text/javascript">
											var tval_raw = '00:00:00'.split(':');

											if (jQuery('#<?=filter_html_special($field, $config['charset'])?>').val())
												tval_raw = String(jQuery('#<?=filter_html_special($field, $config['charset'])?>').val()).split(':');

											jQuery('#<?=filter_html_special($field, $config['charset'])?>').timer({ seconds: parseInt(tval_raw[0]) * 3600 + parseInt(tval_raw[1]) * 60 + parseInt(tval_raw[2]), format: "%H:%M:%S" });
											jQuery('#<?=filter_html_special($field, $config['charset'])?>').timer('pause');
										</script>
										<input type="button" name="button_<?=filter_html($field, $config['charset'])?>" value="<?=filter_html(NDPHP_LANG_MOD_OP_TIMER_START, $config['charset'])?>" onmouseup="jQuery('#<?=filter_html_special($field, $config['charset'])?>').timer('resume');" <?php if (!security_perm_check($security['perms'], 'U', $view['ctrl'], $field)) { echo('disabled'); }?> />
										<input type="button" name="button_<?=filter_html($field, $config['charset'])?>" value="<?=filter_html(NDPHP_LANG_MOD_OP_TIMER_STOP, $config['charset'])?>" onmouseup="jQuery('#<?=filter_html_special($field, $config['charset'])?>').timer('pause');" <?php if (!security_perm_check($security['perms'], 'U', $view['ctrl'], $field)) { echo('disabled'); }?> />
										<?php if ($view['fields'][$field]['help_desc'] != NULL): ?>
											<a href="<?=filter_html($view['fields'][$field]['help_url'], $config['charset'])?>" title="<?=filter_html($view['fields'][$field]['help_desc'], $config['charset'])?>">
												<img src="<?=filter_html(static_images_url(), $config['charset'])?>/themes/<?=filter_html($config['theme']['name'], $config['charset'])?>/icons/help_small.png" alt="<?=filter_html($view['fields'][$field]['help_desc'], $config['charset'])?>" />
											</a>
										<?php endif; ?>
										<script type="text/javascript">
											jQuery("#<?=filter_js_special($field, $config['charset'])?>").timepicker();
										</script>
									</td>
								</tr>
					<?php
							} else if ($view['fields'][$field]['input_type'] == 'file') {
					?>
								<tr id="<?=filter_html_special($field, $config['charset'])?>_row" class="field_<?php echo($i % 2 ? 'even' : 'odd'); ?>">
									<td class="field_name"><?=filter_html(ucfirst($view['fields'][$field]['viewname']), $config['charset'])?>  <?php echo(in_array($field, $view['required']) ? '*' : NULL); ?></td>
									<td class="field_value">
										<a target="_blank" title="<?=filter_html($value, $config['charset'])?>" href="<?=filter_html(base_url(), $config['charset'])?>index.php/files/access/<?=filter_html($view['ctrl'], $config['charset'])?>/<?=filter_html($view['id'], $config['charset'])?>/<?=filter_html($field, $config['charset'])?>/<?=filter_html($value, $config['charset'])?>">
											<?php if ($config['render']['images'] && in_array(end(explode('.', $value)), $config['render']['ext'])): ?>
												<img alt="<?=filter_html($value, $config['charset'])?>" style="width: <?=filter_html($config['render']['size']['width'], $config['charset'])?>; height: <?=filter_html($config['render']['size']['height'], $config['charset'])?>;" src="<?=filter_html(base_url(), $config['charset'])?>index.php/files/access/<?=filter_html($view['ctrl'], $config['charset'])?>/<?=filter_html($view['id'], $config['charset'])?>/<?=filter_html($field, $config['charset'])?>/<?=filter_html($value, $config['charset'])?>" />
											<?php else: ?>
												<?=filter_html($value, $config['charset'])?>
											<?php endif; ?>
										</a>
										<?php if ($value): ?>
											<br />
										<?php endif; ?>
										<input id="<?=filter_html_special($field, $config['charset'])?>" name="<?=filter_html($field, $config['charset'])?>" type="file" alt="<?=filter_html(ucfirst($view['fields'][$field]['viewname']), $config['charset'])?> <?=in_array($field, $view['required']) ? '(' . filter_html(NDPHP_LANG_MOD_WORD_REQUIRED, $config['charset']) . ')' : ''?>" <?php echo(in_array($field, $view['required']) ? 'required="required"' : NULL); ?> <?php if (!security_perm_check($security['perms'], 'U', $view['ctrl'], $field)) { echo('disabled'); }?> <?=$i ? '' : 'autofocus accesskey="' . filter_html(NDPHP_LANG_MOD_OP_ACCESS_KEY_AUTOFOCUS, $config['charset']) . '"'?> />
										<?php if ($view['fields'][$field]['help_desc'] != NULL): ?>
											<a href="<?=filter_html($view['fields'][$field]['help_url'], $config['charset'])?>" title="<?=filter_html($view['fields'][$field]['help_desc'], $config['charset'])?>">
												<img src="<?=filter_html(static_images_url(), $config['charset'])?>/themes/<?=filter_html($config['theme']['name'], $config['charset'])?>/icons/help_small.png" alt="<?=filter_html($view['fields'][$field]['help_desc'], $config['charset'])?>" />
											</a>
										<?php endif; ?>
										<?php if ($value): ?>
											<br />
											<input type="hidden" name="<?=filter_html($field, $config['charset'])?>_remove" value="0" />
											<input type="checkbox" name="<?=filter_html($field, $config['charset'])?>_remove" /> <span class="edit_remove_file"><?=filter_html(NDPHP_LANG_MOD_OP_REMOVE, $config['charset'])?></span>
										<?php endif; ?>
									</td>
								</tr>
					<?php
							} else if ($view['fields'][$field]['input_type'] == 'textarea') {
					?>
								<tr id="<?=filter_html_special($field, $config['charset'])?>_row" class="field_<?php echo($i % 2 ? 'even' : 'odd'); ?>">
									<td class="field_name"><?=filter_html(ucfirst($view['fields'][$field]['viewname']), $config['charset'])?>  <?php echo(in_array($field, $view['required']) ? '*' : NULL); ?></td>
									<td class="field_value">
									<?php if (isset($config['modalbox']) && in_array($field, $config['rich_text'])) { ?>
										<a href="javascript:void(0);" onclick="ndphp.ajax.load_body_edit_frommodal(event, '<?=filter_html_js_str($view['ctrl'], $config['charset'])?>', <?=filter_html_js_special($view['id'], $config['charset'])?>);" title="<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_EDIT, $config['charset'])?> <?=filter_html(ucfirst($view['fields'][$field]['viewname']), $config['charset'])?>" class="context_menu_link">
											<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_EDIT, $config['charset'])?> <?=filter_html(ucfirst($view['fields'][$field]['viewname']), $config['charset'])?>
										</a>
									<?php } else { ?>
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
										<textarea id="<?=filter_html_special($field, $config['charset'])?>" name="<?=filter_html($field, $config['charset'])?>" alt="<?=filter_html(ucfirst($view['fields'][$field]['viewname']), $config['charset'])?> <?=in_array($field, $view['required']) ? '(' . filter_html(NDPHP_LANG_MOD_WORD_REQUIRED, $config['charset']) . ')' : ''?>" <?php echo(in_array($field, $view['required']) ? 'required="required"' : NULL); ?> <?php if (!security_perm_check($security['perms'], 'U', $view['ctrl'], $field)) { echo('disabled'); }?> <?=$i ? '' : 'autofocus accesskey="' . filter_html(NDPHP_LANG_MOD_OP_ACCESS_KEY_AUTOFOCUS, $config['charset']) . '"'?> ><?=filter_html($value, $config['charset'])?></textarea>
									<?php } ?>
										<?php if ($view['fields'][$field]['help_desc'] != NULL): ?>
											<a href="<?=filter_html($view['fields'][$field]['help_url'], $config['charset'])?>" title="<?=filter_html($view['fields'][$field]['help_desc'], $config['charset'])?>">
												<img src="<?=filter_html(static_images_url(), $config['charset'])?>/themes/<?=filter_html($config['theme']['name'], $config['charset'])?>/icons/help_small.png" alt="<?=filter_html($view['fields'][$field]['help_desc'], $config['charset'])?>" />
											</a>
										<?php endif; ?>
									</td>
								</tr>
					<?php
							} else {
					?>
								<tr id="<?=filter_html_special($field, $config['charset'])?>_row" class="field_<?php echo($i % 2 ? 'even' : 'odd'); ?>">
									<td class="field_name"><?=filter_html(ucfirst($view['fields'][$field]['viewname']), $config['charset'])?>  <?php echo(in_array($field, $view['required']) ? '*' : NULL); ?></td>
									<td class="field_value">
										<?php if ($view['fields'][$field]['type'] == 'datetime') { $_datetime = explode(' ', $value); } ?>
										<input id="<?=filter_html_special($field, $config['charset'])?>" name="<?=filter_html($field, $config['charset'])?>" alt="<?=filter_html(ucfirst($view['fields'][$field]['viewname']), $config['charset'])?> <?=in_array($field, $view['required']) ? '(' . filter_html(NDPHP_LANG_MOD_WORD_REQUIRED, $config['charset']) . ')' : ''?>" <?php if ($view['fields'][$field]['type'] == 'varchar') { echo('maxlength="' . filter_html($view['fields'][$field]['max_length'], $config['charset']) . '"'); } ?> type="<?php if (strstr($field, 'password')) { echo('password'); } else { echo(filter_html($view['fields'][$field]['input_type'], $config['charset'])); } ?>" value="<?php echo($view['fields'][$field]['type'] == 'datetime' ? filter_html($_datetime[0], $config['charset']) : filter_html($value, $config['charset'])); ?>" <?php if (($view['fields'][$field]['type'] == 'datetime') || ($view['fields'][$field]['type'] == 'date')) { echo('placeholder="YYYY-MM-DD"'); } else if ($view['fields'][$field]['type'] == 'time') { echo('placeholder="HH:MM:SS"'); } ?> <?php echo(in_array($field, $view['required']) ? 'required="required"' : NULL); ?> <?php if (!security_perm_check($security['perms'], 'U', $view['ctrl'], $field)) { echo('disabled'); }?> <?=$i ? '' : 'autofocus accesskey="' . filter_html(NDPHP_LANG_MOD_OP_ACCESS_KEY_AUTOFOCUS, $config['charset']) . '"'?> />
										<?php if (strstr($field, 'password')): ?>
											<?=filter_html(NDPHP_LANG_MOD_COMMON_REPEAT, $config['charset'])?>: <input id="password_verification_<?=filter_html_special($field, $config['charset'])?>" alt="<?=filter_html(NDPHP_LANG_MOD_COMMON_PASSWORD_REPEAT, $config['charset'])?> (<?=filter_html(NDPHP_LANG_MOD_WORD_REQUIRED, $config['charset'])?>)" type="password" value="<?=filter_html($value, $config['charset'])?>" required="required" <?php if (!security_perm_check($security['perms'], 'U', $view['ctrl'], $field)) { echo('disabled'); }?> />
										<?php endif; ?>
										<?php if ($view['fields'][$field]['type'] == 'datetime'): ?>
											<input id="<?=filter_html_special($field, $config['charset'])?>_time" name="<?=filter_html($field, $config['charset'])?>_time" alt="<?=filter_html(ucfirst($view['fields'][$field]['viewname']), $config['charset'])?> <?=in_array($field, $view['required']) ? '(' . filter_html(NDPHP_LANG_MOD_WORD_REQUIRED, $config['charset']) . ')' : ''?>" type="<?=filter_html($view['fields'][$field]['input_type'], $config['charset'])?>" value="<?=filter_html($_datetime[1], $config['charset'])?>" placeholder="HH:MM:SS" <?php echo(in_array($field, $view['required']) ? 'required="required"' : NULL); ?> <?php if (!security_perm_check($security['perms'], 'U', $view['ctrl'], $field)) { echo('disabled'); }?> />
											<script type="text/javascript">
												jQuery("#<?=filter_js_special($field, $config['charset'])?>").datepicker();
												jQuery("#<?=filter_js_special($field, $config['charset'])?>").datepicker('option', 'dateFormat', 'yy-mm-dd');
												jQuery("#<?=filter_js_special($field, $config['charset'])?>").datepicker('setDate', '<?=filter_html_js_str($_datetime[0], $config['charset'])?>');
												jQuery("#<?=filter_js_special($field, $config['charset'])?>_time").timepicker();
												jQuery("#<?=filter_js_special($field, $config['charset'])?>").addClass('input_date');
												jQuery("#<?=filter_js_special($field, $config['charset'])?>_time").addClass('input_time');
											</script>
										<?php endif;?>
										<?php if ($view['fields'][$field]['type'] == 'date'): ?>
											<script type="text/javascript">
												jQuery("#<?=filter_js_special($field, $config['charset'])?>").datepicker();
												jQuery("#<?=filter_js_special($field, $config['charset'])?>").datepicker('option', 'dateFormat', 'yy-mm-dd');
												jQuery("#<?=filter_js_special($field, $config['charset'])?>").datepicker('setDate', '<?=filter_html_js_str($_datetime[0], $config['charset'])?>');
												jQuery("#<?=filter_js_special($field, $config['charset'])?>").addClass('input_date');
											</script>
										<?php endif; ?>
										<?php if ($view['fields'][$field]['type'] == 'time'): ?>
											<script type="text/javascript">
												jQuery("#<?=filter_js_special($field, $config['charset'])?>").timepicker();
												jQuery("#<?=filter_js_special($field, $config['charset'])?>").addClass('input_time');
											</script>
										<?php endif; ?>
										<?php if ($view['fields'][$field]['help_desc'] != NULL): ?>
											<a href="<?=filter_html($view['fields'][$field]['help_url'], $config['charset'])?>" title="<?=filter_html($view['fields'][$field]['help_desc'], $config['charset'])?>">
												<img src="<?=filter_html(static_images_url(), $config['charset'])?>/themes/<?=filter_html($config['theme']['name'], $config['charset'])?>/icons/help_small.png" alt="<?=filter_html($view['fields'][$field]['help_desc'], $config['charset'])?>" />
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
				<?php include('lib/multiple_edit.php'); ?>
			</div>
			<!-- End of Multiple relationships -->
			<!-- Begin of Mixed relationships -->
			<div id="mixed_relationships">
				<?php include('lib/mixed_edit.php'); ?>
			</div>
			<!-- End of Mixed relationships -->
		</div>
		<div class="edit_ops">
			<a href="javascript:void(0);" onclick="ndphp.form.submit_edit_wrapper(event, '<?=filter_html_js_str($view['ctrl'], $config['charset'])?>', 'editform', <?=isset($config['modalbox']) ? 1 : 0?>, '<?=filter_html_js_str($view['id'], $config['charset'])?>');" title="<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_UPDATE, $config['charset'])?>" class="context_menu_link">
				<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_UPDATE, $config['charset'])?>
			</a>
			<a href="javascript:void(0);" onclick="ndphp.form.cancel_edit(event, '<?=filter_html_js_str($view['ctrl'], $config['charset'])?>', <?=isset($config['modalbox']) ? 1 : 0?>, '<?=filter_html_js_str($view['id'], $config['charset'])?>');" title="<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_CANCEL, $config['charset'])?>" class="context_menu_link">
				<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_CANCEL, $config['charset'])?>
			</a>
			<?php if (isset($config['modalbox'])): ?>
				<a href="javascript:void(0);" onclick="ndphp.ajax.load_body_edit_frommodal(event, '<?=filter_html_js_str($view['ctrl'], $config['charset'])?>', <?=filter_html_js_special($view['id'], $config['charset'])?>);" title="<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_EXPAND, $config['charset'])?>" class="context_menu_link">
					<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_EXPAND, $config['charset'])?>
				</a>
				<script type="text/javascript">
					jQuery('#MB_content div.edit').css('width', '100%');
				</script>
			<?php endif; ?>
			<script type="text/javascript">
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
		</div>
	</form>
	<?php include('lib/tabs_footer.php'); ?>
</div>
