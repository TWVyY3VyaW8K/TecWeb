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

    function insertImageInGallery($artista,$nomeImmagine, $numFig,$boolDeleteButton){
        if ( is_session_started() === FALSE || (!isset($_SESSION['Username']))){
            $isLiked = false;
        }else if(isset($_SESSION['Username'])){
            $isLiked = boolImageLiked($artista,$_SESSION['Username'],$nomeImmagine)['Result'];
        }
        echo '<div class="liFigures">';
        echo     '<div class="galleryFigureWrapper" id="figureWrapper_'.$numFig.'">';
        echo '      <div class="background-color-90929294"><a href="viewArtwork.php?Title='.$nomeImmagine.'&amp;Artist='.$artista.'"><img alt="'.$nomeImmagine.'" src="Images/Art/'.rawurlencode($artista).'/'.rawurlencode($nomeImmagine).'.jpeg"/></a></div>';
        echo '      <input type="hidden" value="'.$artista.'" name="nameArtist"/>';
        echo '      <input type="hidden" value="'.$nomeImmagine.'" name="nameImage"/>';
        echo '      <div class="galleryCaption">';
        echo '              <h2>'.$nomeImmagine.'</h2>';
        echo '          <div class="wrapper">';
        echo '              <div class="width-15">';
        if($isLiked == true){
            echo '              <div class="like-btn like-btn-added" onclick="btnLikeOnClick(this)" id="LikeBtn_'.$numFig.'"></div>';
        }else{
            echo '              <div class="like-btn" onclick="btnLikeOnClick(this)" id="LikeBtn_'.$numFig.'"></div>';
        }
        echo '              </div>';
        echo '              <div class="width-85">';
        echo '                  <a class="customLink" href="gallery.php?gallerySearch='.$artista.'">Artist: '.$artista.'</a>';
        echo '                  <p class="customLink" id="Likes_'.$numFig.'" onclick="btnLikedByOnClick(this)">Likes: '.getLikesByItem($artista,$nomeImmagine)['Result'].'</p>';       
        echo '              </div>';
        echo '          </div>';
        echo '                  <a href="viewArtwork.php?Title='.$nomeImmagine.'&amp;Artist='.$artista.'"><button class="btnDiscover" type="submit" id="DelBtn_'.$numFig.'">Details</button></a>';
        if($boolDeleteButton == TRUE){
            echo '<button class="btnDelete" type="submit" id="DelBtn_'.$numFig.'" onclick="btnDeleteOnClick(this)">Delete</button>';
        }
        echo '      </div>';
        echo '   </div>';
        echo '</div>';
        echo "\r\n";
    }

    //return true if image is already liked
    function boolImageLiked($artista,$username,$nomeImmagine){
        $myDb= new DbConnector();
        $myDb->openDBConnection();
        $resArr=array();
        if($myDb->connected){
            $result = $myDb->doQuery('SELECT Utente FROM likes WHERE opera="'.$nomeImmagine.'" AND Utente="'.strtolower($username).'" AND Creatore="'.$artista.'";');
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

    //prints div containing buttons for pagination in the gallery page
    //IN:
    // - $i: number of buttons to be displayed
    function printDivPagination($i){
        echo '<div class="div-center">';
        echo '   <div class="div-bar gal-pag-border gal-border-round">';
        echo '      <a href="#" class="div-bar-item gal-pag-button" id="btnPagBack" onclick="btnPagBackOnClick()">&laquo;</a>';
        for($j=1; $j < $i; $j++){
            echo '  <a href="#" class="div-bar-item gal-pag-button" id="btnPagination'.$j.'" onclick="btnPaginationOnClick(this.id)">'.$j.'</a>';
        }
        echo '      <a href="#" class="div-bar-item gal-pag-button" id="btnPagForward" onclick="btnPagForwardOnClick()">&raquo;</a>';
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
    function printGalleryItems($result,$boolDeleteButton){
        $j=0;//solo una pagina
        $j=1;
        $boolChiudi = False;
        for ($i = 0; $i < $result->num_rows; $i++) {
            if($i%($GLOBALS['imagesPerPage']) == 0){
                if($boolChiudi == FALSE){
                    echo "<div id='galImgPag".$j."' class='liPaginationBlock'>";
                    $j++;
                    $boolChiudi = TRUE; 
                }else{
                    echo "</div>";
                    echo "<div id='galImgPag".$j."' class='liPaginationBlock'>";
                    $j++;
                }
            }
            $row = $result->fetch_assoc();
            insertImageInGallery($row['Artista'],$row['Nome'],$i+1,$boolDeleteButton);
        }
        if($boolChiudi == TRUE || ($i%($result->num_rows)!= 1)){
            echo "</div>";
        }
        return $j;
    }

    function getLikesByItem($artista, $nomeImmagine){
        $myDb= new DbConnector();
        $myDb->openDBConnection();
        
        $resArr = array();
        if($myDb->connected){
            $qrStr= "SELECT COUNT(Opera) as Likes FROM likes WHERE Creatore='".$artista."' AND Opera='".$nomeImmagine."'";
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

    function deleteItem($artista, $nomeImmagine){
        $myDb= new DbConnector();
        $myDb->openDBConnection();

        $resArr = array();
        if($myDb->connected){
            $qrStr= "DELETE FROM opere WHERE Artista='".$artista."' AND Nome='".$nomeImmagine."'";
            $result = $myDb->doQuery($qrStr);
            if ($result == TRUE) {
                deleteFileFromFileSystem($artista,$nomeImmagine);
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

    function printPagination($mostraPagination,$j){
        if($mostraPagination == TRUE && ($j > 2)){
            printDivPagination($j);
        }
    }

    function escapePathTraversal($path){
        return str_replace("/", "", str_replace(".", "", $path));
    }
?>