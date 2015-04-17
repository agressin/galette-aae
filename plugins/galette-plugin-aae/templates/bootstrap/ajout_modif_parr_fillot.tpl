{if $login->isLogged()}	
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
	<form action="ajout_modif_parr_fillot.php" method="post">
			<fieldset class="form-group">
				<div class="col-md-4">						
					{*Searching student by name*}
					<div class="row">
						<label>{_T string="Research of parrain"}
							<input type="text" name ="parrain" {if isset($smarty.post.nomprenom)} value="{$smarty.post.nomprenom}" {/if}/> 
						</label>
						<button type="submit" class="btn btn-primary"><img src="templates/bootstrap/images/loupe.png"/>{if $param_selected eq 1} {_T string="Refresh"} {else} {""} {/if}</button>												
					</div>
					<div class="row">
						<label>{_T string="Research of fillot"}
							<input type="text" name ="fillot" {if isset($smarty.post.nomprenom)} value="{$smarty.post.nomprenom}" {/if}/>								
						</label>
						<button type="submit" class="btn btn-primary"><img src="templates/bootstrap/images/loupe.png" class="img-responsive" />{if $param_selected eq 1} {_T string="Refresh"} {else} {""} {/if}</button>
					</div>
			</fieldset>
			{*Display student founds*}
			{if $nb_eleves > 0}
					{*Display all student*}
					{foreach from=$eleves item=eleve name=alleleves}
						{if ($eleve.annee_debut >= 2000) && (($eleve.id_cycle == 51) || ($eleve.id_cycle == 2))}
							<div class="col-md-4">
								<!-- On ne fait qu'un seul lien du nom+prénom-->
								<div class="row"><a href="voir_adherent_public.php?id_adh=$eleve.id_adh">{$eleve.nom_adh}{" "}{$eleve.prenom_adh}<br \></a></div>
							</div>
						{/if}
					{/foreach}
				</div>
			{/if}
			<div class="row">
				<input type="submit" id="btnsave" value="{_T string="Search"}"/>
			</div>
		</div>
	</form>
{/if}