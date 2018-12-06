<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html, charset=utf-8" />
    <meta name="description" content="Online artwork database"/>
    <meta name="keywords" content="artwork,picture,image,database"/>
    <meta name="author" content="Daniele Bianchin, Pardeep Singh, Davide Liu, Harwinder Singh"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" media="handheld, screen" href="Style/style.css"/>
    <link rel="stylesheet" href="Style/print-style.css" type="text/css" media="print" />
    <script type="text/javascript" src="script.js" ></script>
    <title>Artbit</title>
</head>

<body onload="eventListnerforLoginModal(); initializePagination(); scrollFunction();" >
    <?php
        require_once "header.php";
        require_once "loginModal.php";
        require_once "signUpModal.php";
        require_once "editProfileModal.php";
        require_once "DbConnector.php";
        require_once "functions.php";
        require_once "searchModal.php";
        require_once "likedByModal.php";
    ?>
    <div id="imgLoader" class="image-loader display-none">
        <img src="/Images/eclipse.svg">
    </div>
    <div class="gallery container1024" id="content">
        <?php $mostraPagination=FALSE; $j=0;?>
        <ul class="clearfix galleryBoard">
            <?php
                if(isset($_SESSION['Username'])){
                    //connecting to db
                    $myDb= new DbConnector();
                    $myDb->openDBConnection();
                    $result = array();
                    if($myDb->connected){
                        $qrStr = "SELECT Nome, Artista FROM opere o LEFT JOIN likes on Nome=Opera and Artista=Creatore
                                    WHERE likes.Utente='".$_SESSION['Username']."'
                                    GROUP BY o.Nome, o.Artista ORDER BY COUNT(Nome) DESC";
                        $result = $myDb->doQuery($qrStr);
                    }
                    else 
                        echo "<li class='liPaginationBlock'>Errore connessione</li>";
                    $myDb->disconnect();

                    if($result && ($result->num_rows > 0)){
                        $mostraPagination = ($result->num_rows <= $GLOBALS['imagesPerPage']) ? false : true;
                        $j = printGalleryItems($result,FALSE);
                    }elseif(!$result || ($result->num_rows == 0)){
                        echo "<li class='liPaginationBlock'><div class='div-center'><p>Nothing to show here ... </p></div></li>";
                    }
                }
            ?>
            
        </ul> 
        <?php
            printPagination($mostraPagination,$j);
        ?>
    </div>
    <?php require_once "footer.html"?>
</body>
</html>