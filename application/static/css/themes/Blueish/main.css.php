<?php header('Content-Type: text/css'); ?>

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

/**
 *  BEGIN OF:
 * 
 *  - General Styles
 * 
 */

* {
	outline: none;
}

html {
	color: #000000;
	background-color: #eeeeee;
	min-width: 1024px; /* FIXME: This theme is not responsive... yet :( */
}

body.default {
	background-color: #eeeeee;
	padding: 0;
	margin: 0 auto;
	font-family: helvetica, verdana, arial, sans-serif;
	font-size: 80%;
}

a:link, a:visited {
	outline: none;
	color: #0077BB;
	background-color: transparent;
	text-decoration: none;
	font-weight: bold;
}

a:hover, a:active {
	outline: none;
	color: #0099DD;
	background-color: transparent;
	text-decoration: none;
	font-weight: bold;
}

h1 {
	float: left;
	padding-left: 15px;
}

/* text, password, number, textarea */
#create input[type="text"], #create input[type="password"], #create input[type="number"],
#edit input[type="text"], #edit input[type="password"], #edit input[type="number"],
#search input[type="text"], #search input[type="password"], #search input[type="number"], #search textarea {
	width: 250px;
	font-size: 14px;
	border: 1px solid #aaa;
	padding: 5px 5px 5px 3px;
	border-radius: 4px;
	background: #fafafa;
	outline: none;
	display: inline-block;
	-webkit-appearance: none;
	position: relative;
}

#create input[type="file"], #edit input[type="file"] {
	width: 260px;
	font-size: 13px;
	outline: none;
	display: inline-block;
	position: relative;
}

.edit_remove_file {
	display: inline-block;
	position: relative;
	bottom: 3px;
}

.input_time {
	width: 70px;
}

.input_date {
	width: 85px;
}

/*
select {
	background-color: white;
}
*/

/* select */

select {
	width:260px;
	/*min-width: 100px;*/
	border:1px solid #aaa;
	padding:5px 5px 5px 3px;
	border-radius:4px;
	background: #fafafa;
	outline:none;
	display: inline-block;
	-webkit-appearance:none;
	cursor:pointer;
	position:relative;
}

select:after {
	content:'<>';
	font:12px "Consolas", monospace;
	color:#aaa;
	-webkit-transform:rotate(90deg);
	-moz-transform: rotate(90deg);
	position:absolute;
	right:4px;
	top:0px;
	padding:0 0 2px;
	pointer-events:none;
	border-bottom:1px solid #ddd;
}

select:hover:after{
	color: #666;
}

select[multiple] {
	height: auto;
	min-height: 250px;
	max-height: 500px;
}

img {
	border: 0;
}

textarea {
	overflow: auto;
}

#browsing_actions {
	top: 0;
	right: 0;
	margin-top: 5px;
	margin-right: 18px;
	padding: 0;
}

img.browsing_actions_icon {
	width: 16px;
	height: 16px;
}

div.browsing_actions_entry {
	display: inline-block;
}

#container {
	padding: 0;
	margin: 0 auto;
	height: 100%;
	background-color: #eeeeee;
}

#header {
	padding: 0;
	background-color: #eeeeee;
	text-align: left;
}

#session_info {
	display: inline-block;
	padding-right: 5px;
	float: right;
	text-align: right;
	height: 100%;
	vertical-align: text-bottom;
}

table.session_info, td.session_info, tr.session_info {
	border: 0;
	padding: 0;
	margin: 0;
}

.session_info_username {
	vertical-align: middle;
	padding-right: 5px;
}

.session_info_user, .user_notifications {
	font-weight: bold;
}

.session_info_user_photo, .user_notifications_icon {
	height: 32px;
	width: 32px;
	margin-top: 6px;
	margin-right: 5px;
}

.user_notifications_total {
	position: relative;
	display: inline-block;
	text-align: center;
	vertical-align: middle;
	width: 100%;
	width: 12px;
	height: 12px;
	font-size: 8px;
	top: -24px;
	right: 22px;
	background-color: red;
	color: white;
	border-radius: 8px;
	padding: 1px;
}

#body {
	background-color: #eeeeee;
	padding: 0;
	margin: 0 auto;
	overflow: hidden;
}

#footer {
	background-color: #1d84c7;
	color: #eeeeee;
	text-align: center;
	/* vertical-align: middle; */
	width: 100%;
	padding-top: 5px;
	padding-bottom: 5px;
	float: bottom;
	/* line-height: 15px; */
}

#sitemap {
	margin: 0;
	padding: 0;
	background-color: #cccccc;
	min-height: 250px;
}

