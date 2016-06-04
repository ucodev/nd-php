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
<form action="<?=filter_html(base_url(), $config['charset'])?>index.php/register/confirm_sms_token" name="confirm_sms_form" id="confirm_sms_form" method="post">
	<table id="confirm_sms_table">
		<tr id="confirm_sms_row">
			<td id="confirm_sms_key"><?=filter_html(NDPHP_LANG_MOD_REGISTER_CONFIRM_SMS_TOKEN, $config['charset'])?>:</td>
			<td id="confirm_sms_value"><input name="smstoken" type="text" required="required" minlength="6" maxlength="6" /></td>
		</tr>
	</table>
	<br />
	<input name="users_id" type="hidden" value="<?=filter_html($view['users_id'], $config['charset'])?>" />
	<div class="confirm_sms_actions">
		<a href="javascript:ndphp.form.submit_token('<?=filter_html(addslashes(base_url()), $config['charset'])?>', 'register', 'confirm_sms_form');" title="<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_CONFIRM, $config['charset'])?>" class="register_button_link">
			<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_CONFIRM, $config['charset'])?>
		</a>
		<!-- <input type="submit" value="Confirm" /> -->
	</div>
</form>
<script type="text/javascript">
	jQuery.validator.setDefaults({
		success: "valid"
	});
	jQuery("#confirm_sms_form").validate({
		rules: {
			smstoken: "digits"
		}
	});
</script>

