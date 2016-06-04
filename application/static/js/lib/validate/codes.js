/**
 * ND PHP Framework - Validation JavaScript Handlers
 *
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
 * ND PHP Framework (www.nd-php.org) - Contributions Agreement
 *
 * When contributing material to this project, please read, accept, sign
 * and send us the Contributor Agreement located in the file NDCA.pdf
 * inside the documentation/ directory.
 *
 */
 
if (typeof validation == 'undefined') {
	validation = {};
	validation.code = {};

}

validation.code.pt = {};

validation.code.pt.nib = function(nib) {
	var result = 0;
	var control = '';

	if (nib.length != 21)
		return false;

	for (var i = 0; i < 19; i ++)
		result = ((result + parseInt(nib[i])) * 10) % 97;

	result = 98 - ((result * 10) % 97);

	if (result < 10)
		control = '0' + result.toString();
	else
		control = result.toString();

	return (nib.substring(19, 21) == control);
}

validation.code.pt.vat = function(nif) {
	var sum = 0;

	if (nif.length != 9)
		return false;

	if ((nif[0] != '1') && (nif[0] != '2') && (nif[0] != '5') && (nif[0] != '6') && (nif[0] != '8') && (nif[0] != '9'))
		return false;

	sum = (9 * parseInt(nif[0])) + (8 * parseInt(nif[1])) + (7 * parseInt(nif[2])) + (6 * parseInt(nif[3])) +
			(5 * parseInt(nif[4])) + (4 * parseInt(nif[5])) + (3 * parseInt(nif[6])) + (2 * parseInt(nif[7])) + parseInt(nif[8]);

	if (!(sum % 11))
		return true;

	/* Check for exceptions */
	if (nif[8] == '0')
		sum += 10;
	
	if (!(sum % 11))
		return true;

	return false;
}

validation.code.eu.vat = function(vat) {
	return validation.code.pt.vat(vat);
}
