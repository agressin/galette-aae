CREATE TABLE IF NOT EXISTS galette_aae_cycles (
  id_cycle int(10) unsigned NOT NULL AUTO_INCREMENT,
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
  FOREIGN KEY (id_adh) REFERENCES galette_adherents (id_adh),
  FOREIGN KEY (id_cycle) REFERENCES galette_cycles (id_cycle)
);


CREATE TABLE IF NOT EXISTS galette_aae_entreprises (
  id_entreprise int(10) unsigned NOT NULL AUTO_INCREMENT,
  employeur varchar(160) NOT NULL DEFAULT '',
  website varchar(160)  DEFAULT '',
  PRIMARY KEY (id_entreprise)
);

CREATE TABLE IF NOT EXISTS galette_aae_postes (
  id_poste int(10) unsigned NOT NULL AUTO_INCREMENT,
  id_adh int(10) unsigned NOT NULL,
  id_entreprise int(10) unsigned NOT NULL,
  type enum('Stage','CDD','CDI') NOT NULL,
  titre text DEFAULT '',
  activites text DEFAULT '',
  adresse text DEFAULT '',
  annee_ini int(4) unsigned NOT NULL DEFAULT '2015',
  annee_fin int(4) unsigned DEFAULT NULL,
  PRIMARY KEY (id_poste),
  FOREIGN KEY (id_adh) REFERENCES galette_adherents (id_adh),
  FOREIGN KEY (id_entreprise) REFERENCES galette_aae_entreprises (id_entreprise)
);


CREATE TABLE IF NOT EXISTS galette_aae_domaines (
  id_domaine int(10) unsigned NOT NULL AUTO_INCREMENT,
  nom varchar(60) NOT NULL,
  PRIMARY KEY (id_domaine)
);

CREATE TABLE IF NOT EXISTS galette_aae_liens_poste_domaine (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  id_poste int(10) unsigned NOT NULL,
  id_domaine int(10) unsigned NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (id_poste) REFERENCES galette_aae_postes (id_poste),
  FOREIGN KEY (id_domaine) REFERENCES galette_aae_domaines (id_domaine)
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

