<form action="aaetools.php" method="post" enctype="multipart/form-data">
<div class="bigtable">
    <fieldset class="cssform" id="{$mtxt->tlang}">
        <legend class="ui-state-active ui-corner-top">{_T string="AAETools settings"}</legend>
        <p>
            <label for="prib" class="bline">{_T string="RIB"}</label>
            <input type="text" name="pref_rib" id="prib" value="{$AAE_Pref->getPref('rib')}" maxlength="255" size="32"/> <span class="exemple">{_T string="(Max 255 characters)"}</span>
        </p>
        <p>
            <label for="pwebmaster" class="bline">{_T string="Mail webmaster"}</label>
            <input type="text" name="pref_webmaster" id="pwebmaster" value="{$AAE_Pref->getPref('mail_webmaster')}" maxlength="255" size="32"/> <span class="exemple">{_T string="(Max 255 characters)"}</span>
        </p>
        <p>
            <label for="pcotis" class="bline">{_T string="Mail cotisation"}</label>
            <input type="text" name="pref_cotis" id="pcotis" value="{$AAE_Pref->getPref('mail_cotis')}" maxlength="255" size="32"/> <span class="exemple">{_T string="(Max 255 characters)"}</span>
        </p>
        <p>
            <label for="pjob" class="bline">{_T string="Mail job"}</label>
            <input type="text" name="pref_job" id="pjob" value="{$AAE_Pref->getPref("mail_job")}" maxlength="255" size="32"/> <span class="exemple">{_T string="(Max 255 characters)"}</span>
        </p>
        <p>
         <label for="pmontant" class="bline">{_T string="Amount of contributions"}</label>
            <textarea name="pref_montant" id="pmontant"  size="32" rows="5">{$AAE_Pref->getPref('montant_cotis')}</textarea>
        </p>
        <p>
            <label for="pkeyIGN" class="bline">{_T string="API Key for Geoportail"}</label>
            <input type="text" name="api_key_ign" id="pkeyIGN" value="{$AAE_Pref->getPref('api_key_ign')}" maxlength="255" size="32"/> <span class="exemple">{_T string="(Max 255 characters)"}</span>
        </p>
        <p>
            <label for="pkeyGo" class="bline">{_T string="API Key for Google Maps"}</label>
            <input type="text" name="api_key_google" id="pkeyGo" value="{$AAE_Pref->getPref('api_key_google')}" maxlength="255" size="32"/> <span class="exemple">{_T string="(Max 255 characters)"}</span>
        </p>
    </fieldset>
</div>
<div class="button-container">
    <input type="hidden" name="valid" id="valid" value="1"/>
    <input type="submit" value="{_T string="Save"}"/>
</div>
</form>
