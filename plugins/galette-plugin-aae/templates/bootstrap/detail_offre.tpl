
	<article class="post type-post">
		<header class="entry-header">
			<h1 class="entry-title">{$offre.titre}</h1>
			<div class="entry-meta">
			<span class="posted-on">
				{_T string="Posted on "} {date("d/m/o",strtotime($offre.date_parution))}
			</span>
			<span class="by">
				{_T string=" by "} {$member->sfullname}
				{if  $offre.organisme!=""} {_T string=" from "} {$offre.organisme} {/if}
			</span>  <br>
			{if  $offre.mots_cles!=""}
			<span class="keyword">
				{_T string=" Key word(s):"} {$offre.mots_cles}
			</span> <br>
			{/if}
			
			<span class="type">
				{_T string=" Type:"} {$offre.type_offre}
			</span>
			{if $offre.duree!=""}
			<span class="duration">
				{_T string=" of "} {$offre.duree}
			</span>
			{/if}
			{if $offre.date_debut!="0000-00-00"}
			<span class="from">
				{_T string=", begining "} {date("d/m/o",strtotime($offre.date_debut))}
			</span>
			{/if}
			<br>
			{if $offre.remuneration!=""}
			<span class="salary">
				{_T string="Pay: "} {$offre.remuneration}
			</span> <br>
			{/if}
			<span class="contact">
				{_T string="Contact: "}
	{if $login->isLogged()}
				{$offre.nom_contact}
				{" "}
				{$offre.mail_contact}
				{" "}
				{$offre.tel_contact}
	{else}
				{_T string="Please log in to access contact information."}
	{/if}
			</span>		
			</div><!-- .entry-meta -->
			</header><!-- .entry-header -->
			<div class="entry-content">
			<h3> {_T string="Description: "}</h3>
			<p style="text-align: justify;">{$offre.desc_offre}</p>
			</div><!-- .entry-content -->
	
			<footer class="entry-meta">
				<ul class="entry-meta-taxonomy"></ul>
			</footer><!-- .entry-meta -->

		</article><!-- #post-## -->
