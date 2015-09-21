{if !$public_page}
	<li{if $PAGENAME eq "voir_adherent_public.php"} class="selected"{/if}>
		<a href="{$galette_base_path}{$aaetools_path}voir_adherent_public.php">{_T string="My shared profil"}</a>
	</li>
	<li{if $PAGENAME eq "liste_eleves.php"} class="selected"{/if}>
		<a href="{$galette_base_path}{$aaetools_path}liste_eleves.php">{_T string="Former students list"}</a>
	</li>
	<li class="{if $PAGENAME eq "liste_job.php"}current_{/if}page_item">
		<a href="{$galette_base_path}{$aaetools_path}liste_job.php">{_T string="Jobs list"}</a>
	</li>
	<li{if $PAGENAME eq "aaecotiz.php"} class="selected"{/if}>
		<a href="{$galette_base_path}{$aaetools_path}aaecotiz.php">{_T string="How to contribute ?"}</a>
	</li>
  <li{if $PAGENAME eq "liste_offres.php"} class="selected"{/if}>
          <a href="{$galette_base_path}{$aaetools_path}liste_offres.php">{_T string="Job offers list"}</a>
  </li>
  <li{if $PAGENAME eq "ajouter_offre.php"} class="selected"{/if}>
          <a href="{$galette_base_path}{$aaetools_path}ajouter_offre.php" title="{_T string="Add job offer"}">{_T string="Add job offer"}</a>
  </li>
{else}
	<li class="{if $PAGENAME eq "liste_eleves.php"}current_{/if}page_item">
		<a href="{$galette_base_path}{$aaetools_path}liste_eleves.php">{_T string="Former students list"}</a>
		<ul class='children'>
			<li class="page_item">
				<a href="{$galette_base_path}{$aaetools_path}liste_job.php">{_T string="Jobs list"}</a>
			</li>
		</ul>
	</li>
	<li class="{if $PAGENAME eq "aaecotiz.php"}current_{/if}page_item">
		<a href="{$galette_base_path}{$aaetools_path}aaecotiz.php">{_T string="How to contribute ?"}</a>
	</li>
	<li class="{if $PAGENAME eq "liste_offres.php"}current_{/if}page_item">
		<a href="{$galette_base_path}{$aaetools_path}liste_offres.php">{_T string="Job offers list"}</a>
		<ul class='children'>
			<li class="{if $PAGENAME eq "ajouter_offre.php"}current_{/if}page_item">
				<a href="{$galette_base_path}{$aaetools_path}ajouter_offre.php">{_T string="Add job offers"}</a>
			</li>
		</ul>
	</li>
	
	{if $login->isLogged()}	
	<li class="{if $PAGENAME eq "voir_adherent_public.php"}current_{/if}page_item">
		<a href="{$galette_base_path}{$aaetools_path}voir_adherent_public.php">{_T string="My shared profil"}</a>
		<ul class='children'>
			<li class="{if $PAGENAME eq "gestion_formations_eleve.php"}current_{/if}page_item">
				<a href="{$galette_base_path}{$aaetools_path}gestion_formations_eleve.php">{_T string="My formations"}</a>
			</li>
			<li class="{if $PAGENAME eq "gestion_postes.php"}current_{/if}page_item">
				<a href="{$galette_base_path}{$aaetools_path}gestion_postes.php">{_T string="My jobs"}</a>
			</li>
		</ul>
	</li>
	{/if}
{/if}