#sitemap_block_left {
	display: inline-block;
	float: left;
	margin: 0 auto;
	width: 25%;
	vertical-align: top;
}

#sitemap_block_center_left {
	float: left;
	display: inline-block;
	margin: 0 auto;
	width: 25%;
	vertical-align: top;
}

#sitemap_block_center_right {
	float: left;
	display: inline-block;
	margin: 0 auto;
	width: 25%;
	vertical-align: top;
}

#sitemap_block_right {
	display: inline-block;
	float: left;
	margin: 0 auto;
	width: 25%;
	vertical-align: top;
}

#powered_by {
	margin: 0 auto;
	padding: 0;
	float: right;
}

a.powered_by_link {
	color: #333333;
}

/**
 *  END OF:
 * 
 *  - General Styles
 * 
 */


/**
 *  BEGIN OF:
 * 
 *  - Home
 * 
 */

h1.home {
	font-size: 150%;
	text-align: center;
	color: #DDDDDD;
}

div.home_body {
	text-align: center;
	display: table;
	margin: 0 auto;
	width: 100%;
	padding-top: 25px;
	padding-bottom: 25px;
	background-color: #999999;
}

div.home_menu_entry {
	display: inline-block;
	margin: 30px;
}

span.home_menu_entry_legend {
	color: #eeeeee;
	font-weight: bold;
}

table.home_menu_entry_table {
	border: 0;
	margin: 0;
	padding: 0;
}

a.home_icon_link {
	
}

/**
 *  END OF:
 * 
 *  - Home
 * 
 */


/**
 *  BEGIN OF:
 * 
 *  - Menus
 * 
 */

#mainmenu {
	background-color: #1d84c7;
	text-align: center;
	width: 100%;
	min-height: 20px;
	padding: 0;
	margin: 0 auto;
	/* line-height: 20px; */
}

table.mainmenu_container {
	padding: 0;
	margin: 0 auto;
	border-width: 0px;
	border-spacing: 0px;
	border-style: none;
}

td.mainmenu_left_container {
	text-align: left;
	margin-left: auto;
	width: 100%;
}

td.mainmenu_right_container {
	text-align: right;
	margin-right: auto;
	width: 100%;
}

table.mainmenu_left_entries {
	float: left;
	padding: 0;
	margin: 0 auto;
	border-width: 0px;
	border-spacing: 0px;
	border-style: none;
	text-align: left;
}

td.mainmenu_left_entry {
	overflow: hidden;
	white-space: nowrap;
	padding-left: 15px;
	padding-right: 15px;
	border-right: 1px solid #DDD;
	color: #eeeeee;
}

td.mainmenu_left_entry:hover {
	background-color: #3399FF;
}

a.mainmenu_left_entry:link, a.mainmenu_left_entry:visited {
	text-decoration: none;
	font-weight: normal;
	color: #eeeeee;
}

a.mainmenu_left_entry:hover, a.mainmenu_left_entry:active {
	text-decoration: none;
	font-weight: normal;
	color: #cccccc;
}

table.mainmenu_right_entries {
	float: right;
	padding: 0;
	margin: 0 auto;
	border-spacing: 0px;
	border-style: none;
	text-align: right;
}

td.mainmenu_right_entry {
	overflow: hidden;
	white-space: nowrap;
	padding-left: 15px;
	padding-right: 15px;
	border-left: 1px solid #DDD;
	color: #eeeeee;
}

td.mainmenu_right_entry:hover {
	background-color: #3399FF;
}

a.mainmenu_right_entry:link, a.mainmenu_right_entry:visited {
	text-decoration: none;
	font-weight: normal;
	color: #eeeeee;
}

a.mainmenu_right_entry:hover, a.mainmenu_right_entry:active {
	text-decoration: none;
	font-weight: normal;
	color: #cccccc;
}

#wrapper_content {
	float: left;
	width: 100%;
}

div.submenu {
	background-color: #999999;
	float: left;
	padding-bottom: 1000%;
	margin-bottom: -1000%;
	/* overflow: hidden; */
	width: 15%;
	display: block;
	/* margin: 0 auto; */
	text-align: center;
}

a.submenu_link:link, a.submenu_link:visited {
	margin-top: 10px;
	margin-bottom: 10px;
	margin-left: 20px;
	margin-right: 20px;
	display: block;
	overflow: none;
	padding: 5px;
	background-color: #777777;
	color: #eeeeee;
}

a.submenu_link:hover, a.submenu_link:active {
	margin-top: 10px;
	margin-bottom: 10px;
	margin-left: 20px;
	margin-right: 20px;
	display: block;
	overflow: none;
	padding: 5px;
	background-color: #555555;
	color: #eeeeee;
}

