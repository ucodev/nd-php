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
    ndphp.ui.busy = function() {
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
            message: "<center>Working...</center>"
        });
        jQuery("body").css("cursor", "progress");
    };

    ndphp.ui.ready = function() {
        jQuery("body").css("cursor", "auto");
        jQuery.unblockUI();
    };
}

ndphp.ide = {};

ndphp.ide.current_data = '';
ndphp.ide.obj_count = 0;
ndphp.ide.ignore_container_event = false;

ndphp.ide.ide_integrity_check_menu = function(menu, menu_array) {
    if (!menu['name'].length) {
        alert('A menu of type "' + menu['title'] + '" has no name defined.');
        return false;
    }

    /* Check menu name for invalid characters */
    var pat = /^[a-zA-Z0-9_\ ]+$/;

    if (!pat.test(menu['name'])) {
        alert('Menu "' + menu['title'] + '/' + menu['name'] + '" contains invalid characters in its name.');
        return false;
    }

    pat = /^([0-9_\ ]+|rel_|mixed_).*$/;

    if (pat.test(menu['name'])) {
        alert('Menu "' + menu['title'] + '/' + menu['name'] + '" cannot start with a digit, a space nor an underscore and cannot start with \'rel_\' nor \'mixed_\' prefixes.');
        return false;
    }

    /* Check for reserved menu names */
    switch (menu['name'].toLowerCase()) {
        case '_acl_rtcp':
        case '_acl_rtp':
        case '_acl_sessions':
        case '_help_tfhd':
        case '_saved_searches':
        case '_static':
        case 'accounting':
        case 'acl_rtcp':
        case 'acl_rtp':
        case 'acl_sessions':
        case 'builder':
        case 'charts_config':
        case 'charts_geometry':
        case 'charts_types':
        case 'configuration':
        case 'countries':
        case 'dbms':
        case 'documentation':
        case 'features':
        case 'files':
        case 'help_tfhd':
        case 'home':
        case 'install':
        case 'items':
        case 'logging':
        case 'login':
        case 'magic':
        case 'model_objects':
        case 'months':
        case 'notifications':
        case 'payment_actions':
        case 'payment_status':
        case 'payment_types':
        case 'payments':
        case 'paypal':
        case 'paypal_ipn':
        case 'register':
        case 'roles':
        case 'saved_searches':
        case 'scheduler':
        case 'sessions':
        case 'subscription_types':
        case 'themes':
        case 'themes_animations_default':
        case 'themes_animations_ordering':
        case 'timezones':
        case 'transaction_history':
        case 'transaction_types':
        case 'update':
        case 'weekdays':
        case 'users': alert('Reserved menu name used on menu "' + menu['title'] + '/' + menu['name'] + '"'); return false;
        case 'entry name': alert('No name was set on menu "' + menu['title'] + '/' + menu['name'] + '"'); return false;
    }

    /* Check for name collisions */
    var collision = false;

    menu_array.forEach(function(entry) {
        if (collision)
            return false;

        if (menu['name'] == entry['name']) {
            collision = true;
            return false;
        }
    });

    if (collision) {
        alert('Name collision detected on menu "' + menu['title'] + '/' + menu['name'] + '".');
        return false;
    }

    /* Check if there is at least one field set on the menu */
    if (!menu['fields'].length) {
        alert('Menu "' + menu['title'] + '/' + menu['name'] + '" has no fields.');
        return false;
    }

    /* Validate rows per page value */
    if ('properties' in menu && menu['properties']['rpp']) {
        var pat = /^\d+$/;

        if (!pat.test(menu['properties']['rpp'])) {
            alert('Menu "' + menu['title'] + '/' + menu['name'] + '" Rows per Page property must be an integer.');
            return false;
        }
    }

    /* Grant that order field is an existing and valid menu field (including 'id') */
    var order_field_exists = false;
    if ('properties' in menu && menu['properties']['order_field'].length) {
        menu['fields'].forEach(function(entry) {
            if (order_field_exists)
                return true;

            if (menu['properties']['order_field'].toLowerCase() == 'id' || menu['properties']['order_field'].toLowerCase() == entry['name'].toLowerCase()) {
                order_field_exists = true;
                return true;
            }
        });
    } else {
        order_field_exists = true; /* Assumed field 'Id' by default */
    }

    if (!order_field_exists) {
        alert('Menu "' + menu['title'] + '/' + menu['name'] + '" Order Field property references an non-existent menu field.');
        return false;
    }

    /* All good */
    return true;
};

