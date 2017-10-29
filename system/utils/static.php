<?php if (!defined('FROM_BASE')) { header($_SERVER['SERVER_PROTOCOL'] . ' 403'); die('Invalid requested path.'); }

/* Author: Pedro A. Hortas
 * Email: pah@ucodev.org
 * Date: 26/04/2016
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

function static_base_dir() {
	return base_dir() . '/application/static';
}

function static_base_url() {
	return base_url() . '/index.php/_static';
}

function static_css_dir($prefix = NULL) {
	return static_base_dir() . ($prefix !== NULL ? ('/' . $prefix) : '') . '/css';
}

function static_css_url($prefix = NULL) {
	return static_base_url() . ($prefix !== NULL ? ('/' . $prefix) : '')  . '/css';
}

function static_images_dir($prefix = NULL) {
	return static_base_dir() . ($prefix !== NULL ? ('/' . $prefix) : '')  . '/images';
}

function static_images_url($prefix = NULL) {
	return static_base_url() . ($prefix !== NULL ? ('/' . $prefix) : '')  . '/images';
}

function static_js_dir($prefix = NULL) {
	return static_base_dir() . ($prefix !== NULL ? ('/' . $prefix) : '')  . '/js';
}

function static_js_url($prefix = NULL) {
	return static_base_url() . ($prefix !== NULL ? ('/' . $prefix) : '')  . '/js';
}

