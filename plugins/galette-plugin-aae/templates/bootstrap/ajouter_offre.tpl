<form class="form-horizontal" action="ajouter_poste.php" method="post">
  </div>
		{if $haveRights}
		<p>
			<label class="control-label" for="valid" class="bline">{_T string="Validation"}</label>
			<input type="checkbox" name="valid" id="valid" {if $offer.valide} checked  {/if}/>
		</p>
		{/if}
    <legend> {_T string="Offer general"}  </legend>
    <div class="row col-sm-offset-1">
      <div class="form-group col-md-6">
        <p>
          <label for="organisme" class="bline">{_T string="Employeur :"}</label>
        </p>
        <p>
          <select class="selectpicker" name="id_employeur" id="id_employeur" required {if {$vis} eq True} disabled {/if} title="{_T string="Choisir un employeur"}" data-size="auto">
            {foreach from=$entreprises key=k item=v}
              <option value="{$k}" {if {$offer.id_entreprise} eq {$k} } selected="selected"{/if}>{$v.employeur}</option>
            {/foreach}
          </select>
        </p>
        <p>
          <a href="ajouter_ent.php"><img src="{$template_subdir}images/icon-add.png" align="middle" />{_T string="Ajouter un employeur"}</a>
        </p>
        <p>
          <label for="titre_offre">{_T string="Title"}</label>
        </p>
        <p>
          <input type="text" name="titre_offre" id="titre_offre" value="{$offer.titre}" maxlength="150" required/>
        </p>
        <p>
          <label for="type_offre" class="bline">{_T string="Offer Type"}</label>
        </p>
        <p>
          <select  class="selectpicker" name="type_offre" id="type_offre" required data-size="auto">
            <option value="">{_T string=" select a type "}</option>
            <option value="Stage" {if $offer.type_offre eq "Stage"} selected="selected"{/if}>{_T string="Stage"}</option>
            <option value="CDD"   {if $offer.type_offre eq "CDD"}   selected="selected"{/if}>{_T string="CDD"}</option>
            <option value="CDI"   {if $offer.type_offre eq "CDI"}   selected="selected"{/if}>{_T string="CDI"}</option>
          </select>
        </p>
        <p>
          <label class="bline" for="domaines">{_T string="Skills"}</label>
        </p>
        <p>
          <select class="selectpicker" name="domaines[]" id="domaines" {if {$vis} eq True} disabled {/if} multiple title="{_T string="Select one or more skill(s)"}" data-size="auto">
            {foreach from=$domaines key=k item=d}
              <option value="{$k}"{if in_array($k, $offer.domaines)} selected="selected"{/if}>{$d|htmlspecialchars}</option>
            {/foreach}
          </select>
        </p>
        <p>
          <label for="desc_offre" class="bline">{_T string="Description"}</label>
        </p>
        <p>
          <textarea name="desc_offre" id="desc_offre" cols="40" rows="4" required>{$offer.desc_offre}</textarea>
        </p>
        <p>
          <label for="localisation" class="bline">{_T string="Localisation"}</label>
        </p>
        <p>
          <textarea name="localisation" id="localisation" cols="40" rows="4">{$offer.localisation}</textarea>
        </p>
        <p>
          <label for="date_fin" class="bline">{_T string="Available until"}</label>
        </p>
        <p>
          <input class="past-date-pick" type="text" name="date_fin" id="date_fin" value="{$offer.date_fin}" placeholder="{_T string="(dd/mm/yyyy format)"}" maxlength="10"/>
        </p>
       </div>
    </div>
		<legend> {_T string="Contact information"}  </legend>
		<div class="row col-sm-offset-1">
			<div class="form-group col-md-6">
        <p>
          <label for="nom_contact" class="bline">{_T string="Name"}</label>
        </p>
        <p>
          <input type="text" name="nom_contact" id="nom_contact" value="{$offer.nom_contact}" maxlength="150"/>
        </p>
        <p>
          <label for="mail_contact" class="bline">{_T string="Mail"}</label>
        </p>
        <p>
          <input type="text" name="mail_contact" id="mail_contact" value="{$offer.mail_contact}" maxlength="150" required/>
        </p>
        <p>
          <label for="tel_contact" class="bline">{_T string="Phone number"}</label>
        </p>
        <p>
          <input type="text" name="tel_contact" id="tel_contact" value="{$offer.tel_contact}" maxlength="150"/>
        </p>
     </div>
    </div>
		<legend> {_T string="Offer details"}  </legend>
		<div class="row col-sm-offset-1">
			<div class="form-group col-md-6">
      <p>
        <label for="mots_cles" class="bline">{_T string="Key word(s)"}</label>
      </p>
      <p>
        <input type="text" name="mots_cles" id="mots_cles" value="{$offer.mots_cles}" maxlength="150"/>
      </p>
      <p>
        <label for="duree" class="bline">{_T string="Duration"}</label>
      </p>
      <p>
        <input type="text" name="duree" id="duree" value="{$offer.duration}" placeholder="{_T string="(e.g., 3 months or 2 years; leave empty for permanent job)"}" maxlength="150"/>
      </p>
      <p>
        <label for="date_debut" class="bline">{_T string="Beginning"}</label>
      </p>
      <p>
        <input class="past-date-pick" type="text" name="date_debut" id="date_debut" value="{$offer.date_debut}" placeholder="{_T string="(dd/mm/yyyy format)"}" maxlength="10"/>
      </p>
      <p>
        <label for="remuneration" class="bline">{_T string="Salary"}</label>
      </p>
      <p>
        <input type="text" name="remuneration" id="remuneration" value="{$offer.remuneration}" maxlength="150"/>
      </p>
      <p>
        <label for="cursus" class="bline">{_T string="Cursus"}</label>
      </p>
      <p>
        <input type="text" name="cursus" id="cursus" value="{$offer.cursus}" maxlength="150"/>
      </p>

			<div class="button-container">
				<input type="submit" id="btnsave" value="{_T string="Save"}"/>
				<input type="hidden" name="id_offre" value="{$offer.id}"/>
				<input type="hidden" name="id_adh" value="{$offer.id_adh}"/>
			</div>
     </div>
    </div>
</form>
<script type="text/javascript">
	$(function() {

		_collapsibleFieldsets();

		$('#date_fin').datepicker({
			changeMonth: true,
			changeYear: true,
			showOn: 'button',
			buttonImage: '{$template_subdir}images/calendar.png',
			buttonImageOnly: true,
			maxDate: '-0d',
			yearRange: 'c-100:c+0'
		});

		$('#date_debut').datepicker({
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
