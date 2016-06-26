<?php if (!defined('FROM_BASE')) { header('HTTP/1.1 403 Forbidden'); die('Invalid requested path.'); }

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

class Payments extends ND_Controller {
	/* Constructor */
	public function __construct($session_enable = true, $json_replies = false) {
		parent::__construct($session_enable, $json_replies);

		$this->_viewhname = get_class();
		$this->_name = strtolower($this->_viewhname);
		$this->_hook_construct();

		/* Include any setup procedures from ide builder. */
		include('lib/ide_setup.php');
	}
	
	/** Hooks **/
	
	/** Other overloads **/

	/* Direction by which the listing views shall be ordered by */
	protected $_table_field_listing_order_modifier = 'desc';

	/* Direction by which the result views shall be ordered by */
	protected $_table_field_result_order_modifier = 'desc';

	protected $_hide_fields_create = array('id');
	protected $_hide_fields_edit = array('id');
	protected $_hide_fields_view = array('password');
	protected $_hide_fields_remove = array('password');
	protected $_hide_fields_list = array('payment_fee', 'item_description', 'created', 'payer_first_name', 'payer_last_name', 'payer_address_name', 'payer_address_country', 'payer_address_city', 'payer_address_street', 'payer_address_zip', 'payer_address_state', 'payer_address_status', 'payer_status', 'payer_residence_country', 'status_desc');
	protected $_hide_fields_result = array('password');
	protected $_hide_fields_search = array('password'); // Include fields searched on searchbar (basic)
	protected $_hide_fields_export = array('password');


	/* Aliases for the current table field names */
	protected $_table_field_aliases = array(
		'txnid' => NDPHP_LANG_MOD_COMMON_TXNID,
		'amount' => NDPHP_LANG_MOD_COMMON_AMOUNT,
		'tax_rate' => NDPHP_LANG_MOD_PAYMENT_TAX_RATE,
		'payment_fee' => NDPHP_LANG_MOD_PAYMENT_PAYMENT_FEE,
		'total_tax' => NDPHP_LANG_MOD_PAYMENT_TOTAL_TAX,
		'status_desc' => NDPHP_LANG_MOD_PAYMENT_STATUS_DESCRIPTION,
		'item_price' => NDPHP_LANG_MOD_PAYMENT_ITEM_PRICE,
		'item_quantity' => NDPHP_LANG_MOD_PAYMENT_ITEM_QUANTITY,
		'item_description' => NDPHP_LANG_MOD_PAYMENT_ITEM_DESCRIPTION,
		'created' => NDPHP_LANG_MOD_COMMON_CREATED,
		'updated' => NDPHP_LANG_MOD_COMMON_UPDATED,
		'payer_email' => NDPHP_LANG_MOD_PAYMENT_PAYER_EMAIL,
		'payer_first_name' => NDPHP_LANG_MOD_PAYMENT_PAYER_FIRST_NAME,
		'payer_last_name' => NDPHP_LANG_MOD_PAYMENT_PAYER_LAST_NAME,
		'payer_address_name' => NDPHP_LANG_MOD_PAYMENT_PAYER_ADDR_NAME,
		'payer_address_country' => NDPHP_LANG_MOD_PAYMENT_PAYER_ADDR_COUNTRY,
		'payer_address_city' => NDPHP_LANG_MOD_PAYMENT_PAYER_ADDR_CITY,
		'payer_address_street' => NDPHP_LANG_MOD_PAYMENT_PAYER_ADDR_STREET,
		'payer_address_zip' => NDPHP_LANG_MOD_PAYMENT_PAYER_ADDR_ZIP,
		'payer_address_state' => NDPHP_LANG_MOD_PAYMENT_PAYER_ADDR_STATE,
		'payer_address_status' => NDPHP_LANG_MOD_PAYMENT_PAYER_ADDR_STATUS,
		'payer_status' => NDPHP_LANG_MOD_PAYMENT_PAYER_STATUS,
		'payer_residence_country' => NDPHP_LANG_MOD_PAYMENT_PAYER_RESID_COUNTRY,
		'payer_payment_date' => NDPHP_LANG_MOD_PAYMENT_PAYER_PAYMENT_DATE
	);
	
	protected $_rel_table_fields_config = array(
		'payment_types' => array(NDPHP_LANG_MOD_PAYMENT_TYPE, NULL, array(1), array('id', 'asc')),
		'payment_status' => array(NDPHP_LANG_MOD_PAYMENT_STATUS, NULL, array(1), array('id', 'asc')),
		'payment_actions' => array(NDPHP_LANG_MOD_PAYMENT_ACTION, NULL, array(1), array('id', 'asc'))
	);

	/** Custom functions **/
}

