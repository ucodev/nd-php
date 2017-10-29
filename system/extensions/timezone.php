<?php if (!defined('FROM_BASE')) { header($_SERVER['SERVER_PROTOCOL'] . ' 403'); die('Invalid requested path.'); }

/* Author: Pedro A. Hortas
 * Email: pah@ucodev.org
 * Date: 21/05/2017
 * License: GPLv3
 */

/*
 * This file is part of uweb.
 *
 * uWeb - uCodev Low Footprint Web Framework (https://github.com/ucodev/uweb)
 * Copyright (C) 2014-2017  Pedro A. Hortas
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

class UW_Timezone {
	public function convert($datetime_value, $from, $to, $format = 'Y-m-d H:i:s') {
		/* Initial validations */
		if (!$datetime_value || !$from || !$to) {
			header($_SERVER['SERVER_PROTOCOL'] . ' 400');
			die('Invalid datetime convertion parameters.');
		}

		/* Store the current configured timezone */
		$default_timezone = date_default_timezone_get();

		/* Set the current timezone to $from */
		if (date_default_timezone_set($from) === false) {
			header($_SERVER['SERVER_PROTOCOL'] . ' 400');
			die('Unrecognized timezone: ' . $from);
		}

		/* Process datetime value to be converted */
		try {
			$datetime = new DateTime($datetime_value, new DateTimeZone($from));
		} catch (Exception $e) {
			header($_SERVER['SERVER_PROTOCOL'] . ' 400');
			die('Unrecognized datetime value: ' . $datetime_value);
		}

		if (!$datetime) {
			header($_SERVER['SERVER_PROTOCOL'] . ' 400');
			die('Unrecognized datetime value: ' . $datetime_value);
		}

		/* Create the new timezone instance */
		$to_timezone = new DateTimeZone($to);

		/* Convert the datetime to the desired timezone */
		$datetime->setTimezone($to_timezone);

		/* Retrieve the converted datetime value based on the $format */
		$ret = $datetime->format($format);

		/* Restore the original configured timezone */
		date_default_timezone_set($default_timezone);

		/* Return converted datetime value */
		return $ret;
	}

	public function from_utc($datetime_value, $to, $format = 'Y-m-d H:i:s') {
		return $this->convert($datetime_value, 'Etc/UTC', $to, $format);
	}

	public function to_utc($datetime_value, $from, $format = 'Y-m-d H:i:s') {
		return $this->convert($datetime_value, $from, 'Etc/UTC', $format);
	}
}
