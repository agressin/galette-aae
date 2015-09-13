
		<form class="form-horizontal" action="liste_job.php" method="post">
			<fieldset>
				<legend>{_T string="Select entreprise"}</legend>
					<div class="row col-sm-offset-1">
						<div class="form-group col-md-3">
							{*Searching poste by Entreprise*}
							<label for="id_entreprise" class="control-label">{_T string="Entreprise"}</label> <br>
							<select class="form-control" name="id_entreprise" id="id_cycle">
								<option value="" {if $id_entreprise eq ""} selected="selected"{/if} > -- {_T string="all"} -- </option>
								{foreach $entreprises  as $entreprise}
								<option value="{$entreprise.id_entreprise}" {if $entreprise.id_entreprise eq $id_entreprise} selected="selected"{/if} >{$entreprise.employeur}</option>
								{/foreach}
							</select>
						</div>
					</div>
					
					<div class="row col-xs-offset-2">
					  <div class="form-group">
						<div class=" col-sm-10">
						  <button type="submit"  class="btn btn-primary">{if $param_selected eq 1} {_T string="Refresh"} {else} {_T string="Continue"} {/if}</button>
						  <input type="hidden" name="valid" value="1"/>
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
			<table id="table_postes" class="listing table  table-hover details">
				<thead>
					<tr>
						<th class="left">
								{_T string="Member name"}
						</th>
						<th class="left">
								{_T string="Entreprise"}
						</th>
						<th class="left">
								{_T string="Principal activity"}
						</th>
						{*
						<th class="left">
								{_T string="Address"}
						</th>
						<th class="left">
								{_T string="Postal code"}
						</th>
						*}
						<th class="left">
								{_T string="City"}
						</th>
						<th class="left">
								{_T string="Begin year"}
						</th>
						<th class="left">
								{_T string="Year of end"}
						</th>
						<th class="left">
								{_T string="Details"}
						</th>
					</tr>
				</thead>
				<tbody>
		{*Display all poste*}
		{foreach $postes as $poste}
					<tr class="{if $smarty.foreach.allmembers.iteration % 2 eq 0}even{else}odd{/if}">
						<td class="nowrap"><a href="voir_adherent_public.php?id_adh={$poste.id_adh}">{$poste.nom_adh}</a></td>
						<td class="nowrap">
						<a href="liste_job.php?id_entreprise={$poste.id_entreprise}">
						{foreach $entreprises  as $entreprise}
								{if $entreprise.id_entreprise eq $poste.id_entreprise} {$entreprise.employeur} {/if}
						{/foreach}
						</a>
						</td>
						<td class="nowrap">{$poste.activite_principale}</td>
						{*
						<td class="nowrap">{$poste.adresse}</td>
						<td class="nowrap">{$poste.code_postal}</td>
						*}
						<td class="nowrap">{$poste.ville}</td>
						<td class="nowrap">{$poste.annee_ini}</td>
						<td class="nowrap">{if $poste.annee_fin eq 0}{_T string="present"}{else}{$poste.annee_fin}{/if}</td>
						<td>
							<a href="" data-toggle="modal" data-target=".bs-example-modal-lg-{$poste.id_poste}"><img src="{$template_subdir}images/icon-fiche.png" align="middle" /></a>
						</td>
					</tr>
		{/foreach}
				</tbody>
			</table>
			{_T string="If you see an error, please send an email to:"}
			<a href='mailto:{$AAE_Pref->getPref('mail_webmaster')}'>{$AAE_Pref->getPref('mail_webmaster')}</a>



		{foreach $postes as $poste}
			<div class="modal fade bs-example-modal-lg-{$poste.id_poste}" tabindex="-1" role="dialog">
			  <div class="modal-dialog modal-lg">
				<div class="modal-content">
					<table class="table">
						<tr>
				    		<td><h2><a href="voir_adherent_public.php?id_adh={$poste.id_adh}">{$poste.nom_adh}</a></h2></td>
				    	</tr>    
				    	<tr>
				    		<td><h4>Période</h4> </td>
				    		<td> {if $poste.annee_fin eq $poste.annee_ini}
				    				{$poste.annee_fin}
				    			{else}
				    				{$poste.annee_ini}-{if $poste.annee_fin eq 0}{_T string="present"}{else}{$poste.annee_fin}{/if}
				    			{/if}
				    		</td>
				    	</tr>
				    	<tr>
				    		<td><h4>Employeur</h4></td>
				    		<td> <a href="liste_job.php?id_entreprise={$poste.id_entreprise}">{foreach $entreprises  as $entreprise}{if $entreprise.id_entreprise eq $poste.id_entreprise} {$entreprise.employeur} {/if}{/foreach}</a></td>
				    	</tr>
				    	<tr>
				    		<td><h4>Site internet</h4></td>
				    		<td> <a href="{if strpos($poste.website,"http") !==0}http://{/if}{$poste.website}" target="_blank">{$poste.website}</a></td>
				    	</tr>
				    	<tr>
				    		<td><h4>Adresse</h4></td>
				    		<td>{$poste.adresse} {$poste.code_postal} {$poste.ville}</td>
				    	</tr> 
				    	<tr>
				    		<td><h4>Type de contrat</h4></td>
				    		<td>{$poste.type}</td>
				    	</tr> 
				    	<tr>
				    		<td><h4>Intitulé du poste</h4></td>
				    		<td>{$poste.activite_principale}</td>
				    	</tr>
				    </table> 
				</div>
			  </div>
			</div>
		{/foreach}
		
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
		
