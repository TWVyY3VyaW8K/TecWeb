<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <meta http-equiv="Content-Type" content="application/xhtml+xml; charset=UTF-8"/>
    <meta name="description" content="Online artwork database"/>
    <meta name="keywords" content="artwork,picture,image,database"/>
    <meta name="author" content="Daniele Bianchin, Pardeep Singh, Davide Liu, Harwinder Singh"/>
    <meta name="title" content="Edit Profile - Artbit"/>
    <meta name="language" content="english en"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1"/>
    <link rel="stylesheet" type="text/css" href="Style/style.css" media="all"/>
    <link rel="stylesheet" type="text/css" href="Style/print.css" media="print" />
    <script type="text/javascript" src="script.js" ></script>
    <link rel="icon" type="image/png" href="Images/logo.png"/>
    <title>Edit Profile - Artbit</title>
</head>

<body>
<?php
	require_once "header.php";
	require_once "DbConnector.php";
	require_once "functions.php";
	
	if(isset($_SERVER['HTTP_REFERER'])&&strstr($_SERVER['HTTP_REFERER'], "signUp.php")==false)saveBackPage();
    //variable used for possibile error states
    $invalidPwdMinLenght = false;
    $invalidConfirmPwd = false;
    $invalidEmail = false;
    $notAllFieldsFilled=false;
    $connectionError=false;
    $editSuccess=false;
  

    //check if all fields have values
    if(!isset($_POST["pwdEdit"])|| !isset($_POST["nameEdit"]) || !isset($_POST["surnameEdit"]) || !isset($_POST["emailEdit"]) || !isset($_POST["pwdConfirmEdit"] )){
        $notAllFieldsFilled = true;
    }
    else{
        if(empty($_POST["pwdEdit"])||  empty($_POST["nameEdit"]) || empty($_POST["surnameEdit"]) || empty($_POST["emailEdit"]) || empty($_POST["pwdConfirmEdit"] ))
            $notAllFieldsFilled = true;
        


    }
    
    if(!$notAllFieldsFilled){
        $pwd= htmlspecialchars($_POST["pwdEdit"], ENT_QUOTES, "UTF-8");//cleaning the input
        $usr= $_SESSION['Username'];
        $name= htmlspecialchars($_POST["nameEdit"], ENT_QUOTES, "UTF-8");//cleaning the input
        $surname= htmlspecialchars($_POST["surnameEdit"], ENT_QUOTES, "UTF-8");
        $email= htmlspecialchars($_POST["emailEdit"], ENT_QUOTES, "UTF-8");
        $pwdConfirm= htmlspecialchars($_POST["pwdConfirmEdit"], ENT_QUOTES, "UTF-8");
        //password and email constraints
        if(strlen($pwd)<5)$invalidPwdMinLenght=true;
        if($pwd !== $pwdConfirm)$invalidConfirmPwd=true;
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))$invalidEmail=true;

        if(!$invalidPwdMinLenght && !$invalidConfirmPwd && !$invalidEmail ){
        //connecting to db
            $myDb= new DbConnector();
            $myDb->openDBConnection();
            $pwd=password_hash($pwd,PASSWORD_BCRYPT);
            if($myDb->connected){

                if(isset($_SESSION['Username']))
                {
                    $result = $myDb->doQuery("UPDATE artisti SET Password='$pwd', Nome='$name', Cognome='$surname',Email='$email' where Username ='$usr'");//excecute query
                    if($result === TRUE){//if inserted
                        $editSuccess=true;
                        $_SESSION["Username"] = $usr;
        
                    }
                    else
                        $editSuccess=false;
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
        <form class="loginCard-content " onsubmit="return validateEditForm()"   method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
            <div class="loginCard-Head">
                <h1>Edit Profile</h1>
            </div>
            <div class="container">
                <label for="usrEdit">
                    Username 
                </label>
                <input id="usrEdit" type="text"  name="usrEdit" maxlength="20" <?php echo ' value="'.$_SESSION["Username"].'"'; ?> readonly="readonly" />

                <label for="emailEdit">
                    Email
                    <?php 
                    if(!isset($_POST['emailEdit']))
                    {
                        echo "(You MUST fill this field)";
                    }else{
                        if($invalidEmail)
                            echo " (INVALID EMAIL)";
                    }
                    ?>

                </label>
                <input id="emailEdit" type="text"  name="emailEdit" <?php if(isset($_POST['emailEdit']) && !$invalidEmail)echo 'value="'.$_POST['emailEdit'].'"'?> />

                <label for="pwdEdit">Password (minimum length 5 characters)
                    <?php 
                        if(!isset($_POST['pwdEdit']) || $_POST['pwdEdit']=="")
                        {
                            echo "(You MUST fill this field)";
                        }else{
                            if($invalidPwdMinLenght)
                                echo " - INVALID LENGTH";
                        }
                    ?>

                </label>
                <input id="pwdEdit" type="password" name="pwdEdit" maxlength="30" />

                <label for="pwdConfirmEdit">
                    Confirm Password
                    <?php 
                        if(!isset($_POST['pwdConfirmEdit']) || $_POST['pwdConfirmEdit']=="" )
                        {
                            echo "(You MUST fill this field)";
                        }else{
                            if($invalidConfirmPwd)
                                echo " (PASSWORDS DO NOT MATCH)";
                        }
                    ?>

                </label>
                <input id="pwdConfirmEdit" type="password" name="pwdConfirmEdit" maxlength="30"/>

                <label for="nameEdit">
                    Name
                    <?php 
                        if(!isset($_POST['nameEdit'])||$_POST['nameEdit']=="")
                        {
                            echo "(You MUST fill this field)";
                        }
                    ?>
                </label>
                <input id="nameEdit" type="text"  name="nameEdit" <?php if(isset($_POST['nameEdit']))echo 'value="'.$_POST['nameEdit'].'"'?> />

                <label for="surnameEdit">
                    Surname
                    <?php 
                        if(!isset($_POST['surnameEdit'])||$_POST['surnameEdit']=="")
                        {
                            echo "(You MUST fill this field)";
                        }
                    ?>
                </label>
                <input id="surnameEdit" type="text"  name="surnameEdit"  <?php if(isset($_POST['surnameEdit']))echo 'value="'.$_POST['surnameEdit'].'"'?> />

            </div>
            <div class="container" id="EditProfileMessage">
                <!--container for invalid login message-->
                <?php
                    if($connectionError)
                        echo " Connection error. Please try again later ";
                    else{
                        if($editSuccess)
                            echo "New updates saved correctly";
                        else{
                            if($notAllFieldsFilled){
                                echo " Please fill all the fields ";
                            }else{
                                
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
                <button type="submit">Confirm</button>
				<?php echo getBackButton(); ?>
            </div>

        </form>
    </div>
</div>








 <?php require_once "footer.html"?>
</body>
</html>
