<div>
		<h3 class="page-header"> {_T string="Montants des cotisations"} </h3>
		<p>
			{_T string="Les montants des différentes cotisations sont :"}
		<br>
		 	{$AAE_Pref->getPref('montant_cotis')}
		<br>
		</p>
		<h3 class="page-header"> {_T string="Paiement par chèque"} </h3>
		<p>
			{_T string="Merci d'envoyer un chèque à l'ordre de l'AAE-ENSG à l'adresse suivante :"}
		<br>
		 	{$preferences->getPostalAdress()}
		<br>
		 	{_T string="et de prévenir de votre paiement en envoyant un mail à"}
			<a href='mailto:{$AAE_Pref->getPref('mail_cotis')}'>{$AAE_Pref->getPref('mail_cotis')}</a>
		</p>
		<h3 class="page-header"> {_T string="Paiement par RIB"} </h3>
{if $login->isLogged()}
		<p>
			{_T string="Merci de faire un virement avec l'intitulé 'cotis nom prenom' sur le compte suivant :"} </br>
			{$AAE_Pref->getPref("rib")} </br>
			{_T string="et de prévenir de votre paiement en envoyant un mail à"}
			<a href='mailto:{$AAE_Pref->getPref('mail_cotis')}'>{$AAE_Pref->getPref('mail_cotis')}</a>
			{_T string="En cas de virement groupé pour plusieurs personnes, merci de le préciser dans votre mail."}
		</p>
{else}
		<p>
			{_T string="Veuillez vous identifier pour obtenir le RIB, ou le demander à"}
			<a href='mailto:{$AAE_Pref->getPref('mail_cotis')}'>{$AAE_Pref->getPref('mail_cotis')}</a>
			</p>
{/if}
</div>
