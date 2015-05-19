{if $login->isLogged()}	
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
	<input type="button" id="buttonparrain" value={_T string="Backtotree"} onclick="self.location.href='arbre.php'"/>
	<form action="ajout_parrain_fillot.php" method="post" id="ajoutlien">
		<fieldset class="form-group">
			<div class="col-md-4">						
				{*Searching parrains by name*}
				<label>{_T string="Research of parrain"}
					<input type="text" name ="parrain" id="p" {if isset($parrain)} value="{$parrain}" {/if}/> 
					<input type="hidden" name ="id_p" id="idp" {if isset($id_p)} value="{$id_p}" {/if}/> 							
				</label>
				{*Searching fillots by name*}
				<label>{_T string="Research of fillot"}
					<input type="text" name ="fillot" id="f" {if isset($fillot)} value="{$fillot}" {/if}/> 	
					<input type="hidden" name ="id_f" id="idf" {if isset($id_f)} value="{$id_f}" {/if}/> 	
				</label>
				<script type="text/javascript">
					var form_lien = document.getElementById("ajoutlien");
					form_lien.addEventListener("button", function(event){
						var champs_lien = form_lien.elements["p"];
						console.log(champs_lien);
						var form_ok = true;
						if (champs_lien.value == ""){
							form_ok = false;
						}
						if(!form_ok){
							event.preventDefault();
							console.log("erreur");
						}
					}, false);
				</script>
				<div id="Execute">
					<input type="submit" id="Exe" value={_T string="Execute"} onclick="self.location.href='ajout_parrain_fillot.php'"/>
				</div>
				<div>
					{$result}
				</div>
			</div>
		</fieldset>
	</form>
{/if}