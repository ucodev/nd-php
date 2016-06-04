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
	<meta charset="<?=filter_html($config['charset'], $config['charset'])?>" />
 	<title><?=filter_html($view['title'], $config['charset'])?></title>
	<meta name="author" content="<?=filter_html($project['author'], $config['charset'])?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="description" content="<?=filter_html($view['description'], $config['charset'])?>" />
	<link rel="stylesheet" type="text/css" href="<?=filter_html(static_css_url(), $config['charset'])?>/builder/ide.css.php" />
	<link rel="stylesheet" type="text/css" href="<?=filter_html(static_js_url(), $config['charset'])?>/lib/jquery-ui/1.10.4/css/jquery-ui.css" />
 	<script type="text/javascript" src="<?=filter_html(static_js_url(), $config['charset'])?>/lib/jquery/1.12.4/jquery.js"></script>
 	<script type="text/javascript" src="<?=filter_html(static_js_url(), $config['charset'])?>/lib/jquery-ui/1.10.4/jquery-ui.js"></script>
 	<script type="text/javascript" src="<?=filter_html(static_js_url(), $config['charset'])?>/lib/jquery/blockui/blockui.js"></script>
 	<script type="text/javascript" src="<?=filter_html(static_js_url(), $config['charset'])?>/lib/ndphp/ide.js.php"></script>
 	<script type="text/javascript" src="<?=filter_html(static_js_url(), $config['charset'])?>/lib/json/json2.js"></script>
 	<script type="text/javascript" src="<?=filter_html(static_js_url(), $config['charset'])?>/lib/editarea/edit_area/edit_area_full.js"></script>

 	<script type="text/javascript">
   		jQuery(function() {
   			jQuery(document).tooltip();
   		});
 	</script>
