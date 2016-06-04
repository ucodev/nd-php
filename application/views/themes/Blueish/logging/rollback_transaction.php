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
<div id="confirmation_rollback">
	<div id="confirmation_info">
		<fieldset class="confirmation_fieldset">
			<legend class="confirmation_legend"><?=filter_html(NDPHP_LANG_MOD_BLOCK_ROLLBACK_TRANSACTION, $config['charset'])?></legend>
			<p><?=filter_html(NDPHP_LANG_MOD_INFO_ROLLBACK_TRANSACTION, $config['charset'])?> <strong><?=filter_html($view['transaction'], $config['charset'])?></strong>.</p>
			<br />
			<p style="font-weight: bold; text-align: center; font-size: 120%;"><?=filter_html(NDPHP_LANG_MOD_INFO_ROLLBACK_CHANGES, $config['charset'])?></p>
			<table style="margin: 0 auto; text-align: center;">
				<thead>
					<tr>
						<th style="border: 1px solid #333333; padding-left: 5px; padding-right: 5px; white-space: nowrap;"><?=filter_html(NDPHP_LANG_MOD_COMMON_TABLE, $config['charset'])?></th>
						<th style="border: 1px solid #333333; padding-left: 5px; padding-right: 5px; white-space: nowrap;"><?=filter_html(NDPHP_LANG_MOD_COMMON_FIELD, $config['charset'])?></th>
						<th style="border: 1px solid #333333; padding-left: 5px; padding-right: 5px; white-space: nowrap;"><?=filter_html(NDPHP_LANG_MOD_COMMON_ENTRY_ID, $config['charset'])?></th>
						<th style="border: 1px solid #333333; padding-left: 5px; padding-right: 5px; white-space: nowrap;"><?=filter_html(NDPHP_LANG_MOD_COMMON_VALUE_OLD, $config['charset'])?></th>
						<th style="border: 1px solid #333333; padding-left: 5px; padding-right: 5px; white-space: nowrap;"><?=filter_html(NDPHP_LANG_MOD_COMMON_VALUE_NEW, $config['charset'])?></th>
						<th style="border: 1px solid #333333; padding-left: 5px; padding-right: 5px; white-space: nowrap;"><?=filter_html(NDPHP_LANG_MOD_COMMON_REGISTERED, $config['charset'])?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($view['changes'] as $change): ?>
						<tr>
							<td style="border: 1px solid #333333;"><?=filter_html($change['_table'], $config['charset'])?></td>
							<td style="border: 1px solid #333333;"><?=filter_html($change['_field'], $config['charset'])?></td>
							<td style="border: 1px solid #333333;"><?=filter_html($change['entryid'], $config['charset'])?></td>
							<td style="border: 1px solid #333333;"><?=filter_html($change['value_old'], $config['charset'])?></td>
							<td style="border: 1px solid #333333;"><?=filter_html($change['value_new'], $config['charset'])?></td>
							<td style="border: 1px solid #333333;"><?=filter_html($change['registered'], $config['charset'])?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<br />
			<p style="color: red; font-size: 120%; font-weight: bold; text-align: center;">
				<?=filter_html(NDPHP_LANG_MOD_ATTN_ROLLBACK_CONFIRM, $config['charset'])?>
				<br />
				<span style="color: black; font-size: 60%; text-align: center;">(<?=filter_html(NDPHP_LANG_MOD_INFO_ROLLBACK_NOTE, $config['charset'])?>)</span>
			</p>
			<br />
		</fieldset>
	</div>
	<div class="confirmation_ops">
		<!-- The Cancel and Confirm buttons are switched on purpose: This is an important confirmation and user attention is required -->
		<a href="javascript:void(0);" onclick="ndphp.form.cancel_confirmation(event, <?=isset($config['modalbox']) ? 1 : 0?>);" title="<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_CANCEL, $config['charset'])?>" class="context_menu_link">
			<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_CANCEL, $config['charset'])?>
		</a>
		&nbsp;&nbsp;&nbsp;&nbsp;
		<a href="javascript:void(0);" onclick="ndphp.form.submit_confirmation(event, '<?=filter_html_js_str($view['ctrl'], $config['charset'])?>', 'rollback/<?=filter_html_js_str($view['transaction'], $config['charset'])?>', <?=isset($config['modalbox']) ? 1 : 0?>);" title="<?=filter_html_js_str(NDPHP_LANG_MOD_OP_CONTEXT_CONFIRM, $config['charset'])?>" class="context_menu_link">
			<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_CONFIRM, $config['charset'])?>
		</a>
	</div>
</div>