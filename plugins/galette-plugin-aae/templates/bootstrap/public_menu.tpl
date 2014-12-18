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
{else}
	<li class="{if $PAGENAME eq "liste_eleves.php"}current_{/if}page_item">
		<a href="{$galette_base_path}{$aaetools_path}liste_eleves.php">{_T string="Former students list"}</a>
	</li>
	<li class="{if $PAGENAME eq "aaecotiz.php"}current_{/if}page_item">
		<a href="{$galette_base_path}{$aaetools_path}aaecotiz.php">{_T string="How to contribute ?"}</a>
	</li>
	<li class="{if $PAGENAME eq "liste_offres.php"}current_{/if}page_item">
		<a href="{$galette_base_path}{$aaetools_path}liste_offres.php">{_T string="Job offers list"}</a>
	</li>
{/if}
