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
 <html>
 <head>
  <title>Account Registration Confirmation</title>
 </head>
 <body>
  Greetings <?=filter_html($view['first_name'], $config['charset'])?>,<br />
  <br />
  Welcome to <?=filter_html($project['name'], $config['charset'])?>!<br />
  <br />
  Please copy the link below into your browser's address bar in order to confirm your email:<br />
  <br />
  <a href="<?=filter_html($view['confirm_email_url'], $config['charset'])?>"><?=filter_html($view['confirm_email_url'], $config['charset'])?></a><br />
  <br />
  <?php if ($view['register_confirm_phone'] != '0'): ?>
    Hopefully, you've also received a SMS with a confirmation token in your mobile in order to activate your account. If you weren't automatically redirected to the confirmation form after the register data submission, you may want to access the following link:<br />
    <br />
    <a href="<?=filter_html($view['confirm_sms_url'], $config['charset'])?>"><?=filter_html($view['confirm_sms_url'], $config['charset'])?></a><br />
    <br />
    If you've not received the SMS, please send us an email to <a href="mailto:no-support@nd-php.org">no-support@nd-php.org</a> from your registered email address with your mobile number in the email body, so we can help you finishing the activation process. Remember that the mobile number format must include your country prefix, excluding the plus (+) sign (Eg: 351961112233 for a portuguese mobile number).<br />
    <br />
    Note that your account will be considered active only when both mobile number and email address are confirmed.<br />
    <br />
  <?php else: ?>
    Note that your account will be considered active only when your email address is confirmed.<br />
    <br />
  <?php endif; ?>
  If you need any kind of support regarding the integration of our services with your applications, please contact us through <a href="mailto:no-support@nd-php.org">no-support@nd-php.org</a>. We'll be glad to help!<br />
  <br />
  Thank you for your preference.<br />
  <br />
  --<br />
  ND PHP Framework<br />
  <a href="https://www.nd-php.org">https://www.nd-php.org</a><br />
 </body>
</html>

