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
<div id="import_csv" class="import_csv">
	<form action="<?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($view['ctrl'], $config['charset'])?>/import/csv" id="importcsvform" name="importcsvform" enctype="multipart/form-data" method="post">
		<div class="fields">
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
						<tr class="field_odd">
							<td class="field_name"><?=filter_html(NDPHP_LANG_MOD_COMMON_IMPORT_CSV_FILE, $config['charset'])?></td>
							<td class="field_value"><input type="file" name="import_csv_file" /></td>
						</tr>
						<tr class="field_even">
							<td class="field_name"><?=filter_html(NDPHP_LANG_MOD_COMMON_IMPORT_CSV_TEXT, $config['charset'])?></td>
							<td class="field_value"><textarea name="import_csv_text" /></textarea></td>
						</tr>
						<tr class="field_odd">
							<td class="field_name"><?=filter_html(NDPHP_LANG_MOD_COMMON_IMPORT_CSV_REL_TYPE, $config['charset'])?></td>
							<td class="field_value">
								<select name="import_csv_rel_type">
									<option value="value">Value</option>
									<option value="id">ID</option>
								</select>
							</td>
						</tr>
						<tr class="field_even">
							<td class="field_name"><?=filter_html(NDPHP_LANG_MOD_COMMON_IMPORT_CSV_SEP, $config['charset'])?></td>
							<td class="field_value">
								<select name="import_csv_sep">
									<option value=",">,</option>
									<option value=";">;</option>
								</select>
							</td>
						</tr>
						<tr class="field_odd">
							<td class="field_name"><?=filter_html(NDPHP_LANG_MOD_COMMON_IMPORT_CSV_DELIM, $config['charset'])?></td>
							<td class="field_value">
								<select name="import_csv_delim">
									<option value="&quot;">&quot;</option>
									<option value="'">'</option>
								</select>
							</td>
						</tr>
						<tr class="field_even">
							<td class="field_name"><?=filter_html(NDPHP_LANG_MOD_COMMON_IMPORT_CSV_ESC, $config['charset'])?></td>
							<td class="field_value">
								<select name="import_csv_esc">
									<option value="\">\</option>
								</select>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<div class="import_csv_ops">
			<a href="javascript:void(0);" onclick="ndphp.form.submit_import_csv(event, '<?=filter_html_js_str($view['ctrl'], $config['charset'])?>', 'importcsvform', <?=isset($config['modalbox']) ? 1 : 0?>);" title="<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_CONFIRM, $config['charset'])?>" class="context_menu_link">
				<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_CONFIRM, $config['charset'])?>
			</a>
			<a href="javascript:void(0);" onclick="ndphp.form.cancel_import_csv(event, '<?=filter_html_js_str($view['ctrl'], $config['charset'])?>', <?=isset($config['modalbox']) ? 1 : 0?>);" title="<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_CANCEL, $config['charset'])?>" class="context_menu_link">
				<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_CANCEL, $config['charset'])?>
			</a>
		</div>
	</form>
</div>