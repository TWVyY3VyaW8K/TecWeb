//function to close the  modal clicking outside
function eventListnerforLoginModal() {
	var arrModal = ['LoginModal','SignUpModal','EditProfileModal','SearchModal','LikedByModal'];

	// When the user clicks anywhere outside of the modal, close it
	window.onclick = function(event) {
		for(i=0; i < arrModal.length; i++){
			if (event.target === document.getElementById(arrModal[i]))
				closeModal(arrModal[i]);
		}
		/*
		if (event.target === document.getElementById('LoginModal')) {
			closeModal('LoginModal');
		}
		if (event.target === document.getElementById('SignUpModal')) {
			closeModal('SignUpModal');
		}
		if (event.target === document.getElementById('EditProfileModal')) {
			closeModal('EditProfileModal');
		}
		if (event.target === document.getElementById('SearchModal')) {
			closeModal('SearchModal');
			//document.getElementById('SearchModal').style.display = "none";
		}
		if (event.target === document.getElementById('LikedByModal')) {
			closeModal('LikedByModal');
			//document.getElementById('LikedByModal').style.display = "none";
		}
		*/
	}
}

// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function() {scrollFunction()};
function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        document.getElementById("scrollToTop").style.display = "block";
    } else {
        document.getElementById("scrollToTop").style.display = "none";
    }
}
// When the user clicks on the button, scroll to the top of the document
function topFunction() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}

function skipFocused(){
	document.getElementById('skipmenu').style.height = "100%";
}

function skipUnfocused(){
	document.getElementById('skipmenu').style.height = 0;
}

function validateString(str){
	return !(str==="");
}
//function to login using ajax
function doLogin(event) {
	event.preventDefault()//prevents to reload the page if login data arent correct

	//getting the values of the fields
	var usr=document.getElementById('usr').value;
	var pwd=document.getElementById('pwd').value;
    if((validateString(usr)===true)&&(validateString(pwd)===true)){
      // creating ajax object
      var xhttp;
      if (window.XMLHttpRequest) {
      // code for modern browsers
      xhttp = new XMLHttpRequest();
      } else {
      // code for IE6, IE5
      xhttp = new ActiveXObject("Microsoft.XMLHTTP");
      }
      //calback function for the request
      xhttp.onreadystatechange = function() {
      	if (this.readyState === 4 && this.status === 200) {
          //if status is ok
          if(this.responseText==="Success")
              location.reload();
          else
			  document.getElementById("InvalidLogin").innerHTML = this.responseText;

			  closeModal('imgLoader');
      	}
      };

	  openModal('imgLoader');
      //doing th ajax request
      xhttp.open("POST", "doLogin.php", true);
      xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhttp.send("usr="+usr+"&pwd="+pwd);
	}else{
			document.getElementById("InvalidLogin").innerHTML = "Please Insert a Username/Password";

	}
  return false;
}