ndphp.ide.ide_integrity_check_field = function(menu, field, field_array) {
    if (!field['name'].length) {
        alert('A field of type "' + field['title'] + '" from menu "' + menu['title'] + '/' + menu['name'] + '" has no name defined.');
        return false;
    }

    /* Check menu name for invalid characters */
    var pat = /^[a-zA-Z0-9_\ ]+$/;

    if (!pat.test(field['name'])) {
        alert('Field "' + field['title'] + '/' + field['name'] + '" from menu "' + menu['title'] + '/' + menu['name'] + '" contains invalid characters in its name.');
        return false;
    }

    pat = /^([0-9_\ ]+|rel_|mixed_).*$/;

    if (pat.test(field['name'])) {
        alert('Field "' + field['title'] + '/' + field['name'] + '" from menu "' + menu['title'] + '/' + menu['name'] + '" cannot start with a digit, a space nor an underscore and cannot start with \'rel_\' nor \'mixed_\' prefixes.');
        return false;
    }

    /* Check for reserved field names */
    switch (field['name'].toLowerCase()) {
        case 'id':
        case 'users_id': alert('Reserved field name used on field "' + field['title'] + '/' + field['name'] + '" from menu "' + menu['title'] + '/' + menu['name'] + '".'); return false;
        case 'field name': alert('No name was set on field "' + field['title'] + '/' + field['name'] + '" from menu "' + menu['title'] + '/' + menu['name'] + '".'); return false;
        case 'controller link': alert('No link was set on field "' + field['title'] + '/' + field['name'] + '" from menu "' + menu['title'] + '/' + menu['name'] + '".'); return false;
    }

    /* Check for name collisions */
    var collision = false;

    field_array.forEach(function(entry) {
        if (collision)
            return false;

        if (field['name'] == entry['name']) {
            collision = true;
            return false;
        }
    });

    if (collision) {
        alert('Name collision detected on field "' + field['title'] + '/' + field['name'] + '" from menu "' + menu['title'] + '/' + menu['name'] + '".');
        return false;
    }

    /* Check for relationship inconsistencies */
    if (field['title'] == 'Drop-Down' || field['title'] == 'Multiple' || field['title'] == 'Mixed') {
        /* Check if the relationship is pointing to itself ... */
        if (field['name'].toLowerCase() == menu['name'].toLowerCase()) {
            alert('A relationship field cannot point to its own menu controller: "' + field['title'] + '/' + field['name'] + '" from menu "' + menu['title'] + '/' + menu['name'] + '".');
            return false;
        }

        /* Check if the relationship is pointing to anything valid */

    }

    /* Check default value */
    if ('properties' in field && field['properties']['default_value'].length) {
        /* Integer values shall not have default values greater than 2**32 (FIXME: This should be fixed by converting the int() types to bigint()) */
        if (field['title'] == 'Drop-Down' || field['title'] == 'Numeric') {
            if (field['properties']['default_value'] > 4294967295) {
                alert('Default value of field "' + field['title'] + '/' + field['name'] + '" from menu "' + menu['title'] + '/' + menu['name'] + '" must be lesser than 4294967295.');
                return false;
            }
        }
    }

    /* TODO: FIXME: Also check for duplicate Multiple and Mixed relationships that will generate similar relationship tables
     *              if created under different controllers but pointing to the same place (AKA ambiguity).
     */

    /* Validate the field length value */
    if ('properties' in field && field['properties']['len']) {
        var pat = /(^\d+$|^\d+[\.\,]{1,1}\d+$)/;

        if (!pat.test(field['properties']['len'])) {
            alert('Length of field "' + field['title'] + '/' + field['name'] + '" from menu "' + menu['title'] + '/' + menu['name'] + '" must be an integer or float.');
            return false;
        }

        switch (field['title']) {
            case 'Numeric': {
                if (parseInt(field['properties']['len']) > 255) {
                    alert('Length of field "' + field['title'] + '/' + field['name'] + '" from menu "' + menu['title'] + '/' + menu['name'] + '" must be less than or equal to 255.');
                    return false;
                }
            } break;
            case 'File':
            case 'Text': {
                if (field['constraints']['unique'] && parseInt(field['properties']['len']) > 767) {
                    alert('Field "' + field['title'] + '/' + field['name'] + '" from menu "' + menu['title'] + '/' + menu['name'] + '" has a UNIQUE constraint set and its length must be less than or equal to 767.');
                    return false;
                } else if (parseInt(field['properties']['len']) > 65532) {
                    /* Fields of type Text with sizes greater than 65532 will be converted to textarea (dbms type: text) */
                }
            } break;
        }
    }

    /* If this is a required field, validate if the placeholder is of the right type */
    if ('constraints' in field && field['constraints']['required']) {
        /* If this is a required field and no placeholder is set, it will cause an error on data model... */
        if (!field['properties']['default_value'].length) {
            alert('Field "' + field['title'] + '/' + field['name'] + '" from menu "' + menu['title'] + '/' + menu['name'] + '" was set as required, but no default value was defined.');
            return false;
        }

        /* Validate, per field type, if the supplied value match a particular pattern */
        switch (field['title']) {
            case 'Separator': {
                /* Separators cannot be set as required because they will never (and they must not) have values set */
                alert('Field "' + field['title'] + '/' + field['name'] + '" from menu "' + menu['title'] + '/' + menu['name'] + '" cannot have a required constraint.');
                return false;
            } break;
            case 'Numeric': {
                var pat = /(^\d+$|^\d+\.\d+$)/;

                if (!pat.test(field['properties']['default_value'])) {
                    alert('Field "' + field['title'] + '/' + field['name'] + '" from menu "' + menu['title'] + '/' + menu['name'] + '" contains an invalid format on its default value for the Numeric type (integer or float expected).');
                    return false;
                }
            } break;
            case 'Timer':
            case 'Time': {
                var pat = /(^\d{1,2}:\d{1,2}$|^\d{1,2}:\d{1,2}:\d{1,2}$)/;

                if (!pat.test(field['properties']['default_value'])) {
                    alert('Field "' + field['title'] + '/' + field['name'] + '" from menu "' + menu['title'] + '/' + menu['name'] + '" contains an invalid format on its default value for the Time type (HH:MM:SS expected).');
                    return false;
                }
            } break;
            case 'Date': {
                var pat = /^\d{4,4}-\d{2,2}-\d{2,2}$/;

                if (!pat.test(field['properties']['default_value'])) {
                    alert('Field "' + field['title'] + '/' + field['name'] + '" from menu "' + menu['title'] + '/' + menu['name'] + '" contains an invalid format on its default value for the Date type (YYYY-MM-DD expected).');
                    return false;
                }
            } break;
            case 'Date &amp; Time': {
                var pat = /^\d{4,4}-\d{2,2}-\d{2,2} \d{1,2}:\d{1,2}:\d{1,2}$/;

                if (!pat.test(field['properties']['default_value'])) {
                    alert('Field "' + field['title'] + '/' + field['name'] + '" from menu "' + menu['title'] + '/' + menu['name'] + '" contains an invalid format on its default value for the Date & Time type (YYYY-MM-DD HH:MM:SS expected).');
                    return false;
                }
            } break;
            case 'Drop-Down': {
                var pat = /^\d+$/i;

                if (!pat.test(field['properties']['default_value'])) {
                    alert('Field "' + field['title'] + '/' + field['name'] + '" from menu "' + menu['title'] + '/' + menu['name'] + '" contains an invalid format on its default value for the Drop-Down type (integer expected).');
                    return false;
                }
            } break;
        }
    }

    /* All good */
    return true;
};

