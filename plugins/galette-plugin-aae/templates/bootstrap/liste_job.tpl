
		<form class="form-horizontal" action="liste_job.php" method="post">
			<fieldset>
				<legend>{_T string="Select entreprise"}</legend>
					<div class="row col-sm-offset-1">
						<div class="form-group col-md-3">
							{*Searching poste by Entreprise*}
							<label for="id_entreprise" class="control-label">{_T string="Entreprise:"}</label> <br>
							<select class="form-control" name="id_entreprise" id="id_cycle">
								<option value="" {if $id_entreprise eq ""} selected="selected"{/if} > -- </option>
								{foreach from=$entreprises item=entreprise name=allentreprises}
								<option value="{$entreprise.id_entreprise}" {if $entreprise.id_entreprise eq $id_entreprise} selected="selected"{/if} >{$entreprise.employeur}</option>
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
	{*Display poste founds*}
	{if $nb_postes > 0}
			<table id='table_poste' class="table">
				<tr>
					<td class="left">{$nb_postes} {if $nb_postes != 1}{_T string="Jobs"}{else}{_T string="job"}{/if}</td>
				</tr>
			</table>
			<table id="table_postes" class="listing table">
				<thead>
					<tr>
						<th class="left">
								{_T string="Nom adherent :"}
						</th>
						<th class="left">
								{_T string="Principal activity :"}
						</th>
						<th class="left">
								{_T string="Adresse :"}
						</th>
						<th class="left">
								{_T string="Code postal :"}
						</th>
						<th class="left">
								{_T string="Ville :"}
						</th>
						<th class="left">
								{_T string="Annee de debut:"}
						</th>
						<th class="left">
								{_T string="Annee de fin :"}
						</th>
					</tr>
				</thead>
				<tbody>
		{*Display all poste*}
		{foreach from=$postes item=poste name=allpostes}
					<tr class="{if $smarty.foreach.allmembers.iteration % 2 eq 0}even{else}odd{/if}">
						<td class="nowrap"><a href="voir_adherent_public.php?id_adh={$poste.id_adh}">{$poste.nom_adh}</a></td>
						<td class="nowrap">{$poste.activite_principale}</td>
						<td class="nowrap">{$poste.adresse}</td>
						<td class="nowrap">{$poste.code_postal}</td>
						<td class="nowrap">{$poste.ville}</td>
						<td class="nowrap">{$poste.annee_ini}</td>
						<td class="nowrap">{$poste.annee_fin}</td>
					</tr>
		{/foreach}
				</tbody>
			</table>
			{_T string="If you see an error, please send an email to:"}
			<a href='mailto:{$preferences->pref_email}'>{$preferences->pref_email}</a>

	{else}
		<div id="warningbox">{_T string="No job to show"}</div>
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

				// Sorting plugin Dynatable
				$('#table_postes').dynatable({
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
						searchText: '{_T string="Search:"}',
						perPageText: '{_T string="Show:"}',
						pageText: '{_T string="Pages:"}'
		
					},
					dataset: {
					perPageDefault: 100,
					perPageOptions: [10,20,50,100]}
				});
		
				$(".dynatable-sort-header").css("color","black");

    </script>		
		
