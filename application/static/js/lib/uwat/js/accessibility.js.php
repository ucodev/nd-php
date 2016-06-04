/*
 *
 * uWAT - uCodev Web Accessibility Tools
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
 *
 * Author:  Pedro A. Hortas
 * Module:  uWAT JavaScript Implementation
 * Version: 0.1b
 * Email:   pah@ucodev.org
 * Date:    2016/06/04
 * License: GPLv3
 *
 * URL: http://www.ucodev.org
 * 
 */

/* SECTION: Globals */
var _accessibility_resize_ratio = 0;
var _accessibility_hcm_status = false;
var _accessibility_hcm_mode = "original";
var _accessibility_hcm_mode_index = 0;


/* SECTION: Config */
var config_images_path = '<?=static_js_dir()?>/lib/uwat/images/';
var config_resize_percentage = 5;
var config_hcm_modes = [ "original", "dark", "bright" ];
var config_hcm_color_bg_bright = [ 255, 255, 255 ];
var config_hcm_color_bg_dark = [ 0, 0, 0 ];
var config_hcm_color_text_bright = [ 255, 255, 255 ];
var config_hcm_color_text_dark = [ 0, 0, 0 ];
var config_force_dark_class = [ ];	/* Force processing of specific classes for dark hcm mode */
var config_force_bright_class = [ ];	/* Force processing of specific classes for bright hcm mode */
var config_invert_image_colors = true;  /* Invert document images colors */
var config_ignore_ids = [ 'accessibility_button_enlarge', 'accessibility_button_reduce', 'accessibility_button_contrast', 'accessibility_button_reload' ];

function config_resize_hook(obj, op, percent) {
	/* Per-object hook for resize routine */
	return;
}

function config_original_pre() {
	/* User-defined routines before original hcm mode is triggered */
	return;
}

function config_dark_pre() {
	/* User-defined routines before dark hcm mode is triggered */
	return;
}

function config_bright_pre() {
	/* User-defined routines before bright hcm mode is triggered */
	return;
}

function config_original_hook(obj) {
	/* Per-object hook for original hcm mode */
	return;
}

function config_dark_hook(obj) {
	/* Per-object hook for dark hcm mode */
	return;
}

function config_bright_hook(obj) {
	/* Per-object hook for bright hcm mode */
	return;
}

function config_original_post() {
	/* User-defined routines after original hcm mode is complete */
	return;
}

function config_dark_post() {
	/* User-defined routines after dark hcm mode is complete */
	return;
}

function config_bright_post() {
	/* User-defined routines after bright hcm mode is complete */
	return;
}

function accessibility_config_mode_next() {
	_accessibility_hcm_mode_index ++;
	_accessibility_hcm_mode_index %= config_hcm_modes.length;

	localStorage.setItem("_accessibility_hcm_mode_index", _accessibility_hcm_mode_index);

	return config_hcm_modes[_accessibility_hcm_mode_index];
}

/* SECTION: Calc */
function accessibility_color_contrast(r, g, b) {
	var res = ((r * 299) + (g * 587) + (b * 114)) / 1000.0;

	return (res > 125.0); /* true if bright, false if dark */
}

function accessibility_check_inheritance(obj, attribute) {
	/* Was font-style explicitly set for this object? Or was it inherited? */
	var attr_orig = null;
	var attr_inhrt = null;

	if (!(attr_orig = jQuery(obj).css(attribute))) {
		/* If font size is zero we don't really need to process it */
		return;
	}

	jQuery(obj).css(attribute, 'inherit');

	attr_inhrt = jQuery(obj).css(attribute);
	jQuery(obj).css(attribute, attr_orig);

	return (attr_inhrt == attr_orig);
}

function accessibility_obj_rgb_attr_explicit(obj, attribute) {
	css_attr = jQuery(obj).css(attribute);

	/* rgba(0, 0, 0, 0) is typically an indication of non-explicit color */
	explicit = css_attr.match(/^rgba\(0,\s*0,\s*0,\s*0\)$/);

	return !explicit;
}

function accessibility_obj_rgb_attr(obj, attribute) {
	rgb = jQuery(obj).css(attribute);

	rgb_p = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);

	if (!rgb_p)
		rgb_p = rgb.match(/^rgba\((\d+),\s*(\d+),\s*(\d+),\s*(\d+)\)$/);

	return rgb_p;
}

function accessibility_obj_has_class(obj, class_list) {
	for (var i = 0; i < class_list.length; i ++) {
		if (jQuery(obj).hasClass(class_list[i]))
			return true;
	}

	return false;
}