</head>
<body>
	<?php if (isset($view['application']['obj_count'])): ?>
		<script type="text/javascript">
			ndphp.ide.obj_count = <?=$view['application']['obj_count']?>;
		</script>
	<?php endif; ?>
	<div>
		<fieldset id="container_trash" class="container trash" ondrop="ndphp.ide.ide_obj_container_drop(event)" ondragover="ndphp.ide.ide_obj_allow_drop(event)">
			<legend>Trash</legend>
		</fieldset>
		<fieldset id="menu" class="pool pool_menu">
			<legend>Controller Pool</legend>
			<div id="obj_menu_entry_a" class="object" oncontextmenu="ndphp.ide.menu_entry_edit_controller(event, this);" onclick="ndphp.ide.menu_entry_load_fields(this);" draggable="true" ondragover="ndphp.ide.ide_obj_allow_drop(event)" ondrop="ndphp.ide.ide_obj_draggable_drop(event);" ondragstart="ndphp.ide.ide_obj_drag_common(event, [ this.parentNode.id ])">
				<input id="type" type="hidden" value="generic" />
				<span id="title" class="title menu_obj" onClick="ndphp.ide.dialog_show(this, 'menu');" title="This is a Generic Controller type. You can drag it into the 'Application Model -> Controllers' container. You can then select the instantiated controller and a Fields container will be displayed, where you can drag fields from the Fields Pool into the 'Application Model -> Fields' container. The Controller name will create a database table and each Field will create a database column under that table.">Generic</span><br />
				<span id="name" onClick="ndphp.ide.name_click(this);">entry name</span>
				<input id="name_edit" onblur="ndphp.ide.input_blur(event);" onkeydown="return ndphp.ide.keydown(event);" type="text" />
			</div>
			<div id="obj_menu_entry_b" class="object" oncontextmenu="ndphp.ide.menu_entry_edit_controller(event, this);" onclick="ndphp.ide.menu_entry_load_fields(this);" draggable="true" ondragover="ndphp.ide.ide_obj_allow_drop(event)" ondrop="ndphp.ide.ide_obj_draggable_drop(event);" ondragstart="ndphp.ide.ide_obj_drag_common(event, [ this.parentNode.id ])">
				<input id="type" type="hidden" value="limited" />
				<span id="title" class="title menu_obj" onClick="ndphp.ide.dialog_show(this, 'menu');" title="This is a Limited Controller type. You can drag it into the 'Application Model -> Controllers' container. All data handled by the controller of this menu will be user-specific. This means that data inserted by one user can only be seen or modified by that user, unless the user is ROLE_ADMIN which, in this case, will be able to view all the data managed by this controller.">Limited</span><br />
				<span id="name" onClick="ndphp.ide.name_click(this);">entry name</span>
				<input id="name_edit" onblur="ndphp.ide.input_blur(event);" onkeydown="return ndphp.ide.keydown(event);" type="text" />
			</div>
			<div id="obj_menu_entry_c" class="object" oncontextmenu="ndphp.ide.menu_entry_edit_controller(event, this);" onclick="ndphp.ide.menu_entry_load_fields(this);" draggable="true" ondragover="ndphp.ide.ide_obj_allow_drop(event)" ondrop="ndphp.ide.ide_obj_draggable_drop(event);" ondragstart="ndphp.ide.ide_obj_drag_common(event, [ this.parentNode.id ])">
				<input id="type" type="hidden" value="custom" />
				<span id="title" class="title menu_obj" onClick="ndphp.ide.dialog_show(this, 'menu');" title="Custom Controllers are intended to be used as database Views based on a custom, developer supplied, SQL query. The menu will behave as a Generic Controller, except that operations such as INSERT, UPDATE and DELETE are disabled. All fields that the custom SQL query will display must be also created in the Controller's 'Application Model -> Fields' container. Please refer to the specific documentation of this menu type.">Custom</span><br />
				<span id="name" onClick="ndphp.ide.name_click(this);">entry name</span>
				<input id="name_edit" onblur="ndphp.ide.input_blur(event);" onkeydown="return ndphp.ide.keydown(event);" type="text" />
			</div>
			<div id="obj_menu_entry_d" class="object" oncontextmenu="ndphp.ide.menu_entry_edit_controller(event, this);" onclick="ndphp.ide.menu_entry_load_fields(this);" draggable="true" ondragover="ndphp.ide.ide_obj_allow_drop(event)" ondrop="ndphp.ide.ide_obj_draggable_drop(event);" ondragstart="ndphp.ide.ide_obj_drag_common(event, [ this.parentNode.id ])">
				<input id="type" type="hidden" value="detached" />
				<span id="title" class="title menu_obj" onClick="ndphp.ide.dialog_show(this, 'menu');" title="Detached Controllers will have no internal logic associated with it. They'll be hidden by default, no controller nor views will be created. It should be used to create tables populated by external applications that should not directly interfere with the framework.">Detached</span><br />
				<span id="name" onClick="ndphp.ide.name_click(this);">entry name</span>
				<input id="name_edit" onblur="ndphp.ide.input_blur(event);" onkeydown="return ndphp.ide.keydown(event);" type="text" />
			</div>
		</fieldset>
		<fieldset id="fields" class="pool pool_fields">
			<legend>Fields Pool</legend>
			<div id="obj_fields_entry_a" class="object" draggable="true" ondrop="ndphp.ide.ide_obj_draggable_drop(event);" ondragover="ndphp.ide.ide_obj_allow_drop(event)" ondragstart="ndphp.ide.ide_obj_drag_common(event, [ this.parentNode.id ])">
				<input id="type" type="hidden" value="text" />
				<span id="title" class="title field_obj" class="field_title" onClick="ndphp.ide.dialog_show(this, 'field');">Text</span><br />
				<span id="name" class="field_name" onClick="ndphp.ide.name_click(this);">field name</span>
				<input id="name_edit" onblur="ndphp.ide.input_blur(event);" onkeydown="return ndphp.ide.keydown(event);" type="text" />
			</div>
			<div id="obj_fields_entry_b" class="object" draggable="true" ondrop="ndphp.ide.ide_obj_draggable_drop(event);" ondragover="ndphp.ide.ide_obj_allow_drop(event)" ondragstart="ndphp.ide.ide_obj_drag_common(event, [ this.parentNode.id ])">
				<input id="type" type="hidden" value="numeric" />
				<span id="title" class="title field_obj" onClick="ndphp.ide.dialog_show(this, 'field');">Numeric</span><br />
				<span id="name" onClick="ndphp.ide.name_click(this);">field name</span>
				<input id="name_edit" onblur="ndphp.ide.input_blur(event);" onkeydown="return ndphp.ide.keydown(event);" type="text" />
			</div>
			<div id="obj_fields_entry_c" class="object" draggable="true" ondrop="ndphp.ide.ide_obj_draggable_drop(event);" ondragover="ndphp.ide.ide_obj_allow_drop(event)" ondragstart="ndphp.ide.ide_obj_drag_common(event, [ this.parentNode.id ])">
				<input id="type" type="hidden" value="time" />
				<span id="title" class="title field_obj" onClick="ndphp.ide.dialog_show(this, 'field');">Time</span><br />
				<span id="name" onClick="ndphp.ide.name_click(this);">field name</span>
				<input id="name_edit" onblur="ndphp.ide.input_blur(event);" onkeydown="return ndphp.ide.keydown(event);" type="text" />
			</div>
			<div id="obj_fields_entry_d" class="object" draggable="true" ondrop="ndphp.ide.ide_obj_draggable_drop(event);" ondragover="ndphp.ide.ide_obj_allow_drop(event)" ondragstart="ndphp.ide.ide_obj_drag_common(event, [ this.parentNode.id ])">
				<input id="type" type="hidden" value="date" />
				<span id="title" class="title field_obj" onClick="ndphp.ide.dialog_show(this, 'field');">Date</span><br />
				<span id="name" onClick="ndphp.ide.name_click(this);">field name</span>
				<input id="name_edit" onblur="ndphp.ide.input_blur(event);" onkeydown="return ndphp.ide.keydown(event);" type="text" />
			</div>
			<div id="obj_fields_entry_e" class="object" draggable="true" ondrop="ndphp.ide.ide_obj_draggable_drop(event);" ondragover="ndphp.ide.ide_obj_allow_drop(event)" ondragstart="ndphp.ide.ide_obj_drag_common(event, [ this.parentNode.id ])">
				<input id="type" type="hidden" value="datetime" />
				<span id="title" class="title field_obj" onClick="ndphp.ide.dialog_show(this, 'field');">Date &amp; Time</span><br />
				<span id="name" onClick="ndphp.ide.name_click(this);">field name</span>
				<input id="name_edit" onblur="ndphp.ide.input_blur(event);" onkeydown="return ndphp.ide.keydown(event);" type="text" />
			</div>
			<div id="obj_fields_entry_f" class="object" draggable="true" ondrop="ndphp.ide.ide_obj_draggable_drop(event);" ondragover="ndphp.ide.ide_obj_allow_drop(event)" ondragstart="ndphp.ide.ide_obj_drag_common(event, [ this.parentNode.id ])">
				<input id="type" type="hidden" value="dropdown" />
				<span id="title" class="title field_obj" onClick="ndphp.ide.dialog_show(this, 'field');">Drop-Down</span><br />
				<span id="name">menu link</span>
			</div>
			<div id="obj_fields_entry_g" class="object" draggable="true" ondrop="ndphp.ide.ide_obj_draggable_drop(event);" ondragover="ndphp.ide.ide_obj_allow_drop(event)" ondragstart="ndphp.ide.ide_obj_drag_common(event, [ this.parentNode.id ])">
				<input id="type" type="hidden" value="multiple" />
				<span id="title" class="title field_obj" onClick="ndphp.ide.dialog_show(this, 'field');">Multiple</span><br />
				<span id="name">menu link</span>
			</div>
			<div id="obj_fields_entry_h" class="object" draggable="true" ondrop="ndphp.ide.ide_obj_draggable_drop(event);" ondragover="ndphp.ide.ide_obj_allow_drop(event)" ondragstart="ndphp.ide.ide_obj_drag_common(event, [ this.parentNode.id ])">
				<input id="type" type="hidden" value="mixed" />
				<span id="title" class="title field_obj" onClick="ndphp.ide.dialog_show(this, 'field');">Mixed</span><br />
				<span id="name">menu link</span>
			</div>
			<div id="obj_fields_entry_i" class="object" draggable="true" ondrop="ndphp.ide.ide_obj_draggable_drop(event);" ondragover="ndphp.ide.ide_obj_allow_drop(event)" ondragstart="ndphp.ide.ide_obj_drag_common(event, [ this.parentNode.id ])">
				<input id="type" type="hidden" value="timer" />
				<span id="title" class="title field_obj" onClick="ndphp.ide.dialog_show(this, 'field');">Timer</span><br />
				<span id="name" onClick="ndphp.ide.name_click(this);">field name</span>
				<input id="name_edit" onblur="ndphp.ide.input_blur(event);" onkeydown="return ndphp.ide.keydown(event);" type="text" />
			</div>
			<div id="obj_fields_entry_j" class="object" draggable="true" ondrop="ndphp.ide.ide_obj_draggable_drop(event);" ondragover="ndphp.ide.ide_obj_allow_drop(event)" ondragstart="ndphp.ide.ide_obj_drag_common(event, [ this.parentNode.id ])">
				<input id="type" type="hidden" value="file" />
				<span id="title" class="title field_obj" onClick="ndphp.ide.dialog_show(this, 'field');">File</span><br />
				<span id="name" onClick="ndphp.ide.name_click(this);">field name</span>
				<input id="name_edit" onblur="ndphp.ide.input_blur(event);" onkeydown="return ndphp.ide.keydown(event);" type="text" />
			</div>
			<div id="obj_fields_entry_k" class="object" draggable="true" ondrop="ndphp.ide.ide_obj_draggable_drop(event);" ondragover="ndphp.ide.ide_obj_allow_drop(event)" ondragstart="ndphp.ide.ide_obj_drag_common(event, [ this.parentNode.id ])">
				<input id="type" type="hidden" value="separator" />
				<span id="title" class="title field_obj" onClick="ndphp.ide.dialog_show(this, 'field');">Separator</span><br />
				<span id="name" onClick="ndphp.ide.name_click(this);">field name</span>
				<input id="name_edit" onblur="ndphp.ide.input_blur(event);" onkeydown="return ndphp.ide.keydown(event);" type="text" />
			</div>
		</fieldset>
		<fieldset id="canvas" class="canvas">
			<legend>Application Model</legend>
			<fieldset id="container_menu" class="container menu" ondrop="ndphp.ide.ide_obj_container_drop(event)" ondragover="ndphp.ide.ide_obj_allow_drop(event)">
				<legend>Controllers</legend>
				<?php if (count($view['application']['menus'])): ?>
					<?php foreach ($view['application']['menus'] as $menu_item): ?>
						<div id="<?=$menu_item['obj_id']?>" class="object" oncontextmenu="ndphp.ide.menu_entry_edit_controller(event, this);" onclick="ndphp.ide.menu_entry_load_fields(this);" draggable="true" ondragover="ndphp.ide.ide_obj_allow_drop(event)" ondrop="ndphp.ide.ide_obj_draggable_drop(event);" ondragstart="ndphp.ide.ide_obj_drag_common(event, [ 'menu', 'trash' ])">
							<input id="type" type="hidden" value="<?=filter_html($menu_item['type'], $config['charset'])?>" />
							<span id="title" class="title menu_obj" onClick="ndphp.ide.dialog_show(this, 'menu');" style="cursor: pointer;"><?=filter_html(html_entity_decode($menu_item['title']), $config['charset'])?></span><br />
							<span id="name" onClick="ndphp.ide.name_click(this);" style="cursor: cell; display: inline-block; font-weight: bold;"><?=filter_html($menu_item['name'], $config['charset'])?></span>
							<input id="name_edit" onblur="ndphp.ide.input_blur(event);" onkeydown="return ndphp.ide.keydown(event);" type="text" value="<?=filter_html($menu_item['name'], $config['charset'])?>" />
							<?php if (isset($menu_item['permissions'])): ?>
								<div id="dialog_menu_settings_<?=end(explode('_', $menu_item['obj_id']))?>" class="dialog_settings">
									<fieldset id="options">
										<legend>Options</legend>
										<table class="options_table">
											<thead class="options_table_head">
												<tr class="options_table_row">
													<td class="options_table_head_field">Logging</td>
													<td class="options_table_head_field">Accounting</td>
													<td class="options_table_head_field">Linking</td>
													<td class="options_table_head_field">Hidden</td>
												</tr>
											</thead>
											<tbody class="options_table_body">
												<tr class="options_table_row">
													<td class="options_table_field"><input id="options_logging" type="checkbox" <?=$menu_item['options']['logging'] ? 'checked="checked"' : ''?> /></td>
													<td class="options_table_field"><input id="options_accounting" type="checkbox" <?=$menu_item['options']['accounting'] ? 'checked="checked"' : ''?> /></td>
													<td class="options_table_field"><input id="options_linking" type="checkbox" <?=$menu_item['options']['linking'] ? 'checked="checked"' : ''?> /></td>
													<td class="options_table_field"><input id="options_hidden" type="checkbox" <?=$menu_item['options']['hidden'] ? 'checked="checked"' : ''?> /></td>
												</tr>
											</tbody>
										</table>
									</fieldset>
									<fieldset id="properties">
										<legend>Properties</legend>
										<table class="properties_table">
											<tr class="properties_table_row">
												<td class="properties_table_field_title">Alias</td>
												<td class="properties_table_field_value"><input id="property_alias" type="text" placeholder="Default" value="<?=filter_html($menu_item['properties']['alias'], $config['charset'])?>" /></td>
											</tr>
											<tr class="properties_table_row">
												<td class="properties_table_field_title">Icon</td>
												<td class="properties_table_field_value">
													<select id="property_icon" name="property_icon">
														<option value="custom">Custom...</option>
														<?php foreach ($view['menu_icons'] as $icon): ?>
															<option value="<?=filter_html($icon, $config['charset'])?>" <?=$menu_item['properties']['icon'] == $icon ? 'selected="selected"' : ''?> ><?=explode('.', $icon)[0]?></option>
														<?php endforeach; ?>
													</select>
												</td>
											</tr>
											<tr class="properties_table_row">
												<td class="properties_table_field_title">Order Field</td>
												<td class="properties_table_field_value"><input id="property_order_field" type="text" placeholder="Id" value="<?=filter_html($menu_item['properties']['order_field'], $config['charset'])?>" /></td>
											</tr>
											<tr class="properties_table_row">
												<td class="properties_table_field_title">Order</td>
												<td class="properties_table_field_value">
													<select id="property_order_direction">
														<option value="ASC" <?=$menu_item['properties']['order_direction'] == "ASC" ? 'selected="selected"' : ''?> >Ascending Order</option>
														<option value="DESC" <?=$menu_item['properties']['order_direction'] == "DESC" ? 'selected="selected"' : ''?> >Descending Order</option>
													</select>
												</td>
											</tr>
											<tr class="properties_table_row">
												<td class="properties_table_field_title">Rows per Page</td>
												<td class="properties_table_field_value"><input id="property_rpp" type="text" placeholder="10" value="<?=$menu_item['properties']['rpp']?>" /></td>
											</tr>
											<tr class="constraints_table_row">
												<td class="properties_table_field_title">Help</td>
												<td class="properties_table_field_value"><textarea id="property_help" type="text" placeholder="This menu purpose is ..."><?=filter_html($menu_item['properties']['help'], $config['charset'])?></textarea></td>
											</tr>
										</table>
									</fieldset>
									<fieldset id="permissions">
										<legend>Permissions</legend>
										<select id="perm_roles_create" multiple="multiple">
											<?php foreach ($view['roles'] as $row): ?>
												<option value="<?=$row['role']?>" <?=in_array($row['role'], $menu_item['permissions']['create']) ? 'selected="selected"' : ''?> ><?=$row['role']?>_CREATE</option>
											<?php endforeach; ?>
										</select>
										<select id="perm_roles_read" multiple="multiple">
											<?php foreach ($view['roles'] as $row): ?>
												<option value="<?=$row['role']?>" <?=in_array($row['role'], $menu_item['permissions']['read']) ? 'selected="selected"' : ''?> ><?=$row['role']?>_READ</option>
											<?php endforeach; ?>
										</select>
										<select id="perm_roles_update" multiple="multiple">
											<?php foreach ($view['roles'] as $row): ?>
												<option value="<?=$row['role']?>" <?=in_array($row['role'], $menu_item['permissions']['update']) ? 'selected="selected"' : ''?> ><?=$row['role']?>_UPDATE</option>
											<?php endforeach; ?>
										</select>
										<select id="perm_roles_delete" multiple="multiple">
											<?php foreach ($view['roles'] as $row): ?>
												<option value="<?=$row['role']?>" <?=in_array($row['role'], $menu_item['permissions']['delete']) ? 'selected="selected"' : ''?> ><?=$row['role']?>_DELETE</option>
											<?php endforeach; ?>
										</select>
									</fieldset>
								</div>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			</fieldset>
			<?php if (count($view['application']['menus'])): ?>
				<?php foreach ($view['application']['menus'] as $menu_item): ?>
					<fieldset id="container_fields_<?=$menu_item['obj_id']?>" class="container fields" ondrop="ndphp.ide.ide_obj_container_drop(event)" ondragover="ndphp.ide.ide_obj_allow_drop(event)">
						<legend>Fields</legend>
						<?php foreach ($menu_item['fields'] as $field_item): ?>
							<div id="<?=$field_item['obj_id']?>" class="object" draggable="true" ondrop="ndphp.ide.ide_obj_draggable_drop(event);" ondragover="ndphp.ide.ide_obj_allow_drop(event)" ondragstart="ndphp.ide.ide_obj_drag_common(event, [ 'fields', 'trash' ])">
								<input id="type" type="hidden" value="<?=filter_html($field_item['type'], $config['charset'])?>" />
								<span id="title" class="title field_obj" onClick="ndphp.ide.dialog_show(this, 'field');" style="cursor: context-menu;"><?=filter_html(html_entity_decode($field_item['title']), $config['charset'])?></span><br />
								<?php if ($field_item['type'] != "dropdown" && $field_item['type'] != "multiple" && $field_item['type'] != "mixed"): ?>
									<span id="name" class="field_name" onClick="ndphp.ide.name_click(this);" style="cursor: cell; display: inline-block; font-weight: bold;"><?=filter_html($field_item['name'], $config['charset'])?></span>
									<input id="name_edit" onblur="ndphp.ide.input_blur(event);" onkeydown="return ndphp.ide.keydown(event);" type="text" value="<?=filter_html($field_item['name'], $config['charset'])?>" />
								<?php else: ?>
									<span id="name" class="field_name" style="display: inline-block; font-weight: bold;"><?=filter_html($field_item['name'], $config['charset'])?></span>
								<?php endif; ?>
								<?php if (isset($field_item['permissions'])): ?>
									<div id="dialog_field_settings_<?=end(explode('_', $field_item['obj_id']))?>" class="dialog_settings">
										<fieldset id="constraints">
											<legend>Constraints</legend>
											<table class="constraints_table">
												<thead class="contraints_table_head">
													<tr class="contraints_table_row">
														<td class="constraints_table_head_field">Required</td>
														<td class="constraints_table_head_field">Unique</td>
														<td class="constraints_table_head_field">Hidden</td>
													</tr>
												</thead>
												<tbody class="contraints_table_body">
													<tr class="contraints_table_row">
														<td class="constraints_table_field"><input id="constraint_required" type="checkbox" <?=$field_item['constraints']['required'] ? 'checked="checked"' : ''?> /></td>
														<td class="constraints_table_field"><input id="constraint_unique" type="checkbox" <?=$field_item['constraints']['unique'] ? 'checked="checked"' : ''?> /></td>
														<td class="constraints_table_field"><input id="constraint_hidden" type="checkbox" <?=$field_item['constraints']['hidden'] ? 'checked="checked"' : ''?> /></td>
													</tr>
												</tbody>
											</table>
										</fieldset>
										<fieldset id="properties">
											<legend>Properties</legend>
											<table class="properties_table">
												<tr class="properties_table_row">
													<td class="properties_table_field_title">Alias</td>
													<td class="properties_table_field_value"><input id="property_alias" type="text" placeholder="Default" value="<?=filter_html($field_item['properties']['alias'], $config['charset'])?>" /></td>
												</tr>
												<tr class="properties_table_row">
													<td class="properties_table_field_title">Placeholder</td>
													<td class="properties_table_field_value"><input id="property_placeholder" type="text" placeholder="Default field value..." value="<?=filter_html($field_item['properties']['placeholder'], $config['charset'])?>" /></td>
												</tr>
												<tr class="properties_table_row">
													<td class="properties_table_field_title">Length</td>
													<td class="properties_table_field_value"><input id="property_length" type="numeric" placeholder="255" value="<?=filter_html($field_item['properties']['len'], $config['charset'])?>" /></td>
												</tr>
												<tr class="properties_table_row">
													<td class="properties_table_field_title">Units</td>
													<td class="properties_table_field_value"><input id="property_units" type="text" placeholder="Unit" value="<?=filter_html($field_item['properties']['units'], $config['charset'])?>" /></td>
												</tr>
												<tr class="constraints_table_row">
													<td class="properties_table_field_title">Help</td>
													<td class="properties_table_field_value"><textarea id="property_help" type="text" placeholder="This field purpose is ..."><?=filter_html($field_item['properties']['help'], $config['charset'])?></textarea></td>
												</tr>
											</table>
										</fieldset>
										<fieldset id="visualization">
											<legend>Visualization</legend>
											<table class="visualization_table">
												<thead class="visualization_table_head">
													<tr class="visualization_table_row">
														<td class="visualization_table_head_field">Create</td>
														<td class="visualization_table_head_field">View</td>
														<td class="visualization_table_head_field">Edit</td>
														<td class="visualization_table_head_field">Remove</td>
														<td class="visualization_table_head_field">List</td>
														<td class="visualization_table_head_field">Result</td>
														<td class="visualization_table_head_field">Search</td>
														<td class="visualization_table_head_field">Export</td>
														<td class="visualization_table_head_field">Mixed</td>
													</tr>
												</thead>
												<tbody class="visualization_table_body">
													<tr class="visualization_table_row">
														<td class="visualization_table_field"><input id="visual_show_create" type="checkbox" <?=$field_item['visualization']['create'] ? 'checked="checked"' : ''?> /></td>
														<td class="visualization_table_field"><input id="visual_show_view" type="checkbox" <?=$field_item['visualization']['view'] ? 'checked="checked"' : ''?> /></td>
														<td class="visualization_table_field"><input id="visual_show_edit" type="checkbox" <?=$field_item['visualization']['edit'] ? 'checked="checked"' : ''?> /></td>
														<td class="visualization_table_field"><input id="visual_show_remove" type="checkbox" <?=$field_item['visualization']['remove'] ? 'checked="checked"' : ''?> /></td>
														<td class="visualization_table_field"><input id="visual_show_list" type="checkbox" <?=$field_item['visualization']['list'] ? 'checked="checked"' : ''?> /></td>
														<td class="visualization_table_field"><input id="visual_show_result" type="checkbox" <?=$field_item['visualization']['result'] ? 'checked="checked"' : ''?> /></td>
														<td class="visualization_table_field"><input id="visual_show_search" type="checkbox" <?=$field_item['visualization']['search'] ? 'checked="checked"' : ''?> /></td>
														<td class="visualization_table_field"><input id="visual_show_export" type="checkbox" <?=$field_item['visualization']['export'] ? 'checked="checked"' : ''?> /></td>
														<td class="visualization_table_field"><input id="visual_show_mixed" type="checkbox" <?=$field_item['visualization']['mixed'] ? 'checked="checked"' : ''?> /></td>
													</tr>
												</tbody>
											</table>
										</fieldset>
										<fieldset id="permissions">
											<legend>Permissions</legend>
											<select id="perm_roles_create" multiple="multiple">
												<?php foreach ($view['roles'] as $row): ?>
													<option value="<?=$row['role']?>" <?=in_array($row['role'], $field_item['permissions']['create']) ? 'selected="selected"' : ''?> ><?=$row['role']?>_CREATE</option>
												<?php endforeach; ?>
											</select>
											<select id="perm_roles_read" multiple="multiple">
												<?php foreach ($view['roles'] as $row): ?>
													<option value="<?=$row['role']?>" <?=in_array($row['role'], $field_item['permissions']['read']) ? 'selected="selected"' : ''?> ><?=$row['role']?>_READ</option>
												<?php endforeach; ?>
											</select>
											<select id="perm_roles_update" multiple="multiple">
												<?php foreach ($view['roles'] as $row): ?>
													<option value="<?=$row['role']?>" <?=in_array($row['role'], $field_item['permissions']['update']) ? 'selected="selected"' : ''?> ><?=$row['role']?>_UPDATE</option>
												<?php endforeach; ?>
											</select>
											<select id="perm_roles_search" multiple="multiple">
												<?php foreach ($view['roles'] as $row): ?>
													<option value="<?=$row['role']?>" <?=in_array($row['role'], $field_item['permissions']['search']) ? 'selected="selected"' : ''?> ><?=$row['role']?>_SEARCH</option>
												<?php endforeach; ?>
											</select>
										</fieldset>
									</div>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					</fieldset>
					<fieldset id="container_controller_<?=$menu_item['obj_id']?>" class="container controller">
						<legend>Controller</legend>
							
								<?php if ($menu_item['controller']['code']): ?>
									<textarea id="textarea_ide_<?=$menu_item['obj_id']?>" class="ide_code_editarea" disabled><?=$menu_item['controller']['code']?></textarea>
								<?php else: ?>
									<textarea id="textarea_ide_<?=$menu_item['obj_id']?>" class="ide_code_editarea" disabled>/** Hooks - Documentation: <?=base_url()?>index.php/documentation/hooks **/

