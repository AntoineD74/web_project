
<?php session_start(); ?>

<?php //connection to the database
    try
    {
        $bdd = new PDO('mysql:host=localhost;dbname=site_project;charset=utf8', 'root', '');
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }                                                                              

    catch(Exception $e)
    {
        die('Erreur : '.$e->getMessage());
    }
?>

<!-- -----------------------------------------------Adding of the subject and redirect----------------------------------------------------- -->


	<?php
		if(isset($_GET['subject']))
		{	
			if(isset($_SESSION['id']) && !empty($_SESSION['id']))
			{
				$to_add= $_GET['subject'];			//We fetch the id of the subject to add
		   		$req= $bdd->prepare('SELECT subjects_id FROM subjects WHERE subject_name= (:name)');
		   		$req->execute(array("name"=> $to_add));
		   		$answer= $req-> fetch();
		   

		   		$to_add= $answer['subjects_id'];			//Checking if subjects already in bdd for user
		   		$id= $_SESSION['id'];
		   		$req= 'SELECT subjects_id FROM subjects_attended WHERE person_id='.$id;
		   		$answer= $bdd->query($req);

		   		$same= False;
		   		while($data= $answer->fetch())
		   		{
		   			if($data[0]==$to_add){$same=True;}
		  		}



			   	if($same)
			   	{
			   		$_SESSION['add']= $same;
			   		header('Location: courses.php');
			   	}
			   	else
			   	{
			   		$req= $bdd->prepare('INSERT INTO subjects_attended(person_id, subjects_id) VALUES (:person, :subject)');
				   	$req->execute(array(
				   						"person"=> $id,
				   						"subject"=> $to_add,
				   						));

				   	$_SESSION['add']= $same;
			   		header('Location: courses.php');
			   	}
			}

			else
			{
				$_SESSION['add2']= 'not connected';
				header('Location: courses.php');
			}
		   
	   
 		}

		else
		{
			echo "An error happened, you will be redirected, please retry later";
			header("refresh:3;url=index.php");
		}

	?>