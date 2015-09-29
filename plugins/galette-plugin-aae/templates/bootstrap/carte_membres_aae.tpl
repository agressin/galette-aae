
		<form class="form-horizontal" action="carte_eleves.php" method="get" id="afficherCarteMembres">
			<legend>{_T string="Select cycle and promotion"}</legend>
				<div class="row col-xs-offset-1">
					<div class="form-group col-md-4">
					
						{*Searching student by name*}
						<label>{_T string="Name and/or First Name"}{if !$login->isLogged()} (*) {/if}
						<input class="form-control" type="text" name ="nom_prenom" {if isset($nom_prenom)} value="{$nom_prenom}" {/if}
						{if !$login->isLogged()} DISABLED {/if} /> 
						</label><br>
						{if !$login->isLogged()} * {_T string="Please sign in to access search by name"} <br> {/if}
						{*Searching student by promotion*}
						<label for="annee_debut" class="control-label">{_T string="Promotion"}</label><br>
						<select class="form-control" name="annee_debut" id="annee_debut" >
							<option value="0" {if $param_selected eq 1} selected="selected"{/if} >--</option>
						</select><br>
					</div>
					<div class="form-group col-md-1"></div>
					<div class="form-group col-md-4">
						{*Searching student by Formation*}
						<label for="id_cycle_simple" class="control-label">{_T string="Formation"}</label> <br>
							<input name="id_cycle_simple[]" id="IT"   value="IT"   type="checkbox" {if in_array("IT",$id_cycle_simple)}   checked {/if}> <label for="IT"> Ingénieur </label>  <br>
							<input name="id_cycle_simple[]" id="G"    value="G"    type="checkbox" {if in_array("G",$id_cycle_simple)}    checked {/if}> <label for="G"> Géomètre  </label> <br>
							<input name="id_cycle_simple[]" id="DC"   value="DC"   type="checkbox" {if in_array("DC",$id_cycle_simple)}   checked {/if}> <label for="DC"> Dessinateur </label> <br>
							<input name="id_cycle_simple[]" id="LPRO" value="LPRO" type="checkbox" {if in_array("LPRO",$id_cycle_simple)} checked {/if}> <label for="LPRO"> Licence Pro </label> <br>
						{*Searching student by Cycle*}
						<label for="id_cycle" class="control-label">{_T string="or by Cycle"}</label> <br>
						<select class="form-control" name="id_cycle" id="id_cycle">
							<option value="0" {if $param_selected eq 1} selected="selected"{/if} > -- </option>
							{foreach from=$cycles item=cycle name=allcycles}
							<option value="{$cycle.id_cycle}" {if $cycle.id_cycle eq $id_cycle} selected="selected"{/if} >{$cycle.nom}</option>
							{/foreach}
						</select>
					</div>
				</div>
				
				<div class="row col-xs-offset-2">
				  <div class="form-group">
					<div class=" col-sm-10">
					 <input type="hidden" name="valid" id="valid" value="1">
					  <button type="submit"  class="btn btn-primary">
					  	{if $param_selected} {_T string="Refresh"} {else} {_T string="Continue"} {/if}
					  </button>
					</div>
				  </div>
				</div>
		</form>

		<div class="details table-hover">
			<legend>{_T string="Geographic situation"}</legend>
			<div id="loading"><img src="../../templates/bootstrap/images/loading.gif" alt="{_T string="Loading..."}" title="{_T string="Loading..."}"></div>
			<div id="noResult">{_T string="No detail found"}</div>
			<div id="carteMembres" class="carteMembres" style="display:none;"><div id="popup"></div></div>
		</div>

		<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.3/leaflet.css" />
		<link rel="stylesheet" href="css/style_cartes.css" type="text/css" />
		<link rel="stylesheet" type="text/css" href="http://leaflet.github.io/Leaflet.markercluster/dist/MarkerCluster.css">
		<link rel="stylesheet" type="text/css" href="http://leaflet.github.io/Leaflet.markercluster/dist/MarkerCluster.Default.css">
		<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.3/leaflet.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/proj4js/2.3.6/proj4.js"></script>
		<script type="text/javascript" src="http://leaflet.github.io/Leaflet.markercluster/dist/leaflet.markercluster-src.js"></script>
		<script type="text/javascript" src="js/CarteMembres.js"></script>

		<script type="text/javascript">

			var initiateSelects = function() {
				var myDate = new Date();
			
				var year = myDate.getFullYear();
				var curYear = "{$annee_debut}";
				//curYear = (curYear=="")?year-1:curYear;

				for(var i = year-1; i >= 1941; i--){
					opt='<option value="'+ i +'">'+ i +'</option>';
					if(curYear==i)
						opt='<option value="'+ i +'" selected="selected" >'+ i +'</option>';
					$('#annee_debut').append(opt);
				};
			};

			// Set values from id_cycle to 0 if id_cycle_simple is selected
			initiateSelects();
			
			$('#id_cycle').on('change', function() {
				$('input[name=id_cycle_simple]').attr('checked',false);
			});
			
			$('input[name=id_cycle_simple]').on('change', function() {
				$('#id_cycle').val('0');
			});
			
			// Sorting plugin Dynatable
			$('#table_eleves').dynatable({
				features: {
					paginate: true,
					sort: true,
					pushState: false,
					search: false,
					recordCount: false,
					perPageSelect: true
				},
				inputs: {
					paginationPrev: '{_T string="Previous"}',
					paginationNext: '{_T string="Next"}',
					searchText: '{_T string="Search"}',
					perPageText: '{_T string="Show"}',
					pageText: '{_T string="Pages"}'
	
				},
				dataset: {
				perPageDefault: 100,
				perPageOptions: [10,20,50,100]}
			});
	
			$(".dynatable-sort-header").css("color","black");

			// Ajout de la mise à jour de la carte :
			var carteMembres = false;
			$('#afficherCarteMembres').submit(function(e) {
				e.preventDefault();

				function lancerCarteMembres(id_adh) {
					var options = {
						idCarte : 'carteMembres',
						idNoResult : 'noResult',
						idLoading : 'loading',
						center : [46.49839, 3.20801],
						zoom : 6,
						boundsFrance : L.latLngBounds([41.331554,-5.197418],[51.020073,9.516022]),
						hauteurAuto : true,
						scrollAuto : true/*,
						keyMaps : 'AIzaSyCpMXa7ZJn2L7WebriShk4v8NSU4n3N-s8',
						keyIGN : '3s9er40tvaqliky3tswb38l2'*/
					};

					if (carteMembres === false) {
						carteMembres = new CarteMembres(options);
					}
					var paraAjax = {
						type : 'GET',
						url : 'ajax_carte.php?type=multi&' + $('#afficherCarteMembres').serialize()
					};

					carteMembres.ajax(paraAjax);
				}

				lancerCarteMembres({$member->id});

			});

    </script>		
		