a.context_menu_link:link, a.context_menu_link:visited {
	margin-top: 10px;
	margin-bottom: 10px;
	margin-right: 5px;
	margin-left: 5px;
	display: inline-block;
	overflow: none;
	width: 85px;
	padding: 5px;
	background-color: #777777;
	color: #eeeeee;
}

a.context_menu_link:hover, a.context_menu_link:active {
	margin-top: 10px;
	margin-bottom: 10px;
	margin-right: 5px;
	margin-left: 5px;
	display: inline-block;
	overflow: none;
	width: 85px;
	padding: 5px;
	background-color: #555555;
	color: #eeeeee;
}

/**
 * 	END OF:
 * 
 *  - Menus
 * 
 */


/**
 * 	BEGIN OF:
 * 
 *  - Search Bar
 * 
 */

#searchbar {
	display: block;
	float: right;
	vertical-align: middle;
	height: 24px;
	line-height: 24px;
}

input.searchbar {
	background-color: white;
	border: 0;
	margin-left: 5px;
}

input.searchbar::-webkit-input-placeholder {
    color: #444;
}
input.searchbar::-moz-placeholder {
    color: #444;
}
input.searchbar:-moz-placeholder {
    color: #444;
}
input.searchbar:-ms-input-placeholder {
    color: #444;
}

img.searchbutton {
	/* padding-top: 3px; */
	margin-right: 14px;
	vertical-align: middle;
}

/**
 *  END OF:
 * 
 *  - Search Bar
 * 
 */


/**
 *  BEGIN OF:
 * 
 *  - CRUD
 * 
 */

/* jQuery validate error style */
.error {
	background: rgba(255, 0, 0, 0.2) !important;
}

/*
.checked {
	background: rgba(0, 255, 0, 0.2) !important;
}
.valid {
	background: rgba(0, 255, 0, 0.2) !important;
}
*/

/* No data */
p.no_results, p.no_charts, p.no_searches, p.no_groups, p.no_mainmenu {
	color: #777777;
	display: table;
	margin: 0 auto;
	font-size: 120%;
	font-weight: bold;
	padding-left: 30px;
	padding-right: 30px;
}

/* CRUD contents */
#crud_title, #home_title {
	background-color: #555555;
	min-height: 26px;
	width: 100%;
	padding: 0;
	margin: 0 auto;
}

h1.crud_header, h1.home_header {
	float: left;
	background-color: #555555;
	/* background-color: transparent; */
	padding: 0;
	margin: 0;
	/* border: 0; */
	font-size: 100%;
	font-weight: normal;
	text-align: left;
	color: white;
	padding-left: 15px;
	height: 24px;
	line-height: 24px;
}

a.breadcrumb {
	font-size: 100%;
	font-weight: normal;
	text-align: left;
	color: white;
}

a.breadcrumb:hover {
	text-decoration: underline;
}

#listing_footer {
	display: table;
	margin: 0 auto;
	background-color: #555555;
	width: -moz-calc(100% - 2px);
	width: -webkit-calc(100% - 2px);
	width: -o-calc(100% - 2px);
	width: calc(100% - 2px);
}

h2.crud_warning {
	font-size: 110%;
	text-align: center;
	color: #FF0000;
}

#total_items, div.total_items {
	float: left;
}

#total_items span {
	display: block;
	background-color: #555555;
	color: white;
	padding-top: 3px;
	padding-bottom: 3px;
	padding-left: 12px;
	padding-right: 12px;
}

#pagination, div.pagination {
	display: block;
	margin: 0;
	padding-top: 3px;
	padding-right: 12px;
	float: right;
	text-align: right;
}

#pagination span {
	margin: 0;
	background-color: #777777;
	color: white;
	font-weight: bold;
	padding-left: 8px;
	padding-right: 8px;
}

#pagination a {
	background-color: #777777;
	color: white;
	font-weight: bold;
	padding-left: 8px;
	padding-right: 8px;
}

#list, div.list, #create, div.create, #edit, div.edit, #remove, div.remove, #result, div.result, #view, div.view, div.search, #search, div.groups, #groups {
    /* background-color: #ffffee; */
    background-color: #ffffff;
	display: table;
	margin: 0 auto;
	width: 85%;
	height: 100%;
	float: right;
}

div.export {
	display: table;
	margin: 0px auto;
	text-align: center;
}

div.view_ops, div.edit_ops, div.remove_ops, div.create_ops, div.search_ops, div.search_save_ops, div.import_csv_ops {
	display: table;
	margin: 0px auto;
	text-align: center;
	padding-top: 10px;
	padding-bottom: 90px;
}

img.confirm_op {
	margin-right: 10px;
}

img.cancel_op {
	margin-left: 10px;
}

