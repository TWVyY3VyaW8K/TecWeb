<?php
	require_once "DbConnector.php";
	session_start();
    
	$ID = $_POST['ID'];
	if(!isset($_SESSION['Username'])) {
    	echo '--alert--Login before!';
        exit(0);
    }
	$qrstr = "SELECT ID FROM commenti WHERE ID=".$ID;
    if ($_SESSION['Username'] !== 'Admin')
    	$qrstr .= " AND Utente='".$_SESSION['Username']."'";
    $myDb= new DbConnector();
    $myDb->openDBConnection();
    if(!$myDb->connected) {
        echo '--alert--Database problem!';
        exit(0);
    }
    if($myDb->doQuery($qrstr)->num_rows !== 1) {
    	echo '--alert--Artwork not found or wrong artwork owner';
        exit(0);
    }
    $qrstr = "DELETE FROM `my_artbit`.`commenti` WHERE `commenti`.`ID`=".$ID;
    $myDb->doQuery($qrstr);
?>
