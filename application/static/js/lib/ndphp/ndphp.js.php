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

/*
 *
 * TODO, FIXME: A lot of javascript is still wandering around
 *              the views that should be migrated to this file.
 *				There's also plenty of code here that must be
 *				reviewed and consolidated.
 *
 */

if (typeof ndphp == 'undefined') {
    ndphp = {};

	ndphp.origin_controller = null;
	ndphp.last_listing_op = null;
	ndphp.mixed_timer = new Array();

	/* Current context */
    ndphp.current = {};
    ndphp.current.controller = null;
    ndphp.current.charset = null;
    ndphp.current.tab_index = 0;
}

/* Theme */
ndphp.theme = {}
ndphp.theme.name = 'Blueish';

ndphp.theme.set = function(name) {
	ndphp.theme.name = name;
};

/* Animations */
ndphp.animation = {}
ndphp.animation.delay = 800;
ndphp.animation.ordering_delay = 600;

ndphp.animation.set_default_delay = function(speed) {
	ndphp.animation.delay = speed;
}

ndphp.animation.set_ordering_delay = function(speed) {
	ndphp.animation.ordering_delay = speed;
}

ndphp.animation.set_default_type = function(type) {
	if (type == 'Slide') {
		jQuery.fn.nd_animate_hide = function(speed, callback) {
			return jQuery(this).slideUp(speed, callback);
		}

		jQuery.fn.nd_animate_show = function(speed, callback) {
			return jQuery(this).slideDown(speed, callback);
		}
	} else if (type == 'Fade') {
		jQuery.fn.nd_animate_hide = function(speed, callback) {
			return jQuery(this).fadeOut(speed, callback);
		}

		jQuery.fn.nd_animate_show = function(speed, callback) {
			return jQuery(this).fadeIn(speed, callback);
		}
	} else if (type == 'None') {
		jQuery.fn.nd_animate_hide = function(speed, callback) {
			return jQuery(this).fadeOut(0, callback);
		}

		jQuery.fn.nd_animate_show = function(speed, callback) {
			return jQuery(this).fadeIn(0, callback);
		}
	} else {
		alert('<?=filter_js_str(NDPHP_LANG_MOD_INVALID_ANIMATION_DEFAULT, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>');
	}
}

ndphp.animation.set_ordering_type = function(type) {
	if (type == 'Slide') {
		jQuery.fn.nd_animate_ordering_hide = function(speed, callback) {
			return jQuery(this).slideUp(speed, callback);
		}

		jQuery.fn.nd_animate_ordering_show = function(speed, callback) {
			return jQuery(this).slideDown(speed, callback);
		}
	} else if (type == 'Fade') {
		jQuery.fn.nd_animate_ordering_hide = function(speed, callback) {
			return jQuery(this).fadeOut(speed, callback);
		}

		jQuery.fn.nd_animate_ordering_show = function(speed, callback) {
			return jQuery(this).fadeIn(speed, callback);
		}
	} else if (type == 'None') {
		jQuery.fn.nd_animate_ordering_hide = function(speed, callback) {
			/* FIXME: For some reason, if we just return callback() here, the contents will disapear.
			 * So currently we still use the fadeing animation, but with 0 speed.
			 */ 
			return jQuery(this).fadeOut(0, callback);
		}

		jQuery.fn.nd_animate_ordering_show = function(speed, callback) {
			/* FIXME: For some reason, if we just return callback() here, the contents will disapear.
			 * So currently we still use the fadeing animation, but with 0 speed.
			 */
			return jQuery(this).fadeIn(0, callback);
		}
	} else {
		alert('<?=filter_js_str(NDPHP_LANG_MOD_INVALID_ANIMATION_ORDERING, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>');
	}
}

/* Utils */
ndphp.utils = {};

ndphp.utils.crlf_callback = function(e, callback) {
	if (typeof e != 'undefined' && (e.keyCode == 10 || e.keyCode == 13))
		callback(e);
};

ndphp.utils.safe_b64encode = function(input) {
	return base64.encode(input).replace('/', '@');
};

/* Global UI Management */
ndphp.ui = {};

ndphp.ui.busy = function(msg) {
	if (typeof msg == "undefined")
		msg = "<?=filter_html_js_str(NDPHP_LANG_MOD_INFO_LOADING, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>";

	jQuery.blockUI({
		css: { 
            border: 'none',
            padding: '2px',
            backgroundColor: '#000',
            'border-radius': '10px',
            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',
            opacity: .5,
            color: '#fff',
            top: '48px'
        },
        message: "<center>" + msg + "</center>"
	});
	jQuery("body").css("cursor", "progress");
};

ndphp.ui.ready = function() {
	jQuery("body").css("cursor", "auto");
	jQuery.unblockUI();
};

/* Navigation handlers */
ndphp.nav = {};

ndphp.nav._back_element_id = Array();
ndphp.nav._back_element_html = Array();
ndphp.nav._forward_element_id = Array();
ndphp.nav._forward_element_html = Array();

ndphp.nav.back_store = function(elem_id, elem_html) {
	ndphp.nav._back_element_id.push(elem_id);
	ndphp.nav._back_element_html.push(elem_html);
	ndphp.nav._forward_element_id = [];
	ndphp.nav._forward_element_html = [];
	jQuery('#ba_forward').html('<img alt="<?=filter_html_js_str(NDPHP_LANG_MOD_ACTION_FORWARD, NDPHP_LANG_MOD_DEFAULT_CHARSET)?> (<?=filter_html_js_str(NDPHP_LANG_MOD_STATUS_DISABLED, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>)" class="browsing_actions_icon" src="<?=static_images_url()?>/themes/' + ndphp.theme.name + '/icons/forward_disabled.png" />');
	jQuery('#ba_back').html('<a title="<?=filter_html_js_str(NDPHP_LANG_MOD_ACTION_BACK, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>" class="browsing_actions_entry" href="javascript:ndphp.nav.back_do()"><img alt="Back" class="browsing_actions_icon" src="<?=static_images_url()?>/themes/' + ndphp.theme.name + '/icons/back.png" /></a>');
};

ndphp.nav.back_do = function() {
	var _element_id = ndphp.nav._back_element_id.pop();
	var _element_html = ndphp.nav._back_element_html.pop();
	ndphp.nav._forward_element_id.push('body');
	ndphp.nav._forward_element_html.push(jQuery('#body').html());
	jQuery('#' + _element_id).html(_element_html);
	jQuery('#ba_forward').html('<a title="<?=filter_html_js_str(NDPHP_LANG_MOD_ACTION_FORWARD, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>" class="browsing_actions_entry" href="javascript:ndphp.nav.forward_do()"><img alt="<?=filter_html_js_str(NDPHP_LANG_MOD_ACTION_FORWARD, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>" class="browsing_actions_icon" src="<?=static_images_url()?>/themes/' + ndphp.theme.name + '/icons/forward.png" /></a>');

	if (!ndphp.nav._back_element_id.length)
		jQuery('#ba_back').html('<img alt="<?=filter_html_js_str(NDPHP_LANG_MOD_ACTION_BACK, NDPHP_LANG_MOD_DEFAULT_CHARSET)?> (<?=filter_html_js_str(NDPHP_LANG_MOD_STATUS_DISABLED, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>)" class="browsing_actions_icon" src="<?=static_images_url()?>/themes/' + ndphp.theme.name + '/icons/back_disabled.png" />');

	/* Update tabs, if any */
	jQuery(function() {
		jQuery('div[id^=entry_tabs]').tabs();
		jQuery('div[id^=entry_tabs]').on('tabsactivate', function(event, ui) {
			ndphp.current.tab_index = ui.newTab.index();
		});
		jQuery('div[id^=entry_tabs]').removeClass("ui-widget");
		jQuery('div[id^=entry_tabs]').css('border-radius', '0px');
		jQuery('#create, #edit, #remove, #view, #list, #result, #search, #groups').css('padding-top', '0px').css('padding-bottom', '0px');
	});
};

ndphp.nav.forward_do = function() {
	var _element_id = ndphp.nav._forward_element_id.pop();
	var _element_html = ndphp.nav._forward_element_html.pop();
	ndphp.nav._back_element_id.push('body');
	ndphp.nav._back_element_html.push(jQuery('#body').html());
	jQuery('#' + _element_id).html(_element_html);
	jQuery('#ba_back').html('<a title="<?=filter_html_js_str(NDPHP_LANG_MOD_ACTION_BACK, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>" class="browsing_actions_entry" href="javascript:ndphp.nav.back_do()"><img alt="<?=filter_html_js_str(NDPHP_LANG_MOD_ACTION_BACK, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>" class="browsing_actions_icon" src="<?=static_images_url()?>/themes/' + ndphp.theme.name + '/icons/back.png" /></a>');

	if (!ndphp.nav._forward_element_id.length)
		jQuery('#ba_forward').html('<img alt="<?=filter_html_js_str(NDPHP_LANG_MOD_ACTION_FORWARD, NDPHP_LANG_MOD_DEFAULT_CHARSET)?> (<?=filter_html_js_str(NDPHP_LANG_MOD_STATUS_DISABLED, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>)" class="browsing_actions_icon" src="<?=static_images_url()?>/themes/' + ndphp.theme.name + '/icons/forward_disabled.png" />');

	/* Update tabs, if any */
	jQuery(function() {
		jQuery('div[id^=entry_tabs]').tabs();
		jQuery('div[id^=entry_tabs]').on('tabsactivate', function(event, ui) {
			ndphp.current.tab_index = ui.newTab.index();
		});
		jQuery('div[id^=entry_tabs]').removeClass("ui-widget");
		jQuery('div[id^=entry_tabs]').css('border-radius', '0px');
		jQuery('#create, #edit, #remove, #view, #list, #result, #search, #groups').css('padding-top', '0px').css('padding-bottom', '0px');
	});
};

ndphp.nav.refresh_do = function() {
	return;
};

/* Form Handlers */
ndphp.form = {};

ndphp.form.submit_confirmation = function(e, ctrl, method_with_args, from_modal) {
	e.preventDefault();

	ndphp.ui.busy();

	jQuery.ajax({
		type: "POST",
		url: "<?=filter_js_str(base_url(), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>index.php/" + ctrl + "/" + method_with_args,
		success: function(data) {
			if (from_modal)
				Modalbox.hide();

			ndphp.ui.ready();
		},
		error: function(xhr, ajaxOptions, thrownError) {
			if (from_modal)
				Modalbox.hide();

			jQuery("#ajax_error_dialog").html(xhr.responseText);
			jQuery("#ajax_error_dialog").dialog({ modal: true, title: "<?=filter_html_js_str(NDPHP_LANG_MOD_UNABLE_OPERATION, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>" });
			ndphp.ui.ready();
		}
	});
}

ndphp.form.cancel_confirmation = function(e, from_modal) {
	e.preventDefault();

	if (from_modal)
		Modalbox.hide();
}

ndphp.form.submit_login = function(e) {
	e.preventDefault();

	ndphp.ui.busy('Authenticating...');

	jQuery.ajax({
		type: "POST",
		url: "<?=filter_js_str(base_url(), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>index.php/login/authenticate",
		data: jQuery("#loginform").serialize(),
		success: function(data) {
			ndphp.ui.ready();

			jQuery("#loginform").submit();
		},
		error: function(xhr, ajaxOptions, thrownError) {
			jQuery("#ajax_error_dialog").html(xhr.responseText);
			jQuery("#ajax_error_dialog").dialog({ modal: true, title: "<?=filter_html_js_str(NDPHP_LANG_MOD_UNABLE_AUTHENTICATE, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>" });
			ndphp.ui.ready();
		}
	});
}