img.expand_op {
	margin-left: 20px;
}

div.more_op {
	display: table;
	margin: 0 auto;
	padding-top: 10px;
}

img.create_op_icon {
	display: inline-block;
	margin: 0 auto;
	padding: 0;
	width: 20px;
	height: 20px;
}

div.fields_basic, div.listing, div.charts {
	display: table;
	margin: 0 auto;
}

fieldset.fields_basic_fieldset {
	padding: 30px;
	margin-top: 20px;
	margin-bottom: 20px;
	margin-left: 15px;
	margin-right: 15px;
	text-align: left;
	float: left;
	background: rgba(225,225,225,0.2);
	border: 1px double #555;
	min-width: 250px;
}

legend.fields_basic_legend {
	font-weight: bold;
	/* display: inline; */ /* This will fix the rounded corners bug in IE 9 and 10, but repositioning with
							* position: relative is required to adjust the legend back to it's original position.
							*/
}

table.list, table.create, table.edit, table.remove, table.result, table.export, table.search, table.view, table.fields {
	padding: 0;
	border-spacing: 1px;
	vertical-align: middle;
	width: 100%;
}

th.list, th.create, th.edit, th.remove, th.result, th.export, th.search, th.view, th.fields {
	background-color: #777777;
	color: white;
	font-weight: bold;
	padding-top: 5px;
	padding-left: 5px;
	padding-right: 5px;
	padding-bottom: 8px;
	text-align: center;
}

th.list:hover, th.create:hover, th.edit:hover, th.remove:hover, th.result:hover, th.export:hover, th.search:hover, th.view:hover, th.fields:hover {
	background-color: #999999;
	color: white;
	font-weight: bold;
	font-weight: bold;
	padding-top: 5px;
	padding-left: 5px;
	padding-right: 5px;
	padding-bottom: 8px;
	text-align: center;
}

a.list_header_grouping_button {
	display: inline-block;
	float: right;
}

img.list_header_grouping_button {
	padding: 0;
	margin: 0 auto;
}

th a {
	text-decoration: none;
}

th a:link, th a:visited {
	color: #eeeeee;
}

th a:hover, th a:focus {
	color: #cccccc;
}

tr.list_even, tr.result_even, tr.export_even, tr.field_even {
	background-color: #dedede;
	padding: 5px;
}

tr.list_odd, tr.result_odd, tr.export_odd, tr.field_odd {
	background-color: #bebebe;
}

tr.list_green, tr.result_green, tr.export_green {
	background: rgba(0, 255, 0, 0.2);
}

tr.list_yellow, tr.result_yellow, tr.export_yellow {
	background: rgba(255, 255, 0, 0.2);
}

tr.list_red, tr.result_red, tr.export_red {
	background: rgba(255, 0, 0, 0.2);
}

tr.list_orange, tr.result_orange, tr.export_orange {
	background: rgba(255, 165, 0, 0.2);
}

tr.list_blue, tr.result_blue, tr.export_blue {
	background: rgba(0, 0, 255, 0.2);
}

tr.list_black, tr.result_black, tr.export_black {
	background: rgba(0, 0, 0, 0.2);
}

tr.list_green:hover, tr.result_green:hover {
	background: rgba(0, 255, 0, 0.35);
}

tr.list_yellow:hover, tr.result_yellow:hover {
	background: rgba(255, 255, 0, 0.35);
}

tr.list_red:hover, tr.result_red:hover {
	background: rgba(255, 0, 0, 0.35);
}

tr.list_orange:hover, tr.result_orange:hover {
	background: rgba(255, 165, 0, 0.35);
}

tr.list_black:hover, tr.result_black:hover {
	background: rgba(0, 0, 0, 0.35);
}

tr.list_blue:hover, tr.result_blue:hover {
	background: rgba(0, 0, 255, 0.35);
}

tr.list_even:hover, tr.result_even:hover, tr.field_even:hover, tr.list_odd:hover, tr.result_odd:hover, tr.field_odd:hover {
	background-color: #aaaaaa;
}

td.field_name {
	vertical-align: middle;
	padding: 5px;
	white-space: nowrap;
}

td.list, td.result, td.export, td.field_value {
	vertical-align: middle;
	padding: 5px;
}

td.list_op {
	padding-left: 5px;
	padding-right: 5px;
}

div.list_container {
	display: table;
	margin: 0 auto;
	width: 100%;
}

div.fields {
	display: table;
	margin: 0 auto;
}

p.submit {
	text-align: center;
}

div.search_criteria_fields, div.search_result_fields, div.search_fields {
	background-color: transparent;
	display: table;
	margin: 0 auto;
	width: 100%;
/*	padding-bottom: 30px; */
	text-align: center;
}

