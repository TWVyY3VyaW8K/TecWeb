<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html, charset=utf-8" />
  <meta name="description" content="Online artwork database"/>
  <meta name="keywords" content="artwork,picture,image,database"/>
  <meta name="author" content="Daniele Bianchin, Pardeep Singh, Davide Liu, Harwinder Singh"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<link rel="stylesheet" href="Style/style.css"/>
	<link rel="stylesheet" href="Style/print-style.css" type="text/css" media="print" />
	<script type="text/javascript" src="script.js" ></script>
  <title>Artbit Login</title>
</head>

<body  >
  <?php
	require_once "header.php";
	require_once "DbConnector.php";
	require_once "functions.php";
	
	if(isset($_SERVER['HTTP_REFERER'])&&strstr($_SERVER['HTTP_REFERER'], "login.php")==false && strstr($_SERVER['HTTP_REFERER'], "signUp.php")==false && strstr($_SERVER['HTTP_REFERER'], "resetPassword.php")==false)saveBackPage();
	$connectionError=$invalidPwd=$invalidUsw=false;

	if(isset($_POST["pwd"])&&isset($_POST["usr"])){
			$pwd= htmlspecialchars($_POST["pwd"], ENT_QUOTES, "UTF-8");//cleaning the input
			$usr= htmlspecialchars($_POST["usr"], ENT_QUOTES, "UTF-8");
			//connecting to db
			$myDb= new DbConnector();
			$myDb->openDBConnection();
			if($myDb->connected)
			{
				$result = $myDb->doQuery("select * from artisti where Username='".$usr."'");//excecute query
				if(isset($usr) && !is_null($result) && $result->num_rows === 1)
				{
					$row = $result->fetch_assoc();
					if (password_verify($pwd, $row["Password"]))
					{
						$_SESSION["Username"] = $usr;
				
						if(isset($_SESSION['backPage'])) {
							header("location: ".$_SESSION['backPage']);
							die();
						} else {
							header("location: /index.php");
							die();
						}
					}
					else
						$invalidPwd=true;
				}
				else
						$invalidUsw=true;
			}
			else 
					$connectionError=true;
			$myDb->disconnect();
	}


  ?>
  <div class="fullScreenHeight loginTopPadding">
  <div id="LoginCard" class="loginCard container1024">
  	<form class="loginCard-content " method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
  		<div class="loginCard-Head">
  			<h1>LOGIN FORM</h1>
  		</div>
  		<div class="container">
  			<label for="usr">Username<?php if($invalidUsw)echo '<span class="red">**<span>' ?></label>
  			<input id="usr" type="text" name="usr" maxlength="20"/>

  			<label for="pwd">Password<?php if($invalidPwd)echo '<span class="red">**<span>' ?></label>
  			<input  id="pwd" type="password" name="pwd" maxlength="30"/>
			Don't remember your password? <a href="resetPassword.php">Click to Reset</a>
  		</div>
  		<div class="container" id="InvalidLogin">
  			<!--container for invalid login message-->
				<?php 
					if($connectionError)echo "Connection Error. Try again later.";
					if($invalidPwd)echo "Invalid Password";
					if($invalidUsw)echo "Invalid Username";
				
				
				?>
  		</div>

  		<div class="container loginCard-footer" >
  			<button type="submit">Login</button>
				<?php echo getBackButton(); ?>
  		</div>

  	</form>
  </div>
</div>


  <?php require_once "footer.html"?>
</body>
</html>
