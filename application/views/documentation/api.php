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
		<title>ND PHP - API Documentation</title>
		<link rel="stylesheet" type="text/css" href="<?=filter_html(static_css_url(), $config['charset'])?>/documentation/api.css" />
	</head>
	<body>
		<?php include('navigation.php'); ?>
		<fieldset>
			<legend>INDEX</legend>
			<table>
				<tr>
					<td><strong>Functions</strong></td>
					<td>
						<a href="#list">List</a>&nbsp;
						<a href="#search">Search</a>&nbsp;
						<a href="#result">Result</a>&nbsp;
						<a href="#export">Export</a>&nbsp;
						<a href="#create">Create</a>&nbsp;
						<a href="#edit">Edit</a>&nbsp;
						<a href="#view">View</a>&nbsp;
						<a href="#remove">Remove</a>&nbsp;
						<a href="#groups">Groups</a>&nbsp;
						<a href="#insert">Insert</a>&nbsp;
						<a href="#update">Update</a>&nbsp;
						<a href="#delete">Delete</a>&nbsp;
					</td>
				</tr>
			</table>
		</fieldset>
		<br />
		<a name="list"></a>
		<fieldset>
			<legend>LIST</legend>
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">public</span> <span class="keyword">function</span> <span class="name api">list_default</span>($<span class="name">field</span> = <span class="constant">NULL</span>, $<span class="name">order</span> = <span class="constant">NULL</span>, $<span class="name">page</span> = <span class="literal">0</span>);<br />
			<br />
			<br />
			<br />
			<table>
				<tr>
					<td><strong>Description</strong></td>
					<td><span>Loads the listing view, displaying all the stored entries managed by <i>controller</i>.</td>
				</tr>
				<tr>
					<td><strong>Friendliness</strong></td>
					<td><span class="basic">Basic</span> <span class="dim advanced">Advanced</span> <span class="dim expert">Expert</span></td>
				</tr>
				<tr>
					<td><strong>REST API</strong></td>
					<td><span class="console"><?=filter_html(base_url(), $config['charset'])?>index.php/<i>controller</i>/</span>json_doc#list</td>
				</tr>
				<tr>
					<td><strong>URL Usage</strong></td>
					<td><span class="console"><?=filter_html(base_url(), $config['charset'])?>index.php/<i>controller</i>/</span><span class="name api">list_default</span></td>
				</tr>
				<tr>
					<td><strong>Method</strong></td>
					<td><span class="method">GET</span></td>
				</tr>
				<tr>
					<td><strong>Hooks</strong></td>
					<td><a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/hooks#views_list">_hook_list_generic_enter()</a>, <a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/hooks#views_list">_hook_list_generic_filter()</a>, <a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/hooks#views_list">_hook_list_generic_leave()</a></td>
				</tr>
				<tr>
					<td><strong>Authentication</strong></td>
					<td><span class="required">Required</span></td>
				</tr>
				<tr>
					<td><strong>Request Data</strong></td>
					<td><span class="none">None</span></td>
				</tr>
				<tr>
					<td><strong>Parameters</strong></td>
					<td>
						<table>
							<tr><td style="padding-left: 0px;">$field</td><td><span class="optional">Optional</span></td><td>The field name that will be used for ordering. (default: id)</td></tr>
							<tr><td style="padding-left: 0px;">$order</td><td><span class="optional">Optional</span></td><td>The order by which the listing will be displayed. (default: asc)</td></tr>
							<tr><td style="padding-left: 0px;">$page</td><td><span class="optional">Optional</span></td><td>The number of the page to be displayed, if pagination is enabled. (default: 0)</td></tr>
						</table>
					</td>
				</tr>
			</table>
		</fieldset>
		<a name="search"></a>
		<fieldset>
			<legend>SEARCH</legend>
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">public</span> <span class="keyword">function</span> <span class="name api">search</span>($<span class="name">advanced</span> = <span class="constant">true</span>);<br />
			<br />
			<br />
			<br />
			<table>
				<tr>
					<td><strong>Description</strong></td>
					<td><span>Loads the search view, displaying an interface to search for data already stored by <i>controller</i>.</td>
				</tr>
				<tr>
					<td><strong>Friendliness</strong></td>
					<td><span class="basic">Basic</span> <span class="dim advanced">Advanced</span> <span class="dim expert">Expert</span></td>
				</tr>
				<tr>
					<td><strong>REST API</strong></td>
					<td><span class="console">N/A (see <a href="#result">RESULT</a>)</td>
				</tr>
				<tr>
					<td><strong>URL Usage</strong></td>
					<td><span class="console"><?=filter_html(base_url(), $config['charset'])?>index.php/<i>controller</i>/</span><span class="name api">search</span></td>
				</tr>
				<tr>
					<td><strong>Method</strong></td>
					<td><span class="method">GET</span></td>
				</tr>
				<tr>
					<td><strong>Hooks</strong></td>
					<td><a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/hooks#views_search">_hook_search_generic_enter()</a>, <a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/hooks#views_search">_hook_search_generic_leave()</a></td>
				</tr>
				<tr>
					<td><strong>Authentication</strong></td>
					<td><span class="required">Required</span></td>
				</tr>
				<tr>
					<td><strong>Request Data</strong></td>
					<td><span class="none">None</span></td>
				</tr>
				<tr>
					<td><strong>Parameters</strong></td>
					<td>
						<table>
							<tr><td style="padding-left: 0px;">$advanced</td><td><span class="optional">Optional</span></td><td>Toggles between basic (false) and advanced (true) search. (default: true)</td></tr>
						</table>
					</td>
				</tr>
			</table>
		</fieldset>
		<a name="result"></a>
		<fieldset>
			<legend>RESULT</legend>
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">public</span> <span class="keyword">function</span> <span class="name api">result</span>($<span class="name">type</span> = <span class="literal">'advanced'</span>, $<span class="name">result_query</span> = <span class="constant">NULL</span>, $<span class="name">order_field</span> = <span class="constant">NULL</span>, $<span class="name">order_type</span> = <span class="constant">NULL</span>, $<span class="name">page</span> = <span class="literal">0</span>);<br />
			<br />
			<br />
			<br />
			<table>
				<tr>
					<td><strong>Description</strong></td>
					<td><span>Requests a search operation based on Request Data on the <i>controller</i>, loading an interface similar to <a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/api#list">list_default</a> with the search results.</td>
				</tr>
				<tr>
					<td><strong>Friendliness</strong></td>
					<td><span class="no basic">Basic</span> <span class="no advanced">Advanced</span> <span class="expert">Expert</span></td>
				</tr>
				<tr>
					<td><strong>REST API</strong></td>
					<td><span class="console"><?=filter_html(base_url(), $config['charset'])?>index.php/<i>controller</i>/</span>json_doc#result</td>
				</tr>
				<tr>
					<td><strong>URL Usage</strong></td>
					<td><span class="console"><?=filter_html(base_url(), $config['charset'])?>index.php/<i>controller</i>/</span><span class="name api">result</span></td>
				</tr>
				<tr>
					<td><strong>Method</strong></td>
					<td><span class="method">POST</span></td>
				</tr>
				<tr>
					<td><strong>Hooks</strong></td>
					<td><a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/hooks#views_result">_hook_result_generic_enter()</a>, <a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/hooks#views_result">_hook_result_generic_filter()</a>, <a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/hooks#views_result">_hook_result_generic_leave()</a></td>
				</tr>
				<tr>
					<td><strong>Authentication</strong></td>
					<td><span class="required">Required</span></td>
				</tr>
				<tr>
					<td><strong>Request Data</strong></td>
					<td>
						<table>
							<tr><td style="padding-left: 0px;">$<span class="name">_POST</span>[<span class="literal">'search_value'</span>] = <span class="literal">"search string"</span>; <span class="comment">// For $type == 'basic'</span></td></tr>
						</table>
					</td>
				</tr>
				<tr>
					<td><strong>Parameters</strong></td>
					<td>
						<table>
							<tr><td style="padding-left: 0px;">$type</td><td><span class="optional">Optional</span></td><td>Sets the search operation type to basic ('basic') and advanced ('advanced') search. (default: 'advanced')</td></tr>
							<tr><td style="padding-left: 0px;">$result_query</td><td><span class="optional">Optional</span></td><td>The resulting query (encrypted, base64 encoded and URL encoded) for the performed search (See <a href="<?=base_url()?>index.php/documentation/internals">Internals</a>).</td></tr>
							<tr><td style="padding-left: 0px;">$order_field</td><td><span class="optional">Optional</span></td><td>The field name that will be used for ordering. (default: id)</td></tr>
							<tr><td style="padding-left: 0px;">$order_type</td><td><span class="optional">Optional</span></td><td>The order by which the listing will be displayed. (default: asc)</td></tr>
							<tr><td style="padding-left: 0px;">$page</td><td><span class="optional">Optional</span></td><td>The number of the page to be displayed, if pagination is enabled. (default: 0)</td></tr>
						</table>
					</td>
				</tr>
			</table>
		</fieldset>
		<a name="export"></a>
		<fieldset>
			<legend>EXPORT</legend>
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">public</span> <span class="keyword">function</span> <span class="name api">export</span>($<span class="name">result_query</span> = <span class="constant">NULL</span>, $<span class="name">type</span> = <span class="literal">'pdf'</span>);<br />
			<br />
			<br />
			<br />
			<table>
				<tr>
					<td><strong>Description</strong></td>
					<td><span>Exports the data stored by the <i>controller</i> to one of the available export formats.</td>
				</tr>
				<tr>
					<td><strong>Friendliness</strong></td>
					<td><span class="basic">Basic</span> <span class="dim advanced">Advanced</span> <span class="dim expert">Expert</span></td>
				</tr>
				<tr>
					<td><strong>REST API</strong></td>
					<td><span class="console">N/A</td>
				</tr>
				<tr>
					<td><strong>URL Usage</strong></td>
					<td><span class="console"><?=filter_html(base_url(), $config['charset'])?>index.php/<i>controller</i>/</span><span class="name api">export</span></td>
				</tr>
				<tr>
					<td><strong>Method</strong></td>
					<td><span class="method">GET</span></td>
				</tr>
				<tr>
					<td><strong>Hooks</strong></td>
					<td><a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/hooks#views_export">_hook_export_enter()</a>, <a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/hooks#views_export">_hook_export_leave()</a></td>
				</tr>
				<tr>
					<td><strong>Authentication</strong></td>
					<td><span class="required">Required</span></td>
				</tr>
				<tr>
					<td><strong>Request Data</strong></td>
					<td><span class="none">None</span></td>
				</tr>
				<tr>
					<td><strong>Parameters</strong></td>
					<td>
						<table>
							<tr><td style="padding-left: 0px;">$result_query</td><td><span class="optional">Optional</span></td><td>The resulting query (encrypted, base64 encoded and URL encoded) for the performed search (See <a href="<?=base_url()?>index.php/documentation/internals">Internals</a>).</td></tr>
							<tr><td style="padding-left: 0px;">$type</td><td><span class="optional">Optional</span></td><td>Sets the export format type ('csv' or 'pdf'). (default: 'pdf')</td></tr>
						</table>
					</td>
				</tr>
			</table>
		</fieldset>
		<a name="create"></a>
		<fieldset>
			<legend>CREATE</legend>
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">public</span> <span class="keyword">function</span> <span class="name api">create</span>();<br />
			<br />
			<br />
			<br />
			<table>
				<tr>
					<td><strong>Description</strong></td>
					<td><span>Loads the create form view for the <i>controller</i>.</td>
				</tr>
				<tr>
					<td><strong>Friendliness</strong></td>
					<td><span class="basic">Basic</span> <span class="dim advanced">Advanced</span> <span class="dim expert">Expert</span></td>
				</tr>
				<tr>
					<td><strong>REST API</strong></td>
					<td><span class="console">N/A (see <a href="#insert">INSERT</a>)</td>
				</tr>
				<tr>
					<td><strong>URL Usage</strong></td>
					<td><span class="console"><?=filter_html(base_url(), $config['charset'])?>index.php/<i>controller</i>/</span><span class="name api">create</span></td>
				</tr>
				<tr>
					<td><strong>Method</strong></td>
					<td><span class="method">GET</span></td>
				</tr>
				<tr>
					<td><strong>Hooks</strong></td>
					<td><a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/hooks#views_create">_hook_create_generic_enter()</a>, <a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/hooks#views_create">_hook_create_generic_leave()</a></td>
				</tr>
				<tr>
					<td><strong>Authentication</strong></td>
					<td><span class="required">Required</span></td>
				</tr>
				<tr>
					<td><strong>Request Data</strong></td>
					<td><span class="none">None</span></td>
				</tr>
				<tr>
					<td><strong>Parameters</strong></td>
					<td><span class="None">None</span></td>
				</tr>
			</table>
		</fieldset>
		<a name="edit"></a>
		<fieldset>
			<legend>EDIT</legend>
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">public</span> <span class="keyword">function</span> <span class="name api">edit</span>($<span class="name">id</span>);<br />
			<br />
			<br />
			<br />
			<table>
				<tr>
					<td><strong>Description</strong></td>
					<td><span>Loads the edit form view for the entry identified by <i>id</i> on <i>controller</i>.</td>
				</tr>
				<tr>
					<td><strong>Friendliness</strong></td>
					<td><span class="basic">Basic</span> <span class="dim advanced">Advanced</span> <span class="dim expert">Expert</span></td>
				</tr>
				<tr>
					<td><strong>REST API</strong></td>
					<td><span class="console">N/A (see <a href="#update">UPDATE</a>)</td>
				</tr>
				<tr>
					<td><strong>URL Usage</strong></td>
					<td><span class="console"><?=filter_html(base_url(), $config['charset'])?>index.php/<i>controller</i>/</span><span class="name api">edit</span>/<i>id</id></td>
				</tr>
				<tr>
					<td><strong>Method</strong></td>
					<td><span class="method">GET</span></td>
				</tr>
				<tr>
					<td><strong>Hooks</strong></td>
					<td><a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/hooks#views_edit">_hook_edit_generic_enter()</a>, <a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/hooks#views_edit">_hook_edit_generic_leave()</a></td>
				</tr>
				<tr>
					<td><strong>Authentication</strong></td>
					<td><span class="required">Required</span></td>
				</tr>
				<tr>
					<td><strong>Request Data</strong></td>
					<td><span class="none">None</span></td>
				</tr>
				<tr>
					<td><strong>Parameters</strong></td>
					<td>
						<table>
							<tr><td style="padding-left: 0px;">$id</td><td><span class="required">Required</span></td><td>The entry <i>id</i> to be edited.</td></tr>
						</table>
					</td>
				</tr>
			</table>
		</fieldset>
		<a name="view"></a>
		<fieldset>
			<legend>VIEW</legend>
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">public</span> <span class="keyword">function</span> <span class="name api">view</span>($<span class="name">id</span>, $<span class="name">export</span>);<br />
			<br />
			<br />
			<br />
			<table>
				<tr>
					<td><strong>Description</strong></td>
					<td><span>Loads the visualization view for the entry identified by <i>id</i> on <i>controller</i>.</td>
				</tr>
				<tr>
					<td><strong>Friendliness</strong></td>
					<td><span class="basic">Basic</span> <span class="dim advanced">Advanced</span> <span class="dim expert">Expert</span></td>
				</tr>
				<tr>
					<td><strong>REST API</strong></td>
					<td><span class="console"><?=filter_html(base_url(), $config['charset'])?>index.php/<i>controller</i>/</span>json_doc#view</td>
				</tr>
				<tr>
					<td><strong>URL Usage</strong></td>
					<td><span class="console"><?=filter_html(base_url(), $config['charset'])?>index.php/<i>controller</i>/</span><span class="name api">view</span>/<i>id</id></td>
				</tr>
				<tr>
					<td><strong>Method</strong></td>
					<td><span class="method">GET</span></td>
				</tr>
				<tr>
					<td><strong>Hooks</strong></td>
					<td><a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/hooks#views_view">_hook_view_generic_enter()</a>, <a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/hooks#views_view">_hook_view_generic_leave()</a></td>
				</tr>
				<tr>
					<td><strong>Authentication</strong></td>
					<td><span class="required">Required</span></td>
				</tr>
				<tr>
					<td><strong>Request Data</strong></td>
					<td><span class="none">None</span></td>
				</tr>
				<tr>
					<td><strong>Parameters</strong></td>
					<td>
						<table>
							<tr><td style="padding-left: 0px;">$id</td><td><span class="required">Required</span></td><td>The entry <i>id</i> to be visualized.</td></tr>
							<tr><td style="padding-left: 0px;">$export</td><td><span class="optional">Optional</span></td><td>If set (to 'pdf'), instead of loading the visualization view in the browser, an export file will be downloaded.</td></tr>
						</table>
					</td>
				</tr>
			</table>
		</fieldset>
		<a name="remove"></a>
		<fieldset>
			<legend>REMOVE</legend>
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">public</span> <span class="keyword">function</span> <span class="name api">remove</span>($<span class="name">id</span>);<br />
			<br />
			<br />
			<br />
			<table>
				<tr>
					<td><strong>Description</strong></td>
					<td><span>Loads the remove view for the entry identified by <i>id</i> on <i>controller</i>, asking for confirmation to delete such data.</td>
				</tr>
				<tr>
					<td><strong>Friendliness</strong></td>
					<td><span class="basic">Basic</span> <span class="dim advanced">Advanced</span> <span class="dim expert">Expert</span></td>
				</tr>
				<tr>
					<td><strong>REST API</strong></td>
					<td><span class="console">N/A (see <a href="#delete">DELETE</a>)</td>
				</tr>
				<tr>
					<td><strong>URL Usage</strong></td>
					<td><span class="console"><?=filter_html(base_url(), $config['charset'])?>index.php/<i>controller</i>/</span><span class="name api">remove</span>/<i>id</id></td>
				</tr>
				<tr>
					<td><strong>Method</strong></td>
					<td><span class="method">GET</span></td>
				</tr>
				<tr>
					<td><strong>Hooks</strong></td>
					<td><a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/hooks#views_remove">_hook_remove_generic_enter()</a>, <a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/hooks#views_remove">_hook_remove_generic_leave()</a></td>
				</tr>
				<tr>
					<td><strong>Authentication</strong></td>
					<td><span class="required">Required</span></td>
				</tr>
				<tr>
					<td><strong>Request Data</strong></td>
					<td><span class="none">None</span></td>
				</tr>
				<tr>
					<td><strong>Parameters</strong></td>
					<td>
						<table>
							<tr><td style="padding-left: 0px;">$id</td><td><span class="required">Required</span></td><td>The entry <i>id</i> to be removed (with confirmation step included).</td></tr>
						</table>
					</td>
				</tr>
			</table>
		</fieldset>
		<a name="groups"></a>
		<fieldset>
			<legend>GROUPS</legend>
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">public</span> <span class="keyword">function</span> <span class="name api">groups</span>();<br />
			<br />
			<br />
			<br />
			<table>
				<tr>
					<td><strong>Description</strong></td>
					<td><span>Loads the groups view, displaying the list of groups available for that <i>controller</i>. Groups are automatically created if relationships are found for that <i>controller</i>.</td>
				</tr>
				<tr>
					<td><strong>Friendliness</strong></td>
					<td><span class="basic">Basic</span> <span class="dim advanced">Advanced</span> <span class="dim expert">Expert</span></td>
				</tr>
				<tr>
					<td><strong>REST API</strong></td>
					<td><span class="console">N/A</td>
				</tr>
				<tr>
					<td><strong>URL Usage</strong></td>
					<td><span class="console"><?=filter_html(base_url(), $config['charset'])?>index.php/<i>controller</i>/</span><span class="name api">groups</span></td>
				</tr>
				<tr>
					<td><strong>Method</strong></td>
					<td><span class="method">GET</span></td>
				</tr>
				<tr>
					<td><strong>Hooks</strong></td>
					<td><a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/hooks#views_groups">_hook_groups_generic_enter()</a>, <a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/hooks#views_groups">_hook_groups_generic_leave()</a></td>
				</tr>
				<tr>
					<td><strong>Authentication</strong></td>
					<td><span class="required">Required</span></td>
				</tr>
				<tr>
					<td><strong>Request Data</strong></td>
					<td><span class="none">None</span></td>
				</tr>
				<tr>
					<td><strong>Parameters</strong></td>
					<td><span class="none">None</span></td>
				</tr>
			</table>
		</fieldset>
		<a name="insert"></a>
		<fieldset>
			<legend>INSERT</legend>
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">public</span> <span class="keyword">function</span> <span class="name api">insert</span>();<br />
			<br />
			<br />
			<br />
			<table>
				<tr>
					<td><strong>Description</strong></td>
					<td><span>Requests the <i>controller</i> to insert an entry described by <i>Request Data</i>.</td>
				</tr>
				<tr>
					<td><strong>Friendliness</strong></td>
					<td><span class="no basic">Basic</span> <span class="advanced">Advanced</span> <span class="dim expert">Expert</span></td>
				</tr>
				<tr>
					<td><strong>REST API</strong></td>
					<td><span class="console"><?=filter_html(base_url(), $config['charset'])?>index.php/<i>controller</i>/</span>json_doc#insert</td>
				</tr>
				<tr>
					<td><strong>URL Usage</strong></td>
					<td><span class="console"><?=filter_html(base_url(), $config['charset'])?>index.php/<i>controller</i>/</span><span class="name api">insert</span></td>
				</tr>
				<tr>
					<td><strong>Method</strong></td>
					<td><span class="method">POST</span></td>
				</tr>
				<tr>
					<td><strong>Hooks</strong></td>
					<td><a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/hooks#operations_insert">_hook_insert_pre()</a>, <a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/hooks#operations_insert">_hook_insert_post()</a></td>
				</tr>
				<tr>
					<td><strong>Authentication</strong></td>
					<td><span class="required">Required</span></td>
				</tr>
				<tr>
					<td><strong>Request Data</strong></td>
					<td>
						<table>
							<tr><td style="padding-left: 0px;">$<span class="name">_POST</span>[<span class="literal">'field'</span>] = <span class="literal">"value"</span>; <span class="comment">// 'field' is a DBMS field name of the table managed by the controller</span></td></tr>
							<tr><td style="padding-left: 0px;">$<span class="name">_POST</span>[<span class="literal">'field2'</span>] = <span class="literal">"value"</span>; <span class="comment">// 'field2' is a DBMS field name of the table managed by the controller</span></td></tr>
							<tr><td style="padding-left: 0px;">...</td></tr>
						</table>
					</td>
				</tr>
				<tr>
					<td><strong>Parameters</strong></td>
					<td><span class="None">None</span></td>
				</tr>
			</table>
		</fieldset>
		<a name="update"></a>
		<fieldset>
			<legend>UPDATE</legend>
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">public</span> <span class="keyword">function</span> <span class="name api">update</span>($<span class="name">id</span>, $<span class="name">field</span> = <span class="constant">NULL</span>, $<span class="name">value</span> = <span class="constant">NULL</span>);<br />
			<br />
			<br />
			<br />
			<table>
				<tr>
					<td><strong>Description</strong></td>
					<td><span>Requests the <i>controller</i> to update the entry identified by <i>id</id> with the contents of <i>Request Data</i>.</td>
				</tr>
				<tr>
					<td><strong>Friendliness</strong></td>
					<td><span class="no basic">Basic</span> <span class="advanced">Advanced</span> <span class="dim expert">Expert</span></td>
				</tr>
				<tr>
					<td><strong>REST API</strong></td>
					<td><span class="console"><?=filter_html(base_url(), $config['charset'])?>index.php/<i>controller</i>/</span>json_doc#update</td>
				</tr>
				<tr>
					<td><strong>URL Usage</strong></td>
					<td><span class="console"><?=filter_html(base_url(), $config['charset'])?>index.php/<i>controller</i>/</span><span class="name api">update</span>/<i>id</i></td>
				</tr>
				<tr>
					<td><strong>Method</strong></td>
					<td><span class="method">POST</span></td>
				</tr>
				<tr>
					<td><strong>Hooks</strong></td>
					<td><a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/hooks#operations_update">_hook_update_pre()</a>, <a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/hooks#operations_update">_hook_update_post()</a></td>
				</tr>
				<tr>
					<td><strong>Authentication</strong></td>
					<td><span class="required">Required</span></td>
				</tr>
				<tr>
					<td><strong>Request Data</strong></td>
					<td>
						<table>
							<tr><td style="padding-left: 0px;">$<span class="name">_POST</span>[<span class="literal">'field'</span>] = <span class="literal">"value"</span>; <span class="comment">// 'field' is a DBMS field name of the table managed by the controller</span></td></tr>
							<tr><td style="padding-left: 0px;">$<span class="name">_POST</span>[<span class="literal">'field2'</span>] = <span class="literal">"value"</span>; <span class="comment">// 'field2' is a DBMS field name of the table managed by the controller</span></td></tr>
							<tr><td style="padding-left: 0px;">...</td></tr>
						</table>
					</td>
				</tr>
				<tr>
					<td><strong>Parameters</strong></td>
					<td>
						<table>
							<tr><td style="padding-left: 0px;">$id</td><td><span class="required">Required</span></td><td>The entry <i>id</i> to be updated.</td></tr>
							<tr><td style="padding-left: 0px;">$field</td><td><span class="optional">Optional</span></td><td>Force the specified <i>field</i> to be updated with <i>value</i>.</td></tr>
							<tr><td style="padding-left: 0px;">$value</td><td><span class="optional">Optional</span></td><td>The <i>value</i> to be set on <i>field</i>.</td></tr>
						</table>
					</td>
				</tr>
			</table>
		</fieldset>
		<a name="delete"></a>
		<fieldset>
			<legend>DELETE</legend>
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">public</span> <span class="keyword">function</span> <span class="name api">delete</span>($<span class="name">id</span>);<br />
			<br />
			<br />
			<br />
			<table>
				<tr>
					<td><strong>Description</strong></td>
					<td><span>Requests the <i>controller</i> to delete the entry identified by <i>id</id>.</td>
				</tr>
				<tr>
					<td><strong>Friendliness</strong></td>
					<td><span class="basic">Basic</span> <span class="dim advanced">Advanced</span> <span class="dim expert">Expert</span></td>
				</tr>
				<tr>
					<td><strong>REST API</strong></td>
					<td><span class="console"><?=filter_html(base_url(), $config['charset'])?>index.php/<i>controller</i>/</span>json_doc#delete</td>
				</tr>
				<tr>
					<td><strong>URL Usage</strong></td>
					<td><span class="console"><?=filter_html(base_url(), $config['charset'])?>index.php/<i>controller</i>/</span><span class="name api">delete</span>/<i>id</i></td>
				</tr>
				<tr>
					<td><strong>Method</strong></td>
					<td><span class="method">GET</span></td>
				</tr>
				<tr>
					<td><strong>Hooks</strong></td>
					<td><a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/hooks#operations_delete">_hook_delete_pre()</a>, <a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/hooks#operations_update">_hook_delete_post()</a></td>
				</tr>
				<tr>
					<td><strong>Authentication</strong></td>
					<td><span class="required">Required</span></td>
				</tr>
				<tr>
					<td><strong>Request Data</strong></td>
					<td><span class="none">None</span></td>
				</tr>
				<tr>
					<td><strong>Parameters</strong></td>
					<td>
						<table>
							<tr><td style="padding-left: 0px;">$id</td><td><span class="required">Required</span></td><td>The entry <i>id</i> to be deleted.</td></tr>
						</table>
					</td>
				</tr>
			</table>
		</fieldset>
	</body>
</html>