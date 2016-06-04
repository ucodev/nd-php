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
<!DOCTYPE html>
<html>
<head>
	<meta charset="<?=filter_html($config['charset'], $config['charset'])?>">
	<title><?=filter_html($view['title'], $config['charset'])?></title>
	<meta name="author" content="<?=filter_html($project['author'], $config['charset'])?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="description" content="<?=filter_html($view['description'], $config['charset'])?>" />
	<link rel="stylesheet" href="<?=filter_html(static_css_url(), $config['charset'])?>/themes/<?=filter_html($config['theme']['name'], $config['charset'])?>/main.css.php" type="text/css" />
	<link rel="stylesheet" href="<?=filter_html(static_js_url(), $config['charset'])?>/lib/jquery-ui/1.10.4/css/jquery-ui.css" type="text/css" />
	<script type="text/javascript" src="<?=filter_html(static_js_url(), $config['charset'])?>/lib/jquery/1.12.4/jquery.js"></script>
	<script type="text/javascript" src="<?=filter_html(static_js_url(), $config['charset'])?>/lib/jquery-ui/1.10.4/jquery-ui.js"></script>
	<script type="text/javascript">jQuery.noConflict();</script>
	<script type="text/javascript" src="<?=filter_html(static_js_url(), $config['charset'])?>/lib/jquery/validate/jquery.validate.js"></script>
	<script type="text/javascript" src="<?=filter_html(static_js_url(), $config['charset'])?>/lib/ndphp/ndphp.js.php"></script>
	<script type="text/javascript">
		jQuery.extend(jQuery.validator.messages, {
			required: "<br /><span style=\"color: red; font-size: 75%;\">* <?=filter_html_js_str(ucfirst(NDPHP_LANG_MOD_WORD_REQUIRED), $config['charset'])?></span>",
			maxlength: "<br /><span style=\"color: red; font-size: 75%;\">* <?=filer_html_js_str(NDPHP_LANG_MOD_ATTN_VALUE_LESS_THAN, $config['charset'])?> {0} <?=filter_html_js_str(NDPHP_LANG_MOD_WORD_CHARACTERS, $config['charset'])?></span>",
			minlength: "<br /><span style=\"color: red; font-size: 75%;\">* <?=filter_html_js_str(NDPHP_LANG_MOD_ATTN_VALUE_MORE_THAN, $config['charset'])?> {0} <?=filter_html_js_str(NDPHP_LANG_MOD_WORD_CHARACTERS, $config['charset'])?></span>",
			digits: "<br /><span style=\"color: red; font-size: 75%;\">* <?=filter_html_js_str(NDPHP_LANG_MOD_ATTN_ONLY_DIGITS, $config['charset'])?></span>",
		});
	</script>
</head>
<body class="confirm_sms">
<div id="confirm_sms" class="confirmsmsform">
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
		<a href="javascript:ndphp.form.submit_token('<?=filter_html_js_str(base_url()), $config['charset'])?>', 'register', 'confirm_sms_form');" title="<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_CONFIRM, $config['charset'])?>" class="register_button_link">
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
</div>
<div id="ajax_error_dialog">
</div>
</body>
</html>
