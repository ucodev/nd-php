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

<?php foreach ($view['rel'] as $field => $values): ?>
	<div id="multiple_<?=filter_html_special($field, $config['charset'])?>_container" style="display: table; margin: 0 auto; padding-top: 34px;">
		<fieldset class="edit_multiple_fieldset">
			<legend class="edit_multiple_legend">
				<?=filter_html(ucfirst($view['fields'][$field]['viewname']), $config['charset'])?>
			</legend>
			<table id="<?=filter_html_special($field, $config['charset'])?>_row_select" class="field_select_multiple">
				<tbody>
					<tr>
						<td>
							<select id="<?=filter_html_special($field, $config['charset'])?>" multiple <?php if (!security_perm_check($security['perms'], 'U', $view['ctrl'], $field)) { echo('disabled'); }?> >
								<?php
								foreach ($view['fields'][$field]['options'] as $opt_id => $opt_value):
									$selected = false;
									foreach ($values as $val_id => $val_value) {
										if ($val_id == $opt_id)
											$selected = true;
									}
									if ($selected === true)
										continue;
											
								?>
									<option value="<?=filter_html($opt_id, $config['charset'])?>"><?=filter_html($opt_value, $config['charset'])?></option>
								<?php
								endforeach;
								?>
							</select>
						</td>
						<td>
				 			<center>
					 			<input type="button" onclick="ndphp.multi.select_multi_add_selected('<?=filter_html_js_str($field, $config['charset'])?>', '<?=filter_html_js_str($field, $config['charset'])?>_selected');" value=">>" />
					 			<br />
					 			<input type="button" onclick="ndphp.multi.select_multi_del_selected('<?=filter_html_js_str($field, $config['charset'])?>_selected', '<?=filter_html_js_str($field, $config['charset'])?>');" value="<<" />
					 		</center>
						</td>
						<td>
							<select id="<?=filter_html_special($field, $config['charset'])?>_selected" name="<?=filter_html($field, $config['charset'])?>[]" multiple <?php if (!security_perm_check($security['perms'], 'U', $view['ctrl'], $field)) { echo('disabled'); }?> >
								<?php
								foreach ($view['fields'][$field]['options'] as $opt_id => $opt_value):
									$selected = false;
									foreach ($values as $val_id => $val_value) {
										if ($val_id == $opt_id)
											$selected = true;
									}
									if ($selected === false)
										continue;
											
								?>
									<option value="<?=filter_html($opt_id, $config['charset'])?>"><?=filter_html($opt_value, $config['charset'])?></option>
								<?php
								endforeach;
								?>
							</select>
						</td>
						<td>
							<?php if ($view['fields'][$field]['help_desc'] != NULL): ?>
								<a href="<?=filter_html($view['fields'][$field]['help_url'], $config['charset'])?>" title="<?=filter_html($view['fields'][$field]['help_desc'], $config['charset'])?>">
									<img src="<?=filter_html(static_images_url(), $config['charset'])?>/themes/<?=filter_html($config['theme']['name'], $config['charset'])?>/icons/help_small.png" alt="<?=filter_html($view['fields'][$field]['help_desc'], $config['charset'])?>" /></a>
							<?php endif; ?>
							<?php if (!isset($config['modalbox'])): ?>
								<a href="<?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($view['fields'][$field]['table'], $config['charset'])?>/create_data_modalbox" title="New <?=filter_html(ucfirst($view['fields'][$field]['table']), $config['charset'])?>" onclick="Modalbox.show(this.href, {title: this.title, width: 800}); return false;">
									<img height="20" width="20" class="create_op_icon" alt="<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_NEW, $config['charset'])?> <?=filter_html(ucfirst($view['fields'][$field]['table']), $config['charset'])?>" src="<?=filter_html(static_images_url(), $config['charset'])?>/themes/<?=filter_html($config['theme']['name'], $config['charset'])?>/icons/more.png" />
								</a>
							<?php endif; ?>
						</td>
					</tr>
				</tbody>
			</table>
		</fieldset>
	</div>
<?php endforeach; ?>