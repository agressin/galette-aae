	<form class="form-horizontal" action="liste_eleves.php" method="post">
		<fieldset>
			<legend>{_T string="Select cycle and promotion"}</legend>
			  <div class="form-group">
				<label for="id_cycle" class="col-sm-2 control-label">{_T string="Cycle:"}</label>
				<div class="col-xs-2">
					<input type= "radio" name="id_cycle_simple" value="IT" {if $id_cycle_simple == "IT"} checked{/if}> Ingénieur </br>
					<input type= "radio" name="id_cycle_simple" value="G"  {if $id_cycle_simple == "G"} checked{/if} > Géomètre   </br>
					<input type= "radio" name="id_cycle_simple" value="DC" {if $id_cycle_simple == "DC"} checked{/if}> Dessinateur </br>
				</div>
				<div class="col-xs-3">
					<label for="id_cycle" class="col-sm-1 control-label">{_T string="or"}</label>
					<select class="form-control" name="id_cycle" id="id_cycle">
						<option value="0" {if $param_selected eq 1} selected="selected"{/if} > -- </option>
					{foreach from=$cycles item=cycle name=allcycles}
						<option value="{$cycle.id_cycle}" {if $id_cycle == $cycle.id_cycle} selected="selected"{/if} >{$cycle.nom}</option>
					{/foreach}
					</select>
				</div>
			  </div>
			  <div class="form-group">
				<label for="annee_debut" class="col-sm-2 control-label">{_T string="Promotion:"}</label>
				<div class="col-xs-3">
					<select class="form-control" name="annee_debut" id="annee_debut">

					</select>
				</div>
			  </div>
			  <div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
				  <button type="submit"  class="btn btn-primary">{if $param_selected eq 1} {_T string="Refresh"} {else} {_T string="Continue"} {/if}</button>
				</div>
			  </div>
		</fieldset>
	</form>
{if $param_selected eq 1}
{if $nb_eleves > 0}
        <table class="table">
            <tr>
                <td class="left">{$nb_eleves} {if $nb_eleves != 1}{_T string="students"}{else}{_T string="student"}{/if}</td>
            </tr>
        </table>
        <table id="table_eleves" class="listing table">
            <thead>
                <tr>
                    <th class="left">
                            {_T string="Name"}
                    </th>
                    <th class="left">
                            {_T string="First Name"}
                    </th> 
                    <th class="left"> 
                       {_T string="Speciality"}
                    </th>
                </tr>
            </thead>
            <tbody>
    {foreach from=$eleves item=eleve name=alleleves}
                <tr class="{if $smarty.foreach.allmembers.iteration % 2 eq 0}even{else}odd{/if}">
                    <td class="nowrap username_row">{$eleve.nom_adh}</td>
                    <td class="nowrap">{$eleve.prenom_adh}</td>
                    <td class="nowrap">{$eleve.specialite}</td>
                </tr>
    {/foreach}
            </tbody>
        </table>

{else}
    <div id="infobox">{_T string="No member to show"}</div>
{/if}
{/if} {* $param_selected eq 1 *}

       <script type="text/javascript">

            var initiateSelects = function() {
                var myDate = new Date();
            
                var year = myDate.getFullYear();
                var curYear = "{$annee_debut}";
                //curYear = (curYear=="")?year-1:curYear;

                for(var i = year-1; i >= 1950; i--){
                	opt='<option value="'+ i +'">'+ i +'</option>';
                	if(curYear==i)
                		opt='<option value="'+ i +'" selected="selected" >'+ i +'</option>';
                    $('#annee_debut').append(opt);
                };
            };


            initiateSelects();
            
            $('#id_cycle').on('change', function() {
  				$('input[name=id_cycle_simple]').attr('checked',false);
			});
			
			$('input[name=id_cycle_simple]').live('change', function() {
				$('#id_cycle').val('0');
			});

        </script>
