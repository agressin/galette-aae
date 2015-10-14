<?php

define('GALETTE_BASE_PATH', '../../');
require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';

//Constants and classes from plugin
require_once '_config.inc.php';

require_once 'lib/GaletteAAE/Cycles.php';
use Galette\AAE\Cycles as Cycles;

require_once 'lib/GaletteAAE/Postes.php';
use Galette\AAE\Postes as Postes;

require_once 'lib/GaletteAAE/Annuaire.php';
use Galette\AAE\Annuaire as Annuaire;

//require_once 'lib/GaletteAAE/Entreprises.php';
//use Galette\AAE\Entreprises as Entreprises;


if ( !$preferences->showPublicPages($login) ) {
    //public pages are not actives
    header('location:' . GALETTE_BASE_PATH  . 'index.php');
    die();
}

$postes = new Postes();
$annuaire = new Annuaire();

if ( !$login->isLogged() ) {
	//Non connecté : c'est fini
	die('Vous devez être connecté.');
}

//ini_set('display_errors', 1);

/**
	INITIALISATION
**/

$type = (isset($_GET["type"]) ? $_GET["type"] : 'mono'); // mono ou multi
$lieux = array();
$MAISONS = array();
$POSTES = array();

/**
	REQUETES MULTI OU MONO
**/


if ($type == 'multi') {

	$req = array();
	$id_cycle = get_numeric_form_value('id_cycle', '0');
	if ($id_cycle != '0') {
		$req["cycle"] = $id_cycle;
	}

	$id_cycle_simple = $_GET["id_cycle_simple"];
	if ($id_cycle_simple!="") {
		$req["cycle_simple"] = $id_cycle_simple;
	}

	$annee_debut = get_numeric_form_value('annee_debut', '0');
	if ($annee_debut != '0') {
		$req["annee_debut"] = $annee_debut;
	}

	$nom_prenom = $_GET["nom_prenom"];
	if ($nom_prenom!="") {
		$req["nom_prenom"] = $nom_prenom;
	};

  $req["group_by_adh"] = true;

	$eleves = $annuaire -> getStudent($req);

	// Obtient une liste de colonnes
	foreach ($eleves as $key) {
		$MAISONS = array_merge($annuaire->getGeoSpatialInfo($key['id_adh']),$MAISONS);
		$POSTES = array_merge($postes->getPostes(array('id_adh' => $key['id_adh'],'get_info_adh' => true)),$POSTES);
	}

} elseif ($type == 'mono') {
	$id_adh = (isset($_GET["id_adh"]) ? intval($_GET["id_adh"]) : '0');

  $MAISONS = $annuaire->getGeoSpatialInfo($id_adh);
  $POSTES = $postes->getPostes(array('id_adh' => $id_adh,'get_info_adh' => true));
}

/**
	CONVERSION EN LIEUX
**/

// Leurs maisons :
foreach ($MAISONS as $maison) {
	$adr = $maison['ville_adh'];
	$adr .= ($maison['cp_adh'] != '' ? ($adr != '' ? ', ' : '') . $maison['cp_adh'] : '');
	$adr .= ($maison['pays_adh'] != '' ? ($adr != '' ? ', ' : '') . $maison['pays_adh'] : '');
	if ($adr != '') {
		array_push($lieux, array(
			'title' => $maison['nom_adh'] . ' ' . $maison['prenom_adh'],
			'info' => '<p class="title">' . $maison['nom_adh'] . ' ' . $maison['prenom_adh'] . '</p><p class="center">Logement</p>',
			'adresse' => $adr,
			'coords' => false, // TODO : tester si les coordonnées existent dans la base et les envoyer directement
			'type' => 'logement'
		));
	}
}

// Ou leurs postes :
foreach ($POSTES as $poste) {
	//print_r($poste);
	$adr = $poste['adresse'];
	$adr .= ($poste['code_postal'] != '' ? ($adr != '' ? ', ' : '') . $poste['code_postal'] : '');
	$adr .= ($poste['ville'] != '' ? ($adr != '' ? ', ' : '') . $poste['ville'] : '');
	if ($adr != '') {
		// Creation des info :
		$info = '';
		$info .= '<p class="title">' . $poste['nom_adh'] . ' ' . $poste['prenom_adh'] . '</p>';
		$info .= '<p><a href="voir_adherent_public.php?id_adh=' . $poste['id_adh'] . '" title="' . $poste['nom_adh'] . ' ' . $poste['prenom_adh'] . '">' . $poste['titre'] . '</a></p>';
		// TODO : Lien vers la bonne page

		array_push($lieux, array(
			'title' => $poste['nom_adh'] . ' ' . $poste['prenom_adh'],
			'info' => $info,
			'adresse' => $adr,
			'coords' => false, // TODO : tester si les coordonnées existent dans la base et les envoyer directement
			'type' => 'poste'
		));
	}
}


/**
	RETOUR
**/

echo json_encode($lieux);
?>
