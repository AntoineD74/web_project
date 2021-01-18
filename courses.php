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
                    // $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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

    <!-- ---------------------------------------------------------LINK TO COURSES----------------------------------------------------- -->
        
        <?php
            if(!isset($_SESSION['id']) || empty($_SESSION['id']))
            {
                echo '<p class="notConnectedSubjects"><a href="login.php">Login</a> to continue your lessons</p>';
            }

            else
            {
               //take all subject's ids
                $req= $bdd->prepare('SELECT subjects_id FROM subjects_attended WHERE person_id = (:id)');
                $req->execute(array("id"=>$_SESSION['id']));


                $subjects_ids= array();
                while($data= $req->fetch()){$subjects_ids[]= $data[0];}


                $subjects= array();     //table with links and names
                foreach ($subjects_ids as $value) 
                {
                    $req= $bdd->prepare('SELECT subject_name, subject_link FROM subjects WHERE subjects_id= (:id)');
                    $req-> execute(array("id"=>$value));
                    $data = $req->fetch();
                    $subjects[$data['subject_name']]= $data["subject_link"];
                }
          


                echo '<h1 id="continue_title">Continue your lessons '.$_SESSION['username'].':'.'</h1>';
                echo '<div id="continue_lessons">';
                        foreach ($subjects as $key => $value) 
                        {
                            echo '<div class= "one_subject">';
                                echo '<p class="names_link">'.$key.'</p>';
                                echo '<a href='.$value.' target="_blank" class="subjects_link">'.'<img class="sign" src="sign.png" alt="Open the lesson">'.'</a>';
                            echo'</div>';
                        }
                echo '</div>';
            }
        ?>
    <!-- ----------------------------------------------------------Messages------------------------------------------------------------ -->


    <?php

        if(isset($_SESSION['add']))
        {   
            if($_SESSION['add'])
            {
                echo '<p class= "incorrect">'.'This subjects is already in your list !'.'</p>';
                unset($_SESSION['add']);
            }
            else
            {
                echo '<p class= "correct">'.'This subject was added to your list !'.'</p>';
                unset($_SESSION['add']);
            }
        }


        if(isset($_SESSION['add2']))
        {
            if ($_SESSION['add2']== 'not connected') 
            {
                echo '<p class= "incorrect">'.'Please <a href="login.php">login</a> before adding a subject to you list'.'</p>';
                unset($_SESSION['add2']);
            }
        }

    ?>

    <!-- ----------------------------------------------------OUR COURSES AVAILABLE----------------------------------------------------- -->

        
        <h1 id='available'>Our courses currently available: (click to add)</h1>

            <?php
                $req= $bdd->query('SELECT subjects_id, subject_name FROM subjects');
                $all_subjects= array();
                while($data= $req->fetch())
                {
                    $all_subjects[$data['subjects_id']]= $data['subject_name'];
                } 
            ?>

        <div id="all_courses">
            <?php 
                foreach ($all_subjects as $value) 
                {
                    echo '-'.'<a href="add_subject.php?subject='.$value.'" class="link_subject">'.$value.'</a>'.'<br/>';
                }
            ?>
        </div>


	</body>
</html>