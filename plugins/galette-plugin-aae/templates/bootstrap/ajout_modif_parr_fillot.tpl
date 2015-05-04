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
				<label>{_T string="Research of fillot"}
					<input type="text" name ="fillot" {if isset($smarty.post.fillot)} value="{$smarty.post.fillot}" {/if}/> 
				</label>					

		</fieldset>
	
		<div class="row">
			<button type="submit" class="btn btn-primary" value="Execute">{if $param_selected eq 1} {else} {""} {/if}</button>
			<!--<input type="button" id="executerequete" value={_T string="Execute"} onclick="self.location.href='ajout_modif_parr_fillot.php'"/>-->
		</div>
		</form>
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