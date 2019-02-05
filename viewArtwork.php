<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

    <head>
      <meta http-equiv="Content-Type" content="application/xhtml; charset=UTF-8"/>
      <meta name="description" content="Online artwork database"/>
      <meta name="keywords" content="artwork,picture,image,database"/>
      <meta name="author" content="Daniele Bianchin, Pardeep Singh, Davide Liu, Harwinder Singh"/>
      <meta name="title" content="View Artwork - Artbit"/>
      <meta name="language" content="english en"/>
      <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1"/>
      <link rel="stylesheet" type="text/css" href="Style/style.css" media="handheld, screen"/>
      <link rel="stylesheet" type="text/css" href="Style/print.css" media="print" />
      <script type="text/javascript" src="script.js" ></script>
      <link rel="icon" type="image/png" href="Images/logo.png"/>
      <title>View Artwork - Artbit</title>
    </head>
    <body onload="magnify(); " >
      <?php
        require_once "header.php";
        require_once "DbConnector.php";
        require_once "functions.php";
        saveBackPage();
        if(isset($_GET['lNumI'])){
          galleryImageNumberFromUrl();
        }
        $Title = $_GET['Title'];
        $Artist = $_GET['Artist'];

        $myDb= new DbConnector();
        $myDb->openDBConnection();
        if($myDb->connected)
        {
         if(isset($Title) && isset($Artist))
         {
          $qrStr = 'SELECT Artista, Nome, Descrizione, Categoria, Data_upload FROM opere WHERE Artista ="'.$Artist.'"'.' AND Nome ="'.$Title.'"';
          $result = $myDb->doQuery($qrStr);
          if(isset($result) && ($result->num_rows === 1))
          {
            $Error= "";
            $row = $result->fetch_assoc();
            $Title = $row['Nome'];
            $Artist = $row['Artista'];
            $Description = $row['Descrizione'];
            $Category = $row['Categoria'];
            $Date = $row['Data_upload'];
            $qrStr = 'SELECT Opera FROM likes WHERE Opera="'.$Title.'"'.' AND Creatore="'.$Artist.'"';
            $Likes = $myDb->doQuery($qrStr)->num_rows;
            $qrStr = 'SELECT Opera FROM commenti WHERE Opera="'.$Title.'"'.' AND Creatore="'.$Artist.'"';
            $Comments = $myDb->doQuery($qrStr)->num_rows;
            $qrStr = 'SELECT Nome, Cognome FROM artisti WHERE Username="'.$Artist.'"';
            $result = $myDb->doQuery($qrStr);
            $row = $result->fetch_assoc();
            $ArtistName = $row['Nome'] . " " . $row['Cognome'];
            $isLiked = false;
            $descriptionUpdated = false;

            if(isset($_POST['UserNotLogged']) && !isset($_SESSION['Username'])){
              header("location: login.php");
            }
            if(isset($_POST['input-description']) && isset($_SESSION['Username']))
            {
              if (strtolower($_SESSION['Username']) !== 'admin')
              {
                $qrstr = "SELECT * FROM opere WHERE Artista='".$_SESSION['Username']."' AND Nome='".$Title."'";
                if($myDb->doQuery($qrstr)->num_rows !== 1)
                {
                  $Error = 'Artwork not found or wrong artwork owner';
                  $check = false;
                }
                else
                  $check = true;
              }
              else
                $check = true;
              if($check)
              {
                $Description = htmlspecialchars($_POST['input-description'], ENT_QUOTES, "UTF-8");
                $qrstr = "UPDATE opere SET Descrizione='".$Description."' WHERE Nome='".$Title."' AND Artista='".$Artist."'";
                $myDb->doQuery($qrstr);
                $descriptionUpdated = true;
              }
            }
            if(isset($_GET['Remove']))
            {
              $ID = $_GET['Remove'];
              $qrstr = "SELECT ID FROM commenti WHERE ID=".$ID;
              if (isset($_SESSION['Username']) && strtolower($_SESSION['Username']) !== 'admin')
                $qrstr .= " AND Utente='".$_SESSION['Username']."'";
              if($myDb->doQuery($qrstr)->num_rows !== 1)
                $Error = 'Artwork not found or wrong artwork owner';
              else if(isset($_SESSION['Username']))
                 {
                  $qrstr = "DELETE FROM commenti WHERE ID=".$ID;
                  $myDb->doQuery($qrstr);

                  $_SESSION["backPage"] =  removeqsvar($_SERVER['REQUEST_URI'], 'Remove');
                 }
            }
            else if(isset($_POST['input-comment']))
            {
              $Comment = htmlspecialchars($_POST['input-comment'], ENT_QUOTES, "UTF-8");
              if(empty(trim($Comment)))
                $Error = 'Empty field!';
              else
              {
                if(isset($_SESSION['Username']))
                  $qrStr = "INSERT INTO `commenti`(`Opera`, `Utente`, `Creatore`, `Commento`) VALUES ('".$Title."','".$_SESSION['Username']."','".$Artist."','".$Comment."')";
                if(!$myDb->doQuery($qrStr))
                  $Error = 'Query failed!';

                $_SESSION["backPage"] =  removeqsvar($_SERVER['REQUEST_URI'], 'input-comment');
              }
            }
          }
          if ( is_session_started() === FALSE || (!isset($_SESSION['Username']))){
             $isLiked = false;
           }else if(isset($_SESSION['Username'])){
             $isLiked = boolImageLiked($Artist,$_SESSION['Username'],$Title)['Result'];
           }
          }
          else
             echo "<script> window.location.replace('404.php') </script>";
         }
         else
           echo "<script>alert(\'Database problem!\');</script>";
      ?>
      <div class="container1024">
      <h1 id="artworkTitle"><?php echo $Title; ?></h1>
      <div id="imageAndDescription">
      <!--Lense and image-->
        <div id="imageContainer">
          <div class="img-magnifier-glass" id="glass"></div>
          <img id="myimage" src=<?php echo "'Images/Art/".rawurlencode($Artist)."/".rawurlencode($Title).".jpeg'";?> alt=<?php echo '"'.$Title.'"' ?> />
        </div>
      <!--Description-->
          <div id="description-comments">
            <div class="descriptionTitle">Description</div>
            <div class="imageInfo">
              <?php echo '<br/>By: <a href="gallery.php?gallerySearch='.$Artist.'">'.$Artist.'</a><br/>' ?>
              Artist: <?php echo $ArtistName; ?><br/>
              Uploaded on: <?php echo $Date; ?><br/>
              Category: <?php echo $Category; ?><br/>
              Comments: <?php echo $Comments; ?>

              <?php
                if(isset($_SESSION['giveLike']) && ($_SESSION['giveLike'] == 1)){
                  $tmp = giveLike($Artist,$Title);
                  unset($_SESSION['giveLike']);
                }
                if ( is_session_started() === FALSE || (!isset($_SESSION['Username']))){
                  $isLiked = false;
                }else if(isset($_SESSION['Username'])){
                    $isLiked = boolImageLiked($Artist,$_SESSION['Username'],$Title)['Result'];
                }
                $url = $_SERVER['REQUEST_URI'];
                //echo $url;
                $query = parse_url($url, PHP_URL_QUERY);
                $lBL = htmlspecialchars("likedBy.php?artist=$Artist&imgName=$Title");
                if ($query) {
                    $url .= '&lNumI=1';
                } else {
                    $url .= '?lNumI=1';
                }
                echo '<div class="wrapper">';
                echo '<div class="width-15">';
                if($isLiked == true){
                  echo '                <a href="'.htmlspecialchars($url).'" title="give like to '.$Title.' by '.$Artist.'"><span class="like-btn like-btn-added"></span></a>';
                }else{
                    echo '              <a href="'.htmlspecialchars($url).'" title="give like to '.$Title.' by '.$Artist.'"><span class="like-btn"></span></a>';
                }
                echo '  </div>';
                echo '  <div class="width-85">';
                echo '       <p><a class="customLink" href="'.$lBL.'" title="Likes of '.$Title.' by '.$Artist.'">Likes: '.getLikesByItem($Artist,$Title)['Result'].'</a></p>';
                echo '  </div></div>';
              ?>            
              </div>

            <div id="main-description">
              <?php
              if(isset($_SESSION['Username']) && ($_SESSION['Username'] === $Artist || strtolower($_SESSION['Username']) === 'admin'))
              {
                echo "Edit description:<br/>";
                echo "<form action="."\"".htmlspecialchars("viewArtwork.php?Title=".$Title."&Artist=".$Artist)."\" method=\"post\">";
                echo "<div>";
                echo "<textarea rows=\"5\" cols=\"30\" name=\"input-description\">";
                echo $Description;
                echo "</textarea>";
                if($descriptionUpdated){
                  echo '<p class="success_message">Description changed successfully</p>';
                  $descriptionUpdated = false;
                }
                echo "<input type= \"submit\" value=\"Edit\" id=\"description-btn\"/>";
                echo "</div>";
                echo "</form>"; 
              }
              else
                echo $Description; 
              ?>  
            </div>
          </div>
          </div>
          <div id="commentSection" class="container1024">
          <div class="comment" id="topComment">
          <form action=<?php echo "\"".htmlspecialchars("viewArtwork.php?Title=".$Title."&Artist=".$Artist)."\"" ?> method="post">
            <div>
              <input type="hidden" name="UserNotLogged" value="true"/>
              <?php
              if($myDb->connected && isset($_SESSION['Username']))
                  echo $_SESSION['Username'];
                else
                  echo "Login to add a comment.";
                if(!empty($Error))
                  echo ' ('.$Error.') ';
              ?>
              <?php $en = !isset($_SESSION['Username']) ? "disabled=\"disabled\"" : ""; ?>
              <textarea name="input-comment" id="texxt" rows="2" cols="10" <?php echo  $en?>> </textarea>
              <?php
              echo '<input type="submit" value="Send" id="comment-btn"/>';
              ?>
            </div>
          </form>
          </div>
          <?php
              if($myDb->connected)
              {
                $qrStr = 'SELECT * FROM commenti WHERE Opera ="'.$Title.'" ORDER BY ID DESC';
                $result = $myDb->doQuery($qrStr);
                if(isset($result) && ($result->num_rows > 0))
                {
                  while($row = $result->fetch_assoc())
                  {
                    echo '<div class="comment">';
                    if(isset($_SESSION['Username'])){
                      if($row['Utente'] === $_SESSION['Username'] || strtolower($_SESSION['Username']) === 'admin')
                        echo '<div class="delComment"> <a href="'.htmlspecialchars('viewArtwork.php?Remove='.$row['ID'].'&Title='.$Title.'&Artist='.$Artist).'"> x </a></div>';
                    }
                    echo '<a href="gallery.php?gallerySearch='.$row['Utente'].'">'.$row['Utente'].'</a>';
                    echo ' '.$row['Commento']."</div>";
                  }
                }
              }
          ?>
    </div>
  </div>
      <?php require_once "footer.html"?>
    </body>
</html>
