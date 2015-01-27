{if !$public_page}
	<li{if $PAGENAME eq "liste_eleves.php"} class="selected"{/if}>
		<a href="{$galette_base_path}{$aaetools_path}liste_eleves.php">{_T string="Former students list"}</a>
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
	</li>
	<li class="{if $PAGENAME eq "aaecotiz.php"}current_{/if}page_item">
		<a href="{$galette_base_path}{$aaetools_path}aaecotiz.php">{_T string="How to contribute ?"}</a>
	</li>
	<li class="page_item page_item_has_children">
		<a href="{$galette_base_path}{$aaetools_path}liste_offres.php">{_T string="Job offers list"}</a>
		<ul class='children'>
		<li class="{if $PAGENAME eq "ajouter_offre.php"}current_{/if}page_item">
			<a href="{$galette_base_path}{$aaetools_path}ajouter_offre.php">{_T string="Add job offers"}</a>
		</li>
		</ul>
	</il>
{/if}
