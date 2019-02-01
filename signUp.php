<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html, charset=utf-8" />
  <meta name="description" content="Online artwork database"/>
  <meta name="keywords" content="artwork,picture,image,database"/>
  <meta name="author" content="Daniele Bianchin, Pardeep Singh, Davide Liu, Harwinder Singh"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<link rel="stylesheet" href="Style/style.css"/>
	<link rel="stylesheet" href="Style/print.css" type="text/css" media="print" />
	<script type="text/javascript" src="script.js" ></script>
  <title>Artbit SignUp</title>
</head>

<body>
<?php
	require_once "header.php";
	require_once "DbConnector.php";
	require_once "functions.php";

	//if(isset($_SERVER['HTTP_REFERER'])&&strstr($_SERVER['HTTP_REFERER'], "signUp.php")==false && strstr(strtolower($_SERVER['HTTP_REFERER']), "login.php")==false && strstr($_SERVER['HTTP_REFERER'], "resetPassword.php")==false)saveBackPage();
    $invalidPwdMinLenght = false;
    $invalidConfirmPwd = false;
    $invalidEmail = false;
    $notAllFieldsFilled=false;
    $invalidUsername=false;
    $connectionError=false;



    if(!isset($_POST["pwdSignUp"])|| !isset($_POST["usrSignUp"]) || !isset($_POST["name"]) || !isset($_POST["surname"]) || !isset($_POST["emailSignUp"]) || !isset($_POST["pwdConfirmSignUp"] )){

        $notAllFieldsFilled = true;
    }
    else{
        if(empty($_POST["pwdSignUp"])|| empty($_POST["usrSignUp"]) || empty($_POST["name"]) || empty($_POST["surname"]) || empty($_POST["emailSignUp"]) || empty($_POST["pwdConfirmSignUp"] ))
            $notAllFieldsFilled = true;



    }

    if(!$notAllFieldsFilled){
        $pwd= htmlspecialchars($_POST["pwdSignUp"], ENT_QUOTES, "UTF-8");//cleaning the input
        $usr= htmlspecialchars($_POST["usrSignUp"], ENT_QUOTES, "UTF-8");
        $name= htmlspecialchars($_POST["name"], ENT_QUOTES, "UTF-8");//cleaning the input
        $surname= htmlspecialchars($_POST["surname"], ENT_QUOTES, "UTF-8");
        $email= htmlspecialchars($_POST["emailSignUp"], ENT_QUOTES, "UTF-8");
        $pwdConfirm= htmlspecialchars($_POST["pwdConfirmSignUp"], ENT_QUOTES, "UTF-8");

        if(strlen($pwd)<5)$invalidPwdMinLenght=true;
        if($pwd !== $pwdConfirm)$invalidConfirmPwd=true;
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))$invalidEmail=true;

        if(!$invalidPwdMinLenght && !$invalidConfirmPwd && !$invalidEmail ){
        //connecting to db
            $myDb= new DbConnector();
            $myDb->openDBConnection();
            $pwd=password_hash($pwd,PASSWORD_BCRYPT);
            if($myDb->connected){
                $result = $myDb->doQuery("insert into artisti values ('$usr','$pwd','$name','$surname','$email')");//excecute query
                if($result === TRUE){//if inserted
                    //echo "Success";
                    $_SESSION["Username"] = $usr;
                    mkdir("./Images/Art/$usr", 0777, true);

                    $to      = $email;
                    $subject = 'Registration on ArtBit';
                    $message = 'Hello '.$name.',<br>We confirm you the registration on our website. Have Fun! <br>Thanks, ArtBit Team';
                    $headers = 'From: artbitemail@gmail.com' . "\r\n" .
                    'Reply-To: artbitemail@gmail.com' . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();

                    mail($to, $subject, $message, $headers);




                    if(isset($_SESSION['backPage'])) {
                        header("location: ".$_SESSION['backPage']);
                        die();
                    } else {
                        header("location: /index.php");
                        die();
                    }
                }else{
                    $invalidUsername=true;

                    //echo 'Username already exists. Try another one';
                }
            }
            else
                $connectionError=true;
            $myDb->disconnect();
        }
    }



