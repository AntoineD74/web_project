<!DOCTYPE html>
<?php session_start(); ?>
 
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

	    <title>XEducation</title>
	</head>
</head>


<header>
    <main>
        <section class="banner">
            <figure>
                <a href="index.php"><img id="logo" src="logo.png" alt="Logo of the website"></a>
            </figure>
            <br/>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                        <div class="line2"></div>
                    <li><a href="courses.php">Courses</a></li>
                        <div class="line2"></div>
                    <li><a href="subjects.php">My Subjects</a></li>
                        <div class="line2"></div>
                    <li><a href="login.php">Your profile</a></li>
                </ul>
            </nav>
        </section>
    </main>
</header>
<body>

	<?php
		$infos= array();
		foreach ($_SESSION as $key => $value) 
		{
			$infos[$key]= $value;
		}
	?>


	<!------------------------------------------------------ Second part of the form -------------------------------------------------------->
        <div id="noAccount2">
            <h1 class="form2">You don't have an profile yet (2/2)</h1><br/>

            <?php
                $errors= array();
                $degree_study= 0;
                $field_study= "";
                $name_school= "";
                $diploma= "";
                $form_subjects= array();


                if(!empty($_POST))		//only the degree of study is not optional
                {
                	if(isset($_POST['degree_study']) && $_POST['degree_study'] != 0) {
						$degree_study = $_POST['degree_study'];
					}
					if(empty($degree_study)) {
						$errors['degree_study'] = 'Please enter your degree of study';
					}


					if(!empty($_POST['field_study'])) {
						$field_study = trim($_POST['field_study']);
					}
					if(empty($field_study)) {
						$errors['field_study'] = 'Please precise your field of study work';
					}

					if(!empty($_POST['name_school'])) {
						$name_school = trim($_POST['name_school']);
					}
					if(empty($name_school)) {
						$errors['name_school'] = 'Enter the name of your school/enterprise';
					}


					if(!empty($_POST['diploma'])) {
						$diploma = trim($_POST['diploma']);
					}
					if(empty($diploma)) {
						$errors['diploma'] = 'Precise your highest diploma';
					}


					if(!empty($_POST['subjects'])) {
						$form_subjects = $_POST['subjects'];
					}
					if(empty($form_subjects)) {
						$errors['subjects'] = 'Please select at least 1 subject';
					}

                }
            ?>


            <form method="post" action="">

            	<div class="formText"><label for="degree_study">What is your degree of study:</label>					<!--town in drop down list-->
					<select name="degree_study" value=<?php echo $degree_study; ?>>
						 <?php
							$degree=array(' ','1st grade','2nd grade','3rd grade','4th grade','5th grade','6th grade','7th grade','8th grade','9th grade','10th grade','11th grade','12th grade','college(1-3 years)','college(4-5 years)','college(5-8 years)');

							foreach($degree as $degree_id => $degree_name)
							{																		//drop list
								echo '<option value='.$degree_id.'>'.$degree_name.'</option>';
							}


							$degree_index= $_POST[$degree_name];
							$degree_name = '';

			
							if(!empty($degree_index)) 
							{
								$degree_name = $degree[$city_index];
								unset($_POST['$degree_name']);
							}
						?>
					</select>
				</div><br/>
					<?php
						if(!empty($errors['degree_study'])) {
						echo "<div class='errorMessage'>".$errors['degree_study']."</div>";
						}
					?>


                <div class="formText">Field of your study:  <input type="text" name="field_study" value="<?php echo $field_study; ?>"></div><br/>
                	<?php
						if(!empty($errors['field_study'])) {
						echo "<div class='errorMessage'>".$errors['field_study']."</div>";
						}
					?>


                <div class="formText">Name of your school:  <input type="text" name="name_school" value="<?php echo $name_school; ?>"></div><br/>
                	<?php
						if(!empty($errors['name_school'])) {
						echo "<div class='errorMessage'>".$errors['name_school']."</div>";
						}
					?>


                <div class="formText">Your highest diploma:  <input type="text" name="diploma" value="<?php echo $diploma; ?>"></div><br/>
                	<?php
						if(!empty($errors['diploma'])) {
						echo "<div class='errorMessage'>".$errors['diploma']."</div>";
						}
					?>


                <div class="formText">Select some subjects to begin:<br/>									<!--subjects in checkbox-->
					<?php
						$subjects= array();
						$req= $bdd->query('SELECT subject_name, subjects_id FROM subjects ORDER BY RAND() LIMIT 0, 8');

						while($data = $req->fetch()){$subjects[$data['subjects_id']]=$data['subject_name'];}

						$i= 1;
						foreach($subjects as $id => $name) 
						{
							
							$checked = ' ';
							if(in_array($id, $form_subjects)) 
							{
								$checked = 'checked';
							}

							echo '<input type="checkbox" class="check" name="subjects[]" value="'.$id.'" '.$checked.'>';
							echo '<label for="h'.$id.'">'.$name.'</label>';
							if($i%2){}
							else{echo '<br/>';}
							$i++;
						}
					?>
				</div>
				<?php
					if(!empty($errors['subjects'])) {
						echo "<div class='errorMessage'>".$errors['subjects']."</div>";
					}
				?>



                <input  type="submit" id="submit3" name="submit" value="submit">

            </form>

        </div>


	<?php

		if(!empty($_POST) && empty($errors))
		{
			$infos= array();
			foreach ($_SESSION as $key => $value) {$infos[$key]= $value;}		//preparing all informations for insertions
	        foreach ($_POST as $key => $value) 
	        {
	            if($key=='submit'){}
	            elseif($key=='degree_study'){$infos['degree_study']= $degree[$_POST['degree_study']];}
	            elseif($key=='subjects') 
	            {
	            	$tab= array();
	            	foreach($value as $value2)
	            	{
	            		foreach ($subjects as $key3 => $value3) 
	            		{
	            			if($value2== $key3){$tab[]=$key3;}
	            		}
	            	}
	            	$infos['subjects']= $tab;
	            }
	            else{$infos[$key]= $value;}
        	}
		
		
		////////////////////////////////////// Creation of the a new user ///////////////////////////////////////////


			//In the degree_of_study table
			$req = $bdd-> prepare('INSERT INTO degree_of_studies(degree_of_study, field_of_study, name_school, diploma) VALUES (:degree, :field, :name, :diploma)');
			$exec = $req->execute(array("degree"=> $infos['degree_study'],
										"field"=> $infos['field_study'],
										"name"=> $infos['name_school'],
										"diploma"=> $infos['diploma'],
									));
			$id_degree= $bdd->lastInsertId();



			//In the person table
		    $req = $bdd->prepare('INSERT INTO person(person_name, person_surname, person_birthdate, person_username, person_password, mail_adress, city_id, study_id) VALUES (:name, :surname, :birth, :username, :password, :mail, :city, :study)');

		    $exec = $req->execute(array("name"=> $infos["form_name"],
		                                "surname"=> $infos["form_surname"],
		                                "birth"=> $infos["form_birth"],
		                                "username"=> $infos["form_username"],
		                                "password"=> $infos["form_password"],
		                                "mail"=> $infos["form_mail"],
		                                "city"=> $infos['city_id'],
		                                "study"=> $id_degree,
		    						));
		    $id_person= $bdd->lastInsertId();



		    //In the subjects_attended table
		    $tab= $infos['subjects'];
		    foreach ($tab as $value) 
		    {
		    	$req = $bdd->prepare('INSERT INTO subjects_attended(person_id, subjects_id) VALUES (:person, :subject)');
		    	$exec = $req->execute(array("subject"=> $value,
		    								"person"=> $id_person,
		    							));
		    }

		    //////////////////////////////////////////Connection and leaving////////////////////////////////////////////

		    echo '<p id="created">Your account is now created, welcome!</p>';

		    foreach ($_SESSION as $key => $value) 
		    {
		    	unset($_SESSION[$key]);
		    }

		    $_SESSION['id'] = $id_person;
            $_SESSION['username'] = $infos['form_username'];

            header("refresh:3;url=index.php");	//will redirect in 3s
		} 
                       
	?>

</body>
</html>