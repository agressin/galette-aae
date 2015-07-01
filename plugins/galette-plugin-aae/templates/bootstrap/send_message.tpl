<form class="form-horizontal" action="send_message.php" method="post">
	<legend>{_T string="Send a message to"} <strong>{$to_adh_name}</strong></legend>
	<div class="row col-sm-offset-1"> 
		<div class="form-group col-md-6">             
		<p>
			<label class="bline" for="subject">
					{_T string="Subject"}
			</label>
			{$sujet}
		</p>
		<p>
			{_T string="Votre message sera précédé du message suivant :"} </br> {$pre_message}
		</p>
		<p>
			<label for="message" class="bline">{_T string="Message:"}</label>
			<textarea name="message" id="message" cols="40" rows="4" required>{$message}</textarea>
		</p>
		</div>
	</div>
     <div class="button-container">
        <input type="submit" id="btnsave" value="{_T string="Send"}"/>
        <input type="hidden" name="id_adh" value="{$id_adh}"/>
    </div>
</form>

