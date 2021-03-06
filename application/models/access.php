<?php if (!defined('FROM_BASE')) { header($_SERVER['SERVER_PROTOCOL'] . ' 403'); die('Invalid requested path.'); }

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

class UW_Access extends UW_Model {
	public function controller($name, $session_enable = false, $json_replies = false) {
		/* Load the controller file */
		require_once(SYSTEM_BASE_DIR . '/application/controllers/' . $name . '.php');

		if (!preg_match('/^[a-zA-Z0-9\_]+$/i', $name)) {
			header($_SERVER['SERVER_PROTOCOL'] . ' 500');
			die(NDPHP_LANG_MOD_INVALID_CTRL_NAME . ': ' . $name);
		}

		/* Save and load a new context */
		$saved_context = core_context_get();
		core_context_set($name);

		/* Temporarily disable object cache. This will force uWeb to instantiate new models instead of reusing them,
		 * avoiding any conflicts with the previously saved context.
		 */
		core_object_cache_disable();

		/* Create the controller object. (TODO: FIXME: Store the object (to reduce overhead on further calls)) */
		eval('$ctrl = new ' . ucfirst($name) . '(' . ($session_enable ? 'true' : 'false') . ', ' . ($json_replies ? 'true' : 'false') . ');');

		/* Restore previously saved context */
		core_context_set($saved_context);

		/* Re-enable uWeb object cache */
		core_object_cache_enable();

		/* NOTE: We can only access $ctrl protected properties/methods if this function is called from ND_Controller (sibling objects) */
		return $ctrl;
	}
}
