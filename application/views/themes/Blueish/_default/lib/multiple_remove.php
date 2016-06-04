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
		<fieldset class="remove_multiple_fieldset">
			<legend class="remove_multiple_legend">
				<?=filter_html(ucfirst($view['fields'][$field]['viewname']), $config['charset'])?>
			</legend>
			<table class="fields">
				<tr class="fields">
					<th class="fields"><?=filter_html(NDPHP_LANG_MOD_COMMON_CRUD_TITLE_FIELD_NAME, $config['charset'])?></th>
					<th class="fields"><?=filter_html(NDPHP_LANG_MOD_COMMON_CRUD_TITLE_FIELD_VALUE, $config['charset'])?></th>
				</tr>
				<tr class="field_<?php echo($i % 2 ? 'even' : 'odd'); ?>">
					<td class="field_name">
						<?=filter_html(ucfirst($view['fields'][$field]['viewname']), $config['charset'])?>
					</td>
					<td class="field_value">
					<?php
						foreach ($view['fields'][$field]['options'] as $opt_id => $opt_value):
							foreach ($values as $val_id => $val_value):
								if ($val_id == $opt_id):
					?>
									<a href="<?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($view['fields'][$field]['table'], $config['charset'])?>/view_data_modalbox/<?=filter_html($opt_id, $config['charset'])?>" onclick="Modalbox.show(this.href, {title: this.title, width: 600}); return false;" title="<?=filter_html(NDPHP_LANG_MOD_OP_LIST_VIEW_ITEM, $config['charset'])?> <?=filter_html($opt_value, $config['charset'])?>" class="view_rel_link">
										<?=filter_html($opt_value, $config['charset'])?>
									</a>
									<br />
					<?php
								endif;
							endforeach;
						endforeach;
					?>
					</td>
				</tr>
			</table>
		</fieldset>
	</div>
<?php endforeach; ?>