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

function filter_html($value, $charset) {
	return htmlentities($value, ENT_QUOTES, $charset);
}

function filter_html_special($value, $charset) {
	return preg_replace('/[^a-zA-Z0-9\_\s]/', '', $value);
}

function filter_html_js_str($value, $charset) {
	return htmlentities(addslashes($value), ENT_QUOTES, $charset);
}

function filter_html_js_special($value, $charset) {
	return htmlentities(preg_replace('/[^a-zA-Z0-9\_\s]/', '', $value), ENT_QUOTES, $charset);
}

function filter_js_str($value, $charset) {
	return addslashes($value);
}

function filter_js_special($value, $charset) {
	return preg_replace('/[^a-zA-Z0-9\_\s]/', '', $value);
}

function filter_css_str($value, $charset) {
	return addslashes($value);
}

function filter_css_special($value, $charset) {
	return preg_replace('/[^a-zA-Z0-9\_\s]/', '', $value);
}