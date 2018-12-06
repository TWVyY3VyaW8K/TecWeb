<?php
	require_once "DbConnector.php";
//pass per account admin AdminIsHere

	session_start();
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
				echo 'Success';
			}
			else
				echo 'Invalid password';
		}
		else
			echo 'Invalid username';
	}
	else 
		echo 'Connection error';
	$myDb->disconnect();
?>