       <form action="liste_eleves.php" method="post">
            <fieldset class="cssform">
                <legend class="ui-state-active ui-corner-top">{_T string="Select cycle and promotion"}</legend>
                <p>
                    <label class="bline" for="id_cycle">{_T string="Cycle:"}</label>
                    <select name="id_cycle" id="id_cycle">
					{foreach from=$cycles item=cycle name=allcycles}
						<option value="{$cycle.id_cycle}" {if $id_cycle == $cycle.id_cycle} selected="selected"{/if} >{$cycle.nom}</option>
					{/foreach}
                    </select>
                </p>
                <p>
                    <label for="annee_debut" class="bline">{_T string="Promotion:"}</label>
                    <select name="annee_debut" id="annee_debut">

                    </select>
                </p>
            </fieldset>
    {if $param_selected eq 1}
        	<div class="button-container">
				<input type="submit" id="btn_refresh" value="{_T string="Refresh"}"/>
            </div>
    {else} {* $param_selected ne 1 *}
    		<div class="button-container">
				<input type="submit" value="{_T string="Continue"}"/>
            </div>
    {/if} {* $param_selected eq 1 *}
		</form>

{if $param_selected eq 1}
{if $nb_eleves > 0}
        <table class="infoline">
            <tr>
                <td class="left">{$nb_eleves} {if $nb_eleves != 1}{_T string="students"}{else}{_T string="student"}{/if}</td>
            </tr>
        </table>
        <table id="table_eleves" class="listing">
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
