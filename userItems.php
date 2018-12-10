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
        require_once "DbConnector.php";
        require_once "functions.php";
       // require_once "likedByModal.php";
        //require_once "searchModal.php";

        if(isset($_GET['pagNum'])){
            $pagNumber = (intval(htmlspecialchars($_GET['pagNum'], ENT_QUOTES, "UTF-8")) >= 1) ? intval(htmlspecialchars($_GET['pagNum'], ENT_QUOTES, "UTF-8")) : 1;   
            $_SESSION['pagNum'.ucfirst(pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME))] = $pagNumber;
        }elseif(!isset($_SESSION['pagNum'.ucfirst(pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME))])){
            resetSessionPaginationNum('pagNum'.ucfirst(pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME)));
        }
        if(isset($_GET['lNumI'])){
            $numI = (intval(htmlspecialchars($_GET['lNumI'], ENT_QUOTES, "UTF-8")) >= 1) ? intval(htmlspecialchars($_GET['lNumI'], ENT_QUOTES, "UTF-8")) : 0;
            if($numI != 0){
                $_SESSION['giveLike'] = $numI;
            }
            $_SERVER['REQUEST_URI'] = removeqsvar($_SERVER['REQUEST_URI'], 'lNumI');
            //$_SERVER['REQUEST_URI'] = modifyGetParameterInURI($_GET,'lNumI');
            
            unset($_GET['lNumI']);
        }
        if(isset($_GET['dNumI'])){
            $numI = (intval(htmlspecialchars($_GET['dNumI'], ENT_QUOTES, "UTF-8")) >= 1) ? intval(htmlspecialchars($_GET['dNumI'], ENT_QUOTES, "UTF-8")) : 0;
            if($numI != 0){
                $_SESSION['deleteImage'] = $numI;
            }
            $_SERVER['REQUEST_URI'] = removeqsvar($_SERVER['REQUEST_URI'], 'dNumI');
            //$_SERVER['REQUEST_URI'] = modifyGetParameterInURI($_GET,'lNumI');
            
            unset($_GET['dNumI']);
        }
    ?>
    <div id="imgLoader" class="image-loader display-none">
        <img src="/Images/eclipse.svg">
    </div>
    <div class="gallery fullScreenHeight container1024" id="content">
        <?php $mostraPagination=FALSE; $j=0;?>
        <ul class="clearfix galleryBoard">
            <?php
                if(isset($_SESSION['Username'])){
                    //connecting to db
                    $myDb= new DbConnector();
                    $myDb->openDBConnection();
                    $result = array();
                    $qrStr = "";
                    if($myDb->connected){
                        if(strtolower($_SESSION['Username']) === 'admin'){
                            $qrStr = "SELECT Nome,Artista FROM opere";
                        }else{
                            $qrStr = "SELECT Nome,Artista FROM opere WHERE Artista='".strtolower($_SESSION['Username'])."'";
                        }
                        $result = $myDb->doQuery($qrStr);

                        $myDb->disconnect();

                        if($result && ($result->num_rows > 0)){
                            $mostraPagination = ($result->num_rows > $GLOBALS['imagesPerPage']) ? true : false;
                            $j = printGalleryItems($result,TRUE,$_SESSION['pagNum'.ucfirst(pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME))]);
                        }elseif(!$result || ($result->num_rows == 0)){
                            echo "<div class='liPaginationBlock'><div class='div-center'><p>Nothing to show here ... </p></div></div>";
                        }
                    }else{
                        echo "<li class='liPaginationBlock'>Errore connessione</li>";
                    }
                }
            ?>

        </ul>
        <?php
            printPagination($mostraPagination,$j,$_SESSION['pagNum'.ucfirst(pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME))],basename($_SERVER['PHP_SELF']));
        ?>
    </div>
    <?php require_once "footer.html"?>
</body>
</html>