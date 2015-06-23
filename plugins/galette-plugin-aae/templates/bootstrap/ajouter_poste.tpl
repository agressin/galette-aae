<form class="form-horizontal" action="ajouter_poste.php" method="post">
		<legend>{_T string="Job details of"} <strong>{$member->sfullname}</strong></legend>
		<div class="row col-sm-offset-1"> 
			<div class="form-group col-md-4">             
            <p>
                <label class="bline" for="employeur">
                        {_T string="Employeur :"}
                </label>
            </p>
            <p>
                <select name="id_employeur" id="id_employeur" required {if {$vis} eq True} disabled {/if}>
                    <option value="">{_T string=" Choisir un employeur "}</option>
						{foreach from=$entreprises key=k item=v}
							<option value="{$k}" {if {$poste.id_entreprise} eq {$k} } selected="selected"{/if}>{$v.employeur}</option>
						{/foreach}
                </select>
            </p>
            {if {$vis} eq False}
            <p>
                <a href="ajouter_ent.php"><img src="{$template_subdir}images/icon-add.png" align="middle" />   {_T string="Ajouter un employeur"}</a>
            </p>
            {/if}
            <p>
                <label for="type" class="bline">
                    {_T string="Job Type:"}
                </label>
            </p>
            <p>
                <select name="type" id="type" required {if {$vis} eq True} disabled {/if}>
                        <option value="">{_T string=" Select a type "}</option>
                        <option value="Stage" {if {$poste.type} eq "Stage"} selected="selected"{/if}>{_T string="Stage"}</option>
                        <option value="CDD"   {if {$poste.type} eq "CDD"}   selected="selected"{/if}>{_T string="CDD"}</option>
                        <option value="CDI"   {if {$poste.type} eq "CDI"}   selected="selected"{/if}>{_T string="CDI"}</option>
                </select>
            </p>
            <p>
                <label class="bline" for="activite_principale">
                        {_T string="Activité principale"}
                </label>
                <input class="text" type="text" name="activite_principale" id="activite_principale" value="{$poste.activite_principale}" maxlength="100" {if {$vis} eq True} disabled {/if}/>
            </p>
            <p>
                <label class="bline" for="nb_personne_encadre">
                        {_T string="Nombre de personnes encadrées"}
                </label>
                <input class="number" type="number" min="0"  name="nb_personne_encadre" id="nb_personne_encadre" value="{$poste.nb_personne_encadre}" maxlength="100" {if {$vis} eq True} disabled {/if}/>
            </p>
            <p>
                <label class="bline" for="adresse" >
                        {_T string="Adresse"}
                </label>
                <input class="text" type="text" name="adresse" id="adresse" value="{$poste.adresse}" maxlength="100" required {if {$vis} eq True} disabled {/if}/>
            </p>
            <p>
                <label class="bline" for="code_postal">
                        {_T string="Code postal"}
                </label>
                <input class="text" type="text" name="code_postal" id="code_postal" value="{$poste.code_postal}" maxlength="5" required {if {$vis} eq True} disabled {/if}/>
            </p>
            <p>
                <label class="bline" for="ville">
                        {_T string="Ville"}
                </label>
                <input class="text" type="text" name="ville" id="ville" value="{$poste.ville}" maxlength="100" required {if {$vis} eq True} disabled {/if}/>
            </p>
            <p>
                <label class="bline" for="annee_ini">
                        {_T string="Begin"}
                </label><br>
                <input class="date" type="number" name="annee_ini" id="annee_ini" min="1950" max="2100" value="{$poste.annee_ini}" placeholder="{_T string="(yyyy format)"}" maxlength="10" required {if {$vis} eq True} disabled {/if}/>
            </p>
            <p>
                <label class="bline" for="annee_fin">
                        {_T string="End"}
                </label><br>
                <input class="date" type="number" name="annee_fin" id="annee_fin" min="1950" max="2100" value="{$poste.annee_fin}" placeholder="{_T string="empty if actual"}" maxlength="10"  {if {$vis} eq True} disabled {/if}/>
            </p>
           </div>
          </div>

    {if {$vis} eq False}
    <div class="button-container">
        <input type="submit" id="btnsave" value="{_T string="Save"}"/>
        <input type="hidden" name="id_poste" value="{$poste.id_poste}"/>
        <input type="hidden" name="id_adh" value="{$member->id}"/>

        {* Second step validator *}
        <input type="hidden" name="valid" value="1"/>

    </div>
    {/if}
</form>
