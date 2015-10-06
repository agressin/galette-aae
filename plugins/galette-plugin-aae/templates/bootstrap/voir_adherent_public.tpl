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
		{foreach $postes as $key}
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
					<td>{$key.titre}</td>
					<td>
						<a href="javascript:;" data-toggle="modal" data-target=".bs-example-modal-lg-{$key.id_poste}"><img src="{$template_subdir}images/icon-fiche.png" align="middle" /></a>
					</td>
				</tr>
		{/foreach}
			</table>
			{include file="details_job.tpl"}

		<div class="details table-hover">
			<legend>{_T string="Geographic situation"}</legend>
			<div id="loading"><img src="../../templates/bootstrap/images/loading.gif" alt="{_T string="Loading..."}" title="{_T string="Loading..."}"></div>
			<div id="warningbox">{_T string="No detail found"}</div>
			<div id="carteMembres" class="carteMembres" style="display:none;"><div id="popup"></div></div>
		</div>

		<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.3/leaflet.css" />
		<link rel="stylesheet" href="css/style_cartes.css" type="text/css" />
		<link rel="stylesheet" type="text/css" href="http://leaflet.github.io/Leaflet.markercluster/dist/MarkerCluster.css">
		<link rel="stylesheet" type="text/css" href="http://leaflet.github.io/Leaflet.markercluster/dist/MarkerCluster.Default.css">
		<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.3/leaflet.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/proj4js/2.3.6/proj4.js"></script>
		<script type="text/javascript" src="http://leaflet.github.io/Leaflet.markercluster/dist/leaflet.markercluster-src.js"></script>
		<script type="text/javascript" src="js/CarteMembres.js"></script>

		<script type="text/javascript">
			var carteMembres = false;


			function lancerCarteMembres(id_adh) {
				// Creation de la carte :
				if (carteMembres === false) {
					var options = {
						idCarte : 'carteMembres',
						idNoResult : 'warningbox',
						idLoading : 'loading',
						center : [46.49839, 3.20801],
						zoom : 6,
						boundsFrance : L.latLngBounds([41.331554,-5.197418],[51.020073,9.516022]),
						hauteurAuto : true/*,
						keyMaps : 'AIzaSyCpMXa7ZJn2L7WebriShk4v8NSU4n3N-s8',
						keyIGN : '3s9er40tvaqliky3tswb38l2'*/
					};
					carteMembres = new CarteMembres(options);
				}

				// Ajout des résultats :
				var paraAjax = {
					type : 'GET',
					url : 'ajax_carte.php?type=mono&id_adh='+id_adh
				};
				carteMembres.ajax(paraAjax);
			}

			lancerCarteMembres({$member->id});
		</script>
