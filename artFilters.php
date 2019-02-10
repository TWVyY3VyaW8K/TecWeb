<form method="get" action="" name="formArtFilter">
    <div class="artFilter">
        <div class="inputSearch">
            <?php
                if(isset($_GET['gallerySearch'])){
                    $gallerySearch = htmlspecialchars($_GET["gallerySearch"], ENT_QUOTES, "UTF-8");//cleaning the input
                    echo '<label for="searchField">Search for category, artist, description ..</label>';
                    echo '<input id="searchField" type="text" name="gallerySearch" value="'.$gallerySearch.'"/>';
                    resetSessionPaginationNum('pagNum'.ucfirst(pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME)));
                }else{
                    echo '<label for="searchField">Search for category, artist, description ..</label>';
                    echo '<input id="searchField" type="text" name="gallerySearch"/>';
                }

            ?>
            <button class="btnSearch" type="submit"><span class="searchIcon"></span></button>
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
                    resetSessionPaginationNum('pagNum'.ucfirst(pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME)));
                    $galleryCategory = htmlspecialchars($_GET["galleryCategory"], ENT_QUOTES, "UTF-8");//cleaning the input
                    $_SESSION['galleryCategory'] = $galleryCategory;
                }
            ?>
            <div class="div-center">
                <div class="divCategoryButtons">
                    <button type="submit" name="galleryCategory" value="All" <?php if(isset($galleryCategory) && $galleryCategory=='All'){echo "class='active'";} ?>>All</button>
                    <button type="submit" name="galleryCategory" value="Landscape" <?php if(isset($galleryCategory) && $galleryCategory=='Landscape'){echo "class='active'";} ?>>Landscape</button>
                    <button type="submit" name="galleryCategory" value="Fantasy"<?php if(isset($galleryCategory) && $galleryCategory=='Fantasy'){echo "class='active'";} ?>>Fantasy</button>
                    <button type="submit" name="galleryCategory" value="Abstract" <?php if(isset($galleryCategory) && $galleryCategory=='Abstract'){echo "class='active'";} ?>>Abstract</button>
                    <button type="submit" name="galleryCategory" value="Cartoon" <?php if(isset($galleryCategory) && $galleryCategory=='Cartoon'){echo "class='active'";} ?>>Cartoon</button>
                    <button type="submit" name="galleryCategory" value="Portrait" <?php if(isset($galleryCategory) && $galleryCategory=='Portrait'){echo "class='active'";} ?>>Portrait</button>
                    <button type="submit" name="galleryCategory" value="Nature" <?php if(isset($galleryCategory) && $galleryCategory=='Nature'){echo "class='active'";} ?>>Nature</button>
                    <button type="submit" name="galleryCategory" value="Others" <?php if(isset($galleryCategory) && $galleryCategory=='Others'){echo "class='active'";} ?>>Others</button>
                </div>
            </div>
            <label for="orderBy">Order By:</label>
            <select id="orderBy" name="orderBy" onchange='this.form.submit()'>
                <option value="none"> --- </option>
                <option value="likes" <?php if(isset($_GET['orderBy']) && $_GET['orderBy']=='likes'){echo "selected='selected'";} ?>>Likes</option>
                <option value="latestAdded" <?php if((isset($_GET['orderBy']) && $_GET['orderBy']=='latestAdded') || (!isset($_GET['orderBy']))){echo "selected='selected'"; $_GET['orderBy'] = 'latestAdded';} ?>>Latest Added</option>
            </select>
        </div>
    </div>
</form>