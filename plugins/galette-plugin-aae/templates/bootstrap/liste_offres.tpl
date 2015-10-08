{if $nb_offres > 0}

	<img src="{$galette_base_path}/templates/bootstrap/images/rss.png" width="20" height=20 alt="RSS"> </a> <a href="{$galette_base_path}{$aaetools_path}liste_offres.php?rss"> {_T string="Subscrite"}  / <a href="{$galette_base_path}{$aaetools_path}ajouter_offre.php"><img src="{$template_subdir}images/icon-add.png" align="middle" /> {_T string="Add job offers"}</a>
	<br>
	<table id='table_offre' class="listing">
        <thead>
            <tr>
                <th>{_T string="Title"}</th>
                <th>{_T string="Enterprise"}</th>
                <th> {_T string="Publication date"} </th>
                <th> {_T string="Offer type"} </th>
        </thead>

        <tbody>
    		{foreach from=$offres item=offre}
	            <tr class="formation_row">
	                <td> <a href="{$galette_base_path}{$aaetools_path}liste_offres.php?id_offre={$offre.id_offre}"> {utf8_encode($offre.titre)} </a> </td>
	                <td><a href="liste_job.php?id_entreprise={$offre.id_entreprise}">{$offre.employeur|htmlspecialchars}</a></td>
	                <td>{$offre.date_parution}</td>
	                <td>{$offre.type_offre}</td>
	            </tr>
	    	{/foreach}
        </tbody>
    </table>

    <script type="text/javascript">


		$('#table_offre').dynatable({
		  	features: {
			    paginate: true,
			    sort: true,
			    pushState: false,
			    search: true,
			    recordCount: true,
			    perPageSelect: true
			},
			inputs: {
				paginationPrev: '{_T string="Previous"}',
				paginationNext: '{_T string="Next"}',
				searchText: '{_T string="Search:"}',
			    perPageText: '{_T string="Show:"}',
			    pageText: '{_T string="Pages:"}'

			}
		});

		$(".dynatable-sort-header").css("color","black");

    </script>

{else}
    <div id="infobox">{_T string="No offer to show"}</div>
{/if} {* $nb_offres > 0 *}