function accessibility_img_pixel_rgb(img, x, y) {
	var canvas = document.createElement('canvas');

	context = canvas.getContext('2d');

	return context.getImageData(x - 1, y - 1, x, y).data;
}

function accessibility_elem_in_arr(arr, elem) {
	for (var i = 0; i < arr.length; i ++) {
		if (elem == arr[i])
			return true;
	}

	return false;
}

/* SECTION: Reset */
function accessibility_reset(reload) {
	localStorage.setItem("_accessibility_resize_ratio", 0);
	localStorage.setItem("_accessibility_hcm_status", false);
	localStorage.setItem("_accessibility_hcm_mode", "original");
	localStorage.setItem("_accessibility_hcm_mode_index", 0);

	if (reload)
		location.reload();
}

/* SECTION: Text size */
function accessibility_resize_font(obj, op, percent) {
	/*
	 * op:
	 *  1 == enlarge
	 * -1 == reduce
	 */
	if (obj.id.indexOf("accessibility") >= 0)
		return;

	if (obj.tagName.toUpperCase() == "HEAD")
		return;

	if (obj.tagName.toUpperCase() == "SCRIPT")
		return;

	/* Resize hook */
	config_resize_hook(obj, op, percent);

	/* If font-size was not inhertied or if this object is the HTML tag... */
	if (!accessibility_check_inheritance(obj, 'font-size') || (obj.tagName.toUpperCase() == "HTML")) {
		var spx = parseFloat(jQuery(obj).css('font-size'));

		spx = Math.round((spx * (1 / (1 + (percent / 100.0)) * ((-op * -op) + (-op))) / 2.0) + ((spx * (1 + (percent / 100.0))) * (op + 1) / 2.0));

		jQuery(obj).css('font-size', spx + "px");
	}
}

