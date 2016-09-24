<?php if (!defined('FROM_BASE')) { header('HTTP/1.1 403 Forbidden'); die('Invalid requested path.'); }

/* Author: Pedro A. Hortas
 * Email: pah@ucodev.org
 * Date: 18/09/2016
 * License: GPLv3
 */

/*
 * This file is part of uweb.
 *
 * uWeb - uCodev Low Footprint Web Framework (https://github.com/ucodev/uweb)
 * Copyright (C) 2014-2016  Pedro A. Hortas
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

class UW_Pagination {
	private $_config = array();

	private function _convert_entity_space($value) {
		return str_replace(' ', '&nbsp;', $value);
	}

	public function initialize($config) {
		/* Reset config */
		$this->_config = array();

		/* Required */
		if (!isset($config['base_url']))
			return false;

		if (!isset($config['total_rows']))
			return false;

		/* Defaults */
		if (!isset($config['onclick']))
			$config['onclick'] = NULL;

		if (!isset($config['url_var_page_nr']))
			$config['url_var_page_nr'] = '@PAGE_NR@';

		if (!isset($config['url_var_row_nr']))
			$config['url_var_row_nr'] = '@ROW_NR@';

		if (!isset($config['per_page']))
			$config['per_page'] = 10;

		if (!isset($config['page']))
			$config['page'] = 1;

		if (!isset($config['prev_link']))
			$config['prev_link'] = '<';

		if (!isset($config['next_link']))
			$config['next_link'] = '>';

		if (!isset($config['first_link']))
			$config['first_link'] = '«';

		if (!isset($config['last_link']))
			$config['last_link'] = '»';

		if (!isset($config['next_pages']))
			$config['next_pages'] = 2;

		if (!isset($config['prev_pages']))
			$config['prev_pages'] = 2;

		if (!isset($config['separator']))
			$config['separator'] = '  ';

		/* Enforce */
		$config['total_rows'] = intval($config['total_rows']);
		$config['per_page'] = intval($config['per_page']);
		$config['page'] = intval($config['page']);
		$config['prev_link'] = $this->_convert_entity_space(htmlentities($config['prev_link'], ENT_QUOTES, 'UTF-8'));
		$config['next_link'] = $this->_convert_entity_space(htmlentities($config['next_link'], ENT_QUOTES, 'UTF-8'));
		$config['first_link'] = $this->_convert_entity_space(htmlentities($config['first_link'], ENT_QUOTES, 'UTF-8'));
		$config['last_link'] = $this->_convert_entity_space(htmlentities($config['last_link'], ENT_QUOTES, 'UTF-8'));
		$config['next_pages'] = intval($config['next_pages']);
		$config['prev_pages'] = intval($config['prev_pages']);
		$config['separator'] = $this->_convert_entity_space(htmlentities($config['separator'], ENT_QUOTES, 'UTF-8'));

		/* If current page is greater than total pages, force current page to the max possible page value */
		if (intval(ceil(floatval($config['total_rows']) / floatval($config['per_page']))) < $config['page'])
			$config['page'] = intval(ceil(floatval($config['total_rows']) / floatval($config['per_page'])));

		/* If current page is less than 1, force it to be 1 */
		if ($config['page'] < 1)
			$config['page'] = 1;

		/* Update configuration */
		$this->_config = $config;
	}

	public function create_links() {
		/* Compute total pages */
		$total_pages = intval(ceil(floatval($this->_config['total_rows']) / floatval($this->_config['per_page'])));

		/* Compute first page of pagination listing */
		if (($first_page = intval($this->_config['page'] - $this->_config['prev_pages'])) <= 0)
			$first_page = 1;

		/* Compute last page of pagination listing */
		if (($last_page = intval($this->_config['page'] + $this->_config['next_pages'])) > $total_pages)
			$last_page = $total_pages;

		/* Create pagination listing */
		$page_list = array(); /* [ link text, page number, href argument ] */

		/* Insert first page link (top) if required */
		if ($first_page > 1)
			array_push($page_list, array($this->_config['first_link'], 1, 0));

		/* Insert previous page link if required */
		if ($this->_config['page'] > 1)
			array_push($page_list, array($this->_config['prev_link'], $this->_config['page'] - 1, ($this->_config['page'] - 2) * $this->_config['per_page']));

		/* Insert prev pages list */
		for ($i = $first_page; $i < $this->_config['page']; $i ++)
			array_push($page_list, array($i, $i, ($i - 1) * $this->_config['per_page']));

		/* Insert current page */
		array_push($page_list, array($this->_config['page'], $this->_config['page'], NULL));

		/* Insert next pages list */
		for ($i = $this->_config['page'] + 1; $i <= $last_page; $i ++)
			array_push($page_list, array($i, $i, ($i - 1) * $this->_config['per_page']));

		/* Insert next page link if required */
		if ($this->_config['page'] < $total_pages)
			array_push($page_list, array($this->_config['next_link'], $this->_config['page'] + 1, $this->_config['page'] * $this->_config['per_page']));

		/* Insert last page link (bottom) if required */
		if ($last_page < $total_pages)
			array_push($page_list, array($this->_config['last_link'], $total_pages, ($total_pages - 1) * $this->_config['per_page']));

		/* Create links */
		$links = '<ul class="pagination">';
		foreach ($page_list as $p) {
			if ($p[2] === NULL) {
				$links .= '<li class="active"><a href="#">' . $p[1] . '</a></li>' . $this->_config['separator'];
				continue;
			}

			$links .= '<li><a href="' . str_replace($this->_config['url_var_row_nr'], $p[2], str_replace($this->_config['url_var_page_nr'], $p[1], $this->_config['base_url'])) . '"' . ($this->_config['onclick'] ? ' onclick="' . str_replace($this->_config['url_var_row_nr'], $p[2], str_replace($this->_config['url_var_page_nr'], $p[1], $this->_config['onclick'])) . '"' : '') . '>' . $p[0] . '</a>' . $this->_config['separator'] . '</li>';
		}

		return $links . '</ul>';
	}
}