ndphp.ide.ide_integrity_check_application = function(application) {
    var valid = true; /* Assume valid state */

    /* Iterate over all application entries and grant that they're valid */
    application['menus'].forEach(function(menu) {
        menu['fields'].forEach(function(field) {
            /* If the field type is a relationship ... */
            if (field['title'] == 'Drop-Down' || field['title'] == 'Multiple' || field['title'] == 'Mixed') {
                var linked = false;

                /* ... grant that every relationship points to a valid controller */
                application['menus'].forEach(function(ctrl) {
                    if ((field['name'].toLowerCase() == ctrl['name'].toLowerCase()) && (menu['name'] != ctrl['name'])) {
                        linked = true;
                        return false;
                    }
                });

                /* If the relationship field is not linked (or the link is invalid), interrupt the validation */
                if (!linked) {
                    alert('A field of type "' + field['title'] + '" from menu "' + menu['title'] + '/' + menu['name'] + '" does not link to a valid menu controller.');
                    valid = false;
                    return false;
                }
            }
        });

        /* Do not continue validation if the current application state is invalid */
        if (!valid)
            return false;
    });

    /* Return the current validation state */
    return valid;
}

ndphp.ide.ide_obj_allow_drop = function(e) {
    e.preventDefault();
};

ndphp.ide.ide_obj_drag_common = function(e, family) {
	/* Set object properties */
    e.dataTransfer.setData("objId", e.target.id);
    e.dataTransfer.setData("family", family); /* If family is an array, it'll be serialized */
};

ndphp.ide.ide_obj_container_drop = function(e) {
    /* NOTE: This function handles object instatiation (moves between different, compatible containers) */

    e.preventDefault();
    var drag_obj_id = e.dataTransfer.getData("objId");
    var drag_obj_family = e.dataTransfer.getData("family").split(',');

    /* Check if some other event handler (ide_obj_draggable_drop()) is already processing the drop. */
    if (ndphp.ide.ignore_container_event) {
        ndphp.ide.ignore_container_event = false;
        return;
    }

    /* Validate if this object is dropable on the target */
    if (drag_obj_family.indexOf('trash') > 0 && e.target.id != 'container_trash') {
    	/* This object can only be destroyed, not cloned */
		alert('This object can only be moved to trash.');
		return;
	} else if (e.target.id == 'container_trash' && drag_obj_family.indexOf('trash') == -1) {
		/* Cannot remove a pool */
		alert('This object cannot be trashed.');
		return;
	} else if (drag_obj_family.indexOf(String(e.target.id).split('_')[1]) == -1) {
		/* Incompatible object families */
    	alert('Incompatible objects');
    	return;
    }

    /* Drop the object, but decide if we'll be cloning or deleting it */
    if (e.target.id != 'container_trash') {
    	/* Clone and append a new object to target. Also modify the cloned object id attr by setting a new and unique counter */
    	jQuery('#' + e.target.id).append(jQuery('#' + drag_obj_id).clone().attr('id', drag_obj_id + '_' + (++ ndphp.ide.obj_count)).attr('ondragstart', 'ndphp.ide.ide_obj_drag_common(event, [ \'' + drag_obj_family[0] + '\', \'trash\' ])'));
        jQuery('#' + drag_obj_id + '_' + ndphp.ide.obj_count + ' span#title').removeAttr('title');

        /* Cursor shall change to 'cell' if the object name is directly editable */
        if (jQuery('#' + drag_obj_id + '_' + ndphp.ide.obj_count + ' span#title').html() != 'Drop-Down' &&
            jQuery('#' + drag_obj_id + '_' + ndphp.ide.obj_count + ' span#title').html() != 'Multiple' &&
            jQuery('#' + drag_obj_id + '_' + ndphp.ide.obj_count + ' span#title').html() != 'Mixed')
        {
            jQuery('#' + drag_obj_id + '_' + ndphp.ide.obj_count + ' span#name').css('cursor', 'cell');
        }

        /* If the object is of fields family, cursor shall be a context-menu by default... */
        if (drag_obj_family.indexOf('fields') != -1) {
            jQuery('#' + drag_obj_id + '_' + ndphp.ide.obj_count + ' span#title').css('cursor', 'context-menu');
        } else if (drag_obj_family.indexOf('menu') != -1) {
            /* If it is a menu object, cursor is a pointer (which will change to context-menu upon selection) */
            jQuery('#' + drag_obj_id + '_' + ndphp.ide.obj_count + ' span#title').css('cursor', 'pointer');
        }
    } else {
    	/* Remove this object */
   		jQuery('#' + drag_obj_id).remove();
    }
};