/** Other overloads - Documentation: <?=base_url()?>index.php/documentation/overloads **/

/** Custom methods **/

	</textarea>
								<?php endif; ?>
							</textarea>
					</fieldset>
				<?php endforeach; ?>
			<?php endif; ?>
			<fieldset id="container_fields" class="container fields" ondrop="ndphp.ide.ide_obj_container_drop(event)" ondragover="ndphp.ide.ide_obj_allow_drop(event)">
				<legend>Fields</legend>
			</fieldset>
			<fieldset id="container_controller" class="container controller">
				<legend>Controller</legend>
					<textarea id="textarea_ide" class="ide_code_editarea" disabled>/** Hooks - Documentation: <?=base_url()?>index.php/documentation/hooks **/

/** Other overloads - Documentation: <?=base_url()?>index.php/documentation/overloads **/

/** Custom methods **/

</textarea>
			</fieldset>
			<div id="actions" class="actions">
				<!-- <input type="button" class="button_action" onClick="window.open('<?=base_url()?>index.php/configuration');" value="Config" /> -->
				<input type="button" class="button_action" onClick="ndphp.ide.check();" value="Check" />
				<input type="button" class="button_action" onClick="ndphp.ide.save();" value="Save" />
				<input type="button" class="button_action" onClick="ndphp.ide.deploy();" value="Deploy" />
			</div>
		</fieldset>
	</div>
	<div id="dialog_menu_settings" class="dialog_settings">
		<fieldset id="options">
			<legend>Options</legend>
			<table class="options_table">
				<thead class="options_table_head">
					<tr class="options_table_row">
						<td class="options_table_head_field">Logging</td>
						<td class="options_table_head_field">Accounting</td>
						<td class="options_table_head_field">Linking</td>
						<td class="options_table_head_field">Hidden</td>
					</tr>
				</thead>
				<tbody class="options_table_body">
					<tr class="options_table_row">
						<td class="options_table_field"><input id="options_logging" type="checkbox" checked="checked" /></td>
						<td class="options_table_field"><input id="options_accounting" type="checkbox" checked="checked" /></td>
						<td class="options_table_field"><input id="options_linking" type="checkbox" checked="checked" /></td>
						<td class="options_table_field"><input id="options_hidden" type="checkbox" /></td>
					</tr>
				</tbody>
			</table>
		</fieldset>
		<fieldset id="properties">
			<legend>Properties</legend>
			<table class="properties_table">
				<tr class="properties_table_row">
					<td class="properties_table_field_title">Alias</td>
					<td class="properties_table_field_value"><input id="property_alias" type="text" placeholder="Default" /></td>
				</tr>
				<tr class="properties_table_row">
					<td class="properties_table_field_title">Icon</td>
					<td class="properties_table_field_value">
						<select id="property_icon" name="property_icon">
							<option value="Empty button.png">Custom...</option>
							<?php foreach ($view['menu_icons'] as $icon): ?>
								<option value="<?=$icon?>"><?=explode('.', $icon)[0]?></option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
				<tr class="properties_table_row">
					<td class="properties_table_field_title">Order Field</td>
					<td class="properties_table_field_value"><input id="property_order_field" type="text" placeholder="Id" /></td>
				</tr>
				<tr class="properties_table_row">
					<td class="properties_table_field_title">Order</td>
					<td class="properties_table_field_value">
						<select id="property_order_direction">
							<option value="ASC" selected="selected">Ascending Order</option>
							<option value="DESC">Descending Order</option>
						</select>
					</td>
				</tr>
				<tr class="properties_table_row">
					<td class="properties_table_field_title">Rows per Page</td>
					<td class="properties_table_field_value"><input id="property_rpp" type="text" placeholder="10" /></td>
				</tr>
				<tr class="constraints_table_row">
					<td class="properties_table_field_title">Help</td>
					<td class="properties_table_field_value"><textarea id="property_help" type="text" placeholder="This menu purpose is ..."></textarea></td>
				</tr>
			</table>
		</fieldset>
		<fieldset id="permissions">
			<legend>Permissions</legend>
			<select id="perm_roles_create" multiple="multiple">
				<?php foreach ($view['roles'] as $row): ?>
					<option value="<?=$row['role']?>" <?=$row['role'] == 'ROLE_ADMIN' ? 'selected="selected"' : ''?> ><?=$row['role']?>_CREATE</option>
				<?php endforeach; ?>
			</select>
			<select id="perm_roles_read" multiple="multiple">
				<?php foreach ($view['roles'] as $row): ?>
					<option value="<?=$row['role']?>" <?=$row['role'] == 'ROLE_ADMIN' ? 'selected="selected"' : ''?> ><?=$row['role']?>_READ</option>
				<?php endforeach; ?>
			</select>
			<select id="perm_roles_update" multiple="multiple">
				<?php foreach ($view['roles'] as $row): ?>
					<option value="<?=$row['role']?>" <?=$row['role'] == 'ROLE_ADMIN' ? 'selected="selected"' : ''?> ><?=$row['role']?>_UPDATE</option>
				<?php endforeach; ?>
			</select>
			<select id="perm_roles_delete" multiple="multiple">
				<?php foreach ($view['roles'] as $row): ?>
					<option value="<?=$row['role']?>" <?=$row['role'] == 'ROLE_ADMIN' ? 'selected="selected"' : ''?> ><?=$row['role']?>_DELETE</option>
				<?php endforeach; ?>
			</select>
		</fieldset>
	</div>
	<div id="dialog_field_settings" class="dialog_settings">
		<fieldset id="constraints">
			<legend>Constraints</legend>
			<table class="constraints_table">
				<thead class="contraints_table_head">
					<tr class="contraints_table_row">
						<td class="constraints_table_head_field">Required</td>
						<td class="constraints_table_head_field">Unique</td>
						<td class="constraints_table_head_field">Hidden</td>
					</tr>
				</thead>
				<tbody class="contraints_table_body">
					<tr class="contraints_table_row">
						<td class="constraints_table_field"><input id="constraint_required" type="checkbox" /></td>
						<td class="constraints_table_field"><input id="constraint_unique" type="checkbox" /></td>
						<td class="constraints_table_field"><input id="constraint_hidden" type="checkbox" /></td>
					</tr>
				</tbody>
			</table>
		</fieldset>
		<fieldset id="properties">
			<legend>Properties</legend>
			<table class="properties_table">
				<tr class="properties_table_row">
					<td class="properties_table_field_title">Alias</td>
					<td class="properties_table_field_value"><input id="property_alias" type="text" placeholder="Default" /></td>
				</tr>
				<tr class="properties_table_row">
					<td class="properties_table_field_title">Placeholder</td>
					<td class="properties_table_field_value"><input id="property_placeholder" type="text" placeholder="Default field value..." /></td>
				</tr>
				<tr class="properties_table_row">
					<td class="properties_table_field_title">Length</td>
					<td class="properties_table_field_value"><input id="property_length" type="numeric" placeholder="255" /></td>
				</tr>
				<tr class="properties_table_row">
					<td class="properties_table_field_title">Units</td>
					<td class="properties_table_field_value"><input id="property_units" type="text" placeholder="Unit" /></td>
				</tr>
				<tr class="constraints_table_row">
					<td class="properties_table_field_title">Help</td>
					<td class="properties_table_field_value"><textarea id="property_help" type="text" placeholder="This field purpose is ..."></textarea></td>
				</tr>
			</table>
		</fieldset>
		<fieldset id="visualization">
			<legend>Visualization</legend>
			<table class="visualization_table">
				<thead class="visualization_table_head">
					<tr class="visualization_table_row">
						<td class="visualization_table_head_field">Create</td>
						<td class="visualization_table_head_field">View</td>
						<td class="visualization_table_head_field">Edit</td>
						<td class="visualization_table_head_field">Remove</td>
						<td class="visualization_table_head_field">List</td>
						<td class="visualization_table_head_field">Result</td>
						<td class="visualization_table_head_field">Search</td>
						<td class="visualization_table_head_field">Export</td>
						<td class="visualization_table_head_field">Mixed</td>
					</tr>
				</thead>
				<tbody class="visualization_table_body">
					<tr class="visualization_table_row">
						<td class="visualization_table_field"><input id="visual_show_create" type="checkbox" checked="checked" /></td>
						<td class="visualization_table_field"><input id="visual_show_view" type="checkbox" checked="checked" /></td>
						<td class="visualization_table_field"><input id="visual_show_edit" type="checkbox" checked="checked" /></td>
						<td class="visualization_table_field"><input id="visual_show_remove" type="checkbox" checked="checked" /></td>
						<td class="visualization_table_field"><input id="visual_show_list" type="checkbox" checked="checked" /></td>
						<td class="visualization_table_field"><input id="visual_show_result" type="checkbox" checked="checked" /></td>
						<td class="visualization_table_field"><input id="visual_show_search" type="checkbox" checked="checked" /></td>
						<td class="visualization_table_field"><input id="visual_show_export" type="checkbox" checked="checked" /></td>
						<td class="visualization_table_field"><input id="visual_show_mixed" type="checkbox" checked="checked" /></td>
					</tr>
				</tbody>
			</table>
		</fieldset>
		<fieldset id="permissions">
			<legend>Permissions</legend>
			<select id="perm_roles_create" multiple="multiple">
				<?php foreach ($view['roles'] as $row): ?>
					<option value="<?=$row['role']?>" <?=$row['role'] == 'ROLE_ADMIN' ? 'selected="selected"' : ''?> ><?=$row['role']?>_CREATE</option>
				<?php endforeach; ?>
			</select>
			<select id="perm_roles_read" multiple="multiple">
				<?php foreach ($view['roles'] as $row): ?>
					<option value="<?=$row['role']?>" <?=$row['role'] == 'ROLE_ADMIN' ? 'selected="selected"' : ''?> ><?=$row['role']?>_READ</option>
				<?php endforeach; ?>
			</select>
			<select id="perm_roles_update" multiple="multiple">
				<?php foreach ($view['roles'] as $row): ?>
					<option value="<?=$row['role']?>" <?=$row['role'] == 'ROLE_ADMIN' ? 'selected="selected"' : ''?> ><?=$row['role']?>_UPDATE</option>
				<?php endforeach; ?>
			</select>
			<select id="perm_roles_search" multiple="multiple">
				<?php foreach ($view['roles'] as $row): ?>
					<option value="<?=$row['role']?>" <?=$row['role'] == 'ROLE_ADMIN' ? 'selected="selected"' : ''?> ><?=$row['role']?>_SEARCH</option>
				<?php endforeach; ?>
			</select>
		</fieldset>
	</div>
</body>
</html>
