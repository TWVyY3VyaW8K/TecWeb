<!-- The Modal -->
<div id="SearchModal" class="Modal">
	<!-- Modal Content -->
	<form class="modal-content animate container1024" method="get" action="gallery.php">
        <div class="modalHead">
			<span onclick="closeModal('SearchModal')" class="close" title="Close Modal">&times;</span>
			<h1>Search images</h1>
		</div>
		<div class="container">
            <div class="inputSearch">
                <input type="text" placeholder="Search for category, artist or description..." name="gallerySearch"/>
                <button class="btnSearch" type="submit"><span class="searchIcon"></span></button>
            </div>
		</div>
	</form>
</div>
