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
	foreach ($rel as $rel_field):
		$field = $rel_field['field'];
		$meta = $rel_field['meta'];
?>
	<div class="tab-pane fade" id="multiple_<?=filter_html_special($field, $config['charset'])?>_container">
		<fieldset class="form_fieldset">
			<div class="form-group valign">
				<label for="" class="col-sm-2 control-label">
				</label>
				<div class="col-sm-3">
					<select class="form-control" id="<?=filter_html_special($field, $config['charset'])?>" multiple>
						<?php foreach ($meta['options'] as $opt_id => $opt_value): ?>
								<option <?php if ($default[$field] == $opt_id) { echo("selected=\"selected\""); } ?> value="<?=filter_html($opt_id, $config['charset'])?>">
									<?=filter_html($opt_value, $config['charset'])?>
								</option>
						<?php endforeach; ?>
					</select>
				</div>

				<div class="col-sm-1">
					<input
						style="width: 100%"
						class="btn btn-primary btn-sm"
						type="button"
						onclick="ndphp.multi.select_multi_add_selected('<?=filter_html_js_str($field, $config['charset'])?>', '<?=filter_html_js_str($field, $config['charset'])?>_selected');"
						value=">>"
					/>
					<br />
					<input
						style="width: 100%"
						class="btn btn-cancel btn-sm"
						type="button"
						onclick="ndphp.multi.select_multi_del_selected('<?=filter_html_js_str($field, $config['charset'])?>_selected', '<?=filter_html_js_str($field, $config['charset'])?>');"
						value="<<"
					/>
				</div>

				<div class="col-sm-3">
					<select class="form-control" id="<?=filter_html_special($field, $config['charset'])?>_selected" name="<?=filter_html($field, $config['charset'])?>[]" multiple>
					</select>
				</div>

				<div class="col-sm-1">
					<?php if ($meta['help_desc'] != NULL): ?>
						<a
							href="<?=filter_html($meta['help_url'], $config['charset'])?>"
							title="<?=filter_html($meta['help_desc'], $config['charset'])?>"
						>
							<img
								src="<?=filter_html(static_images_url(), $config['charset'])?>/themes/<?=filter_html($config['theme']['name'], $config['charset'])?>/icons/help_small.png"
								alt="<?=filter_html($meta['help_desc'], $config['charset'])?>"
							/>
						</a>
					<?php endif; ?>
					<?php if (!isset($config['modalbox'])): ?>
						<a
							class="btn btn-primary btn-sm"
							href="<?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($meta['table'], $config['charset'])?>/create_data_modalbox"
							title="<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_NEW, $config['charset'])?> <?=filter_html(ucfirst($meta['table']), $config['charset'])?>"
							onclick="ndphp.modal.show(this.href, '<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_NEW, $config['charset'])?> <?=filter_html(ucfirst($meta['table']), $config['charset'])?>'); return false;"
						>
							+
						</a>
					<?php endif; ?>
				</div>
			</div>
		</fieldset>
	</div>
<?php endforeach; ?>