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
		<title>JSON API Documentation</title>
		<link rel="stylesheet" type="text/css" href="<?=filter_html(static_css_url(), $config['charset'])?>/documentation/json.css" />
	</head>
	<body>
		<a name="credentials"></a>
		<fieldset>
			<legend>CREDENTIALS</legend>
			<table>
				<tr><td><strong>User ID</strong></td><td><span class="console"><?=filter_html($view['user_id'], $config['charset'])?></span></td></tr>
				<tr><td><strong>API Key</strong></td><td><span class="console"><?=filter_html($view['apikey'], $config['charset'])?></span></td></tr>
			</table>
		</fieldset>
		<a name="view"></a>
		<fieldset>
			<legend>VIEW</legend>
			<table>
				<tr><td><strong>Description</strong></td><td>Returns the contents of the given <i>entry_id</i>.</td></tr>
				<tr><td><strong>URL</strong></td><td><span class="console"><?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($view['ctrl'], $config['charset'])?>/view/<i>entry_id</i></span></td></tr>
				<tr><td><strong>Data Fields</strong></td><td><span class="console">N/A</span></td></tr>
				<tr><td><strong>Method</strong></td><td><span class="method">POST</span></td></tr>
				<tr><td><br /></td><td><br /></td>
				</tr><tr><td><strong>CURL Example</strong></td><td><span class="console">$ curl -XPOST <?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($view['ctrl'], $config['charset'])?>/view/1 -d \<br />
				&nbsp;&nbsp;&nbsp;&nbsp;'{ "_userid": <?=filter_html($view['user_id'], $config['charset'])?>, "_apikey": "<?=filter_html($view['apikey'], $config['charset'])?>" }'</span></td></tr>
			</table>
		</fieldset>
		<a name="list"></a>
		<fieldset>
			<legend>LIST</legend>
			<table>
				<tr><td><strong>Description</strong></td><td>Returns a list of all available entries.</td></tr>
				<tr><td><strong>URL</strong></td><td><span class="console"><?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($view['ctrl'], $config['charset'])?>/list_default</span></td></tr>
				<tr><td><strong>Data Fields</strong></td><td><span class="console">N/A</span></td></tr>
				<tr><td><strong>Method</strong></td><td><span class="method">POST</span></td></tr>
				<tr><td><br /></td><td><br /></td>
				</tr><tr><td><strong>CURL Example</strong></td><td><span class="console">$ curl -XPOST <?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($view['ctrl'], $config['charset'])?>/list_default -d \<br />
				&nbsp;&nbsp;&nbsp;&nbsp;'{ "_userid": <?=filter_html($view['user_id'], $config['charset'])?>, "_apikey": "<?=filter_html($view['apikey'], $config['charset'])?>" }'</span></td></tr>
			</table>
		</fieldset>
		<a name="result"></a>
		<fieldset>
			<legend>RESULT</legend>
			<table>
				<tr><td><strong>Description</strong></td><td>Searches and returns all entries that match <i>search string</i>.</td></tr>
				<tr><td><strong>URL</strong></td><td><span class="console"><?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($view['ctrl'], $config['charset'])?>/result/basic</span></td></tr>
				<tr><td><strong>Data Fields</strong></td><td><span class="console">"data": { "search_value": "<i>search string</i>" }</span></td></tr>
				<tr><td><strong>Method</strong></td><td><span class="method">POST</span></td></tr>
				<tr><td><br /></td><td><br /></td>
				</tr><tr><td><strong>CURL Example</strong></td><td><span class="console">$ curl -XPOST <?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($view['ctrl'], $config['charset'])?>/result/basic -d \<br />
				&nbsp;&nbsp;&nbsp;&nbsp;'{ "_userid": <?=filter_html($view['user_id'], $config['charset'])?>, "_apikey": "<?=filter_html($view['apikey'], $config['charset'])?>", "data": { "search_value": "ND PHP" } }'</span></td></tr>
			</table>
		</fieldset>
		<?php if (security_perm_check($security['perms'], 'C', $view['ctrl'])): ?>
			<a name="insert"></a>
			<fieldset>
				<legend>INSERT</legend>
				<table>
					<tr><td><strong>Description</strong></td><td>Inserts an entry based on data field values.</td></tr>
					<tr><td><strong>URL</strong></td><td><span class="console"><?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($view['ctrl'], $config['charset'])?>/insert</span></td></tr>
					<tr><td><strong>Data Fields</strong></td><td><span class="console">"data": {<br />
						<?php $field_first = 0; ?>
						<?php foreach ($view['data_fields'] as $field => $meta): ?>
							<?php if (!security_perm_check($security['perms'], 'C', $view['ctrl'], $field)) continue; ?>
							<?php if ($field == 'id' || ($meta['type'] == 'separator')) continue; ?>
							<?php if ($field_first != 0): ?>,<br /><?php else: $field_first = 1; endif; ?>
							&nbsp;&nbsp;&nbsp;&nbsp;"<?=filter_html($field, $config['charset'])?>": "<i><?=filter_html($meta['type'], $config['charset'])?></i>"
						<?php endforeach; ?>
						<br />}</span>
					</td></tr>
					<tr><td><strong>Method</strong></td><td><span class="method">POST</span></td></tr>
					<tr><td><br /></td><td><br /></td>
					</tr><tr><td><strong>CURL Example</strong></td><td><span class="console">$ curl -XPOST <?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($view['ctrl'], $config['charset'])?>/insert -d \<br />
					&nbsp;&nbsp;&nbsp;&nbsp;'{ "_userid": <?=filter_html($view['user_id'], $config['charset'])?>, "_apikey": "<?=filter_html($view['apikey'], $config['charset'])?>", "data": { <i>...</i> } }'</span></td></tr>
				</table>
			</fieldset>
		<?php endif; ?>
		<?php if (security_perm_check($security['perms'], 'U', $view['ctrl'])): ?>
			<a name="update"></a>
			<fieldset>
				<legend>UPDATE</legend>
				<table>
					<tr><td><strong>Description</strong></td><td>Updates the <i>entry_id</i> based on data field values or based on the optional <i>field</i> and <i>value</i>.</td></tr>
					<tr><td><strong>URL</strong></td><td><span class="console"><?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($view['ctrl'], $config['charset'])?>/update/<i>entry_id</i>/<i class="optional">field</i>/<i class="optional">value</i></span></td></tr>
					<tr><td><strong>Data Fields</strong></td><td><span class="console">"data": {<br />
						<?php $field_first = 0; ?>
						<?php foreach ($view['data_fields'] as $field => $meta): ?>
							<?php if (!security_perm_check($security['perms'], 'U', $view['ctrl'], $field)) continue; ?>
							<?php if ($field == 'id' || ($meta['type'] == 'separator')) continue; ?>
							<?php if ($field_first != 0): ?>,<br /><?php else: $field_first = 1; endif; ?>
							&nbsp;&nbsp;&nbsp;&nbsp;"<?=filter_html($field, $config['charset'])?>": "<i><?=filter_html($meta['type'], $config['charset'])?></i>"
						<?php endforeach; ?>
						<br />}</span>
					</td></tr>
					<tr><td><strong>Method</strong></td><td><span class="method">POST</span></td></tr>
					<tr><td><br /></td><td><br /></td>
					</tr><tr><td><strong>CURL Example</strong></td><td><span class="console">$ curl -XPOST <?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($view['ctrl'], $config['charset'])?>/update/1 -d \<br />
					&nbsp;&nbsp;&nbsp;&nbsp;'{ "_userid": <?=filter_html($view['user_id'], $config['charset'])?>, "_apikey": "<?=filter_html($view['apikey'], $config['charset'])?>", "data": { <i>...</i> } }'</span></td></tr>
				</table>
			</fieldset>
		<?php endif; ?>
		<?php if (security_perm_check($security['perms'], 'D', $view['ctrl'])): ?>
			<a name="delete"></a>
			<fieldset>
				<legend>DELETE</legend>
				<table>
					<tr><td><strong>Description</strong></td><td>Deletes the given <i>entry_id</i>.</td></tr>
					<tr><td><strong>URL</strong></td><td><span class="console"><?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($view['ctrl'], $config['charset'])?>/delete/<i>entry_id</i></span></td></tr>
					<tr><td><strong>Data Fields</strong></td><td><span class="console">N/A</span></td></tr>
					<tr><td><strong>Method</strong></td><td><span class="method">POST</span></td></tr>
					<tr><td><br /></td><td><br /></td>
					</tr><tr><td><strong>CURL Example</strong></td><td><span class="console">$ curl -XPOST <?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($view['ctrl'], $config['charset'])?>/delete/1 -d \<br />
					&nbsp;&nbsp;&nbsp;&nbsp;'{ "_userid": <?=filter_html($view['user_id'], $config['charset'])?>, "_apikey": "<?=filter_html($view['apikey'], $config['charset'])?>" }'</span></td></tr>
				</table>
			</fieldset>
		<?php endif; ?>
	</body>
</html>