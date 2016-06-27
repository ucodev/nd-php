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
	$tabs = array();

	if (isset($tabs_listing)) {
		array_push($tabs, array(
			'href' => 'listing',
			'title' => NDPHP_LANG_MOD_TABS_TITLE_LISTING
		));

		if ($config['charts']['enable_list']) {
			array_push($tabs, array(
				'href' => 'charts',
				'title' => $view['crud_charts_tab_name']
			));
		}
	} else 	if (isset($tabs_result)) {
		array_push($tabs, array(
			'href' => 'listing',
			'title' => NDPHP_LANG_MOD_TABS_TITLE_LISTING
		));

		if ($config['charts']['enable_result']) {
			array_push($tabs, array(
				'href' => 'charts',
				'title' => $view['crud_charts_tab_name']
			));
		}
	} else if (isset($tabs_searches)) {
		array_push($tabs, array(
			'href' => 'search_advanced',
			'title' => NDPHP_LANG_MOD_TABS_TITLE_SEARCH_ADVANCED
		));
		array_push($tabs, array(
			'href' => 'search_saved',
			'title' => NDPHP_LANG_MOD_TABS_TITLE_SEARCH_SAVED
		));
	} else if (isset($tabs_groups)) {
		array_push($tabs, array(
			'href' => 'groups_list',
			'title' => NDPHP_LANG_MOD_TABS_TITLE_GROUPS
		));
	} else {
		array_push($tabs, array(
			'href' => 'fields_basic',
			'title' => $view['crud_main_tab_name']
		));

		foreach ($view['fields'] as $field => $meta) {
			if ($meta['type'] == 'mixed') {
				array_push($tabs, array(
					'href' => 'mixed_' . $meta['rel_table'] . '_container',
					'title' => ucfirst($meta['viewname'])
				));
			} else if ($meta['type'] == 'rel') {
				array_push($tabs, array(
					'href' => 'multiple_' . $field . '_container',
					'title' => ucfirst($meta['viewname'])
				));
			} else if ($meta['type'] == 'separator') {
				array_push($tabs, array(
					'href' => 'fields_' . $field . '_container',
					'title' => ucfirst($meta['viewname'])
				));
			}
		}

		if (isset($tabs_view) && $config['charts']['enable_view']) {
			array_push($tabs, array(
				'href' => 'charts',
				'title' => $view['crud_charts_tab_name']
			));
		}
	}
?>

<script type="text/javascript">
	required_fields_tab_map = [];

	jQuery(function() {
		jQuery('div[id^=entry_tabs]').tabs();
		jQuery('div[id^=entry_tabs]').on('tabsactivate', function(event, ui) {
			ndphp.current.tab_index = ui.newTab.index();
			<?php if (isset($tabs_result) || isset($tabs_listing)): ?>
				ndphp.current.tab_index_list_result = ndphp.current.tab_index;
			<?php endif; ?>
		});
		jQuery('div[id^=entry_tabs]').removeClass("ui-widget");
		jQuery('div[id^=entry_tabs]').css('border-radius', '0px');
		jQuery('#create, #edit, #remove, #view, #list, #result, #search, #groups').css('padding-top', '0px').css('padding-bottom', '0px');
	});
</script>

<div id="entry_tabs">
	<ul>
		<?php foreach ($tabs as $tab): ?>
			<li>
				<a href="#<?=filter_html_special($tab['href'], $config['charset'])?>">
					<?=filter_html($tab['title'], $config['charset'])?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
