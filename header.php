<div id="skipmenu">
  <a href="#content" onfocus="skipFocused()" onfocusout="skipUnfocused()">Go to content</a>
</div>
<div class="menu" id="Topnav">
<div class="container1024">
  <a class="imageLink" href="index.php"><img src="Images/logo.png" alt="Logo"/></a>
  <ul>
    <?php
      session_start();
      $page = basename($_SERVER['PHP_SELF']);
    ?>
    <li class="firstMenuItem <?php if(($page)=="index.php")echo "activeMenuItem";?>"><?php if(($page)!="index.php")echo '<a href="index.php">Home</a>'; else echo '<div class="notClickable">Home</div>';?></li>
    <li class="<?php if(($page)=="gallery.php")echo "activeMenuItem";?>"><?php if(($page)!="gallery.php")echo '<a href="gallery.php">Gallery</a>'; else echo '<div class="notClickable">Gallery</div>';?></li>
    <li class="<?php if(($page)=="upload.php")echo "activeMenuItem";?>"><?php if(($page)!="upload.php")echo '<a href="upload.php">Upload</a>'; else echo '<div class="notClickable">Upload</div>';?></li>
    <li class="<?php if(($page)=="likedItems.php")echo "activeMenuItem";?>">
      <?php
        //Se l'utente è loggato allora può vedere i suoi preferiti
        if(isset($_SESSION['Username'])){
          if(($page)!="likedItems.php")
            echo '<a href="likedItems.php">Liked Images</a>';
          else
            echo '<div class="notClickable">Liked Images</div>';
        }
      ?>
    </li>
    <li class="<?php if(($page)=="userItems.php")echo "activeMenuItem";?>">
      <?php
        //Se l'utente è loggato allora può vedere i suoi preferiti
        if(isset($_SESSION['Username'])){
          if(($page)!="userItems.php")
            echo '<a href="userItems.php">Your Images</a>';
          else
            echo '<div class="notClickable">Your Images</div>';
        }
      ?>
    </li>
    <li><a href="index.php#team">Team</a></li>
    <li>
      <a class="btnSearch" href="#" onclick="openModal('SearchModal')"><span class="searchIcon"></span></a>
    </li>
    <li class="user account-dropdown">
      <?php //To Sign in or edit profile of User
        if(isset($_SESSION['Username'])){
          echo '<a href="#">'.$_SESSION["Username"].'</a>';
          echo '<div class="account-dropdown-content">
                  <a href="#" onclick="openModal(\'EditProfileModal\')">Edit Profile</a>
                  <a href="#" onclick="doLogOut()">Logout</a>
                </div>';
        }
      ?>
  </li>
  <li class="user account-content">
    <?php //To Sign in or edit profile of User
      if(isset($_SESSION['Username']))
        //echo '<a href="#" onclick="openEditProfileModal()">Edit Profile: '.$_SESSION["Username"].'</a>';
        echo '<a href="#" onclick="openModal(\'EditProfileModal\')">Edit Profile: '.$_SESSION["Username"].'</a>';
    ?>
  </li>
  <li class="user">
    <?php //To Sign in or edit profile of User
      if(!isset($_SESSION['Username']))
        //echo '<a href="#" onclick="openSignUpModal()" >Sign Up</a>';
        echo '<a href="#" onclick="openModal(\'SignUpModal\')">Sign Up</a>';
    ?>
  </li>
  <li class="user">
    <?php
      if(!isset($_SESSION['Username']))
        //echo '<a href="#" onclick="openLoginModal()">Login</a>';
        echo '<a href="#" onclick="openModal(\'LoginModal\')">Login</a>';
    ?>
   </li>
   <li class="user account-content">
    <?php
      if(isset($_SESSION['Username']))
        echo '<a href="#" onclick="doLogOut()" >Logout</a>';
    ?>
    </li>
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