ndphp.ide.ide_obj_draggable_drop = function(e) {
    /* NOTE: This function handles object moves inside the same container */

    e.preventDefault();
    var drag_obj_id = e.dataTransfer.getData("objId");
    var drag_obj_family = e.dataTransfer.getData("family").split(',');
    var target_obj = e.target;

    /* If we did not hit the div itself (the parent) we need to adjust target_obj (as we must have hit a div child) */
    if (!jQuery(e.target).is('div'))
        target_obj = jQuery(e.target).parent();

    /* Create obj id lists */
    obj_drag_id_list = drag_obj_id.split('_');
    obj_target_id_list = String(jQuery(target_obj).attr('id')).split('_');

    /* Grant that we're not droping an non-instanciated object over an instantiated object (disallow reordering) */
    if (!(parseInt(obj_drag_id_list[obj_drag_id_list.length - 1]) > 0))
        return;

    /* Also grant that an instantiated object cannot be dropped over a non-instantiated object */
    if (!(parseInt(obj_target_id_list[obj_target_id_list.length - 1]) > 0))
        return;

    /* Deny ide_obj_container_drop() execution */
    ndphp.ide.ignore_container_event = true;

    /* Allow moving menu entries into field objects (linking) ... */
    if (drag_obj_family[0] == "menu" && String(jQuery(target_obj).attr('id')).split('_')[1] == "fields") {
        /* ... but only allow it if the target is a Drop-Down, Multiple or Mixed type field */
        if (jQuery('#' + jQuery(target_obj).attr('id') + ' span#title').html() != 'Drop-Down' &&
            jQuery('#' + jQuery(target_obj).attr('id') + ' span#title').html() != 'Multiple' &&
            jQuery('#' + jQuery(target_obj).attr('id') + ' span#title').html() != 'Mixed')
        {
            alert('Menu entries can only be linked with relational fields (Drop-Down, Multiple or Mixed).');
            return;
        }

        /* Menu entry name will be copied into field name */
        jQuery('#' + jQuery(target_obj).attr('id') + ' span#name').html(jQuery('#' + drag_obj_id + ' span#name').html());
        jQuery('#' + jQuery(target_obj).attr('id') + ' span#name').css('font-weight', 'bold');
        return;
    }

    /* Grant that object won't move outside it's current outer <div> */
    if (drag_obj_family[0].indexOf(String(jQuery(target_obj).attr('id')).split('_')[1])) {
        alert('Cannot move the object outside of its container.');
        return;
    }

    /* Get the parent object, as we're hiting a <span>, not a <div> */
    parent_drop_obj_id = jQuery(target_obj).attr('id');

    /* Grant that we're not moving into the same object (which will cause a loss of the object) */
    if (parent_drop_obj_id == drag_obj_id) {
        alert('Cannot move an object into itself.');
        return;
    }

    /* Place the dragging object right before the target */
    jQuery('#' + parent_drop_obj_id).before(jQuery('#' + drag_obj_id));
};

ndphp.ide.name_click = function(spanObj) {
    /* Check if this is an instanciated object */
    nameobj_id_list = String(jQuery(spanObj).parent().attr('id')).split('_');

    if (!parseInt(nameobj_id_list[nameobj_id_list.length - 1]) > 0) {
        /* If it's not an instance of an object, do nothing ... */
        return;
    }

    jQuery(spanObj).next().css('display', 'inline');
    jQuery(spanObj).css('display', 'none');
    jQuery(spanObj).next().focus();
};

ndphp.ide.input_blur = function(e) {
    var evt = e || window.event
    jQuery(evt.target).prev().html(jQuery(evt.target).val());

    jQuery(evt.target).prev().css('display', 'inline-block');
    jQuery(evt.target).prev().css('font-weight', 'bold');

    jQuery(evt.target).css('display', 'none');
};

ndphp.ide.keydown = function(e) {
    var evt = e || window.event;

    if (evt.keyCode === 13) {
        jQuery(evt.target).prev().html(jQuery(evt.target).val());

        jQuery(evt.target).prev().css('display', 'inline-block');
        jQuery(evt.target).prev().css('font-weight', 'bold');

        jQuery(evt.target).css('display', 'none');

        return false;
    }
};

