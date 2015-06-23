<form class="form-horizontal" action="ajouter_ent.php" method="post">
	<fieldset>
		<legend>{_T string="Ajouter un employeur"}</legend>
		
			<div class="form-horizontal col-md-10">
				<div class="form-group col-md-10">
						<label for="employeur" class="col-sm-4 control-label">{_T string="Name"}</label>
						<div class="col-sm-6">
						  <input class="form-control" name="employeur" id="employeur"  value="{$postes.employeur}" maxlength="100" placeholder="{_T string="Entrepise"}">
						</div>
				 </div>
				<div class="form-group col-md-10">
						<label for="employeur_website" class="col-sm-4 control-label">{_T string="Website"}</label>
						<div class="col-sm-6">
						  <input class="form-control" name="employeur_website" id="employeur_website"  value="{$postes.employeur_website}" maxlength="100" placeholder="{_T string="Website"}">
						</div>
				</div>
			</div>
	</fieldset>
			<div class="col-sm-offset-3 col-md-10">
				<div class="form-group">
					  <button type="submit" class="btn btn-primary">{_T string="Save"}</button>
					  <input type="hidden" name="ident" value="1" />
					<a class="btn btn-warning" href="{$galette_base_path}ajouter_poste.php">{_T string="Go back"}</a>
				</div>
			</div>
</form>

