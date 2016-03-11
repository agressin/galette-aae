<table id='table_cycle' class="table table-hover">
    <thead>
        <tr>
            <!-- <th class="listing id_row">#</th> -->
            <th class="listing left">{_T string="Cycle"}</th>
            <th class="listing actions_row">{_T string="Actions"}</th>
    </thead>

    <tbody>
{foreach $cycles as $cycle}
        <tr class="formation_row">
            <td class="center nowrap">{$cycle.nom}</td>
            <td class="center nowrap">
                <input class='btn_supp' border=0 src="{$template_subdir}images/delete.png" type=image Value='{$cycle.id_cycle}' align="middle" />
            </td>
        </tr>
{/foreach}
        <tr>
            <td class="center nowrap">
              <input id="cycle_name" name="cycle_name" type="text"/>
            </td>
            <td class="center nowrap">
                <input id='btn_add' border=0 src="{$template_subdir}images/icon-add.png" type=image align="middle" />
            </td>
        </tr>
   </tbody>
</table>

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
