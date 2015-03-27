	<div class="bigtable wrmenu">
		<div class="bigtable wrmenu">
			<div id="member_stateofdue" class="{$member->getRowClass()}">{$member->getDues()}</div>
			<table class="details">
				<legend>{_T string="Identity:"}</legend>
				<tr>
					<th>{_T string="Name:"}</th>
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
					<th>{_T string="Company:"}</th>
					<td>{$member->company_name}</td>
				</tr>
		{/if}
	{/if}
	{if $visibles.pseudo_adh eq constant('Galette\Entity\FieldsConfig::VISIBLE') or ($visibles.pseudo_adh eq constant('Galette\Entity\FieldsConfig::ADMIN') and ($login->isStaff() or $login->isAdmin() or $login->isSuperAdmin()))}
				<tr>
					<th>{_T string="Nickname:"}</th>
					<td>{$member->nickname|htmlspecialchars}</td>
				</tr>
	{/if}
	{if $visibles.ddn_adh eq constant('Galette\Entity\FieldsConfig::VISIBLE') or ($visibles.ddn_adh eq constant('Galette\Entity\FieldsConfig::ADMIN') and ($login->isStaff() or $login->isAdmin() or $login->isSuperAdmin()))}
				<tr>
					<th>{_T string="Birth date:"}</th>
					<td>{$member->birthdate}</td>
				</tr>
	{/if}
	{if $visibles.prof_adh eq constant('Galette\Entity\FieldsConfig::VISIBLE') or ($visibles.prof_adh eq constant('Galette\Entity\FieldsConfig::ADMIN') and ($login->isStaff() or $login->isAdmin() or $login->isSuperAdmin()))}
				<tr>
					<th>{_T string="Profession:"}</th>
					<td>{$member->job|htmlspecialchars}</td>
				</tr>
	{/if}

	{foreach $form as $key}
				<tr>
					<th>{_T string="Cycle:"}</th>
					<form class="form-horizontal" action="liste_eleves.php" method="post">
					<td><a href="promotion.php?cycle={$key.id_cycle}&year={$key.annee_debut}">{$key.nom}	{$key.annee_debut}</a></td>
					</form>
				</tr>

	{/foreach}
			</table>
			
			
			
			
			
			<table class="details">
				<legend>{_T string="Contact information:"}</legend>
	{if $visibles.ville_adh eq constant('Galette\Entity\FieldsConfig::VISIBLE') or ($visibles.ville_adh eq constant('Galette\Entity\FieldsConfig::ADMIN') and ($login->isStaff() or $login->isAdmin() or $login->isSuperAdmin()))}
				<tr>
					<th>{_T string="City:"}</th>
					<td>{$member->town|htmlspecialchars}</td>
				</tr>
	{/if}
	{if $visibles.pays_adh eq constant('Galette\Entity\FieldsConfig::VISIBLE') or ($visibles.pays_adh eq constant('Galette\Entity\FieldsConfig::ADMIN') and ($login->isStaff() or $login->isAdmin() or $login->isSuperAdmin()))}
				<tr>
					<th>{_T string="Country:"}</th>
					<td>{$member->country|htmlspecialchars}</td>
				</tr>
	{/if}
	{if $visibles.email_adh eq constant('Galette\Entity\FieldsConfig::VISIBLE') or ($visibles.email_adh eq constant('Galette\Entity\FieldsConfig::ADMIN') and ($login->isStaff() or $login->isAdmin() or $login->isSuperAdmin()))}
				<tr>
					<th>{_T string="E-Mail:"}</th>
					<td>
		{if $member->email ne ''}
						<a href="mailto:{$member->email}">{$member->email}</a>
		{/if}
					</td>
				</tr>
	{/if}
	{if $visibles.url_adh eq constant('Galette\Entity\FieldsConfig::VISIBLE') or ($visibles.url_adh eq constant('Galette\Entity\FieldsConfig::ADMIN') and ($login->isStaff() or $login->isAdmin() or $login->isSuperAdmin()))}
				<tr>
					<th>{_T string="Website:"}</th>
					<td>
		{if $member->website ne ''}
						<a href="{$member->website}">{$member->website}</a>
		{/if}
					</td>
				</tr>
	{/if}

			</table>

	