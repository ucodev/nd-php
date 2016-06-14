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
					<td><strong>Drop-Down Overloads</strong></td>
					<td>
						<a href="#conditional_hidden_dropdown_fields">Conditional Hidden Drop-Down Fields</a>&nbsp;
						<a href="#conditional_style_dropdown_fields">Conditional Style Drop-Down Fields</a>&nbsp;
						<a href="#dropdown_field_settings">Drop-Down Field Settings</a>&nbsp;
					</td>
				</tr>
				<tr>
					<td><strong>Concatenation Overloads</strong></td>
					<td>
						<a href="#group_concatenation">Group Concatenation</a>&nbsp;
					</td>
				</tr>
				<tr>
					<td><strong>Export Overloads</strong></td>
					<td>
						<a href="#csv_export_settings">CSV Export Settings</a>&nbsp;
					</td>
				</tr>
				<tr>
					<td><strong>Mixed Relationship Overloads</strong></td>
					<td>
						<a href="#mixed_field_aliases">Mixed Field Aliases</a>&nbsp;
						<a href="#mixed_fieldset_legend">Mixed Fieldset Legend</a>&nbsp;
						<a href="#mixed_missing_entry_handlers">Mixed Missing Entry Handlers</a>&nbsp;
					</td>
				</tr>
				<tr>
					<td><strong>File Upload Overloads</strong></td>
					<td>
						<a href="#file_upload_configuration">File Upload Configuration</a>&nbsp;
					</td>
				</tr>
				<tr>
					<td><strong>Custom Controller Overloads</strong></td>
					<td>
						<a href="#custom_controller_setup">Custom Controller Setup</a>&nbsp;
					</td>
				</tr>
			</table>
		</fieldset>
		<br />
		<a name="conditional_hidden_dropdown_fields">
		<fieldset>
			<legend>CONDITIONAL HIDDEN DROP-DOWN FIELDS</legend>
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
		<a name="conditional_style_dropdown_fields">
		<fieldset>
			<legend>CONDITIONAL STYLE DROP-DOWN FIELDS</legend>
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">/* Set a custom class for table row based on single relationship field values (Drop-Down).</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> * Any class specified here must exist in a loaded CSS, with the following prefixes:</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> *</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> *&nbsp;&nbsp;&nbsp;list_&lt;class_suffix_name&gt;</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> *&nbsp;&nbsp;&nbsp;result_&lt;class_suffix_name&gt;</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> *&nbsp;&nbsp;&nbsp;export_&lt;class_suffix_name&gt;</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> *</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> * Example:</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> *</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> *&nbsp;&nbsp;&nbsp;tr.list_even,&nbsp;&nbsp;&nbsp;tr.result_even,&nbsp;&nbsp;&nbsp;tr.export_even&nbsp;&nbsp;&nbsp;{ ... }</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> *&nbsp;&nbsp;&nbsp;tr.list_odd,&nbsp;&nbsp;&nbsp;tr.result_odd,&nbsp;&nbsp;&nbsp;tr.export_odd&nbsp;&nbsp;&nbsp;{ ... }</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> *&nbsp;&nbsp;&nbsp;tr.list_red,&nbsp;&nbsp;&nbsp;tr.result_red,&nbsp;&nbsp;&nbsp;tr.export_red&nbsp;&nbsp;&nbsp;{ ... }</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> *&nbsp;&nbsp;&nbsp;tr.list_yellow,&nbsp;&nbsp;&nbsp;tr.result_yellow,&nbsp;&nbsp;&nbsp;tr.export_yellow&nbsp;&nbsp;&nbsp;{ ... }</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> *&nbsp;&nbsp;&nbsp;tr.list_green,&nbsp;&nbsp;&nbsp;tr.result_green,&nbsp;&nbsp;&nbsp;tr.export_green&nbsp;&nbsp;&nbsp;{ ... }</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> *</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> * NOTE: The :hover modifier should also be set for list_ and result_ classes.</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> *</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> * There are already some predefined classes in main.css: odd, even, green, red, yellow, blue, orange and black.</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> *</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> */</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">protected</span> $<span class="overload">_table_row_choice_class</span> = <span class="type">array</span>(<br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">/*</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">'rel_field' =&gt; 'field_id',</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">'class_even' =&gt; 'even',</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">'class_odd' =&gt; 'odd',</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">'values' =&gt; array(</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">'CRITICAL' =&gt; 'red',</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">'WARNING' =&gt; 'yellow',</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">'OK' =&gt; 'green'</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">)</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">*/</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;);<br />
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
		<a name="mixed_missing_entry_handlers">
		<fieldset>
			<legend>MIXED MISSING ENTRY HANDLERS</legend>
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">/* If set to true, inserts user values into the foreign table if they do not exist.</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> * Also, if set to true, this option will cause the framework to ignore the $_mixed_table_set_missing settings.</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> */</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">protected</span> $<span class="overload">_mixed_table_add_missing</span> = <span class="literal">true</span>;<br />
			<br />
			<br />
			<br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">/* When a mixed entry does not belong to any table row of the foreign table associated to the mixed relationship to</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> * be inserted or updated (this is, when a &lt;foreign table&gt;_id value is missing due to autocompletion is disabled or</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> * wasn't used by the user), we can force a default foreign table id value to be set, on a per-foreign-table basis.</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> * </span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> * The framework will check the following array for any default id value set for the foreign table in case of</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> * such id is missing when inserting or updating mixed relationship entries.</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> * </span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"> */</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">protected</span> $<span class="overload">_mixed_table_set_missing</span> = <span class="type">array</span>(<br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">/* 'table' => id */</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;);<br />
			<br />
			<br />
			<br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">/* Enable (set to true) or Disable (set to false) mixed relationships create/edit views autocompletation */</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">protected</span> $<span class="overload">_mixed_views_autocomplete</span> = <span class="literal">true</span>;<br />
			<br />
			<br />
			<br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">/* Ajust the views of mixed relationship field widths (forced on element style= attribute) */</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">protected</span> $<span class="overload">_mixed_table_fields_width</span> = <span class="type">array</span>(<br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">/*</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">'field1' => '32px',</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">'field2' => '250px'</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">*/</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;);<br />
		</fieldset>
		<a name="file_upload_configuration">
		<fieldset>
			<legend>FILE UPLOAD CONFIGURATION</legend>
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">/* If set to true, uploaded files will be stored encrypted */</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">protected</span> $<span class="overload">_encrypted_uploaded_files</span> = <span class="literal">true</span>;<br />
			<br />
			<br />
			<br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">/* Upload max file size */</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">protected</span> $<span class="overload">_upload_max_file_size</span> = <span class="literal">10485760</span>; <span class="comment">/* 10MiB by default */</span><br />
		</fieldset>
		<a name="custom_controller_setup">
		<fieldset>
			<legend>CUSTOM CONTROLLER SETUP</legend>
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">/* If this controller is associated to a DATABASE VIEW instead of a DATABASE TABLE, set the following variable to true */</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">protected</span> $<span class="overload">_table_type_view</span> = <span class="literal">false</span>;<br />
			<br />
			<br />
			<br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">/* The query that will generate the view (requires $_table_type_view set to true) */</span><br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">protected</span> $<span class="overload">_table_type_view_query</span> = <span class="literal">''</span>; <span class="comment">/* Eg: 'SELECT * FROM users WHERE id > 1' */</span><br />
		</fieldset>
	</body>
</html>