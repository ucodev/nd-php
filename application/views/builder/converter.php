<!DOCTYPE html>
<html>
	<head>
		<title>ND PHP Framework - Converter</title>
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
			<h1>ND PHP Framework - Converter</title>
		</div>
		<div id="body">
			<table>
				<tr>
					<td>
						<span>From:</span><br />
						<textarea id="from" cols="64" rows="25"></textarea>
					</td>
					<td>
						<input id="d2b_btn" type="button" value="Dec2Bin" />
						<br />
						<input id="b2d_btn" type="button" value="Bin2Dec" />
						<br />
						<br />
						<input id="d2h_btn" type="button" value="Dec2Hex" />
						<br />
						<input id="h2d_btn" type="button" value="Hex2Dec" />
						<br />
						<br />
						<input id="d2o_btn" type="button" value="Dec2Oct" />
						<br />
						<input id="o2d_btn" type="button" value="Oct2Dec" />
					</td>
					<td>
						<span>To:</span><br />
						<textarea id="to" cols="64" rows="25"></textarea>
					</td>
				</tr>
			</table>
		</div>
		<script type="text/javascript">
			parseInt(String(jQuery('#from').val()), 10).toString(2);
			jQuery(function() {
				/* Decimal / Binary */
				jQuery('#d2b_btn').click(function() {
					jQuery('#to').val(parseInt(String(jQuery('#from').val()), 10).toString(2));
				});

				jQuery('#b2d_btn').click(function() {
					jQuery('#to').val(parseInt(String(jQuery('#from').val()), 2).toString(10));
				});

				/* Decimal / Hexadecimal */
				jQuery('#d2h_btn').click(function() {
					jQuery('#to').val(parseInt(String(jQuery('#from').val()), 10).toString(16));
				});

				jQuery('#h2d_btn').click(function() {
					jQuery('#to').val(parseInt(String(jQuery('#from').val()), 16).toString(10));
				});

				/* Decimal / Octal */
				jQuery('#d2o_btn').click(function() {
					jQuery('#to').val(parseInt(String(jQuery('#from').val()), 10).toString(8));
				});

				jQuery('#o2d_btn').click(function() {
					jQuery('#to').val(parseInt(String(jQuery('#from').val()), 8).toString(10));
				});
			});
		</script>
	</body>
</html>
