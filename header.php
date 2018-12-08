<div id="skipmenu">
  <a href="#content" onfocus="skipFocused()" onfocusout="skipUnfocused()">Go to content</a>
</div>
<div class="menu" id="Topnav">
<div class="container1024">
  <a class="imageLink" href="index.php"><img src="Images/logo.png" alt="Logo"/></a>
  <ul>
    <?php
      session_start();
      if(isset($_POST['logOut'])){//log out request
        unset($_SESSION['Username']);
      }

      $page = basename($_SERVER['PHP_SELF']);
    ?>
    <li class="firstMenuItem <?php if(($page)=="index.php")echo "activeMenuItem";?>">
      <?php if(($page)!="index.php")echo '<a href="index.php">Home</a>'; else echo '<div class="notClickable">Home</div>';?>
    </li>

    <li class="<?php if(($page)=="gallery.php")echo "activeMenuItem";?>">
      <?php if(($page)!="gallery.php")echo '<a href="gallery.php">Gallery</a>'; else echo '<div class="notClickable">Gallery</div>';?>
    </li>

    <li class="<?php if(($page)=="upload.php")echo "activeMenuItem";?>">
      <?php if(($page)!="upload.php")echo '<a href="upload.php">Upload</a>'; else echo '<div class="notClickable">Upload</div>';?>
    </li>
    
    
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
      <a class="btnSearch" href="#" onclick="openModal('SearchModal')"><span class="searchIcon"></span></a>
    </li>
      <?php //To Edit Profile and logOut
 


        
        if(isset($_SESSION['Username'])){
          echo ' <li class="user account-dropdown">';  
          echo '<a href="#">'.$_SESSION["Username"].'</a>';
          echo '<div class="account-dropdown-content">';
          echo '<a href="#" onclick="openModal("EditProfileModal")">Edit Profile</a>';
          echo '<form  method="post" action="'. $_SERVER["PHP_SELF"].'">';
          echo '<input name="logOut" type="submit" value="LogOut"></input>';
          echo '</form>';
          echo '</div>';
          echo '</li>';
        }
      


      ?>


    <?php 
      if(!isset($_SESSION['Username'])){
        echo '<li class="user">';

        echo '<a href="#" onclick="openModal(\'SignUpModal\')">Sign Up</a>';
        echo '</li>';
      }
    ?>
  </li>


    <?php
      if(!isset($_SESSION['Username']))
        if(($page)=="login.php")
          echo '<li class="user activeMenuItem"><div class="notClickable">Login</div></li>';
      else{
        echo '<li class="user "><a href="login.php">Login</a></li>';

      }
    

    ?>


    <li class="hamburgerMenu">
        <div class="hamburgerMenuContainer" onclick="openDrobDownMenu(this)">
          <div class="line1"></div>
          <div class="line2"></div>
          <div class="line3"></div>
        </div>
    </li>
  </ul>
  </div>
</div>
