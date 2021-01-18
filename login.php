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



    <?php
        $login= False;
        $register= False;

        $infos= array();
        if(!empty($_POST))
        {
            foreach ($_POST as $key => $value) 
            {
                if($key=='submit'){}
                else{$infos[$key]= $value;}

            }

            if($_POST["submit"]=="login"){$login= True;}
            elseif($_POST["submit"]=="register"){$register= True;}
        }

    ?>


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
    
    <div class="twoForms">



    <!--------------------------------------------------------- First form --------------------------------------------------------->

        <div id="haveAccount">
            <h1 class="form1">You already have a profile</h1><br/>

            <?php
                $errors= array();

                $username= "";
                $password= "";

                if(!empty($_POST)) 
                {
                    if(!empty($_POST['username'])) 
                    {
                        $username = trim($_POST['username']);
                    }
                    if(empty($username) && $login==True) 
                    {
                        $errors['username'] = 'Your username is required';
                    }

                    if(!empty($_POST['password'])) 
                    {
                        $password = trim($_POST['password']);
                    }
                    if(empty($password) && $login==True) 
                    {
                        $errors['password'] = 'Your password is required';
                    }
                }

            ?>


            <form method="post" action="">
                <div class="accountText">Enter your username <br/>or your mail adress: <input type="text" name="username" value="<?php echo $username; ?>"></div><br/>
                <?php
                    if(!empty($errors['username']) && $login==true){
                                echo "<div class='errorMessage1'>".$errors['username']."</div>";
                            }
                ?>



                <div class="accountText">Your password: <input type="password" name="password"></div><br/>
                <?php
                    if(!empty($errors['password']) && $login==true) {
                        echo "<div class='errorMessage1'>".$errors['password']."</div>";
                    }
                ?>

                <input  type="submit" id="submit1" name="submit" value="login">

            </form>
           
        </div>



        <!------------------------------------------- treatment data of the first form ------------------------------------------->
        <?php
            $correct= False;
            $incorrect= False;

            if(!empty($_POST) && $login==true)
            {
                if(empty($errors))
                {
                    // We are revovering informations correponding to username or mail adress and checking the password
                    $req = $bdd->prepare('SELECT person_id, person_password, mail_adress FROM person WHERE person_username=:username OR mail_adress=:username');
                    $req->execute(array('username'=> $infos['username']));


                    $result = $req->fetch();

                    //Verification of the password
                    $passwordCorrect= password_verify($infos['password'], $result['person_password']);


                    if($passwordCorrect)
                    {
                        $id= $result['person_id'];
                        $_SESSION['id'] = $id;

                        $req= $bdd->query('SELECT person_username FROM person WHERE person_id='.$id);
                        $result= $req->fetch();
                        $_SESSION['username'] = $result['person_username']; 
                        $passwordCorrect1= 'OK';

                        $correct= True;
                    }
                    elseif($result== false) 
                    {
                        $incorrect= True;
                    }
                    else
                    {
                        $incorrect= True;
                    }

                    $req ->closeCursor(); 
                }

                else{}
            }
        ?>