?>



<div class="fullScreenHeight loginTopPadding">

   <div id="LoginCard" class="loginCard container1024">
        <form class="loginCard-content " method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
            <div class="loginCard-Head">
                <h1>SIGN UP FORM</h1>
            </div>
            <div class="container">
                <label for="usrSignUp">
                    Username
                    <?php
                    if(!isset($_POST['usrSignUp']) ||$_POST['usrSignUp'] =="" )
                    {
                        echo "(You MUST fill this field)";
                    }else{
                        if($invalidUsername)
                            echo " already exists. Choose another one ";
                    }
                    ?>

                </label>
                <input id="usrSignUp" type="text"  name="usrSignUp" maxlength="20" <?php if(isset($_POST['usrSignUp']) && !$invalidUsername)echo 'value="'.$_POST['usrSignUp'].'"'?> />

                <label for="emailSignUp">
                    Email
                    <?php
                    if(!isset($_POST['emailSignUp']))
                    {
                        echo "(You MUST fill this field)";
                    }else{
                        if($invalidEmail)
                            echo " (INVALID EMAIL)";
                    }
                    ?>

                </label>
                <input id="emailSignUp" type="text"  name="emailSignUp" <?php if(isset($_POST['emailSignUp']) && !$invalidEmail)echo 'value="'.$_POST['emailSignUp'].'"'?> />

                <label for="pwdSignUp">Password (minimum length 5 characters)
                    <?php
                        if(!isset($_POST['pwdSignUp']) || $_POST['pwdSignUp']=="")
                        {
                            echo "(You MUST fill this field)";
                        }else{
                            if($invalidPwdMinLenght)
                                echo " - INVALID LENGTH";
                        }
                    ?>

                </label>
                <input id="pwdSignUp" type="password" name="pwdSignUp" maxlength="30" />

                <label for="pwdConfirmSignUp">
                    Confirm Password
                    <?php
                        if(!isset($_POST['pwdConfirmSignUp']) || $_POST['pwdConfirmSignUp']=="" )
                        {
                            echo "(You MUST fill this field)";
                        }else{
                            if($invalidConfirmPwd)
                                echo " (PASSWORDS DO NOT MATCH)";
                        }
                    ?>

                </label>
                <input id="pwdConfirmSignUp" type="password" name="pwdConfirmSignUp" maxlength="30"/>

                <label for="name">
                    Name
                    <?php
                        if(!isset($_POST['name'])||$_POST['name']=="")
                        {
                            echo "(You MUST fill this field)";
                        }
                    ?>
                </label>
                <input id="name" type="text"  name="name" <?php if(isset($_POST['name']))echo 'value="'.$_POST['name'].'"'?> />

                <label for="surname">
                    Surname
                    <?php
                        if(!isset($_POST['surname'])||$_POST['surname']=="")
                        {
                            echo "(You MUST fill this field)";
                        }
                    ?>
                </label>
                <input id="surname" type="text"  name="surname"  <?php if(isset($_POST['surname']))echo 'value="'.$_POST['surname'].'"'?> />

            </div>
            <div class="container" id="SignUpMessage">
                <!--container for invalid login message-->
                <?php
                    if($connectionError)
                        echo " Connection error. Please try again later ";
                    else{
                        if($notAllFieldsFilled){
                            echo " Please fill all the fields ";
                        }else{
                            if($invalidUsername)
                                echo " Username already exists. Choose another one ";
                            else{
                                if($invalidPwdMinLenght)
                                    echo " Password must be at least 5 characters ";
                                else{
                                    if($invalidConfirmPwd)
                                        echo " Passwords do not match ";
                                    else{
                                        if($invalidEmail)
                                            echo " Invalid Email ";
                                    }

                                }
                            }
                        }
                    }
                ?>

            </div>

            <div class="container loginCard-" >
                <button type="submit">Register</button>
				<?php echo getBackButton(); ?>
            </div>

        </form>
    </div>
</div>








 <?php require_once "footer.html"?>
</body>
</html>