div.search_criteria_fields_inner, div.search_result_fields_inner, div.search_fields_inner {
	background-color: transparent;
	display: table;
	margin: 0 auto;
	width: 100%;
	text-align: center;
}

div.search_criteria_field, div.search_result_field {
	background-color: #acacac;
	display: inline-block;
	width: 200px;
	margin: 5px;
}

div.search_criteria_field input[type="checkbox"], div.search_result_field input[type="checkbox"] {
	visibility: hidden;
}

div.search_criteria_label, div.search_result_label {
	display: inline-block;
	text-align: center;
	padding-right: 10px;
}

table.search_criteria_field_table, table.search_result_field_table {
	border: 0;
	margin: 0;
	padding: 0;
	width: 100%;
}

td.search_field_name {
	padding-right: 15px;
}

td.search_field_value {
	
}

a.search_options_link:link, a.search_options_link:visited {
	display: inline-block;
	padding-top: 3px;
	padding-bottom: 5px;
	padding-left: 10px;
	padding-right: 10px;
	margin-top: 2px;
	margin-bottom: 3px;
	background-color: #777777;
	color: #eeeeee;
	vertical-align: middle;
}

a.search_options_link:hover, a.search_options_link:active {
	display: inline-block;
	overflow: none;
	padding-top: 3px;
	padding-bottom: 5px;
	padding-left: 10px;
	padding-right: 10px;
	margin-top: 2px;
	margin-bottom: 3px;
	background-color: #555555;
	color: #eeeeee;
	vertical-align: middle;
}

img.search_options_icon {
	border: 0;
	vertical-align: middle;
}

fieldset.search_fields_fieldset {
	padding: 30px;
	margin-bottom: 20px;
	display: table;
	margin: 0 auto;
	width: 93%;
	text-align: left;
	float: left;
	border: 1px double #555;
}

fieldset.search_criteria_fieldset, fieldset.search_result_fieldset {
	padding: 30px;
	margin-bottom: 20px;
	display: table;
	margin: 0 auto;
	width: 93%;
	text-align: left;
	float: left;
	border: 1px double #777;
}

legend.search_criteria_legend, legend.search_fields_legend, legend.search_result_legend {
	font-weight: bold;
	/* display: inline; */ /* This will fix the rounded corners bug in IE 9 and 10, but repositioning with
							* position: relative is required to adjust the legend back to it's original position.
							*/
}

div.search_save {
	display: table;
	margin: 0 auto;
}

div.search_saved_block {
	display: inline-block;
	padding: 15px;
	background-color: #777777;
	color: #eeeeee;
	margin: 20px;
}

span.search_saved_link {
	display: inline-block;
	vertical-align: middle;
	margin-bottom: 5px;
	margin-right: 10px;
}

span.saved_search_ops {
	display: inline-block;
	vertical-align: middle;
	margin-top: 2px;
}

a.search_saved_link:link, a.search_saved_link:visited {
	vertical-align: middle;
	overflow: none;
	background-color: #777777;
	color: #eeeeee;
}

a.search_saved_link:hover, a.search_saved_link:active {
	vertical-align: middle;
	overflow: none;
	background-color: #777777;
	color: #eeeeee;
}

/* BEGIN: Search data view */
div[class^=search_criteria_checkbox_] {
	display: inline-block;
	float: right;
	margin-right: 10px;
}

[class^=search_criteria_checkbox_] {
	width: 70px;
	height: 20px;
	background: #333;
	margin: 5px auto;
	position: relative;
}

[class^=search_criteria_checkbox_]:after {
	content: '<?=filter_css_str(NDPHP_LANG_MOD_STATUS_OFF, $config['charset'])?>';
	font: 12px/18px Arial, sans-serif;
	top: 1px;
	color: #000;
	position: absolute;
	right: 10px;
	z-index: 0;
	font-weight: bold;
	text-shadow: 1px 1px 0px rgba(255,255,255,.15);
}

[class^=search_criteria_checkbox_]:before {
	content: '<?=filter_css_str(NDPHP_LANG_MOD_STATUS_ON, $config['charset'])?>';
	font: 12px/18px Arial, sans-serif;
	top: 1px;
	color: #1d84c7;
	position: absolute;
	left: 10px;
	z-index: 0;
	font-weight: bold;
}

[class^=search_criteria_checkbox_] label {
	display: block;
	width: 28px;
	height: 14px;
	-webkit-transition: all .4s ease;
	-moz-transition: all .4s ease;
	-o-transition: all .4s ease;
	-ms-transition: all .4s ease;
	transition: all .4s ease;
	cursor: pointer;
	position: absolute;
	top: 3px;
	left: 3px;
	z-index: 1;
	background: #fcfff4;
}