//function to lofOut using ajax
function doLogOut(event) {

	// creating ajax object
	var xhttp;
	if (window.XMLHttpRequest) {
	// code for modern browsers
	xhttp = new XMLHttpRequest();
	} else {
	// code for IE6, IE5
	xhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	//calback function for the request
	xhttp.onreadystatechange = function() {
	if (this.readyState === 4 && this.status == 200) {
		//if status is ok
		if(this.responseText=="success"){
			document.location.href="/"; //go to home page
		}
		closeModal('imgLoader');
	}
	};
	openModal('imgLoader');
	//doing th ajax request
	xhttp.open("POST", "doLogOut.php", true);
	xhttp.send();

}


//function to Singh Up using ajax
function doSignUp(event) {
	event.preventDefault()//prevents to reload the page if login data arent correct
    //getting the values of the fields
	var usr=document.getElementById('usrSignUp').value;
	var pwd=document.getElementById('pwdSignUp').value;
	var name=document.getElementById('name').value;
	var surname=document.getElementById('surname').value;

    if((validateString(usr)===true)&&(validateString(pwd)===true)&&(validateString(name)===true)&&(validateString(surname)===true)){
		if(pwd.length>=5){
            // creating ajax object
            var xhttp;
            if (window.XMLHttpRequest) {
            // code for modern browsers
            xhttp = new XMLHttpRequest();
            } else {
            // code for IE6, IE5
            xhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            //calback function for the request
            xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //if status is ok
                if(this.responseText==="Success"){
                    location.reload();
                }else{
                    document.getElementById("SignUpMessage").innerHTML = this.responseText;
                }
				closeModal('imgLoader');
            }
			};
			openModal('imgLoader');
            //doing th ajax request
            xhttp.open("POST", "doSignUp.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("usr="+usr+"&pwd="+pwd+"&name="+name+"&surname="+surname);
       	}else{
			document.getElementById("SignUpMessage").innerHTML = "Password must be at least 5 characters";
		}
	}else{
			document.getElementById("SignUpMessage").innerHTML = "Please fill all the fields";

	}
  	return false;
}



//function to Edit Profile using ajax
function doEditProfile(event) {
	event.preventDefault()//prevents to reload the page if login data arent correct
   //getting the values of the fields
    var usr=document.getElementById('usrEdit').value;
 	var pwd=document.getElementById('pwdEdit').value;
  	var name=document.getElementById('nameEdit').value;
  	var surname=document.getElementById('surnameEdit').value;
    if((validateString(usr)===true)&&(validateString(pwd)===true)&&(validateString(name)===true)&&(validateString(surname)===true)){
		if(pwd.length>=5){
            // creating ajax object
            var xhttp;
            if (window.XMLHttpRequest) {
            // code for modern browsers
            xhttp = new XMLHttpRequest();
            } else {
            // code for IE6, IE5
            xhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            //calback function for the request
            xhttp.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {
                //if status is ok
                document.getElementById("EditProfileMessage").innerHTML = this.responseText;
				closeModal('imgLoader');
            }
            };
			openModal('imgLoader');
            //doing th ajax request
            xhttp.open("POST", "doEditProfile.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("usr="+usr+"&pwd="+pwd+"&name="+name+"&surname="+surname);
        }else{
			document.getElementById("EditProfileMessage").innerHTML = "Password must be at least 5 characters";
		}
	}else{
			document.getElementById("EditProfileMessage").innerHTML = "Please fill all the fields";

	}

  return false;
}

//drobdown menu event
function openDrobDownMenu(btn) {
    var x = document.getElementById("Topnav");
    if (x.className === "menu") {
        x.className += " responsive";
    } else {
        x.className = "menu";
    }
	btn.classList.toggle("rotate");

}

function openModal(idModal){
	if(idModal == "imgLoader")
		document.getElementById(idModal).style.display = "flex";
	else
		document.getElementById(idModal).classList.add('display-block');
}
function closeModal(idModal){
	if(idModal == "imgLoader")
		document.getElementById(idModal).style.display = "none";
	else
		document.getElementById(idModal).classList.remove('display-block');
}

function orderByGalleryChanged(){
	document.getElementsByName('formArtFilter')[0].submit();
}

/*
Delete function
*/
function btnDeleteOnClick(obj){
	if(confirm("Do you really want to delete this image?")){
		// creating ajax object
		var xhttp;
		var idNumber = obj.id.substring(("DelBtn_").length);
	        var artist = document.querySelector('[id="figureWrapper_'+idNumber+'"] [name="nameArtist"]').value;
	        var immg = document.querySelector('[id="figureWrapper_'+idNumber+'"] [name="nameImage"]').value;
		if (window.XMLHttpRequest) {
			// code for modern browsers
			xhttp = new XMLHttpRequest();
		} else {
			// code for IE6, IE5
			xhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}

		//calback function for the request
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				//if status is ok
				$arrRes = JSON.parse(this.responseText);
				if($arrRes['Result'] == 1){//Item deleted with success
					location.reload();
				}else if($arrRes['Result'] == -1 || $arrRes['Result'] == "Connection Error"){//Error
					alert('Error');
				}
			}
			closeModal('imgLoader');
		};
		openModal('imgLoader');
		//doing th ajax request
		xhttp.open("POST", "deleteItem.php", true);
		xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhttp.send("art="+artist+"&nomeImg="+immg);
	}
}

