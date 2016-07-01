<!DOCTYPE html>
<html>
	<head>
		<title>ND PHP Framework - Base64 Converter</title>
		<script type="text/javascript" src="<?=filter_html(static_js_url(), $config['charset'])?>/lib/jquery/1.12.4/jquery.js"></script>
		<script type="text/javascript" src="<?=filter_html(static_js_url(), $config['charset'])?>/lib/base64/base64.js"></script>
		<style type="text/css">
			h1 {
				font-size: 150%;
				font-family: monospace;
				display: table;
				margin: 0 auto;
				padding-top: 20px;
				padding-bottom: 30px;
			}

			span {
				font-family: monospace;
				font-weight: bold;
				padding-left: 5px;
			}

			input[type="button"] {
				margin-left: 10px;
				margin-right: 10px;
			}

			#body {
				display: table;
				margin: 0 auto;
			}
		</style>
	</head>
	<body>
		<div id="header">
			<h1>ND PHP Framework - Base64 Converter</title>
		</div>
		<div id="body">
			<table>
				<tr>
					<td>
						<span>From:</span><br />
						<textarea id="from" cols="64" rows="25"></textarea>
					</td>
					<td>
						<input id="encode_btn" type="button" value="Encode" />
						<br />
						<br />
						<input id="decode_btn" type="button" value="Decode" />
					</td>
					<td>
						<span>To:</span><br />
						<textarea id="to" cols="64" rows="25"></textarea>
					</td>
				</tr>
			</table>
		</div>
		<script type="text/javascript">
			jQuery(function() {
				jQuery('#encode_btn').click(function() {
					jQuery('#to').val(base64.encode(String(jQuery('#from').val())).replace('+', '-').replace('/', '_'));
				});

				jQuery('#decode_btn').click(function() {
					jQuery('#to').val(base64.decode(String(jQuery('#from').val())).replace('-', '+').replace('_', '/'));
				});			
			});
		</script>
	</body>
</html>
