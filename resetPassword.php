<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <meta http-equiv="Content-Type" content="application/xhtml+xml; charset=UTF-8"/>
    <meta name="description" content="Online artwork database"/>
    <meta name="keywords" content="artwork,picture,image,database"/>
    <meta name="author" content="Daniele Bianchin, Pardeep Singh, Davide Liu, Harwinder Singh"/>
    <meta name="title" content="Reset Password - Artbit"/>
    <meta name="language" content="english en"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1"/>
    <link rel="stylesheet" type="text/css" href="Style/style.css" media="all"/>
    <link rel="stylesheet" type="text/css" href="Style/print.css" media="print" />
    <script type="text/javascript" src="script.js" ></script>
    <link rel="icon" type="image/png" href="Images/logo.png"/>
    <title>Reset Password - Artbit</title>
</head>

<body  >
  <?php
	require_once "header.php";
	require_once "DbConnector.php";
	require_once "functions.php";

	//if(isset($_SERVER['HTTP_REFERER'])&&strstr($_SERVER['HTTP_REFERER'], "login.php")==false && strstr($_SERVER['HTTP_REFERER'], "signUp.php")==false )saveBackPage();
	$connectionError=$resetSuccess=$resetEmail=false;

	if(isset($_POST["email"])&&isset($_POST["usr"])){
			$email= htmlspecialchars($_POST["email"], ENT_QUOTES, "UTF-8");//cleaning the input
			$usr= htmlspecialchars($_POST["usr"], ENT_QUOTES, "UTF-8");
			//connecting to db
			$myDb= new DbConnector();
            $myDb->openDBConnection();
            $randomPwd=randomPassword();
            $pwd=password_hash($randomPwd,PASSWORD_BCRYPT);
			if($myDb->connected)
			{

                $result = $myDb->doQuery("select * from artisti where Username ='$usr' and Email='$email'");//excecute query

                if($result->num_rows===1)
                {

                    $result = $myDb->doQuery("UPDATE artisti SET Password='$pwd' where Username ='$usr' and Email='$email'");//excecute query
                    if($result === TRUE){//if inserted
                        echo '<div style="display:none;">'.$randomPwd.'</div>';

                        $resetSuccess=true;
                        $to      = $email;
                        $subject = 'Artbit Password Reset';
                        $message = 'Hello '.$usr.',<br>Your Password has been resetted to:'.$randomPwd.' . Have Fun! <br>Thanks, ArtBit Team';
                        $headers = 'From: artbitemail@gmail.com' . "\r\n" .
                        'Reply-To: artbitemail@gmail.com' . "\r\n" .
                        'X-Mailer: PHP/' . phpversion();

                        $resetEmail= mail($to, $subject, $message, $headers);

                    }
                    else
                        $resetSuccess=false;
                }else
                    $resetSuccess=false;
			}
			else
					$connectionError=true;
			$myDb->disconnect();
	}


  ?>
  <div class="fullScreenHeight loginTopPadding">
  <div id="LoginCard" class="loginCard container1024">
  	<form class="loginCard-content " onsubmit="return validateResetForm()" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
  		<div class="loginCard-Head">
  			<h1>RESET PASSWORD FORM</h1>
  		</div>
  		<div class="container">
  			<label for="usr">Username</label>
  			<input id="usr" type="text" name="usr" maxlength="20"/>

  			<label for="email">Email</label>
  			<input  id="email" type="text" name="email" maxlength="30"/>

  		</div>
  		<div class="container" id="InvalidLogin">

				<?php
                    if($connectionError)
                        echo "Connection Error. Try again later.";
                    else
                        if(!$resetSuccess)
                            if(!isset($_POST['reset']))
                                echo "Fill all the fields and press Reset.";
                            else
                                echo "Invalid combination of Username and Password";
                        else
                            if(!$resetEmail)
                                echo "Your Password was resetted to a new randome one, but we weren't enable to send you the new password by email. Please try to reset later.";
                            else
                                echo "Password resetted successfully. Check the new password in your email.";

				?>
  		</div>

  		<div class="container loginCard-footer" >
  			<button class="singleButton" type="submit" name="reset">Reset</button>
  		</div>

  	</form>
  </div>
</div>


  <?php require_once "footer.html"?>
</body>
</html>
