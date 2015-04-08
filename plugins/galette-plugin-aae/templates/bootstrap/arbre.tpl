{if $login->isLogged()}	

		<form class="form-horizontal" action="arbre.php" method="post">
			<fieldset>
				<legend>{_T string="Select name or/and first name"}</legend>
					<div class="row col-sm-offset-1">
						<div class="form-group col-md-4">
						
							{*Searching student by name*}
							<label>{_T string="name or first name"}
								<input type="text" name ="nomprenom" {if isset($smarty.post.nomprenom)} value="{$smarty.post.nomprenom}" {/if}/> 
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
			<!--<table id='table_eleve' class="table">
				<tr>
					<td class="left">{$nb_eleves} {if $nb_eleves != 1}{_T string="students"}{else}{_T string="student"}{/if}</td>
				</tr>
			</table>-->
			<table id="table_eleves" class="listing table">
				<thead>
					<tr>
						<th class="left">
								{_T string="Name"}
						</th>
						<th class="left">
								{_T string="First Name"}
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
{/if}