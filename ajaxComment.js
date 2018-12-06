Element.prototype.remove = function() {
    this.parentElement.removeChild(this);
}
NodeList.prototype.remove = HTMLCollection.prototype.remove = function() {
    for(var i = this.length - 1; i >= 0; i--) {
        if(this[i] && this[i].parentElement) {
            this[i].parentElement.removeChild(this[i]);
        }
    }
}

function doComment(Opera, Creatore)
{
	var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
    	 var res = this.response;
         if(res.startsWith("--alert--"))
         	alert(res.replace("--alert--", ""));
         else
         {
         	var topComment = document.getElementById("topComment");
            var topComment_txt = document.getElementById("topComment").innerHTML;
            topComment.remove();
    	 	document.getElementById("commentSection").innerHTML = '<div id="topComment" class="comment">' + topComment_txt + '</div>' + res + document.getElementById("commentSection").innerHTML;
         }
    }
    }
    var text = document.getElementById("texxt");
    xhttp.open("POST","AddComment.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("Opera="+Opera+"&Creatore="+Creatore+"&Commento="+text.value);
}

function removeComment(comment, ID){
	var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
    	 var res = this.response;
         if(res.startsWith("--alert--"))
         	alert(res.replace("--alert--", ""));
         else
    	 	comment.parentNode.style.display = "none";
    }
    }
    
    xhttp.open("POST","RemoveComment.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("ID="+ID.toString());
}