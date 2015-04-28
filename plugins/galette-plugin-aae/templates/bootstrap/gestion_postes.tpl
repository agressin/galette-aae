 <strong>{$member->sname}</strong>
 
    <table id='table_poste' class="listing">
        
        <thead>
            <tr>
                <!-- <th class="listing id_row">#</th> -->
                <th class="listing left">{_T string="Employeur"} </th>
                <th class="listing left">{_T string="Activité principale"}</th>
                <th class="listing left">{_T string="Encadrement"}</th>
                <th class="listing left">{_T string="Nombre de personnes encadrées"}</th>
          
                <th class="listing left">{_T string="Adresse"}</th>
                <th class="listing left">{_T string="Site internet"}</th>
                <th class="listing left date_row"> {_T string="Begin"} </th>
                <th class="listing left date_row"> {_T string="End"} </th>
                {if $haveRights}
                <th class="listing actions_row">{_T string="Actions"}</th>
                {/if}
        </thead>

        <tbody>
 {foreach from=$list_postes item=form}
            <tr class="poste_row">
                <!-- <th class="listing id_row">#</th> -->
    
                <td class="center nowrap">{$form.employeur|htmlspecialchars}</td>
                <td class="center nowrap">{$form.activite_principale|htmlspecialchars}</td>
                <td class="center nowrap">{$form.encadrement|htmlspecialchars}</td>
                <td class="center nowrap">{$form.nb_personne_encadre|htmlspecialchars}</td>
            
                <td class="center nowrap">{$form.adresse|htmlspecialchars}</td>
                <td class="center nowrap">{$form.website|htmlspecialchars}</td>
                <td class="center nowrap">{$form.annee_ini|htmlspecialchars}</td>
                <td class="center nowrap">{if $form.annee_fin eq 0} {_T string="present"} {else} {$form.annee_fin|htmlspecialchars} {/if}</td>
                {if $haveRights}
                <td class="center nowrap">

                    <input class='btn_supp' border=0 src="{$template_subdir}images/delete.png" type=image Value='{$form.id_poste|htmlspecialchars}' align="middle" /> 

                </td>
                {/if}
            </tr>
    {/foreach}

    {if $haveRights}
            <tr>
                <td class="center nowrap">
                    <input list="entreprise" id="employeur" type="text">
                    <datalist id="entreprise" type="text">
                        {foreach from=$entreprises key=k item=v}
                            <option value="{$v.employeur}">{$v.employeur}</option>
                        {/foreach}
                    </datalist>
                    
                </td>
                <td class="center nowrap">
                    <input id="activite_principale" type="text"/>
                </td>
                <td class="center nowrap">
                    <form id ="formbutton">
                    <INPUT id="encadrement1" name="encadr" type= "radio" value="1">{_T string="Yes"}
                    <INPUT id="encadrement2" name="encadr" type= "radio" value="0" checked="checked">{_T string="No"}
                   </form>
                </td>
                <td class="center nowrap">
                    <input id="nb_personne_encadre" type="text"  disabled=""/>
                </td>
                <td class="center nowrap">
                    <input id="adresse" type="text" />
                </td>
                <td class="center nowrap">
                    <input id="employeur_website" type="text"/>
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
	{/if}
       </tbody>
    </table>    

    {if $haveRights}
        <script type="text/javascript">

			var present = "{_T string="present"}";
            var initiateSelects = function() {
                var myDate = new Date();
            
                var year = myDate.getFullYear();

                for(var i = year-1; i >= 1950; i--){
                    $('#StartYear').append('<option value="'+ i +'">'+ i +'</option>');
                };

                $('#EndYear').append('<option value="'+year+'">'+year+'</option>');
                $('#EndYear').append('<option value="'+present+'">'+present+'</option>');
                updateSelect();
            };

            var updateSelect = function () {

                //sauvegarde du choix de l'utilisateur :
                var startYearSave = $('#StartYear').find(":selected").val();
                var endYearSave = $('#EndYear').find(":selected").val();


                $('#StartYear').find('option').remove();
                $('#EndYear').find('option').remove();

                var myDate = new Date();
            
                var year = myDate.getFullYear() +3;
                
                isPresent = false;
                if(endYearSave == present)
                {
					endYearSave = myDate.getFullYear();
					isPresent = true;
				}
				$('#EndYear').append('<option value="{_T string="present"}">{_T string="present"}</option>');
                for(var i = year; i >= 1950; i--){
                    if(i>=startYearSave) $('#EndYear').append('<option value="'+i+'">'+i+'</option>');
                    if((i-1)<=endYearSave)
                    {
                        $('#StartYear').append('<option value="'+ (i-1) +'">'+ (i-1) +'</option>');
                    } 
                };
                

                $('#StartYear').val(startYearSave);
                if(isPresent)
					$('#EndYear').val(present);
                else
					$('#EndYear').val(endYearSave);

            };


            var updateEncadre = function () {
                //sauvegarde du choix de l'utilisateur :
                var form = document.getElementById('formbutton');
				if(form.encadr[0].checked)
					document.getElementById('nb_personne_encadre').disabled = false;
                else if (form.encadr[1].checked)
					document.getElementById('nb_personne_encadre').disabled = true;
            };

			var addPoste = function(e) {
                var form = document.getElementById('formbutton');
                $.post( 'ajouter_poste.php',
                    {
                        id_adh: {$mid},
                        employeur: $('#employeur').val(), 
                        activite_principale: $('#activite_principale').val(),
                        encadrement: form.encadr.value,
                        nb_personne_encadre: $('#nb_personne_encadre').val(),
                        adresse: $('#adresse').val(),
                        employeur_website: $('#employeur_website').val(),
                        annee_ini: $('#StartYear').find(":selected").val(),
                        annee_fin: $('#EndYear').find(":selected").val()

                    })
                .done(function(data) {       
                    reloadTable();
                });
            };
            
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
            
            var changeEmployeur = function(){
                // console.log($('#employeur').val());
                console.log(this.value);
                {foreach from=$entreprises key=k item=v}
                            if (this.value=="{$v.employeur}") {
                                 $('#employeur_website').val("{$v.website}");
                            };
                 {/foreach}
            }
            
            var init = function(){
				$('#StartYear').change(updateSelect);
				$('#EndYear').change(updateSelect);
				$('#encadrement1').change(updateEncadre );
				$('#encadrement2').change(updateEncadre );
				initiateSelects();
				$('#btn_add').click(addPoste);
				$('.btn_supp').click(rmPoste);
				document.getElementById("employeur").addEventListener("change", changeEmployeur, false); 
			};
            
            init();
        </script>
        
        {else}
        {_T string="You are not allowed to modify your formations. However, if you see an error, please send an email to:"}
        <a href='mailto:{$preferences->pref_email}'>{$preferences->pref_email}</a>
        {/if}
