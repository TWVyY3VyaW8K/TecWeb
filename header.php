<div id="skipmenu">
  <a href="#content" onfocus="skipFocused()" onfocusout="skipUnfocused()">Go to content</a>
</div>
<?php
  if(isset($_POST['menuDropDown'])){
    if($_POST['menuDropDown'] == 'true')
      echo '<div class="menu" id="Topnav">';
    else if($_POST['menuDropDown'] == 'false')
      echo '<div class="menu responsive" id="Topnav">';
  }else{
    echo '<div class="menu" id="Topnav">';
  }
?>

<!-- <div class="menu" id="Topnav"> -->
<div class="container1024">
  <a class="imageLink" href="index.php"><img src="Images/logo.png" alt="Logo"/></a>
  <ul>
    <?php
      session_start();
      if(isset($_POST['logOut'])){//log out request
        unset($_SESSION['Username']);
        if(strstr($_SERVER['HTTP_REFERER'], "upload.php") || strstr($_SERVER['HTTP_REFERER'], "editProfile.php") || strstr($_SERVER['HTTP_REFERER'], "likedItems.php") ||strstr($_SERVER['HTTP_REFERER'], "userItems.php")  )
        {
          header("location: /dliu/TecWeb/index.php");
					die();
        }


      }

      $page = basename($_SERVER['PHP_SELF']);
    ?>
    <li class="firstMenuItem <?php if(($page)=="index.php")echo "activeMenuItem";?>">
      <?php if(($page)!="index.php")echo '<a href="index.php">Home</a>'; else echo '<div class="notClickable">Home</div>';?>
    </li>

    <li class="<?php if(($page)=="gallery.php")echo "activeMenuItem";?>">
      <?php if(($page)!="gallery.php")echo '<a href="gallery.php">Gallery</a>'; else echo '<div class="notClickable">Gallery</div>';?>
    </li>

    <?php
      if(isset($_SESSION['Username'])){
        echo '<li class="<?php if(($page)=="upload.php")echo "activeMenuItem";">';
        if(($page)!="upload.php")echo '<a href="upload.php">Upload</a>';
        else echo '<div class="notClickable">Upload</div>';
        echo '</li>';
      }
    ?>


    <?php
      //Se l'utente è loggato allora può vedere i suoi preferiti
      if(isset($_SESSION['Username'])){
        echo ' <li class="<?php if(($page)=="likedItems.php")echo "activeMenuItem";">';
        if(($page)!="likedItems.php")
          echo '<a href="likedItems.php">Liked Images</a>';
        else
          echo '<div class="notClickable">Liked Images</div>';

        echo '</li>';
      }
    ?>


    <?php
      //Se l'utente è loggato allora può vedere le sue immagini
      if(isset($_SESSION['Username'])){
        echo ' <li class="<?php if(($page)=="likedItems.php")echo "activeMenuItem";">';
        if(($page)!="userItems.php")
          echo '<a href="userItems.php">Your Images</a>';
        else
          echo '<div class="notClickable">Your Images</div>';
        echo '</li>';

      }
    ?>


    <li><a href="index.php#team">Team</a></li>
    <li>
      <form method="get" action="gallery.php">
        <div class="inputSearch">
            <input type="text" name="gallerySearch" />
            <button class="btnSearch" type="submit"><span class="searchIcon"></span></button>
        </div>
      </form>
    </li>
      <?php //To Edit Profile and logOut




        if(isset($_SESSION['Username'])){
          echo ' <li class="user account-dropdown">';
          echo '<a href="#">'.$_SESSION["Username"].'</a>';
          echo '<div class="account-dropdown-content">';
          echo '<a href="editProfile.php">Edit Profile</a>';
          echo '<form  method="post" action="'.$_SERVER['REQUEST_URI'].'">';
          echo '<div><input name="logOut" type="submit" value="LogOut"></input></div>';
          echo '</form>';
          echo '</div>';
          echo '</li>';
        }
      ?>


    <?php
      if(!isset($_SESSION['Username'])){




      if(($page)=="signUp.php")
        echo '<li class="user activeMenuItem"><div class="notClickable">Sign Up</div></li>';
      else{
        echo '<li class="user "><a href="signUp.php">Sign Up</a></li>';
      }
    }
    ?>

    <?php
      if(!isset($_SESSION['Username']))
        if(strtolower($page)=="login.php")
          echo '<li class="user activeMenuItem"><div class="notClickable">Login</div></li>';
      else{
        echo '<li class="user "><a href="login.php">Login</a></li>';
      }
    ?>
  <!--<div class="hamburgerMenu">
    <button type="submit">Menù</button>
  </div>-->
  <li class="hamburgerMenu">
    <?php
      echo '<form  method="post" action="'.$_SERVER['REQUEST_URI'].'"><div>';
      if(isset($_POST['menuDropDown'])){
        if($_POST['menuDropDown'] == 'true')
          echo '<input name="menuDropDown" type="hidden" value="false"/>';
        else if($_POST['menuDropDown'] == 'false')
          echo '<input name="menuDropDown" type="hidden" value="true"/>';
      }else{
        echo '<input name="menuDropDown" type="hidden" value="false"/>';
      }
      echo '<input type="submit" style="position: absolute;" value=""></input>';
      echo '</div></form>';
    ?>
        <div class="hamburgerMenuContainer">
          <div class="line1"></div>
          <div class="line2"></div>
          <div class="line3"></div>
        </div>
    </li>
  </ul>
  </div>
</div>

<div class=" breadcrumb">
  <div class="container1024">
      <?php
      echo "You are on: ";
      if($page=="index.php")
          echo "HOME";
      if($page=="gallery.php")
          echo "GALLERY";
      if($page=="upload.php")
          echo "UPLOAD";
      if($page=="likedItems.php")
          echo "LIKED ITEMS";
      if($page=="userItems.php")
          echo "USER ITEMS";
      if($page=="signUp.php")
          echo "SIGN UP";
      if($page=="resetPassword.php")
          echo "RESET PASSWORD";
      if(strtolower($page)=="login.php")
          echo "LOGIN";
      if($page=="editProfile.php")
          echo "EDIT PROFILE";
      if($page=="viewArtwork.php")
          echo '<a href="'.$_SERVER['HTTP_REFERER'].'">GALLERY</a> >> '.$_GET['Title'];
    ?>
  </div>

</div>