ndphp.form.search_submitform = function(e, ctrl) {
	e.preventDefault();

	if (document.searchform.onsubmit && !document.searchform.onsubmit()) {
		return;
	}

	ndphp.ui.busy();

	jQuery("#body").nd_animate_hide(ndphp.animation.delay, function() {
		jQuery.ajax({
			type: "POST",
			url: "<?=filter_js_str(base_url(), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>index.php/" + ctrl + "/result_body_ajax/basic",
			data: jQuery("#searchform").serialize(),
			success: function(data) {
				var html = jQuery(data);
				ndphp.nav.back_store('body', jQuery('#body').html());
				jQuery("#body").html(html).nd_animate_show(ndphp.animation.delay, function() {
					ndphp.ui.ready();
				});
			},
			error: function(xhr, ajaxOptions, thrownError) {
				jQuery("#ajax_error_dialog").html('<?=filter_html_js_str(NDPHP_LANG_MOD_UNABLE_SEARCH, NDPHP_LANG_MOD_DEFAULT_CHARSET)?> <?=filter_html_js_str(NDPHP_LANG_MOD_ATTN_VALIDATE_VALUES, NDPHP_LANG_MOD_DEFAULT_CHARSET)?><br /><br /><span style="font-weight: bold"><?=filter_html_js_str(ucfirst(NDPHP_LANG_MOD_WORD_REASON), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>:</span> ' + xhr.responseText);
				jQuery("#ajax_error_dialog").dialog({ modal: true, title: '<?=filter_html_js_str(NDPHP_LANG_MOD_CANNOT_SEARCH, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>' });
				ndphp.ui.ready();
			}
		})
	});

	return false;
};

ndphp.form.search_global_submitform = function(e, ctrl) {
	e.preventDefault();

	if (document.searchform.onsubmit && !document.searchform.onsubmit()) {
		return;
	}

	ndphp.ui.busy();

	jQuery("#body").nd_animate_hide(ndphp.animation.delay, function() {
		jQuery.ajax({
			type: "POST",
			url: "<?=filter_js_str(base_url(), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>index.php/" + ctrl + "/result_global_body_ajax",
			data: jQuery("#searchform").serialize(),
			success: function(data) {
				var html = jQuery(data);
				ndphp.nav.back_store('body', jQuery('#body').html());
				jQuery("#body").html(html).nd_animate_show(ndphp.animation.delay, function() {
					ndphp.ui.ready();
				});
			},
			error: function(xhr, ajaxOptions, thrownError) {
				jQuery("#ajax_error_dialog").html('<?=filter_html_js_str(NDPHP_LANG_MOD_UNABLE_SEARCH, NDPHP_LANG_MOD_DEFAULT_CHARSET)?> <?=filter_html_js_str(NDPHP_LANG_MOD_ATTN_VALIDATE_VALUES, NDPHP_LANG_MOD_DEFAULT_CHARSET)?><br /><br /><span style="font-weight: bold"><?=filter_html_js_str(ucfirst(NDPHP_LANG_MOD_WORD_REASON), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>:</span> ' + xhr.responseText);
				jQuery("#ajax_error_dialog").dialog({ modal: true, title: '<?=filter_html_js_str(NDPHP_LANG_MOD_CANNOT_SEARCH, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>' });
				ndphp.ui.ready();
			}
		})
	});

	return false;
};

ndphp.form.cancel_create = function(e, ctrl, from_modal) {
	e.preventDefault();

	if (from_modal) {
		Modalbox.hide();
		return;
	}

	ndphp.ui.busy();

	jQuery("#body").nd_animate_hide(ndphp.animation.delay, function() {
		jQuery.ajax({
			type: "POST",
			url: "<?=filter_js_str(base_url(), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>index.php/" + ctrl + "/list_body_ajax",
			success: function(data) {
				var html = jQuery(data);
				ndphp.nav.back_store('body', jQuery('#body').html());
				jQuery("#body").html(html).nd_animate_show(ndphp.animation.delay, function() {
					ndphp.ui.ready();
				});
			},
			error: function(xhr, ajaxOptions, thrownError) {
				jQuery("#ajax_error_dialog").html('<?=filter_html_js_str(NDPHP_LANG_MOD_UNABLE_RETURN_PREV_VIEW, NDPHP_LANG_MOD_DEFAULT_CHARSET)?><br /><br /><span style="font-weight: bold"><?=filter_html_js_str(ucfirst(NGPHP_LANG_MOD_WORD_REASON), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>:</span> ' + xhr.responseText);
				jQuery("#ajax_error_dialog").dialog({ modal: true, title: '<?=filter_html_js_str(NDPHP_LANG_MOD_CANNOT_LOAD_PREV_VIEW, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>' });
				ndphp.ui.ready();
			}
		})
	});
};

