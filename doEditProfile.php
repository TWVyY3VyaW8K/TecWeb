<?php
	require_once "DbConnector.php";

	session_start();
	$pwd= htmlspecialchars($_POST["pwd"], ENT_QUOTES, "UTF-8");//cleaning the input
	$usr= $_SESSION['Username'];
	$name= htmlspecialchars($_POST["name"], ENT_QUOTES, "UTF-8");//cleaning the input
	$surname= htmlspecialchars($_POST["surname"], ENT_QUOTES, "UTF-8");
	//connecting to db
	$myDb= new DbConnector();
	$myDb->openDBConnection();
	$pwd=password_hash($pwd,PASSWORD_BCRYPT);
	if($myDb->connected){
		if(isset($_SESSION['Username']))
		{
			$result = $myDb->doQuery("UPDATE artisti SET Password='$pwd', Nome='$name', Cognome='$surname' where Username ='$usr'");//excecute query
			if($result === TRUE){//if inserted

				echo "New updates saved correctly";
				$_SESSION["Username"] = $usr;

			}
			else
				echo 'Error. Try Again';
		}
		else
			echo 'Please login before';
	}
	else
		echo 'Connection Error';
	$myDb->disconnect();
?>
