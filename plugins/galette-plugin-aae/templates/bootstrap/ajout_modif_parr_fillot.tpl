{if $login->isLogged()}	

	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
	<script src="http://cytoscape.github.io/cytoscape.js/api/cytoscape.js-latest/cytoscape.min.js"></script>
	<input type="button" id="buttonparrain" value={_T string="Backtotree"} onclick="self.location.href='arbre.php'"/>
	<form action="ajout_modif_parr_fillot.php" method="post">
		<fieldset class="form-group">
			<div class="row">
				<div class="col-md-4">						
					{*Searching parrains by name*}
					<label>{_T string="Research of parrain"}
						<input type="text" name ="parrain" {if isset($parrain)} value="{$parrain}" {/if}/> 
						<input type="hidden" name ="id_p" {if isset($id_p)} value="{$id_p}" {/if}/> 							
					</label>
					<div id="image">
					</div>
					<button type="submit" class="btn btn-primary"><img src="templates/bootstrap/images/loupe.png" class="img-responsive" />{if $param_selected eq 1} {_T string="Refresh"} {else} {""} {/if}</button>				
					
					{*Display parrains found*}
					{if $nb_parr > 0}
							{*Display all student*}
							{foreach from=$parrains item=p name=allparrains}
								{if ($p.annee_debut >= 2000) && (($p.id_cycle == 51) || ($p.id_cycle == 2))}
										<!-- On ne fait qu'un seul lien du nom+prénom-->
										<div class="row" id="jesuisleparrain"><a href="ajout_modif_parr_fillot.php?id_p={$p.id_adh}&value=ok">{$p.nom_adh}{" "}{$p.prenom_adh}<br \></a></div>
										<script type="text/javascript">
											document.getElementById("jesuisleparrain").addEventListener("click", function(event){ 
																													console.log("blabla");
																													if (value==ok){
																														var img = document.getElementById("image");
																														var im_valid;
																														im_valid = document.createElement('img');
																														im_valid.src = 'templates/bootstrap/images/valid-icon.png';
																														img.appendChild(im_valid);
																														}
																													}, false);
										</script>
								{/if}
							{/foreach}
						</div>
					{/if}
					
				<div class="col-md-4">
					{*Searching fillots by name*}
					<label>{_T string="Research of fillot"}
						<input type="text" name ="fillot" {if isset($fillot)} value="{$fillot}" {/if}/> 								
					</label>
					<button type="submit" class="btn btn-primary"><img src="templates/bootstrap/images/loupe.png" class="img-responsive" />{if $param_selected eq 1} {_T string="Refresh"} {else} {""} {/if}</button>				
					{*Display parrains found*}
					{if $nb_fill > 0}
							{*Display all student*}
							{foreach from=$fillots item=f name=allfillots}
								{if ($f.annee_debut >= 2000) && (($f.id_cycle == 51) || ($f.id_cycle == 2))}
										<!-- On ne fait qu'un seul lien du nom+prénom-->
										<div class="row"><a href="ajout_modif_parr_fillot.php?id_p={$p.id_adh}&id_f={$f.id_adh}">{$f.nom_adh}{" "}{$f.prenom_adh}<br \></a></div>
								{/if}
							{/foreach}
					{/if}
				</div>
					
			</div>
		</fieldset>
	</form>

{/if}