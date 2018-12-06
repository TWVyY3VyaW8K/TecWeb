<?php
    require_once "functions.php";
    require_once "DbConnector.php";

    if ( is_session_started() === FALSE ) session_start();
    $resArr = array();
    $artista= htmlspecialchars($_POST["art"], ENT_QUOTES, "UTF-8");//cleaning the input
    $nomeImmagine= htmlspecialchars($_POST["nomeImg"], ENT_QUOTES, "UTF-8");
    //connecting to db
    $myDb= new DbConnector();
    $myDb->openDBConnection();
    
    $tmpArr = array();
    if($myDb->connected){
        //echo 'INSERT INTO Likes (Opera, Utente, Creatore) VALUES ("'.$nomeImmagine.'", "'.$_SESSION["Username"].'", "'.$artista.'");';
        //$result = $myDb->doQuery('INSERT INTO Likes (Opera, Utente, Creatore) VALUES ("'.$nomeImmagine.'", "'.$_SESSION['Username'].'", "'.$artista.'");');//excecute query
        $result = $myDb->doQuery('SELECT Utente FROM likes WHERE Opera="'.$nomeImmagine.'" AND Creatore="'.$artista.'";');
        //echo $myDb->getLastErrorString();
        if($result){
            for ($i = 0; $i < $result->num_rows; $i++) {
                $row = $result->fetch_assoc();
                array_push($tmpArr,$row['Utente']);
            }
            $resArr['Users'] = $tmpArr;
            $resArr['Result'] = 1;
        }else{
            $resArr['Result'] = -1;
        }
    }
    else 
        $resArr['Result'] = "Connection Error";
    $myDb->disconnect();

    echo json_encode($resArr);
?>