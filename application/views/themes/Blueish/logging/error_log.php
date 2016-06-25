<!DOCTYPE html>
<html>
	<head>
		<title><?=filter_html(NDPHP_LANG_MOD_OP_ERROR_LOG, $config['charset'])?></title>
		<script type="text/javascript">
			window.onload = function() {
				var el = document.getElementById('error_log');
				el.scrollTop = el.scrollHeight;
			}
		</script>
	</head>
	<body>
		<p style="font-family: monospace; font-size: 120%;"><b><?=filter_html(NDPHP_LANG_MOD_OP_ERROR_LOG, $config['charset'])?>: <?=filter_html(SYSTEM_BASE_DIR . '/logs/error.log', $config['charset'])?></b></p>
		<textarea id="error_log" style="font-family: monospace; color: blue; width: 100%; min-height: 400px;" readonly><?=filter_html($view['error_log'], $config['charset'])?></textarea>
		<input style="display: table; margin: 0 auto; margin-top: 15px;" type="button" value="Refresh" onClick="window.location.reload()">
	</body>
</html>