/* SECTION: Contrast */
function accessibility_high_contrast(reload) {
	if (_accessibility_hcm_mode == "dark") {
		config_dark_pre();

		/* Set the background to white before processing the document.
		 * Since the first iteration will invert the brightness, if it is white (bright)
		 * the result will be a dark hcm mode.
		 */
		jQuery('html').css('background-color', 'white');

		/* Change accessibility menu buttons */
		jQuery('#accessibility_button_enlarge').attr('src', config_images_path + '/accessibility_enlarge_white.png');
		jQuery('#accessibility_button_reduce').attr('src', config_images_path + '/accessibility_reduce_white.png');
		jQuery('#accessibility_button_contrast').attr('src', config_images_path + '/accessibility_contrast_white.png');
		jQuery('#accessibility_button_reload').attr('src', config_images_path + '/accessibility_reload_white.png');

		_accessibility_hcm_status = true;

		localStorage.setItem("_accessibility_hcm_status", _accessibility_hcm_status);
		localStorage.setItem("_accessibility_hcm_mode", _accessibility_hcm_mode);
		localStorage.setItem("_accessibility_hcm_mode_index", _accessibility_hcm_mode_index);
	} else if (_accessibility_hcm_mode == "bright") {
		config_bright_pre();

		/* Set the background to black before processing the document.
		 * Since the first iteration will invert the brightness, if it is black (dark)
		 * the result will be a bright hcm mode.
		 */
		jQuery('html').css('background-color', 'black');

		/* Change accessibility menu buttons */
		jQuery('#accessibility_button_enlarge').attr('src', config_images_path + '/accessibility_enlarge.png');
		jQuery('#accessibility_button_reduce').attr('src', config_images_path + '/accessibility_reduce.png');
		jQuery('#accessibility_button_contrast').attr('src', config_images_path + '/accessibility_contrast.png');
		jQuery('#accessibility_button_reload').attr('src', config_images_path + '/accessibility_reload.png');

		_accessibility_hcm_status = true;

		localStorage.setItem("_accessibility_hcm_status", _accessibility_hcm_status);
		localStorage.setItem("_accessibility_hcm_mode", _accessibility_hcm_mode);
		localStorage.setItem("_accessibility_hcm_mode_index", _accessibility_hcm_mode_index);
	} else if (_accessibility_hcm_mode == "original") {
		_accessibility_hcm_status = false;

		config_original_pre();

		localStorage.setItem("_accessibility_hcm_status", _accessibility_hcm_status);
		localStorage.setItem("_accessibility_hcm_mode", _accessibility_hcm_mode);
		localStorage.setItem("_accessibility_hcm_mode_index", _accessibility_hcm_mode_index);

		accessibility_reset(reload);

		return;
	}

	var bg_rgb_p = null;

	/* Retrieve the first element with explicit background color set if hcm status is enabled */
	if (_accessibility_hcm_status) {
		jQuery("*").each(function() {
			if (!bg_rgb_p) {
				if (accessibility_obj_rgb_attr_explicit(this, 'background-color')) {
					bg_rgb_p = accessibility_obj_rgb_attr(this, 'background-color');
				} else {
					return;
				}
			}
		});
	}

	/* If there're no explicit background color, assume white */
	if (!bg_rgb_p)
		bg_rgb_p = [0, 255, 255, 255];

	var invert_img = 0;

	/* Iterate all objects in the document and change contrast based on brightness */
	jQuery("*").each(function() {
		if (accessibility_elem_in_arr(config_ignore_ids, jQuery(this).attr('id')))
			return;

		if (_accessibility_hcm_mode == "dark") {
			config_dark_hook(this);
		} else if (_accessibility_hcm_mode == "bright") {
			config_bright_hook(this);
		} else if (_accessibility_hcm_mode == "original") {
			config_original_hook(this);
		}

		var go_further = 
			_accessibility_hcm_status ||
			!accessibility_check_inheritance(this, 'background-color') ||
			(this.tagName.toUpperCase() == "HTML") ||
			bg_rgb_p[1] != config_hcm_color_bg_bright[0] ||
			bg_rgb_p[1] != config_hcm_color_bg_dark[0] ||
			bg_rgb_p[2] != config_hcm_color_bg_bright[1] ||
			bg_rgb_p[2] != config_hcm_color_bg_dark[1] ||
			bg_rgb_p[3] != config_hcm_color_bg_bright[2] ||
			bg_rgb_p[3] != config_hcm_color_bg_dark[2];

		if (go_further && (accessibility_color_contrast(bg_rgb_p[1], bg_rgb_p[2], bg_rgb_p[3]) == true || accessibility_obj_has_class(this, config_force_dark_class)) && !accessibility_obj_has_class(this, config_force_bright_class)) {
			/* BG is Bright */
			jQuery(this).css('border-color', 'rgb(' + config_hcm_color_bg_bright[0] + ', ' + config_hcm_color_bg_bright[1] + ', ' + config_hcm_color_bg_bright[2] + ')');
			jQuery(this).css('background-color', 'rgb(' + config_hcm_color_bg_dark[0] + ', ' + config_hcm_color_bg_dark[1] + ', ' + config_hcm_color_bg_dark[2] + ')');
			jQuery(this).css('color', 'rgb(' + config_hcm_color_text_bright[0] + ', ' + config_hcm_color_text_bright[1] + ', ' + config_hcm_color_text_bright[2] + ')');

			/* Make anchors and input submits featured */
			if (this.tagName.toUpperCase() == "A" || this.tagName.toUpperCase() == "INPUT") {
				jQuery(this).css('text-decoration', 'underline');
			}

			/* Make buttons outlined */
			if (this.tagName.toUpperCase() == "BUTTON") {
				jQuery(this).css('outline', '1px solid ' + 'rgb(' + config_hcm_color_text_bright[0] + ', ' + config_hcm_color_text_bright[1] + ', ' + config_hcm_color_text_bright[2] + ')');
			}

			/* Invert image colors */
			if (this.tagName.toUpperCase() == "IMG" && config_invert_image_colors) {
				img_rgb = accessibility_img_pixel_rgb(jQuery(this).attr('src'), 1, 1);

				if (accessibility_color_contrast(img_rgb[0], img_rgb[1], img_rgb[2]) == true) {
					invert_img = 0;
				} else {
					invert_img = 1;
				}

				jQuery(this).css('-webkit-filter', 'invert(' + invert_img + ')');
				jQuery(this).css('filter', 'invert(' + invert_img + ')');
			}
		} else {
			/* BG is Dark */
			jQuery(this).css('border-color', 'rgb(' + config_hcm_color_bg_dark[0] + ', ' + config_hcm_color_bg_dark[1] + ', ' + config_hcm_color_bg_dark[2] + ')');
			jQuery(this).css('background-color', 'rgb(' + config_hcm_color_bg_bright[0] + ', ' + config_hcm_color_bg_bright[1] + ', ' + config_hcm_color_bg_bright[2] + ')');
			jQuery(this).css('color', 'rgb(' + config_hcm_color_text_dark[0] + ', ' + config_hcm_color_text_dark[1] + ', ' + config_hcm_color_text_dark[2] + ')');

			/* Make anchors and input submits featured */
			if (this.tagName.toUpperCase() == "A" || this.tagName.toUpperCase() == "INPUT") {
				jQuery(this).css('text-decoration', 'underline');
			}

			/* Make buttons outlined */
			if (this.tagName.toUpperCase() == "BUTTON") {
				jQuery(this).css('outline', '1px solid ' + 'rgb(' + config_hcm_color_text_dark[0] + ', ' + config_hcm_color_text_dark[1] + ', ' + config_hcm_color_text_dark[2] + ')');
			}

			/* Invert image colors */
			if (this.tagName.toUpperCase() == "IMG" && config_invert_image_colors) {
				img_rgb = accessibility_img_pixel_rgb(jQuery(this).attr('src'), 1, 1);

				if (accessibility_color_contrast(img_rgb[0], img_rgb[1], img_rgb[2]) == true) {
					invert_img = 1;
				} else {
					invert_img = 0;
				}

				jQuery(this).css('-webkit-filter', 'invert(' + invert_img + ')');
				jQuery(this).css('filter', 'invert(' + invert_img + ')');
			}
		}
	});

	if (_accessibility_hcm_mode == "dark")
		config_dark_post();
	else if (_accessibility_hcm_mode == "bright")
		config_bright_post();
	else if (_accessibility_hcm_mode == "original")
		config_original_post();
}

