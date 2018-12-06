<?php
    require_once "functions.php";
    require_once "DbConnector.php";

    $gallerySearch = htmlspecialchars($_POST["gallerySearch"], ENT_QUOTES, "UTF-8");//cleaning the input
    $galleryCategory = htmlspecialchars($_POST["galleryCategory"], ENT_QUOTES, "UTF-8");//cleaning the input
    $resultArray = array(); //array to return
    $result = array();

    //connecting to db
    $myDb= new DbConnector();
    $myDb->openDBConnection();
    
    if($myDb->connected){
        if(isset($gallerySearch)){
            if(!isset($galleryCategory) || (isset($galleryCategory) && ($galleryCategory == 'All'))){
                $qrStr = 'SELECT Artista,Nome FROM opere WHERE Descrizione LIKE "%'.$gallerySearch.'%" OR Categoria LIKE "%'.$gallerySearch.'%" OR Artista LIKE "%'.$gallerySearch.'%" OR Nome LIKE "%'.$gallerySearch.'%"';
                /*
                $qrStr = "SELECT Nome, Artista, COUNT(Nome) as Likes FROM opere o LEFT JOIN likes on Nome=Opera and Artista=Creatore
                        WHERE o.Descrizione LIKE '%".$param."%' OR o.Categoria LIKE '%".$param."%' OR o.Artista LIKE '%".$param."%'
                        GROUP BY o.Nome, o.Artista ORDER BY COUNT(Nome) DESC";
                */
            }elseif(isset($galleryCategory) && ($galleryCategory != 'All')){
                $qrStr = 'SELECT Artista,Nome FROM opere WHERE Categoria="'.$galleryCategory.'" AND (Descrizione LIKE "%'.$gallerySearch.'%" OR Artista LIKE "%'.$gallerySearch.'%" OR Nome LIKE "%'.$gallerySearch.'%")';
                /*
                $qrStr = 'SELECT Nome, Artista, COUNT(Nome) as Likes FROM opere LEFT JOIN likes on Nome=Opera and Artista=Creatore
                            WHERE Categoria="'.$_GET['galleryCategory'].'" AND (Descrizione LIKE "%'.$param.'%" OR Artista LIKE "%'.$param.'%")
                            GROUP BY Nome, Artista ORDER BY COUNT(Nome) DESC';
                */
            }
            $result = $myDb->doQuery($qrStr);
        }elseif(isset($galleryCategory)){
            if($param == 'All'){
                $qrStr = 'SELECT Artista,Nome FROM opere;';
                //$qrStr = "SELECT Nome, Artista, COUNT(Nome) as Likes FROM opere LEFT JOIN likes on Nome=Opera and Artista=Creatore GROUP BY Nome, Artista ORDER BY COUNT(Nome) DESC";
            }else{
                $qrStr = 'SELECT Artista,Nome FROM opere WHERE Categoria="'.$galleryCategory.'"';
                /*
                $qrStr = "SELECT Nome, Artista, COUNT(Nome) as Likes FROM opere JOIN likes on Nome=Opera and Artista=Creatore
                            WHERE Categoria='".$param."' GROUP BY Nome, Artista ORDER BY COUNT(Nome) DESC";
                */
            }
            $result = $myDb->doQuery($qrStr);
        }
    }
    
    //populating the result array
    if($result){
        $resultArray["Result"] = "Success";
        $tempArr = array();
        if($result->num_rows == 0){
            $resultArray["Images"] = "Nothing to show here ... ";
        }else{
            for ($i = 0; $i < $result->num_rows; $i++) {
                $row = $result->fetch_assoc();
                $tmpRow = array(
                    "Artist" => $row["Artista"],
                    "ImageName" => $row["Nome"]
                );
                array_push($tempArr,$tmpRow);
            }
            $resultArray["Images"] = $tempArr;
        }
    }elseif(!$result || !$myDb->connected){
        $resultArray["Images"] = "";
        $resultArray["Result"] = "Error";
    }

    echo json_encode($resultArray);
    $myDb->disconnect();
?>