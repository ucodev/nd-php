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

function truncate_str($string, $len, $charset = 'UTF-8', $trailing = 5, $separator = '...', $character_reset = ' ', $entities = true, $anchor_prompt = true, $prompt_title = '') {
	/* Some sanity checks */
	if (strlen($string) <= $len || strlen($string) <= (1 + strlen($separator) + $trailing) || $len <= (1 + strlen($separator) + $trailing))
		return $entities ? htmlentities($string, ENT_QUOTES, $charset) : $string;

	if ($character_reset !== NULL) {
		/* If $character_reset is set, the $string is split by that character and each fragment length is checked against $len.
		 * The string truncate will only occur if a fragment greater than $len is found.
		 */
		$frag_len_found = false;
		foreach (explode(' ', $string) as $frag) {
			if (strlen($frag) > $len) {
				$frag_len_found = true;
				break;
			}
		}

		if (!$frag_len_found)
			return $string;
	}

	/* Truncate $string */
	$tstr = substr($string, 0, $len - strlen($separator) - $trailing) . $separator . substr($string, -$trailing);

	/* Encode entities if requested */
	if ($entities)
		$tstr = htmlentities($tstr, ENT_QUOTES, $charset);

	/* Add a prompt anchor if requested */
	if ($anchor_prompt) {
		$tstr = '' .
			'<a title="' . htmlentities($string, ENT_QUOTES, $charset) . '" href="javascript:void(0);" onclick="window.prompt(\'' . htmlentities(addslashes($prompt_title), ENT_QUOTES, $charset) . '\', \'' . htmlentities(addslashes($string), ENT_QUOTES, $charset) . '\');">' .
				$tstr .
			'</a>';
	}

	/* All good */
	return $tstr;
}
