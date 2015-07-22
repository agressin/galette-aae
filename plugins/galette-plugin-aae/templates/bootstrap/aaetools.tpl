        <form action="aaetools.php" method="post" enctype="multipart/form-data"> 
        <div class="bigtable">
            <fieldset class="cssform" id="{$mtxt->tlang}">
                <legend class="ui-state-active ui-corner-top">{_T string="AAETools settings"}</legend>
                <p>
                    <label for="prib" class="bline">{_T string="RIB"}</label> 
                    <input type="text" name="pref_rib" id="prib" value="{$AAE_pref->getPref("rib")}" maxlength="255" size="32"/> <span class="exemple">{_T string="(Max 255 characters)"}</span>
                </p>
                <p>
                    <label for="pwebmaster" class="bline">{_T string="Mail webmaster"}</label> 
                    <input type="text" name="pref_webmaster" id="pwebmaster" value="{$AAE_pref->getPref("webmaster")}" maxlength="255" size="32"/> <span class="exemple">{_T string="(Max 255 characters)"}</span>
                </p>
                <p>
                    <label for="pcotis" class="bline">{_T string="Mail cotisation"}</label> 
                    <input type="text" name="pref_cotis" id="pcotis" value="{$AAE_pref->getPref("cotis")}" maxlength="255" size="32"/> <span class="exemple">{_T string="(Max 255 characters)"}</span>
                </p>
            </fieldset>
        </div>
        <div class="button-container">
            <input type="hidden" name="valid" id="valid" value="1"/>
            <input type="submit" value="{_T string="Save"}"/>
        </div>
        </form>