/* SECTION: Initialization */
function accessibility_init() {
	jQuery('html').css('display: none');

	jQuery("#accessibility_button_contrast").click(function() {
		_accessibility_hcm_mode = accessibility_config_mode_next();

		accessibility_high_contrast(true);
	});

	jQuery("#accessibility_button_reload").click(function() {
		accessibility_reset(true);
	});

	jQuery("#accessibility_button_enlarge").click(function() {
		_accessibility_resize_ratio ++;

		localStorage.setItem("_accessibility_resize_ratio", _accessibility_resize_ratio);

		jQuery("*").each(function() {
			accessibility_resize_font(this, 1, config_resize_percentage);
		});
	});

	jQuery("#accessibility_button_reduce").click(function() {
		_accessibility_resize_ratio --;

		localStorage.setItem("_accessibility_resize_ratio", _accessibility_resize_ratio);

		jQuery("*").each(function() {
			accessibility_resize_font(this, -1, config_resize_percentage);
		});
	});

	/* Load client session data */
	if (localStorage.getItem("_accessibility_resize_ratio"))
		_accessibility_resize_ratio = localStorage.getItem("_accessibility_resize_ratio");

	if (localStorage.getItem("_accessibility_hcm_status"))
		_accessibility_hcm_status = localStorage.getItem("_accessibility_hcm_status");

	if (localStorage.getItem("_accessibility_hcm_mode"))
		_accessibility_hcm_mode = localStorage.getItem("_accessibility_hcm_mode");

	if (localStorage.getItem("_accessibility_hcm_mode_index"))
		_accessibility_hcm_mode_index = localStorage.getItem("_accessibility_hcm_mode_index");
}

function accessibility_onready() {
	/* Setup the document based on client session state */
	if (_accessibility_resize_ratio) {
		if (_accessibility_resize_ratio < 0) {
			for (var i = 0; i > _accessibility_resize_ratio; i --) {
				jQuery("*").each(function() {
					accessibility_resize_font(this, -1, config_resize_percentage);
				});
			}
		} else {
			for (var i = 0; i < _accessibility_resize_ratio; i ++) {
				jQuery("*").each(function() {
					accessibility_resize_font(this, 1, config_resize_percentage);
				});
			}
		}
	}

	/* Check if we're in hcm and update document accordingly */
	if (_accessibility_hcm_status)
		accessibility_high_contrast(false);

	/* Reset local storage variables */
	localStorage.setItem("_accessibility_resize_ratio",_accessibility_resize_ratio);
	localStorage.setItem("_accessibility_hcm_status", _accessibility_hcm_status);
	localStorage.setItem("_accessibility_hcm_mode", _accessibility_hcm_mode);
	localStorage.setItem("_accessibility_hcm_mode_index", _accessibility_hcm_mode_index);
}

/* SECTION: Main */
function accessibility_main() {
	accessibility_init();

	jQuery(document).ready(function() {
		accessibility_onready();
	});
}

/* SECTION: Entry point */
accessibility_main();

