<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <meta http-equiv="Content-Type" content="application/xhtml+xml; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1"/>
    <meta name="description" content="Online artwork database"/>
    <meta name="keywords" content="artwork,picture,image,database"/>
    <meta name="author" content="Daniele Bianchin, Pardeep Singh, Davide Liu, Harwinder Singh"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="Style/style.css" media="handheld, screen"/>
    <link rel="stylesheet" href="Style/print-style.css" type="text/css" media="print" />
    <script type="text/javascript" src="script.js" ></script>
    <title>Artbit</title>
</head>

<body onload="eventListnerforLoginModal(); initializePagination(); scrollFunction();" >
    <?php
        require_once "header.php";
        require_once "loginModal.php";
        require_once "searchModal.php";
        require_once "likedByModal.php";
        require_once "signUpModal.php";
        require_once "editProfileModal.php";
        require_once "DbConnector.php";
        require_once "functions.php";
    ?>
    <div class="gallery container1024" id="content">
        <form method="get" action="" name="formArtFilter">
            <div class="artFilter">
                <div class="inputSearch">
                    <?php
                        if(isset($_GET['gallerySearch'])){
                            $gallerySearch = htmlspecialchars($_GET["gallerySearch"], ENT_QUOTES, "UTF-8");//cleaning the input
                            echo '<input type="text" placeholder="Cerca per categoria, artista o descrizione .." name="gallerySearch" value="'.$gallerySearch.'"/>';
                        }else{
                            echo '<input type="text" placeholder="Cerca per categoria, artista o descrizione .." name="gallerySearch"/>';
                        }

                    ?>
                    <button class="btnSearch" type="submit" onclick="deleteCookie('divPagNumber')"><span class="searchIcon"></span></button>
                </div>
                <div class="divCategoryFilter">
                    <p>Categories</p>

                    <?php
                        if(!isset($_SESSION['galleryCategory'])){
                            $_SESSION['galleryCategory'] = $galleryCategory = 'All';
                        }
                        if(!isset($_GET['galleryCategory'])){
                            $galleryCategory= $_SESSION['galleryCategory'];
                        }else{
                            $galleryCategory = htmlspecialchars($_GET["galleryCategory"], ENT_QUOTES, "UTF-8");//cleaning the input
                            $_SESSION['galleryCategory'] = $galleryCategory;
                        }
                    ?>
                    <div class="div-center">
                        <div class="divCategoryButtons">
                            <button type="submit" name="galleryCategory" value="All" <?php if(isset($galleryCategory) && $galleryCategory=='All'){echo "class='active'";} ?>>All</button>
                            <button type="submit" name="galleryCategory" value="Landscape" onclick="deleteCookie('divPagNumber')" <?php if(isset($galleryCategory) && $galleryCategory=='Landscape'){echo "class='active'";} ?>>Landscape</button>
                            <button type="submit" name="galleryCategory" value="Fantasy" onclick="deleteCookie('divPagNumber')" <?php if(isset($galleryCategory) && $galleryCategory=='Fantasy'){echo "class='active'";} ?>>Fantasy</button>
                            <button type="submit" name="galleryCategory" value="Abstract" onclick="deleteCookie('divPagNumber')" <?php if(isset($galleryCategory) && $galleryCategory=='Abstract'){echo "class='active'";} ?>>Abstract</button>
                            <button type="submit" name="galleryCategory" value="Cartoon" onclick="deleteCookie('divPagNumber')" <?php if(isset($galleryCategory) && $galleryCategory=='Cartoon'){echo "class='active'";} ?>>Cartoon</button>
                            <button type="submit" name="galleryCategory" value="Portrait" onclick="deleteCookie('divPagNumber')" <?php if(isset($galleryCategory) && $galleryCategory=='Portrait'){echo "class='active'";} ?>>Portrait</button>
                            <button type="submit" name="galleryCategory" value="Nature" onclick="deleteCookie('divPagNumber')" <?php if(isset($galleryCategory) && $galleryCategory=='Nature'){echo "class='active'";} ?>>Nature</button>
                            <button type="submit" name="galleryCategory" value="Others" onclick="deleteCookie('divPagNumber')" <?php if(isset($galleryCategory) && $galleryCategory=='Others'){echo "class='active'";} ?>>Others</button>
                        </div>
                    </div>
                    <label for="orderBy">Order By:</label>
                    <select id="orderBy" name="orderBy" onchange="orderByGalleryChanged()">
                      <option value="none"> --- </option>
                      <option value="likes" <?php if(isset($_GET['orderBy']) && $_GET['orderBy']=='likes'){echo "selected='selected'";} ?>>Likes</option>
                      <option value="latestAdded" <?php if((isset($_GET['orderBy']) && $_GET['orderBy']=='latestAdded') || (!isset($_GET['orderBy']))){echo "selected='selected'"; $_GET['orderBy'] = 'latestAdded';} ?>>Latest Added</option>
                    </select>
                </div>

            </div>
        </form>

        <?php $mostraPagination=FALSE; $j=0;?>
        <div class="clearfix galleryBoard">
            <?php
                $result;
                $orderBy = isset($_GET['orderBy']) ? $_GET['orderBy'] : 'none';
                $mostraPagination;
                if(isset($gallerySearch)){
                    //connecting to db
                    $myDb= new DbConnector();
                    $myDb->openDBConnection();
                    $result = array();
                    if($myDb->connected){
                        if(!isset($galleryCategory) || (isset($galleryCategory) && ($galleryCategory == 'All'))){
                            $qrStr = "SELECT Artista,Nome FROM opere WHERE Descrizione LIKE '%".$gallerySearch."%' OR Categoria LIKE '%".$gallerySearch."%' OR Artista LIKE '%".strtolower($gallerySearch)."%' OR Nome LIKE '%".$gallerySearch."%'";
                            if($orderBy == 'likes'){
                            	$qrStr = "SELECT Nome, Artista, COUNT(likes.Opera) as Likes FROM opere o LEFT JOIN likes on Nome=Opera and Artista=Creatore
                                    WHERE o.Descrizione LIKE '%".$gallerySearch."%' OR o.Categoria LIKE '%".$gallerySearch."%' OR o.Artista LIKE '%".$gallerySearch."%' OR Nome LIKE '%".$gallerySearch."%'
                                    GROUP BY o.Nome, o.Artista ORDER BY COUNT(likes.Opera) DESC";
                            }
                            if($orderBy == 'latestAdded'){
                            	$qrStr = "SELECT Artista,Nome FROM opere WHERE Descrizione LIKE '%".$gallerySearch."%' OR Categoria LIKE '%".$gallerySearch."%' OR Artista LIKE '%".strtolower($gallerySearch)."%' OR Nome LIKE '%".$gallerySearch."%' ORDER BY Data_upload DESC";
                            }
                        }elseif(isset($galleryCategory) && ($galleryCategory != 'All')){
                            $qrStr = 'SELECT Artista,Nome FROM opere WHERE Categoria="'.$galleryCategory.'" AND (Descrizione LIKE "%'.$gallerySearch.'%" OR Artista LIKE "%'.strtolower($gallerySearch).'%" OR Nome LIKE "%'.$gallerySearch.'%")';
                            if($orderBy == 'likes'){
                            	$qrStr = 'SELECT Nome, Artista, COUNT(likes.Opera) as Likes FROM opere LEFT JOIN likes on Nome=Opera and Artista=Creatore
                                     WHERE Categoria="'.$galleryCategory.'" AND (Descrizione LIKE "%'.$gallerySearch.'%" OR Artista LIKE "%'.$gallerySearch.'%" OR Nome LIKE "%'.$gallerySearch.'%")
                                     GROUP BY Nome, Artista ORDER BY COUNT(likes.Opera) DESC';
                            }
                            if($orderBy == 'latestAdded'){
                                $qrStr = 'SELECT Artista,Nome FROM opere WHERE Categoria="'.$galleryCategory.'" AND (Descrizione LIKE "%'.$gallerySearch.'%" OR Artista LIKE "%'.strtolower($gallerySearch).'%" OR Nome LIKE "%'.$gallerySearch.'%") ORDER BY Data_upload DESC';
                            }
                        }
                        $result = $myDb->doQuery($qrStr);
                    }
                    else
                        echo "<div class='liPaginationBlock'>Errore connessione</div>";
                    $myDb->disconnect();
                }elseif(isset($galleryCategory)){
                    //connecting to db
                    $myDb= new DbConnector();
                    $myDb->openDBConnection();
                    $result = array();
                    if($myDb->connected){
                        if($galleryCategory == 'All'){
                            $qrStr = "SELECT Artista,Nome FROM opere;";
                            if($orderBy == 'likes'){
                                $qrStr = "SELECT Nome, Artista, COUNT(likes.Opera) as Likes FROM opere LEFT JOIN likes on Nome=Opera and Artista=Creatore GROUP BY Nome, Artista ORDER BY COUNT(likes.Opera) DESC";
                            }
                            if($orderBy == 'latestAdded'){
                                $qrStr = "SELECT Artista,Nome FROM opere ORDER BY Data_upload DESC;";
                            }
                        }else{
                            $qrStr = "SELECT Artista,Nome FROM opere WHERE Categoria='".$galleryCategory."'";
                            if($orderBy == 'likes'){
                                $qrStr = "SELECT Nome, Artista, COUNT(likes.Opera) as Likes FROM opere JOIN likes on Nome=Opera and Artista=Creatore
                                     WHERE Categoria='".$galleryCategory."' GROUP BY Nome, Artista ORDER BY COUNT(likes.Opera) DESC";
                            }
                            if($orderBy == 'latestAdded'){
                                $qrStr = "SELECT Artista,Nome FROM opere WHERE Categoria='".$galleryCategory."' ORDER BY Data_upload DESC;";
                            }
                        }

                        $result = $myDb->doQuery($qrStr);
                    }
                    else
                        echo "<div class='liPaginationBlock'>Errore connessione</div>";
                    $myDb->disconnect();
                }
                if($result && ($result->num_rows > 0)){
                    $mostraPagination = ($result->num_rows <= $GLOBALS['imagesPerPage']) ? false : true;
                    $j = printGalleryItems($result,FALSE);
                }elseif(!$result || ($result->num_rows == 0)){
                    echo "<div class='liPaginationBlock'><div class='div-center'><p>Nothing to show here ... </p></div></div>";
                }
            ?>
        </div>
        <?php
            printPagination($mostraPagination,$j);
        ?>
    </div>
	<?php require_once "footer.html"?>
</body>
</html>
