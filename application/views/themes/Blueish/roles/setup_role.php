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
<div id="setup_role" class="setup_role">
	<fieldset class="setup_role">
		<legend class="setup_role"><?=$view['role_name']?></legend>
		<form action="<?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($view['ctrl'], $config['charset'])?>/setup_role_update" name="setuproleform" id="setuproleform" method="post">
			<input name="role_id" type="hidden" value="<?=filter_html($view['role'], $config['charset'])?>" />
			<table class="setup_role">
			<?php foreach ($view['tables'] as $table => $fields): ?>
				<tr class="setup_role_table_head">
					<td class="setup_role_table_head">Table</td>
					<td class="setup_role_table_head">Create</td>
					<td class="setup_role_table_head">Read</td>
					<td class="setup_role_table_head">Update</td>
					<td class="setup_role_table_head">Delete</td>
				</tr>
				<tr class="setup_role_table_content">
					<td class="setup_role_table_content"><?=$table?></td>
					<td>
						<input name="perm_create_table_<?=filter_html($table, $config['charset'])?>" type="hidden" value="0" />
						<input name="perm_create_table_<?=filter_html($table, $config['charset'])?>" type="checkbox" value="1"
						<?php if (isset($view['table_perms'][$table]) && (strpos($view['table_perms'][$table], 'C') !== false)): ?>
							checked="checked"
						<?php endif; ?>
						 />
					</td>
					<td>
						<input name="perm_read_table_<?=filter_html($table, $config['charset'])?>" type="hidden" value="0" />
						<input name="perm_read_table_<?=filter_html($table, $config['charset'])?>" type="checkbox" value="1"
						<?php if (isset($view['table_perms'][$table]) && (strpos($view['table_perms'][$table], 'R') !== false)): ?>
							checked="checked"
						<?php endif; ?>
						 />
					</td>					
					<td>
						<input name="perm_update_table_<?=filter_html($table, $config['charset'])?>" type="hidden" value="0" />
						<input name="perm_update_table_<?=filter_html($table, $config['charset'])?>" type="checkbox" value="1"
						<?php if (isset($view['table_perms'][$table]) && (strpos($view['table_perms'][$table], 'U') !== false)): ?>
							checked="checked"
						<?php endif; ?>
						 />
					</td>					
					<td>
						<input name="perm_delete_table_<?=filter_html($table, $config['charset'])?>" type="hidden" value="0" />
						<input name="perm_delete_table_<?=filter_html($table, $config['charset'])?>" type="checkbox" value="1"
						<?php if (isset($view['table_perms'][$table]) && (strpos($view['table_perms'][$table], 'D') !== false)): ?>
							checked="checked"
						<?php endif; ?>
						 />
					</td>					
				</tr>
				<tr class="setup_role_column_head">
					<td class="setup_role_column_head">Column</td>
					<td class="setup_role_column_head">Create</td>
					<td class="setup_role_column_head">Read</td>
					<td class="setup_role_column_head">Update</td>
					<td class="setup_role_column_head">Search</td>
				</tr>
				<?php foreach ($fields as $field_nr => $field_name): ?>
					<tr class="setup_role_column_content">
						<td class="setup_role_column_content"><?=$field_name?></td>
						<td>
							<input name="perm_create_field_<?=filter_html($table, $config['charset'])?>-<?=filter_html($field_name, $config['charset'])?>" type="hidden" value="0" />
							<input name="perm_create_field_<?=filter_html($table, $config['charset'])?>-<?=filter_html($field_name, $config['charset'])?>" type="checkbox" value="1"
							<?php if (isset($view['table_col_perms'][$table][$field_name]) && (strpos($view['table_col_perms'][$table][$field_name], 'C') !== false)): ?>
								checked="checked"
							<?php endif; ?>
							 />
						</td>
						<td>
							<input name="perm_read_field_<?=filter_html($table, $config['charset'])?>-<?=filter_html($field_name, $config['charset'])?>" type="hidden" value="0" />
							<input name="perm_read_field_<?=filter_html($table, $config['charset'])?>-<?=filter_html($field_name, $config['charset'])?>" type="checkbox" value="1"
							<?php if (isset($view['table_col_perms'][$table][$field_name]) && (strpos($view['table_col_perms'][$table][$field_name], 'R') !== false)): ?>
								checked="checked"
							<?php endif; ?>
							 />
						</td>
						<td>
							<input name="perm_update_field_<?=filter_html($table, $config['charset'])?>-<?=filter_html($field_name, $config['charset'])?>" type="hidden" value="0" />
							<input name="perm_update_field_<?=filter_html($table, $config['charset'])?>-<?=filter_html($field_name, $config['charset'])?>" type="checkbox" value="1"
							<?php if (isset($view['table_col_perms'][$table][$field_name]) && (strpos($view['table_col_perms'][$table][$field_name], 'U') !== false)): ?>
								checked="checked"
							<?php endif; ?>
							 />
						</td>
						<td>
							<input name="perm_search_field_<?=filter_html($table, $config['charset'])?>-<?=filter_html($field_name, $config['charset'])?>" type="hidden" value="0" />
							<input name="perm_search_field_<?=filter_html($table, $config['charset'])?>-<?=filter_html($field_name, $config['charset'])?>" type="checkbox" value="1"
							<?php if (isset($view['table_col_perms'][$table][$field_name]) && (strpos($view['table_col_perms'][$table][$field_name], 'S') !== false)): ?>
								checked="checked"
							<?php endif; ?>
							 />
						</td>
					</tr>
				<?php endforeach; ?>
				<tr class="setup_role_separator">
					<td class="setup_role_separator">&nbsp;</td>
					<td class="setup_role_separator">&nbsp;</td>
					<td class="setup_role_separator">&nbsp;</td>
					<td class="setup_role_separator">&nbsp;</td>
					<td class="setup_role_separator">&nbsp;</td>
				</tr>
			<?php endforeach; ?>
			</table>
			<input type="submit" />
		</form>
	</fieldset>
</div>
