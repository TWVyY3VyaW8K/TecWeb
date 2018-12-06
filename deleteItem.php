<?php
    require_once "functions.php";
    require_once "DbConnector.php";

    $artista= htmlspecialchars($_POST["art"], ENT_QUOTES, "UTF-8");//cleaning the input
    $nomeImmagine= htmlspecialchars($_POST["nomeImg"], ENT_QUOTES, "UTF-8");
    
    //Calling function from file functions.php
    echo json_encode(deleteItem($artista, $nomeImmagine));
?>