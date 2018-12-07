<?php
	require_once "DbConnector.php";
	session_start();

	$Opera = $_POST['Opera'];
	$Creatore = $_POST['Creatore'];
	$Commento = htmlspecialchars($_POST['Commento'], ENT_QUOTES, "UTF-8");

	if(!isset($_SESSION['Username'])) {
    	echo '--alert--Login before!';
        exit(0);
    }
    if(!isset($Opera) || !isset($Creatore)|| empty(trim($_POST['Commento'])))
    {
    	echo '--alert--Empty field!';
        exit(0);
    }

	$qrStr = "INSERT INTO `commenti`(`Opera`, `Utente`, `Creatore`, `Commento`) VALUES ('".$Opera."','".$_SESSION['Username']."','".$Creatore."','".$Commento."')";
	$myDb= new DbConnector();
    $myDb->openDBConnection();

    if(!$myDb->connected) {
        echo '--alert--Database problem!';
        exit(0);
    }

    if($myDb->doQuery($qrStr))
    {
    	echo '<div class="comment">';
        echo '<div class="delComment" onclick="removeComment(this, '.$myDb->lastInsertID().')"> x </div>';
        echo '<a href="gallery.php?gallerySearch='.$_SESSION['Username'].'">'.$_SESSION['Username'].'</a>';
        echo $Commento."</div>";
    }
    else
    	echo '--alert--Query failed!';
?>
