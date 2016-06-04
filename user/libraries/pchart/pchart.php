<?php

/*
 * This file is part of ND PHP Framework.
 * Its purpose is to integrate ND PHP Framework with pChart library (http://pchart.net)
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

$__pchart_package = "pChart2.1.4";

/* TODO: FIXME: Not everything here is required... remove the unused classes */
require_once($__pchart_package . '/class/pBarcode128.class.php');
require_once($__pchart_package . '/class/pBarcode39.class.php');
require_once($__pchart_package . '/class/pBubble.class.php');
require_once($__pchart_package . '/class/pCache.class.php');
require_once($__pchart_package . '/class/pData.class.php');
require_once($__pchart_package . '/class/pDraw.class.php');
require_once($__pchart_package . '/class/pImage.class.php');
require_once($__pchart_package . '/class/pIndicator.class.php');
require_once($__pchart_package . '/class/pPie.class.php');
require_once($__pchart_package . '/class/pRadar.class.php');
require_once($__pchart_package . '/class/pScatter.class.php');
require_once($__pchart_package . '/class/pSplit.class.php');
require_once($__pchart_package . '/class/pSpring.class.php');
require_once($__pchart_package . '/class/pStock.class.php');
require_once($__pchart_package . '/class/pSurface.class.php');


class pchart {
	public function fonts_path() {
		return SYSTEM_BASE_DIR . '/user/libraries/pchart/' . $GLOBALS['__pchart_package'] . '/fonts';
	}

	public function pData() {
		return new pData();
	}

	public function pImage($width, $height, $dataset, $bg_transparent = false) {
		return new pImage($width, $height, $dataset, $bg_transparent);
	}
}

