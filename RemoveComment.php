<?php
	require_once "DbConnector.php";
	session_start();

    function error($str) {
        echo $str;
        sleep(3);
        header('location: /viewArtwork.php');
        exit(0);
    }
    
	$ID = $_GET['ID'];

	if(!isset($_SESSION['Username']))
    	error('Login before!');

	$qrstr = "SELECT ID FROM commenti WHERE ID=".$ID;
    if ($_SESSION['Username'] !== 'Admin')
    	$qrstr .= " AND Utente='".$_SESSION['Username']."'";

    $myDb= new DbConnector();
    $myDb->openDBConnection();

    if(!$myDb->connected)
        error('Database problem!');

    if($myDb->doQuery($qrstr)->num_rows !== 1)
        error('Artwork not found or wrong artwork owner');

    $qrstr = "DELETE FROM `my_artbit`.`commenti` WHERE `commenti`.`ID`=".$ID;
    $myDb->doQuery($qrstr);
    header('location: /viewArtwork.php');
?>
