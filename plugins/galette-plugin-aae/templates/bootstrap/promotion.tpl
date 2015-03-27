{if $login->isLogged()}
	{*Display student founds*}
	{if $nb_eleves > 0}
			<table class="table">
				<tr>
					<td class="left">{$nb_eleves} {if $nb_eleves != 1}{_T string="students"}{else}{_T string="student"}{/if}</td>
				</tr>
			</table>
			<table id="table_promotions" class="listing table">
				<thead>
					<tr>
						<th class="left">
								{_T string="Name"}
						</th>
						<th class="left">
								{_T string="First Name"}
						</th> 
						<th class="left">
								{_T string="Promotion:"}
						</th>
						<th class="left">
								{_T string="Cycle:"}
						</th>
					</tr>
				</thead>
				<tbody>
		{*Display all student*}
		{foreach from=$eleves item=eleve name=alleleves}
					<tr>
						<td class="nowrap username_row"><a href="voir_adherent_public.php?id_adh={$eleve.id_adh}">{$eleve.nom_adh}</a></td>
						<td class="nowrap"><a href="voir_adherent_public.php?id_adh={$eleve.id_adh}">{$eleve.prenom_adh}</a></td>
						<td class="nowrap">{$eleve.annee_debut}</td>
						<td class="nowrap">{$eleve.nom}</td>
					</tr>
		{/foreach}
				</tbody>
			</table>
			{_T string="If you see an error, please send an email to:"}
			<a href='mailto:{$preferences->pref_email}'>{$preferences->pref_email}</a>
		<script type="text/javascript">
			$('#table_promotions').dynatable({
				features: {
					paginate: true,
					sort: true,
					pushState: false,
					search: false,
					recordCount: false,
					perPageSelect: true
				},
				inputs: {
					paginationPrev: '{_T string="Previous"}',
					paginationNext: '{_T string="Next"}',
					searchText: '{_T string="Search:"}',
					perPageText: '{_T string="Show:"}',
					pageText: '{_T string="Pages:"}'

				},
				dataset: {
				perPageDefault: 100,
				perPageOptions: [10,20,50,100]}
			});

			$(".dynatable-sort-header").css("color","black");

		</script>
	{else}
		<div id="infobox">{_T string="No member to show"}</div>
	{/if}
{else}
		<p>
			{_T string="Please, log on in order to display information."}
		</p>
		<p> <a href="../../index.php" class="btn btn-primary">{_T string="Login"}</a> </p>
		



{/if}	