<!-- ----------------------------------------------------------Second form------------------------------------------------------- -->
        <div id="noAccount">
            <h1 class="form2">You don't have a profile yet (1/2)</h1><br/>

            <?php
                $form_errors= array();

                $form_name= "";
                $form_surname= "";
                $form_birth= 0;
                $form_mail= "";
                $form_city= "";
                $form_country= "";
                $form_username= "";
                $form_password= "";

                if(!empty($_POST)) 
                {
                    if(!empty($_POST['form_name'])) {
                        $form_name = trim($_POST['form_name']);
                    }
                    if(empty($form_name) && $register==True) {
                        $form_errors['form_name'] = 'Please enter your name';
                    }

                    if(!empty($_POST['form_surname'])) {
                        $form_surname = trim($_POST['form_surname']);
                    }
                    if(empty($form_surname) && $register==True) {
                        $form_errors['form_surname'] = 'Your surname is required';
                    }
                    

                    if(isset($_POST['form_birth']) && !empty($_POST['form_birth'])) {
                        $form_birth = trim($_POST['form_birth']);
                    }
                    if(empty($form_birth) && $register==True) {
                        $form_errors['form_birth'] = 'Please enter your birthdate';
                    }

                    if(!empty($_POST['form_mail'])) {
                        $form_mail = trim($_POST['form_mail']);
                    }
                    if(empty($form_mail) && $register==True) {
                        $form_errors['form_mail'] = "You didn't enter your mail";
                    }

                    if(!empty($_POST['form_city'])) {
                        $form_city = trim($_POST['form_city']);
                    }
                    if(empty($form_city) && $register==True) {
                        $form_errors['form_city'] = "Your city is required";
                    }

                    if(!empty($_POST['form_country'])) {
                        $form_country = trim($_POST['form_country']);
                    }
                    if(empty($form_country) && $register==True) {
                        $form_errors['form_country'] = "Your country is missing";
                    }

                    if(!empty($_POST['form_username'])) 
                    {
                        $form_username = trim($_POST['form_username']);
                    }
                    if(empty($form_username) && $register==True) 
                    {
                        $form_errors['form_username'] = 'Your username is required';
                    }

                    if(!empty($_POST['form_password'])) {
                        $form_password = trim($_POST['form_password']);
                    }
                    if(empty($form_password) && $register==True) {
                        $form_errors['form_password'] = 'Your password is required';
                    }
                }

            ?>


            <form method="post" action="">

                    <div class="formText">Name:  <input type="text" name="form_name" value="<?php echo $form_name; ?>"></div><br/>
                        <?php
                            //if($errorName==1 && !empty($_POST["submit"])){echo "<div class=".$errorsMess.">".'You need to enter a name!'."</div>";}
                            if(!empty($form_errors['form_name']) && $register==true) {
                                echo "<div class='errorMessage'>".$form_errors['form_name']."</div>";
                            }
                        ?>



                    <div class="formText">Surname: <input type="text" name="form_surname" value="<?php echo $form_surname; ?>"></div><br/>
                        <?php
                            if(!empty($form_errors['form_surname']) && $register==true) {
                                echo "<div class='errorMessage'>".$form_errors['form_surname']."</div>";
                            }
                        ?>


                    <div class="formText">Your birthdate:
                        <input type="date" name="form_birth" value= "<?php echo $form_birth; ?>"/>
                    </div> <br/>
                        <?php
                            if(!empty($form_errors['form_birth']) && $register==true) {
                                echo "<div class='errorMessage'>".$form_errors['form_birth']."</div>";
                            }
                        ?>



                    <div class="formText">Your mail adress: <input type="text" name="form_mail" value="<?php echo $form_mail; ?>"></div><br/>
                        <?php
                            if(!empty($form_errors['form_mail']) && $register==true) {
                                echo "<div class='errorMessage'>".$form_errors['form_mail']."</div>";
                            }
                        ?>

                        
                    <div class="formText">The name of your city: <input type="text" name="form_city" value="<?php echo $form_city; ?>"></div><br/>
                        <?php
                            if(!empty($form_errors['form_city']) && $register==true) {
                                echo "<div class='errorMessage'>".$form_errors['form_city']."</div>";
                            }
                        ?>


                    <div class="formText">Your country: <input type="text" name="form_country" value="<?php echo $form_country; ?>"></div><br/>
                        <?php
                            if(!empty($form_errors['form_country']) && $register==true) {
                                echo "<div class='errorMessage'>".$form_errors['form_country']."</div>";
                            }
                        ?>


                    <div class="formText">Choose an username: <input type="text" name="form_username" value="<?php echo $form_username; ?>"></div><br/>
                        <?php
                            if(!empty($form_errors['form_username']) && $register==true) {
                                echo "<div class='errorMessage'>".$form_errors['form_username']."</div>";
                            }
                        ?>



                    <div class="formText">Define a password: <input type="password" name="form_password" value="<?php echo $form_password; ?>"></div><br/>
                        <?php
                            if(!empty($form_errors['form_password']) && $register==true) {
                                echo "<div class='errorMessage'>".$form_errors['form_password']."</div>";
                            }
                        ?>
                    
                        <input  type="submit" id="submit2" name="submit" value="register">
            </form>
        </div>



                <!------------------------------------------- Treatment data of second form ------------------------------------------->
                <?php
                    $city_existing= False;
                    $id_city=0;
                    $existing= False;
                    $added= False;
                    if(!empty($_POST) && $register== True)
                    {

                        //We start by checking if the username or the mail adress is already existing
                        $req = $bdd->prepare('SELECT * FROM person WHERE person_username=:username OR mail_adress= :mail');
                        $req->execute(array(
                                        'username'=> $_POST['form_username'],
                                        'mail'=> $_POST['form_mail'],
                                    )); 
                        $answer = $req->fetch();


                        if ($answer){$existing= True;}
                        else {}

                        if($existing){}     //we will print an error message below
                        else        //if it doesn't exist we can insert
                        {     
                            if(empty($form_errors))
                            {
                                //We also need to check if the town exist
                                $req = $bdd->prepare('SELECT * FROM cities WHERE city_name=:name AND city_country=:country');
                                $req->execute(array(
                                                'name'=> $_POST['form_city'],
                                                'country'=> $_POST['form_country'],
                                            )); 
                                $answer = $req->fetch();

                                if($answer) //if city already exist we add just the id
                                {
                                    $id_city= $answer['city_id'];
                                }

                                else 
                                {
                                    // We now prepare the request for the city table
                                    $req = $bdd ->prepare('INSERT INTO cities(city_name, city_country) VALUES (:name, :country)');
                                    $req->execute(array(
                                                    'name' => $infos["form_city"],
                                                    'country' => $infos["form_country"],
                                                    ));

                                    // And we add, in the person table, the id of what we added before
                                    $id_city= $bdd->lastInsertId();
                                }


                                $infos["city_id"]= $id_city;
                                $infos['form_password'] = password_hash($infos['form_password'], PASSWORD_DEFAULT); //We make password unreadable
                                $added= True;
                            }

                            else{}
                        }
                    }
                    
                ?> 

            </div>


