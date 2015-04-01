{if $login->isLogged()}	

		<form class="form-horizontal" action="arbre.php" method="post">
			<fieldset>
				<legend>{_T string="Select name or/and first name"}</legend>
					<div class="row col-sm-offset-1">
						<div class="form-group col-md-4">
						
							{*Searching student by name*}
							<label>{_T string="name or first name"}
								<input type="text" name ="nomprenom" {if isset($smarty.post.nomprenom)} value="{$smarty.post.nomprenom}" {/if}/> 
								<!--<input type="text" name="nom"/>-->
							</label><br>
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
			<table id="table_eleves" class="listing table">
				<thead>
					<tr>
						<th class="left">
								{_T string="Name"}
						</th>
						<th class="left">
								{_T string="First Name"}
						</th> 
						<th class="left">
								{_T string="Promotion:"}
						</th>
						<th class="left">
								{_T string="Cycle:"}
						</th>
					</tr>
				</thead>
				<tbody>
		{*Display all student*}
		{foreach from=$eleves item=eleve name=alleleves}
		{if ($eleve.annee_debut >= 2000) && (($eleve.id_cycle == 51) || ($eleve.id_cycle == 2))}{
					<tr class="{if $smarty.foreach.allmembers.iteration % 2 eq 0}even{else}odd{/if}">
						<td class="nowrap username_row"><a href="voir_adherent_public.php?id_adh={$eleve.id_adh}">{$eleve.nom_adh}</a></td>
						<td class="nowrap"><a href="voir_adherent_public.php?id_adh={$eleve.id_adh}">{$eleve.prenom_adh}</a></td>
						<td class="nowrap"><a href="liste_eleves.php?cycle={$eleve.id_cycle}&year={$eleve.annee_debut}">{$eleve.annee_debut}</td>
						<td class="nowrap">{$eleve.nom}</td>
					</tr>}
		{/if}
		{/foreach}
				</tbody>
			</table>
			{_T string="If you see an error, please send an email to:"}
			<a href='mailto:{$preferences->pref_email}'>{$preferences->pref_email}</a>

	{else}
		<div id="infobox">{_T string="No member to show"}</div>
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
					$('input[name=id_cycle_simple]').attr('checked',false);
				});
				
				$('input[name=id_cycle_simple]').live('change', function() {
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
		
{/if}