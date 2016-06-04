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
  <title>Credentials Recover</title>
 </head>
 <body>
  Dear <?=filter_html($view['first_name'], $config['charset'])?>,<br />
  <br />
  You've requested a credentials recover for your <?=filter_html($project['name'], $config['charset'])?> account.<br />
  <br />
  Please use the following credentials to login into your account as soon as possible and permanently delete this email. Also, you should change your password after login.<br />
  <br />
  Username: <?=filter_html($view['username'], $config['charset'])?><br />
  Password: <?=filter_html($view['password'], $config['charset'])?><br />
  <br />
  If you need any kind of support regarding this or any other subject, please contact us at <a href="mailto:no-support@nd-php.org">no-support@nd-php.org</a>. We'll be glad to help!<br />
  <br />
  Thank you for your preference.<br />
  <br />
  --<br />
  ND PHP Framework<br />
  <a href="https://www.nd-php.org">https://www.nd-php.org</a><br />
 </body>
</html>

