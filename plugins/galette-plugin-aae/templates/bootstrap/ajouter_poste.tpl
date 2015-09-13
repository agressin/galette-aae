<form class="form-horizontal" action="ajouter_poste.php" method="post">
		<legend> {if {$vis} eq True} {_T string="Job details of"} {else} {_T string="Add a new job to"} {/if}  <strong>{$member->sfullname}</strong> </legend>
		
		<div class="row col-sm-offset-1"> 
			<div class="form-group col-md-6">             
            <p>
                <label class="bline" for="employeur">
                        {_T string="Employeur :"}
                </label>
            </p>
            <p>
                <select class="selectpicker" name="id_employeur" id="id_employeur" required {if {$vis} eq True} disabled {/if} title="{_T string="Choisir un employeur"}">
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
                <select class="selectpicker" name="type" id="type" {if {$vis} eq True} disabled {/if} title="{_T string="Select a type"}">
                        <option value="Stage" {if {$poste.type} eq "Stage"} selected="selected"{/if}>{_T string="Stage"}</option>
                        <option value="CDD"   {if {$poste.type} eq "CDD"}   selected="selected"{/if}>{_T string="CDD"}</option>
                        <option value="CDI"   {if {$poste.type} eq "CDI"}   selected="selected"{/if}>{_T string="CDI"}</option>
                </select>
            </p>
            <p>
                <label class="bline" for="job_title" >
                        {_T string="Job title"}
                </label>
                <input class="text" type="text" name="job_title" id="job_title" value="{$poste.title}" maxlength="100" required {if {$vis} eq True} disabled {/if}/>
            </p>
            <p>
                <label class="bline" for="activites">
                        {_T string="Activities description"}
                </label>
                <textarea class="text" type="text" name="activites" id="activites" required {if {$vis} eq True} disabled {/if}>{$poste.activites|htmlspecialchars}</textarea>
            </p>
            <p>
                <label class="bline" for="domaines">
                        {_T string="Skills"}
                </label>
            </p>
            <p>
            	<select class="selectpicker" name="domaines" id="domaines" {if {$vis} eq True} disabled {/if} multiple title={_T string="Select one or more skill(s)"}>
                        <option value="Photogrammétrie" >Photogrammétrie</option>
                        <option value="Géodésie" >Géodésie</option>
                        <option value="Cartographie" >Cartographie</option>
                </select>
            </p>
            <p>
                <label class="bline" for="adresse" >
                        {_T string="Adresse"}
                </label>
                <textarea class="text" type="text" name="adresse" id="adresse" required {if {$vis} eq True} disabled {/if} >{$poste.adresse|htmlspecialchars}</textarea>
            </p>
            <p>
                <label class="bline" for="annee_ini">
                        {_T string="Begin"}
                </label>
                -
                <label class="bline" for="annee_fin">
                        {_T string="End"}
                </label>
            </p>
            <p>
                <input class="date" type="number" name="annee_ini" id="annee_ini" min="1950" max="2100" value="{$poste.annee_ini}" placeholder="{_T string="yyyy"}" maxlength="10" required {if {$vis} eq True} disabled {/if}/>
                -
                <input class="date" type="number" name="annee_fin" id="annee_fin" min="1950" max="2100" value="{if {$poste.annee_fin} neq 0}{$poste.annee_fin}{/if}" placeholder="{_T string="empty if actual"}" maxlength="10"  {if {$vis} eq True} disabled {/if}/>
            </p>
    {if {$vis} eq False}
			<div class="button-container">
				<input type="submit" id="btnsave" value="{_T string="Save"}"/>
				<input type="hidden" name="id_poste" value="{$poste.id_poste}"/>
				<input type="hidden" name="id_adh" value="{$member->id}"/>

				{* Second step validator *}
				<input type="hidden" name="valid" value="1"/>

			</div>

    {/if}
           </div>
          </div>
</form>
