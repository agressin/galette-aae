{if !$head_redirect}
{if isset($adh_options)}
        <form action="ajouter_formation_eleve.php" method="post">
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
                <legend class="ui-state-active ui-corner-top">{_T string="Formation details"}</legend>
                <p>
                    <label class="bline" for="id_cycle">{_T string="Cycle:"}</label>
                    <select name="id_cycle" id="id_cycle">
					{foreach $cycles as $k=>$v}
						<option value="{$k}" {if $formation.id_cycle == $k} selected="selected"{/if} >{$v}</option>
					{/foreach}
                    </select>
                </p>
                <p>
                    <label class="bline" for="annee_debut">
                            {_T string="Beging of the formation:"}
                    </label>
                    <input class="text" type="text" name="annee_debut" id="annee_debut" value="{$formation.annee_debut}" maxlength="10" required/>
                    <span class="exemple">{_T string="(yyyy format)"}</span>
                </p>
                <p>
                    <label class="bline" for="annee_fin">
                            {_T string="End of the formation:"}
                    </label>
                    <input class="text" type="text" name="annee_fin" id="annee_fin" value="{$formation.annee_fin}" maxlength="10" required/>
                    <span class="exemple">{_T string="(yyyy format)"}</span>
                </p>
                <p>
                    <label class="bline" for="specialite">
                            {_T string="Speciality:"}
                    </label>
                    <input class="text" type="text" name="specialite" id="specialite" value="{$formation.specialite}" maxlength="100" />
                </p>
            </fieldset>

        </div>
        <div class="button-container">
            <input type="submit" id="btnsave" value="{_T string="Save"}"/>
            <input type="hidden" name="id_form" value="{$formation.id}"/>
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
