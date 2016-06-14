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
		<title>ND PHP - Hooks Documentation</title>
		<link rel="stylesheet" type="text/css" href="<?=filter_html(static_css_url(), $config['charset'])?>/documentation/hooks.css" />
	</head>
	<body>
		<?php include('navigation.php'); ?>
		<fieldset>
			<legend>INDEX</legend>
			<table>
				<tr>
					<td><strong>Construct</strong></td>
					<td>
						<a href="#construct">Construct</a>&nbsp;
					</td>
				</tr>
				<tr>
					<td><strong>Charts</strong></td>
					<td>
						<a href="#charts">Charts</a>&nbsp;
					</td>
				</tr>
				<tr>
					<td><strong>Views</strong></td>
					<td>
						
						<a href="#views_list">List</a>&nbsp;
						<a href="#views_search">Search</a>&nbsp;
						<a href="#views_result">Result</a>&nbsp;
						<a href="#views_export">Export</a>&nbsp;
						<a href="#views_create">Create</a>&nbsp;
						<a href="#views_edit">Edit</a>&nbsp;
						<a href="#views_view">View</a>&nbsp;
						<a href="#views_remove">Remove</a>&nbsp;
						<a href="#views_groups">Groups</a>&nbsp;
					</td>
				</tr>
				<tr>
					<td><strong>Operations</strong></td>
					<td>
						<a href="#operations_insert">Insert</a>&nbsp;
						<a href="#operations_update">Update</a>&nbsp;
						<a href="#operations_delete">Delete</a>&nbsp;
					</td>
				</tr>
			</table>
		</fieldset>
		<br />
		<a name="construct"></a>
		<fieldset>
			<legend>CONSTRUCT</legend>
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">protected</span> <span class="keyword">function</span> <span class="name">_hook_construct</span>() {<br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">/* Triggers right before the <a href="#">::__construct()</a> method returns. */</span><br /><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">return</span>;<br />
			&nbsp;&nbsp;&nbsp;&nbsp;}<br />
		</fieldset>
		<a name="charts"></a>
		<fieldset>
			<legend>CHARTS</legend>
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">protected</span> <span class="keyword">function</span> <span class="name">_hook_charts</span>() {<br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">/* NOTE: This hook should only be used for raw charts. */</span><br /><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">return</span>;<br />
			&nbsp;&nbsp;&nbsp;&nbsp;}<br />
		</fieldset>
		<a name="views_list"></a>
		<fieldset>
			<legend>VIEWS - LIST</legend>
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">protected</span> <span class="keyword">function</span> <span class="name">_hook_list_generic_enter</span>(<span class="special">&amp;</span>$<span class="name">data</span>, <span class="special">&amp;</span>$<span class="name">field</span>, <span class="special">&amp;</span>$<span class="name">order</span>, <span class="special">&amp;</span>$<span class="name">page</span>) {<br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">/* Triggers right after the <a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/api#list">list_default()</a> method is invoked. */</span><br /><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$<span class="name">hook_enter_return</span> = <span class="constant">NULL</span>; <span class="comment">// This context variable is passed as _hook_list_generic_leave() 5th parameter.</span><br /><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">return</span> $<span class="name">hook_enter_return</span>;<br />
			&nbsp;&nbsp;&nbsp;&nbsp;}<br />
			<br />
			<br />
			<br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">protected</span> <span class="keyword">function</span> <span class="name">_hook_list_generic_filter</span>(<span class="special">&amp;</span>$<span class="name">data</span>, <span class="special">&amp;</span>$<span class="name">field</span>, <span class="special">&amp;</span>$<span class="name">order</span>, <span class="special">&amp;</span>$<span class="name">page</span>, $<span class="name">hook_enter_return</span>) {<br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">/* Triggers right after the result query is constructed, but before limit and grouping clauses are applied. */</span><br /><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">return</span> $<span class="name">hook_enter_return</span>;<br />
			&nbsp;&nbsp;&nbsp;&nbsp;}<br />
			<br />
			<br />
			<br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">protected</span> <span class="keyword">function</span> <span class="name">_hook_list_generic_leave</span>(<span class="special">&amp;</span>$<span class="name">data</span>, <span class="special">&amp;</span>$<span class="name">field</span>, <span class="special">&amp;</span>$<span class="name">order</span>, <span class="special">&amp;</span>$<span class="name">page</span>, $<span class="name">hook_enter_return</span>) {<br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">/* Triggers right before the <a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/api#list">list_default()</a> method loads the view. */</span><br /><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">return</span>;<br />
			&nbsp;&nbsp;&nbsp;&nbsp;}<br />
		</fieldset>
		<a name="views_search"></a>
		<fieldset>
			<legend>VIEWS - SEARCH</legend>
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">protected</span> <span class="keyword">function</span> <span class="name">_hook_search_generic_enter</span>(<span class="special">&amp;</span>$<span class="name">data</span>, <span class="special">&amp;</span>$advanced) {<br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">/* Triggers right after the <a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/api#search">search()</a> method is invoked. */</span><br /><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$<span class="name">hook_enter_return</span> = <span class="constant">NULL</span>; <span class="comment">// This context variable is passed as _hook_search_generic_leave() 3rd parameter.</span><br /><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">return</span> $<span class="name">hook_enter_return</span>;<br />
			&nbsp;&nbsp;&nbsp;&nbsp;}<br />
			<br />
			<br />
			<br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">protected</span> <span class="keyword">function</span> <span class="name">_hook_search_generic_leave</span>(<span class="special">&amp;</span>$<span class="name">data</span>, <span class="special">&amp;</span>$advanced, $<span class="name">hook_enter_return</span>) {<br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">/* Triggers right before the <a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/api#search">search()</a> method loads the view. */</span><br /><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">return</span>;<br />
			&nbsp;&nbsp;&nbsp;&nbsp;}<br />
		</fieldset>
		<a name="views_result"></a>
		<fieldset>
			<legend>VIEWS - RESULT</legend>
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">protected</span> <span class="keyword">function</span> <span class="name">_hook_result_generic_enter</span>(<span class="special">&amp;</span>$<span class="name">data</span>, <span class="special">&amp;</span>$<span class="name">type</span>, <span class="special">&amp;</span>$<span class="name">result_query</span>, <span class="special">&amp;</span>$<span class="name">order_field</span>, <span class="special">&amp;</span>$<span class="name">order_type</span>, <span class="special">&amp;</span>$<span class="name">page</span>) {<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">/* Triggers right after the <a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/api#result">result()</a> method is invoked. */</span><br /><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$<span class="name">hook_enter_return</span> = <span class="constant">NULL</span>; <span class="comment">// This context variable is passed as _hook_result_generic_leave() 7th parameter.</span><br /><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">return</span> $<span class="name">hook_enter_return</span>;<br />
			&nbsp;&nbsp;&nbsp;&nbsp;}<br />
			<br />
			<br />
			<br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">protected</span> <span class="keyword">function</span> <span class="name">_hook_result_generic_filter</span>(<span class="special">&amp;</span>$<span class="name">data</span>, <span class="special">&amp;</span>$<span class="name">type</span>, <span class="special">&amp;</span>$<span class="name">result_query</span>, <span class="special">&amp;</span>$<span class="name">order_field</span>, <span class="special">&amp;</span>$<span class="name">order_type</span>, <span class="special">&amp;</span>$<span class="name">page</span>, $<span class="name">hook_enter_return</span>) {<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">/* Triggers right after the result query is constructed, but before limit and grouping clauses are applied. */</span><br /><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">return</span> $<span class="name">hook_enter_return</span>;<br />
			&nbsp;&nbsp;&nbsp;&nbsp;}<br />
			<br />
			<br />
			<br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">protected</span> <span class="keyword">function</span> <span class="name">_hook_result_generic_leave</span>(<span class="special">&amp;</span>$<span class="name">data</span>, <span class="special">&amp;</span>$<span class="name">type</span>, <span class="special">&amp;</span>$<span class="name">result_query</span>, <span class="special">&amp;</span>$<span class="name">order_field</span>, <span class="special">&amp;</span>$<span class="name">order_type</span>, <span class="special">&amp;</span>$<span class="name">page</span>, $<span class="name">hook_enter_return</span>) {<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">/* Triggers right before the <a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/api#result">result()</a> method loads the view. */</span><br /><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">return</span>;<br />
			&nbsp;&nbsp;&nbsp;&nbsp;}<br />
		</fieldset>
		<a name="views_export"></a>
		<fieldset>
			<legend>VIEWS - EXPORT</legend>
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">protected</span> <span class="keyword">function</span> <span class="name">_hook_export_enter</span>(<span class="special">&amp;</span>$<span class="name">data</span>, <span class="special">&amp;</span>$<span class="name">export_query</span>, <span class="special">&amp;</span>$<span class="name">type</span>) {<br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">/* Triggers right after the <a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/api#export">export()</a> method is invoked. */</span><br /><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$<span class="name">hook_enter_return</span> = <span class="constant">NULL</span>; <span class="comment">// This context variable is passed as _hook_export_leave() 4th parameter.</span><br /><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">return</span> $<span class="name">hook_enter_return</span>;<br />
			&nbsp;&nbsp;&nbsp;&nbsp;}<br />
			<br />
			<br />
			<br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">protected</span> <span class="keyword">function</span> <span class="name">_hook_export_leave</span>(<span class="special">&amp;</span>$<span class="name">data</span>, <span class="special">&amp;</span>$<span class="name">export_query</span>, <span class="special">&amp;</span>$<span class="name">type</span>, $<span class="name">hook_enter_return</span>) {<br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">/* Triggers right before the <a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/api#export">export()</a> method loads the view. */</span><br /><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">return</span>;<br />
			&nbsp;&nbsp;&nbsp;&nbsp;}<br />
		</fieldset>
		<a name="views_create"></a>
		<fieldset>
			<legend>VIEWS - CREATE</legend>
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">protected</span> <span class="keyword">function</span> <span class="name">_hook_create_generic_enter</span>(<span class="special">&amp;</span>$<span class="name">data</span>) {<br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">/* Triggers right after the <a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/api#create">create()</a> method is invoked. */</span><br /><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$<span class="name">hook_enter_return</span> = <span class="constant">NULL</span>; <span class="comment">// This context variable is passed as _hook_create_generic_leave() 2nd parameter.</span><br /><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">return</span> $<span class="name">hook_enter_return</span>;<br />
			&nbsp;&nbsp;&nbsp;&nbsp;}<br />
			<br />
			<br />
			<br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">protected</span> <span class="keyword">function</span> <span class="name">_hook_create_generic_leave</span>(<span class="special">&amp;</span>$<span class="name">data</span>, $<span class="name">hook_enter_return</span>) {<br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">/* Triggers right before the <a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/api#create">create()</a> method loads the view. */</span><br /><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">return</span>;<br />
			&nbsp;&nbsp;&nbsp;&nbsp;}<br />
		</fieldset>
		<a name="views_edit"></a>
		<fieldset>
			<legend>VIEWS - EDIT</legend>
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">protected</span> <span class="keyword">function</span> <span class="name">_hook_edit_generic_enter</span>(<span class="special">&amp;</span>$<span class="name">data</span>, <span class="special">&amp;</span>$<span class="name">id</span>) {<br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">/* Triggers right after the <a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/api#edit">edit()</a> method is invoked. */</span><br /><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$<span class="name">hook_enter_return</span> = <span class="constant">NULL</span>; <span class="comment">// This context variable is passed as _hook_edit_generic_leave() 3rd parameter.</span><br /><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">return</span> $<span class="name">hook_enter_return</span>;<br />
			&nbsp;&nbsp;&nbsp;&nbsp;}<br />
			<br />
			<br />
			<br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">protected</span> <span class="keyword">function</span> <span class="name">_hook_edit_generic_leave</span>(<span class="special">&amp;</span>$<span class="name">data</span>, <span class="special">&amp;</span>$<span class="name">id</span>, $<span class="name">hook_enter_return</span>) {<br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">/* Triggers right before the <a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/api#edit">edit()</a> method loads the view. */</span><br /><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">return</span>;<br />
			&nbsp;&nbsp;&nbsp;&nbsp;}<br />
		</fieldset>
		<a name="views_view"></a>
		<fieldset>
			<legend>VIEWS - VIEW</legend>
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">protected</span> <span class="keyword">function</span> <span class="name">_hook_view_generic_enter</span>(<span class="special">&amp;</span>$<span class="name">data</span>, <span class="special">&amp;</span>$<span class="name">id</span>, <span class="special">&amp;</span>$<span class="name">export</span>) {<br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">/* Triggers right after the <a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/api#view">view()</a> method is invoked. */</span><br /><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$<span class="name">hook_enter_return</span> = <span class="constant">NULL</span>; <span class="comment">// This context variable is passed as _hook_view_generic_leave() 4th parameter.</span><br /><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">return</span> $<span class="name">hook_enter_return</span>;<br />
			&nbsp;&nbsp;&nbsp;&nbsp;}<br />
			<br />
			<br />
			<br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">protected</span> <span class="keyword">function</span> <span class="name">_hook_view_generic_leave</span>(<span class="special">&amp;</span>$<span class="name">data</span>, <span class="special">&amp;</span>$<span class="name">id</span>, <span class="special">&amp;</span>$<span class="name">export</span>, $<span class="name">hook_enter_return</span>) {<br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">/* Triggers right before the <a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/api#view">view()</a> method loads the view. */</span><br /><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">return</span>;<br />
			&nbsp;&nbsp;&nbsp;&nbsp;}<br />
		</fieldset>
		<a name="views_remove"></a>
		<fieldset>
			<legend>VIEWS - REMOVE</legend>
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">protected</span> <span class="keyword">function</span> <span class="name">_hook_remove_generic_enter</span>(<span class="special">&amp;</span>$<span class="name">data</span>, <span class="special">&amp;</span>$<span class="name">id</span>) {<br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">/* Triggers right after the <a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/api#remove">remove()</a> method is invoked. */</span><br /><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$<span class="name">hook_enter_return</span> = <span class="constant">NULL</span>; <span class="comment">// This context variable is passed as _hook_remove_generic_leave() 3rd parameter.</span><br /><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">return</span> $<span class="name">hook_enter_return</span>;<br />
			&nbsp;&nbsp;&nbsp;&nbsp;}<br />
			<br />
			<br />
			<br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">protected</span> <span class="keyword">function</span> <span class="name">_hook_remove_generic_leave</span>(<span class="special">&amp;</span>$<span class="name">data</span>, <span class="special">&amp;</span>$<span class="name">id</span>, $<span class="name">hook_enter_return</span>) {<br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">/* Triggers right before the <a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/api#remove">remove()</a> method loads the view. */</span><br /><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">return</span>;<br />
			&nbsp;&nbsp;&nbsp;&nbsp;}<br />
		</fieldset>
		<a name="views_groups"></a>
		<fieldset>
			<legend>VIEWS - GROUPS</legend>
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">protected</span> <span class="keyword">function</span> <span class="name">_hook_groups_generic_enter</span>(<span class="special">&amp;</span>$<span class="name">data</span>) {<br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">/* Triggers right after the <a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/api#groups">groups()</a> method is invoked. */</span><br /><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$<span class="name">hook_enter_return</span> = <span class="constant">NULL</span>; <span class="comment">// This context variable is passed as _hook_groups_generic_leave() 2nd parameter.</span><br /><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">return</span> $<span class="name">hook_enter_return</span>;<br />
			&nbsp;&nbsp;&nbsp;&nbsp;}<br />
			<br />
			<br />
			<br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">protected</span> <span class="keyword">function</span> <span class="name">_hook_groups_generic_leave</span>(<span class="special">&amp;</span>$<span class="name">data</span>, $<span class="name">hook_enter_return</span>) {<br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">/* Triggers right before the <a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/api#groups">groups()</a> method loads the view. */</span><br /><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">return</span>;<br />
			&nbsp;&nbsp;&nbsp;&nbsp;}<br />
		</fieldset>
		<a name="operations_insert"></a>
		<fieldset>
			<legend>OPERATIONS - INSERT</legend>
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">protected</span> <span class="keyword">function</span> <span class="name">_hook_insert_pre</span>(<span class="special">&amp;</span>$<span class="name">POST</span>, <span class="special">&amp;</span>$<span class="name">fields</span>) {<br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">/* Triggers right after the <a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/api#insert">insert()</a> method is invoked. */</span><br /><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$<span class="name">hook_pre_return</span> = <span class="constant">NULL</span>; <span class="comment">// This context variable is passed as _hook_insert_post() 3rd parameter.</span><br /><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">return</span> $<span class="name">hook_pre_return</span>;<br />
			&nbsp;&nbsp;&nbsp;&nbsp;}<br />
			<br />
			<br />
			<br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">protected</span> <span class="keyword">function</span> <span class="name">_hook_insert_post</span>(<span class="special">&amp;</span>$<span class="name">POST</span>, <span class="special">&amp;</span>$<span class="name">fields</span>, $<span class="name">hook_pre_return</span>) {<br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">/* Triggers right before the <a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/api#insert">insert()</a> method returns. */</span><br /><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">return</span>;<br />
			&nbsp;&nbsp;&nbsp;&nbsp;}<br />
		</fieldset>
		<a name="operations_update"></a>
		<fieldset>
			<legend>OPERATIONS - UPDATE</legend>
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">protected</span> <span class="keyword">function</span> <span class="name">_hook_update_pre</span>(<span class="special">&amp;</span>$<span class="name">id</span>, <span class="special">&amp;</span>$<span class="name">POST</span>, <span class="special">&amp;</span>$<span class="name">fields</span>) {<br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">/* Triggers right after the <a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/api#update">update()</a> method is invoked. */</span><br /><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$<span class="name">hook_pre_return</span> = <span class="constant">NULL</span>; <span class="comment">// This context variable is passed as _hook_update_post() 4th parameter.</span><br /><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">return</span> $<span class="name">hook_pre_return</span>;<br />
			&nbsp;&nbsp;&nbsp;&nbsp;}<br />
			<br />
			<br />
			<br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">protected</span> <span class="keyword">function</span> <span class="name">_hook_update_post</span>(<span class="special">&amp;</span>$<span class="name">id</span>, <span class="special">&amp;</span>$<span class="name">POST</span>, <span class="special">&amp;</span>$<span class="name">fields</span>, $<span class="name">hook_pre_return</span>) {<br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">/* Triggers right before the <a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/api#update">update()</a> method returns. */</span><br /><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">return</span>;<br />
			&nbsp;&nbsp;&nbsp;&nbsp;}<br />
		</fieldset>
		<a name="operations_delete"></a>
		<fieldset>
			<legend>OPERATIONS - DELETE</legend>
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">protected</span> <span class="keyword">function</span> <span class="name">_hook_delete_pre</span>(<span class="special">&amp;</span>$<span class="name">id</span>, <span class="special">&amp;</span>$<span class="name">POST</span>, <span class="special">&amp;</span>$<span class="name">fields</span>) {<br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">/* Triggers right after the <a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/api#delete">delete()</a> method is invoked. */</span><br /><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$<span class="name">hook_pre_return</span> = <span class="constant">NULL</span>; <span class="comment">// This context variable is passed as _hook_delete_post() 4th parameter.</span><br /><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">return</span> $<span class="name">hook_pre_return</span>;<br />
			&nbsp;&nbsp;&nbsp;&nbsp;}<br />
			<br />
			<br />
			<br />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="visibility">protected</span> <span class="keyword">function</span> <span class="name">_hook_delete_post</span>(<span class="special">&amp;</span>$<span class="name">id</span>, <span class="special">&amp;</span>$<span class="name">POST</span>, <span class="special">&amp;</span>$<span class="name">fields</span>, $<span class="name">hook_pre_return</span>) {<br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">/* Triggers right before the <a href="<?=filter_html(base_url(), $config['charset'])?>index.php/documentation/api#delete">delete()</a> method returns. */</span><br /><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">return</span>;<br />
			&nbsp;&nbsp;&nbsp;&nbsp;}<br />
		</fieldset>
	</body>
</html>