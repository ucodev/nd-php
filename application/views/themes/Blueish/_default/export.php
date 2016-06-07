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
<?="<?xml version=\"1.0\" encoding=\"" . filter_html(strtolower($config['charset']), $config['charset']) . "\"?>\n"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US" xml:lang="en-US">
<head>
	<title><?=filter_html($view['title'], $config['charset'])?></title>
	<meta http-equiv="content-type" content="application/xhtml+xml; charset=<?=filter_html(strtolower($config['charset']), $config['charset'])?>" />
	<meta name="author" content="<?=filter_html($project['author'], $config['charset'])?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="description" content="<?=filter_html($view['description'], $config['charset'])?>" />
	<link rel="stylesheet" href="<?=filter_html(static_css_url(), $config['charset'])?>/themes/<?=filter_html($config['theme']['name'], $config['charset'])?>/main.css.php" type="text/css" />
</head>
<body class="default">
	<div class="export">
		<table class="export">
		<tr class="export">
		<?php
			$row = array_values($view['result_array'])[0];

			foreach ($row as $field => $value):
				/* Ignore fields without meta data */
				if (!isset($view['fields'][$field]))
					continue;

				if ($view['fields'][$field]['type'] == 'separator')
					continue;
		?>
			<th class="export"><?=filter_html(ucfirst($view['fields'][$field]['viewname']), $config['charset'])?></th>
		<?php
			endforeach;
		
		?>
		</tr>
		<?php
			$i = 0;
			foreach ($view['result_array'] as $row):
		?>
			<?php
				/* Setup proper table row class based on controller configuration */
				$tr_class = 'export_' . ($i % 2 ? 'even' : 'odd');

				if (count($config['choices_class']) && isset($row[$config['choices_class']['rel_field']])) {
					if (isset($config['choices_class']['values'][$row[$config['choices_class']['rel_field']]])) {
						$tr_class = 'export_' . $config['choices_class']['values'][$row[$config['choices_class']['rel_field']]];
					} else {
						$tr_class = 'export_' . ($i % 2 ? $config['choices_class']['class_even'] : $config['choices_class']['class_odd']);
					}
				}
			?>

			<tr class="<?=filter_html($tr_class, $config['charset'])?>">
			<?php
				foreach ($row as $field => $value):
					/* Ignore fields without meta data */
					if (!isset($view['fields'][$field]))
						continue;

					if ($view['fields'][$field]['type'] == 'separator')
						continue;
			?>
					<td class="export">
						<?php if ($view['fields'][$field]['input_type'] == 'checkbox'): ?>
							<?=$value == 1 ? filter_html(NDPHP_LANG_MOD_STATUS_CHECKBOX_CHECKED, $config['charset']) : filter_html(NDPHP_LANG_MOD_STATUS_CHECKBOX_UNCHECKED, $config['charset'])?>
						<?php elseif ($field == 'id'): ?>
							<a href="<?=filter_html(base_url(), $config['charset'])?>index.php/<?=$view['ctrl']?>/view/<?=filter_html($value, $config['charset'])?>"><?=filter_html($value, $config['charset'])?></a>
						<?php else: ?>
							<?php if ($view['fields'][$field]['units']['unit'] && $view['fields'][$field]['units']['left']): ?>
								<?=filter_html($view['fields'][$field]['units']['unit'], $config['charset'])?>&nbsp;
							<?php endif; ?>
							<?=filter_html($value, $config['charset'])?>
							<?php if ($view['fields'][$field]['units']['unit'] && !$view['fields'][$field]['units']['left']): ?>
								<?=filter_html($view['fields'][$field]['units']['unit'], $config['charset'])?>&nbsp;
							<?php endif; ?>
						<?php endif; ?>
					</td>
			<?php
				endforeach;
			?>
			</tr>	
		<?php
				$i ++;
			endforeach;
		?>
		</table>
	</div>
	</body>
</html>