[class^=search_criteria_checkbox_] input[type=checkbox]:checked + label {
	left: 38px;
}

div[class^=search_result_checkbox_] {
	display: inline-block;
	float: right;
	margin-right: 10px;
}

[class^=search_result_checkbox_] {
	width: 70px;
	height: 20px;
	background: #333;
	margin: 5px auto;
	position: relative;
}

[class^=search_result_checkbox_]:after {
	content: '<?=filter_css_str(NDPHP_LANG_MOD_STATUS_OFF, $config['charset'])?>';
	font: 12px/18px Arial, sans-serif;
	top: 1px;
	color: #000;
	position: absolute;
	right: 10px;
	z-index: 0;
	font-weight: bold;
	text-shadow: 1px 1px 0px rgba(255,255,255,.15);
}

[class^=search_result_checkbox_]:before {
	content: '<?=filter_css_str(NDPHP_LANG_MOD_STATUS_ON, $config['charset'])?>';
	font: 12px/18px Arial, sans-serif;
	top: 1px;
	color: #1d84c7;
	position: absolute;
	left: 10px;
	z-index: 0;
	font-weight: bold;
}

[class^=search_result_checkbox_] label {
	display: block;
	width: 28px;
	height: 14px;
	-webkit-transition: all .4s ease;
	-moz-transition: all .4s ease;
	-o-transition: all .4s ease;
	-ms-transition: all .4s ease;
	transition: all .4s ease;
	cursor: pointer;
	position: absolute;
	top: 3px;
	left: 3px;
	z-index: 1;
	background: #fcfff4;
}

[class^=search_result_checkbox_] input[type=checkbox]:checked + label {
	left: 38px;
}

div[id^=search_cond_button_field_] {
	display: inline-block;
}

div[id^=search_cond_field_] {
	display: inline-block;
	vertical-align: middle;
}

div[id^=search_field_] {
	background-color: #999999;
	display: table;
	margin: 0 auto;
	margin-top: 15px;
	margin-bottom: 15px;
	padding-left: 20px;
	padding-right: 20px;
	padding-top: 5px;
	padding-bottom: 5px;
	text-align: center;
	vertical-align: middle;
}

/* END: Search data view */

div.import_csv {
	display: table;
	margin: 0 auto;
}

fieldset.create_mixed_fieldset, fieldset.edit_mixed_fieldset, fieldset.view_mixed_fieldset, fieldset.remove_mixed_fieldset {
	padding: 30px;
	margin-bottom: 20px;
	margin-left: 15px;
	margin-right: 15px;
	min-width: 250px;
	text-align: left;
	float: left;
	background: rgba(225,225,225,0.2);
	border: 1px double #555;
}

legend.create_mixed_legend, legend.edit_mixed_legend, legend.view_mixed_legend, legend.remove_mixed_legend {
	font-weight: bold;
}

#mixed_relationships {
	display: table;
	margin: 0 auto;
}

fieldset.create_multiple_fieldset, fieldset.edit_multiple_fieldset, fieldset.view_multiple_fieldset, fieldset.remove_multiple_fieldset {
	padding: 30px;
	margin-bottom: 20px;
	margin-left: 15px;
	margin-right: 15px;
	min-width: 250px;
	text-align: left;
	float: left;
	background: rgba(225,225,225,0.2);
	border: 1px double #555;
}

legend.create_multiple_legend, legend.edit_multiple_legend, legend.view_multiple_legend, legend.remove_multiple_legend {
	font-weight: bold;
}

/**
 *  END OF:
 * 
 *  - CRUD
 * 
 */


/**
 *  BEGIN OF:
 * 
 *  - Charts
 * 
 */

div.chart {
	display: inline-block;
}

/**
 *  END OF:
 * 
 *  - Charts
 * 
 */


/**
 *
 *  BEGIN OF:
 *
 *  - REGISTER
 *
 */

body.register {
	white-space: nowrap;
	font-family: helvetica, verdana, arial, sans-serif;
	font-size: 80%;
}

div.registerform {
	background-color: white;
	padding: 50px;
	margin: 0 auto;
	margin-top: 10%;
	display: table;
}

div.register_actions {
	text-align: center;
}

#register input[type="text"], #register input[type="password"] {
	width:270px;
	font-size: 14px;
	border:1px solid #aaa;
	padding:5px 5px 5px 3px;
	border-radius:4px;
	background: #fafafa;
	outline:none;
	display: inline-block;
	-webkit-appearance:none;
	position:relative;
}

#register select {
	width: 280px;
}

