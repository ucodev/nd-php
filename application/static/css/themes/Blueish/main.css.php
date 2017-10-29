<?php header('content-type: text/css'); ?>

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
 * Generics / Overrides
 *
 */

a, .btn {
	outline: none;
}

/**
 * BEGIN OF:
 *
 *  - Login
 *
 */

.login {
	margin-left: 10px;
	margin-right: 10px;
	padding-left: 10px;
	padding-right: 10px;
}

.login_header {
	padding-top: 15px;
	padding-bottom: 40px;
	display: table;
	margin: 0 auto;
}

.login_logo {
	padding-bottom: 5px;
}

.login_project_name {
	font-size: 120%;
	font-weight: bold;
	display: block;
	text-align: center;
}

.login_project_tagline {
	font-size: 90%;
	display: block;
	text-align: center;
}

/**
 *  BEGIN OF:
 * 
 *  - General Styles
 * 
 */

#header {
	height: 50px;
}

.logo {
	height: 40px;
	width: auto;
	padding-top: 8px;
	padding-right: 5px;
	padding-left: 5px;
	vertical-align: middle;
}

.project_name {
	font-size: 160%;
	font-style: italic;
	color: #1d84c7;
	vertical-align: middle;
	padding-right: 5px;
}

.project_tagline {
	font-size: 110%;
	color: #706F70;
	vertical-align: middle;
}

#session_info {
	padding-top: 8px;
	padding-right: 10px;
	float: right;
}

.session_info_user_name {
	margin-left: 5px;
	margin-right: 5px;	
}

.session_info_user_photo {
	width: auto;
	height: 32px;
}

#browsing_actions {
	top: 0;
	right: 0;
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

#body {
	margin-left: 10px;
	margin-right: 10px;
	padding-left: 10px;
	padding-right: 10px;
}

.home_body {
	padding-top: 50px;
	padding-bottom: 50px;
	display: table;
	margin: 0 auto;
}

.home_result {
	padding-top: 50px;
	padding-bottom: 50px;
}

.home_menu_entry {
	padding: 25px 25px 25px 25px;
	text-align: center;
	display: inline-block;
}

.home_menu_entry_legend {
	/* display: none; */
}

p.no_results, p.no_charts, p.no_searches, p.no_groups, p.no_mainmenu {
	color: #777777;
	display: table;
	margin: 0 auto;
	font-size: 120%;
	font-weight: bold;
	padding-left: 30px;
	padding-right: 30px;
	padding-top: 50px;
	padding-bottom: 50px;
}

#crud_title, #home_title {	/* AKA Breadcrumb */
	margin-top: 20px;
}

.searchbar {
	margin-bottom: 20px;
}

.submenu {
	float: right;
}


.list_op, .mixed_op {
	text-align: right;
}

#total_items {
	text-align: right;
}

#pagination {
	padding: 0;
	display: table;
	margin: 0 auto;
}


.form_fieldset {
	margin-top: 20px;
}

.form-group.required .control-label:before {
  content:"*";
  color:red;
}

.search_criteria_fields, .search_fields, .search_result_fields {
	display: table;
	margin: 0 auto;
}

#footer {

}

#copy {
	font-size: 90%;
	text-align: center;
}

#powered_by {
	font-size: 80%;
	text-align: right;
}


/** Customizations **/
.valign > [class*="col"] {
  display: inline-block;
  float: none;
  vertical-align: middle;
}
