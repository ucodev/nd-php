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
	<title><?=$title?></title>
	<meta http-equiv="content-type" content="application/xhtml+xml; charset=<?=filter_html(strtolower($config['charset']), $config['charset'])?>" />
	<meta name="author" content="<?=filter_html($project['author'], $config['charset'])?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="description" content="<?=filter_html($view['description'], $config['charset'])?>" />
	<link rel="stylesheet" href="<?=filter_html(static_css_url(), $config['charset'])?>/themes/<?=filter_html($config['theme']['name'], $config['charset'])?>/main.css.php" type="text/css" />
</head>
<body class="default">
	<div class="export">
		<?php $row = array_values($view['result_array'])[0]; ?>
		<div class="fields">
			<table class="fields">
				<tr class="fields">
					<th class="fields"><?=filter_html(NDPHP_LANG_MOD_COMMON_CRUD_TITLE_FIELD_NAME, $config['charset'])?></th>
					<th class="fields"><?=filter_html(NDPHP_LANG_MOD_COMMON_CRUD_TITLE_FIELD_VALUE, $config['charset'])?></th>
				</tr>
				<?php $i = 0; ?>
				<?php foreach ($row as $field => $value): ?>
					<?php if (!isset($view['fields'][$field])) continue; ?>
					<?php if ($view['fields'][$field]['type'] == 'separator') continue; ?>
					<tr class="field_<?php echo($i % 2 ? 'even' : 'odd'); ?>">
						<td class="field_name"><?=filter_html(ucfirst($view['fields'][$field]['viewname']), $config['charset'])?></td>
						<td class="field_value">
						<?php if ($view['fields'][$field]['input_type'] == 'checkbox'): ?>
							<?php echo($value == 1 ? filter_html(NDPHP_LANG_MOD_STATUS_CHECKBOX_CHECKED, $config['charset']) : filter_html(NDPHP_LANG_MOD_STATUS_CHECKBOX_UNCHECKED, $config['charset'])); ?>
						<?php elseif ($view['fields'][$field]['input_type'] == 'select'): ?>
							<?php foreach ($view['fields'][$field]['options'] as $opt_id => $opt_value): ?>
								<?php if ($opt_id == $value): ?>
									<a href="<?=filter_html($view['fields'][$field]['table'], $config['charset'])?>/view/<?=filter_html($opt_id, $config['charset'])?>">
										<?=filter_html($opt_value, $config['charset'])?>
									</a>
								<?php endif; ?>
							<?php endforeach; ?>
						<?php elseif ($view['fields'][$field]['input_type'] == 'file' && isset($view['fields'][$field]['base64_format'])): ?>
							<img alt="<?=filter_html($value, $config['charset'])?>" style="width: <?=filter_html($config['render']['size']['width'], $config['charset'])?>; height: <?=filter_html($config['render']['size']['height'], $config['charset'])?>;" src="<?=$view['fields'][$field]['base64_format']?>" />
						<?php else: ?>
							<?php if ($field == 'id'): ?>
									<a href="<?=filter_html($view['ctrl'], $config['charset'])?>/view/<?=filter_html($value, $config['charset'])?>">
										<?=filter_html($value, $config['charset'])?>
									</a>
							<?php else: ?>
									<?=filter_html($value, $config['charset'])?>
							<?php endif; ?>
						<?php endif; ?>
						</td>
					</tr>
					<?php $i ++; ?>
				<?php endforeach; ?>

				<?php /* Process multiple relationship fields */ ?>
				<?php foreach ($view['rel'] as $field => $values): ?>
					<tr class="field_<?php echo($i % 2 ? 'even' : 'odd'); ?>">
						<td class="field_name"><?=filter_html(ucfirst($view['fields'][$field]['viewname']), $config['charset'])?></td>
						<td class="field_value">
							<?php foreach ($values as $val_id => $val_value): ?>
								<a href="<?=filter_html($view['fields'][$field]['table'], $config['charset'])?>/view/<?=filter_html($val_id, $config['charset'])?>">
									<?=filter_html($val_value, $config['charset'])?>
								</a>
								<br />
							<?php endforeach; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
		</div>
	</div>
	</body>
</html>
