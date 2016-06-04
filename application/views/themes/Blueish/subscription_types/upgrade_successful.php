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
<div id="subscription_types_upgrade_postop" class="subscription_types_upgrade_postop">
	<?=filter_html(NDPHP_LANG_MOD_SUBSCRIPTION_UPGRADE_SUCCESS, $config['charset'])?> <strong><?=filter_html($view['plan_name'], $config['charset'])?></strong>.
	<?=filter_html(NDPHP_LANG_MOD_SUBSCRIPTION_DEBT_PREFIX, $config['charset'])?> <strong><?=filter_html(round($view['plan_price'], 2), $config['charset'])?> <?=filter_html(NDPHP_LANG_MOD_DEFAULT_CURRENCY, $config['charset'])?></strong> <?=filter_html(NDPHP_LANG_MOD_SUBSCRIPTION_DEBT_SUFFIX, $config['charset'])?>
	<br />
	<br />
	<a href="<?=filter_html(base_url(), $config['charset'])?>"><?=filter_html(NDPHP_LANG_MOD_ATTN_RELOAD_PAGE, $config['charset'])?></a>
</div>
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<div id="ajax_error_dialog">
</div>
