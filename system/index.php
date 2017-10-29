<?php if (!defined('FROM_BASE')) { header($_SERVER['SERVER_PROTOCOL'] . ' 403'); die('Invalid requested path.'); }

/* Author: Pedro A. Hortas
 * Email: pah@ucodev.org
 * Date: 27/07/2016
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

ob_start();

/* Include configuration */
include('system/config/index.php');
include('user/config/index.php');

/* Initialize globals */
$__objects = array();
$__objects['enabled'] = true;
$__objects['autoload'] = array();
$__objects['adhoc'] = array();
$__controller = NULL;
$__function = NULL;
$__args = NULL;
$__args_list = '';
$__path_dir = '';

/* Grant URI match the acceptable regex */
if (!preg_match($config['base']['acceptable_uri_regex'], $_SERVER['REQUEST_URI'])) {
	header($_SERVER['SERVER_PROTOCOL'] . ' 403');
	die('URI contains invalid characters.');
}

/* Get request URI */
$__uri = explode('/', $_SERVER['REQUEST_URI']);

/* Try to get the controller offset through index.php */
$__a_koffset = array_search('index.php', $__uri);

/* If index.php isn't present in the URI, assume the base dir as the offset */
if ($__a_koffset === false) {
	$__a_koffset = count($__uri);
	$__a_count = 0;
} else {
	$__a_count = count($__uri) - ($__a_koffset + 1);
}

/* Extract controller */
if (($__a_count >= 1) && $__uri[$__a_koffset + 1]) {
	$__controller = strtolower($__uri[$__a_koffset + 1]);

	if (!preg_match('/^[a-z0-9_]+$/', $__controller)) {
		header($_SERVER['SERVER_PROTOCOL'] . ' 403');
		die('Controller name contains invalid characters.');
	}
}

/* Extract function */
if (($__a_count >= 2) && $__uri[$__a_koffset + 2]) {
	$__function = strtolower($__uri[$__a_koffset + 2]);

	if (!preg_match('/^[a-z0-9_]+$/', $__function)) {
		header($_SERVER['SERVER_PROTOCOL'] . ' 403');
		die('Function name contains invalid characters.');
	}
}

/* Extract args */
if (($__a_count >= 3) && isset($__uri[$__a_koffset + 3])) {
	$__args = array_slice($__uri, $__a_koffset + 3);
}

/* Include system utilities */
include('system/utils/index.php');

/* Include libraries */
include('system/libraries/index.php');

/* Include system core controllers */
include('system/core/index.php');

/* Include user extensions */
include('user/index.php');

/* Set the configured default controller, if none was defined */
if (!$__controller)
	$__controller = $config['base']['controller'];

/* Load requested controller, if any */
if ($__controller) {
	/* There is a special "controller" (it doesn't really exists) named _static, which handles application/static/ files */
	if ($__controller == '_static') {
		/* Glue the path if there's any */
		if ($__args) {
			foreach ($__args as $__arg) {
				/* Check for .. on all arguments to avoid ../ paths */
				if (strstr($__arg, '..')) {
					header($_SERVER['SERVER_PROTOCOL'] . ' 403');
					die('Static path contains ../ references, which are invalid.');
				}

				$__path_dir .= $__arg . '/';
			}

			/* Minor fix */
			$__path_dir = rtrim($__path_dir, '/');
		}

		/* If the requested path ends with .php extension, then we shall process the file as PHP */
		if (substr(end($__args), -4) == '.php') {
			require_once('application/static/' . $__function . '/' . $__path_dir);
		} else {
			/* Otherwise, just redirect to the real path */
			redirect('application/static/' . $__function . '/' . $__path_dir, false);
		}
	} else {
		/* This is a real controller */
		if (!file_exists('application/controllers/' . $__controller . '.php')) {
			header($_SERVER['SERVER_PROTOCOL'] . ' 404');
			die('No such controller: ' . $__controller);
		} else {
			include('application/controllers/' . $__controller . '.php');
		}

		eval('$__r_ = new ' . ucfirst($__controller) . ';');

		/* Call requested function, if any */
		if (!$__function) {
			$__function = 'index';
		} else if ($__function == '__construct') {
			header($_SERVER['SERVER_PROTOCOL'] . ' 403');
			die('Calling __construct() methods directly from HTTP requests is not allowed.');
		}

		/* Glue the args if there's any */
		if ($__args) {
			foreach ($__args as $__arg)
				$__args_list .= '\'' . str_replace('\'', '\\\'', $__arg) . '\',';

			/* Minor fix */
			$__args_list = rtrim($__args_list, ',');
		}

		/* Invoke the function with the arguments */
		eval('$__r_->' . $__function . '(' . $__args_list . ');');
	}
} else {
	header($_SERVER['SERVER_PROTOCOL'] . ' 400');
	die('No controller defined in the request. Nothing to process.<br />');
}

ob_flush();

