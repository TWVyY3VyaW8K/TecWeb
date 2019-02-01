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
  <title>Liked By</title>
</head>

<body  >
  <?php
	require_once "header.php";
	require_once "DbConnector.php";
	require_once "functions.php";

  ?>
  <div class="fullScreenHeight loginTopPadding">
  <div class="loginCard container1024" id="LikedByDiv">
  		<div class="loginCard-Head">
  			<h1>Liked By</h1>
  		</div>
  		<div class="container">
        <?php
            if ( is_session_started() === FALSE ) session_start();
            $artist= isset($_GET["artist"]) ? htmlspecialchars($_GET["artist"], ENT_QUOTES, "UTF-8") : "";//cleaning the input
            $imgName= isset($_GET["imgName"]) ? htmlspecialchars($_GET["imgName"], ENT_QUOTES, "UTF-8") : "";
            //connecting to db
            $myDb= new DbConnector();
            $myDb->openDBConnection();

            $tmpArr = array();
            if($myDb->connected){

                $result = $myDb->doQuery('SELECT Utente FROM likes WHERE Opera="'.$imgName.'" AND Creatore="'.$artist.'";');

                if($result){
                    if($result->num_rows > 0){
                        for($i=0; $i < $result->num_rows; $i++){
                            $row = $result->fetch_assoc();
                            echo '<div class="comment"><a href="gallery.php?gallerySearch='.$row['Utente'].'">'.$row['Utente'].'</a></div>';
                        }
                    }else{
                        echo "<div class='liPaginationBlock'><div class='div-center'><p>Nothing to show here ... </p></div></div>";
                    }
                }else{
                    echo "<div class='liPaginationBlock'><div class='div-center'><p>Nothing to show here ... </p></div></div>";
                }
            }
            else
                echo "<div class='liPaginationBlock'><div class='div-center'><p>Connection error! </p></div></div>";
            $myDb->disconnect();
        ?>
  		</div>

  		<div class="container loginCard-footer" >
			<?php echo getBackButton(); ?>
  		</div>
  </div>
</div>


  <?php require_once "footer.html"?>
</body>
</html>
