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
<div id="search_save" class="search_save">
	<form class="form-horizontal" action="<?=filter_html(base_url(), $config['charset'])?>index.php/<?=filter_html($view['ctrl'], $config['charset'])?>/save_search_process" id="searchsaveform" name="searchsaveform" enctype="multipart/form-data" method="post">
		<input id="search_result_query" type="hidden" name="search_save_result_query" value="" />
		<div class="fields">
			<fieldset class="form_fieldset">
				<div class="form-group">
					<label for="search_save_name" class="col-sm-3 control-label">
						<?=filter_html(NDPHP_LANG_MOD_COMMON_SEARCH_NAME, $config['charset'])?>
					</label>
					<div class="col-sm-6">
						<input class="form-control" id="search_save_name" type="text" name="search_save_name" />
					</div>
				</div>
				<div class="form-group">
					<label for="search_save_description" class="col-sm-3 control-label">
						<?=filter_html(NDPHP_LANG_MOD_COMMON_DESCRIPTION, $config['charset'])?>
					</label>
					<div class="col-sm-6">
						<input class="form-control" id="search_save_description" type="text" name="search_save_description" />
					</div>
				</div>
			</fieldset>
		</div>
		<div class="form-group">
			<div class="col-sm-6 col-sm-offset-3">
				<a class="btn btn-primary" href="javascript:void(0);" onclick="ndphp.form.submit_search_save(event, '<?=filter_html_js_str($view['ctrl'], $config['charset'])?>', 'searchsaveform', <?=isset($config['modalbox']) ? 1 : 0?>);" title="<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_CONFIRM, $config['charset'])?>">
					<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_CONFIRM, $config['charset'])?>
				</a>
				<a class="btn btn-default" href="javascript:void(0);" onclick="ndphp.form.cancel_search_save(event, '<?=filter_html_js_str($view['ctrl'], $config['charset'])?>', <?=isset($config['modalbox']) ? 1 : 0?>);" title="<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_CANCEL, $config['charset'])?>">
					<?=filter_html(NDPHP_LANG_MOD_OP_CONTEXT_CANCEL, $config['charset'])?>
				</a>
			</div>
		</div>
		<script type="text/javascript">
			jQuery('#search_result_query').val(jQuery('#result_query').val());
		</script>
	</form>
</div>