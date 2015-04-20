<script type="text/javascript">
	$(function(){
		//Get the Json
		var contenu = {$content_json}; //Get the content of the file
		var contenu_string = JSON.stringify(contenu); //Transform it into a string
		var cara1 = contenu_string.substring(0,1); //Get the first letter
		var lng = contenu_string.length; //Calculate the string's length
		var rest = contenu_string.substr(1, lng-1); //Get the rest of the string without the first letter
		var ajout = '"container": document.getElementById("cy"),'; //The string to add
		var fin = cara1+ajout+rest; //Concatenate the first letter, the add and the rest in order to have the complet JSON for cytoscape

		var obj = eval("("+fin+")"); //A changer si trouver autre méthode qui marche
		var cy = cytoscape(obj);

		//Style
		cy.style()
			.selector('node')
			.css({
				'content':'data(id)'
			})
			.selector('edge')
			.css({
				'target-arrow-shape':'triangle',
				'width':4,
				'line-color': '#ddd',
				'target-arrow-color':'#ddd'
			})
			.update();

		cy.on('tap', 'node', { foo: 'bar' }, function(evt){
			var node = evt.cyTarget;
			if (node.id() >= 2000){ //if a year were tapped
				console.log(node.id() );
			}else{
				console.log( 'tapped ' + node.id() );
			}
		});

		cy.userPanningEnabled( false ); //Block the tree moving
		cy.userZoomingEnabled( false ); //Block the tree zooming
		cy.autoungrabify( true ); //Block the node moving

		//Look each element's id
		cy.nodes().forEach(function( ele ){
			//If the id is negative, the element were hiden
			if (ele.id() < 0){
				ele.hide()
			}
		});

	}); //fin de la fonction jquery
</script>