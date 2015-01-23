{if !$head_redirect}
        <form action="send_message.php" method="post">
        <div class="bigtable">
            <p>{_T string="NB : The mandatory fields are in"} <span class="required">{_T string="red"}</span></p>
            <fieldset class="cssform">
                <legend class="ui-state-active ui-corner-top">{_T string="Send a message to"} {$to_adh_name}</legend>
                <div>
                <p>
                    <label for="subject" class="bline">{_T string="Subject:"}</label>
                    <input type="text" name="subject" id="subject" value="{$subject}"  maxlength="150" required/>
                </p>
                <p>
                	{_T string="Votre message sera précédé du message suivant :"} </br> {$pre_message}
                </p>
                <p>
                    <label for="message" class="bline">{_T string="Message:"}</label>
                    <textarea name="message" id="message" cols="40" rows="4" required>{$message}</textarea>
                </p>
                </div>
			</fieldset>
        <div class="button-container">
            <input type="submit" id="btnsave" value="{_T string="Send"}"/>
            <input type="hidden" name="id_adh" value="{$id_adh}"/>
        </div>
        </form>
{/if}
