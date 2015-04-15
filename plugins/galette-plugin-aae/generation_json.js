function requete(nom, prenom){
	var ajax = new XMLHttpRequest();
	/*ajax.open('POST','donnees_json.php',true);
	ajax.setRequestHeader('Content-type','application/x-www-form-urlencoded');
	ajax.addEventListener('readystatechange',function(e){
		if(ajax.readyState == 4 && ajax.satus == 200){
			//var reponse = document.getElementById('json');
			//reponse.innerHTML = ajax.responseText;
		}
	});
	ajax.send('nom='+nom+'prenom='+prenom);*/
	console.log(nom);
}

