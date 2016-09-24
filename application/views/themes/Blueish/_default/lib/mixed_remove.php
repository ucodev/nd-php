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

/*
 *
 *
 *  +----------------------+
 *  | Variable information |
 *  +----------------------+
 *
 *
 *  +----------------+---------------+-------------------------------------------------------+
 *  | Variable Name  | Type          | Description                                           |
 *  +----------------+---------------+-------------------------------------------------------+
 *  | $config        | array() assoc | Configuration data: Charset, theme, features, ...     |
 *  | $view          | array() assoc | View data: Field meta data, values, ...               |
 *  | $project       | array() assoc | Project information: Name, Tagline, Description, ...  |
 *  | $session       | array() assoc | Session data: Contains all session K/V pairs, ...     |
 *  | $security      | array() assoc | Security information: Role access, user info, ...     |
 *  +----------------+---------------+-------------------------------------------------------+
 *
 *  - Use the browser's 'D' access key in any ND PHP Framework page to access extended documentation.
 *  
 */

 ?>

<?php
	foreach ($view['fields'] as $field => $meta):
		if ($meta['type'] == 'mixed'):
?>
			<script type="text/javascript">
				var mixed_item_<?=filter_js_special($meta['rel_table'], $config['charset'])?> = 1;

				mixed_populate_items_<?=filter_js_special($meta['rel_table'], $config['charset'])?> = function(count) {
					while (mixed_item_<?=filter_js_special($meta['rel_table'], $config['charset'])?> <= count) {
						ndphp.mixed.deploy_item_view('<?=filter_js_str(base_url(), $config['charset'])?>', '<?=filter_js_str($view['ctrl'], $config['charset'])?>', <?=filter_js_special($view['id'], $config['charset'])?>, 'mixed_<?=filter_js_str($meta['rel_table'], $config['charset'])?>', mixed_item_<?=filter_js_special($meta['rel_table'], $config['charset'])?>, '<?=filter_js_str($meta['rel_table'], $config['charset'])?>', '<?=filter_js_str($meta['mixed_first_field'], $config['charset'])?>');

						mixed_item_<?=filter_js_special($meta['rel_table'], $config['charset'])?> ++;
					}
				};

				jQuery.ajax({
					type: "POST",
					url: "<?=filter_js_str(base_url(), $config['charset'])?>index.php/<?=filter_js_str($meta['rel_table'], $config['charset'])?>/remove_mixed_rel_count/<?=filter_js_str($view['ctrl'], $config['charset'])?>/<?=filter_js_str($view['id'], $config['charset'])?>",
					success: function(data) {
						mixed_populate_items_<?=filter_js_special($meta['rel_table'], $config['charset'])?>(parseInt(data));
					},
					error: function(xhr, ajaxOptions, thrownError) {
						jQuery("#ajax_error_dialog").html('<?=filter_html_js_str(NDPHP_LANG_MOD_UNABLE_RETRIEVE_RELATED_ITEM, $config['charset'])?> <?=filter_html_js_str($meta['rel_table'], $config['charset'])?>.<br /><br /><span style="font-weight: bold"><?=filter_html_js_str(ucfirst(NDPHP_LANG_MOD_WORD_REASON), $config['charset'])?>:</span> ' + xhr.responseText);
						jQuery("#ajax_error_dialog").dialog({ modal: true, title: '<?=filter_html_js_str(NDPHP_LANG_MOD_CANNOT_LOAD_DATA, $config['charset'])?>' });
					}
				});
			</script>
			<div class="tab-pane fade" id="mixed_<?=filter_html_special($meta['rel_table'], $config['charset'])?>_container">
				<fieldset class="form_fieldset">
					<div class="table-responsive">
						<table id="mixed_<?=filter_html_special($meta['rel_table'], $config['charset'])?>" class="table table-striped table-hover">
							<tr class="fields">
								<?php foreach ($meta['mixed_fields_alias'] as $mixed_field_alias): ?>
									<th class="fields"><?=filter_html(ucfirst($mixed_field_alias['alias']), $config['charset'])?></th>
								<?php endforeach; ?>
							</tr>
						</table>
					</div>
				</fieldset>
			</div>
<?php
		endif;
	endforeach;
?>