function btnLikedByOnClick(obj){
	// creating ajax object
	var xhttp;
	var idNumber = obj.id.substring(("Likes_").length);
	var artist;
	var immg;
	if((window.location.href).indexOf("viewArtwork.php")!== -1){
		artist = document.querySelector('[id="description-comments"] [name="nameArtist"]').value;
		immg = document.querySelector('[id="description-comments"] [name="nameImage"]').value;
	}else{
		artist = document.querySelector('[id="figureWrapper_'+idNumber+'"] [name="nameArtist"]').value;
		immg = document.querySelector('[id="figureWrapper_'+idNumber+'"] [name="nameImage"]').value;
	}
	if (window.XMLHttpRequest) {
		// code for modern browsers
		xhttp = new XMLHttpRequest();
	} else {
		// code for IE6, IE5
		xhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}

	//calback function for the request
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			//if status is ok
			arrRes = JSON.parse(this.responseText);
			if(arrRes['Result'] == 1){//Users found
				str = "";
				arrUsers = arrRes['Users'];
				for(var i=0; i < arrUsers.length; ++i){
					str += '<div class="comment"><a href="gallery.php?gallerySearch=' + arrUsers[i]+'">'+arrUsers[i]+'</a></div>';
				}
				document.querySelector('#LikedByModal .commentSection').innerHTML = str;
				openModal('LikedByModal');
			}else if(arrRes['Result'] == -1 || arrRes['Result'] == "Connection Error"){//Error
				alert('Error');
			}
			closeModal('imgLoader');
		}
	};
	//doing th ajax request
	//document.getElementById('imgLoader').classList.toggle('display-none');
	openModal('imgLoader');
	xhttp.open("POST", "likedBy.php", true);
	xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhttp.send("art="+artist+"&nomeImg="+immg);
}

/*
Function by page gallery.php
*/
function btnLikeOnClick(obj){
	// creating ajax object
	var xhttp;
	var idNumber = obj.id.substring(("LikeBtn_").length);
	var artist;
	var immg;
	if((window.location.href).indexOf("viewArtwork.php")!== -1){
		artist = document.querySelector('[id="description-comments"] [name="nameArtist"]').value;
		immg = document.querySelector('[id="description-comments"] [name="nameImage"]').value;
	}else{
		artist = document.querySelector('[id="figureWrapper_'+idNumber+'"] [name="nameArtist"]').value;
		immg = document.querySelector('[id="figureWrapper_'+idNumber+'"] [name="nameImage"]').value;
	}
	if (window.XMLHttpRequest) {
        // code for modern browsers
        xhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    //calback function for the request
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
			//if status is ok
			var arrRes = JSON.parse(this.responseText);
            if(arrRes['Result']==0){//finestra di login
				openModal('LoginModal');
            }else if(arrRes['Result'] == 1){//like inserito
                obj.classList.add("like-btn-added");
            }else if(arrRes['Result'] == 2){//like rimosso
                obj.classList.remove("like-btn-added");
            }else{//errore
                alert("Errore");
			}
			if((window.location.href).indexOf("likedItems.php")!== -1){
				location.reload();
			}else if(arrRes['Result'] == 1 || arrRes['Result'] == 2){
				updateLikeCounter(idNumber,artist,immg);
			}
			closeModal('imgLoader');
        }
	};
	openModal('imgLoader');
	//doing th ajax request
	xhttp.open("POST", "giveLike.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("art="+artist+"&nomeImg="+immg);
}

//function used to update the like counter of an image
function updateLikeCounter(idNumber,artist,imageName){
	// creating ajax object
	var xhttp;
	var obj = document.getElementById("Likes_"+idNumber);
	if (window.XMLHttpRequest) {
        // code for modern browsers
        xhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    //calback function for the request
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
			//if status is ok
			var arrRes = JSON.parse(this.responseText);
            if(arrRes['Result']=="Connection Error"){
                obj.innerHTML = "Likes: 0";
            }else{
				obj.innerHTML = "Likes: "+arrRes['Result'];
			}
			closeModal('imgLoader');
        }
	};
	openModal('imgLoader');
	//doing th ajax request
	xhttp.open("POST", "getLikes.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("art="+artist+"&nomeImg="+imageName);
}

