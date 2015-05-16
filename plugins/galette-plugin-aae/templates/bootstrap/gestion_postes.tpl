 <strong>{$member->sname}</strong>
 
 
 			<table id='table_poste' class="listing">
				<legend>{_T string="Jobs information:"}</legend>

		{foreach $list_postes as $key}
				<tr>
					<th style="width:50%" >
						{$key.annee_ini|htmlspecialchars} - {if $key.annee_fin eq 0} {_T string="present"} {else} {$key.annee_fin|htmlspecialchars} {/if}
					</th>
					<td>
						{$key.activite_principale|htmlspecialchars}
					</td>
					<td>
						<a href="liste_job.php?id_entreprise={$key.id_entreprise}">{$key.employeur|htmlspecialchars}</a>
					</td>
					<td>
						<a href="{$key.website}">{$key.website}</a>
					</td>
					<td class="center nowrap">
						<input class='btn_supp' border=0 src="{$template_subdir}images/delete.png" type=image Value='{$key.id_poste|htmlspecialchars}' align="middle" />
					</td>
                    <td class="center nowrap">
                        <a href="ajouter_poste.php?id_adh={$id_adh}&id_poste={$key.id_poste|htmlspecialchars}"><img src="{$template_subdir}images/icon-edit.png" align="middle" /></a>
                    </td>
				</tr>
		{/foreach}
					<a href="ajouter_poste.php?id_adh={$id_adh}"><img src="{$template_subdir}images/icon-add.png" align="middle" />   {_T string="Ajouter poste"}</a>
			</table>
 
<script type="text/javascript">


    var rmPoste = function(e) {
        e.preventDefault();
        
        $.get( 'supprimer_poste.php',
            {
                id_form: e.target.value
            })
        .done(function(data) {       
            reloadTable();
        });
    };
    

    var reloadTable = function(){
        $.get( 'gestion_postes.php?id_adh={$mid}')
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
