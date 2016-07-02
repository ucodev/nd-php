<!DOCTYPE html>
<html>
	<head>
		<title>ND PHP Framework - Transcoder</title>
		<script type="text/javascript" src="<?=filter_html(static_js_url(), $config['charset'])?>/lib/jquery/1.12.4/jquery.js"></script>
		<script type="text/javascript" src="<?=filter_html(static_js_url(), $config['charset'])?>/lib/base64/base64.js"></script>
		<script type="text/javascript" src="<?=filter_html(static_js_url(), $config['charset'])?>/lib/charset/utf8.js"></script>
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
				width: 128px;
				text-align: center;
			}

			#body {
				display: table;
				margin: 0 auto;
			}
		</style>
	</head>
	<body>
		<div id="header">
			<h1>ND PHP Framework - Transcoder</title>
		</div>
		<div id="body">
			<table>
				<tr>
					<td>
						<span>From:</span><br />
						<textarea id="from" cols="64" rows="25"></textarea>
					</td>
					<td>
						<input id="b64_encode_btn" type="button" value="Encode Base64" />
						<br />
						<input id="b64_decode_btn" type="button" value="Decode Base64" />
						<br />
						<br />
						<input id="uri_encode_btn" type="button" value="Encode URI" />
						<br />
						<input id="uri_decode_btn" type="button" value="Decode URI" />
						<br />
						<br />
						<input id="utf8_encode_btn" type="button" value="Encode UTF-8" />
						<br />
						<input id="utf8_decode_btn" type="button" value="Decode UTF-8" />
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
				/* Base64 */
				jQuery('#b64_encode_btn').click(function() {
					jQuery('#to').val(base64.encode(String(jQuery('#from').val())).replace('+', '-').replace('/', '_'));
				});

				jQuery('#b64_decode_btn').click(function() {
					jQuery('#to').val(base64.decode(String(jQuery('#from').val())).replace('-', '+').replace('_', '/'));
				});

				/* URI */
				jQuery('#uri_encode_btn').click(function() {
					jQuery('#to').val(encodeURIComponent(String(jQuery('#from').val())));
				});

				jQuery('#uri_decode_btn').click(function() {
					jQuery('#to').val(decodeURIComponent(String(jQuery('#from').val())));
				});

				/* UTF-8 */
				jQuery('#utf8_encode_btn').click(function() {
					jQuery('#to').val(utf8_encode(String(jQuery('#from').val())));
				});

				jQuery('#utf8_decode_btn').click(function() {
					jQuery('#to').val(utf8_decode(String(jQuery('#from').val())));
				});
			});
		</script>
	</body>
</html>
