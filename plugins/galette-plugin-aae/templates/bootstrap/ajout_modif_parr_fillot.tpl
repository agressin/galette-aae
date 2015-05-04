{if $login->isLogged()}	
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
	
	<!--ajout du parrain-->
	<div class="row">
	<form action="ajout_modif_parr_fillot.php" method="post">
		<div class="col-md-4 col-md-offset-1">
		<fieldset class="form-group">		
				{*Searching student by name*}
					<label>{_T string="Research of parrain"}
						<input type="text" name ="parrain" {if isset($smarty.post.parrain)} value="{$smarty.post.parrain}" {/if}/> 
					</label>
					<button type="submit" class="btn btn-primary"><img src="templates/bootstrap/images/loupe.png"/>{if $param_selected eq 1} {_T string="Refresh"} {else} {""} {/if}</button>												

		</fieldset>
	</form>
	{*Display student founds*}
	<div class="row">
		{if $nb_parrains > 0}
				{*Display all student*}
				{foreach from=$parrains item=parrain name=allparrains}
					{if ($parrain.annee_debut >= 2000) && (($parrain.id_cycle == 51) || ($parrain.id_cycle == 2))}
						<div class="col-md-4">
							<!-- On ne fait qu'un seul lien du nom+prénom-->
							<div class="row" id="lienparrain"><a href="ajout_modif_parr_fillot.php?id_parrain={$parrain.id_adh}&id_fillot={$fillot.id_adh}">{$parrain.nom_adh}{" "}{$parrain.prenom_adh}<br \></a></div>
							<script type="text/javascript">
								var lien = document.getElementById("lienparrain");
								lien.click(function(event){
									event.preventDefault();
								});
							</script>
						</div>
					{/if}
				{/foreach}
		{/if}

	
	<!--ajout du fillot-->
	<form action="ajout_modif_parr_fillot.php" method="post">
		<fieldset class="form-group">					
			{*Searching student by name*}
				<label>{_T string="Research of fillot"}
					<input type="text" name ="fillot" {if isset($smarty.post.fillot)} value="{$smarty.post.fillot}" {/if}/> 
				</label>
				<button type="submit" class="btn btn-primary"><img src="templates/bootstrap/images/loupe.png"/>{if $param_selected eq 1} {_T string="Refresh"} {else} {""} {/if}</button>												
		</fieldset>
	</form>
	{*Display student founds*}
		<div class="row">
			{if $nb_fillots > 0}
				{*Display all student*}
				{foreach from=$fillots item=fillot name=allfillots}
					{if ($fillot.annee_debut >= 2000) && (($fillot.id_cycle == 51) || ($fillot.id_cycle == 2))}
						<div class="col-md-4">
							<!-- On ne fait qu'un seul lien du nom+prénom-->
							<div class="row"><a href="ajout_modif_parr_fillot.php?id_parrain={$parrain.id_adh}&id_fillot={$fillot.id_adh}">{$fillot.nom_adh}{" "}{$fillot.prenom_adh}<br \></a></div>
							<script type="text/javascript">
								var lien = document.getElementById("lienparrain");
								lien.click(function(event){
									event.preventDefault();
								});
							</script>
						</div>
					{/if}
				{/foreach}
			{/if}
		</div>
		<div class="row">
			<input type="button" id="executerequete" value={_T string="Execute"} onclick="self.location.href='ajout_modif_parr_fillot.php'"/>
		</div>
	</div>

	
		<div class="col-md-4 col-md-offset-5">
			<form action="ajout_modif_parr_fillot.php" method="post">
				<fieldset class="form-group">					
					{*Searching student by name*}
						<label>{_T string="Research of fillot"}
							<input type="text" name ="fillot" {if isset($smarty.post.fillot)} value="{$smarty.post.fillot}" {/if}/> 
						</label>
						<button type="submit" class="btn btn-primary"><img src="templates/bootstrap/images/loupe.png"/>{if $param_selected eq 1} {_T string="Refresh"} {else} {""} {/if}</button>												
						<label for="id_formation" class="control-label">{_T string="Formation:"}</label> <br>
						<input name="choix" value="1" type="radio" {if $smarty.post.choix eq 51}checked {/if}> Parrain <br>
						<input name="choix" value="2" type="radio" {if $smarty.post.choix eq 52} checked {/if}> Fillot   <br>
				</fieldset>
			</form>
		</div>
	</div>
{/if}