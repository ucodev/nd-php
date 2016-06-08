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

function error_upload_file($errno) {
	switch ($errno) {
		case UPLOAD_ERR_OK:			return NDPHP_LANG_MOD_ERROR_UPLOAD_OK;
		case UPLOAD_ERR_INI_SIZE:	return NDPHP_LANG_MOD_ERROR_UPLOAD_INI_SIZE;
		case UPLOAD_ERR_FORM_SIZE:	return NDPHP_LANG_MOD_ERROR_UPLOAD_FORM_SIZE;
		case UPLOAD_ERR_PARTIAL:	return NDPHP_LANG_MOD_ERROR_UPLOAD_PARTIAL;
		case UPLOAD_ERR_NO_FILE:	return NDPHP_LANG_MOD_ERROR_UPLOAD_NO_FILE;
		case UPLOAD_ERR_NO_TMP_DIR:	return NDPHP_LANG_MOD_ERROR_UPLOAD_NO_TMP_DIR;
		case UPLOAD_ERR_CANT_WRITE:	return NDPHP_LANG_MOD_ERROR_UPLOAD_CANT_WRITE;
		case UPLOAD_ERR_EXTENSION:	return NDPHP_LANG_MOD_ERROR_UPLOAD_EXTENSION;
	}

	return NDPHP_LANG_MOD_ERROR_UPLOAD_UNKNOWN;
}
