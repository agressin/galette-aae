{if $login->isLogged()}	
		<link rel="stylesheet" type="text/css" href="{$galette_base_path}{$aaetools_path}/templates/bootstrap/arbre.css" /> 
		<form class="form-horizontal" action="arbre.php" method="post">
			<fieldset class="champ-texte-loupe">
				<legend>{_T string="Select name or/and first name"}</legend>
					<div class="row col-sm-offset-1">
						<div class="form-group col-md-4">
						
							{*Searching student by name*}
							<label>{_T string="name or first name"}
								<input type="text" name ="nomprenom" {if isset($smarty.post.nomprenom)} value="{$smarty.post.nomprenom}" {/if}/> 								
							</label>
							<button type="submit" class="btn btn-primary">{if $param_selected eq 1} {_T string="Refresh"} {else} {""} {/if}</button>
						</div>						
					</div>
			</fieldset>
		</form>
	{*Display student founds*}
	{if $nb_eleves > 0}
		{*Display all student*}
		<div class= "liste-eleves">
			{foreach from=$eleves item=eleve name=alleleves}
			{if ($eleve.annee_debut >= 2000) && (($eleve.id_cycle == 51) || ($eleve.id_cycle == 2))}{
				<tr class="{if $smarty.foreach.allmembers.iteration % 2 eq 0}even{else}odd{/if}">
					<td class="nowrap username_row"><a href="voir_adherent_public.php?id_adh={$eleve.id_adh}">{$eleve.nom_adh}</a></td>
					<td class="nowrap"><a href="voir_adherent_public.php?id_adh={$eleve.id_adh}">{$eleve.prenom_adh}</a></td>
				</tr>}
			{/if}
			{/foreach}
		</div>
		{_T string="If you see an error, please send an email to:"}
		<a href='mailto:{$preferences->pref_email}'>{$preferences->pref_email}</a>

	{else}
		<div id="infobox">{_T string="No member to show"}</div>
	{/if}
{/if}