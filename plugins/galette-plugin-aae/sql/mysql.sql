CREATE TABLE IF NOT EXISTS galette_aae_cycles (
  id_cycle int(10) NOT NULL AUTO_INCREMENT,
  nom varchar(60) NOT NULL,
  PRIMARY KEY (id_cycle)
);

CREATE TABLE IF NOT EXISTS galette_aae_formations (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  id_cycle int(10) unsigned NOT NULL,
  specialite text NOT NULL,
  annee_debut int(4) NOT NULL,
  annee_fin int(4) NOT NULL,
  id_adh int(10) unsigned NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (id_adh) REFERENCES galette_adherents (id_adh)
);

CREATE TABLE IF NOT EXISTS galette_aae_offres (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  id_adh int(10) unsigned NOT NULL,
  titre text  NOT NULL,
  organisme text  NOT NULL,
  localisation text,
  site text,
  nom_contact text  NOT NULL,
  mail_contact text  NOT NULL,
  tel_contact text,
  date_parution date NOT NULL,
  date_fin date NOT NULL,
  type_offre enum('Stage','CDD','CDI') NOT NULL,
  desc_offre text,
  mots_cles text  NOT NULL,
  duree text,
  date_debut date NOT NULL,
  remuneration text,
  cursus text,
  rech_majeures text,
  PRIMARY KEY (id),
  FOREIGN KEY (id_adh) REFERENCES galette_adherents (id_adh)
);

DROP TABLE IF EXISTS `galette_aae_postes`;
CREATE TABLE `galette_aae_postes` (
  `id_poste` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `annee_ini` int(4) unsigned NOT NULL DEFAULT '2015',
  `annee_fin` int(4) unsigned DEFAULT NULL,
  `activite_principale` text COLLATE utf8_unicode_ci,
  `encadrement` boolean,
  `nb_personne_encadre` int(11) unsigned DEFAULT NULL,
  `adresse` varchar(160) COLLATE utf8_unicode_ci DEFAULT '',
  `code_postal` int(5) unsigned,
  `ville` varchar(160)  COLLATE utf8_unicode_ci DEFAULT '',
  `id_adh` int(10) unsigned NOT NULL,
  `id_entreprise` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_poste`),
  KEY `id_adh` (`id_adh`),
  KEY `id_entreprise` (`id_entreprise`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `galette_aae_entreprises`;
CREATE TABLE `galette_aae_entreprises` (
  `id_entreprise` int(10) NOT NULL AUTO_INCREMENT,
  `employeur` varchar(160) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `website` varchar(160) COLLATE utf8_unicode_ci DEFAULT '',
  PRIMARY KEY (`id_entreprise`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
