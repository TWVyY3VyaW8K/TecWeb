document.getElementById('title').onkeyup = function(){
	//document.getElementById('titleCount').innerHTML =elemento.value.length;
	var tmp = document.getElementById('title');
	document.getElementById('titleCount').innerHTML = tmp.value.length;
};

document.getElementById('description').onkeyup = function(){
	//document.getElementById('titleCount').innerHTML =elemento.value.length;
	var tmp = document.getElementById('description');
	document.getElementById('descriptionCount').innerHTML = tmp.value.length;
};