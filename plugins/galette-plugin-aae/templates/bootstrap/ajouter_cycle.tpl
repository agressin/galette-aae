<form class="form-horizontal" action="ajouter_cycle.php" method="post">
		<legend> {_T string="Add / edit Cycle"} </legend>

		<div class="row col-sm-offset-1">
			<div class="form-group col-md-6">
        <p>
            <label class="bline" for="nom">
                    {_T string="Name :"}
            </label>
        </p>
        <p>
              <input class="text" type="text" name="nom" id="nom" value="{$cycle.nom}" maxlength="100" required />
        </p>

        <p>
            <label class="bline" for="detail">
                    {_T string="Cycle description"}
            </label>
            <textarea class="text" type="text" name="detail" id="detail" >{$cycle.detail|htmlspecialchars}</textarea>
        </p>
				<div class="button-container">
					<input type="submit" id="btnsave" value="{_T string="Save"}"/>
					<input type="hidden" name="id_cycle" value="{$cycle.id_cycle}"/>

					{* Second step validator *}
					<input type="hidden" name="valid" value="1"/>

				</div>
    	</div>
    </div>
</form>
