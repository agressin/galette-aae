
	<a href="{$galette_base_path}{$aaetools_path}ajouter_offre.php" title="{_T string="Add job offer"}">{_T string="Add job offer"}</a>

    <table id='table_offre' class="listing">
        <thead>
            <tr>
                <!-- <th class="listing id_row">#</th> -->
                <th class="listing left">{_T string="Titre"}</th>
                <th class="listing left">{_T string="Type"}</th>
                <th class="listing actions_row">{_T string="Actions"}</th>
        </thead>

        <tbody>
            

    {foreach from=$offres item=offre}
            <tr class="offre_row">
                <td class="center nowrap"><a href="{$galette_base_path}plugins/galette-plugin-aae/ajouter_offre.php?id_offre={$offre.id}"> {$offre.titre} </a></td>
                <td class="center nowrap"> {$offre.type} </a></td>
                <td class="center nowrap">
                    <input class='btn_supp' border=0 src="{$template_subdir}images/delete.png" type=image Value='{$offre.id}' align="middle" />
               {if $haveRights}
                    {if $offre.valide}
						<input class='btn_valid' border=0 src="{$template_subdir}images/ok.png" type=image Value='{$offre.id}' align="middle" /> 
					{else}
						<input class='btn_invalid' border=0 src="{$template_subdir}images/icon-warning.png" type=image Value='{$offre.id}' align="middle" /> 
					{/if}
				{/if}
                </td>
            </tr>
    {/foreach}
    <div>
    Actions : <img src="{$template_subdir}images/delete.png" />  to delete offer </br>
    {if $haveRights}
    	<img src="{$template_subdir}images/ok.png" />  to unvalide a valide offer </br>
    	<img src="{$template_subdir}images/icon-warning.png" />  to valide a unvalide offer </br>
    {/if}
    </div>
        <script type="text/javascript">

            $('.btn_valid').click(function(e) {
				e.preventDefault();
                $.get( 'valider_offre.php',
                    {
                        id_offre: e.target.value,
                        valide: 0
                    })
                .done(function(data) {
                    reloadTable();
                });
            });
            
             $('.btn_invalid').click(function(e) {
				e.preventDefault();
                $.get( 'valider_offre.php',
                    {
                        id_offre: e.target.value,
                        valide: 1
                    })
                .done(function(data) {
                    reloadTable();
                });
            });

            $('.btn_supp').click(function(e) {
                e.preventDefault();
                // alert("supp");
                $.get( 'supprimer_offre.php',
                    {
                        id_offre: e.target.value
                    })
                .done(function(data) {       
                    reloadTable();
                });
            });


            var reloadTable = function(){
                $.get( 'gestion_offres.php')
                    .done(function(data) {
                        var $response=$(data);
                        var table = $response.find('#table_offre').html();
                        $('#table_offre').html(table);
                    });
            }
        </script>
        </tbody>
    </table>
