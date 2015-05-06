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
				'content':'data(name)',
				'text-halign':'center',
				'text-valign':'center'
			})
			.selector('edge')
			.css({
				'target-arrow-shape':'triangle',
				'width':4,
				'line-color': '#ddd',
				'target-arrow-color':'#ddd'
			})
			.update();

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

		//variable initialisation
		var canvas = document.getElementById("cy");
		var infobulle = document.getElementById("popup");
		var lien = document.getElementById("lien_profil");
		var x_souris = 0;
		var y_souris = 0;

		infobulle.style.display = 'none'; //hide the tooltip

		//Mouse's coordinates
		function getMousePos(canvas, evt) {
			var rect = canvas.getBoundingClientRect();
			return {
				x: evt.clientX - rect.left,
				y: evt.clientY - rect.top
			};
		}

		//Get coordinate at each moment
		canvas.addEventListener('mousemove', function(evt) {
			var mousePos = getMousePos(canvas, evt);
			x_souris = mousePos.x;
			y_souris = mousePos.y;
		}, false);

		cy.on('tap', 'node', function(evt){ 
			var node = evt.cyTarget;
			var clic = node.id();
			lien.href = ""; //Reset the link
			if (clic <= 100){ //If we tap a year
				console.log(clic);
			}else{ //If we tap someone
				infobulle.style.height = 100+"px";
				infobulle.style.width = 100+"px";
				infobulle.style.backgroundColor = "#53AAFF";
				infobulle.style.position = "absolute";
				infobulle.style.left = (x_souris+15) + "px";
				infobulle.style.top = (y_souris-100) + "px";
				lien.href = "voir_adherent_public.php?id_adh="+clic; //Make the link of the member's page
				//masq and display the tooltip
				if (infobulle.style.display == 'none')
					infobulle.style.display = 'block';
				else
					infobulle.style.display = 'none';
			}
		});


	}); //end of the jquery's function
</script>