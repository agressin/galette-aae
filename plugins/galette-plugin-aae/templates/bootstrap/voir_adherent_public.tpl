	<div class="bigtable wrmenu">
		<div class="bigtable wrmenu">
			<!--<div id="member_stateofdue" class="{$member->getRowClass()}">{$member->getDues()}</div>-->
			<table class="details table-hover">
				<legend>{_T string="Identity"}</legend>
				<tr>
					<th style="width:50%" >{_T string="Name"}</th>
					<td>
						{if $member->isCompany()}
							<img src="{$template_subdir}images/icon-company.png" alt="{_T string="[C]"}" width="16" height="16"/>
						{elseif $member->isMan()}
							<img src="{$template_subdir}images/icon-male.png" alt="{_T string="[M]"}" width="16" height="16"/>
						{elseif $member->isWoman()}
							<img src="{$template_subdir}images/icon-female.png" alt="{_T string="[W]"}" width="16" height="16"/>
						{/if}
						{$member->sfullname}
					</td>
					<td rowspan="{if $member->isCompany()}7{else}6{/if}" style="width:{$member->picture->getOptimalWidth()}px;">
						<img
							src="{$galette_base_path}picture.php?id_adh={$member->id}&amp;rand={$time}"
							width="{$member->picture->getOptimalWidth()}"
							height="{$member->picture->getOptimalHeight()}"
							alt="{_T string="Picture"}"
							id="photo_adh"/>
					</td>
				</tr>
	{if $visibles.societe_adh eq constant('Galette\Entity\FieldsConfig::VISIBLE') or ($visibles.societe_adh eq constant('Galette\Entity\FieldsConfig::ADMIN') and ($login->isStaff() or $login->isAdmin() or $login->isSuperAdmin()))}
		{if $member->isCompany()}
				<tr>
					<th style="width:50%" >{_T string="Company"}</th>
					<td>{$member->company_name}</td>
				</tr>
		{/if}
	{/if}
	{if $visibles.pseudo_adh eq constant('Galette\Entity\FieldsConfig::VISIBLE') or ($visibles.pseudo_adh eq constant('Galette\Entity\FieldsConfig::ADMIN') and ($login->isStaff() or $login->isAdmin() or $login->isSuperAdmin()))}
				<tr>
					<th style="width:50%" >{_T string="Nickname"}</th>
					<td>{$member->nickname|htmlspecialchars}</td>
				</tr>
	{/if}
	{if $visibles.ddn_adh eq constant('Galette\Entity\FieldsConfig::VISIBLE') or ($visibles.ddn_adh eq constant('Galette\Entity\FieldsConfig::ADMIN') and ($login->isStaff() or $login->isAdmin() or $login->isSuperAdmin()))}
				<tr>
					<th style="width:50%" >{_T string="Birth date"}</th>
					<td>{$member->birthdate}</td>
				</tr>
	{/if}
	{if $visibles.prof_adh eq constant('Galette\Entity\FieldsConfig::VISIBLE') or ($visibles.prof_adh eq constant('Galette\Entity\FieldsConfig::ADMIN') and ($login->isStaff() or $login->isAdmin() or $login->isSuperAdmin()))}
				<tr>
					<th style="width:50%" >{_T string="Profession"}</th>
					<td>{$member->job|htmlspecialchars}</td>
				</tr>
	{/if}

	
				<tr>
					<th style="width:50%" >{_T string="Cycle(s)"}</th>
					<form class="form-horizontal" action="liste_eleves.php" method="post">
					<td>
	{foreach $form as $key}
					<a href="liste_eleves.php?id_cycle={$key.id_cycle}&annee_debut={$key.annee_debut}">{$key.nom}	{$key.annee_debut}</a>
	{/foreach}
					</td>
					</form>
				</tr>

	
			</table>
			
			<table class="details table-hover">
				<legend>{_T string="Contact information"}</legend>
	{if $visibles.ville_adh eq constant('Galette\Entity\FieldsConfig::VISIBLE') or ($visibles.ville_adh eq constant('Galette\Entity\FieldsConfig::ADMIN') and ($login->isStaff() or $login->isAdmin() or $login->isSuperAdmin()))}
				<tr>
					<th style="width:50%" >{_T string="City"}</th>
					<td>{$member->town|htmlspecialchars}</td>
				</tr>
	{/if}
	{if $visibles.pays_adh eq constant('Galette\Entity\FieldsConfig::VISIBLE') or ($visibles.pays_adh eq constant('Galette\Entity\FieldsConfig::ADMIN') and ($login->isStaff() or $login->isAdmin() or $login->isSuperAdmin()))}
				<tr>
					<th style="width:50%" >{_T string="Country"}</th>
					<td>{$member->country|htmlspecialchars}</td>
				</tr>
	{/if}
	{if $visibles.email_adh eq constant('Galette\Entity\FieldsConfig::VISIBLE') or ($visibles.email_adh eq constant('Galette\Entity\FieldsConfig::ADMIN') and ($login->isStaff() or $login->isAdmin() or $login->isSuperAdmin()))}
				<tr>
					<th style="width:50%" >{_T string="E-Mail"}</th>
					<td>
		{if $member->email ne ''}
						<a href="{$galette_base_path}plugins/galette-plugin-aae/send_message.php?id_adh={$member->id}"><img src="{$template_subdir}images/icon-mail.png" alt="" width="16" height="16"/></a>
		{else}
						<img src="{$template_subdir}images/icon-empty.png" alt="" width="16" height="16"/>
		{/if}
					</td>
				</tr>
	{/if}
	{if $visibles.url_adh eq constant('Galette\Entity\FieldsConfig::VISIBLE') or ($visibles.url_adh eq constant('Galette\Entity\FieldsConfig::ADMIN') and ($login->isStaff() or $login->isAdmin() or $login->isSuperAdmin()))}
				<tr>
					<th style="width:50%" >{_T string="Website"}</th>
					<td>
		{if $member->website ne ''}
						<a href="{$member->website}">{$member->website}</a>
		{/if}
					</td>
				</tr>
	{/if}

			</table>

			
			<table class="details table-hover">
				<legend>{_T string="Jobs information"}</legend>
		{foreach $list_postes as $key}
		
				<tr>
					<th style="width:25%" >
					{if $key.annee_fin eq $key.annee_ini}
	    				{$key.annee_fin}
	    			{else}
	    				{$key.annee_ini}-{if $key.annee_fin eq 0}{_T string="present"}{else}{$key.annee_fin}{/if}
	    			{/if}
					</th>
					<td>{$key.activite_principale|htmlspecialchars}</td>
					<td><a href="liste_job.php?id_entreprise={$key.id_entreprise}">{$key.employeur|htmlspecialchars}</a></td>
					<td>
						<a href="{$key.website}">{$key.website}</a>
					</td>
					<td>
						<a href="" data-toggle="modal" data-target=".bs-example-modal-lg-{$key.id_poste}"><img src="{$template_subdir}images/icon-fiche.png" align="middle" /></a>
					</td>	
				</tr>
		{/foreach}
			</table>

		{foreach $list_postes as $key}
			<div class="modal fade bs-example-modal-lg-{$key.id_poste}" tabindex="-1" role="dialog">
			  <div class="modal-dialog modal-lg">
				<div class="modal-content">
				  <table class="table"> 
				    	<tr>
				    		<td><h4>Période</h4> </td>
				    		<td>{$key.annee_ini}-{if $key.annee_fin eq 0}{_T string="present"}{else}{$key.annee_fin}{/if}</td>
				    	</tr>
				    	<tr>
				    		<td><h4>Employeur</h4></td>
				    		<td> <a href="liste_job.php?id_entreprise={$key.id_entreprise}">{$key.employeur}</a></td>
				    	</tr>
				    	<tr>
				    		<td><h4>Site internet</h4></td>
				    		<td> <a href="{if strpos($key.website,"http") !==0}http://{/if}{$key.website}" target="_blank">{$key.website}</a></td>
				    	</tr>
				    	<tr>
				    		<td><h4>Adresse</h4></td>
				    		<td>{$key.adresse} {$key.code_postal} {$key.ville}</td>
				    	</tr> 
				    	<tr>
				    		<td><h4>Type de contrat</h4></td>
				    		<td>{$key.type}</td>
				    	</tr> 
				    	<tr>
				    		<td><h4>Intitulé du poste</h4></td>
				    		<td>{$key.activite_principale}</td>
				    	</tr>
				    </table> 
				</div>
			  </div>
			</div>
		{/foreach}
