
<style type="text/css">
	.row {
	 -moz-column-width: 18em;
	 -webkit-column-width: 18em;
	 -moz-column-gap: 1em;
	 -webkit-column-gap:1em;  
	}
	
	.item {
	 display: inline-block;
	 padding:  .25rem;
	 width:  100%;
	 text-align: center;
	}
</style>


{if $nb_members > 0}
<div class="row">
	{foreach from=$members item=member}
        <div class="item">
            <img src="{$galette_base_path}picture.php?id_adh={$member->id}&amp;rand={$time}" height="{$member->picture->getOptimalHeight()}" width="{$member->picture->getOptimalWidth()}" alt="{$member->sfullname}{if $member->nickname ne ''} ({$member->nickname|htmlspecialchars}){/if}"/>
            <br/>{$member->sfullname}{if $member->nickname ne ''} ({$member->nickname|htmlspecialchars}){/if}
        </div>
    {/foreach}
</div>
{else}
        <div id="warningbox">{_T string="No member to show"}</div>
{/if}
