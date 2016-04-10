

    <table class="table">
      <tr>
        <td><h2><a href="liste_eleves.php?id_cycle={$id_cycle}">{$cycle["nom"]}</a></h2></td>
      </tr>
      <tr>
        <td>
        {$cycle["detail"]}
        </td>
      </tr>
      {foreach $stat as $year => $count}
        <tr>
          <td><h4><a href="liste_eleves.php?id_cycle={$id_cycle}&annee_debut={$year}">{$year}</a></h4> </td>
          <td>{$count}</td>
        </tr>
      {/foreach}
    </table>
