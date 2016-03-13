<table id='table_cycle' class="table table-hover">
    <thead>
        <tr>
            <!-- <th class="listing id_row">#</th> -->
            <th class="listing left">{_T string="Cycle"}</th>
            <th class="listing left">{_T string="Effectifs"}</th>
            <th class="listing actions_row">{if $haveRights}{_T string="Actions"}{else}{_T string="Details"}{/if}</th>
    </thead>

    <tbody>
{foreach $cycles as $cycle}
        <tr class="formation_row">
            <td class="center nowrap">{$cycle.nom}</td>
            <td class="center nowrap">{$cycles_stats[$cycle.id_cycle]}</td>
            <td class="center nowrap">
                {if not isset($cycles_stats[$cycle.id_cycle])}
                  {if $haveRights}
                  <input class='btn_supp' border=0 src="{$template_subdir}images/delete.png" type=image Value='{$cycle.id_cycle}' align="middle" />
                  {/if}
                {else}
                  <a href="javascript:;" data-toggle="modal" data-target=".bs-example-modal-lg-{$cycle.id_cycle}"><img src="{$template_subdir}images/icon-fiche.png" align="middle" /></a>
                {/if}
            </td>
        </tr>
{/foreach}
        {if $haveRights}
        <tr>
            <td class="center nowrap">
              <input id="cycle_name" name="cycle_name" type="text"/>
            </td>
            <td class="center nowrap"></td>
            <td class="center nowrap">
                <input id='btn_add' border=0 src="{$template_subdir}images/icon-add.png" type=image align="middle" />
            </td>
        </tr>
        {/if}
   </tbody>
</table>

{if $haveRights}
<script type="text/javascript">

    var addCycle = function(e) {

        $.get( 'gestion_cycles.php',
            {
                nom: $('#cycle_name').val(),
                action:"add"
            })
        .done(reloadTable);
    };

    var rmCycle = function(e) {
        e.preventDefault();
        $.get( 'gestion_cycles.php',
            {
                id_cycle: e.target.value,
                action:"rm"
            })
        .done(reloadTable);
    };

  var init = function() {
    $('#btn_add').click(addCycle);
    $('.btn_supp').click(rmCycle);
  };

  var reloadTable = function(data){
      var $response=$(data);
      var table = $response.find('#table_cycle').html();
      $('#table_cycle').html(table);
      init();
  };
  init();
</script>
{/if}

{foreach $cycles_stats_by_year as $id_cycle => $stat}
  <div class="modal fade bs-example-modal-lg-{$cycle.id_cycle}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <table class="table">
        {foreach $stat as $year => $count}
          <tr>
            <td><h4><a href="liste_eleves.php?id_cycle={$id_cycle}&annee_debut={$year}">{$year}</a></h4> </td>
            <td>{$count}</td>
          </tr>
        {/foreach}
        </table>
    </div>
    </div>
  </div>
{/foreach}
