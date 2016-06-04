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
<html class="login">
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
	<script type="text/javascript" src="<?=filter_html(static_js_url(), $config['charset'])?>/lib/jquery/blockui/blockui.js"></script>
	<script type="text/javascript" src="<?=filter_html(static_js_url(), $config['charset'])?>/lib/jquery/validate/jquery.validate.js"></script>
	<script type="text/javascript" src="<?=filter_html(static_js_url(), $config['charset'])?>/lib/ndphp/ndphp.js.php"></script>
	<script type="text/javascript">
		/* Set theme */
		ndphp.theme.set("<?=filter_js_str($config['theme']['name'], $config['charset'])?>");

		/* Set animation types */
		ndphp.animation.set_default_type('None');
		ndphp.animation.set_ordering_type('None');
	</script>
</head>
<body class="login">
	<div id="login_logo" class="login_logo">
		<img class="login_logo" src="<?=filter_html(static_images_url(), $config['charset'])?>/logo.png" alt="ND PHP Framework Logo" />
		<span class="login_logo_project_name"><?=filter_html($project['name'], $config['charset'])?></span>
		<span class="login_logo_tagline"><?=filter_html($project['tagline'], $config['charset'])?></span>
	</div>
	<div id="login" class="loginform">
		<form action="<?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($view['ctrl'], $config['charset'])?>/authenticate" name="loginform" id="loginform" method="post">
			<input name="referer" type="hidden" value="<?=filter_html(strip_tags($view['referer']), $config['charset'])?>" />
			<span class="login_username"><?=filter_html(NDPHP_LANG_MOD_LOGIN_USERNAME, $config['charset'])?></span><br />
			<input name="username" alt="<?=filter_html(NDPHP_LANG_MOD_LOGIN_USERNAME, $config['charset'])?>" type="text" required="required" minlength="5" maxlength="32" onkeyup="ndphp.utils.crlf_callback(event, ndphp.form.submit_login);" autofocus accesskey="<?=filter_html(NDPHP_LANG_MOD_OP_ACCESS_KEY_AUTOFOCUS, $config['charset'])?>" /><br /><br />
			<span class="login_password"><?=filter_html(NDPHP_LANG_MOD_LOGIN_PASSWORD, $config['charset'])?></span><br/>
			<input name="password" alt="<?=filter_html(NDPHP_LANG_MOD_LOGIN_PASSWORD, $config['charset'])?>" type="password" required="required" minlength="5" maxlength="32" onkeyup="ndphp.utils.crlf_callback(event, ndphp.form.submit_login);" /><br />
			<?php foreach ($view['fields_extra'] as $field): ?>
				<br />
				<span class="login_extra_field"><?=filter_html($field['viewname'], $config['charset'])?></span><br/>
				<input name="<?=filter_html($field['input_name'], $config['charset'])?>" alt="<?=filter_html($field['viewname'], $config['charset'])?>" type="<?=filter_html($field['input_type'], $config['charset'])?>" <?=$field['required'] ? 'required="required"' : ''?> onkeyup="ndphp.utils.crlf_callback(event, ndphp.form.submit_login);" /><br />
			<?php endforeach; ?>
			<br /><br />
			<div class="login_actions">
				<a href="javascript:void(0);" onclick="ndphp.form.submit_login(event);" title="<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_LOGIN, $config['charset'])?>" class="login_button_link">
					<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_LOGIN, $config['charset'])?>
				</a>
			</div>
		</form>
		<br />
		<br />
		<p style="display: table; margin: 0 auto;">
			[<a href="<?=filter_html(base_url(), $config['charset'])?>index.php/register"><?=filter_html(NDPHP_LANG_MOD_LOGIN_NEW_USER, $config['charset'])?></a>]
			&nbsp;&nbsp;&nbsp;
			[<a href="<?=filter_html(base_url(), $config['charset'])?>index.php/register/recover_password_form"><?=filter_html(NDPHP_LANG_MOD_LOGIN_FORGOT_PASSWORD, $config['charset'])?></a>]
		</p>
		<script type="text/javascript">
			jQuery.extend(jQuery.validator.messages, {
				required: "<br /><span style=\"color: red; font-size: 75%;\">* <?=filter_html_js_str(ucfirst(NDPHP_LANG_MOD_WORD_REQUIRED), $config['charset'])?></span>",
				maxlength: "<br /><span style=\"color: red; font-size: 75%;\">* <?=filter_html_js_str(NDPHP_LANG_MOD_ATTN_VALUE_LESS_THAN, $config['charset'])?> {0} <?=filter_html_js_str(NDPHP_LANG_MOD_WORD_CHARACTERS, $config['charset'])?></span>",
				minlength: "<br /><span style=\"color: red; font-size: 75%;\">* <?=filter_html_js_str(NDPHP_LANG_MOD_ATTN_VALUE_MORE_THAN, $config['charset'])?> {0} <?=filter_html_js_str(NDPHP_LANG_MOD_WORD_CHARACTERS, $config['charset'])?></span>",
			});

			jQuery.validator.setDefaults({
				success: "valid"
			});
			jQuery("#loginform").validate({
				rules: {
					username: "required",
					password: "required"
				}
			});
		</script>
	</div>
	<div id="ajax_error_dialog">
	</div>
</body>
</html>
