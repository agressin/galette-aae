{if $login->isLogged()}	
		<link rel="stylesheet" type="text/css" href="{$galette_base_path}{$aaetools_path}/templates/bootstrap/arbre.css" /> 
		<form class="form-horizontal" action="arbre.php" method="post">
			<fieldset>
				<div class="form-group col-md-4">						
					{*Searching student by name*}
					<label>{_T string="name or first name"}
						<input type="text" name ="nomprenom" {if isset($smarty.post.nomprenom)} value="{$smarty.post.nomprenom}" {/if}/> 								
					</label>
					<button type="submit" class="btn btn-primary">{if $param_selected eq 1} {_T string="Refresh"} {else} {""} {/if}</button>
						{*Display student founds*}
					{if $nb_eleves > 0}
						<div id="liste-eleves">
							{*Display all student*}
							{foreach from=$eleves item=eleve name=alleleves}
								{if ($eleve.annee_debut >= 2000) && (($eleve.id_cycle == 51) || ($eleve.id_cycle == 2))}
									<tr class="{if $smarty.foreach.allmembers.iteration % 2 eq 0}even{else}odd{/if}">
										<!-- On ne fait qu'un seul lien du nom+prénom-->
										<td class="nowrap username_row"><a href="voir_adherent_public.php?id_adh=$eleve.id_adh">{$eleve.nom_adh}{" "}{$eleve.prenom_adh}<br \></a></td>
									</tr>
								{/if}
							{/foreach}
						</div>
					</div>						
			</fieldset>
		</form>
	{/if}
	<div class="error">
		{_T string="If you see an error, please send an email to:"}
		<a href='mailto:{$preferences->pref_email}'>{$preferences->pref_email}</a>
	</div>
{/if}
