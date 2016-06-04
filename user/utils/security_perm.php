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


function security_perm_check($security_perms, $reqperm, $table, $column = NULL) {
        /* Magic controller refers to a special temporary table, so it will always have full permissions */
        if ($table == 'magic')
                return true;

        /* Check if we're validating column or table permissions */
        if (!$column) {
                /* Validate table permissions */
                if (strpos($security_perms['table'][$table], $reqperm) === false)
                        return false; /* Permission denied */

                /* Permission granted */
                return true;
        } else {
                /* Validate table column permissions */
                if (strpos($security_perms['column'][$table][$column], $reqperm) === false)
                        return false; /* Permission denied */

                /* Permission granted */
                return true;
        }

        /* Permission denied */
        return false;
}
