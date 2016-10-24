<?php if (!defined('FROM_BASE')) { header('HTTP/1.1 403 Forbidden'); die('Invalid requested path.'); }

/* Author: Pedro A. Hortas
 * Email: pah@ucodev.org
 * Date: 26/09/2016
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

/** THIS FILE IS LOADED FROM system/index.php **/

$config = array();

/* Autoload settings */
include('system/config/autoload.php');
$config['autoload'] = $autoload;

/* AWS Settings */
include('system/config/aws.php');
$config['aws'] = $aws;

/* Base settings */
include('system/config/base.php');
$config['base'] = $base;

/* Cache settings */
include('system/config/cache.php');
$config['cache'] = $cache;

/* Database settings */
include('system/config/database.php');
$config['database'] = $database;

/* Encryption settings */
include('system/config/encrypt.php');
$config['encrypt'] = $encrypt;

/* Session settings */
include('system/config/session.php');
$config['session'] = $session;
