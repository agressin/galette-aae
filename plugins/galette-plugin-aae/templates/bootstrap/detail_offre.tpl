	<article id="post-107" class="post-107 post type-post">
		<header class="entry-header">
			<h1 class="entry-title">{utf8_encode($offre.titre)}</h1>
{* TODO : liste des attributs à afficher
 o Titre text  NOT NULL,
 o Organisme text  NOT NULL,
  Localisation text,
  Site text,
 o Nom_contact text  NOT NULL,
 o Mail_contact text  NOT NULL,
 o Tel_contact text,
 o Date_parution date NOT NULL,
 ? Date_fin date NOT NULL,
 o Type_offre enum('Stage','CDD','CDI') NOT NULL,
 o Desc_offre text,
 o Mots_cles text  NOT NULL,
 o Duree text,
 o Date_debut date NOT NULL,
 o Remuneration text,
  Cursus text,
  Tech_majeures text,
*}
			<div class="entry-meta">
			<span class="posted-on">
				{_T string="Posted on "} {date("d F o",strtotime($offre.date_parution))}
			</span>
			<span class="by">
				{_T string=" by "} {$offre.id_adh}{_T string=" from "} {utf8_encode($offre.oOrganisme)}
			</span>  <br>
			<span class="keyword">
				{_T string=" Key word(s):"} {utf8_encode($offre.mots_cles)}
			</span> <br>
			<span class="type">
				{_T string=" Type:"} {utf8_encode($offre.type_offre)}
			</span>
			<span class="duration">
				{_T string=" of "} {utf8_encode($offre.duree)}
			</span>
			<span class="from">
				{_T string=", begining "} {date("d F o",strtotime($offre.date_debut))}
			</span><br>
			<span class="salary">
				{_T string="Pay: "} {utf8_encode($offre.remuneration)}
			</span>	<br>
			<span class="contact">
				{_T string="Contact: "}
	{if $login->isLogged()}
				{_T string=" Name: "}
				{utf8_encode($offre.nom_contact)}
				{_T string=" , email: "}
				{utf8_encode($offre.mail_contact)}
				{_T string=" , phone: "}
				{utf8_encode($offre.tel_contact)}
	{else}
				{_T string="Please log in to access contact information."}
	{/if}
			</span>		
			</div><!-- .entry-meta -->
			</header><!-- .entry-header -->
			<div class="entry-content">
			<p style="text-align: justify;">{utf8_encode($offre.desc_offre)}</p>
			</div><!-- .entry-content -->
	
			<footer class="entry-meta">
				<ul class="entry-meta-taxonomy"></ul>
			</footer><!-- .entry-meta -->

		</article><!-- #post-## -->
