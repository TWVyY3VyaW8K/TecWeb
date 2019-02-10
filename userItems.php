<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <meta http-equiv="Content-Type" content="application/xhtml+xml; charset=UTF-8"/>
    <meta name="description" content="Online artwork database"/>
    <meta name="keywords" content="artwork,picture,image,database"/>
    <meta name="author" content="Daniele Bianchin, Pardeep Singh, Davide Liu, Harwinder Singh"/>
    <meta name="title" content="User Items - Artbit"/>
    <meta name="language" content="english en"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1"/>
    <link rel="stylesheet" type="text/css" href="Style/style.css" media="all"/>
    <link rel="stylesheet" type="text/css" href="Style/print.css" media="print" />
    <script type="text/javascript" src="script.js" ></script>
    <link rel="icon" type="image/png" href="Images/logo.png"/>
    <title>User Items - Artbit</title>
</head>

<body onload=" scrollFunction(); scrollToImage();" >
    <?php
        require_once "header.php";
        require_once "DbConnector.php";
        require_once "functions.php";
       // require_once "likedByModal.php";
        //require_once "searchModal.php";

        saveBackPage();
        $pagNumber = galleryPagNumberFromUrl();
        if(isset($_GET['lNumI'])){
            galleryImageNumberFromUrl();
        }
        if(isset($_GET['dNumI'])){
            galleryDeleteImageNumberFromUrl();
        }
    ?>
    <div class="gallery fullScreenHeight container1024">
        <?php require_once "artFilters.php"; ?>
        <?php $mostraPagination=FALSE; $j=0;?>
        <div class="clearfix galleryBoard">
            <?php
                if(isset($_SESSION['Username'])){
                    //connecting to db
                    $myDb= new DbConnector();
                    $myDb->openDBConnection();
                    $orderBy = isset($_GET['orderBy']) ? $_GET['orderBy'] : 'none';
                    $result = array();
                    $qrStr = "";
                    if($myDb->connected){

                        $artistB = (strtolower($_SESSION['Username']) === 'admin');
                        $strToLowArtist = strtolower($_SESSION['Username']);
                        $artQ = $artistB ? "LIKE '%%'" : "='".$strToLowArtist."' ";
                        if(isset($gallerySearch)){
                            if(!isset($galleryCategory) || (isset($galleryCategory) && ($galleryCategory == 'All'))){
                                $qrStr = "SELECT Artista,Nome FROM opere WHERE Artista ".$artQ." AND (Descrizione LIKE '%".$gallerySearch."%' OR Categoria LIKE '%".$gallerySearch."%' OR Nome LIKE '%".$gallerySearch."%')";
                                if($orderBy == 'likes'){
                                    $qrStr = "SELECT Nome, Artista, COUNT(likes.Opera) as Likes FROM opere o LEFT JOIN likes on Nome=Opera and Artista=Creatore
                                        WHERE Artista ".$artQ." AND (o.Descrizione LIKE '%".$gallerySearch."%' OR o.Categoria LIKE '%".$gallerySearch."%' OR Nome LIKE '%".$gallerySearch."%')
                                        GROUP BY o.Nome, o.Artista ORDER BY COUNT(likes.Opera) DESC";
                                }
                                if($orderBy == 'latestAdded'){
                                    $qrStr = "SELECT Artista,Nome FROM opere WHERE Artista ".$artQ." AND (Descrizione LIKE '%".$gallerySearch."%' OR Categoria LIKE '%".$gallerySearch."%' OR Nome LIKE '%".$gallerySearch."%') ORDER BY Data_upload DESC";
                                }
                            }elseif(isset($galleryCategory) && ($galleryCategory != 'All')){
                                $qrStr = 'SELECT Artista,Nome FROM opere WHERE Categoria="'.$galleryCategory.'" AND Artista '.$artQ.' AND (Descrizione LIKE "%'.$gallerySearch.'%" OR Nome LIKE "%'.$gallerySearch.'%")';
                                if($orderBy == 'likes'){
                                    $qrStr = 'SELECT Nome, Artista, COUNT(likes.Opera) as Likes FROM opere LEFT JOIN likes on Nome=Opera and Artista=Creatore
                                            WHERE Categoria="'.$galleryCategory.'" AND Artista '.$artQ.' AND (Descrizione LIKE "%'.$gallerySearch.'%" OR Nome LIKE "%'.$gallerySearch.'%")
                                            GROUP BY Nome, Artista ORDER BY COUNT(likes.Opera) DESC';
                                }
                                if($orderBy == 'latestAdded'){
                                    $qrStr = 'SELECT Artista,Nome FROM opere WHERE Categoria="'.$galleryCategory.'" AND Artista '.$artQ.'  AND (Descrizione LIKE "%'.$gallerySearch.'%" OR Nome LIKE "%'.$gallerySearch.'%") ORDER BY Data_upload DESC';
                                }
                            }
                        }elseif(isset($galleryCategory)){
                            if($galleryCategory == 'All'){
                                $qrStr = "SELECT Artista,Nome FROM opere WHERE Artista ".$artQ.";";
                                if($orderBy == 'likes'){
                                    $qrStr = "SELECT Nome, Artista, COUNT(likes.Opera) as Likes FROM opere LEFT JOIN likes on Nome=Opera and Artista=Creatore WHERE Artista ".$artQ." GROUP BY Nome, Artista ORDER BY COUNT(likes.Opera) DESC";
                                }
                                if($orderBy == 'latestAdded'){
                                    $qrStr = "SELECT Artista,Nome FROM opere WHERE Artista ".$artQ." ORDER BY Data_upload DESC;";
                                }
                            }else{
                                $qrStr = "SELECT Artista,Nome FROM opere WHERE Artista ".$artQ." AND Categoria='".$galleryCategory."'";
                                if($orderBy == 'likes'){
                                    $qrStr = "SELECT Nome, Artista, COUNT(likes.Opera) as Likes FROM opere JOIN likes on Nome=Opera and Artista=Creatore
                                            WHERE Artista ".$artQ." AND Categoria='".$galleryCategory."' GROUP BY Nome, Artista ORDER BY COUNT(likes.Opera) DESC";
                                }
                                if($orderBy == 'latestAdded'){
                                    $qrStr = "SELECT Artista,Nome FROM opere WHERE Artista ".$artQ." Categoria='".$galleryCategory."' ORDER BY Data_upload DESC;";
                                }
                            }
                        }

                        $result = $myDb->doQuery($qrStr);
                        if(isset($_SESSION['deleteImage']) && $result){
                            $row = getImageAtPosition($result, $_SESSION['deleteImage']);
                            $tmp = deleteItem($row['Artista'],$row['Nome']);
                            unset($_SESSION['deleteImage']);
                            $result = $myDb->doQuery($qrStr);
                        }
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

        </div>
        <?php
            printPagination($mostraPagination,$j,$_SESSION['pagNum'.ucfirst(pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME))],basename($_SERVER['PHP_SELF']));
        ?>
    </div>
    <?php require_once "footer.html"?>
</body>
</html>
