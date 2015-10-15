		<table id='table_poste' class="table table-hover">
		 <thead>
            <tr>
                <th>{_T string="Period"}</th>
                <th>{_T string="Title"}</th>
                <th> {_T string="Entreprise"} </th>
                <th> {_T string="Site web"} </th>
                {if $haveRights}
                <th>{_T string="Actions"}</th>
                {/if}
        </thead>
	{foreach $list_postes as $key}
			<tr>
				<th>
					{if $key.annee_fin eq $key.annee_ini}
	    				{$key.annee_fin}
	    			{else}
	    				{$key.annee_ini}-{if $key.annee_fin eq 0}{_T string="present"}{else}{$key.annee_fin}{/if}
	    			{/if}
				</th>
				<td>
					{$key.titre|htmlspecialchars}
				</td>
				<td>
					<a href="liste_job.php?id_entreprise={$key.id_entreprise}">{$key.employeur|htmlspecialchars}</a>
				</td>
				<td>
					<a href="{$key.website}">{$key.website}</a>
				</td>
				<td class="center nowrap">
					<input class='btn_supp' border=0 src="{$template_subdir}images/delete.png" type=image Value='{$key.id_poste|htmlspecialchars}' align="middle" />
					<a href="ajouter_poste.php?id_poste={$key.id_poste|htmlspecialchars}"><img src="{$template_subdir}images/icon-edit.png" align="middle" /></a>
				</td>
			</tr>
	{/foreach}
		</table>

 <a href="ajouter_poste.php?id_adh={$id_adh}"><img src="{$template_subdir}images/icon-add.png" align="middle" />   {_T string="Add a job"}</a>

<script type="text/javascript">


    var rmPoste = function(e) {
        e.preventDefault();

        $.get( 'supprimer_poste.php',
            {
                id_poste: e.target.value
            })
        .done(function(data) {
            reloadTable();
        });
    };


    var reloadTable = function(){
        $.get( 'gestion_postes.php?id_adh={$member->id}')
            .done(function(data) {
                var $response=$(data);
                var table = $response.find('#table_poste').html();
                $('#table_poste').html(table);
                init();
            });

    }



    var init = function(){

		$('.btn_supp').click(rmPoste);

	};

    init();
</script>
