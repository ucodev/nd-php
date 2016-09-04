<!DOCTYPE html>
<html>
	<head>
		<title>ND PHP Framework - Importer</title>
	</head>
	<body>
		<br />
		<fieldset>
			<legend>
				ND App Importer (not yet implemented)
			</legend>
			<form id="ndapp_importer" action="<?=base_url()?>index.php/builder/import_ndapp" enctype="multipart/form-data" method="post">
				<table>
					<tr>
						<td>ND App URL</td>
						<td><input name="ndapp_url" type="text" /></td>
					</tr>
					<tr>
						<td>ND App File</td>
						<td><input name="ndapp_file" type="file" /> (*.ndapp)</td>
					</tr>
					<tr>
						<td></td>
						<td><input type="submit" value="Import ND App" disabled /></td>
					</tr>
				</table>
			</form>
		</fieldset>
		<br />
		<br />
		<fieldset>
			<legend>
				ND Model Importer
			</legend>
			<form id="ndmodel_importer" action="<?=base_url()?>index.php/builder/import_ndmodel" enctype="multipart/form-data" method="post">
				<table>
					<tr>
						<td>ND Model Contents</td>
						<td><textarea name="ndmodel_contents"></textarea></td>
					</tr>
					<tr>
						<td>ND Model File</td>
						<td><input name="ndmodel_file" type="file" /> (*.ndmodel)</td>
					</tr>
					<tr>
						<td></td>
						<td><input type="submit" value="Import ND Model" /></td>
					</tr>
				</table>
			</form>
		</fieldset>
		<br />
		<br />
		<fieldset>
			<legend>
				ND Data Importer (not yet implemented)
			</legend>
			<form id="ndmodel_importer" action="<?=base_url()?>index.php/builder/import_nddata" enctype="multipart/form-data" method="post">
				<table>
					<tr>
						<td>ND Data Contents</td>
						<td><textarea name="nddata_contents"></textarea></td>
					</tr>
					<tr>
						<td>ND Data File</td>
						<td><input name="nddata_file" type="file" /> (*.nddata)</td>
					</tr>
					<tr>
						<td></td>
						<td><input type="submit" value="Import ND Data" disabled /></td>
					</tr>
				</table>
			</form>
		</fieldset>
	</body>
</html>
