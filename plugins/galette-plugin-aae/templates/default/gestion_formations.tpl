
    <table id='table_formation' class="listing">
        <thead>
            <tr>
                <!-- <th class="listing id_row">#</th> -->
                <th class="listing left">{_T string="Cycle"}</th>
                <th class="listing left">{_T string="Speciality"}</th>
                <th class="listing left date_row"> {_T string="Begin"} </th>
                <th class="listing left date_row"> {_T string="End"} </th>
                {if $haveRights}
                <th class="listing actions_row">{_T string="Actions"}</th>
                {/if}
        </thead>

        <tbody>
            

    {foreach from=$list_formations item=form}
            <tr class="formation_row">
                <td class="center nowrap">{$form.nom}</td>
                <td class="center nowrap">{$form.specialite}</td>
                <td class="center nowrap">{$form.annee_debut}</td>
                <td class="center nowrap">{$form.annee_fin}</td>
                {if $haveRights}
                <td class="center nowrap">

                    <input class='btn_supp' border=0 src="{$template_subdir}images/delete.png" type=image Value='{$form.id}' align="middle" /> 

                </td>
                {/if}
            </tr>
    {/foreach}
    {if $haveRights}
            <tr>
                <td class="center nowrap">
                    <select name="id_cycle" id="id_cycle">
                            {foreach from=$cycles key=k item=v}
                            <option value="{$k}">{$v}</option>
                            {/foreach}
                    </select>
                </td>
                <td class="center nowrap">
                    <input id="specialite" type="text"/>
                </td>
                <td class="center nowrap">
                    <select id="StartYear"/>
                </td>
                <td class="center nowrap">
                    <select id="EndYear"/>
                </td>
                <td class="center nowrap">
                    <input id='btn_add' border=0 src="{$template_subdir}images/icon-add.png" type=image align="middle" /> 
                </td>
            </tr>

        
        <script type="text/javascript">

            var intiateSelects = function() {
                var myDate = new Date();
            
                var year = myDate.getFullYear();

                for(var i = year-1; i >= 1950; i--){
                    $('#StartYear').append('<option value="'+ i +'">'+ i +'</option>');
                };

                $('#EndYear').append('<option value="'+year+'">'+year+'</option>');
            };

            var updateSelect = function () {

                //sauvegarde du choix de l'utilisateur :
                var startYearSave = $('#StartYear').find(":selected").val();
                var endYearSave = $('#EndYear').find(":selected").val();

                $('#StartYear').find('option').remove();
                $('#EndYear').find('option').remove();

                var myDate = new Date();
            
                var year = myDate.getFullYear();

                for(var i = year; i >= 1950; i--){
                    if(i>startYearSave) $('#EndYear').append('<option value="'+i+'">'+i+'</option>');
                    if((i-1)<endYearSave)
                    {
                        
                        $('#StartYear').append('<option value="'+ (i-1) +'">'+ (i-1) +'</option>');
                    } 
                };

                $('#StartYear').val(startYearSave);
                $('#EndYear').val(endYearSave);

            };

            $('#StartYear').change(updateSelect);
            $('#EndYear').change(updateSelect);

            intiateSelects();

            $('#btn_add').click(function(e) {

                $.post( 'ajouter_formation_eleve.php',
                    {
                        id_adh: {$mid},
                        id_cycle: $('#id_cycle').find(":selected").val(), 
                        specialite: $('#specialite').val(),
                        annee_debut: $('#StartYear').find(":selected").val(),
                        annee_fin: $('#EndYear').find(":selected").val()

                    })
                .done(function(data) {       
                    reloadTable();
                });
            });

            $('.btn_supp').click(function(e) {
                e.preventDefault();
                
                $.get( 'supprimer_formation_eleve.php',
                    {
                        id_form: e.target.value
                    })
                .done(function(data) {       
                    reloadTable();
                });
            });


            var reloadTable = function(){
                $.get( 'gestion_formations_eleve.php?id_adh={$mid}')
                    .done(function(data) {
                        var $response=$(data);
                        var table = $response.find('#table_formation').html();
                        $('#table_formation').html(table);
                    });
            }
        </script>
        {/if}
        </tbody>
    </table>
