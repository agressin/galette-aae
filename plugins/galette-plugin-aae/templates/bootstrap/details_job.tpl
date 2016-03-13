{if $nb_postes > 0}
{foreach $postes as $poste}
  <div class="modal fade bs-example-modal-lg-{$poste.id_poste}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <table class="table">
          <tr>
            <td><h2><a href="voir_adherent_public.php?id_adh={$poste.id_adh}">{$poste.nom_adh}</a></h2></td>
          </tr>
          <tr>
            <td><h4>Période</h4> </td>
            <td>{$poste.annee_ini}-{if $poste.annee_fin eq 0}{_T string="present"}{else}{$poste.annee_fin}{/if}</td>
          </tr>
          <tr>
            <td><h4>Employeur</h4></td>
            <td> <a href="liste_job.php?id_entreprise={$poste.id_entreprise}">{$poste.employeur}</a></td>
          </tr>
          <tr>
            <td><h4>Site internet</h4></td>
            <td> <a href="{if strpos($poste.website,"http") !==0}http://{/if}{$poste.website}" target="_blank">{$poste.website}</a></td>
          </tr>
          <tr>
            <td><h4>Adresse</h4></td>
            <td>{$poste.adresse|htmlspecialchars}</td>
          </tr>
          <tr>
            <td><h4>Type de contrat</h4></td>
            <td>{$poste.type|htmlspecialchars}</td>
          </tr>
          <tr>
            <td><h4>Intitulé du poste</h4></td>
            <td>{$poste.titre|htmlspecialchars}</td>
          </tr>
          <tr>
            <td><h4>Compétences</h4></td>
            <td>{$poste.domaines|htmlspecialchars}</td>
          </tr>
          <tr>
            <td><h4>Description du poste</h4></td>
            <td>{$poste.activites}</td>
          </tr>
        </table>
    </div>
    </div>
  </div>
{/foreach}
{/if}
