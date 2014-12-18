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
  Titre text  NOT NULL,
  Organisme text  NOT NULL,
  Localisation text,
  Site text,
  Nom_contact text  NOT NULL,
  Mail_contact text  NOT NULL,
  Tel_contact text,
  Date_parution date NOT NULL,
  Date_fin date NOT NULL,
  Type_offre enum('Stage','CDD','CDI') NOT NULL,
  Desc_offre text,
  Mots_cles text  NOT NULL,
  Duree text,
  Date_debut date NOT NULL,
  Remuneration text,
  Cursus text,
  Tech_majeures text,
  PRIMARY KEY (id),
  FOREIGN KEY (id_adh) REFERENCES galette_adherents (id_adh)
);
