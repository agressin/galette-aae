	<form class="form-horizontal" action="liste_eleves.php" method="post">
		<fieldset>
			<legend>{_T string="Select cycle and promotion"}</legend>
			  <div class="form-group">
				<label for="id_cycle" class="col-sm-2 control-label">{_T string="Cycle:"}</label>
				<div class="col-xs-3">
					<select class="form-control" name="id_cycle" id="id_cycle">
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
            

        </script>
