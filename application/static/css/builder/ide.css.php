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

html {
	min-width: 1280px; /* FIXME: Not responsive... yet :( */
}

body {
	color: #222222;
	height: 100%;
	overflow: auto;
}

select {
	min-width: 300px;
}

textarea {
	min-width: 300px;
}

span {
	padding-top: 5px;
	width: 100%;
	text-align: center;
	margin: 0 auto;
	display: inline-block;
}

legend {
	font-weight: bold;
	margin-left: 10px;
}

fieldset {
	font-family: arial, verdana;
	border-radius: 10px;
}

/** Field settings **/
.dialog_settings {
	display: none;
	font-size: 80%;
}

#options {
	text-align: center;
	margin-bottom: 15px;
}

#constraints {
	text-align: center;
	margin-bottom: 15px;
}

#properties {
	margin-bottom: 15px;
}

#properties span {
	text-align: left;
}

.properties_table {
	margin: 0 auto;
}

#visualization {
	text-align: center;
	margin-bottom: 15px;
}

#permissions {
	text-align: center;
	margin-bottom: 15px;
}

#property_length {
	text-align: center;
	width: 70px;
}

#property_help {
	width: 300px;
}

.properties_table_field_title {
	padding-right: 5px;
}

.visualization_table, .constraints_table, .options_table {
	text-align: center;
	margin: 0 auto;
	border: 1px dotted #aaaaaa;

}
.visualization_table_head_field, .constraints_table_head_field, .options_table_head_field {
	padding-left: 15px;
	padding-right: 15px;
}

.visualization_table_field, .constraints_table_field, .options_table_field {
	border: 1px dotted #aaaaaa;
	margin: 0 auto;
	text-align: center;
}

/** Pool Elements **/
.pool {
	border: 1px solid #aaaaaa;
	display: block;
	-webkit-box-shadow: 8px 8px 8px -8px black;
	   -moz-box-shadow: 8px 8px 8px -8px black;
	        box-shadow: 8px 8px 8px -8px black;
}

.pool_fields {
	background: repeating-linear-gradient(
		-45deg,
		rgba(221,204,85,0.2),
		rgba(221,204,85,0.2) 10px,
		rgba(204,204,204,0.2) 10px,
		rgba(204,204,204,0.2) 20px
	);
	padding: 0;
	width: 125px;
	min-height: 725px;
	margin-top: 15px;
	margin-left: 20px;
	margin-right: 15px;
	float: left;
}

.pool_menu {
	background: repeating-linear-gradient(
		-45deg,
		rgba(85,153,204,0.1),
		rgba(85,153,204,0.1) 10px,
		rgba(204,204,204,0.1) 10px,
		rgba(204,204,204,0.1) 20px
	);
	padding: 0;
	height: 85px;
	min-width: 800px;
	width: 85%;
	margin: 0 auto;
}

/** Container Elements **/
.container {
	width: 350px;
	height: 70px;
	padding: 10px;
	display: block;
	border: 1px solid #aaaaaa;
	-webkit-box-shadow: 7px 7px 7px -7px gray;
	   -moz-box-shadow: 7px 7px 7px -7px gray;
	        box-shadow: 7px 7px 7px -7px gray;
}

.trash {
	background: repeating-linear-gradient(
		45deg,
		rgba(123,123,123,0.5),
		rgba(123,123,123,0.5) 10px,
		rgba(204,204,204,0.5) 10px,
		rgba(204,204,204,0.5) 20px
	);
	padding: 0;
	height: 85px;
	width: 125px;
	float: left;
	margin-left: 20px;
	margin-right: 15px;
	-webkit-box-shadow: 8px 8px 8px -8px black;
	   -moz-box-shadow: 8px 8px 8px -8px black;
	        box-shadow: 8px 8px 8px -8px black;
}

.menu {
	background: repeating-linear-gradient(
		-45deg,
		rgba(85,153,204,0.1),
		rgba(85,153,204,0.1) 10px,
		rgba(204,204,204,0.1) 10px,
		rgba(204,204,204,0.1) 20px
	);
	padding: 0;
	width: 90%;
	height: 150px;
	margin: 0 auto;
	margin-top: 30px;
	overflow-y: auto;
}

.fields {
	background: repeating-linear-gradient(
		-45deg,
		rgba(221,204,85,0.2),
		rgba(221,204,85,0.2) 10px,
		rgba(204,204,204,0.2) 10px,
		rgba(204,204,204,0.2) 20px
	);
	padding: 0;
	width: 90%;
	min-height: 398px;
	margin: 0 auto;
	margin-top: 30px;
	/*margin-bottom: 30px;*/
	overflow-y: auto;
	display: none;
}

.controller {
	background: repeating-linear-gradient(
		-45deg,
		rgba(221,204,85,0.2),
		rgba(221,204,85,0.2) 10px,
		rgba(204,204,204,0.2) 10px,
		rgba(204,204,204,0.2) 20px
	);
	padding: 0;
	width: 90%;
	min-height: 398px;
	margin: 0 auto;
	margin-top: 30px;
	overflow-y: auto;
	display: none;
}

.canvas {
	background: rgba(204,204,204,0.2);
	padding: 0;
	min-width: 800px;
	min-height: 725px;
	width: 85%;
	display: block;
	border: 1px solid #aaaaaa;
	margin: 0 auto;
	margin-top: 15px;
	-webkit-box-shadow: 8px 8px 8px -8px black;
	   -moz-box-shadow: 8px 8px 8px -8px black;
	        box-shadow: 8px 8px 8px -8px black;
}

#canvas input {
	text-align: center;
}

/** Draggable objects **/
.object {
	padding: 0;
	width:110px;
	height:50px;
	margin: 0 auto;
	background-color: #cccccc;
	display: inline-block;
	margin-left: 8px;
	margin-top: 2px;
	margin-bottom: 8px;
	cursor: grab;
	border-radius: 10px;
	-webkit-box-shadow: 0 8px 6px -6px black;
	   -moz-box-shadow: 0 8px 6px -6px black;
	        box-shadow: 0 8px 6px -6px black;
	overflow: hidden;
}

.title {
	font-size: 95%;
	font-weight: bold;
	padding: 0;
	padding-top: 3px;
	padding-bottom: 3px;
	border-top-left-radius: 10px;
	border-top-right-radius: 10px;
}

.menu_obj {
	background-color: #5599CC;	
}

.field_obj {
	background-color: #DDCC55;
}

.separator_obj {
	background-color: #CCDD55;
}

#name {
	font-style: italic;
	font-size: 70%;
}

#name_edit {
	display: none;
	width: 100px;
}

.selected {
	background-color: #ccddee;
}

/* Actions */
.button_action {
	display: inline-block;
	margin: 0 auto;
	margin-top: 30px;
	padding: 5px 20px 5px 20px;
	font-weight: bold;
}

.button_cfg_tooltip {
	display: inline-block;
	font-size: 80%;
	margin-top: 12px;
	margin-right: 10px;
	padding: 5px 20px 5px 20px;
	font-weight: bold;
	float: right;
}
#actions {
	display: table;
	margin: 0 auto;
	float: center;
	margin-bottom: 30px;
}

.ide_code_editarea {
	width: 100%;
	height: 370px;
	padding-left: 6px;
}