<!-- ---------------------------------------------------------DISCONNECT------------------------------------------------------- -->


    <div id="disconnect">
        <form method="post" action="">
            <input  type="submit" class="disconnect_button" name="submit" value="disconnect">
        </form>
    </div>

        <?php 
        // ob_start();
            $disconnected= False;
            $already_disconnected= False;
            if(!empty($_POST))
            {
                if($_POST["submit"]=="disconnect")
                {
                    if(!empty($_SESSION['id']))
                    {
                        unset($_SESSION['id']);
                        unset($_SESSION['username']);

                    $disconnected= True; 
                    }
                    else
                    {
                        $already_disconnected= True;
                    }
                    
                }
            }
        
//<!-- ---------------------------display confirmation messages and proceed to exiting------------------------------ -->


    
        if($correct){echo '<p class= "correct">'.'You are now connected !'.'</p>';}
        if($disconnected){echo '<p class= "correct">'.'You were succesfully disconnected !'.'</p>';}

        if($already_disconnected){echo '<p class= "incorrect">'.'Your are already disconnected !'.'</p>';}
        if($incorrect){echo '<p class= "incorrect">'.'Your username or you password is incorrect !'.'</p>';}
        if($existing){echo '<p class= "incorrect">'.'Your username or your mail adress is already taken !'.'</p>';}

        if($added)
        {
            foreach ($infos as $key => $value) 
            {
                $_SESSION[$key]= $value;    //using the session table will allow us to insert in the next page
            }

            header('Location: register.php');
        }

    // ob_end_flush();
    ?>
    </body>
    
</html>