ndphp.form.submit_create = function(e, ctrl, form_id, from_modal) {
	var password_verified = true;

	/* Perform form validation */
	if (!jQuery("#" + form_id).valid()) {
		jQuery("#ajax_error_dialog").html('<?=filter_html_js_str(NDPHP_LANG_MOD_ATTN_SUBMIT_REQUIRED_FIELDS, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>');
		jQuery("#ajax_error_dialog").dialog({ modal:true, title: '<?=filter_html_js_str(NDPHP_LANG_MOD_MISSING_REQUIRED_FIELDS, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>' });
		return;
	}

	/* Check if, in the case of a password field exists, it matches with password verification field */
	jQuery('[id^=password_verification]').each(function(i) {
		if (!password_verified)
			return;

		password_verified = false;

		if (jQuery(this).prev().val() != jQuery(this).val()) {
			jQuery("#ajax_error_dialog").html('<?=filter_html_js_str(NDPHP_LANG_MOD_FAILED_VERIFY_PASSWORD, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>');
			jQuery("#ajax_error_dialog").dialog({ modal: true, title: '<?=filter_html_js_str(NDPHP_LANG_MOD_CANNOT_VERIFY_PASSWORD, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>' });
			return;		
		}

		password_verified = true;
	});

	if (!password_verified)
		return;

	ndphp.ui.busy();

	var formData = new FormData(jQuery("#" + form_id)[0]);

	jQuery.ajax({
		type: "POST",
		url: "<?=filter_js_str(base_url(), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>index.php/" + ctrl + "/insert",
		data: formData, //jQuery("#" + form_id).serialize(),
		async: false,
		cache: false,
		contentType: false,
		processData: false,
		success: function(data_insert) {
			if (from_modal) {
				var elem_id;
				var relationship;

				Modalbox.hide();

				/* Check whether the element id is a single or multiple relationship */
				if (jQuery("#" + ctrl + "_id").length) {
					elem_id = ctrl + "_id";	/* Single relationship */
					relationship = 'single';
				} else {
					elem_id = ctrl;			/* Multiple relationship */
					relationship = 'multiple';
				}

				/* Update select box elements */
				jQuery.ajax({
					type: "POST",
					url: "<?=filter_js_str(base_url(), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>index.php/" + ndphp.origin_controller + "/rel_get_options/" + elem_id + "/" + relationship + "/" + data_insert,
					success: function(data_options) {
						var html = jQuery(data_options);
						ndphp.nav.back_store(elem_id, jQuery('#' + elem_id).html());
						jQuery("#" + elem_id).html(html);
						ndphp.ui.ready();
					},
					error: function(xhr, ajaxOptions, thrownError) {
						jQuery("#ajax_error_dialog").html('<?=filter_html_js_str(NDPHP_LANG_MOD_UNABLE_UPDATE_ITEM_SELECT, NDPHP_LANG_MOD_DEFAULT_CHARSET)?> <?=filter_html_js_str(NDPHP_LANG_MOD_ATTN_VIEW_RETRY_RELOAD, NDPHP_LANG_MOD_DEFAULT_CHARSET)?><br /><br /><span style="font-weight: bold"><?=filter_html_js_str(ucfirst(NDPHP_LANG_MOD_WORD_REASON), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>:</span> ' + xhr.responseText);
						jQuery("#ajax_error_dialog").dialog({ modal: true, title: '<?=filter_html_js_str(NDPHP_LANG_MOD_CANNOT_UPDATE_DATA, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>' });
						ndphp.ui.ready();
					}
				});
			} else {
				jQuery("#body").nd_animate_hide(ndphp.animation.delay, function() {
					jQuery.ajax({
						type: "POST",
						url: "<?=filter_js_str(base_url(), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>index.php/" + ctrl + "/view_body_ajax/" + data_insert,
						success: function(data_view) {
							var html = jQuery(data_view);
							ndphp.nav.back_store('body', jQuery('#body').html());
							jQuery("#body").html(html).nd_animate_show(ndphp.animation.delay, function() {
								ndphp.ui.ready();
							});
						},
						error: function(xhr, ajaxOptions, thrownError) {
							jQuery("#ajax_error_dialog").html('<?=filter_html_js_str(NDPHP_LANG_MOD_UNABLE_LOAD_VIEW_ITEM_NEW, NDPHP_LANG_MOD_DEFAULT_CHARSET)?> <?=filter_html_js_str(NDPHP_LAND_MOD_NOTE_ITEM_INSERTED, NDPHP_LANG_MOD_DEFAULT_CHARSET)?> <?=filter_html_js_str(NDPHP_LANG_MOD_ATTN_VIEW_RETRY_RELOAD, NDPHP_LANG_MOD_DEFAULT_CHARSET)?><br /><br /><span style="font-weight: bold"><?=filter_html_js_str(ucfirst(NDPHP_LANG_MOD_WORD_REASON), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>:</span> ' + xhr.responseText);
							jQuery("#ajax_error_dialog").dialog({ modal: true, title: '<?=filter_html_js_str(NDPHP_LANG_MOD_CANNOT_LOAD_VIEW, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>' });
							ndphp.ui.ready();
						}
					})
				});
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			if (from_modal)
				Modalbox.hide();

			jQuery("#ajax_error_dialog").html('<?=filter_html_js_str(NDPHP_LANG_MOD_UNABLE_SUBMIT_REQUEST, NDPHP_LANG_MOD_DEFAULT_CHARSET)?> <?=filter_html_js_str(NDPHP_LANG_MOD_ATTN_VALIDATE_VALUES, NDPHP_LANG_MOD_DEFAULT_CHARSET)?><br /><br /><span style="font-weight: bold"><?=filter_html_js_str(ucfirst(NDPHP_LANG_MOD_WORD_REASON), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>:</span> ' + xhr.responseText);
			jQuery("#ajax_error_dialog").dialog({ modal: true, title: '<?=filter_html_js_str(NDPHP_LANG_MOD_CANNOT_SUBMIT_REQUEST, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>' });
			ndphp.ui.ready();
		}
	});
};

ndphp.form.cancel_edit = function(e, ctrl, from_modal, id) {
	e.preventDefault();

	if (from_modal) {
		Modalbox.hide();
		return;
	}

	ndphp.ui.busy();

	jQuery("#body").nd_animate_hide(ndphp.animation.delay, function() {
		jQuery.ajax({
			type: "POST",
			url: "<?=filter_js_str(base_url(), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>index.php/" + ctrl + "/view_body_ajax/" + id,
			success: function(data) {
				var html = jQuery(data);
				ndphp.nav.back_store('body', jQuery('#body').html());
				jQuery("#body").html(html).nd_animate_show(ndphp.animation.delay, function() {
					ndphp.ui.ready();
				});
			},
			error: function(xhr, ajaxOptions, thrownError) {
				jQuery("#ajax_error_dialog").html('<?=filter_html_js_str(NDPHP_LANG_MOD_UNABLE_RETURN_PREV_VIEW, NDPHP_LANG_MOD_DEFAULT_CHARSET)?><br /><br /><span style="font-weight: bold"><?=filter_html_js_str(ucfirst(NDPHP_LANG_MOD_WORD_REASON), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>:</span> ' + xhr.responseText);
				jQuery("#ajax_error_dialog").dialog({ modal: true, title: '<?=filter_html_js_str(NDPHP_LANG_MOD_CANNOT_LOAD_PREV_VIEW, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>' });
				ndphp.ui.ready();
			}
		})
	});
};

ndphp.form.submit_edit = function(e, ctrl, form_id, from_modal, id) {
	var password_verified = true;

	/* Perform form validation */
	if (!jQuery("#" + form_id).valid()) {
		jQuery("#ajax_error_dialog").html('<?=filter_html_js_str(NDPHP_LANG_MOD_ATTN_SUBMIT_REQUIRED_FIELDS, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>');
		jQuery("#ajax_error_dialog").dialog({ modal: true, title: '<?=filter_html_js_str(NDPHP_LANG_MOD_MISSING_REQUIRED_FIELDS, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>' });
		return;
	}

	/* Check if, in the case of a password field exists, it matches with password verification field */
	jQuery('[id^=password_verification]').each(function(i) {
		if (!password_verified)
			return;

		password_verified = false;

		if (jQuery(this).prev().val() != jQuery(this).val()) {
			jQuery("#ajax_error_dialog").html('<?=filter_html_js_str(NDPHP_LANG_MOD_FAILED_VERIFY_PASSWORD, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>');
			jQuery("#ajax_error_dialog").dialog({ modal: true, title: '<?=filter_html_js_str(NDPHP_LANG_MOD_CANNOT_VERIFY_PASSWORD, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>' });
			return;			
		}

		password_verified = true;
	});

	if (!password_verified)
		return;

	ndphp.ui.busy();

	var formData = new FormData(jQuery("#" + form_id)[0]);

	jQuery.ajax({
		type: "POST",
		url: "<?=filter_js_str(base_url(), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>index.php/" + ctrl + "/update",
		data: formData, //jQuery("#" + form_id).serialize(),
		async: false,
		cache: false,
		contentType: false,
		processData: false,
		success: function(data) {
			if (from_modal) {
				Modalbox.hide();

				if ((typeof ndphp.ajax.update_data_list == 'function') && (ndphp.last_listing_op == 'list')) {
					ndphp.ajax.update_data_list();
				} else if ((typeof ndphp.ajax.update_data_result == 'function') && (ndphp.last_listing_op == 'result')) {
					ndphp.ajax.update_data_result();
				}

				ndphp.ui.ready();

				return;
			}

			jQuery("#body").nd_animate_hide(ndphp.animation.delay, function() {
				jQuery.ajax({
					type: "POST",
					url: "<?=filter_js_str(base_url(), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>index.php/" + ctrl + "/view_body_ajax/" + id,
					success: function(data) {
						var html = jQuery(data);
						ndphp.nav.back_store('body', jQuery('#body').html());
						jQuery("#body").html(html).nd_animate_show(ndphp.animation.delay, function() {
							ndphp.ui.ready();
						});
					},
					error: function(xhr, ajaxOptions, thrownError) {
						jQuery("#ajax_error_dialog").html('<?=filter_html_js_str(NDPHP_LANG_MOD_UNABLE_LOAD_VIEW_ITEM_NEW, NDPHP_LANG_MOD_DEFAULT_CHARSET)?> <?=filter_html_js_str(NDPHP_LAND_MOD_NOTE_ITEM_INSERTED, NDPHP_LANG_MOD_DEFAULT_CHARSET)?> <?=filter_html_js_str(NDPHP_LANG_MOD_ATTN_VIEW_RETRY_RELOAD, NDPHP_LANG_MOD_DEFAULT_CHARSET)?><br /><br /><span style="font-weight: bold"><?=filter_html_js_str(ucfirst(NDPHP_LANG_MOD_WORD_REASON), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>:</span> ' + xhr.responseText);
						jQuery("#ajax_error_dialog").dialog({ modal: true, title: '<?=filter_html_js_str(NDPHP_LANG_MOD_CANNOT_LOAD_VIEW, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>' });
						ndphp.ui.ready();
					}
				})
			});
		},
		error: function(xhr, ajaxOptions, thrownError) {
			if (from_modal)
				Modalbox.hide();

			jQuery("#ajax_error_dialog").html('<?=filter_html_js_str(NDPHP_LANG_MOD_UNABLE_SUBMIT_REQUEST, NDPHP_LANG_MOD_DEFAULT_CHARSET)?> <?=filter_html_js_str(NDPHP_LANG_MOD_ATTN_VALIDATE_VALUES, NDPHP_LANG_MOD_DEFAULT_CHARSET)?><br /><br /><span style="font-weight: bold"><?=filter_html_js_str(ucfirst(NDPHP_LANG_MOD_WORD_REASON), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>:</span> ' + xhr.responseText);
			jQuery("#ajax_error_dialog").dialog({ modal: true, title: '<?=filter_html_js_str(NDPHP_LANG_MOD_CANNOT_SUBMIT_REQUEST, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>' });
			ndphp.ui.ready();
		}
	});
};

ndphp.form.cancel_remove = function(e, ctrl, from_modal, id) {
	e.preventDefault();

	if (from_modal) {
		Modalbox.hide();
		return;
	}

	ndphp.ui.busy();

	jQuery("#body").nd_animate_hide(ndphp.animation.delay, function() {
		jQuery.ajax({
			type: "POST",
			url: "<?=filter_js_str(base_url(), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>index.php/" + ctrl + "/view_body_ajax/" + id,
			success: function(data) {
				var html = jQuery(data);
				ndphp.nav.back_store('body', jQuery('#body').html());
				jQuery("#body").html(html).nd_animate_show(ndphp.animation.delay, function() {
					ndphp.ui.ready();
				});
			},
			error: function(xhr, ajaxOptions, thrownError) {
				jQuery("#ajax_error_dialog").html('<?=filter_html_js_str(NDPHP_LANG_MOD_UNABLE_RETURN_PREV_VIEW, NDPHP_LANG_MOD_DEFAULT_CHARSET)?><br /><br /><span style="font-weight: bold"><?=filter_html_js_str(ucfirst(NDPHP_LANG_MOD_WORD_REASON), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>:</span> ' + xhr.responseText);
				jQuery("#ajax_error_dialog").dialog({ modal: true, title: '<?=filter_html_js_str(NDPHP_LANG_MOD_CANNOT_LOAD_PREV_VIEW, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>' });
				ndphp.ui.ready();
			}
		})
	});
};

ndphp.form.submit_remove = function(e, ctrl, form_id, from_modal, id) {
	e.preventDefault();

	ndphp.ui.busy();

	jQuery.ajax({
		type: "POST",
		url: "<?=filter_js_str(base_url(), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>index.php/" + ctrl + "/delete",
		data: jQuery("#" + form_id).serialize(),
		success: function(data) {
			if (from_modal) {
				Modalbox.hide();
				
				if ((typeof ndphp.ajax.update_data_list == 'function') && (ndphp.last_listing_op == 'list')) {
					ndphp.ajax.update_data_list();
				} else if ((typeof ndphp.ajax.update_data_result == 'function') && (ndphp.last_listing_op == 'result')) {
					ndphp.ajax.update_data_result();
				}

				ndphp.ui.ready();

				return;
			}

			jQuery("#body").nd_animate_hide(ndphp.animation.delay, function() {
				jQuery.ajax({
					type: "POST",
					url: "<?=filter_js_str(base_url(), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>index.php/" + ctrl + "/list_body_ajax",
					success: function(data) {
						var html = jQuery(data);
						ndphp.nav.back_store('body', jQuery('#body').html());
						jQuery("#body").html(html).nd_animate_show(ndphp.animation.delay, function() {
							ndphp.ui.ready();
						});
					},
					error: function(xhr, ajaxOptions, thrownError) {
						jQuery("#ajax_error_dialog").html('<?=filter_html_js_str(NDPHP_LANG_MOD_UNABLE_LOAD_VIEW_LIST, NDPHP_LANG_MOD_DEFAULT_CHARSET)?> <?=filter_html_js_str(NDPHP_LANG_MOD_ATTN_VIEW_RETRY_RELOAD, NDPHP_LANG_MOD_DEFAULT_CHARSET)?><br /><br /><span style="font-weight: bold"><?=filter_html_js_str(ucfirst(NDPHP_LANG_MOD_WORD_REASON), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>:</span> ' + xhr.responseText);
						jQuery("#ajax_error_dialog").dialog({ modal: true, title: '<?=filter_html_js_str(NDPHP_LANG_MOD_CANNOT_LOAD_VIEW, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>' });
						ndphp.ui.ready();
					}
				})
			});
		},
		error: function(xhr, ajaxOptions, thrownError) {
			if (from_modal)
				Modalbox.hide();

			jQuery("#ajax_error_dialog").html('<?=filter_html_js_str(NDPHP_LANG_MOD_UNABLE_SUBMIT_REQUEST, NDPHP_LANG_MOD_DEFAULT_CHARSET)?> <?=filter_html_js_str(NDPHP_LANG_MOD_ATTN_TRY_AGAIN, NDPHP_LANG_MOD_DEFAULT_CHARSET)?><br /><br /><span style="font-weight: bold"><?=filter_html_js_str(ucfirst(NDPHP_LANG_MOD_WORD_REASON), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>:</span> ' + xhr.responseText);
			jQuery("#ajax_error_dialog").dialog({ modal: true, title: '<?=filter_html_js_str(NDPHP_LANG_MOD_CANNOT_SUBMIT_REQUEST, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>' });

			ndphp.ui.ready();
		}
	});
};

ndphp.form.submit_import_csv = function(e, ctrl, form_id, from_modal) {
	e.preventDefault();

	ndphp.ui.busy();

	var formData = new FormData(jQuery("#" + form_id)[0]);

	jQuery.ajax({
		type: "POST",
		url:  "<?=filter_js_str(base_url(), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>index.php/" + ctrl + "/import/csv",
		data: formData,
		async: false,
		cache: false,
		contentType: false,
		processData: false,
		success: function(data) {
			if (from_modal)
				Modalbox.hide();

			ndphp.ui.ready();
		},
		error: function(xhr, ajaxOptions, thrownError) {
			if (from_modal)
				Modalbox.hide();

			jQuery("#ajax_error_dialog").html('<?=filter_html_js_str(NDPHP_LANG_MOD_UNABLE_SUBMIT_REQUEST, NDPHP_LANG_MOD_DEFAULT_CHARSET)?> <?=filter_html_js_str(NDPHP_LANG_MOD_ATTN_VALIDATE_VALUES, NDPHP_LANG_MOD_DEFAULT_CHARSET)?><br /><br /><span style="font-weight: bold"><?=filter_html_js_str(ucfirst(NDPHP_LANG_MOD_WORD_REASON), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>:</span> ' + xhr.responseText);
			jQuery("#ajax_error_dialog").dialog({ modal: true, title: '<?=filter_html_js_str(NDPHP_LANG_MOD_CANNOT_SUBMIT_REQUEST, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>' });

			ndphp.ui.ready();
		}
	});
};

ndphp.form.cancel_import_csv = function(e, ctrl, from_modal) {
	e.preventDefault();

	if (from_modal)
		Modalbox.hide();
};

ndphp.form.submit_search_save = function(e, ctrl, form_id, from_modal) {
	e.preventDefault();

	ndphp.ui.busy();

	var formData = new FormData(jQuery("#" + form_id)[0]);

	jQuery.ajax({
		type: "POST",
		url:  "<?=filter_js_str(base_url(), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>index.php/" + ctrl + "/search_save_insert",
		data: formData,
		async: false,
		cache: false,
		contentType: false,
		processData: false,
		success: function(data) {
			if (from_modal)
				Modalbox.hide();

			ndphp.ui.ready();
		},
		error: function(xhr, ajaxOptions, thrownError) {
			if (from_modal)
				Modalbox.hide();

			jQuery("#ajax_error_dialog").html('<?=filter_html_js_str(NDPHP_LANG_MOD_UNABLE_SUBMIT_REQUEST, NDPHP_LANG_MOD_DEFAULT_CHARSET)?> <?=filter_html_js_str(NDPHP_LANG_MOD_ATTN_VALIDATE_VALUES, NDPHP_LANG_MOD_DEFAULT_CHARSET)?><br /><br /><span style="font-weight: bold"><?=filter_html_js_str(ucfirst(NDPHP_LANG_MOD_WORD_REASON), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>:</span> ' + xhr.responseText);
			jQuery("#ajax_error_dialog").dialog({ modal: true, title: '<?=filter_html_js_str(NDPHP_LANG_MOD_CANNOT_SUBMIT_REQUEST, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>' });

			ndphp.ui.ready();
		}
	});
};

ndphp.form.cancel_search_save = function(e, ctrl, from_modal) {
	e.preventDefault();

	if (from_modal)
		Modalbox.hide();
};

ndphp.form.remove_search_save = function(e, ctrl, search_saved_id) {
	e.preventDefault();

	ndphp.ui.busy();

	jQuery.ajax({
		type: "GET",
		url:  "<?=filter_js_str(base_url(), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>index.php/" + ctrl + "/search_save_delete/" + search_saved_id,
		success: function(data) {
			ndphp.ui.ready();
			jQuery('#search_saved_' + search_saved_id).remove();
		},
		error: function(xhr, ajaxOptions, thrownError) {
			jQuery("#ajax_error_dialog").html('<?=filter_html_js_str(NDPHP_LANG_MOD_UNABLE_SUBMIT_REQUEST, NDPHP_LANG_MOD_DEFAULT_CHARSET)?> <?=filter_html_js_str(NDPHP_LANG_MOD_ATTN_VALIDATE_VALUES, NDPHP_LANG_MOD_DEFAULT_CHARSET)?><br /><br /><span style="font-weight: bold"><?=filter_html_js_str(ucfirst(NDPHP_LANG_MOD_WORD_REASON), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>:</span> ' + xhr.responseText);
			jQuery("#ajax_error_dialog").dialog({ modal: true, title: '<?=filter_html_js_str(NDPHP_LANG_MOD_CANNOT_SUBMIT_REQUEST, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>' });

			ndphp.ui.ready();
		}
	});
}

ndphp.form.cancel_adv_search = function(e, ctrl) {
	e.preventDefault();

	jQuery("#body").nd_animate_hide(ndphp.animation.delay, function() {
		if (ndphp.grouping.enabled) {
			ndphp.ajax.url = "<?=filter_js_str(base_url(), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>index.php/" + ctrl + "/list_group_body_ajax/" + ndphp.grouping.field;
		} else {
			ndphp.ajax.url = "<?=filter_js_str(base_url(), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>index.php/" + ctrl + "/list_body_ajax";
		}

		jQuery.ajax({
			type: "POST",
			url: ndphp.ajax.url,
			success: function(data) {
				var html = jQuery(data);
				ndphp.nav.back_store('body', jQuery('#body').html());
				jQuery("#body").html(html).nd_animate_show(ndphp.animation.delay);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				jQuery("#ajax_error_dialog").html('<?=filter_html_js_str(NDPHP_LANG_MOD_UNABLE_RETURN_PREV_VIEW, NDPHP_LANG_MOD_DEFAULT_CHARSET)?><br /><br /><span style="font-weight: bold"><?=filter_html_js_str(ucfirst(NDPHP_LANG_MOD_WORD_REASON), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>:</span> ' + xhr.responseText);
				jQuery("#ajax_error_dialog").dialog({ modal: true, title: '<?=filter_html_js_str(NDPHP_LANG_MOD_CANNOT_LOAD_PREV_VIEW, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>' });
			}
		});

		ndphp.ajax.url = '';
	});
}

ndphp.form.submit_adv_search = function(e, ctrl) {
	e.preventDefault();

	if (ndphp.grouping.enabled) {
		ndphp.ajax.url = "<?=filter_js_str(base_url(), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>index.php/" + ctrl + "/result_group_body_ajax/" + ndphp.grouping.field;
	} else {
		ndphp.ajax.url = "<?=filter_js_str(base_url(), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>index.php/" + ctrl + "/result_body_ajax";
	}

	jQuery.ajax({
		type: "POST",
		url: ndphp.ajax.url,
		data: jQuery("#advsearchform").serialize(),
		success: function(data) {
			jQuery("#body").nd_animate_hide(ndphp.animation.delay, function() {
				var html = jQuery(data);
				ndphp.nav.back_store('body', jQuery('#body').html());
				jQuery("#body").html(html).nd_animate_show(ndphp.animation.delay);
			});
		},
		error: function(xhr, ajaxOptions, thrownError) {
			jQuery("#ajax_error_dialog").html('<?=filter_html_js_str(NDPHP_LANG_MOD_UNABLE_SEARCH, NDPHP_LANG_MOD_DEFAULT_CHARSET)?> <?=filter_html_js_str(NDPHP_LANG_MOD_ATTN_VALIDATE_VALUES, NDPHP_LANG_MOD_DEFAULT_CHARSET)?><br /><br /><span style="font-weight: bold"><?=filter_html_js_str(ucfirst(NDPHP_LANG_MOD_WORD_REASON), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>:</span> ' + xhr.responseText);
			jQuery("#ajax_error_dialog").dialog({ modal: true, title: '<?=filter_html_js_str(NDPHP_LANG_MOD_CANNOT_SEARCH, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>' });
		}
	});

	ndphp.ajax.url = '';
}

ndphp.form.submit_register = function(e, ctrl, form_id) {
	e.preventDefault();

	if (!jQuery("#" + form_id).valid())
		return;

	jQuery.ajax({
		type: "POST",
		url: "<?=filter_js_str(base_url(), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>index.php/" + ctrl + "/newuser/1",
		data: jQuery("#" + form_id).serialize(),
		success: function(data) {
			var html = jQuery(data);
			jQuery("#register").html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			jQuery("#ajax_error_dialog").html(xhr.responseText);
			jQuery("#ajax_error_dialog").dialog({ modal: true, title: "Review the following issues" });
			Recaptcha.reload();
		}
	});
}

ndphp.form.submit_token = function(base_url, ctrl, form_id) {
	if (!jQuery("#" + form_id).valid())
		return;

	jQuery.ajax({
		type: "POST",
		url: base_url + "index.php/" + ctrl + "/confirm_sms_token/",
		data: jQuery("#" + form_id).serialize(),
		success: function(data) {
			jQuery("#register").html(data);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			jQuery("#ajax_error_dialog").html(xhr.responseText);
			jQuery("#ajax_error_dialog").dialog({ modal: true, title: "Review the following issues" });
		}
	});
}

ndphp.form.subscription = {};

ndphp.form.subscription_upgrade_submit = function() {
	jQuery('#subscription_types_upgrade_form').submit();
};

/* AJAX Load - Partial Views */
ndphp.ajax = {};

ndphp.ajax.load_data_ordered_list = function(e, ctrl, field, order, page) {
	e.preventDefault();

	ndphp.ui.busy();

	jQuery.ajax({
		type: "POST",
		url: "<?=filter_js_str(base_url(), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>index.php/" + ctrl + "/list_data_ajax/" + field + "/" + order + "/" + page,
		success: function(data) {
			var html = jQuery(data);
			ndphp.nav.back_store('list', jQuery('#list').html());
			jQuery("#list").nd_animate_ordering_hide(ndphp.animation.ordering_delay, function() {
				jQuery("#list").replaceWith(function() {
					return jQuery(html).nd_animate_ordering_show(ndphp.animation.ordering_delay, function() {
						ndphp.ui.ready();
					});
				});
				/* NOTE: For some reason, jquery 1.8.3 is loosing the display
				 * element of the div style. We need to force it while the div
				 * is loading in order to be correctly rendered.
				 */
				jQuery('#list').css({"display":"table"});
				ndphp.ui.ready();
			});
		},
		error: function(xhr, ajaxOptions, thrownError) {
			jQuery("#ajax_error_dialog").html('<?=filter_html_js_str(NDPHP_LANG_MOD_UNABLE_LOAD_VIEW_LIST_ORDER, NDPHP_LANG_MOD_DEFAULT_CHARSET)?><br /><br /><span style="font-weight: bold"><?=filter_html_js_str(ucfirst(NDPHP_LANG_MOD_WORD_REASON), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>:</span> ' + xhr.responseText);
			jQuery("#ajax_error_dialog").dialog({ modal: true, title: '<?=filter_html_js_str(NDPHP_LANG_MOD_CANNOT_LOAD_VIEW, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>' });
			ndphp.ui.ready();
		}
	});
};

ndphp.ajax.load_group_data_ordered_list = function(e, ctrl, grouping_field, field, order, page) {
	e.preventDefault();

	ndphp.ui.busy();

	jQuery.ajax({
		type: "POST",
		url: "<?=filter_js_str(base_url(), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>index.php/" + ctrl + "/list_group_data_ajax/" + grouping_field + "/" + field + "/" + order + "/" + page,
		success: function(data) {
			var html = jQuery(data);
			ndphp.nav.back_store('list', jQuery('#list').html());
			jQuery("#list").nd_animate_ordering_hide(ndphp.animation.ordering_delay, function() {
				jQuery("#list").replaceWith(function() {
					return jQuery(html).nd_animate_ordering_show(ndphp.animation.ordering_delay, function() {
						ndphp.ui.ready();
					});
				});
				/* NOTE: For some reason, jquery 1.8.3 is loosing the display
				 * element of the div style. We need to force it while the div
				 * is loading in order to be correctly rendered.
				 */
				jQuery('#list').css({"display":"table"});
				ndphp.ui.ready();
			});
		},
		error: function(xhr, ajaxOptions, thrownError) {
			jQuery("#ajax_error_dialog").html('<?=filter_html_js_str(NDPHP_LANG_MOD_UNABLE_LOAD_VIEW_LIST_ORDER, NDPHP_LANG_MOD_DEFAULT_CHARSET)?><br /><br /><span style="font-weight: bold"><?=filter_html_js_str(ucfirst(NDPHP_LANG_MOD_WORD_REASON), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>:</span> ' + xhr.responseText);
			jQuery("#ajax_error_dialog").dialog({ modal: true, title: '<?=filter_html_js_str(NDPHP_LANG_MOD_CANNOT_LOAD_VIEW, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>' });
			ndphp.ui.ready();
		}
	});
};

ndphp.ajax.load_data_ordered_result = function(e, ctrl, result_query, field, order, page) {
	e.preventDefault();

	ndphp.ui.busy();

	jQuery.ajax({
		type: "POST",
		url: "<?=filter_js_str(base_url(), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>index.php/" + ctrl + "/result_data_ajax/query/" + result_query + "/" + field + "/" + order + "/" + page,
		success: function(data) {
			var html = jQuery(data);
			ndphp.nav.back_store('result', jQuery('#result').html());
			jQuery("#result").nd_animate_ordering_hide(ndphp.animation.ordering_delay, function() {
				jQuery("#result").replaceWith(function() {
					return jQuery(html).nd_animate_ordering_show(ndphp.animation.ordering_delay, function() {
						ndphp.ui.ready();	
					});
				});
				/* NOTE: For some reason, jquery 1.8.3 is loosing the display
				 * element of the div style. We need to force it while the div
				 * is loading in order to be correctly rendered.
				 */
				jQuery('#result').css({"display":"table"});
				ndphp.ui.ready();
			});
		},
		error: function(xhr, ajaxOptions, thrownError) {
			jQuery("#ajax_error_dialog").html('<?=filter_html_js_str(NDPHP_LANG_MOD_UNABLE_LOAD_VIEW_LIST_ORDER, NDPHP_LANG_MOD_DEFAULT_CHARSET)?><br /><br /><span style="font-weight: bold"><?=filter_html_js_str(ucfirst(NDPHP_LANG_MOD_WORD_REASON), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>:</span> ' + xhr.responseText);
			jQuery("#ajax_error_dialog").dialog({ modal: true, title: '<?=filter_html_js_str(NDPHP_LANG_MOD_CANNOT_LOAD_VIEW, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>' });
			ndphp.ui.ready();
		}
	});
};

ndphp.ajax.load_group_data_ordered_result = function(e, ctrl, grouping_field, result_query, field, order, page) {
	e.preventDefault();

	ndphp.ui.busy();

	jQuery.ajax({
		type: "POST",
		url: "<?=filter_js_str(base_url(), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>index.php/" + ctrl + "/result_group_data_ajax/" + grouping_field + "/query/" + result_query + "/" + field + "/" + order + "/" + page,
		success: function(data) {
			var html = jQuery(data);
			ndphp.nav.back_store('result', jQuery('#result').html());
			jQuery("#result").nd_animate_ordering_hide(ndphp.animation.ordering_delay, function() {
				jQuery("#result").replaceWith(function() {
					return jQuery(html).nd_animate_ordering_show(ndphp.animation.ordering_delay, function() {
						ndphp.ui.ready();	
					});
				});
				/* NOTE: For some reason, jquery 1.8.3 is loosing the display
				 * element of the div style. We need to force it while the div
				 * is loading in order to be correctly rendered.
				 */
				jQuery('#result').css({"display":"table"});
				ndphp.ui.ready();
			});
		},
		error: function(xhr, ajaxOptions, thrownError) {
			jQuery("#ajax_error_dialog").html('<?=filter_html_js_str(NDPHP_LANG_MOD_UNABLE_LOAD_VIEW_LIST_ORDER, NDPHP_LANG_MOD_DEFAULT_CHARSET)?><br /><br /><span style="font-weight: bold"><?=filter_html_js_str(ucfirst(NDPHP_LANG_MOD_WORD_REASON), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>:</span> ' + xhr.responseText);
			jQuery("#ajax_error_dialog").dialog({ modal: true, title: '<?=filter_html_js_str(NDPHP_LANG_MOD_CANNOT_LOAD_VIEW, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>' });
			ndphp.ui.ready();
		}
	});
};

ndphp.ajax.load_body_search_saved_result_query_uri = function(e, ctrl, title, result_query_uri) {
	e.preventDefault();

	ndphp.ui.busy();

	jQuery.ajax({
		type: "GET",
		url: '<?=filter_js_str(base_url(), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>index.php/' + ctrl + '/result_body_ajax/query/' + result_query_uri,
		success: function(data) {
			jQuery("#body").nd_animate_hide(ndphp.animation.delay, function() {
				var html = jQuery(data);
				ndphp.nav.back_store('body', jQuery('#body').html());
				jQuery("#body").html(html).nd_animate_show(ndphp.animation.delay);
				jQuery("h1.crud_header").append(' - ' + title);
			});
			ndphp.ui.ready();
		},
		error: function(xhr, ajaxOptions, thrownError) {
			jQuery("#ajax_error_dialog").html('<?=filter_html_js_str(NDPHP_LANG_MOD_UNABLE_SEARCH, NDPHP_LANG_MOD_DEFAULT_CHARSET)?><br /><br /><span style="font-weight: bold">Reason:</span> ' + xhr.responseText);
			jQuery("#ajax_error_dialog").dialog({ modal: true, title: '<?=filter_html_js_str(NDPHP_LANG_MOD_CANNOT_SEARCH, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>' });
			ndphp.ui.ready();
		}
	});
};

ndphp.ajax.load_body_edit_frommodal = function(e, ctrl, id) {
	e.preventDefault();

	Modalbox.hide();

	ndphp.ui.busy();

	jQuery("#body").nd_animate_hide(ndphp.animation.delay, function() {
		jQuery.ajax({
			type: "POST",
			url: "<?=filter_js_str(base_url(), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>index.php/" + ctrl + "/edit_body_ajax/" + id,
			success: function(data) {
				var html = jQuery(data);
				ndphp.nav.back_store('body', jQuery('#body').html());
				jQuery("#body").html(html).nd_animate_show(ndphp.animation.delay, function() {
					ndphp.ui.ready();
				});
			},
			error: function(xhr, ajaxOptions, thrownError) {
				jQuery("#ajax_error_dialog").html('<?=filter_html_js_str(NDPHP_LANG_MOD_UNABLE_EDIT_ITEM_SELECT, NDPHP_LANG_MOD_DEFAULT_CHARSET)?> <?=filter_html_js_str(NDPHP_LANG_MOD_ATTN_PERM_CHECK, NDPHP_LANG_MOD_DEFAULT_CHARSET)?><br /><br /><span style="font-weight: bold"><?=filter_html_js_str(ucfirst(NDPHP_LANG_MOD_WORD_REASON), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>:</span> ' + xhr.responseText);
				jQuery("#ajax_error_dialog").dialog({ modal: true, title: '<?=filter_html_js_str(NDPHP_LANG_MOD_CANNOT_EDIT_ITEM, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>' });
				ndphp.ui.ready();
			}
		})
	});
};

ndphp.ajax.load_body_view_frommodal = function(e, table, id) {
	Modalbox.hide();
	/* the ndphp.ajax.load_body_view_rel() function is called here instead of ndphp.ajax.load_body_view() because we cannot know
	 * from where this function is actually being called. It may either being called from within the same controller, or from a foreign
	 * controller (relationship), thus the *_rel() function set will correctly answer to both requests.
	 */
	ndphp.ajax.load_body_view_rel(e, table, id);
};
		
ndphp.ajax.load_body_view = function(e, ctrl, id) {
	e.preventDefault();

	ndphp.ui.busy();

	jQuery("#body").nd_animate_hide(ndphp.animation.delay, function() {
		jQuery.ajax({
			type: "POST",
			url: "<?=filter_js_str(base_url(), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>index.php/" + ctrl + "/view_body_ajax/" + id,
			success: function(data) {
				var html = jQuery(data);
				ndphp.nav.back_store('body', jQuery('#body').html());
				jQuery("#body").html(html).nd_animate_show(ndphp.animation.delay, function() {
					ndphp.ui.ready();					
				});
			},
			error: function(xhr, ajaxOptions, thrownError) {
				jQuery("#ajax_error_dialog").html('<?=filter_html_js_str(NDPHP_LANG_MOD_UNABLE_VIEW_ITEM_SELECT, NDPHP_LANG_MOD_DEFAULT_CHARSET)?> <?=filter_html_js_str(NDPHP_LANG_MOD_ATTN_PERM_CHECK, NDPHP_LANG_MOD_DEFAULT_CHARSET)?><br /><br /><span style="font-weight: bold"><?=filter_html_js_str(ucfirst(NDPHP_LANG_MOD_WORD_REASON), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>:</span> ' + xhr.responseText);
				jQuery("#ajax_error_dialog").dialog({ modal: true, title: '<?=filter_html_js_str(NDPHP_LANG_MOD_CANNOT_LOAD_VIEW, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>' });
				ndphp.ui.ready();
			}
		})
	});
};

ndphp.ajax.load_body_view_rel = function(e, table, id) {
	e.preventDefault();

	/* Check if there's an active modalbox... we may be invoked by ndphp.ajax.load_body_view_frommodal() */
	if (typeof Modalbox != "undefined" && Modalbox.Methods.active) /* FIXME: Still bugged */
		Modalbox.hide();
	
	ndphp.ui.busy();

	jQuery("#body").nd_animate_hide(ndphp.animation.delay, function() {
		jQuery.ajax({
			type: "POST",
			url: "<?=filter_js_str(base_url(), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>index.php/" + table + "/view_body_ajax/" + id,
			success: function(data) {
				var html = jQuery(data);
				ndphp.nav.back_store('body', jQuery('#body').html());
				jQuery("#body").html(html).nd_animate_show(ndphp.animation.delay, function() {
					ndphp.ui.ready();
				});
			},
			error: function(xhr, ajaxOptions, thrownError) {
				jQuery("#ajax_error_dialog").html('<?=filter_html_js_str(NDPHP_LANG_MOD_UNABLE_VIEW_ITEM_SELECT, NDPHP_LANG_MOD_DEFAULT_CHARSET)?> <?=filter_html_js_str(NDPHP_LANG_MOD_ATTN_PERM_CHECK, NDPHP_LANG_MOD_DEFAULT_CHARSET)?><br /><br /><span style="font-weight: bold"><?=filter_html_js_str(ucfirst(NDPHP_LANG_MOD_WORD_REASON), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>:</span> ' + xhr.responseText);
				jQuery("#ajax_error_dialog").dialog({ modal: true, title: '<?=filter_html_js_str(NDPHP_LANG_MOD_CANNOT_LOAD_VIEW, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>' });
				ndphp.ui.ready();
			}
		})
	});
};

ndphp.ajax.load_body_op = function(e, ctrl, op) {
	e.preventDefault();

	ndphp.ui.busy();

	jQuery("#body").nd_animate_hide(ndphp.animation.delay, function() {
		jQuery.ajax({
			type: "POST",
			url: "<?=filter_js_str(base_url(), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>index.php/" + ctrl + "/" + op + "_body_ajax",
			success: function(data) {
				var html = jQuery(data);
				ndphp.nav.back_store('body', jQuery('#body').html());
				jQuery("#body").html(html).nd_animate_show(ndphp.animation.delay, function() {
					ndphp.ui.ready();
				});
			},
			error: function(xhr, ajaxOptions, thrownError) {
				jQuery("#ajax_error_dialog").html('<?=filter_html_js_str(NDPHP_LANG_MOD_UNABLE_OPERATION, NDPHP_LANG_MOD_DEFAULT_CHARSET)?><br /><br /><span style="font-weight: bold"><?=filter_html_js_str(ucfirst(NDPHP_LANG_MOD_WORD_REASON), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>:</span> ' + xhr.responseText);
				jQuery("#ajax_error_dialog").dialog({ modal: true, title: '<?=filter_html_js_str(NDPHP_LANG_MOD_CANNOT_OPERATION, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>' });
				ndphp.ui.ready();
			}
		})
	});
};

ndphp.ajax.load_body_op_id = function(e, ctrl, op, id) {
	e.preventDefault();

	ndphp.ui.busy();

	jQuery("#body").nd_animate_hide(ndphp.animation.delay, function() {
		jQuery.ajax({
			type: "POST",
			url: "<?=filter_js_str(base_url(), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>index.php/" + ctrl + "/" + op + "_body_ajax/" + id,
			success: function(data) {
				var html = jQuery(data);
				ndphp.nav.back_store('body', jQuery('#body').html());
				jQuery("#body").html(html).nd_animate_show(ndphp.animation.delay, function() {
					ndphp.ui.ready();
				});
			},
			error: function(xhr, ajaxOptions, thrownError) {
				jQuery("#ajax_error_dialog").html('<?=filter_html_js_str(NDPHP_LANG_MOD_UNABLE_OPERATION, NDPHP_LANG_MOD_DEFAULT_CHARSET)?><br /><br /><span style="font-weight: bold"><?=filter_html_js_str(ucfirst(NDPHP_LANG_MOD_WORD_REASON), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>:</span> ' + xhr.responseText);
				jQuery("#ajax_error_dialog").dialog({ modal: true, title: '<?=filter_html_js_str(NDPHP_LANG_MOD_CANNOT_OPERATION, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>' });
				ndphp.ui.ready();
			}
		})
	});
};

ndphp.ajax.load_body_group = function(e, ctrl, grouping_field) {
	e.preventDefault();

	ndphp.ui.busy();

	jQuery("#body").nd_animate_hide(ndphp.animation.delay, function() {
		jQuery.ajax({
			type: "POST",
			url: "<?=filter_js_str(base_url(), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>index.php/" + ctrl + "/list_group_body_ajax/" + grouping_field,
			success: function(data) {
				var html = jQuery(data);
				ndphp.nav.back_store('body', jQuery('#body').html());
				jQuery("#body").html(html).nd_animate_show(ndphp.animation.delay, function() {
					ndphp.ui.ready();
				});
			},
			error: function(xhr, ajaxOptions, thrownError) {
				jQuery("#ajax_error_dialog").html('<?=filter_html_js_str(NDPHP_LANG_MOD_UNABLE_OPERATION, NDPHP_LANG_MOD_DEFAULT_CHARSET)?><br /><br /><span style="font-weight: bold"><?=filter_html_js_str(ucfirst(NDPHP_LANG_MOD_WORD_REASON), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>:</span> ' + xhr.responseText);
				jQuery("#ajax_error_dialog").dialog({ modal: true, title: '<?=filter_html_js_str(NDPHP_LANG_MOD_CANNOT_OPERATION, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>' });
				ndphp.ui.ready();
			}
		})
	});
};

ndphp.ajax.load_body_menu = function(e, menu, alias) {
	e.preventDefault();

	ndphp.ui.busy();

	jQuery.ajax({
		type: "POST",
		url: "<?=filter_js_str(base_url(), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>index.php/" + menu + "/index_ajax",
		success: function(data) {
			var html = jQuery(data);
			ndphp.nav.back_store('body', jQuery('#body').html());
			jQuery("#body").nd_animate_hide(ndphp.animation.delay, function() {
				jQuery("#body").html(html).nd_animate_show(ndphp.animation.delay, function() {
					ndphp.ui.ready();
				});
			});
		},
		error: function(xhr, ajaxOptions, thrownError) {
			jQuery("#ajax_error_dialog").html('<?=filter_html_js_str(NDPHP_LANG_MOD_UNABLE_LOAD_MENU, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>: \'' + alias + '\'<br /><br /><span style="font-weight: bold"><?=filter_html_js_str(ucfirst(NDPHP_LANG_MOD_WORD_REASON), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>:</span> ' + xhr.responseText);
			jQuery("#ajax_error_dialog").dialog({ modal: true, title: '<?=filter_html_js_str(NDPHP_LANG_MOD_CANNOT_LOAD_MENU, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>' });
			ndphp.ui.ready();
		}
	});
};

ndphp.ajax.refresh_user_credit = function() {
	jQuery.ajax({
		type: "POST",
		url: '<?=filter_js_str(base_url(), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>index.php/users/user_credit_get',
		success: function(data) {
			jQuery('#user_credit').html(data);
		}
	});
};

ndphp.ajax.load_add_funds = function(e, target_url) {
	e.preventDefault();

	ndphp.ui.busy();

	jQuery.ajax({
		type: "POST",
		url: target_url,
		success: function(data) {
			var html = jQuery(data);
			ndphp.nav.back_store('body', jQuery('#body').html());
			jQuery("#body").nd_animate_hide(ndphp.animation.delay, function() {
				jQuery("#body").html(html).nd_animate_show(ndphp.animation.delay, function() {
					ndphp.ui.ready();
				});
			});
		},
		error: function(xhr, ajaxOptions, thrownError) {
			jQuery("#ajax_error_dialog").html('<?=filter_html_js_str(NDPHP_LANG_MOD_UNABLE_LOAD_MENU, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>: <?=filter_html_js_str(NDPHP_LANG_MOD_LINK_ADD_FUNDS, NDPHP_LANG_MOD_DEFAULT_CHARSET)?><br /><br /><span style="font-weight: bold"><?=filter_html_js_str(ucfirst(NDPHP_LANG_MOD_WORD_REASON), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>:</span> ' + xhr.responseText);
			jQuery("#ajax_error_dialog").dialog({ modal: true, title: '<?=filter_html_js_str(NDPHP_LANG_MOD_CANNOT_LOAD_MENU, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>' });
			ndphp.ui.ready();
		}
	});
};

ndphp.ajax.get_user_subscription = function() {
	jQuery.ajax({
		type: "POST",
		url: '<?=filter_js_str(base_url(), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>index.php/users/user_subscription_get',
		success: function(data) {
			jQuery('#subscription_plan').html(data);
		}
	});
};

ndphp.ajax.load_subscription_upgrade = function(e, target_url) {
	e.preventDefault();

	ndphp.ui.busy();

	jQuery.ajax({
		type: "POST",
		url: target_url,
		success: function(data) {
			var html = jQuery(data);
			ndphp.nav.back_store('body', jQuery('#body').html());
			jQuery("#body").nd_animate_hide(ndphp.animation.delay, function() {
				jQuery("#body").html(html).nd_animate_show(ndphp.animation.delay, function() {
					ndphp.ui.ready();
				});
			});
		},
		error: function(xhr, ajaxOptions, thrownError) {
			jQuery("#ajax_error_dialog").html('<?=filter_html_js_str(NDPHP_LANG_MOD_UNABLE_LOAD_MENU, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>: <?=filter_html_js_str(NDPHP_LANG_MOD_LINK_UPGRADE, NDPHP_LANG_MOD_DEFAULT_CHARSET)?><br /><br /><span style="font-weight: bold"><?=filter_html_js_str(ucfirst(NDPHP_LANG_MOD_WORD_REASON), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>:</span> ' + xhr.responseText);
			jQuery("#ajax_error_dialog").dialog({ modal: true, title: '<?=filter_html_js_str(NDPHP_LANG_MOD_CANNOT_LOAD_MENU, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>' });
			ndphp.ui.ready();
		}
	});
};

ndphp.ajax.get_user_notification_count = function() {
	jQuery.ajax({
		type: "POST",
		url: '<?=filter_js_str(base_url(), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>index.php/notifications/get_count',
		success: function(data) {
			if (parseInt(data) <= 0) {
				jQuery('#user_notifications_total').html('0');
				jQuery('#user_notifications_total').hide();
				return;
			} else {
				if (parseInt(data) > 99)
					data = '...';

				jQuery('#user_notifications_total').html(data);
				jQuery('#user_notifications_total').show();
			}
		}
	})
};

ndphp.ajax.load_user_settings = function(e, target_url) {
	e.preventDefault();

	ndphp.ui.busy();

	jQuery.ajax({
		type: "POST",
		url: target_url,
		success: function(data) {
			var html = jQuery(data);
			ndphp.nav.back_store('body', jQuery('#body').html());
			jQuery("#body").nd_animate_hide(ndphp.animation.delay, function() {
				jQuery("#body").html(html).nd_animate_show(ndphp.animation.delay, function() {
					ndphp.ui.ready();
				});
			});
		},
		error: function(xhr, ajaxOptions, thrownError) {
			jQuery("#ajax_error_dialog").html('<?=filter_html_js_str(NDPHP_LANG_MOD_UNABLE_LOAD_MENU, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>: <?=filter_html_js_str(NDPHP_LANG_MOD_LINK_USER_SETTINGS, NDPHP_LANG_MOD_DEFAULT_CHARSET)?><br /><br /><span style="font-weight: bold"><?=filter_html_js_str(ucfirst(NDPHP_LANG_MOD_WORD_REASON), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>:</span> ' + xhr.responseText);
			jQuery("#ajax_error_dialog").dialog({ modal: true, title: '<?=filter_html_js_str(NDPHP_LANG_MOD_CANNOT_LOAD_MENU, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>' });
			ndphp.ui.ready();
		}
	});
};

ndphp.ajax.load_body_home = function(e) {
	e.preventDefault();

	ndphp.ui.busy();

	jQuery.ajax({
		type: "POST",
		url: "<?=filter_js_str(base_url(), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>index.php/home/home_body_ajax",
		success: function(data) {
			var html = jQuery(data);
			ndphp.nav.back_store('body', jQuery('#body').html());
			jQuery("#body").nd_animate_hide(ndphp.animation.delay, function() {
				jQuery("#body").html(html).nd_animate_show(ndphp.animation.delay, function() {
					ndphp.ui.ready();
				});
			});
		},
		error: function(xhr, ajaxOptions, thrownError) {
			jQuery("#ajax_error_dialog").html('<?=filter_html_js_str(NDPHP_LANG_MOD_UNABLE_LOAD_MENU, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>: <?=filter_html_js_str(NDPHP_LANG_MOD_LINK_HOME, NDPHP_LANG_MOD_DEFAULT_CHARSET)?><br /><br /><span style="font-weight: bold"><?=filter_html_js_str(ucfirst(NDPHP_LANG_MOD_WORD_REASON), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>:</span> ' + xhr.responseText);
			jQuery("#ajax_error_dialog").dialog({ modal: true, title: '<?=filter_html_js_str(NDPHP_LANG_MOD_CANNOT_LOAD_MENU, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>' });
			ndphp.ui.ready();
		}
	});
};

/* Mixed Relationship Offset Counter */
ndphp.mixed = {};

ndphp.mixed.move_item_up_index_offset = function(table, mid) {
	if (mid <= 1)
		return 0;

	var index = mid - 1;

	while (index >= 1) {
		if (jQuery("#mixed_item_" + table + "_" + index).length)
			return mid - index;

		index --;
	}

	return 0;
};

ndphp.mixed.move_item_down_index_offset = function(table, mid, last) {
	var index = mid + 1;

	while (index <= last) {
		if (jQuery("#mixed_item_" + table + "_" + index).length)
			return index - mid;

		index ++;
	}

	return 0;
};

/* Mixed Relationship Data Replication */
ndphp.mixed.replicate_item_data = function(selector_id_from, selector_id_to) {
	var field_content_input = new Array();
	var field_content_textarea = new Array();
	var field_content_select = new Array();
	var index_input = 0;
	var index_textarea = 0;
	var index_select = 0;

	/* Store FROM data */
	jQuery("#" + selector_id_from + " :input").each(function() {
		if (this.tagName.toUpperCase() == "INPUT") {
			field_content_input[index_input ++] = jQuery(this).val();
		}
	});

	jQuery("#" + selector_id_from + " textarea").each(function() {
		if (this.tagName.toUpperCase() == "TEXTAREA") {
			field_content_textarea[index_textarea ++] = jQuery(this).val();
		}
	});

	jQuery("#" + selector_id_from + " select").each(function() {
		if (this.tagName.toUpperCase() == "SELECT") {
			field_content_select[index_select ++] = jQuery(this).html();
		}
	});

	/* Reset indices */
	index_input = 0;
	index_textarea = 0;
	index_select = 0;

	/* Update TO data (with FROM) */
	jQuery("#" + selector_id_to + " :input").each(function() {
		if (this.tagName.toUpperCase() == "INPUT") {
			jQuery(this).val(field_content_input[index_input ++]);
		}
	});

	jQuery("#" + selector_id_to + " textarea").each(function() {
		if (this.tagName.toUpperCase() == "TEXTAREA") {
			jQuery(this).val(field_content_textarea[index_textarea ++]);
		}
	});

	jQuery("#" + selector_id_to + " select").each(function() {
		if (this.tagName.toUpperCase() == "SELECT") {
			jQuery(this).html(field_content_select[index_select ++]);
		}
	});
};

/* Mixed Relationship Data Swapping */
ndphp.mixed.swap_item_data = function(selector_id_from, selector_id_to) {
	var field_content_input_from = new Array();
	var field_content_textarea_from = new Array();
	var field_content_select_from = new Array();
	var field_content_input_to = new Array();
	var field_content_textarea_to = new Array();
	var field_content_select_to = new Array();
	var index_input = 0;
	var index_textarea = 0;
	var index_select = 0;

	/* Store FROM */
	jQuery("#" + selector_id_from + " :input").each(function() {
		if (this.tagName.toUpperCase() == "INPUT") {
			field_content_input_from[index_input ++] = jQuery(this).val();
		}
	});

	jQuery("#" + selector_id_from + " textarea").each(function() {
		if (this.tagName.toUpperCase() == "TEXTAREA") {
			field_content_textarea_from[index_textarea ++] = jQuery(this).val();
		}
	});

	jQuery("#" + selector_id_from + " select").each(function() {
		if (this.tagName.toUpperCase() == "SELECT") {
			field_content_select_from[index_select ++] = jQuery(this).html();
		}
	});

	/* Reset indices */
	index_input = 0;
	index_textarea = 0;
	index_select = 0;

	/* Store TO */
	jQuery("#" + selector_id_to + " :input").each(function() {
		if (this.tagName.toUpperCase() == "INPUT") {
			field_content_input_to[index_input ++] = jQuery(this).val();
		}
	});

	jQuery("#" + selector_id_to + " textarea").each(function() {
		if (this.tagName.toUpperCase() == "TEXTAREA") {
			field_content_textarea_to[index_textarea ++] = jQuery(this).val();
		}
	});

	jQuery("#" + selector_id_to + " select").each(function() {
		if (this.tagName.toUpperCase() == "SELECT") {
			field_content_select_to[index_select ++] = jQuery(this).html();
		}
	});

	/* Reset indices */
	index_input = 0;
	index_textarea = 0;
	index_select = 0;

	/* Update FROM (with TO) */
	jQuery("#" + selector_id_from + " :input").each(function() {
		if (this.tagName.toUpperCase() == "INPUT") {
			jQuery(this).val(field_content_input_to[index_input ++]);
		}
	});

	jQuery("#" + selector_id_from + " textarea").each(function() {
		if (this.tagName.toUpperCase() == "TEXTAREA") {
			jQuery(this).val(field_content_textarea_to[index_textarea ++]);
		}
	});

	jQuery("#" + selector_id_from + " select").each(function() {
		if (this.tagName.toUpperCase() == "SELECT") {
			jQuery(this).html(field_content_select_to[index_select ++]);
		}
	});

	/* Reset indices */
	index_input = 0;
	index_textarea = 0;
	index_select = 0;

	/* Update TO (with FROM) */
	jQuery("#" + selector_id_to + " :input").each(function() {
		if (this.tagName.toUpperCase() == "INPUT") {
			jQuery(this).val(field_content_input_from[index_input ++]);
		}
	});

	jQuery("#" + selector_id_to + " textarea").each(function() {
		if (this.tagName.toUpperCase() == "TEXTAREA") {
			jQuery(this).val(field_content_textarea_from[index_textarea ++]);
		}
	});

	jQuery("#" + selector_id_to + " select").each(function() {
		if (this.tagName.toUpperCase() == "SELECT") {
			jQuery(this).html(field_content_select_from[index_select ++]);
		}
	});
};

/* Mixed Relationship Operation Rendering */
ndphp.mixed.render_ops = function(base_url, ctrl, acitems, divid, mid, table, field, text, no_remove) {
	if (!no_remove) {
		/* TODO: Should be retrieved from controller/view */
		jQuery('#mixed_' + table + '_ops_' + mid).html(
			"<span class=\"mixed_ops_buttons\">" +
				"<a title=\"<?=filter_html_js_str(NDPHP_LANG_MOD_OP_MIXED_MOVE_ITEM_UP, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>\" href=\"javascript:ndphp.mixed.move_item_up('" + table + "', " + mid + ");\">" +
					"<img height=\"16\" width=\"16\" class=\"moveup_op_icon\" alt=\"<?=filter_html_js_str(NDPHP_LANG_MOD_OP_MIXED_MOVE_ITEM_UP, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>\" src=\"<?=static_images_url()?>/themes/" + ndphp.theme.name + "/icons/uparrow.png\" />" +
				"</a>" +
				"&nbsp;&nbsp;" +
				"<a title=\"<?=filter_html_js_str(NDPHP_LANG_MOD_OP_MIXED_CLONE_ITEM, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>\" href=\"javascript:ndphp.mixed.clone_item('" + base_url + "', '" + ctrl + "', autocomplete_items_" + table + ", '" + divid + "', " + mid + ", '" + table + "', '" + field + "', '" + text + "', " + no_remove + ", ++ mixed_item_" + table + ");\">" +
					"<img height=\"16\" width=\"16\" class=\"clone_op_icon\" alt=\"<?=filter_html_js_str(NDPHP_LANG_MOD_OP_MIXED_CLONE_ITEM, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>\" src=\"<?=static_images_url()?>/themes/" + ndphp.theme.name + "/icons/copy.png\" />" +
				"</a>" +
				"<br />" +
				"<a title=\"<?=filter_html_js_str(NDPHP_LANG_MOD_OP_MIXED_MOVE_ITEM_DOWN, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>\" href=\"javascript:ndphp.mixed.move_item_down('" + table + "', " + mid + ", mixed_item_" + table + ");\">" +
					"<img height=\"16\" width=\"16\" class=\"movedown_op_icon\" alt=\"<?=filter_html_js_str(NDPHP_LANG_MOD_OP_MIXED_MOVE_ITEM_DOWN, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>\" src=\"<?=static_images_url()?>/themes/" + ndphp.theme.name + "/icons/downarrow.png\" />" +
				"</a>" +
				"&nbsp;&nbsp;" +
				"<a title=\"<?=filter_html_js_str(NDPHP_LANG_MOD_OP_MIXED_DELETE_ITEM, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>\" href=\"javascript:ndphp.mixed.del_item('" + table + "', " + mid + ");\">" +
					"<img height=\"16\" width=\"16\" class=\"delete_op_icon\" alt=\"<?=filter_html_js_str(NDPHP_LANG_MOD_OP_MIXED_DELETE_ITEM, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>\" src=\"<?=static_images_url()?>/themes/" + ndphp.theme.name + "/icons/delete.png\" />" +
				"</a>" +
			"</span>"
		);
	}
};

/* Mixed Relationship Handlers */
ndphp.mixed.move_item_up = function(table, mid) {
	ndphp.ui.busy();

	/* Compute item offset */
	var offset = ndphp.mixed.move_item_up_index_offset(table, mid);

	/* Check if we can move */	
	if (!offset) {
		ndphp.ui.ready();
		return;
	}

	ndphp.mixed.swap_item_data("mixed_item_" + table + "_" + mid, "mixed_item_" + table + "_" + (mid - offset));

	ndphp.ui.ready();
};

ndphp.mixed.move_item_down = function(table, mid, last) {
	ndphp.ui.busy();

	/* Compute item offset */
	var offset = ndphp.mixed.move_item_down_index_offset(table, mid, last);

	/* Check if we can move */
	if (!offset) {
		ndphp.ui.ready();
		return;
	}

	ndphp.mixed.swap_item_data("mixed_item_" + table + "_" + mid, "mixed_item_" + table + "_" + (mid + offset));

	ndphp.ui.ready();
};

ndphp.mixed.clone_item = function(base_url, ctrl, acitems, divid, mid, table, field, text, no_remove, last) {
	var count = last;

	jQuery('#' + divid).append('<tr id="mixed_item_' + table + '_' + count + '" class="field_' + (count % 2 ? 'even' : 'odd') + '"></tr>');

	ndphp.ui.busy();

	jQuery.ajax({
		type: "POST",
		url: base_url + "index.php/" + table + "/create_mixed_rel/" + count  + "/" + ctrl + "/" + field + "/" + encodeURIComponent(ndphp.utils.safe_b64encode(utf8_encode(text))),
		success: function(data) {
			var html = jQuery(data);
			jQuery('#mixed_item_' + table + '_' + count).html(html);

			ndphp.mixed.render_ops(base_url, ctrl, acitems, divid, count, table, field, text, no_remove);

			jQuery('#mixed_' + table + '_' + field + '_' + count).autocomplete({
				source: acitems,
				select: function(event, ui) {
					ndphp.mixed.refresh_values(base_url, ctrl, acitems, divid, count, table, field, text, no_remove);
				},
				minLength: 0
			}).focus(function() {
				jQuery(this).trigger('keydown.autocomplete');
			});

			ndphp.mixed.replicate_item_data("mixed_item_" + table + "_" + mid, "mixed_item_" + table + "_" + count);

			ndphp.ui.ready();
		},
		error: function(xhr, ajaxOptions, thrownError) {
			jQuery("#ajax_error_dialog").html('<?=filter_html_js_str(NDPHP_LANG_MOD_UNABLE_CLONE_ITEM, NDPHP_LANG_MOD_DEFAULT_CHARSET)?><br /><br /><span style="font-weight: bold"><?=filter_html_js_str(ucfirst(NDPHP_LANG_MOD_WORD_REASON), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>:</span> ' + xhr.responseText);
			jQuery("#ajax_error_dialog").dialog({ modal: true, title: '<?=filter_html_js_str(NDPHP_LANG_MOD_CANNOT_CLONE_ITEM, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>' });
			ndphp.ui.ready();
		}
	});
};

ndphp.mixed.del_item = function(table, mid) {
	jQuery('#mixed_item_' + table + "_" + mid).remove();
};

ndphp.mixed.refresh_values = function(base_url, ctrl, acitems, divid, mid, table, field, text, no_remove) {
	clearTimeout(ndphp.mixed_timer[eval('mixed_item_' + table)]);
	ndphp.mixed_timer[eval('mixed_item_' + table)] = setTimeout(function refresh_values() {
		var field_data = jQuery('#mixed_' + table + '_' + field + '_' + mid).val();

		ndphp.ui.busy();

		jQuery.ajax({
			type: "POST",
			url: base_url + "index.php/" + table + "/create_mixed_rel/" + mid  + "/" + ctrl + "/" + field + "/" + encodeURIComponent(ndphp.utils.safe_b64encode(utf8_encode(field_data))),
			success: function(data) {
				var html = jQuery(data);
				jQuery('#mixed_item_' + table + '_' + mid).html(html);

				ndphp.mixed.render_ops(base_url, ctrl, acitems, divid, mid, table, field, text, no_remove);

				jQuery('#mixed_' + table + '_' + field + '_' + mid).val(field_data);

				jQuery('#mixed_' + table + '_' + field + '_' + mid).autocomplete({
					source: acitems,
					select: function(event, ui) {
						ndphp.mixed.refresh_values(base_url, ctrl, acitems, divid, mid, table, field, text, no_remove);
					},
					minLength: 0
				}).focus(function() {
					jQuery(this).trigger('keydown.autocomplete');
				});
				ndphp.ui.ready();
			},
			error: function(xhr, ajaxOptions, thrownError) {
				jQuery("#ajax_error_dialog").html('<?=filter_html_js_str(NDPHP_LANG_MOD_UNABLE_MIXED_LOAD_VALUES, NDPHP_LANG_MOD_DEFAULT_CHARSET)?><br /><br /><span style="font-weight: bold"><?=filter_html_js_str(ucfirst(NDPHP_LANG_MOD_WORD_REASON), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>:</span> ' + xhr.responseText);
				jQuery("#ajax_error_dialog").dialog({ modal: true, title: '<?=filter_html_js_str(NDPHP_LANG_MOD_CANNOT_UPDATE_DATA, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>' });
				ndphp.ui.ready();
			}
		});
	}, 500);
};

ndphp.mixed.new_item = function(base_url, ctrl, acitems, divid, count, table, field, text, no_remove) {
	jQuery('#' + divid).append('<tr id="mixed_item_' + table + '_' + count + '" class="field_' + (count % 2 ? 'even' : 'odd') + '"></tr>');

	ndphp.ui.busy();

	jQuery.ajax({
		type: "POST",
		url: base_url + "index.php/" + table + "/create_mixed_rel/" + count  + "/" + ctrl + "/" + field + "/" + encodeURIComponent(ndphp.utils.safe_b64encode(utf8_encode(text))),
		success: function(data) {
			var html = jQuery(data);
			jQuery('#mixed_item_' + table + '_' + count).html(html);

			ndphp.mixed.render_ops(base_url, ctrl, acitems, divid, count, table, field, text, no_remove);

			jQuery('#mixed_' + table + '_' + field + '_' + count).autocomplete({
				source: acitems,
				select: function(event, ui) {
					ndphp.mixed.refresh_values(base_url, ctrl, acitems, divid, count, table, field, text, no_remove);
				},
				minLength: 0
			}).focus(function() {
				jQuery(this).trigger('keydown.autocomplete');
			});
			ndphp.ui.ready();
		},
		error: function(xhr, ajaxOptions, thrownError) {
			jQuery("#ajax_error_dialog").html('<?=filter_html_js_str(NDPHP_LANG_MOD_UNABLE_MIXED_INSERT_NEW, NDPHP_LANG_MOD_DEFAULT_CHARSET)?><br /><br /><span style="font-weight: bold"><?=filter_html_js_str(ucfirst(NDPHP_LANG_MOD_WORD_REASON), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>:</span> ' + xhr.responseText);
			jQuery("#ajax_error_dialog").dialog({ modal: true, title: '<?=filter_html_js_str(NDPHP_LANG_MOD_CANNOT_INSERT_ITEM, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>' });
			ndphp.ui.ready();
		}
	});
};

ndphp.mixed.deploy_item = function(base_url, ctrl, id, acitems, divid, count, table, field, text, no_remove, disable_input) {
	jQuery('#' + divid).append('<tr id="mixed_item_' + table + '_' + count + '" class="field_' + (count % 2 ? 'even' : 'odd') + '"></tr>');

	ndphp.ui.busy();

	jQuery.ajax({
		type: "POST",
		url: base_url + "index.php/" + table + "/edit_mixed_rel/" + count  + "/" + ctrl + "/" + id,
		success: function(data) {
			var html = jQuery(data);
			jQuery('#mixed_item_' + table + '_' + count).html(html);

			if (disable_input) {
				jQuery('#mixed_item_' + table + '_' + count + ' :input').prop('disabled', true);
				jQuery('#mixed_' + table + '_ops_' + count).remove();
			}

			ndphp.mixed.render_ops(base_url, ctrl, acitems, divid, count, table, field, text, no_remove);

			jQuery('#mixed_' + table + '_' + field + '_' + count).autocomplete({
				source: acitems,
				select: function(event, ui) {
					ndphp.mixed.refresh_values(base_url, ctrl, acitems, divid, count, table, field, text, no_remove);
				},
				minLength: 0
			}).focus(function() {
				jQuery(this).trigger('keydown.autocomplete');
			});

			ndphp.ui.ready();
		},
		error: function(xhr, ajaxOptions, thrownError) {
			jQuery("#ajax_error_dialog").html('<?=filter_html_js_str(NDPHP_LANG_MOD_UNABLE_MIXED_LOAD_ASSOC_LIST, NDPHP_LANG_MOD_DEFAULT_CHARSET)?><br /><br /><span style="font-weight: bold"><?=filter_html_js_str(ucfirst(NDPHP_LANG_MOD_WORD_REASON), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>:</span> ' + xhr.responseText);
			jQuery("#ajax_error_dialog").dialog({ modal: true, title: '<?=filter_html_js_str(NDPHP_LANG_MOD_CANNOT_LOAD_DATA, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>' });
			ndphp.ui.ready();
		}
	});
};

ndphp.mixed.deploy_item_view = function(base_url, ctrl, id, divid, count, table, field) {
	jQuery('#' + divid).append('<tr id="mixed_item_' + table + '_' + count + '" class="field_' + (count % 2 ? 'even' : 'odd') + '"></tr>');

	ndphp.ui.busy();

	jQuery.ajax({
		type: "POST",
		url: base_url + "index.php/" + table + "/view_mixed_rel/" + count + "/" + ctrl + "/" + id,
		success: function(data) {
			var html = jQuery(data);
			jQuery('#mixed_item_' + table + '_' + count).html(html);
			ndphp.ui.ready();
		},
		error: function(xhr, ajaxOptions, thrownError) {
			jQuery("#ajax_error_dialog").html('<?=filter_html_js_str(NDPHP_LANG_MOD_UNABLE_MIXED_LOAD_ASSOC_LIST, NDPHP_LANG_MOD_DEFAULT_CHARSET)?><br /><br /><span style="font-weight: bold"><?=filter_html_js_str(ucfirst(NDPHP_LANG_MOD_WORD_REASON), NDPHP_LANG_MOD_DEFAULT_CHARSET)?>:</span> ' + xhr.responseText);
			jQuery("#ajax_error_dialog").dialog({ modal: true, title: '<?=filter_html_js_str(NDPHP_LANG_MOD_CANNOT_LOAD_DATA, NDPHP_LANG_MOD_DEFAULT_CHARSET)?>' });
			ndphp.ui.ready();
		}
	});
};

/* Multiple relationships */
ndphp.multi = {};

ndphp.multi.select_multi_add_selected = function(select_available_id, select_selected_id) {
	jQuery("#" + select_available_id + " option:selected").each(function() {
		jQuery("#" + select_selected_id).append('<option value="' + jQuery(this).val() + '">' + jQuery(this).text() + '</option>')
		jQuery(this).remove();
	});
};

ndphp.multi.select_multi_del_selected = function(select_selected_id, select_available_id) {
	jQuery("#" + select_selected_id + " option:selected").each(function() {
		jQuery("#" + select_available_id).append('<option value="' + jQuery(this).val() + '">' + jQuery(this).text() + '</option>')
		jQuery(this).remove();
	});
};

/* Grouping */
ndphp.grouping = {};

ndphp.grouping.visibility = {};
ndphp.grouping.enabled = false;
ndphp.grouping.controller = '';
ndphp.grouping.field = '';

ndphp.grouping.group_visibility_eval = function(ctrl, group) {
	/* Evaluate the visibility state of the group and fixes it if required */
	if (ctrl in ndphp.grouping.visibility && group in ndphp.grouping.visibility[ctrl]) {
		if (ndphp.grouping.visibility[ctrl][group] == "hidden") {
			jQuery('#group_data_' + group).hide();
			jQuery('#arrow_hidden_' + group).show();
			jQuery('#arrow_visible_' + group).hide();
		} else {
			jQuery('#group_data_' + group).show();
			jQuery('#arrow_hidden_' + group).hide();
			jQuery('#arrow_visible_' + group).show();
		}
	}
}

ndphp.grouping.group_visibility_toggle = function(e, ctrl, group) {
	e.preventDefault();

	/* Check if there's a visibility setting for the requested group. If not, assume hidden */
	if (ctrl in ndphp.grouping.visibility && group in ndphp.grouping.visibility[ctrl]) {
		if (ndphp.grouping.visibility[ctrl][group] == "hidden") {
			jQuery('#group_data_' + group).show();
			jQuery('#arrow_hidden_' + group).hide();
			jQuery('#arrow_visible_' + group).show();
			ndphp.grouping.visibility[ctrl][group] = "visible";
		} else {
			jQuery('#group_data_' + group).hide();
			jQuery('#arrow_hidden_' + group).show();
			jQuery('#arrow_visible_' + group).hide();
			ndphp.grouping.visibility[ctrl][group] = "hidden";
		}
	} else {
		/* Set the visibility key for controller if it's not set yet */
		if (!(ctrl in ndphp.grouping.visibility))
			ndphp.grouping.visibility[ctrl] = {};

		/* Set the visibility controller group key as visible and show it (since it was not set, it is currently hidden) */
		ndphp.grouping.visibility[ctrl][group] = "visible";

		/* Make the group visible */
		jQuery('#group_data_' + group).show();
		jQuery('#arrow_hidden_' + group).hide();
		jQuery('#arrow_visible_' + group).show();
	}
}
