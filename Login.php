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
  <title>Artbit Login</title>
</head>

<body  >
  <?php
	require_once "header.php";
	require_once "DbConnector.php";
	require_once "functions.php";
  $myDb= new DbConnector();
  $myDb->openDBConnection();
  ?>
  <!-- The Modal -->
  <div id="LoginModal" class="Modal">
  	<!-- Modal Content -->
  	<form class="modal-content  container1024" method="post" action="/Login.php" onsubmit="return doLogin(event)">
  		<div class="modalHead">
  			<h1>LOGIN FORM</h1>
  		</div>
  		<div class="container">
  			<label for="usr">Username</label>
  			<input id="usr" type="text" placeholder="Enter Username" name="usr" maxlength="20"/>

  			<label for="pwd">Password</label>
  			<input  id="pwd" type="password" placeholder="Enter Password" name="pwd" maxlength="30"/>

  		</div>
  		<div class="container" id="InvalidLogin">
  			<!--container for invalid login message-->

  		</div>

  		<div class="container modalFotter" >
  			<button type="submit">Login</button>
  			<button type="button" onclick="closeModal('LoginModal')" class="cancelbtn">Cancel</button>
  		</div>

  	</form>
  </div>


  <?php require_once "footer.html"?>
</body>
</html>
