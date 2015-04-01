{if !$public_page}
<li{if $PAGENAME eq "liste_eleves.php"} class="selected"{/if}><a href="{$galette_base_path}{$aaetools_path}public.php">{_T string="Former students list"}</a></li>
<li{if $PAGENAME eq "aaecotiz.php"} class="selected"{/if}><a href="{$galette_base_path}{$aaetools_path}aaecotiz.php">{_T string="How to contribute ?"}</a></li>
{else}
<a id="paae" class="button{if $PAGENAME eq "liste_eleves.php"} selected{/if}" href="{$galette_base_path}{$aaetools_path}liste_eleves.php">{_T string="Former students list"}</a>
<a id="paae" class="button{if $PAGENAME eq "aaecotiz.php"} selected{/if}" href="{$galette_base_path}{$aaetools_path}aaecotiz.php">{_T string="How to contribute ?"}</a>
{/if}
