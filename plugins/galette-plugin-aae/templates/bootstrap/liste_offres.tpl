{if $nb_offres > 0}

	{_T string="Subscrite to "} <a href="{$galette_base_path}plugins/galette-plugin-aae/liste_offres.php?rss"> <img src="{$galette_base_path}/templates/bootstrap/images/rss.png" width="20" height=20 alt="RSS"> </a>
	<br>
	<table id='table_offre' class="listing">
        <thead>
            <tr>
                <th class="listing left">{_T string="Title"}</th>
                <th class="listing left">{_T string="Enterprise"}</th>
                <th class="listing left date_row"> {_T string="Publication date"} </th>
                <th class="listing left date_row"> {_T string="Offer type"} </th>
        </thead>

        <tbody>
    		{foreach from=$offres item=offre}
	            <tr class="formation_row">
	                <td class="center nowrap"> <a href="{$galette_base_path}plugins/galette-plugin-aae/liste_offres.php?id_offre={$offre.id}"> {utf8_encode($offre.titre)} </a> </td>
	                <td class="center nowrap">{utf8_encode($offre.organisme)}</td>
	                <td class="center nowrap">{utf8_encode($offre.date_parution)}</td>
	                <td class="center nowrap">{utf8_encode($offre.type_offre)}</td>
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
