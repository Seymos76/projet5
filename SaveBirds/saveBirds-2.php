<?php

extract(filter_input_array(INPUT_POST));
$file = $_FILES["filecsv"]["name"];
try
{
	$db = new PDO('mysql:host=localhost;dbname=project5_openclassrooms;charset=utf8', 'root', '');
}
catch(Exception $e)
{
    die('Erreur : '.$e->getMessage());
}

if ($file) {
	$data = file_get_contents($_FILES["filecsv"]["name"]); 

	$line = explode("\n", $data);

	for ($i=0;$i<count($line);$i++) 
	{
		$values = explode(";", $line[$i]);
		$reign = $values[0]; 
	    $phylum = $values[1]; 
	    $class = $values[2];
	    $bird_order = $values[3]; 
	    $family = $values[4];
	    $vernacularname = $values[13];
	    $validname = $values[9];
		$request = $db->prepare("INSERT INTO bird(reign, phylum, class, bird_order, family, vernacularname, validname) VALUES(:reign, :phylum, :class, :bird_order, :family, :vernacularname, :validname)");
		$result = $request->execute(
			array(
				'reign' => $reign,
				'phylum' => $phylum,
				'class' => $class,
				'bird_order' => $bird_order,
				'family' => $family,
				'vernacularname' => $vernacularname,
				'validname' => $validname
			)
		);
	}
} else {
	die('unknown file');
}