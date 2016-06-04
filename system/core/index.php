<?php if (!defined('FROM_BASE')) { header('HTTP/1.1 403 Forbidden'); die('Invalid requested path.'); }

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

/** THIS FILE IS LOADED FROM system/index.php **/

/* Include all system core modules */
foreach (glob("system/core/*.php") as $sys_core) {
	if (substr($sys_core, -9) == 'index.php')
		continue;

    include($sys_core);
}

/* Include all system models */
foreach (glob("system/models/*.php") as $sys_model)
    include($sys_model);

/* Include all system extensions */
foreach (glob("system/extensions/*.php") as $sys_ext)
    include($sys_ext);

/* Include all application models */
foreach (glob("application/models/*.php") as $app_model)
    include($app_model);
