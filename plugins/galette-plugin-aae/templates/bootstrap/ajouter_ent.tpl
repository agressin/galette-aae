	<form class="form-horizontal" action="ajouter_ent.php" method="post">
        <form action="ajouter_ent.php" method="post">
			<fieldset>
				<legend>{_T string="Ajouter un employeur"}</legend>
					<div class="row col-sm-offset-1"> 
					<div class="form-group col-md-4">             
                <p>
                    <label class="bline" for="employeur">
                            {_T string="Nom :"}
                    <input class="text" type="text" name="employeur" id="employeur" value="{$postes.employeur}" maxlength="100" />
                </p>
                <p>
                    <label class="bline" for="employeur_website">
                            {_T string="Website"}
                    </label>
                    <input class="text" type="text" name="employeur_website" id="employeur_website" value="{$postes.employeur_website}" maxlength="100" />
                </p>
                </div>
                </div>
            </fieldset>


        
        <div class="button-container">
            <input type="submit" id="btnsave" value="{_T string="Save"}"/>
            {* Second step validator *}
            <input type="hidden" name="valid" value="1"/>

        </div>
	</form>
</form>

