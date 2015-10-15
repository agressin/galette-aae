<img src="{$galette_base_path}/templates/bootstrap/images/rss.png" width="20" height=20 alt="RSS"> </a> <a href="{$galette_base_path}{$aaetools_path}liste_offres.php?rss"> {_T string="Subscrite"}  / <a href="{$galette_base_path}{$aaetools_path}ajouter_offre.php"><img src="{$template_subdir}images/icon-add.png" align="middle" /> {_T string="Add job offers"}</a>
<br>


<form class="form-horizontal" action="liste_offres.php" method="post">
	<fieldset>
		<legend>{_T string="Select entreprise"}</legend>
			<div class="row col-sm-offset-1">
				<div class="form-group col-md-3">
					{*Searching poste by Entreprise*}
					<label for="id_entreprise" class="control-label">{_T string="Entreprise"}</label> <br>
					<select class="form-control" name="id_entreprise" id="id_cycle">
						<option value="" {if $id_entreprise eq ""} selected="selected"{/if} > -- {_T string="all"} -- </option>
						{foreach $entreprises  as $entreprise}
						<option value="{$entreprise.id_entreprise}" {if $entreprise.id_entreprise eq $id_entreprise} selected="selected"{/if} >{$entreprise.employeur}</option>
						{/foreach}
					</select>
				</div>
				<div class="form-group col-md-1"></div>
				<div class="form-group col-md-3">
					{*Searching poste by Type*}
					<label for="type" class="control-label">{_T string="Job Type"}</label> <br>
					<select class="selectpicker" name="type[]" id="type" multiple title="{_T string="Select one or more type"}" data-size="auto">
									<option value="Stage" {if in_array("Stage",$type)} selected="selected"{/if}>{_T string="Stage"}</option>
									<option value="CDD"   {if in_array("CDD",$type)} selected="selected"{/if}>{_T string="CDD"}</option>
									<option value="CDI"   {if in_array("CDI",$type)} selected="selected"{/if}>{_T string="CDI"}</option>
					</select>
				</div>
				<div class="form-group col-md-1"></div>
				<div class="form-group col-md-3">
					{*Searching poste by Skills*}
					<label for="domaines" class="control-label">{_T string="Skills"}</label> <br>
					<select class="selectpicker" name="domaines[]" id="domaines" multiple title="{_T string="Select one or more skill(s)"}" data-size="auto">
						{foreach from=$domaines key=k item=d}
						<option value="{$k}" {if in_array($k, $req_domaines)} selected="selected"{/if}>{$d|htmlspecialchars}</option>
						{/foreach}
						</select>
				</div>
			</div>

			<div class="row col-xs-offset-2">
				<div class="form-group">
				<div class=" col-sm-10">
					<button type="submit"  class="btn btn-primary">
						{if $param_selected} {_T string="Refresh"} {else} {_T string="Continue"} {/if}
					</button>
					<input type="hidden" name="valid" value="1"/>
				</div>
			</div>
	</fieldset>
</form>

{if $nb_offres > 0}
	<table id='table_offre' class="listing">
        <thead>
            <tr>
                <th>{_T string="Title"}</th>
                <th>{_T string="Enterprise"}</th>
                <th> {_T string="Publication date"} </th>
                <th> {_T string="Offer type"} </th>
        </thead>

        <tbody>
    		{foreach $offres as $offre}
	            <tr class="formation_row">
	                <td> <a href="{$galette_base_path}{$aaetools_path}liste_offres.php?id_offre={$offre.id_offre}"> {utf8_encode($offre.titre)} </a> </td>
	                <td><a href="liste_offres.php?id_entreprise={$offre.id_entreprise}">{$offre.employeur|htmlspecialchars}</a></td>
	                <td>{$offre.date_parution}</td>
	                <td><a href="liste_offres.php?type={$offre.type_offre}">{$offre.type_offre}</a></td>
	            </tr>
	    	{/foreach}
        </tbody>
    </table>

		<img src="{$galette_base_path}/templates/bootstrap/images/rss.png" width="20" height=20 alt="RSS"> </a> <a href="{$galette_base_path}{$aaetools_path}liste_offres.php?rss{if $id_entreprise neq ''}&id_entreprise={$id_entreprise}{/if}{if $type neq ''}&type={$type}{/if}{foreach $req_domaines as $dom}&domaines[]={$dom}{/foreach}"> {_T string="Subscrite with curent filter"}

    <script type="text/javascript">


		$('#table_offre').dynatable({
		  	features: {
			    paginate: true,
			    sort: true,
			    pushState: false,
			    search: true,
			    recordCount: true,
			    perPageSelect: true
			},
			inputs: {
				paginationPrev: '{_T string="Previous"}',
				paginationNext: '{_T string="Next"}',
				searchText: '{_T string="Search:"}',
			    perPageText: '{_T string="Show:"}',
			    pageText: '{_T string="Pages:"}'

			}
		});

		$(".dynatable-sort-header").css("color","black");

    </script>

{else}
    <div id="infobox">{_T string="No offer to show"}</div>
{/if} {* $nb_offres > 0 *}