ndphp.ide.menu_entry_load_fields = function(menuobj) {
    /* Check if this is an instanciated object */
    menuobj_id_list = String(jQuery(menuobj).attr('id')).split('_');

    if (!parseInt(menuobj_id_list[menuobj_id_list.length - 1]) > 0) { /* We're checking if the last slice of the array is an integer */
        /* If it's not an instance of an object, do nothing ... */
        return;
    }

    /* If this menu item is already selected, do nothing */
    if (jQuery('#' + menuobj.id).hasClass('selected'))
        return; /* No need to process anything else */

    /* Hide all container_fields_* divs */
    jQuery('[id^=container_fields]').css('display', 'none');
    jQuery('[id^=container_controller]').css('display', 'none');

    /* If there isn't a field container for this menu entry, create one... */
    if (!jQuery('#container_fields_' + menuobj.id).length) {
        jQuery('#canvas #actions').before(jQuery('#container_fields').clone().attr('id', 'container_fields_' + menuobj.id));
    }

    /* ... and show the menu fields container */
    jQuery('#container_fields_' + menuobj.id).css('display', 'block');

    /* Remove highlight from all menu entries */
    jQuery('#canvas [id^=obj_menu_entry]').removeClass('selected');
    jQuery('#canvas [id^=obj_menu_entry] span#title').css('cursor', 'pointer');

    /* Highlight the current (selected) menu entry */
    jQuery(menuobj).addClass('selected');
    jQuery('#' + menuobj.id + ' span#title').css('cursor', 'context-menu');
};

ndphp.ide.menu_entry_edit_controller = function(e, menuobj) {
    /* Do not show the default context-menu */
    e.preventDefault();

    /* Toggle between Controller/Fields: If the Controller object is being displayed, hide it and display the Fields object */
    if (jQuery('#container_controller_' + menuobj.id).css('display') == 'block') {
        /* De-select the current menu to allow the menu_entry_load_fields() trigger... (FIXME: Fix this hack...) */
        jQuery('#canvas [id^=obj_menu_entry]').removeClass('selected');
        ndphp.ide.menu_entry_load_fields(menuobj);
        return;
    }

    /* Check if this is an instanciated object */
    menuobj_id_list = String(jQuery(menuobj).attr('id')).split('_');

    if (!parseInt(menuobj_id_list[menuobj_id_list.length - 1]) > 0) { /* We're checking if the last slice of the array is an integer */
        /* If it's not an instance of an object, do nothing ... */
        return;
    }

    /* Hide all container_fields_* divs */
    jQuery('[id^=container_fields]').css('display', 'none');
    jQuery('[id^=container_controller]').css('display', 'none');

    /* If there isn't a field container for this menu entry, create one... */
    if (!jQuery('#container_controller_' + menuobj.id).length) {
        jQuery('#canvas #actions').before(jQuery('#container_controller').clone().attr('id', 'container_controller_' + menuobj.id));
        jQuery('#container_controller_' + menuobj.id + ' textarea').attr('id', 'textarea_ide_' + menuobj.id);
    }

    editAreaLoader.init({
        id: "textarea_ide_" + menuobj.id,
        syntax: "php",
        allow_toggle: false,
        start_highlight: true,
        toolbar: "search, go_to_line, fullscreen, |, undo, redo"
    });

    /* ... and show the menu fields container */
    jQuery('#container_controller_' + menuobj.id).css('display', 'block');

    /* Remove highlight from all menu entries */
    jQuery('#canvas [id^=obj_menu_entry]').removeClass('selected');
    jQuery('#canvas [id^=obj_menu_entry] span#title').css('cursor', 'pointer');

    /* Highlight the current (selected) menu entry */
    jQuery(menuobj).addClass('selected');
    jQuery('#' + menuobj.id + ' span#title').css('cursor', 'context-menu');

};

ndphp.ide.dialog_show = function(obj, type) {
    /* type may assume the following values: field, menu */

    /* Check if the obj is the parent or the child. If it's a child, get the parent (div) */
    if (!jQuery(obj).is('div'))
        obj = jQuery(obj).parent();

    /* If the type is menu, grant that dialog is only shown when the menu is already selected */
    if (type == 'menu' && !jQuery(obj).hasClass('selected')) {
        return;
    }

    /* Check if this is an instanciated object */
    var fieldsobj_id_list = String(jQuery(obj).attr('id')).split('_');
    var obj_counter_id = parseInt(fieldsobj_id_list[fieldsobj_id_list.length - 1]);

    if (!(obj_counter_id > 0)) {
        /* If it's not an instance of an object, do nothing ... */
        return;
    }

    if (!jQuery('#' + jQuery(obj).attr('id') + ' div[id^=dialog_' + type + '_settings_]').length) {
        /* Instanciate a new dialog_field_settings object */
        jQuery(obj).append(jQuery('#dialog_' + type + '_settings').clone().attr('id', 'dialog_' + type + '_settings_' + obj_counter_id));
    }

    /* Display the dialog */
    jQuery('#' + jQuery(obj).attr('id') + ' div[id^=dialog_' + type + '_settings_]').dialog({
        modal: true,
        resizable: false,
        width: 680,
        title: jQuery('#' + jQuery(obj).attr('id') + ' span#name').html() + ' Settings',
        close: function() {
            jQuery(this).dialog("destroy"); /* Reset the dialog upon close */
        }
    });
};

