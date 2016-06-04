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

 ?>
<!DOCTYPE html>
<html>
	<head>
		<title>ND PHP - Overloads Documentation</title>
		<link rel="stylesheet" type="text/css" href="<?=filter_html(static_css_url(), $config['charset'])?>/documentation/overloads.css" />
	</head>
	<body>
		<?php include('navigation.php'); ?>
		<fieldset>
			<legend>INDEX</legend>
			<table>
				<tr>
					<td><strong>Overloads</strong></td>
					<td>
						<a href="#conditional_dropdown_fields">Conditional Drop-Down Fields</a>&nbsp;
						<a href="#dropdown_field_settings">Drop-Down Field Settings</a>&nbsp;
						<a href="#group_concatenation">Group Concatenation</a>&nbsp;
						<a href="#csv_export_settings">CSV Export Settings</a>&nbsp;
						<a href="#mixed_field_aliases">Mixed Field Aliases</a>&nbsp;
						<a href="#mixed_fieldset_legend">Mixed Fieldset Legend</a>&nbsp;
					</td>
				</tr>
			</table>
		</fieldset>
		<br />
		<a name="conditional_dropdown_fields">
		<fieldset>
			<legend>CONDITIONAL DROP-DOWN FIELDS</legend>
			<table>
				<tr>
					<td><strong>ALL VIEWS</strong></td>
					<td>
						&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">protected</span> $<span class="overload">_table_rel_choice_hide_fields</span> = <span class="type">array</span>(<br />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">/* 'field_id' => array(</span><br />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> *&nbsp;&nbsp;&nbsp;&nbsp;1 => array('field_to_hide1', 'field_to_hide2'),</span><br />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> *&nbsp;&nbsp;&nbsp;&nbsp;7 => array('field_to_hide3', 'field_to_hide8'),</span><br />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> *&nbsp;&nbsp;&nbsp;&nbsp;...</span><br />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> * )</span><br />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> */</span><br />
						&nbsp;&nbsp;&nbsp;&nbsp;);
					</td>
				</tr>
				<tr>
					<td><strong>CREATE VIEW</strong></td>
					<td>
						&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">protected</span> $<span class="overload">_table_create_rel_choice_hide_fields</span> = <span class="type">array</span>(<br />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">/* 'field_id' => array(</span><br />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> *&nbsp;&nbsp;&nbsp;&nbsp;1 => array('field_to_hide1', 'field_to_hide2'),</span><br />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> *&nbsp;&nbsp;&nbsp;&nbsp;7 => array('field_to_hide3', 'field_to_hide8'),</span><br />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> *&nbsp;&nbsp;&nbsp;&nbsp;...</span><br />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> * )</span><br />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> */</span><br />
						&nbsp;&nbsp;&nbsp;&nbsp;);
					</td>
				</tr>
				<tr>
					<td><strong>EDIT VIEW</strong></td>
					<td>
						&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">protected</span> $<span class="overload">_table_edit_rel_choice_hide_fields</span> = <span class="type">array</span>(<br />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">/* 'field_id' => array(</span><br />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> *&nbsp;&nbsp;&nbsp;&nbsp;1 => array('field_to_hide1', 'field_to_hide2'),</span><br />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> *&nbsp;&nbsp;&nbsp;&nbsp;7 => array('field_to_hide3', 'field_to_hide8'),</span><br />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> *&nbsp;&nbsp;&nbsp;&nbsp;...</span><br />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> * )</span><br />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> */</span><br />
						&nbsp;&nbsp;&nbsp;&nbsp;);
					</td>
				</tr>
				<tr>
					<td><strong>VISUALIZATION VIEW</strong></td>
					<td>
						&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">protected</span> $<span class="overload">_table_view_rel_choice_hide_fields</span> = <span class="type">array</span>(<br />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">/* 'field_id' => array(</span><br />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> *&nbsp;&nbsp;&nbsp;&nbsp;1 => array('field_to_hide1', 'field_to_hide2'),</span><br />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> *&nbsp;&nbsp;&nbsp;&nbsp;7 => array('field_to_hide3', 'field_to_hide8'),</span><br />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> *&nbsp;&nbsp;&nbsp;&nbsp;...</span><br />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> * )</span><br />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> */</span><br />
						&nbsp;&nbsp;&nbsp;&nbsp;);
					</td>
				</tr>
				<tr>
					<td><strong>REMOVE VIEW</strong></td>
					<td>
						&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">protected</span> $<span class="overload">_table_remove_rel_choice_hide_fields</span> = <span class="type">array</span>(<br />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">/* 'field_id' => array(</span><br />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> *&nbsp;&nbsp;&nbsp;&nbsp;1 => array('field_to_hide1', 'field_to_hide2'),</span><br />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> *&nbsp;&nbsp;&nbsp;&nbsp;7 => array('field_to_hide3', 'field_to_hide8'),</span><br />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> *&nbsp;&nbsp;&nbsp;&nbsp;...</span><br />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> * )</span><br />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> */</span><br />
						&nbsp;&nbsp;&nbsp;&nbsp;);
					</td>
				</tr>
			</table>
		</fieldset>
		<a name="dropdown_field_settings">
		<fieldset>
			<legend>DROP-DOWN FIELD SETTINGS</legend>
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">/* The fields to be concatenated as the options of the relationship table */</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">protected</span> $<span class="overload">_rel_table_fields_config</span> = <span class="type">array</span>(<br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">/* 'table' => array('ViewName', 'separator or NULL', array(field_nr_1, field_nr_2, ...), array('order_by field', 'asc or desc')), */</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;);<br />
		</fieldset>
		<a name="group_concatenation">
		<fieldset>
			<legend>GROUP CONCATENATION</legend>
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">protected</span> $<span class="overload">_group_concat_sep</span> = <span class="literal">' | '</span>;<span class="comment">&nbsp;&nbsp;&nbsp;&nbsp;/* The separator to be used when GROUP_CONCAT() is invoked */</span><br />
		</fieldset>
		<a name="csv_export_settings">
		<fieldset>
			<legend>CSV EXPORT SETTINGS</legend>
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">protected</span> $<span class="overload">_csv_sep</span> = <span class="literal">';'</span>;<span class="comment">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/* Field Separator */</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">protected</span> $<span class="overload">_csv_from_encoding</span> = <span class="literal">'UTF-8'</span>;<span class="comment">&nbsp;&nbsp;/* Expected encoding when processing data to be exported */</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">protected</span> $<span class="overload">_csv_to_encoding</span> = <span class="literal">'UTF-8'</span>;<span class="comment">&nbsp;&nbsp;&nbsp;&nbsp;/* Encoding that will be used when generating the CSV file */</span><br />
		</fieldset>
		<a name="mixed_field_aliases">
		<fieldset>
			<legend>MIXED FIELD ALIASES</legend>
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">/* Field name alias configuration for mixed relationships */</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">protected</span> $<span class="overload">_mixed_table_fields_config</span> = <span class="type">array</span>(<br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">/* 'table' => array(field_nr1 => 'ViewName', field_nr2 => 'ViewName', ...), ...*/</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;);<br />
		</fieldset>
		<a name="mixed_fieldset_legend">
		<fieldset>
			<legend>MIXED FIELDSET LEGEND</legend>
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">/* Fieldset legend aliases for mixed relationships */</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">protected</span> $<span class="overload">_mixed_fieldset_legend_config</span> = <span class="type">array</span>(<br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">/* 'table' => 'legend' */</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;);<br />
		</fieldset>
	</body>
</html>