a.register_button_link:link, a.register_button_link:visited {
	margin-top: 10px;
	margin-bottom: 10px;
	margin-right: 5px;
	margin-left: 5px;
	display: inline-block;
	overflow: none;
	width: 85px;
	padding: 5px;
	background-color: #777777;
	color: #eeeeee;
}

a.register_button_link:hover, a.register_button_link:active {
	margin-top: 10px;
	margin-bottom: 10px;
	margin-right: 5px;
	margin-left: 5px;
	display: inline-block;
	overflow: none;
	width: 85px;
	padding: 5px;
	background-color: #555555;
	color: #eeeeee;
}

/**
 * 	BEGIN OF:
 * 
 *  - LOGIN
 * 
 */

html.login {
	background-color: #999999;
}

body.login {
	font-family: helvetica, verdana, arial, sans-serif;
	font-size: 80%;
}

div.login_logo {
	background-color: #eeeeee;
	padding-left: 50px;
	padding-right: 50px;
	padding-top: 30px;
	padding-bottom: 20px;
	width: 220px;
	margin: 0 auto;
	margin-top: 3%;
	display: table;
	text-align: center;
	border-radius: 5px;
}

img.login_logo {
	margin: 0 auto;
	display: table;
}

span.login_logo_project_name {
	display: block;
	margin-top: 5px;
	font-size: 150%;
	font-weight: bold;
	color: #555555;
}

span.login_logo_tagline {
	display: block;
	font-size: 100%;
	font-weight: bold;
	color: #777777;
}

div.loginform {
	background-color: #eeeeee;
	padding: 50px;
	width: 220px;
	margin: 0 auto;
	margin-top: 3%;
	display: table;
	border-radius: 5px;
}

div.login_actions {
	text-align: center;
}

span.login_username, span.login_password, span.login_extra_field {
	font-weight: bold;
}

#login input[type="text"], #login input[type="password"] {
	width:210px;
	font-size: 14px;
	border:1px solid #aaa;
	padding:5px 5px 5px 3px;
	border-radius:4px;
	background: #fafafa;
	outline:none;
	display: inline-block;
	-webkit-appearance:none;
	position:relative;
}

a.login_button_link:link, a.login_button_link:visited {
	margin-top: 10px;
	margin-bottom: 10px;
	margin-right: 5px;
	margin-left: 5px;
	display: inline-block;
	overflow: none;
	width: 85px;
	padding: 5px;
	background-color: #777777;
	color: #eeeeee;
}

a.login_button_link:hover, a.login_button_link:active {
	margin-top: 10px;
	margin-bottom: 10px;
	margin-right: 5px;
	margin-left: 5px;
	display: inline-block;
	overflow: none;
	width: 85px;
	padding: 5px;
	background-color: #555555;
	color: #eeeeee;
}

/**
 *  END OF:
 * 
 *  - Login
 * 
 */

/**
 * BEGIN OF:
 *
 *  - Roles
 *  
 */

div.setup_role {
	padding-top: 50px;
	padding-bottom: 50px;
	display: table;
	margin: 0 auto;
}

fieldset.setup_role {
	padding: 30px;
	margin-bottom: 20px;
	text-align: left;
	float: left;
	background: rgba(95,95,95,0.4);
	border: 1px double #777;
}

legend.setup_role {
	font-weight: bold;
}

tr.setup_role_table_head, tr.setup_role_column_head {
	font-weight: bold;
}

/**
 * END OF:
 *
 *  - Roles
 *  
 */

/* Payments */
#payments_paypal input[type="text"], #payments_paypal input[type="number"] {
	width:65px;
	font-size: 14px;
	border:1px solid #aaa;
	padding:5px 5px 5px 3px;
	border-radius:4px;
	background: #fafafa;
	outline:none;
	display: inline-block;
	-webkit-appearance:none;
	position:relative;
}

div.payments_paypal {
    /* background-color: #ffffee; */
    background-color: #ffffff;
    padding-top: 15px;
    padding-bottom: 3px;
	display: table;
	margin: 0 auto;
}

fieldset.payments_paypal {
	padding: 30px;
	margin-bottom: 20px;
	margin-left: 15px;
	margin-right: 15px;
	text-align: center;
	float: left;
	background: rgba(225,225,225,0.2);
	border: 1px double #555;
}

legend.payments_paypal {
	font-weight: bold;
}

body.payments_paypal_successful {
	font-family: helvetica, verdana, arial, sans-serif;
	font-size: 80%;
}

div.payments_paypal_successful {
	background-color: white;
	padding: 50px;
	margin: 0 auto;
	margin-top: 15%;
	display: table;
}


