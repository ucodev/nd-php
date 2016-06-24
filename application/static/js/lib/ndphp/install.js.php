/**
 * ND PHP Framework - JavaScript Handlers
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
 * ND PHP Framework (www.nd-php.org) - Contributor Agreement
 *
 * When contributing material to this project, please read, accept, sign
 * and send us the Contributor Agreement located in the file NDCA.pdf
 * inside the documentation/ directory.
 *
 */
 
if (typeof ndphp == 'undefined') {
    ndphp = {};
    ndphp.ui = {};

    /* Global UI Management */
    ndphp.ui.busy = function(msg) {
        jQuery.blockUI({
            css: { 
                border: 'none',
                padding: '10px',
                backgroundColor: '#000',
                'border-radius': '10px',
                '-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',
                opacity: .5,
                color: '#fff',
                top: '125px'
            },
            message: "<center>" + msg + "</center>"
        });
        jQuery("body").css("cursor", "progress");
    };

    ndphp.ui.ready = function() {
        jQuery("body").css("cursor", "auto");
        jQuery.unblockUI();
    };
}

/* Utils */
ndphp.utils = {};

ndphp.utils.safe_b64encode = function(input) {
	return base64.encode(input).replace('+', '-').replace('/', '_').replace('=', ',');
};

/* Installation handlers */
ndphp.install = {}; /* ND PHP Installation routines Namespace */

ndphp.install.db_config_apply = function(dbhost, dbport, dbname, dbuser, dbpass) {
	var errors = false;

	jQuery.ajax({
		type: "GET",
		url: "<?=base_url()?>index.php/install/db_config_apply/" + dbhost + "/" + dbport + "/" + dbname + "/" + dbuser + "/" + dbpass,
        success: function(data) {
			/* Re-test connection... this time checking all privileges on the database */
			jQuery.ajax({
				type: "GET",
				url: "<?=base_url()?>index.php/install/db_test/" + dbhost + "/" + dbport + "/" + dbname + "/" + dbuser + "/" + dbpass + "/1",
	            success: function(data) {
	            	/* Inform the user about the successful connection test */
                    
                    jQuery('#dbdriver').prop('disabled', true);
                    jQuery('#dbhost').prop('disabled', true);
                    jQuery('#dbport').prop('disabled', true);
                    jQuery('#dbname').prop('disabled', true);
                    jQuery('#dbuser').prop('disabled', true);
                    jQuery('#dbpass').prop('disabled', true);
                    jQuery('#dbchar').prop('disabled', true);

                    jQuery('#dbconn_test_btn').prop('disabled', true);
                    jQuery('#dbconn_test_btn').hide();

                    jQuery('#dbconn').html(data);
                    jQuery('#dbconn').show();

	                ndphp.ui.ready();
	            },
	            error: function(xhr, ajaxOptions, thrownError) {
	            	errors = true;
	            	jQuery('#continue_btn').prop('disabled', true);
	                alert(xhr.responseText);
	            }
			});

            return !errors;
        },
        error: function(xhr, ajaxOptions, thrownError) {
        	errors = xhr.responseText;
            return false;
        }
	});

	return !errors;
};

ndphp.install.db_test = function() {
	var errors = false;
	var dbhost = encodeURIComponent(ndphp.utils.safe_b64encode(utf8_encode(jQuery('#dbhost').val())));
	var dbport = encodeURIComponent(ndphp.utils.safe_b64encode(utf8_encode(jQuery('#dbport').val())));
	var dbname = encodeURIComponent(ndphp.utils.safe_b64encode(utf8_encode(jQuery('#dbname').val())));
	var dbuser = encodeURIComponent(ndphp.utils.safe_b64encode(utf8_encode(jQuery('#dbuser').val())));
	var dbpass = encodeURIComponent(ndphp.utils.safe_b64encode(utf8_encode(jQuery('#dbpass').val())));

	ndphp.ui.busy("Testing...");

	jQuery.ajax({
		type: "GET",
		url: "<?=base_url()?>index.php/install/db_test/" + dbhost + "/" + dbport + "/" + dbname + "/" + dbuser + "/" + dbpass,
        success: function(data) {
            /* Create database configuration file */
            var ret = ndphp.install.db_config_apply(dbhost, dbport, dbname, dbuser, dbpass);

            /* Check if the configuration file was successfully created... */
            if (ret !== true) {
            	alert('Unable to create database configuration file: ' + ret);
            	ndphp.ui.ready();
            	return false;
            }

            /* Enable the Continue button */
            jQuery('#continue_btn').prop('disabled', false);

            return true;
        },
        error: function(xhr, ajaxOptions, thrownError) {
        	errors = true;
        	jQuery('#continue_btn').prop('disabled', true);
            alert(xhr.responseText);
            ndphp.ui.ready();
        }
	});

	/* return true if no errors, false if any error was found */
	return !errors;
};