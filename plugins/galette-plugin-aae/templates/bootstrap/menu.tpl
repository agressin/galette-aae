<h1 class="nojs">{_T string="AAE"}</h1>
    <ul>
{if $login->isStaff() or $login->isAdmin()}  
        <li{if $PAGENAME eq "aaetools.php"} class="selected"{/if}>
			<a href="{$galette_base_path}{$aaetools_path}aaetools.php" title="{_T string="Various tools for AAE"}">{_T string="AAE Tools"}</a>
		</li>
{/if}
        <li{if $PAGENAME eq "gestion_formations_eleve.php"} class="selected"{/if}>
			<a href="{$galette_base_path}{$aaetools_path}gestion_formations_eleve.php">{_T string="My formations"}</a>
		</li>
        <li{if $PAGENAME eq "gestion_postes.php"} class="selected"{/if}>
			<a href="{$galette_base_path}{$aaetools_path}gestion_postes.php">{_T string="My jobs"}</a>
		</li>		
        <li{if $PAGENAME eq "aaecotiz.php"} class="selected"{/if}>
			<a href="{$galette_base_path}{$aaetools_path}aaecotiz.php" title="{_T string="Who to contribute ?"}">{_T string="Contribution"}</a>
		</li>
    </ul>
