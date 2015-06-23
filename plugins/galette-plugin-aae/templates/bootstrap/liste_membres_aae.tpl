
		<form class="form-horizontal" action="liste_eleves.php" method="post">
			<fieldset>
				<legend>{_T string="Select cycle and promotion"}</legend>
					<div class="row col-sm-offset-1">
						<div class="form-group col-md-4">
						
							{*Searching student by name*}
							<label>{_T string="Name and/or First Name"}{if !$login->isLogged()} (*) {/if}
							<input type="text" name ="nom_prenom" {if isset($nom_prenom)} value="{$nom_prenom}" {/if}
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
						<div class="form-group col-md-3">
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
						  <button type="submit"  class="btn btn-primary">{if $param_selected eq 1} {_T string="Refresh"} {else} {_T string="Continue"} {/if}</button>
						</div>
					</div>
			</fieldset>
		</form>
	{*Display student founds*}
	{if $nb_eleves > 0}
			<table id='table_eleve' class="table">
				<tr>
					<td class="left">{$nb_eleves} {if $nb_eleves != 1}{_T string="students"}{else}{_T string="student"}{/if}</td>
				</tr>
			</table>
			<table id="table_eleves" class="table table-hover">
				<thead>
					<tr>
						<th class="left">
								{_T string="Name"}
						</th>
						<th class="left">
								{_T string="First Name"}
						</th> 
						<th class="left">
								{_T string="Promotion"}
						</th>
						<th class="left">
								{_T string="Cycle"}
						</th>
					</tr>
				</thead>
				<tbody>
		{*Display all student*}
		{foreach from=$eleves item=eleve name=alleleves}
					<tr class="{if $smarty.foreach.allmembers.iteration % 2 eq 0}even{else}odd{/if}">
						<td class="nowrap username_row">
							{if $login->isLogged()}
								<a href="voir_adherent_public.php?id_adh={$eleve.id_adh}">{$eleve.nom_adh}</a>
							{else}
								{$eleve.nom_adh}
							{/if}
						</td>
						<td class="nowrap">
							{if $login->isLogged()}
								<a href="voir_adherent_public.php?id_adh={$eleve.id_adh}">{$eleve.prenom_adh}</a>
							{else}
								{$eleve.prenom_adh}
							{/if}
						</td>
						<td class="nowrap">
							{if $login->isLogged()}
								<a href="liste_eleves.php?id_cycle={$eleve.id_cycle}&annee_debut={$eleve.annee_debut}">{$eleve.annee_debut}</a>
							{else}
								{$eleve.annee_debut}
							{/if}
						</td>
						<td class="nowrap">{$eleve.nom}</td>
					</tr>
		{/foreach}
				</tbody>
			</table>
			{_T string="If you see an error, please send an email to:"}
			<a href='mailto:{$preferences->pref_email}'>{$preferences->pref_email}</a>

	{else}
		<div id="warningbox">{_T string="No member to show"}</div>
	{/if}

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
					$('input[name="id_cycle_simple[]"]').attr('checked',false);
				});
				
				$('input[name="id_cycle_simple[]"]').live('change', function() {
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

    </script>		
		
