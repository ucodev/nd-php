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

 class UW_Breadcrumb {
 	private $_levels = array();
 	private $_separator = ' > ';
 	private $_charset = 'UTF-8';
 	private $_breadcrumb = '';

 	public function __construct($separator = ' > ', $charset = 'UTF-8') {
 		$this->set('separator', $separator);
 		$this->set('charset', $charset);
 		$this->set('levels', array());
 		$this->_breadcrumb = '';
 	}

 	public function add($name, $title, $url, $onclick, $class = 'breadcrumb') {
 		array_push($this->_levels, array(
 			'name' => $name,
 			'title' => $title,
 			'url' => $url,
 			'onclick' => $onclick, /* Must have been previously filtered */
 			'class' => $class
 		));
 	}

 	public function set($opt, $val) {
 		switch ($opt) {
 			case 'charset'	: $this->_charset = $val; break;
 			case 'levels' : $this->_levels = $val; break;
 			case 'separator': $this->_separator = $val; break;
 			default: return false;
 		}

 		return true;
 	}

 	public function create() {
 		foreach ($this->_levels as $level) {
 			if ($this->_breadcrumb) {
 				/* Add a separator if data in the breadcrumb already exists */
 				$this->_breadcrumb .= filter_html($this->_separator, $this->_charset);
 			}

 			/* Append breadcrumb element */
 			$this->_breadcrumb .=
 				'<a href="' . filter_html($level['url'], $this->_charset) . '" title="' . filter_html($level['title'], $this->_charset) . '" onclick="' . $level['onclick'] . '" class="' . filter_html_special($level['class'], $this->_charset) . '">' . filter_html($level['name'], $this->_charset) . '</a>';
 		}

 		return $this->_breadcrumb;
 	}
 }

