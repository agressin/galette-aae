{if $login->isLogged()}
		<form class="form-horizontal" action="liste_eleves.php" method="post">
			<fieldset>
				<legend>{_T string="Select cycle and promotion"}</legend>
					<div class="row col-sm-offset-1">
						<div class="form-group col-md-4">
							{*Searching student by name*}
							<label>{_T string="Name"}
							<input type="text" name="nom"/>
							</label><br>
							{*Searching student by first name*}
							<label>{_T string="First Name"}
							<input type="text" name="prenom"/>
							</label><br>
							{*Searching student by promotion*}
							<label for="annee_debut" class="control-label">{_T string="Promotion:"}</label><br>
							<select class="form-control" name="annee_debut" id="annee_debut">
								<option value="0" {if $param_selected eq 1} selected="selected"{/if} > -- </option>
							</select><br>
						</div>
						<div class="form-group col-md-3">
							{*Searching student by Formation*}
							<label for="id_formation" class="control-label">{_T string="Formation:"}</label> <br>
							<select class="form-control" name="id_formation" id="id_formation">
								<option value="0" selected="selected" > -- </option>
								<option value="51" selected="selected">ING</option>
								<option value="52" selected="selected">G</option>
								<option value="6" selected="selected">DC</option>
							</select>
							{*Searching student by Cycle*}
							<label for="id_cycle" class="control-label">{_T string="or by Cycle:"}</label> <br>
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
	{if $param_selected eq 1}
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
					<tr class="{if $smarty.foreach.allmembers.iteration % 2 eq 0}even{else}odd{/if}">
						<td class="nowrap username_row"><a href="voir_adherent_public.php?id_adh={$eleve.id_adh}">{$eleve.nom_adh}</a></td>
						<td class="nowrap"><a href="voir_adherent_public.php?id_adh={$eleve.id_adh}">{$eleve.prenom_adh}</a></td>
						<td class="nowrap"><a href="promotion.php?cycle={$eleve.id_cycle}&year={$eleve.annee_debut}">{$eleve.annee_debut}</td>
						<td class="nowrap">{$eleve.nom}</td>
					</tr>
		{/foreach}
				</tbody>
			</table>
			{_T string="If you see an error, please send an email to:"}
			<a href='mailto:{$preferences->pref_email}'>{$preferences->pref_email}</a>

	{else}
		<div id="infobox">{_T string="No member to show"}</div>
	{/if}
	{/if} {* $param_selected eq 1 *}

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


				initiateSelects();
				
				$('#id_cycle').on('change', function() {
					$('input[name=id_cycle_simple]').attr('checked',false);
				});
				
				$('input[name=id_cycle_simple]').live('change', function() {
					$('#id_cycle').val('0');
				});


			</script>
	
	
	
	<script type="text/javascript">
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
{else}
		<p>
			{_T string="Please, log on in order to display information."}
		</p>
		<p> <a href="../../index.php" class="btn btn-primary">{_T string="Login"}</a> </p>
		



{/if}		
		