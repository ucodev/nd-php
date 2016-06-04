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
			equalTo: "<br /><span style=\"color: red; font-size: 75%;\">* <?=filter_html_js_str(NDPHP_LANG_MOD_INFO_PASSWORD_NO_MATCH, $config['charset'])?></span>",
			maxlength: "<br /><span style=\"color: red; font-size: 75%;\">* <?=filer_html_js_str(NDPHP_LANG_MOD_ATTN_VALUE_LESS_THAN, $config['charset'])?> {0} <?=filter_html_js_str(NDPHP_LANG_MOD_WORD_CHARACTERS, $config['charset'])?></span>",
			minlength: "<br /><span style=\"color: red; font-size: 75%;\">* <?=filter_html_js_str(NDPHP_LANG_MOD_ATTN_VALUE_MORE_THAN, $config['charset'])?> {0} <?=filter_html_js_str(NDPHP_LANG_MOD_WORD_CHARACTERS, $config['charset'])?></span>",
			digits: "<br /><span style=\"color: red; font-size: 75%;\">* <?=filter_html_js_str(NDPHP_LANG_MOD_ATTN_ONLY_DIGITS, $config['charset'])?></span>",
			email: "<br /><span style=\"color: red; font-size: 75%;\">* <?=filter_html_js_str(NDPHP_LANG_MOD_ATTN_NEED_VALID_EMAIL, $config['charset'])?></span>",
			min: "<br /><span style=\"color: red; font-size: 75%;\">* <?=filter_html_js_str(NDPHP_LANG_MOD_ATTN_SELECT_COUNTRY, $config['charset'])?></span>"
		});
	</script>
	<script type="text/javascript">

	</script>
