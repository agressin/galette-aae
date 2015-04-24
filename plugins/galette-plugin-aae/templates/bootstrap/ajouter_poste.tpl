{if !$head_redirect}
{if isset($adh_options)}
        <form action="ajouter_poste.php" method="post">
        <div class="bigtable">
            <p>{_T string="NB : The mandatory fields are in"} <span class="required">{_T string="red"}</span></p>
            <fieldset class="cssform">
                <legend class="ui-state-active ui-corner-top">{_T string="Select a member "}</legend>
                <p>
                    <label for="id_adh" class="bline">{_T string="Member:"}</label>
                    <select name="id_adh" id="id_adh"{if isset($disabled.id_adh)} {$disabled.id_adh}{/if}>
                        {if $adh_selected eq 0}
                        <option value="">{_T string="-- select a name --"}</option>
                        {/if}
                        {foreach $adh_options as $k=>$v}
                            <option value="{$k}"{if $adh_selected == $k} selected="selected"{/if}>{$v}</option>
                        {/foreach}
                    </select>
                </p>
            </fieldset>
            <fieldset class="cssform">
                <legend class="ui-state-active ui-corner-top">{_T string="Jobs details"}</legend>
                
                <p>
                    <label class="bline" for="employeur">
                            {_T string="Speciality:"}
                    </label>
                    <input class="text" type="text" name="employeur" id="employeur" value="{$postes.employeur}" maxlength="100" />
                </p>
                <p>
                    <label class="bline" for="activite_principale">
                            {_T string="Speciality:"}
                    </label>
                    <input class="text" type="text" name="activite_principale" id="activite_principale" value="{$postes.activite_principale}" maxlength="100" />
                </p>
                <p>
                    <label class="bline" for="encadrement">
                            {_T string="Speciality:"}
                    </label>
                    <input class="text" type="text" name="encadrement" id="encadrement" value="{$postes.encadrement}" maxlength="100" />
                </p>
                <p>
                    <label class="bline" for="nb_personne_encadre">
                            {_T string="Speciality:"}
                    </label>
                    <input class="text" type="text" name="nb_personne_encadre" id="nb_personne_encadre" value="{$postes.nb_personne_encadre}" maxlength="100" />
                </p>
                <p>
                    <label class="bline" for="adresse">
                            {_T string="Speciality:"}
                    </label>
                    <input class="text" type="text" name="adresse" id="adresse" value="{$postes.adresse}" maxlength="100" />
                </p>
                <p>
                    <label class="bline" for="employeur_website">
                            {_T string="Speciality:"}
                    </label>
                    <input class="text" type="text" name="employeur_website" id="employeur_website" value="{$postes.employeur_website}" maxlength="100" />
                </p>
                <p>
                    <label class="bline" for="annee_ini">
                            {_T string="Beging of the formation:"}
                    </label>
                    <input class="text" type="text" name="annee_ini" id="annee_ini" value="{$postes.annee_ini}" maxlength="10" required/>
                    <span class="exemple">{_T string="(yyyy format)"}</span>
                </p>
                <p>
                    <label class="bline" for="annee_fin">
                            {_T string="End of the formation:"}
                    </label>
                    <input class="text" type="text" name="annee_fin" id="annee_fin" value="{$postes.annee_fin}" maxlength="10" required/>
                    <span class="exemple">{_T string="(yyyy format)"}</span>
                </p>
                
            </fieldset>

        </div>
        <div class="button-container">
            <input type="submit" id="btnsave" value="{_T string="Save"}"/>
            <input type="hidden" name="id_form" value="{$postes.id_poste}"/>
            {* Second step validator *}
            <input type="hidden" name="valid" value="1"/>

        </div>
        </form>
{else} {* No members *}
    <div class="center" id="warningbox">
        <h3>{_T string="No member registered!"}</h3>
        <p>
            {_T string="Unfortunately, there is no member in your database yet,"}
            <br/>
            <a href="ajouter_adherent.php">{_T string="please create a member"}</a>
        </p>
    </div>
{/if}
{/if}
