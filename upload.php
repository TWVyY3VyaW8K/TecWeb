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

<body>

  <?php
  require_once "header.php";
  require_once "DbConnector.php";
  require_once "functions.php";

  unset($_SESSION['backPageRedirect']);
  //$_SESSION['backPageRedirect'] = $_SERVER['REQUEST_URI'];
  ?>
    <div class="Uploadsection container1024" id="content"><!--upload form-->
      <div class="title"><h1>Register your artwork</h1></div>

      <div  id="uploadMessage" class="upload_message">
          <!--container for unfilled inputs-->
        <?php
        error_reporting(0);
        $title = trim(escapePathTraversal(htmlspecialchars($_GET["title"], ENT_QUOTES, "UTF-8")));
        $category = htmlspecialchars($_GET["category"], ENT_QUOTES, "UTF-8");
        $description = trim(htmlspecialchars($_GET["description"], ENT_QUOTES, "UTF-8"));
        if(isset($_GET["title"]) || isset($_GET["description"]) || isset($_FILES['artwork'])){
            $filename = $_FILES['artwork']['name'];
            $filetmp = $_FILES['artwork']['tmp_name'];
            $filesize = $_FILES['artwork']['size'];
            var_dump($_FILES['artwork']);
            if(!isset($_SESSION["Username"])){
              header("location: login.php");
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
              else if($filesize>5242880*4) //controllo dimensione immagine
                echo '<p>File size is too big (max 20Mb)</p>';
              else if($filesize==0) //controllo se c'è l'immagine
                echo '<p>Please select an image to upload</p>';
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
                    unset($_POST);
                    unset($description);
                    unset($title);
                    unset($category);
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
      <form action="" method="get" enctype="multipart/form-data" id="upload">
          <div class="container">
            <label for="title">Title (Max 20 characters):
              <?php if(isset($title) && strlen($title)===0)echo '(MUST BE FILLED)';?>
            </label>
            <input id="title" type="text" name="title" maxlength="20" <?php if(isset($_GET['title']))echo 'value="'.$_GET['title'].'"'?>/>

            <label for="category">Category:</label>
            <select id="category" name="category">
              <option value="landscape" <?php if((isset($_GET['category'])) && $_GET['category']=="landscape") echo "selected=''"?>>Landscape</option>
              <option value="fantasy" <?php if((isset($_GET['category'])) && $_GET['category']=="fantasy") echo "selected=''"?>>Fantasy</option>
              <option value="abstract" <?php if((isset($_GET['category'])) && $_GET['category']=="abstract") echo "selected=''"?>>Abstract</option>
              <option value="cartoon" <?php if((isset($_GET['category'])) && $_GET['category']=="cartoon") echo "selected=''"?>>Cartoon</option>
              <option value="portrait" <?php if((isset($_GET['category'])) && $_GET['category']=="portrait") echo "selected=''"?>>Portrait</option>
              <option value="nature" <?php if((isset($_GET['category'])) && $_GET['category']=="nature") echo "selected=''"?>>Nature</option>
              <option value="others" <?php if((isset($_GET['category'])) && $_GET['category']=="others") echo "selected=''"?>>Others</option>
            </select>

            <label for="description">Description (Max 1000 characters):
              <?php if(isset($description) && strlen($description)===0)echo '(MUST BE FILLED)';?>
            </label>
            <textarea id="description" name="description" rows="2" cols="1" ><?php echo($description)?></textarea>

            <label for="artwork">Artwork (Max 20Mb):</label>
            <input id="artwork" type="file" name="artwork" accept=".png, .jpg, .jpeg" />

            <button type="submit">Upload</button>
          </div>
        </form>
      </div>
    <?php require_once "footer.html"?>
</body>
</html>
