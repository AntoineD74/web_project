<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="/project/stylesheet.css">
		<meta charset="UTF-8">
        <?php 
            try
            {
                $bdd = new PDO('mysql:host=localhost; dbname=site_project; charset=utf8', 'root', '');
                // $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }

            catch(Exception $e)
            {
                die('Erreur : '.$e->getMessage());
            }
        ?>

	<title>Membres</title>
</head>
<body>

<h1>Users: </h1>
	<?php
		$reponse = $bdd->query('SELECT person_name, person_username FROM person');

		$nb= 2;
		while ($donnees = $reponse->fetch())
		{
			echo $nb.'.'.$donnees['person_name'] . ' (' . $donnees['person_username'] . ')<br />';
			$nb++;
		}

		$reponse->closeCursor();

	?>


<h1>Cities: </h1>

	<?php
		$reponse = $bdd->query('SELECT city_name, city_country FROM cities');

		$nb= 0;
		while ($donnees = $reponse->fetch())
		{
			echo $nb.'.'.$donnees['city_name'] . ' in ' . $donnees['city_country'].'<br />';
			$nb++;
		}

		$reponse->closeCursor();

	?>

</body>
</html>