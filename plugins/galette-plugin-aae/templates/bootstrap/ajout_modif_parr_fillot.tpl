{if $login->isLogged()}	

	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
	<script src="http://cytoscape.github.io/cytoscape.js/api/cytoscape.js-latest/cytoscape.min.js"></script>
	<input type="button" value={_T string="Backtotree"} onclick="self.location.href='arbre.php'"/>
	<form action="ajout_modif_parr_fillot.php" method="post">
		<fieldset class="form-group">
			<div class="row">
				<div class="col-md-4">						
					{*Searching parrains by name*}
					<label>{_T string="Research of parrain"}
						<input type="text" name ="parrain" {if isset($smarty.post.parrain)} value="{$smarty.post.parrain}" {/if}/> 								
					</label>
					<button type="submit" class="btn btn-primary"><img src="templates/bootstrap/images/loupe.png" class="img-responsive" />{if $param_selected eq 1} {_T string="Refresh"} {else} {""} {/if}</button>				
					
					{*Display parrains found*}
					{if $nb_parr > 0}
							{*Display all student*}
							{foreach from=$parrains item=p name=allparrains}
								{if ($p.annee_debut >= 2000) && (($p.id_cycle == 51) || ($p.id_cycle == 2))}
										<!-- On ne fait qu'un seul lien du nom+prénom-->
										<div class="row"><a href="ajout_modif_parr_fillot.php?id_p=$p.id_adh">{$p.nom_adh}{" "}{$p.prenom_adh}<br \></a></div>
										<script type="text/javascript">
										
										</script>
								{/if}
							{/foreach}
						</div>
					{/if}
					
				<div class="col-md-4">
					{*Searching fillots by name*}
					<label>{_T string="Research of fillot"}
						<input type="text" name ="fillot" {if isset($smarty.post.fillot)} value="{$smarty.post.fillot}" {/if}/> 								
					</label>
					<button type="submit" class="btn btn-primary"><img src="templates/bootstrap/images/loupe.png" class="img-responsive" />{if $param_selected eq 1} {_T string="Refresh"} {else} {""} {/if}</button>				
					{*Display parrains found*}
					{if $nb_fill > 0}
							{*Display all student*}
							{foreach from=$fillots item=f name=allfillots}
								{if ($f.annee_debut >= 2000) && (($f.id_cycle == 51) || ($f.id_cycle == 2))}
										<!-- On ne fait qu'un seul lien du nom+prénom-->
										<div class="row"><a href="voir_adherent_public.php?id_adh=$f.id_adh">{$f.nom_adh}{" "}{$f.prenom_adh}<br \></a></div>
								{/if}
							{/foreach}
						</div>
					{/if}
				</div>
		</fieldset>
	</form>

{/if}