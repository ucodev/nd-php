<?php if (!defined('FROM_BASE')) { header($_SERVER['SERVER_PROTOCOL'] . ' 403'); die('Invalid requested path.'); }

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

class Currencies extends ND_Controller {
	/* Constructor */
	public function __construct($session_enable = true, $json_replies = false) {
		parent::__construct($session_enable, $json_replies);

		/* Initialize controller */
		$this->_init(get_class(), true);
	}
	
	/** Hooks **/
	
	/** Other overloads **/

	/* Aliases for the current table field names */
	protected $_table_field_aliases = array(
		'currency' => NDPHP_LANG_MOD_COMMON_CURRENCY,
		'code' => NDPHP_LANG_MOD_COMMON_CURRENCY_CODE,
		'sign' => NDPHP_LANG_MOD_COMMON_CURRENCY_SIGN,
		'sign_position' => NDPHP_LANG_MOD_COMMON_CURRENCY_SIGN_POS,
		'rate' => NDPHP_LANG_MOD_COMMON_CURRENCY_RATE,
		'updated' => NDPHP_LANG_MOD_COMMON_CURRENCY_UPDATED,
		'default' => NDPHP_LANG_MOD_COMMON_CURRENCY_DEFAULT
	);


	/** Custom functions **/

	public function update_rates() {
		global $config;

		/* Grant that only ROLE_ADMIN is able to invoke this method */
		if (!$this->security->im_admin())
			$this->response->code('403', NDPHP_LANG_MOD_ACCESS_ONLY_ADMIN, $this->config['default_charset'], !$this->request->is_ajax());

		/* Fetch the default (base) currency */
		$this->db->from('currencies');
		$this->db->where('default', true);
		$this->db->limit(1);
		$q = $this->db->get();

		/* If no default currency is set, bail out */
		if (!$q->num_rows())
			$this->response->code('403', NDPHP_LANG_MOD_ATTN_NO_CURRENCY_DEFAULT, $this->config['default_charset'], !$this->request->is_ajax());

		$row = $q->row_array();

		/* Otherwise set it as the base currency */
		$base_currency = $row['code'];

		/* Fetch the currency exchange rates from OER */
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $config['oer']['base_url'] . '/' . $config['oer']['version'] . '?app_id=' . $config['oer']['key'] . '&base=' . $base_currency);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$output = curl_exec($ch);
		curl_close($ch);

		/* Try to decode JSON data */
		$oer_rates = json_decode($output, true);

		/* If we're unable to decode the data from OER, bail out */
		if ($oer_rates === false)
			$this->response->code('500', NDPHP_LANG_MOD_UNABLE_RETRIEVE_OER_RATES, $this->config['default_charset'], !$this->request->is_ajax());

		/* Initialize currency rates update transaction */
		$this->db->trans_begin();

		/* Update each existing rate */
		foreach ($oer_rates['rates'] as $code => $rate) {
			$this->db->where('code', $code);
			$this->db->update('currencies', array(
				'rate' => $rate,
				'updated' => date('Y-m-d H:i:s', $oer_rates['timestamp']))
			);
		}

		/* Check if we're successful */
		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			$this->response->code('500', NDPHP_LANG_MOD_UNABLE_UPDATE_CURRENCY_RATES, $this->config['default_charset'], !$this->request->is_ajax());
		}

		/* Commit data */
		$this->db->trans_commit();

		/* All good */
		$this->response->code('200', 'OK', $this->config['default_charset'], !$this->request->is_ajax());
	}
}