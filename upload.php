<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html, charset=utf-8" />
  <meta name="description" content="Online artwork database"/>
  <meta name="keywords" content="artwork,picture,image,database"/>
  <meta name="author" content="Daniele Bianchin, Pardeep Singh, Davide Liu, Harwinder Singh"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<link rel="stylesheet" href="Style/style.css"/>
  <script type="text/javascript" src="script.js" ></script>
  <title>Artbit</title>
</head>

<body onload="eventListnerforLoginModal(); scrollFunction();">

  <?php
  require_once "header.php";
  require_once "loginModal.php";
  require_once "searchModal.php";
  require_once "signUpModal.php";
  require_once "editProfileModal.php";
  require_once "DbConnector.php";
  require_once "functions.php";
?>
    <div class="Uploadsection container1024" id="content"><!--upload form-->
      <div class="title"><h1>Register your artwork</h1></div>

      <div  id="uploadMessage" class="upload_message">
          <!--container for unfilled inputs-->
                    <?php
          error_reporting(0);
          if(isset($_POST["title"]) && isset($_FILES['artwork'])){ //l'upload può partire solo se il TITOLO e l'IMMAGINE sono stati selezionati
            if(!isset($_SESSION["Username"])){
              echo '<script type="text/javascript">openModal(\'LoginModal\')</script>';
              //exit();
            }
            if(isset($_SESSION["Username"])){
              $title = escapePathTraversal(htmlspecialchars($_POST["title"], ENT_QUOTES, "UTF-8"));
              $category = htmlspecialchars($_POST["category"], ENT_QUOTES, "UTF-8");
              $description = htmlspecialchars($_POST["description"], ENT_QUOTES, "UTF-8");
              $filename = $_FILES['artwork']['name'];
              $filetmp = $_FILES['artwork']['tmp_name'];
              $filesize = $_FILES['artwork']['size'];
              $username = $_SESSION["Username"];
              $time = date('Y-m-d h:i:s');
              //connecting to db
              $myDb= new DbConnector();
              $myDb->openDBConnection();
              if(!file_exists("./Images/Art/$username"))
                mkdir("./Images/Art/$username", 0777, true);
              if($filesize>5242880*4 || $filesize==0)
                echo '<p>File size is too big (max 20Mb)</p>';
              else if($myDb->connected){
                //check if title already exists
                $result = $myDb->doQuery("select Nome from opere where Nome='".$title."' and Artista='".$username."'");
                if($result->num_rows>0)
                  echo '<p>You have already uploaded an artwork with this name</p>';
                else{
                  //store compressed image
                  $destination_img = "Images/Art/".$username."/".$title.".jpeg";
                  if(compress($filetmp, $destination_img, 80)===true){
                    //update database
                    $result = $myDb->doQuery("insert into opere values ('$title','$description','$time','$username','$category')");
                    echo '<p id="success_message" class="success_message">Update successfully</p>';
                  }
                  else {
                    echo '<p>Selected file is not an image</p>';
                  }
                }
                $myDb->disconnect();
              }
              else
                echo '<p>Connection error</p>';
            }
          }
          function compress($source, $destination, $quality) {

              try{
                if(!$info = getimagesize($source))
                  throw new Exception();
              }
              catch(Exception $e) {
                return false;
              }

              if ($info['mime'] == 'image/jpeg')
                  $image = imagecreatefromjpeg($source);

              elseif ($info['mime'] == 'image/jpg')
                  $image = imagecreatefromgif($source);

              elseif ($info['mime'] == 'image/png')
                  $image = imagecreatefrompng($source);
              else
                  return false;
              imagejpeg($image, $destination, $quality);
              return true;
          }
        ?>
      </div>
      <form action="" method="post" enctype="multipart/form-data" id="upload" onsubmit="return doUploadValidation(event)">
          <div class="container">
          <label for="title">Title (Max 20 characters):</label>
          <input id="title" type="text" placeholder="Title" name="title" maxlength="20" />

          <label for="category">Category:</label>
          <select id="category" name="category">
            <option value="landscape">Landscape</option>
            <option value="fantasy">Fantasy</option>
            <option value="abstract">Abstract</option>
            <option value="cartoon">Cartoon</option>
            <option value="portrait">Portrait</option>
            <option value="nature">Nature</option>
            <option value="others">Others</option>
          </select>

          <label for="description">Description (max 1000 characters):</label>
          <textarea id="description" placeholder="Description" name="description" rows="2" cols="1"></textarea>

          <label for="artwork">Artwork:</label>
          <input id="artwork" type="file" name="artwork" accept=".png, .jpg, .jpeg" />

          <button type="submit">Upload</button>
          </div>
        </form>
      </div>
    <?php require_once "footer.html"?>
</body>
</html>
