{if !$head_redirect}
        <form action="ajouter_offre.php" method="post">
        <div class="bigtable">
            <p>{_T string="NB : The mandatory fields are in"} <span class="required">{_T string="red"}</span></p>
            <fieldset class="cssform">
                <legend class="ui-state-active ui-corner-top">{_T string="Offer general "}</legend>
                <div>
                {if $haveRights}
                <p>
                    <label for="valid" class="bline">{_T string="Validation:"}</label>
                    <input type="checkbox" name="valid" id="valid" {if $offer.valide} checked  {/if}/>
                </p>
                {/if}
                <p>
                    <label for="titre_offre" class="bline">{_T string="Title:"}</label>
                    <input type="text" name="titre_offre" id="titre_offre" value="{$offer.titre}" maxlength="150" required/>
                </p>
                <p>
                    <label for="nom_contact" class="bline">{_T string="Contact Name:"}</label>
                    <input type="text" name="nom_contact" id="nom_contact" value="{$offer.nom_contact}" maxlength="150"/>
                </p>
                <p>
                    <label for="mail_contact" class="bline">{_T string="Contact Mail:"}</label>
                    <input type="text" name="mail_contact" id="mail_contact" value="{$offer.mail_contact}" maxlength="150" required/>
                </p>
                <p>
                    <label for="tel_contact" class="bline">{_T string="Contact Phone number:"}</label>
                    <input type="text" name="tel_contact" id="tel_contact" value="{$offer.tel_contact}" maxlength="150"/>
                </p>
                <p>
                    <label for="type_offre" class="bline">{_T string="Offer Type:"}</label>
                    <select name="type_offre" id="type_offre" required>
                    		<option value="">{_T string=" select a type "}</option>
                            <option value="Stage" {if $offer.type_offre eq "Stage"} selected="selected"{/if}>{_T string="Stage"}</option>
                            <option value="CDD"   {if $offer.type_offre eq "CDD"}   selected="selected"{/if}>{_T string="CDD"}</option>
                            <option value="CDI"   {if $offer.type_offre eq "CDI"}   selected="selected"{/if}>{_T string="CDI"}</option>
                    </select>
                </p>
                <p>
                    <label for="desc_offre" class="bline">{_T string="Description:"}</label>
                    <textarea name="desc_offre" id="desc_offre" cols="40" rows="4" required> {utf8_encode($offer.desc_offre)} </textarea>
                </p>
                </div>
			</fieldset>
			<fieldset class="cssform">
                <legend class="ui-state-active ui-corner-top">{_T string="Offer detail "}</legend>
                <div>
                <p>
                    <label for="organisme" class="bline">{_T string="Organisme:"}</label>
                    <input type="text" name="organisme" id="organisme" value="{utf8_encode($offer.organisme)}" maxlength="150"/>
                </p>
                <p>
                    <label for="localisation" class="bline">{_T string="Localisation:"}</label>
                    <textarea name="localisation" id="localisation" cols="40" rows="4"> {utf8_encode($offer.localisation)} </textarea>
                </p>
                <p>
                    <label for="site" class="bline">{_T string="Site:"}</label>
                    <input type="text" name="site" id="site" value="{$offer.site}" maxlength="150"/>
                </p>
                <p>
                    <label for="date_fin" class="bline">{_T string="Available until:"}</label>
                    <input type="text" name="date_fin" id="date_fin" value="" maxlength="10"/>
                    <span class="exemple">{_T string="(yyyy-mm-dd format)"}</span>
                </p>
                <p>
                    <label for="mots_cles" class="bline">{_T string="Key word(s):"}</label>
                    <input type="text" name="mots_cles" id="mots_cles" value="{utf8_encode($offer.mots_cles)}" maxlength="150"/>
                </p>
                <p>
                    <label for="duree" class="bline">{_T string="Duration:"}</label>
                    <input type="text" name="duree" id="duree" value="{$offer.duration}" maxlength="150"/>
                    <span class="exemple">{_T string="(e.g., 3 months or 2 years; leave empty for permanent job)"}</span>
                </p>
                <p>
                    <label for="date_debut" class="bline">{_T string="Beginning:"}</label>
                    <input type="text" name="date_debut" id="date_debut" value="{$offer.date_debut}" maxlength="10"/>
                    <span class="exemple">{_T string="(yyyy-mm-dd format)"}</span>
                </p>
                <p>
                    <label for="remuneration" class="bline">{_T string="Salary:"}</label>
                    <input type="text" name="remuneration" id="remuneration" value="{$offer.remuneration}" maxlength="150"/>
                </p>
                <p>
                    <label for="cursus" class="bline">{_T string="Cursus:"}</label>
                    <input type="text" name="cursus" id="cursus" value="{$offer.cursus}" maxlength="150"/>
                </p>
                <p>
                    <label for="tech_majeures" class="bline">{_T string="Tech_majeures:"}</label>
                    <input type="text" name="tech_majeures" id="tech_majeures" value="{$offer.tech_majeures}" maxlength="150"/>
                </p>
			</div>
        </fieldset>
        <div class="button-container">
            <input type="submit" id="btnsave" value="{_T string="Save"}"/>
            <input type="hidden" name="id_offre" value="{$offer.id}"/>
            {* Second step validator <input type="hidden" name="valid" value="1"/> *}
            

        </div>
        </form>
        <script type="text/javascript">
            $(function() {

                _collapsibleFieldsets();

                $('#Date_fin').datepicker({
                    changeMonth: true,
                    changeYear: true,
                    showOn: 'button',
                    buttonImage: '{$template_subdir}images/calendar.png',
                    buttonImageOnly: true,
                    maxDate: '-0d',
                    yearRange: 'c-100:c+0'
                });
                
                $('#Date_debut').datepicker({
                    changeMonth: true,
                    changeYear: true,
                    showOn: 'button',
                    buttonImage: '{$template_subdir}images/calendar.png',
                    buttonImageOnly: true,
                    maxDate: '-0d',
                    yearRange: 'c-100:c+0'
                });
                
            });
        </script>
{/if}
