<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <meta http-equiv="Content-Type" content="application/xhtml; charset=UTF-8"/>
    <meta name="description" content="Online artwork database"/>
    <meta name="keywords" content="artwork,picture,image,database"/>
    <meta name="author" content="Daniele Bianchin, Pardeep Singh, Davide Liu, Harwinder Singh"/>
    <meta name="title" content="Liked Items - Artbit"/>
    <meta name="language" content="english en"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1"/>
    <link rel="stylesheet" type="text/css" href="Style/style.css" media="handheld, screen"/>
    <link rel="stylesheet" type="text/css" href="Style/print.css" media="print" />
    <script type="text/javascript" src="script.js" ></script>
    <link rel="icon" type="image/png" href="Images/logo.png"/>
    <title>Liked Items - Artbit</title>
</head>

<body onload=" scrollFunction(); scrollToImage();" >
    <?php
        require_once "header.php";
        require_once "DbConnector.php";
        require_once "functions.php";

        saveBackPage();
        $pagNumber = galleryPagNumberFromUrl();
        if(isset($_GET['lNumI'])){
            galleryImageNumberFromUrl();
        }
    ?>
    <div class="gallery container1024 fullScreenHeight" id="content">
        <?php $mostraPagination=FALSE; $j=0;?>
        <div class="clearfix galleryBoard">
            <?php
                if(isset($_SESSION['Username'])){
                    //connecting to db
                    $myDb= new DbConnector();
                    $myDb->openDBConnection();
                    $result = array();
                    if($myDb->connected){
                        $qrStr = getQueryForLikedImages();
                        $result = $myDb->doQuery($qrStr);
                        //removing like from a specific image
                        if(isset($_SESSION['giveLike']) && $result){
                            $row = getImageAtPosition($result, $_SESSION['giveLike']);
                            $tmp = giveLike($row['Artista'],$row['Nome']);
                            unset($_SESSION['giveLike']);
                            $result = $myDb->doQuery($qrStr);
                        }
                    }
                    else 
                        echo "<li class='liPaginationBlock'>Errore connessione</li>";
                    $myDb->disconnect();

                    if($result && ($result->num_rows > 0)){
                        $mostraPagination = ($result->num_rows > $GLOBALS['imagesPerPage']) ? true : false;
                        $j = printGalleryItems($result,FALSE,$_SESSION['pagNum'.ucfirst(pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME))]);
                    }elseif(!$result || ($result->num_rows == 0)){
                        echo "<div class='liPaginationBlock'><div class='div-center'><p>Nothing to show here ... </p></div></div>";
                    }
                }
            ?>
            
            </div> 
        <?php
            printPagination($mostraPagination,$j,$_SESSION['pagNum'.ucfirst(pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME))],basename($_SERVER['PHP_SELF']));
        ?>
    </div>
    <?php require_once "footer.html"?>
</body>
</html>