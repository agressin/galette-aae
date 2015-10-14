
		<form class="form-horizontal" action="carte_eleves.php" method="get" id="afficherCarteMembres">
			<legend>{_T string="Select cycle and promotion"}</legend>
				<div class="row col-xs-offset-1">
					<div class="form-group col-md-4">

						{*Searching student by name*}
						<label>{_T string="Name and/or First Name"}{if !$login->isLogged()} (*) {/if}
						<input class="form-control" type="text" name ="nom_prenom" {if isset($nom_prenom)} value="{$nom_prenom}" {/if}

						</label><br>
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
					  <button type="submit" id="btn-valid" class="btn btn-primary" {if !$login->isLogged()} disabled="disabled" {/if}> {_T string="Continue"} </button>
						{if !$login->isLogged()} * {_T string="Please sign in to access to this functionality"} <br> {/if}
					</div>
				  </div>
				</div>
		</form>

		<div class="details table-hover" id="details" style="display:none;">
			<legend>{_T string="Geographic situation"}</legend>
			<div id="loading" style="display:none;"><img src="../../templates/bootstrap/images/loading.gif" alt="{_T string="Loading..."}" title="{_T string="Loading..."}"></div>
			<div id="warningbox" style="display:none;">{_T string="No detail found"}</div>
			<div id="carteMembres" class="carteMembres" style="display:none;"><div id="popup"></div></div>
		</div>


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

			// Ajout de la mise à jour de la carte :
			var carteMembres = false;
			$('#afficherCarteMembres').submit(function(e) {
				e.preventDefault();

				function lancerCarteMembres(id_adh) {
					var options = {
						idCarte : 'carteMembres',
						idNoResult : 'warningbox',
						idLoading : 'loading',
						center : [46.49839, 3.20801],
						zoom : 6,
						boundsFrance : L.latLngBounds([41.331554,-5.197418],[51.020073,9.516022]),
						hauteurAuto : true,
						scrollAuto : true,
						keyMaps : "{$AAE_Pref->getPref('api_key_google')}",
						keyIGN : "{$AAE_Pref->getPref('api_key_ign')}"
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
				$('#btn-valid').html('{_T string="Refresh"}');

			});

    </script>
