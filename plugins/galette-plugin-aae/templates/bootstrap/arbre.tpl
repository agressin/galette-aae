{if $login->isLogged()}	
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
	<script src="http://cytoscape.github.io/cytoscape.js/api/cytoscape.js-latest/cytoscape.min.js"></script>
	{if $login->isStaff() || $login->isAdmin()}
		<input type="button" value={_T string="Add"} onclick="self.location.href='ajout_parrain_fillot.php'"/>
	{/if}
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
						<div class="row"><a href="arbre.php?id_adh={$eleve.id_adh}">{$eleve.nom_adh}{" "}{$eleve.prenom_adh}<br \></a></div>
					</div>
				{/if}
			{/foreach}
		</div>
	{/if}
	<div class="row">
		<div class="col-md-15">
			<div id="cy" style="border: 2px black solid; height:700px; position:relative; background:url(templates/bootstrap/images/Visage_transparent.png); background-repeat:no-repeat; background-position:center center; margin-top:100px;">
				<div id="popup" style="z-index:10; text-align:center; border-radius: 10px 10px 10px 0; border:solid 2px #6B737F;">
					<p style="margin-top:10px;"><a id="lien_profil" href="">Mon profil</a></p>
					<p><a id="lien_famille" href="">Ma famille</a></p>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		{_T string="If you see an error, please send an email to:"}
		<a href='mailto:{$preferences->pref_email}'>{$preferences->pref_email}</a>
	</div>
{/if}