ndphp.ide.build = function(check, save, build) {
    var application = {};
    var validated = true; /* Assume model as valid... This may be changed during validation checks... */

    ndphp.ui.busy();

    application['obj_count'] = ndphp.ide.obj_count;
    application['menus'] = [];

    /* Fetch menu data */
    jQuery('#container_menu').children().each(function() {
        /* If the current model is invalid, do not continue processing */
        if (!validated)
            return false;

        /* Check if the found element is a div (an instantiated object) */
        if (!jQuery(this).is('div'))
            return;

        var menu = {};

        /* Process this menu entry object */
        var obj_menu_id = String(jQuery(this).attr('id'));
        //var obj_menu_data = obj_menu_id.split('_');


        /* Process menu basics */
        menu['name'] = jQuery('#' + obj_menu_id + ' #name').html();
        menu['title'] = jQuery('#' + obj_menu_id + ' #title').html();
        menu['type'] = jQuery('#' + obj_menu_id + ' #type').val();
        menu['obj_id'] = obj_menu_id;
        /* Debug */
        //alert('Name: ' + menu['name'] + ', ObjID: ' + menu['obj_id'] + ', Type: ' + menu['type']);

        /* Check if dialog_menu_settings_* exist. If not, skip it as default values will be assumed. */
        if (jQuery('#' + obj_menu_id + ' div[id^=dialog_menu_settings').length) {
            /* Process menu options */
            menu['options'] = {};
            menu['options']['logging'] = !!jQuery('#' + obj_menu_id + ' div[id^=dialog_menu_settings] #options_logging').is(':checked');
            menu['options']['accounting'] = !!jQuery('#' + obj_menu_id + ' div[id^=dialog_menu_settings] #options_accounting').is(':checked');
            menu['options']['linking'] = !!jQuery('#' + obj_menu_id + ' div[id^=dialog_menu_settings] #options_linking').is(':checked');
            menu['options']['hidden'] = !!jQuery('#' + obj_menu_id + ' div[id^=dialog_menu_settings] #options_hidden').is(':checked');
            /* Debug */
            //alert('Options -- Logging: ' + menu['options']['logging'] + ', Accounting: ' + menu['options']['accounting'] + ', Linking: ' + menu['options']['linking']);


            /* Process menu properties */
            menu['properties'] = {};
            menu['properties']['alias'] = jQuery('#' + obj_menu_id + ' div[id^=dialog_menu_settings] #property_alias').val();
            menu['properties']['icon'] = jQuery('#' + obj_menu_id + ' div[id^=dialog_menu_settings] #property_icon').val();
            menu['properties']['order_field'] = jQuery('#' + obj_menu_id + ' div[id^=dialog_menu_settings] #property_order_field').val();
            menu['properties']['order_direction'] = jQuery('#' + obj_menu_id + ' div[id^=dialog_menu_settings] #property_order_direction').val();
            menu['properties']['rpp'] = jQuery('#' + obj_menu_id + ' div[id^=dialog_menu_settings] #property_rpp').val();
            menu['properties']['help'] = jQuery('#' + obj_menu_id + ' div[id^=dialog_menu_settings] #property_help').val();
            /* Debug */
            /*alert('Properties -- Alias: ' + menu['properties']['alias'] + ', OrderField: ' + menu['properties']['order_field'] +
                ', Order: ' + menu['properties']['order_direction'] + ', Rpp: ' + menu['properties']['rpp'] + ', Help: ' + menu['properties']['help']);*/


            /* Process menu permissions */
            menu['permissions'] = {};

            menu['permissions']['create'] = [];
            jQuery('#' + obj_menu_id + ' div[id^=dialog_menu_settings] #perm_roles_create :selected').each(function(i) {
                /* Remove the _CREATE suffix from role name and push it into the roles array */
                var role_array = String(jQuery(this).text()).split('_');
                role_array.pop();
                menu['permissions']['create'][i] = role_array.join('_');
                /* Debug */
                //alert('Index: ' + i + ', Create Role: ' + menu['permissions']['create'][i]);
            });

            menu['permissions']['read'] = [];
            jQuery('#' + obj_menu_id + ' div[id^=dialog_menu_settings] #perm_roles_read :selected').each(function(i) {
                /* Remove the _READ suffix from role name and push it into the roles array */
                var role_array = String(jQuery(this).text()).split('_');
                role_array.pop();
                menu['permissions']['read'][i] = role_array.join('_');
                /* Debug */
                //alert('Index: ' + i + ', Read Role: ' + menu['permissions']['read'][i]);
            });

            menu['permissions']['update'] = [];
            jQuery('#' + obj_menu_id + ' div[id^=dialog_menu_settings] #perm_roles_update :selected').each(function(i) {
                /* Remove the _UPDATE suffix from role name and push it into the roles array */
                var role_array = String(jQuery(this).text()).split('_');
                role_array.pop();
                menu['permissions']['update'][i] = role_array.join('_');
                /* Debug */
                //alert('Index: ' + i + ', Update Role: ' + menu['permissions']['update'][i]);
            });

            menu['permissions']['delete'] = [];
            jQuery('#' + obj_menu_id + ' div[id^=dialog_menu_settings] #perm_roles_delete :selected').each(function(i) {
                /* Remove the _DELETE suffix from role name and push it into the roles array */
                var role_array = String(jQuery(this).text()).split('_');
                role_array.pop();
                menu['permissions']['delete'][i] = role_array.join('_');
                /* Debug */
                //alert('Index: ' + i + ', Delete Role: ' + menu['permissions']['delete'][i]);
            });
        }

        /* Process menu controller code */
        menu['controller'] = {};
        menu['controller']['code'] = editAreaLoader.getValue('textarea_ide_' + menu['obj_id']);

        /* Debug */
        //alert(menu['controller']['code']);

        /* Process menu fields */
        menu['fields'] = [];

        jQuery('#container_fields_' + menu['obj_id']).children().each(function() {
            /* If the current model is invalid, do not continue processing */
            if (!validated)
                return false;

            /* Check if the found element is a div (an instantiated object) */
            if (!jQuery(this).is('div'))
                return;
            
            var field = {};

            var obj_field_id = String(jQuery(this).attr('id'));
            //var obj_field_data = obj_field_id.split('_');

            field['name'] = jQuery('#' + obj_field_id + ' #name').html();
            field['title'] = jQuery('#' + obj_field_id + ' #title').html();
            field['type'] = jQuery('#' + obj_field_id + ' #type').val();
            field['obj_id'] = obj_field_id;
            /* Debug */
            //alert('Field Name: ' + field['name'] + ', Field ObjID: ' + field['obj_id'] + ', Type: ' + field['type']);

            /* Check if dialog_field_settings exist. If not, skip it as default values will be assumed. */
            if (jQuery('#' + obj_field_id + ' div[id^=dialog_field_settings').length) {
                /** Constraints **/
                field['constraints'] = {};
                field['constraints']['required'] = !!jQuery('#' + obj_field_id + ' div[id^=dialog_field_settings] #constraint_required').is(':checked');
                field['constraints']['unique'] = !!jQuery('#' + obj_field_id + ' div[id^=dialog_field_settings] #constraint_unique').is(':checked');
                field['constraints']['hidden'] = !!jQuery('#' + obj_field_id + ' div[id^=dialog_field_settings] #constraint_hidden').is(':checked');
                /* Debug */
                //alert('Field Constraints -- Required: ' + field['constraints']['required'] + ', Unique: ' + field['constraints']['unique'] + ', Hidden: ' + field['constraints']['hidden']);

                /** Properties **/
                field['properties'] = {};
                field['properties']['alias'] = jQuery('#' + obj_field_id + ' div[id^=dialog_field_settings] #property_alias').val();
                field['properties']['default_value'] = jQuery('#' + obj_field_id + ' div[id^=dialog_field_settings] #property_default_value').val();
                field['properties']['placeholder'] = jQuery('#' + obj_field_id + ' div[id^=dialog_field_settings] #property_placeholder').val();
                field['properties']['len'] = jQuery('#' + obj_field_id + ' div[id^=dialog_field_settings] #property_length').val();
                field['properties']['input_pattern'] = jQuery('#' + obj_field_id + ' div[id^=dialog_field_settings] #property_input_pattern').val();
                field['properties']['units'] = jQuery('#' + obj_field_id + ' div[id^=dialog_field_settings] #property_units').val();
                field['properties']['units_on_left'] = jQuery('#' + obj_field_id + ' div[id^=dialog_field_settings] #property_units_left').is(':checked');
                field['properties']['help'] = jQuery('#' + obj_field_id + ' div[id^=dialog_field_settings] #property_help').val();
                /* Debug */
                //alert('Field Properties -- Alias: ' + field['properties']['alias'] + ', Length: ' + field['properties']['len'] + ', Help: ' + field['properties']['help']);

                /** Visualization **/
                field['visualization'] = {};
                field['visualization']['create'] = !!jQuery('#' + obj_field_id + ' div[id^=dialog_field_settings] #visual_show_create').is(':checked');
                field['visualization']['view'] = !!jQuery('#' + obj_field_id + ' div[id^=dialog_field_settings] #visual_show_view').is(':checked');
                field['visualization']['edit'] = !!jQuery('#' + obj_field_id + ' div[id^=dialog_field_settings] #visual_show_edit').is(':checked');
                field['visualization']['remove'] = !!jQuery('#' + obj_field_id + ' div[id^=dialog_field_settings] #visual_show_remove').is(':checked');
                field['visualization']['list'] = !!jQuery('#' + obj_field_id + ' div[id^=dialog_field_settings] #visual_show_list').is(':checked');
                field['visualization']['result'] = !!jQuery('#' + obj_field_id + ' div[id^=dialog_field_settings] #visual_show_result').is(':checked');
                field['visualization']['search'] = !!jQuery('#' + obj_field_id + ' div[id^=dialog_field_settings] #visual_show_search').is(':checked');
                field['visualization']['export'] = !!jQuery('#' + obj_field_id + ' div[id^=dialog_field_settings] #visual_show_export').is(':checked');
                field['visualization']['mixed'] = !!jQuery('#' + obj_field_id + ' div[id^=dialog_field_settings] #visual_show_mixed').is(':checked');
                /* Debug */
                /*alert('Visualization -- Create: ' + field['visualization']['create'] +
                    ', View: ' + field['visualization']['view'] +
                    ', Edit: ' + field['visualization']['edit'] +
                    ', Remove: ' + field['visualization']['create'] +
                    ', List: ' + field['visualization']['list'] +
                    ', Result: ' + field['visualization']['result'] +
                    ', Export: ' + field['visualization']['export']);*/

                /** Permissions **/
                field['permissions'] = {};

                field['permissions']['create'] = [];
                jQuery('#' + obj_field_id + ' div[id^=dialog_field_settings] #perm_roles_create :selected').each(function(i) {
                    /* Remove the _CREATE suffix from role name and push it into the roles array */
                    var role_array = String(jQuery(this).text()).split('_');
                    role_array.pop();
                    field['permissions']['create'][i] = role_array.join('_');
                    /* Debug */
                    //alert('Index: ' + i + ', Create Role: ' + field['permissions']['create'][i]);
                });

                field['permissions']['read'] = [];
                jQuery('#' + obj_field_id + ' div[id^=dialog_field_settings] #perm_roles_read :selected').each(function(i) {
                    /* Remove the _READ suffix from role name and push it into the roles array */
                    var role_array = String(jQuery(this).text()).split('_');
                    role_array.pop();
                    field['permissions']['read'][i] = role_array.join('_');
                    /* Debug */
                    //alert('Index: ' + i + ', Read Role: ' + field['permissions']['read'][i]);
                });

                field['permissions']['update'] = [];
                jQuery('#' + obj_field_id + ' div[id^=dialog_field_settings] #perm_roles_update :selected').each(function(i) {
                    /* Remove the _UPDATE suffix from role name and push it into the roles array */
                    var role_array = String(jQuery(this).text()).split('_');
                    role_array.pop();
                    field['permissions']['update'][i] = role_array.join('_');
                    /* Debug */
                    //alert('Index: ' + i + ', Update Role: ' + field['permissions']['update'][i]);
                });

                field['permissions']['search'] = [];
                jQuery('#' + obj_field_id + ' div[id^=dialog_field_settings] #perm_roles_search :selected').each(function(i) {
                    /* Remove the _SEARCH suffix from role name and push it into the roles array */
                    var role_array = String(jQuery(this).text()).split('_');
                    role_array.pop();
                    field['permissions']['search'][i] = role_array.join('_');
                    /* Debug */
                    //alert('Index: ' + i + ', Search Role: ' + field['permissions']['search'][i]);
                });
            }

            /* Validate menu integrity */
            if (!ndphp.ide.ide_integrity_check_field(menu, field, menu['fields'])) {
                validated = false;
                return false;
            }

            /* Push field object into menu fields array */
            menu['fields'].push(field);
        });

        /* Validate menu integrity */
        if (!ndphp.ide.ide_integrity_check_menu(menu, application['menus'])) {
            validated = false;
            return false;
        }

        /* Push menu into applicaation menus array */
        application['menus'].push(menu);
    });

    /* Perform aditional validaations over the full application model */
    if (validated)
        validated = ndphp.ide.ide_integrity_check_application(application);

    /* Check if everything was successfully passed all validation checks */
    if (!validated) {
        alert('Application Model failed to pass integrity checks.');
        ndphp.ui.ready();
        return false;
    }

    /* Debug */
    //alert('application[\'menus\'][0][\'fields\'][0][\'name\']: ' + application['menus'][0]['fields'][0]['name']);

    /* Perform the requested operation */
    if (check == true && save == false && build == false) {
        /* TODO: Check application model for consistency */
        alert('All checks successfuly passed.');
        ndphp.ui.ready();
    } else if (save == true && build == false) {
        /* Convert application object to JSON and submit it to application builder */
        jQuery.ajax({
            type: "POST",
            data: JSON.stringify(application),
            url:  "<?=base_url()?>index.php/builder/save_model",
            success: function(data) {
                ndphp.ui.ready();
                alert(data);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                ndphp.ui.ready();
                alert(xhr.responseText);
            }
        });
    } else if (build == true) {
        /* Convert application object to JSON and submit it to application builder */
        jQuery.ajax({
            type: "POST",
            data: JSON.stringify(application),
            url:  "<?=base_url()?>index.php/builder/deploy_model",
            success: function(data) {
                ndphp.ui.ready();
                alert(data);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                ndphp.ui.ready();
                alert(xhr.responseText);
            }
        });
    } else {
        alert('Requested operation is invalid.');
    }
};

ndphp.ide.save = function() {
    ndphp.ide.build(true /* check */, true /* save */, false /* build */);
}

ndphp.ide.check = function() {
    ndphp.ide.build(true /* check */, false /* save */, false /* build */);
};

ndphp.ide.deploy = function() {
    ndphp.ide.build(true /* check */, true /* save */, true /* build */);
}