</head>
<body class="register">
<div id="register" class="registerform">
<form action="<?=filter_html(base_url(), $config['charset'])?>index.php/register/newuser" name="registerform" id="registerform" method="post">
	<table id="newuser_table">
		<tr id="newuser_row_first_name">
			<td id="newuser_key_first_name"><?=filter_html(NDPHP_LANG_MOD_COMMON_FIRST_NAME, $config['charset'])?>:</td>
			<td id="newuser_value_first_name">
				<input id="first_name" name="first_name" type="text" required="required" minlength="2" maxlength="16" />
			</td>
		</tr>
		<tr id="newuser_row_last_name">
			<td id="newuser_key_last_name"><?=filter_html(NDPHP_LANG_MOD_COMMON_LAST_NAME, $config['charset'])?>:</td>
			<td id="newuser_value_last_name">
				<input id="last_name" name="last_name" type="text" required="required" minlength="2" maxlength="16" />
			</td>
		</tr>
		<tr id="newuser_row_company">
			<td id="newuser_key_company"><?=filter_html(NDPHP_LANG_MOD_COMMON_COMPANY_NAME, $config['charset'])?>:</td>
			<td id="newuser_value_company">
				<input id="company" name="company" type="text" minlength="2" maxlength="64" />
			</td>
		</tr>
		<tr id="newuser_row_username">
			<td id="newuser_key_username"><?=filter_html(NDPHP_LANG_MOD_COMMON_USERNAME, $config['charset'])?>:</td>
			<td id="newuser_value_username">
				<input id="username" name="username" type="text" required="required" minlength="6" maxlength="32" />
			</td>
		</tr>
		<tr id="newuser_row_password">
			<td id="newuser_key_password"><?=filter_html(NDPHP_LANG_MOD_COMMON_PASSWORD, $config['charset'])?>:</td>
			<td id="newuser_value_password">
				<input id="password" name="password" type="password" required="required" minlength="6" maxlength="32" />
			</td>
		</tr>
		<tr id="newuser_row_password_check">
			<td id="newuser_key_password_check"><?=filter_html(NDPHP_LANG_MOD_COMMON_PASSWORD_REPEAT, $config['charset'])?>:</td>
			<td id="newuser_value_password_check">
				<input id="password_check" name="password_check" type="password" required="required" minlength="6" maxlength="32" />
			</td>
		</tr>
		<tr id="newuser_row_country">
			<td id="newuser_key_country"><?=filter_html(NDPHP_LANG_MOD_COMMON_COUNTRY, $config['charset'])?>:</td>
			<td id="newuser_value_country">
				<select id="countries_id" name="countries_id">
					<option value="0">(<?=filter_html(ucfirst(NDPHP_LANG_MOD_WORD_SELECT), $config['charset'])?>...)</option>
				<?php foreach ($view['countries']->result_array() as $row): ?>
					<option value="<?=filter_html($row['id'], $config['charset'])?>">
						<?=filter_html($row['country'], $config['charset'])?> (<?=filter_html($row['code'], $config['charset'])?>)
					</option>
				<?php endforeach; ?>
				</select>
			</td>
		</tr>
		<tr id="newuser_row_email">
			<td id="newuser_key_email"><?=filter_html(NDPHP_LANG_MOD_COMMON_EMAIL, $config['charset'])?>:</td>
			<td id="newuser_value_email">
				<input id="email" name="email" type="text" required="required" minlength="6" maxlength="255" />
			</td>
		</tr>
		<tr id="newuser_row_phone">
			<td id="newuser_key_phone"><?=filter_html(NDPHP_LANG_MOD_COMMON_PHONE, $config['charset'])?>:</td>
			<td id="newuser_value_phone">
				<input id="phone" name="phone" type="text" required="required" minlength="8" maxlength="16" />
			</td>
		</tr>
		<tr id="newuser_row_vat">
			<td id="newuser_key_vat"><?=filter_html(NDPHP_LANG_MOD_COMMON_VAT_NUMBER_EU, $config['charset'])?>:</td>
			<td id="newuser_value_vat">
				<input id="vat" name="vat" type="text" minlength="10" maxlength="20" /><br />
			</td>
		</tr>

	</table>
	<script type="text/javascript">
		jQuery('#countries_id').change(function() {
			var country_id = jQuery('#countries_id').val();
			jQuery.ajax({
				type: "POST",
				url: "<?=filter_js_str(base_url(), $config['charset'])?>index.php/register/country_get_prefix/" + country_id,
				success: function(data) {
					jQuery("#phone").val(data);
				}
			});

			jQuery.ajax({
				type: "POST",
				url: "<?=filter_js_str(base_url(), $config['charset'])?>index.php/register/country_get_code/" + country_id,
				success: function(data) {
					jQuery("#vat").val(data);
				}
			});
		});
	</script>
	<br />
	<br />
	<div class="register_actions">
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
		<?php endif; ?>
		<br />
		<br />
		<?=filter_html(NDPHP_LANG_MOD_INFO_READ_ACCEPT_THE, $config['charset'])?> <a href="#"><?=filter_html(NDPHP_LANG_MOD_COMMON_TERMS_AND_CONDITIONS, $config['charset'])?></a> <?=filter_html(NDPHP_LANG_MOD_CONJ_AND_THE, $config['charset'])?> <a href="#"><?=filter_html(NDPHP_LANG_MOD_COMMON_PRIVACY_POLICY, $config['charset'])?></a>:
		<br />
		<input id="terms" name="terms" type="checkbox" value="1" />
		<br />
		<br />
		<br />
		<a href="javascript:void(0);" onclick="ndphp.form.submit_register(event, 'register', 'registerform');" title="<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_REGISTER, $config['charset'])?>" class="register_button_link">
			<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_REGISTER, $config['charset'])?>
		</a>
		<!-- <input type="submit" value="Register" /> -->
	</div>
</form>
<script type="text/javascript">
	jQuery.validator.setDefaults({
		success: "valid"
	});
	jQuery("#registerform").validate({
		rules: {
			password: "required",
			password_check: {
				equalTo: "#password"
			},
			countries_id: {
				min: "1"
			},
			email: "email",
			phone: "digits",
			terms: "required"
		}
	});
</script>
</div>
<div id="ajax_error_dialog">
</div>
</body>
</html>
