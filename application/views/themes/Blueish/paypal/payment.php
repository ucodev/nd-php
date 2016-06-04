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
<br />
<br />
<br />
<div id="payments_paypal" class="payments_paypal">
	<fieldset class="payments_paypal">
		<legend class="payments_paypal"><?=filter_html(NDPHP_LANG_MOD_PAYMENT_PAYPAL_TITLE, $config['charset'])?></legend>
		<table border="0" cellpadding="10" cellspacing="0" align="center">
			<tr>
				<td align="center"></td>
			</tr>
			<tr>
				<td align="center">
					<a href="https://www.paypal.com/webapps/mpp/paypal-popup" title="<?=filter_html(NDPHP_LANG_MOD_PAYMENT_HOW_PAYPAL_WORKS, $config['charset'])?>" onclick="javascript:window.open('https://www.paypal.com/webapps/mpp/paypal-popup','WIPaypal','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=1060, height=700'); return false;">
						<img src="https://www.paypalobjects.com/webstatic/mktg/logo/AM_mc_vs_dc_ae.jpg" border="0" alt="PayPal Acceptance Mark">
					</a>
				</td>
			</tr>
		</table>
		<br />
		<form action="<?=filter_html(base_url(), $config['charset'])?>index.php/paypal/payment_request" name="payments_paypal" id="payments_paypal" method="post">
			<?=filter_html(NDPHP_LANG_MOD_PAYMENT_CREDIT_AMMOUNT, $config['charset'])?>: <input name="item_quantity" type="number" value="10" min="10" max="2500" /> <?=filter_html(NDPHP_LANG_MOD_DEFAULT_CURRENCY, $config['charset'])?>
			<br />
			<br />
			<br />
			<div style="text-align: left;">
			<ul>
				<li><?=filter_html(NDPHP_LANG_MOD_PAYMENT_PAYPAL_TAX_APPLY, $config['charset'])?></li>
			</ul>
			<ul>
				<li><?=filter_html(NDPHP_LANG_MOD_PAYMENT_NO_VAT_IF, $config['charset'])?>:</li>
				<ul>
					<li><?=filter_html(NDPHP_LANG_MOD_PAYMENT_NO_VAT_COND_1, $config['charset'])?></li>
					<li><?=filter_html(NDPHP_LANG_MOD_PAYMENT_NO_VAT_COND_2, $config['charset'])?></li>
				</ul>
			</ul>
			<ul>
				<li><?=filter_html(NDPHP_LANG_MOD_PAYMENT_CONTACT_OTHER_METHOD, $config['charset'])?></li>
			</ul>
			</div>
			<br />
			<br />
			<input type="submit" value="<?=filter_html(NDPHP_LANG_MOD_PAYMENT_SUBMIT, $config['charset'])?>" />
			<br />
			<br />
			<br />
		</form>
	</fieldset>
</div>
<br />

