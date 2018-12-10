<?php
    require_once "DbConnector.php";
    $GLOBALS['imagesPerPage'] = 8;
    /**
    * @return bool
    */
    function is_session_started()
    {
        if ( php_sapi_name() !== 'cli' ) {
            if ( version_compare(phpversion(), '5.4.0', '>=') ) {
                return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
            } else {
                return session_id() === '' ? FALSE : TRUE;
            }
        }
        return FALSE;
    }
    function insertImageInGallery($artist,$imgName, $numFig,$boolDeleteButton){
        if(isset($_SESSION['giveLike']) && ($_SESSION['giveLike'] == $numFig)){
            $tmp = giveLike($artist,$imgName);
            unset($_SESSION['giveLike']);
        }
        if ( is_session_started() === FALSE || (!isset($_SESSION['Username']))){
            $isLiked = false;
        }else if(isset($_SESSION['Username'])){
            $isLiked = boolImageLiked($artist,$_SESSION['Username'],$imgName)['Result'];
        }
        if(isset($_SESSION['deleteImage']) && ($_SESSION['deleteImage'] == $numFig)){
            $tmp = deleteItem($artist,$imgName);
            unset($_SESSION['deleteImage']);
        }else{
            echo '<div class="liFigures">';
            echo     '<div class="galleryFigureWrapper" id="figureWrapper_'.$numFig.'">';
            echo '      <div class="background-color-90929294"><a href="viewArtwork.php?Title='.$imgName.'&amp;Artist='.$artist.'"><img alt="'.$imgName.'" src="Images/Art/'.rawurlencode($artist).'/'.rawurlencode($imgName).'.jpeg"/></a></div>';
            echo '      <input type="hidden" value="'.$artist.'" name="nameArtist"/>';
            echo '      <input type="hidden" value="'.$imgName.'" name="nameImage"/>';
            echo '      <div class="galleryCaption">';
            echo '              <h2>'.$imgName.'</h2>';
            echo '          <div class="wrapper">';
            echo '              <div class="width-15">';
            //saveBackPage();
            $url = $_SERVER['REQUEST_URI'];
            //echo $url;
            $query = parse_url($url, PHP_URL_QUERY);
            if ($query) {
                $url .= '&lNumI='.$numFig;
            } else {
                $url .= '?lNumI='.$numFig;
            }

            //$url = addParameterTo($_SERVER['REQUEST_URI'],'lNumI',$numFig);
            if($isLiked == true){
                echo '              <a href="'.$url.'"><div class="like-btn like-btn-added"></div></a>';
            }else{
                echo '              <a href="'.$url.'"><div class="like-btn"></div></a>';
            }
            echo '              </div>';
            echo '              <div class="width-85">';
            echo '                  <a class="customLink" href="gallery.php?gallerySearch='.$artist.'">Artist: '.$artist.'</a>';
            echo '                  <p><a class="customLink" href="likedBy.php?artist='.$artist.'&imgName='.$imgName.'">Likes: '.getLikesByItem($artist,$imgName)['Result'].'</a></p>';
            echo '              </div>';
            echo '          </div>';
            echo '                  <a href="viewArtwork.php?Title='.$imgName.'&amp;Artist='.$artist.'"><button class="btnDiscover" type="submit" id="DelBtn_'.$numFig.'">Details</button></a>';
            $url = $_SERVER['REQUEST_URI'];
            //echo $url;
            $query = parse_url($url, PHP_URL_QUERY);
            if ($query) {
                $url .= '&dNumI='.$numFig;
            } else {
                $url .= '?dNumI='.$numFig;
            }
            if($boolDeleteButton == TRUE){
                echo '<a href="'.$url.'"><button class="btnDelete" type="submit">Delete</button></a>';
            }
            echo '      </div>';
            echo '   </div>';
            echo '</div>';
            echo "\r\n";
        }
    }
    //return true if image is already liked
    function boolImageLiked($artist,$username,$imgName){
        $myDb= new DbConnector();
        $myDb->openDBConnection();
        $resArr=array();
        if($myDb->connected){
            $result = $myDb->doQuery('SELECT Utente FROM likes WHERE opera="'.$imgName.'" AND Utente="'.strtolower($username).'" AND Creatore="'.$artist.'";');
            if($result){
                if($result->num_rows==0){//significa che il like non è ancora presente per l'opera
                    $resArr['Result'] = false;
                }else if($result->num_rows==1){//significa che il like è presente
                    $resArr['Result'] = true;
                }
            }else{
                $resArr['Result'] = "Errore";
            }
        }
        else
            $resArr['Result'] = "Connection Error";
        return $resArr;
        $myDb->disconnect();
    }
    function giveLike($artist,$imgName){
        if ( is_session_started() === FALSE ) session_start();
        $resArr = array();
        if(!isset($_SESSION["Username"])){
            $resArr['Result'] = 0; //utente non loggato
        }else{
            //connecting to db
            $myDb= new DbConnector();
            $myDb->openDBConnection();

            if($myDb->connected){
                //echo 'INSERT INTO Likes (Opera, Utente, Creatore) VALUES ("'.$imgName.'", "'.$_SESSION["Username"].'", "'.$artist.'");';
                //$result = $myDb->doQuery('INSERT INTO Likes (Opera, Utente, Creatore) VALUES ("'.$imgName.'", "'.$_SESSION['Username'].'", "'.$artist.'");');//excecute query
                $result = $myDb->doQuery('SELECT Utente FROM likes WHERE Opera="'.$imgName.'" AND Utente="'.$_SESSION['Username'].'" AND Creatore="'.$artist.'";');
                //echo $myDb->getLastErrorString();
                if($result){
                    if($result->num_rows==0){//significa che il like non è ancora presente per l'opera
                        $result = $myDb->doQuery('INSERT INTO likes (Opera, Utente, Creatore) VALUES ("'.$imgName.'", "'.$_SESSION['Username'].'", "'.$artist.'");');//excecute query
                        if($result)
                        $resArr['Result'] = 1;
                    }else if($result->num_rows==1){
                        $result = $myDb->doQuery('DELETE FROM likes WHERE Opera="'.$imgName.'" AND Utente="'.$_SESSION['Username'].'" AND Creatore="'.$artist.'";');//excecute query
                        if($result)
                            $resArr['Result'] = 2;
                    }
                }else{
                    $resArr['Result'] = 3;
                }
            }
            else
                $resArr['Result'] = "Connection Error";
            $myDb->disconnect();
        }
    }
    //prints div containing buttons for pagination in the gallery page
    //IN:
    // - $i: number of buttons to be displayed
    function printDivPagination($i,$currentPagNum, $currentPageName){
        echo '<div class="div-center pagination">';
        echo '   <div class="div-bar gal-pag-border gal-border-round">';
        if($currentPagNum > 1){
            echo '      <a href="'.$currentPageName.'?pagNum='.(string)($currentPagNum-1).'" class="div-bar-item gal-pag-button" id="btnPagBack">&laquo;</a>';
        }
        for($j=1; $j <= $i; $j++){
            if($j==$currentPagNum)
                echo '  <div href="'.$currentPageName.'?pagNum='.(string)($j).'" class="div-bar-item gal-pag-button btnPaginationActive notClickable" id="btnPagination'.$j.'" onclick="btnPaginationOnClick(this.id)">'.$j.'</div>';
            else
                echo '  <a href="'.$currentPageName.'?pagNum='.(string)($j).'" class="div-bar-item gal-pag-button" id="btnPagination'.$j.'" onclick="btnPaginationOnClick(this.id)">'.$j.'</a>';
        }
        if($currentPagNum < $i){
            echo '      <a href="'.$currentPageName.'?pagNum='.(string)($currentPagNum+1).'" class="div-bar-item gal-pag-button" id="btnPagForward">&raquo;</a>';
        }
        echo '  </div>';
        echo '</div>';
    }
    /*
    * Function for printing gallery items
    * IN:
    *   - $result: object returned after doing $myDb->doQuery()
    * OUT:
    *   - $j: number of pages to be displayed
    */
    function printGalleryItems($result,$boolDeleteButton,$paginationNumber){
        $j=1;
        $tmp=0;
        $boolPrint = False;
        $exitLoop = False;
        echo "<div class='liPaginationBlock'>";
        for ($i = 0; $i < $result->num_rows; $i++) {
            if($i%($GLOBALS['imagesPerPage']) == 0){
                if(($j == $paginationNumber) && ($boolPrint == FALSE)){
                    $boolPrint = TRUE;
                }else{
                    $j++;
                }
            }
            $row = $result->fetch_assoc();
            if($boolPrint == TRUE){
                insertImageInGallery($row['Artista'],$row['Nome'],$i+1,$boolDeleteButton);
                $tmp++;
                if($tmp == $GLOBALS['imagesPerPage']){//exiting the for
                    $boolPrint = FALSE;
                    $j++;
                }
            }
        }
        echo "</div>";
        return ceil($result->num_rows/$GLOBALS['imagesPerPage']);
    }
    function resetSessionPaginationNum($sessionName){
        $_SESSION[$sessionName] = 1;
    }
    function getLikesByItem($artist, $imgName){
        $myDb= new DbConnector();
        $myDb->openDBConnection();

        $resArr = array();
        if($myDb->connected){
            $qrStr= "SELECT COUNT(Opera) as Likes FROM likes WHERE Creatore='".$artist."' AND Opera='".$imgName."'";
            $result = $myDb->doQuery($qrStr);
            if($result && $result->num_rows == 1){
                if($result && $result->num_rows == 1){
                    $row = $result->fetch_assoc();
                    $resArr['Result'] = $row['Likes'];
                }
            }
        }
        else
            $resArr['Result'] = "Connection Error";
        return $resArr;
        $myDb->disconnect();
    }
    function deleteItem($artist, $imgName){
        $myDb= new DbConnector();
        $myDb->openDBConnection();
        $resArr = array();
        if($myDb->connected){
            $qrStr= "DELETE FROM opere WHERE Artista='".$artist."' AND Nome='".$imgName."'";
            $result = $myDb->doQuery($qrStr);
            if ($result == TRUE) {
                deleteFileFromFileSystem($artist,$imgName);
                $resArr['Result'] = 1;
                //echo 1;
            } else { //Error
                $resArr['Result'] = -1;
                //echo -1;
            }
        }
        else
            $resArr['Result'] = "Connection Error";
        return $resArr;
        $myDb->disconnect();
    }
    function deleteFileFromFileSystem($username,$title){
        $destination_img = "Images/Art/".$username."/".$title.".jpeg";
        if(file_exists($destination_img)){
            @unlink($destination_img);
        }
    }
    function printPagination($mostraPagination,$j,$currentPagNum,$currentPageName){
        if($mostraPagination == TRUE && ($j >= 2)){
            printDivPagination($j,$currentPagNum,$currentPageName);
        }
    }
    function escapePathTraversal($path){
        return str_replace("/", "", str_replace(".", "", $path));
    }
    /*---------------------------------------------------------*/
    //generates the code for the back button
    function getBackButton(){
        if(isset($_SESSION['backPage'])) {
            return '<a href="'.$_SESSION['backPage'].'" class="backButton">Back</a>';
        } else {
            return '<a href="/index.php" class="backButton">Back</a>';
        }
    }
    //save the back page
    function saveBackPage(){
       // echo  $_SERVER['HTTP_REFERER'];
        unset($_SESSION["backPage"]);
        $_SERVER['HTTP_REFERER'] = removeqsvar($_SERVER['HTTP_REFERER'], 'lNumI');
        $_SESSION["backPage"] = $_SERVER['HTTP_REFERER'];
       // $_SESSION["backPage"] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
       // setcookie("backPage", "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]", time() + (86400 * 30), "/"); // 30 day
    }
    function getBackPageURL(){
        return $_SESSION["backPage"];
    }
    function randomPassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pwd = array(); // to declare $pass as an array
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, (strlen($alphabet) - 1));
            $pwd[] = $alphabet[$n];
        }
        return implode($pwd); //turn the array into a string
    }
    function removeqsvar($url, $varname) {
        list($urlpart, $qspart) = array_pad(explode('?', $url), 2, '');
        parse_str($qspart, $qsvars);
        unset($qsvars[$varname]);
        $newqs = http_build_query($qsvars);
        $r = (strlen($newqs) > 0) ? $urlpart . '?' . $newqs : $urlpart;
        return $r;
    }
?>