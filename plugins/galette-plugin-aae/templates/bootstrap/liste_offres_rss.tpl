
{if $nb_offres > 0}
<?xml version='1.0' encoding='UTF-8'?><?xml-stylesheet type='text/xsl' href='http://rss.feedsportal.com/xsl/fr/rss.xsl'?>
<rss version="2.0">
    <channel>
        <title>AAE ENSG</title>
        <description>Liste des offres d'emplois</description>
        <lastBuildDate> {$smarty.now|date_format:'%a, %d %b %Y %H:%M:%S'} </lastBuildDate>
        
        <link>http://aae-ensg.dev/</link>
     		{foreach from=$offres item=offre}
			<item>
				<title>{utf8_encode($offre.titre)}</title>
				<organisme>{utf8_encode($offre.organisme)}</organisme>
				<pubDate>{date("D, d M o",strtotime($offre.date_parution))}</pubDate>
				<link>http://aae-ensg.dev/plugins/galette-plugin-aae/liste_offres.php?id_offre={$offre.id}</link>
			</item>
	    	{/foreach}
    </channel>
</rss>

{/if} {* $nb_offres > 0 *}
