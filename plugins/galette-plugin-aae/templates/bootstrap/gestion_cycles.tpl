<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-sparklines/2.1.2/jquery.sparkline.js"></script>

<table id='table_cycle' class="table table-hover">
    <thead>
        <tr>
            <!-- <th class="listing id_row">#</th> -->
            <th class="listing left">{_T string="Cycle"}</th>
            <th class="listing left">{_T string="Effectifs"}</th>
            <th class="listing actions_row">{if $haveRights}{_T string="Actions"}{else}{_T string="Details"}{/if}</th>
    </thead>

    <tbody>
{foreach $cycles as $id_cycle => $nom}
        <tr class="formation_row">
            <td class="center nowrap"><a href="gestion_cycles.php?cycle_detail={$id_cycle}">{$nom}</a></td>
            <td class="center nowrap">{$cycles_stats[$id_cycle]}
               {if isset($cycles_stats_by_year[$id_cycle])}
               <span class="inlinesparkline">
               {foreach $cycles_stats_by_year[$id_cycle] as $year => $count name=stat_foreach}
                 {$count} {if not $smarty.foreach.stat_foreach.last}, {/if}
               {/foreach}
               </span>
               {/if}
            </td>
            <td class="center nowrap">
              {if not isset($cycles_stats[$id_cycle])}
                {if $haveRights}
                <input class='btn_supp' border=0 src="{$template_subdir}images/delete.png" type=image Value='{$id_cycle}' align="middle" />
                {/if}
              {else}
                <a href="javascript:;" data-toggle="modal" data-target=".bs-example-modal-lg-{$id_cycle}"><img src="{$template_subdir}images/icon-fiche.png" align="middle" /></a>
              {/if}
              {if $haveRights}
                <a href="ajouter_cycle.php?id_cycle={$id_cycle}"><img src="{$template_subdir}images/icon-edit.png" align="middle" /></a>
              {/if}
            </td>
        </tr>
{/foreach}
   </tbody>
</table>
{if $haveRights}
<a href="ajouter_cycle.php"><img src="{$template_subdir}images/icon-add.png" align="middle" />   {_T string="Add a cycle"}</a>
{/if}

<script type="text/javascript">
{if $haveRights}

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
    $('.btn_supp').click(rmCycle);
  };
  var reloadTable = function(data){
      var $response=$(data);
      var table = $response.find('#table_cycle').html();
      $('#table_cycle').html(table);
      init();
  };
  init();
  {/if}

  $('.inlinesparkline').sparkline('html',
      {
        type: 'bar',
        chartRangeMin: 0,
        chartRangeMinX: 1941,
        chartRangeMaxX: 2016
        //barColor: 'red'
      }
    );
</script>


{foreach $cycles_stats_by_year as $id_cycle => $stat}
  <div class="modal fade bs-example-modal-lg-{$id_cycle}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <table class="table">
        <tr>
          <td><h2><a href="liste_eleves.php?id_cycle={$id_cycle}">{$cycles[$id_cycle]}</a></h2></td>
        </tr>
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
