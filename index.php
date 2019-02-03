<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html, charset=utf-8" />
  <meta name="description" content="Online artwork database"/>
  <meta name="keywords" content="artwork,picture,image,database"/>
  <meta name="author" content="Daniele Bianchin, Pardeep Singh, Davide Liu, Harwinder Singh"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="icon" href="Images/logo.png"/>
  <link rel="stylesheet" href="Style/style.css"/>
  <link rel="stylesheet" href="Style/print.css" type="text/css" media="print" />
  <script type="text/javascript" src="script.js" ></script>
  <title>Artbit</title>
</head>

<body onload="scrollFunction(); scrollToImage();" >
  <?php
	require_once "header.php";
	require_once "DbConnector.php";
	require_once "functions.php";

	$myDb= new DbConnector();
	$myDb->openDBConnection();

	$pagNumber = galleryPagNumberFromUrl();
	saveBackPage();
	if(isset($_GET['lNumI'])){
		galleryImageNumberFromUrl();
	}
  ?>
  <div class="description"><!--general description-->
      <div class="overlay font_medium">
        <p>
          Everything around us is the result of the unlimited combinations of colors we
          have been given by our Universe. We not only paint them in order to express our creativity but also to
          trasmit our emotions and feelings so we can say that art represents human being essence.
        </p>
    </div>
  </div>
  <div class="container1024" id="content">
    <div class="section" id="intro"><!--website Introduction-->
		<h1 class="title">Introduction</h1>
		<p>
		  Innovation is the key to the future, let the art invade the digital world.
		  This website is meant to be collection of digital artworks, everyone can
		  register and upload his own masterpieces sharing them with the world and
		  <br/>get popularity.
		</p>
	  </div>

		<!-- Top rated -->
		<h2 class="title">Top rated</h2>
		<div class="gallery galNotThree">
			<div class="clearfix galleryBoard">
				<?php
					$qrStr = getQueryForTopRatedImages();
 					$result = $myDb->doQuery($qrStr);
					if(isset($_SESSION['giveLike']) && $result){
						$row = getImageAtPosition($result, $_SESSION['giveLike']);
						$tmp = giveLike($row['Artista'],$row['Nome']);
						unset($_SESSION['giveLike']);
						$result = $myDb->doQuery($qrStr);
					}

					if($result && ($result->num_rows > 0)){
						$j = printGalleryItems($result,FALSE,$_SESSION['pagNum'.ucfirst(pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME))]);
					}elseif(!$result || ($result->num_rows == 0)){
						echo "<div class='div-center'><p>Nothing to show here ... </p></div>";
					}
				?>
			</div>
		</div>
		<p class ="galleryRedirect">
		  <a href="gallery.php" class="btnLink linkAsButton">Explore all the art works in Gallery !! </a> 
		</p>

	  <div class="section"><!--statistics-->
		<div class="statistics">
		  <?php
		  $result = $myDb->doQuery("SELECT COUNT(*) as tot_opere FROM opere");
		  $row = $result->fetch_assoc();
		  $tot_opere = $row["tot_opere"];
		  $result = $myDb->doQuery("SELECT COUNT(Username) as tot_artisti FROM artisti");
		  $row = $result->fetch_assoc();
		  $tot_artisti = $row["tot_artisti"];
		  $result = $myDb->doQuery("SELECT COUNT(*) as tot_likes FROM likes");
		  $row = $result->fetch_assoc();
		  $tot_likes = $row["tot_likes"];
		  ?>
      <table  summary="In the table you can find some statistics of our website as Registered artworks, Registered painters and Total Likes">
       	  <caption>Statistics</caption>
		  <tr><td>Registered artworks</td><td> <?php echo $tot_opere ?></td></tr>
		  <tr><td>Registered painters</td><td> <?php echo $tot_artisti ?></td></tr>
		  <tr><td>Total likes</td><td> <?php echo $tot_likes ?></td></tr>
      </table>
		</div>
		<div class="semicolumn"><!--top artists-->
		  <?php
		  $result = $myDb->doQuery("SELECT Username, COUNT(Username) as tot_arts FROM artisti JOIN opere on Username=Artista
								   GROUP BY (Username) ORDER BY COUNT(Username) DESC LIMIT 5");
		  $nome=array($result->num_rows);
		  $score=array($result->num_rows);
      echo '<div class="statistics"><table  summary="In this table you can find the most active users on our website">
            <caption>Most Artworks</caption>
              <tr>
                <th scope="col">Rank</th>
                <th scope="col" >Username</th>
                <th scope="col" >Artworks</th>
              </tr>';
		  for ($i = 0; $i < $result->num_rows; $i++) {
  			$row = $result->fetch_assoc();
  			$nome[$i] = $row["Username"];
  			$score[$i] = $row["tot_arts"];
  			echo '<tr><td scope="row">'.($i+1).')</td>
              <td>'.$nome[$i].'</td>
              <td>'.$score[$i].'</td></tr>';
		  }
      echo "</table></div>";
		  ?>
		</div>
		<div class="semicolumn"><!--top artists-->
		  <!--<div class="subtitle"><h2>Most Liked</h2></div>-->
		  <?php
		  $result = $myDb->doQuery("SELECT Username, COUNT(Username) as tot_likes FROM artisti JOIN likes on Username=Creatore
								   GROUP BY (Username) ORDER BY COUNT(Username) DESC LIMIT 5");
		  $nome=array($result->num_rows);
		  $score=array($result->num_rows);
      echo '<div class="statistics"><table summary="This tabel containes the top 5 creators">
            <caption>Most Liked</caption>
              <tr>
                <th scope="col">Rank</th>
                <th scope="col">Username</th>
                <th scope="col">Likes</th>
              </tr>';
		  for ($i = 0; $i < $result->num_rows; $i++) {
  			$row = $result->fetch_assoc();
  			$nome[$i] = $row["Username"];
  			$score[$i] = $row["tot_likes"];
        echo '<tr><td scope="row" >'.($i+1).")</td>
              <td>$nome[$i]</td>
              <td>$score[$i]</td></tr>";
        }
      echo "</table></div>";
		  $myDb->disconnect();
		  ?>
		</div>
	  </div>
	  <div class="section" id="team"><!--team-->
		<h3 class="title">Our Amazing Team</h3>
		<div class="teamMember">
		  <img src="Images/Team/davide_liu.jpg" alt='Team member face'/>
		  <div class="memberName">Davide<br/>Liu</div>
		  <hr/>
		  <p>Web Grandmaster</p>
		</div>
		<div class="teamMember">
		  <img src="Images/Team/harwinder_singh.jpg" alt='Team member face'/>
		  <div class="memberName">Harwinder<br/>Singh</div>
		  <hr/>
		  <p>Software Engineer</p>
		</div>
		<div class="teamMember">
		  <img src="Images/Team/pardeep_singh.jpg" alt='Team member face'/>
		  <div class="memberName">Pardeep<br/>Singh</div>
		  <hr/>
		  <p>Full Stack Developer</p>
		</div>
		<div class="teamMember">
		  <img src="Images/Team/daniele_bianchin.jpg" alt='Team member face'/>
		  <div class="memberName">Daniele<br/>Bianchin</div>
		  <hr/>
		  <p>Cybersecurity Expert</p>
		</div>
	  </div>
  </div>
  <?php require_once "footer.html"?>
</body>
</html>
