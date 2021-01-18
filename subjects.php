<!DOCTYPE html>
<?php session_start(); ?>

<html>


	<head>
		<meta charset="utf-8" />
		<link rel="stylesheet" href="/project/stylesheet.css">
		<meta charset="UTF-8">
        <?php //connection to the database
                try
                {
                    $bdd = new PDO('mysql:host=localhost;dbname=site_project;charset=utf8', 'root', '');
                }                                                                              

                catch(Exception $e)
                {
                    die('Erreur : '.$e->getMessage());
                }
        ?>

	    <title>XEducation</title>
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

<!-- ----------------------------------------------------My subjects------------------------------------------------------- -->
        
            <?php
                if(!isset($_SESSION['id']) || empty($_SESSION['id']))
                {
                    echo '<p class="notConnectedSubjects"><a href="login.php">Login</a> to see the subjects your are already studying</p>';
                }

                else
                {   
                    echo "<div class='userSubjects'>";
                        echo '<h2><a href="courses.php" class="my_subjects">My subjects:</a></h2>';

                        #we use the id to find the IDs of the subjects studied by the user
                        $answer= $bdd-> query('SELECT subjects_id FROM subjects_attended WHERE person_id='.$_SESSION['id']);
                        
                        #we are taking back all IDs of subjects and put them into id_subject
                        $id_subjects= array();
                        while($data = $answer-> fetch())
                        {
                            $id_subjects[]= $data[0];
                        }


                        #we go search one by one the subject's name and print it
                        $name_subjects= array();      #array to keep name of the user's subject
                        echo '<div id="userSubjects_names">';
                            foreach($id_subjects as $value)
                            {
                                $answer= $bdd->query('SELECT subject_name FROM subjects WHERE subjects_id='.$value);
                                $data= $answer-> fetch();
                                $name_subjects[]= $data[0];


                                echo '-'.$data[0].'<br/>';       #we display the subjects    
                            }
                        echo '</div>';

                        $answer->closeCursor();

                    echo '</div>';
                }
                    
                
            ?>
        
<!-- ----------------------------------------------------To discover------------------------------------------------------- -->

        <div class="toDiscover">
            <h2>Subjects to discover:</h2>

            <form method="post" action="">
            <table class= tableDiscover>
                
                <?php
                    $subjectsIDS= array();

                    //We take randomly 5 subjects in our subjects table and we display them in the discover table
                    $answer= $bdd->query('SELECT subject_name, subjects_id FROM subjects ORDER BY RAND() LIMIT 0, 5');

                    while($datas = $answer->fetch())
                    {
                        $subjectsIDS[$datas['subject_name']]= $datas['subjects_id'];

                        $name= $datas['subject_name'];
                        echo '<tr>';
                            echo '<td>'.$name.'</td>';
                            echo '<td>'.'<input class="addButton" type=submit name="'.$name.'" value="Add">'.'</td>'; //button to add this subject
                        echo '</tr>';
                    }
                    $answer->closeCursor();
                ?>
              
            </table>
            </form>
        </div>

        <!-- Treatment adding of subject -->
        <?php
            $notConnectedAdd= False;
            $sameSubjects= False;
            $confirmationAdded= False;

            
            if(!empty($_POST))
            {
                if(empty($_SESSION['id']))
                {
                    $notConnectedAdd= True;
                }
                else
                {
                    // We check if the user has already the subjects he wants to add or not
                    $toAdd= "";
                    foreach ($_POST as $key => $value) {$toAdd=$key;}

                    for ($i=0; $i < strlen($toAdd) ; $i++)  //str are again two words to interact with the database
                    { 
                        if($toAdd[$i]=='_'){$toAdd[$i]= ' ';}
                    }


                    $same= False;
                    foreach ($name_subjects as $value) 
                    {
                        if($value==$toAdd){$same= True;}
                    }

                    if($same)
                    {
                        $sameSubjects= True;
                    }
                    else
                    {
                        $toAdd_id= 0;                               // We are take the id of the subjects in order to add it
                        foreach ($subjectsIDS as $key => $value) 
                        {
                            if($toAdd== $key){$toAdd_id= $value;}
                        }
                        
                    
                        // We can now add the id of the subjects into the right INDEX
                        $insert = $bdd->prepare('INSERT INTO subjects_attended (person_id, subjects_id) VALUES(:id, :subject)');
                        $insert -> execute(array(
                                                'id'=> $_SESSION['id'],
                                                'subject'=>  $toAdd_id,
                                                ));

                        $confirmationAdded= True;
                        $sameSubjects= False;
                    }
                }
            }
        ?>

<!-- ----------------------------------------------------Messages------------------------------------------------------- -->

       <?php
            // We display error messages if something is wrong
            if($sameSubjects){echo '<p class="incorrect">This subjects is already in your list !</p>';}
            if($notConnectedAdd){echo '<p class="incorrect">You need to <a href="login.php">connect</a> before adding a subject to your list !</p>';}
            if($confirmationAdded){echo '<p class="correct">The subject was succesfully added to your list !</p>';}

        ?>



	</body>

	<footer>
	</footer>

</html>