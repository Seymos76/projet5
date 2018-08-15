<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Enregistrement oiseaux - fichier csv</title>
</head>
<body>
	<h2>Veuillez choisir un fichier *.csv :</h2>
	<form method="post" enctype="multipart/form-data" action="/saveBirds-2">
		<input type="file" name="filecsv" value="table">
		<input type="submit" name="submit" value="Importer">
	</form>
</body>
</html>