//onclick function for pagination buttons
function btnPaginationOnClick(id){
	var arr = document.querySelectorAll('[id^="galImgPag"]');
	for(i=0; i< arr.length; i++){ //hide all the divs
		var item = arr[i];
		item.classList.remove('display-block');
		item.classList.add('display-none');
	}
	var divNumber = id.substring("btnPagination".length,id.length);

	//show only one gallery images container div
	var tmp = document.querySelector('[id="galImgPag'+divNumber+'"]');
	if(tmp !== null){
		document.querySelector('[id="galImgPag'+divNumber+'"]').classList.remove('display-none');
		document.querySelector('[id="galImgPag'+divNumber+'"]').classList.add('display-block');
	}
	//remove active status to all pagination buttons
	var arr=document.querySelectorAll('[id^="btnPagination"]');
	for(i=0; i< arr.length; i++){
		var item = arr[i];
		item.classList.remove('btnPaginationActive');
	}

	tmp = document.querySelector('[id="btnPagination'+divNumber+'"]');
	if(tmp !== null){
		tmp.classList.add('btnPaginationActive');
	}

	//document.getElementById("btnPagBack").classList.remove("display-none");
	//document.getElementById("btnPagForward").classList.remove("display-none");
	var btnBack = document.getElementById("btnPagBack");
	var btnForward = document.getElementById("btnPagForward");
	if(btnBack !== null)
		btnBack.style.display = 'inline-block';
	if(btnForward !== null)
		btnForward.style.display = 'inline-block';
	if(divNumber == 1 && (btnBack !== null)){//if it is the first btn of pagination
		btnBack.style.display = 'none';
	}
	if(divNumber == document.querySelectorAll('[id^="galImgPag"]').length && (btnForward !== null)){
		btnForward.style.display = 'none';
	}
	//cookie
	setCookie("divPagNumber",divNumber,1,window.location.pathname);
}

//btn pagination back on click
function btnPagBackOnClick(){
	var activeBtnPag = document.querySelector('.btnPaginationActive');
	var num = Number(activeBtnPag.id.substring("btnPagination".length,activeBtnPag.id.length));
	if (num > 1){
		btnPaginationOnClick("btnPagination"+(num-1));
	}
}

//btn pagination forward on click
function btnPagForwardOnClick(){
	var activeBtnPag = document.querySelector('.btnPaginationActive');
	var num = Number(activeBtnPag.id.substring("btnPagination".length,activeBtnPag.id.length));
	if (num < document.querySelectorAll('[id^="galImgPag"]').length){
		btnPaginationOnClick("btnPagination"+(num+1));
	}
}

function doUploadValidation(event){
	//event.preventDefault()//prevents to reload the page if login data arent correct
    var title=document.getElementById('title').value;
   	var description=document.getElementById('description').value;
    var returnValue =true;
	if (document.getElementById("success_message") !=null) {
		document.getElementById("success_message").innerHTML ="";
    }

    if((title==="")||(description==="")){
 		document.getElementById("uploadMessage").innerHTML ="Please Fill all the fields";
        returnValue=false;
    }else{
    	if( document.getElementById("artwork").files.length == 0 ){
        	document.getElementById("uploadMessage").innerHTML ="Please Select an image";
        	returnValue=false;
    	}
    }
	if(description.length>1000){
		document.getElementById("uploadMessage").innerHTML ="Description is too long";
		returnValue=false;
	}

    return returnValue;
}

function initializePagination(){

	if(getCookie('divPagNumber') == "")
		btnPaginationOnClick("btnPagination1");
	else
		btnPaginationOnClick("btnPagination"+getCookie('divPagNumber'));
}

function setCookie(cname, cvalue, exdays, path) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path="+path;
}

function deleteCookie(cname){
	  document.cookie = cname + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path="+window.location.pathname;
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}
