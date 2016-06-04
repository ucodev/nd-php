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
	if ($config['choices']):
		$hidden_rows = array();
		$choice_select = array();
		foreach ($config['choices'] as $rel_field => $rel_choices) {
			array_push($choice_select, $rel_field);
		}

		$process_choice_field = array();
		foreach ($view['fields'] as $field => $meta):
			if (!in_array($field, $choice_select)):
				array_push($hidden_rows, $field);
			else:
				array_push($process_choice_field, $field);
			endif;
		endforeach;

		foreach ($config['choices'] as $rel_field => $rel_choices):
?>
			<script type="text/javascript">
				<?php if (isset($choices_create) || isset($choices_edit)): ?>
					selected_<?=filter_js_special($view['ctrl'], $config['charset'])?>_<?=isset($choices_create) ? 'create' : ''?><?=isset($choices_edit) ? 'edit' : ''?>_choice_<?=filter_js_special($rel_field, $config['charset'])?> = function() {
				<?php else: ?>
					jQuery(document).ready(function() {
				<?php endif; ?>
						var choice = jQuery('#<?=filter_js_str($rel_field, $config['charset'])?>').val();
						<?php foreach ($hidden_rows as $hide_field): ?>
							jQuery('#<?=filter_js_str($hide_field, $config['charset'])?>_row').hide();
						<?php endforeach; ?>
						<?php foreach ($rel_choices as $choice => $hidden_fields): ?>
							if (choice == '<?=filter_js_str($choice, $config['charset'])?>') {
								<?php foreach (array_diff($hidden_rows, $hidden_fields) as $show_field): ?>
									jQuery('#<?=filter_js_str($show_field, $config['charset'])?>_row').show();
								<?php endforeach; ?>
							}
						<?php endforeach; ?>
				<?php if (isset($choices_create) || isset($choices_edit)): ?>
					};

					/* Grant that the option selected by default hides the corresponding fields when page finishes loading */
					jQuery(document).ready(function() {
						selected_<?=filter_js_special($view['ctrl'], $config['charset'])?>_<?=isset($choices_create) ? 'create' : ''?><?=isset($choices_edit) ? 'edit' : ''?>_choice_<?=filter_js_special($rel_field, $config['charset'])?>();
					});
				<?php else: ?>
					});
				<?php endif; ?>
			</script>
<?php
		endforeach;
	endif;
?>