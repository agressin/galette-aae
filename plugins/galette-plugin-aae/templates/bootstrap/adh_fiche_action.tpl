<li>
   <a
      class="button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
      href="{$galette_base_path}{$aaetools_path}aaecotiz.php"
      id="btn_plugins_aaeTools">
      {_T string="How to contribute ?"}
   </a>
</li>
<li>
   <a
      class="button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
      href="{$galette_base_path}{$aaetools_path}gestion_formations_eleve.php?id_adh={$member->id}"
      id="btn_plugins_aaeTools">
      {_T string="View formations"}
   </a>
</li>
<!-- {if $login->isStaff() or $login->isAdmin()}  
<li>
   <a
      aria-disabled="false" role="button" class="button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
      href="{$galette_base_path}{$aaetools_path}ajouter_formation_eleve.php?id_adh={$member->id}"
      id="btn_plugins_aaeTools">
      {_T string="Add a formation"}
   </a>
</li>
{/if} -->