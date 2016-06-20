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
 <div id="result" class="result">
 	<?php $tabs_listing = true; include($view['base_dir'] . '/_default/lib/tabs_header.php'); ?>

	<div class="list_container">
		<div id="listing">
			<?php if (count($view['result_array'])): ?>
				<table class="result">
				<tr class="result">
				<?php $row = array_values($view['result_array'])[0]; ?>

				<?php foreach ($row as $field => $value): ?>
					<?php
						/* Ignore fields without meta data */
						if (!isset($view['fields'][$field]))
							continue;

						/* Ignore hidden fields */
						if (in_array($field, $config['hidden_fields']))
							continue;

						/* Ignore separators */
						if ($view['fields'][$field]['type'] == 'separator')
							continue;
					?>
					<th class="result">
						<a href="<?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($view['ctrl'], $config['charset'])?>/result_data_ajax/query/<?=filter_html($view['result_query'], $config['charset'])?>/<?=filter_html($field, $config['charset'])?>/<?=filter_html($config['order'], $config['charset'])?>/<?=filter_html($view['page'], $config['charset'])?>" onclick="ndphp.ajax.load_data_ordered_result(event, '<?=filter_html_js_str($view['ctrl'], $config['charset'])?>', '<?=filter_html_js_str($view['result_query'], $config['charset'])?>', '<?=filter_html_js_str($field, $config['charset'])?>', '<?=filter_html_js_str($config['order'], $config['charset'])?>', '<?=filter_html_js_str($view['page'], $config['charset'])?>');" title="<?=filter_html(NDPHP_LANG_MOD_OP_LIST_ORDER_BY, $config['charset'])?> <?=filter_html(ucfirst(isset($view['fields'][$field]['viewname']) ? $view['fields'][$field]['viewname'] : $field), $config['charset'])?>">
							<?=filter_html(ucfirst(isset($view['fields'][$field]['viewname']) ? $view['fields'][$field]['viewname'] : $field), $config['charset'])?>
						</a>
						<?php if ($config['order_by'] == $field) { echo('&nbsp;'); echo($config['order'] == 'desc' ? '&uarr;' : '&darr;'); }?>
					</th>
				<?php endforeach; ?>

				<?php if (isset($row['id'])): ?>
						<th class="list">
							&nbsp;
						</th>
				<?php endif; ?>

				</tr>
				<?php $i = 0; foreach ($view['result_array'] as $row): ?>
					<?php
						/* Setup proper table row class based on controller configuration */
						$tr_class = 'result_' . ($i % 2 ? 'even' : 'odd');

						if (count($config['choices_class']) && isset($row[$config['choices_class']['rel_field']])) {
							if (isset($config['choices_class']['values'][$row[$config['choices_class']['rel_field']]])) {
								$tr_class = 'result_' . $config['choices_class']['values'][$row[$config['choices_class']['rel_field']]];
							} else {
								$tr_class = 'result_' . ($i % 2 ? $config['choices_class']['class_even'] : $config['choices_class']['class_odd']);
							}
						}
					?>

					<tr class="<?=filter_html($tr_class, $config['charset'])?>">
					<?php foreach ($row as $field => $value): ?>
						<?php
							/* Ignore fields without meta data */
							if (!isset($view['fields'][$field]))
								continue;

							/* Ignore hidden fields */
							if (in_array($field, $config['hidden_fields']))
								continue;

							/* Ignore separators */
							if ($view['fields'][$field]['type'] == 'separator')
								continue;
						?>
							<td class="result">
							<?php if ($view['fields'][$field]['input_type'] == 'checkbox'): ?>
									<?=($value == 1 ? filter_html(NDPHP_LANG_MOD_STATUS_CHECKBOX_CHECKED, $config['charset']) : filter_html(NDPHP_LANG_MOD_STATUS_CHECKBOX_UNCHECKED, $config['charset']))?>
							<?php elseif (($view['fields'][$field]['input_type'] == 'select') &&
												($view['fields'][$field]['type'] != 'rel') &&
												($config['fk_linking'] === true)): ?>
								<?php
									/* Output referal link to foreign field */
									/* FIXME: Implement caching for fields already resolved.
									 * 			OR
									 * 		  Query the database on the controller to output a resolve
									 * 		  table to be used here as a resolver array (key => value),
									 * 		  being the key the field value, and the value the field id.
									 */
									foreach ($view['fields'][$field]['options'] as $opt_id => $opt_value):
										if ($value == $opt_value):
								?>
											<a href="<?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($view['fields'][$field]['table'], $config['charset'])?>/view_data_modalbox/<?=filter_html($opt_id, $config['charset'])?>" title="<?=filter_html(NDPHP_LANG_MOD_OP_QUICK_VIEW, $config['charset'])?>" onclick="Modalbox.show(this.href, {title: this.title, width: 600}); return false;">
												<?=filter_html($opt_value, $config['charset'])?>
											</a>
								<?php
										endif;
									endforeach;
								?>
							<?php elseif ($view['fields'][$field]['input_type'] == 'file'): ?>
									<!-- FIXME: We're using $row['id'] in the URL, but this field may be hidden, thus not available... -->
									<a id="<?=filter_html_special($field, $config['charset'])?>_<?=$i?>" target="_blank" title="<?=filter_html($value, $config['charset'])?>" href="<?=filter_html(base_url(), $config['charset'])?>index.php/files/access/<?=filter_html($view['ctrl'], $config['charset'])?>/<?=filter_html($row['id'], $config['charset'])?>/<?=filter_html($field, $config['charset'])?>/<?=filter_html($value, $config['charset'])?>">
										<?php if ($config['render']['images'] && in_array(end(explode('.', $value)), $config['render']['ext'])): ?>
											<img alt="<?=filter_html($value, $config['charset'])?>" style="width: <?=filter_html($config['render']['size']['width'], $config['charset'])?>; height: <?=filter_html($config['render']['size']['height'], $config['charset'])?>;" src="<?=filter_html(base_url(), $config['charset'])?>index.php/files/access/<?=filter_html($view['ctrl'], $config['charset'])?>/<?=filter_html($row['id'], $config['charset'])?>/<?=filter_html($field, $config['charset'])?>/<?=filter_html($value, $config['charset'])?>" />
										<?php else: ?>
											<?=filter_html($value, $config['charset'])?>
										<?php endif; ?>
									</a>
							<?php else: ?>
								<?php if ($field == 'id'): ?>
									<a href="<?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($view['ctrl'], $config['charset'])?>/view/<?=filter_html($value, $config['charset'])?>" onclick="ndphp.ajax.load_body_view(event, '<?=filter_html_js_str($view['ctrl'], $config['charset'])?>', <?=filter_html_js_special($value, $config['charset'])?>);" title="<?=filter_html(NDPHP_LANG_MOD_OP_LIST_VIEW_ITEM, $config['charset'])?> <?=filter_html($value, $config['charset'])?>">
										<?=filter_html($value, $config['charset'])?>
									</a>
								<?php else: ?>
									<?php if ($view['fields'][$field]['units']['unit'] && $view['fields'][$field]['units']['left']): ?>
										<?=filter_html($view['fields'][$field]['units']['unit'], $config['charset'])?>
									<?php endif; ?>
									<?=truncate_str($value, $config['truncate']['length'], $config['charset'], $config['truncate']['trail'], $config['truncate']['separator'])?>
									<?php if ($view['fields'][$field]['units']['unit'] && !$view['fields'][$field]['units']['left']): ?>
										<?=filter_html($view['fields'][$field]['units']['unit'], $config['charset'])?>
									<?php endif; ?>
								<?php endif; ?>
							<?php endif; ?>
							</td>
					<?php endforeach; ?>

					<?php if (isset($row['id'])): ?>
							<td class="list_op">
								<?php foreach ($view['links']['quick'] as $link):
										/* $link[0] - Description
										 * $link[1] - Permission (sec_perm)
										 * $link[2] - Function
										 * $link[3] - Image Path
										 * $link[4] - Modal width
										 */
										if (!security_perm_check($security['perms'], $link[1], $view['ctrl']))
											continue;
								?>
									<a href="<?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($view['ctrl'], $config['charset'])?>/<?=filter_html($link[2], $config['charset'])?>/<?=filter_html($row['id'], $config['charset'])?>" title="<?=filter_html($link[0], $config['charset'])?>" onclick="Modalbox.show(this.href, {title: this.title, width: <?=filter_html_js_special($link[4], $config['charset'])?>}); return false;">
										<img height="20" width="20" class="list_op_icon" alt="<?=filter_html($link[0], $config['charset'])?>" src="<?=filter_html(static_images_url(), $config['charset'])?>/themes/<?=filter_html($config['theme']['name'], $config['charset'])?>/<?=filter_html($link[3], $config['charset'])?>" />
									</a>
								<?php endforeach; ?>
							</td>
					<?php endif; ?>
					</tr>	
				<?php $i ++; endforeach; ?>
				</table>
				<div id="listing_footer">
					<div id="total_items" class="total_items">
						<span><?=filter_html($view['total_items_from'], $config['charset'])?>-<?=filter_html($view['total_items_to'], $config['charset'])?> <?=filter_html(NDPHP_LANG_MOD_WORD_OF, $config['charset'])?> <?=filter_html($view['total_items'], $config['charset'])?> <?=filter_html(NDPHP_LANG_MOD_WORD_ROWS, $config['charset'])?></span>
					</div>
					<div id="pagination" class="pagination">
						<?=stripslashes($view['links']['pagination'])?>
					</div>
				</div>
			<?php else: ?>
					<p class="no_results"><?=filter_html(NDPHP_LANG_MOD_EMPTY_RESULTS, $config['charset'])?></p>
			<?php endif; ?>
			<!-- Result Query -->
			<input id="result_query" type="hidden" value="<?=filter_html($view['result_query'], $config['charset']) . '/' . filter_html($config['order_by'], $config['charset']) . '/' . ($config['order'] == 'asc' ? 'desc' : 'asc') . '/' . filter_html($view['page'], $config['charset'])?>" />
		</div>
		<div id="charts">
			<?php if ($config['charts']['total']): ?>
				<?php include($view['base_dir'] . '/_default/lib/charts.php'); ?>
			<?php else: ?>
				<p class="no_charts"><?=filter_html(NDPHP_LANG_MOD_EMPTY_CHARTS, $config['charset'])?></p>
			<?php endif; ?>
		</div>
	</div>
	<?php include($view['base_dir'] . '/_default/lib/tabs_footer.php'); ?>
</div>