/* Payments */
#subscription_types_upgrade select {
	width:230px;
	font-size: 14px;
	text-align: center;
	border:1px solid #aaa;
	padding:5px 5px 5px 3px;
	border-radius:4px;
	background: #fafafa;
	outline:none;
	display: inline-block;
	-webkit-appearance:none;
	position:relative;
}

div.subscription_types_upgrade {
    /* background-color: #ffffee; */
    background-color: #ffffff;
    padding-top: 15px;
    padding-bottom: 3px;
	display: table;
	margin: 0 auto;
}

fieldset.subscription_types_upgrade {
	padding: 30px;
	margin-bottom: 20px;
	margin-left: 15px;
	margin-right: 15px;
	text-align: center;
	float: left;
	background: rgba(225,225,225,0.2);
	border: 1px double #555;
}

legend.subscription_types_upgrade {
	font-weight: bold;
}

div.subscription_types_upgrade_postop {
	background-color: white;
	padding: 50px;
	margin: 0 auto;
	margin-top: 15%;
	display: table;
}

.ui-front {
	z-index: 100000000 !important;
}

/* Confirmation */
fieldset.confirmation_fieldset {
	padding: 30px;
	margin-top: 20px;
	margin-bottom: 20px;
	margin-left: 15px;
	margin-right: 15px;
	text-align: left;
	float: left;
	background: rgba(225,225,225,0.2);
	border: 1px double #555;
}

legend.confirmation_legend {
	font-weight: bold;
	/* display: inline; */ /* This will fix the rounded corners bug in IE 9 and 10, but repositioning with
							* position: relative is required to adjust the legend back to it's original position.
							*/
}

div.confirmation_ops {
	display: table;
	margin: 0px auto;
	text-align: center;
	padding-top: 10px;
	padding-bottom: 10px;
}


/**
 *
 *  BEGIN OF:
 *
 *  - Confirm SMS
 *
 */

body.confirm_sms, body.recover_password {
	background-color: #eeeeee;
	font-family: helvetica, verdana, arial, sans-serif;
	font-size: 80%;
}

div.confirmsmsform, div.recoverpasswordform {
	background-color: white;
	padding: 50px;
	margin: 0 auto;
	margin-top: 10%;
	display: table;
}

div.confirm_sms_actions, div.recover_password_actions {
	text-align: center;
}

#confirm_sms input[type="text"], #confirm_sms input[type="password"], #recover_password input[type="text"], #recover_password input[type="password"] {
	width:250px;
	font-size: 14px;
	border:1px solid #aaa;
	padding:5px 5px 5px 3px;
	background: #fafafa;
	outline:none;
	display: inline-block;
	-webkit-appearance:none;
	position:relative;
}

/** 
 *
 *  END OF:
 *
 *  - Confirm SMS
 *
 */

/**
 *
 *  BEGIN OF:
 *
 *  - Confirm email status
 *
 */

body.confirm_email_status {
	background-color: #eeeeee;
	font-family: helvetica, verdana, arial, sans-serif;
	font-size: 80%;
}

div.confirm_email_status {
	background-color: white;
	padding: 50px;
	margin: 0 auto;
	margin-top: 10%;
	display: table;
}

/** 
 *
 *  END OF:
 *
 *  - Confirm email status
 *
 */


/**
 *
 *  BEGIN OF:
 *
 *  - Grouping
 *
 */

div.group_header {
	background-color: #777777;
	width: 100%;
	margin-top: 5px;
	margin-bottom: 5px;
	overflow: hidden;
}

img.group_header_arrows {
	width: 16px;
	height: 16px;
	display: inline-block;
}

span.group_header_arrows {
	float: left;
	padding-top: 2px;
	padding-right: 10px;
}

div.group_data {
	margin-bottom: 30px;
}

a.group_header_link:link, a.group_header_link:visited {
	display: inline-block;
	padding-left: 15px;
	padding-top: 8px;
	padding-bottom: 8px;
	width: 100%;
	color: white;
}

a.group_header_link:hover, a.group_header_link:active {
	background-color: #999999;
	display: inline-block;
	padding-left: 15px;
	padding-top: 8px;
	padding-bottom: 8px;
	width: 100%;
	color: white;
}

span.group_header_counting {
	text-align: right;
	float: right;
	padding-right: 25px;
}

a.group_link:link, a.group_link:visited {
	text-align: center;
	display: inline-block;
	padding: 15px;
	margin: 20px;
	background-color: #777777;
	color: #eeeeee;
	min-width: 150px;
}

a.group_link:hover, a.group_link:active {
	text-align: center;
	display: inline-block;
	padding: 15px;
	margin: 20px;
	background-color: #999999;
	color: #eeeeee;
	min-width: 150px;
}

/**
 *
 *  END OF:
 *
 *  - Grouping
 *
 */