<?php
	require_once "DbConnector.php";
	session_start();

    function error($str) {
        echo $str;
        sleep(3);
        header('location: /viewArtwork.php');
        exit(0);
    }

	$Opera = $_POST['Opera'];
	$Creatore = $_POST['Creatore'];
	$Commento = htmlspecialchars($_POST['Commento'], ENT_QUOTES, "UTF-8");

	if(!isset($_SESSION['Username']))
    	error('Login before!');

    if(!isset($Opera) || !isset($Creatore)|| empty(trim($_POST['Commento'])))
    	error('Empty field!');

	$qrStr = "INSERT INTO `commenti`(`Opera`, `Utente`, `Creatore`, `Commento`) VALUES ('".$Opera."','".$_SESSION['Username']."','".$Creatore."','".$Commento."')";
	$myDb= new DbConnector();
    $myDb->openDBConnection();

    if(!$myDb->connected)
        error('Database problem!');

    if(!$myDb->doQuery($qrStr))
        error('Query failed!');

    header('location: /viewArtwork.php');
?>
