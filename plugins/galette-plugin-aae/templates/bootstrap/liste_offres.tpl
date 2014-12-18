{* TODO
- passer toute les offres en JSON
- bosser avec dynatable
*}

{if $nb_offres > 0}
{* TODO : liste des attributs à afficher
o  titre text  NOT NULL,
?  organisme text  NOT NULL,
?  localisation text,
  site text,
  nom_contact text  NOT NULL,
  mail_contact text  NOT NULL,
  tel_contact text,
o  date_parution date NOT NULL,
?  date_fin date NOT NULL,
o  type_offre enum('Stage','CDD','CDI') NOT NULL,
  desc_offre text,
?  mots_cles text  NOT NULL,
?  duree text,
?  date_debut date NOT NULL,
?  remuneration text,
?  cursus text,
?  tech_majeures text,
*}

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
	                <td class="center nowrap">{utf8_encode($offre.titre)}</td>
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
