<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html, charset=utf-8" />
  <meta name="description" content="Online artwork database"/>
  <meta name="keywords" content="artwork,picture,image,database"/>
  <meta name="author" content="Daniele Bianchin, Pardeep Singh, Davide Liu, Harwinder Singh"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="Style/style.css"/>
  <link rel="stylesheet" href="Style/print.css" type="text/css" media="print" />
  <script type="text/javascript" src="script.js" ></script>
  <title>Artbit</title>
</head>

<body>

  <?php
  require_once "header.php";
  require_once "DbConnector.php";
  require_once "functions.php";

  ?>
    <div class="Uploadsection container1024 fullScreenHeight" id="content"><!--upload form-->
      <h1 class="title">Register your artwork</h1>

      <div  id="uploadMessage" class="upload_message">
          <!--container for unfilled inputs-->
        <?php
        error_reporting(0);
        $title = "";
        $description = "";
        if(isset($_POST["submit"])){
            $title = trim(escapePathTraversal(htmlspecialchars($_POST["title"], ENT_QUOTES, "UTF-8")));
            $category = htmlspecialchars($_POST["category"], ENT_QUOTES, "UTF-8");
            $description = trim(htmlspecialchars($_POST["description"], ENT_QUOTES, "UTF-8"));
            $filename = $_FILES['artwork']['name'];
            $filetmp = $_FILES['artwork']['tmp_name'];
            $filesize = $_FILES['artwork']['size'];
            if(!isset($_SESSION["Username"])){
              echo '<p>You have to sign up before uploading!</p>';
            }
            if(isset($_SESSION["Username"])){
              $username = $_SESSION["Username"];
              $time = date('Y-m-d h:i:s');
              $myDb= new DbConnector();
              $myDb->openDBConnection();
              if(!file_exists("./Images/Art/$username")) //controllo esistenza cartella utente
                mkdir("./Images/Art/$username", 0777, true);
              else if(strlen($title)==0)
                echo '<p>Title field is missing</p>';
              else if(strlen($description)==0)
                echo '<p>Description field is missing</p>';
              else if(strlen($description)>1000)
                echo '<p>Description is too long (Max 1000 characters)</p>';
              else if($filesize>2000000 || $filesize==0) //controllo dimensione immagine
                echo '<p>File size is too big or file not selected</p>';
              else if($myDb->connected){
                //controlla se esiste già un immagine con lo stesso tipo
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
                    unset($_FILES);
                    unset($_POST);
                    $description="";
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
      <form action="" method="post" enctype="multipart/form-data" id="upload">
          <div class="container">
            <label for="title">Title (Max 20 characters):
              <?php if(isset($title) && strlen($title)===0)echo '(MUST BE FILLED)';?>
            </label>
            <input id="title" type="text" name="title" maxlength="20" <?php if(isset($_POST['title']))echo 'value="'.$_POST['title'].'"'?>/>

            <label for="category">Category:</label>
            <select id="category" name="category">
              <option value="landscape" <?php if((isset($_POST['category'])) && $_POST['category']=="landscape") echo "selected=''"?>>Landscape</option>
              <option value="fantasy" <?php if((isset($_POST['category'])) && $_POST['category']=="fantasy") echo "selected=''"?>>Fantasy</option>
              <option value="abstract" <?php if((isset($_POST['category'])) && $_POST['category']=="abstract") echo "selected=''"?>>Abstract</option>
              <option value="cartoon" <?php if((isset($_POST['category'])) && $_POST['category']=="cartoon") echo "selected=''"?>>Cartoon</option>
              <option value="portrait" <?php if((isset($_POST['category'])) && $_POST['category']=="portrait") echo "selected=''"?>>Portrait</option>
              <option value="nature" <?php if((isset($_POST['category'])) && $_POST['category']=="nature") echo "selected=''"?>>Nature</option>
              <option value="others" <?php if((isset($_POST['category'])) && $_POST['category']=="others") echo "selected=''"?>>Others</option>
            </select>

            <label for="description">Description (Max 1000 characters):
              <?php if(isset($description) && strlen($description)===0)echo '(MUST BE FILLED)';?>
            </label>
            <textarea id="description" name="description" rows="2" cols="1" ><?php echo($description)?></textarea>

            <label for="artwork">Artwork (Max 2Mb):</label>
            <input id="artwork" type="file" name="artwork" accept=".png, .jpg, .jpeg" />

            <button type="submit" name="submit">Upload</button>
          </div>
        </form>
      </div>
    <?php require_once "footer.html"?>
</body>
</html>
