{if $login->isLogged()}	
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
	<script src="http://cytoscape.github.io/cytoscape.js/api/cytoscape.js-latest/cytoscape.min.js"></script>
	<input type="button" value={_T string="Add/Modify"} onclick="self.location.href='ajout_modif_parr_fillot.php'"/>
	<form action="arbre.php" method="post">
		<fieldset class="form-group">
			<div class="row">
				<div class="col-md-4 col-md-offset-8">						
					{*Searching student by name*}
					<label>{_T string="name or first name"}
						<input type="text" name ="nomprenom" {if isset($smarty.post.nomprenom)} value="{$smarty.post.nomprenom}" {/if}/> 								
					</label>
					<button type="submit" class="btn btn-primary"><img src="templates/bootstrap/images/loupe.png" class="img-responsive" />{if $param_selected eq 1} {_T string="Refresh"} {else} {""} {/if}</button>
				</div>	
			</div>
		</fieldset>
	</form>

	{*Display student founds*}
	{if $nb_eleves > 0}
			{*Display all student*}
			{foreach from=$eleves item=eleve name=alleleves}
				{if ($eleve.annee_debut >= 2000) && (($eleve.id_cycle == 51) || ($eleve.id_cycle == 2))}
					<div class="col-md-4 col-md-offset-9">
						<!-- On ne fait qu'un seul lien du nom+prénom-->
						<div class="row"><a href="voir_adherent_public.php?id_adh={$eleve.id_adh}">{$eleve.nom_adh}{" "}{$eleve.prenom_adh}<br \></a></div>
					</div>
				{/if}
			{/foreach}
		</div>
	{/if}
	<div class="row">
		<div class="col-md-15">
			<div id="cy" style="border: 2px black solid; height:700px;"></div>
		</div>
	</div>
	<div class="row">
		{_T string="If you see an error, please send an email to:"}
		<a href='mailto:{$preferences->pref_email}'>{$preferences->pref_email}</a>
	</div>
{/if}
