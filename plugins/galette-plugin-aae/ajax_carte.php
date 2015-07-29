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


if ( !$preferences->showPublicPages($login) ) {
    //public pages are not actives
    header('location:' . GALETTE_BASE_PATH  . 'index.php');
    die();
}

$cycles = new Cycles();
$postes = new Postes();
$annuaire = new Annuaire();

//Recuperation cycles
$allCycles = $cycles->getAllCycles();
foreach ($allCycles as $key => $cycle) {
	$tmp[$key] = $cycle["nom"];
}
//Tri ascendant
array_multisort($tmp, SORT_ASC, $allCycles);

if ( !$login->isLogged() ) {
	//Non connecté : c'est fini
	die('Vous devez être connecté.');
}

// ini_set('display_errors', 1);

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
	$id_cycle = (isset($_GET["id_cycle"]) ? intval($_GET["id_cycle"]) : '0');
	if ($id_cycle != '0') {
		$req["cycle"] = $id_cycle;
	}

	$id_cycle_simple = $_GET["id_cycle_simple"];
	if ($id_cycle_simple!="") {
		$req["cycle_simple"] = $id_cycle_simple;
	}

	$annee_debut = (isset($_GET["annee_debut"]) ? intval($_GET["annee_debut"]) : '0');
	if ($annee_debut != '0') {
		$req["annee_debut"] = $annee_debut;
	}

	//If there is a name or surname
	$nom_prenom = $_GET["nom_prenom"];
	if ($nom_prenom!="") {	
		$req["nom_prenom"] = $nom_prenom;
	};

	// Lancement de la requetes :
		
	$eleves = $annuaire -> getStudent($req);

	// Obtient les id de tous les résultats de la recherche :
	foreach ($eleves as $key => $row) {
		// On recupere ou leur logement :
		$maisons = $annuaire -> getGeoSpatialInfo($row['id_adh']);
		foreach ($maisons as $maison) {array_push($MAISONS, $maison);}

		// Ou leurs postes :
		$lesPostes = $postes -> getPostes($row['id_adh']);
		foreach ($lesPostes as $poste) {
			$tps = $poste; // on ajout le nom et prenom, pas envoyes par getPostes
			$tps['nom_adh'] = $row['nom_adh'];
			$tps['prenom_adh'] = $row['prenom_adh'];
			array_push($POSTES, $tps);
		}
	}

} elseif ($type == 'mono') {
	$id_adh = (isset($_GET["id_adh"]) ? intval($_GET["id_adh"]) : '0');

	// Recuperation du logement :
	$maisons = $annuaire -> getGeoSpatialInfo($id_adh);
	foreach ($maisons as $maison) {array_push($MAISONS, $maison);}

	// Ou des postes :
	$info = $annuaire -> getInfoById($id_adh); // on recup le nom et prenom, pas envoyes par getPostes
	$lesPostes = $postes -> getPostes($id_adh);
	foreach ($lesPostes as $poste) {
			$tps = $poste; // on ajout le nom et prenom, pas envoyes par getPostes
			$tps['nom_adh'] = $info[0]['nom_adh'];
			$tps['prenom_adh'] = $info[0]['prenom_adh'];
			array_push($POSTES, $tps);
	}
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
			'info' => '<p class="title">' . $maison['nom_adh'] . ' ' . $maison['prenom_adh'] . '</p><p class="center">Logement</p>',
			'adresse' => $adr,
			'type' => 'logement'
		));
	}
}

// Ou leurs postes :
foreach ($POSTES as $poste) {
	$adr = $poste['adresse'];
	$adr .= ($poste['code_postal'] != '' ? ($adr != '' ? ', ' : '') . $poste['code_postal'] : '');
	$adr .= ($poste['ville'] != '' ? ($adr != '' ? ', ' : '') . $poste['ville'] : '');
	if ($adr != '') {
		// Creation des info :
		$info = '';
		$info .= '<p class="title">' . $poste['nom_adh'] . ' ' . $poste['prenom_adh'] . '</p>';
		$info .= '<p>Activité : ' . $poste['activite_principale'] . '</p>';
		$info .= '<p>Dates : ' . $poste['annee_ini'] . ' - ' . $poste['annee_fin'] . '</p>';

		array_push($lieux, array(
			'info' => $info,
			'adresse' => $adr,
			'type' => 'poste'
		));
	}
}


/**
	RETOUR
**/

echo json_encode($lieux);
?>