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
<br />
<br />
<br />
<div id="subscription_types_upgrade" class="subscription_types_upgrade">
	<fieldset class="subscription_types_upgrade">
		<legend class="subscription_types_upgrade"><?=filter_html(NDPHP_LANG_MOD_SUBSCRIPTION_UPGRADE, $config['charset'])?></legend>
			<br />

		<form action="<?=filter_html(base_url(), $config['charset'])?>index.php/subscription_types/subscription_upgrade" name="subscription_types_upgrade_form" id="subscription_types_upgrade_form" method="post">
			<?=fitler_html(NDPHP_LANG_MOD_SUBSCRIPTION_CHOOSE_NEW, $config['charset'])?>
			<br />
			<br />
			<br />
			<select id="subscription_types_id" name="subscription_types_id">
			<?php foreach ($view['subscription_types'] as $subscription_types_id => $subscription_types_val): ?>
				<?php if ($view['user_current_plan_id']  >= $subscription_types_id) continue; ?>
				<option value="<?=filter_html($subscription_types_id, $config['charset'])?>">
					<?=filter_html($subscription_types_val[0], $config['charset'])?> (<?=filter_html(round($subscription_types_val[1], 2), $config['charset'])?> <?=filter_html(NDPHP_LANG_MOD_DEFAULT_CURRENCY, $config['charset'])?> / <?=filter_html(NDPHP_LANG_MOD_WORD_MONTH, $config['charset'])?>)
				</option>
			<?php endforeach; ?>
			</select>
			<br />
			<br />
			<br />
			<a href="javascript:ndphp.form.subscription.upgrade_submit()" title="<?=filter_html(NDPHP_LANG_MOD_LINK_UPGRADE, $config['charset'])?>">
				<img width="32" height="32" class="upgradebutton" alt="<?=filter_html(NDPHP_LANG_MOD_LINK_UPGRADE, $config['charset'])?>" src="<?=filter_html(static_images_url(), $config['charset'])?>/icons/confirm.png" />
			</a>
			<br />
			<br />
			<br />
		</form>
	</fieldset>
	<script type="text/javascript">
		jQuery('#subscription_types_upgrade_form').submit(function(e) {
			ndphp.ui.busy();

			jQuery("#body").nd_animate_hide(ndphp.animation.delay, function() {
				jQuery.ajax({
					type: "POST",
					url: "<?=addslashes(base_url())?>index.php/subscription_types/subscription_upgrade",
					data: jQuery("#subscription_types_upgrade_form").serialize(),
					success: function(data) {
						var html = jQuery(data);
						ndphp.nav.back_store('body', jQuery('#body').html());
						jQuery("#body").html(html).nd_animate_show(ndphp.animation.delay);
					},
					error: function(xhr, ajaxOptions, thrownError) {
						jQuery("#ajax_error_dialog").html('<?=filter_html_js_str(NDPHP_LANG_MOD_UNABLE_UPGRADE_SUBSCRIPTION, $config['charset'])?><br /><br /><span style="font-weight: bold"><?=filter_html_js_str(ucfirst(NDPHP_LANG_MOD_WORD_REASON), $config['charset'])?>:</span> ' + xhr.responseText);
						jQuery("#ajax_error_dialog").dialog({ modal: true, title: '<?=filter_html_js_str(NDPHP_LANG_MOD_CANNOT_UPGRADE_SUBSCRIPTION, $config['charset'])?>' });
					}
				})
			});

			ndphp.ui.ready();

			e.preventDefault();

			return false;
		});
	</script>
</div>
<br />

