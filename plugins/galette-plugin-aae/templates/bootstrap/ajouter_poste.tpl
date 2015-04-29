	<form class="form-horizontal" action="ajouter_poste.php" method="post">
			<fieldset>
				<legend>{_T string="Jobs details"}</legend>
					<div class="row col-sm-offset-1"> 
					<div class="form-group col-md-4">             
                <p>
                    <label class="bline" for="employeur">
                            {_T string="Employeur"}
                    </label>
                    <input class="text" type="text" name="employeur" id="employeur" value="{$postes.employeur}" maxlength="100" />
                </p>
                <p>
                    <label class="bline" for="activite_principale">
                            {_T string="Activité principale"}
                    </label>
                    <input class="text" type="text" name="activite_principale" id="activite_principale" value="{$postes.activite_principale}" maxlength="100" />
                </p>
                <p>
                    <label class="bline" for="encadrement">
                            {_T string="Encadrement"}
                    </label>
                    <input class="text" type="text" name="encadrement" id="encadrement" value="{$postes.encadrement}" maxlength="100" />
                </p>
                <p>
                    <label class="bline" for="nb_personne_encadre">
                            {_T string="Nombre de personnes encadrées"}
                    </label>
                    <input class="number" type="number" min="0"  name="nb_personne_encadre" id="nb_personne_encadre" value="{$postes.nb_personne_encadre}" maxlength="100" />
                </p>
                <p>
                    <label class="bline" for="adresse">
                            {_T string="Adresse"}
                    </label>
                    <input class="text" type="text" name="adresse" id="adresse" value="{$postes.adresse}" maxlength="100" />
                </p>
                <p>
                    <label class="bline" for="employeur_website">
                            {_T string="Site internet"}
                    </label>
                    <input class="text" type="text" name="employeur_website" id="employeur_website" value="{$postes.employeur_website}" maxlength="100" />
                </p>
                <p>
                    <label class="bline" for="annee_ini">
                            {_T string="Begin"}
                    </label>
                    <input class="date" type="date" name="annee_ini" id="annee_ini" value="{$postes.annee_ini}" maxlength="10" required/>
                    <span class="exemple">{_T string="(yyyy format)"}</span>
                </p>
                <p>
                    <label class="bline" for="annee_fin">
                            {_T string="End"}
                    </label>
                    <input class="date" type="date" name="annee_fin" id="annee_fin" value="{$postes.annee_fin}" maxlength="10" required/>
                    <span class="exemple">{_T string="(yyyy format)"}</span>
                </p>
                </div>
                </div>
            </fieldset>

        
        <div class="button-container">
            <input type="submit" id="btnsave" value="{_T string="Save"}"/>
            <input type="hidden" name="id_form" value="{$postes.id_poste}"/>
            {* Second step validator *}
            <input type="hidden" name="valid" value="1"/>

        </div>
	</form>
