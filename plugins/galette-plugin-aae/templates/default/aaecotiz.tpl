<div class="bigtable">
 <fieldset class="cssform">
		<legend class="ui-state-active ui-corner-top">{_T string="Contribution process"}</legend>
		<h3> {_T string="Paiement par chèque"} </h3>
		<p>
			{_T string="Merci d'envoyer un chèque à l'ordre de l'AAE-ENSG à l'adresse suivante :"}
		<br>
		 	{$preferences->getPostalAdress()}
		</br>
		 	{_T string="et de prévenir de votre paiement en envoyant un mail à"}
			<a href='mailto:{$preferences->pref_email}'>{$preferences->pref_email}</a>
		</p>
		<h3> {_T string="Paiement par RIB"} </h3>
{if $login->isLogged()}
		<p>
			{_T string="Merci de faire un virement avec l'intitulé 'cotis nom prenom' sur le compte suivant :"} </br>
			{$AAE_pref->getRIB()} </br>
			{_T string="et de prévenir de votre paiement en envoyant un mail à"}
			<a href='mailto:{$preferences->pref_email}'>{$preferences->pref_email}</a>
			{_T string="En cas de virement groupé pour plusieurs personnes, merci de le préciser dans votre mail."}
		</p>
{else}
		<p>
			{_T string="Veuillez vous identifier pour obtenir le RIB, ou le demander à"}
			<a href='mailto:{$preferences->pref_email}'>{$preferences->pref_email}</a>
			</p>
{/if}
 </fieldset>
</div>
