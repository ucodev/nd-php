<!DOCTYPE html>
<html>
	<head>
		<style type="text/css">
			table {
				border: 0;
				padding: 5px;
				margin: 5px;
			}

			td, tr {
				padding: 5px;
				margin: 5px;
			}
		</style>
		<title><?=filter_html($view['code']['number'], $config['charset'])?> <?=filter_html($view['code']['name'], $config['charset'])?></title>
	</head>
	<body>
		<h1><?=filter_html(NDPHP_LANG_MOD_ERROR_STATUS_CODE_HEADER, $config['charset'])?></h1>
		<div>
		 	<table>
		 		<tr>
		 			<td><b><?=filter_html(NDPHP_LANG_MOD_ERROR_STATUS_CODE_FIELD_SC, $config['charset'])?></b></td><td><?=filter_html($view['code']['number'], $config['charset'])?> <?=filter_html($view['code']['name'], $config['charset'])?></td>
		 		</tr>
		 		<tr>
		 			<td><b><?=filter_html(NDPHP_LANG_MOD_ERROR_STATUS_CODE_FIELD_DESC, $config['charset'])?></b></td><td><?=filter_html($view['code']['description'], $config['charset'])?></td>
		 		</tr>
		 		<tr>
		 			<td><b><?=filter_html(NDPHP_LANG_MOD_ERROR_STATUS_CODE_FIELD_WHY, $config['charset'])?></b></td><td><?=filter_html($view['content'], $config['charset'])?></td>
		 		</tr>
		 	</table>
		</div>
	</body>
</html>