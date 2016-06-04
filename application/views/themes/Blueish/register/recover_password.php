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
	<script type="text/javascript">
		jQuery.extend(jQuery.validator.messages, {
			required: "<br /><span style=\"color: red; font-size: 75%;\">* <?=filter_html_js_str(ucfirst(NDPHP_LANG_MOD_WORD_REQUIRED), $config['charset'])?></span>",
			equalTo: "<br /><span style=\"color: red; font-size: 75%;\">* <?=filter_html_js_str(NDPHP_LANG_MOD_INFO_PASSWORD_NO_MATCH, $config['charset'])?></span>",
			maxlength: "<br /><span style=\"color: red; font-size: 75%;\">* <?=filer_html_js_str(NDPHP_LANG_MOD_ATTN_VALUE_LESS_THAN, $config['charset'])?> {0} <?=filter_html_js_str(NDPHP_LANG_MOD_WORD_CHARACTERS, $config['charset'])?></span>",
			minlength: "<br /><span style=\"color: red; font-size: 75%;\">* <?=filter_html_js_str(NDPHP_LANG_MOD_ATTN_VALUE_MORE_THAN, $config['charset'])?> {0} <?=filter_html_js_str(NDPHP_LANG_MOD_WORD_CHARACTERS, $config['charset'])?></span>",
			digits: "<br /><span style=\"color: red; font-size: 75%;\">* <?=filter_html_js_str(NDPHP_LANG_MOD_ATTN_ONLY_DIGITS, $config['charset'])?></span>",
			email: "<br /><span style=\"color: red; font-size: 75%;\">* <?=filter_html_js_str(NDPHP_LANG_MOD_ATTN_NEED_VALID_EMAIL, $config['charset'])?></span>",
			min: "<br /><span style=\"color: red; font-size: 75%;\">* <?=filter_html_js_str(NDPHP_LANG_MOD_ATTN_SELECT_COUNTRY, $config['charset'])?></span>"
		});
	</script>
	<script type="text/javascript">
		submit_recover = function(base_url, ctrl, form_id) {
			if (!jQuery("#" + form_id).valid())
				return;

			jQuery.ajax({
				type: "POST",
				url: base_url + "index.php/" + ctrl + "/recover_password/",
				data: jQuery("#" + form_id).serialize(),
				success: function(data) {
					jQuery("#recover_password").html(data);
				},
				error: function(xhr, ajaxOptions, thrownError) {
					jQuery("#ajax_error_dialog").html(xhr.responseText);
					jQuery("#ajax_error_dialog").dialog({ modal: true, title: '<?=filter_html_js_str(NDPHP_LANG_MOD_ATTN_REVIEW_ISSUES, $config['charset'])?>' });
					Recaptcha.reload();
				}
			});
		}
	</script>
</head>
<body class="recover_password">
<div id="recover_password" class="recoverpasswordform">
<form action="<?=filter_html(base_url(), $config['charset'])?>index.php/register/recover_password" name="recover_password_form" id="recover_password_form" method="post">
	<table id="recover_password_table">
		<tr id="recover_password_row">
			<td id="recover_password_key"><?=filter_html(NDPHP_LANG_MOD_RECOVER_REGISTERED_EMAIL, $config['charset'])?></td>
			<td id="recover_password_value"><input name="email" type="text" required="required" minlength="6" /></td>
		</tr>
		<tr id="recover_password_row">
			<td id="recover_password_key"><?=filter_html(NDPHP_LANG_MOD_RECOVER_REGISTERED_PHONE, $config['charset'])?></td>
			<td id="recover_password_value"><input name="phone" type="text" required="required" minlength="8" /></td>
		</tr>
		<tr id="recover_password_row">
			<td id="recover_password_key"><?=filter_html(NDPHP_LANG_MOD_RECOVER_FIRST_NAME, $config['charset'])?></td>
			<td id="recover_password_value"><input name="first_name" type="text" required="required" minlength="2" /></td>
		</tr>
		<tr id="recover_password_row">
			<td id="recover_password_key"><?=filter_html(NDPHP_LANG_MOD_RECOVER_LAST_NAME, $config['charset'])?></td>
			<td id="recover_password_value"><input name="last_name" type="text" required="required" minlength="2" /></td>
		</tr>
		<tr id="recover_password_row">
			<td id="recover_password_key"><?=filter_html(NDPHP_LANG_MOD_RECOVER_COUNTRY, $config['charset'])?></td>
			<td id="recover_password_value">
				<select id="countries_id" name="countries_id">
					<option value="0">(<?=filter_html(ucfirst(NDPHP_LANG_MOD_WORD_SELECT), $config['charset'])?>...)</option>
				<?php foreach ($countries->result_array() as $row): ?>
					<option value="<?=filter_html($row['id'], $config['charset'])?>">
						<?=filter_html($row['country'], $config['charset'])?> (<?=filter_html($row['code'], $config['charset'])?>)
					</option>
				<?php endforeach; ?>
				</select>
			</td>
		</tr>
	</table>
	<br />
	<div class="recover_password_actions">
		<br />
		<br />
		<?php if ($config['use_recaptcha'] == 1): ?>
			<center>
				<script type="text/javascript">
					var RecaptchaOptions = {
						theme: 'white'
					};
				</script>
				<div id="recaptcha_input">
					<?php echo(recaptcha_get_html($config['recaptcha_public_key'], NULL, true)); ?>
				</div>
			</center>
			<br />
			<br />
		<?php endif; ?>
		<br />
		<a href="javascript:submit_recover('<?=filter_html_js_str(base_url(), $config['charset'])?>', 'register', 'recover_password_form');" title="Confirm" class="register_button_link">
			<?=filter_html(ucfirst(NDPHP_LANG_MOD_WORD_RECOVER), $config['charset'])?>
		</a>
		<!-- <input type="submit" value="Confirm" /> -->
	</div>
</form>
<script type="text/javascript">
	jQuery.validator.setDefaults({
		success: "valid"
	});
	jQuery("#recover_password_form").validate({
		rules: {
			phone: "digits",
			countries_id: {
				min: "1"
			},
			email: "email",
			phone: "digits"
		}
	});
</script>
</div>
<div id="ajax_error_dialog">
</div>
</body>
</html>
