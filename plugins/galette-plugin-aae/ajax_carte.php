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

$cycles = new Cycles();
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

function recupererInfoAdherent($id_adh, $MAISONS, $POSTES) {
	$postes = new Postes();
	$annuaire = new Annuaire();
	//$entreprises = new Entreprises();

	// Recuperation du logement :
	$maisons = $annuaire -> getGeoSpatialInfo($id_adh);
	foreach ($maisons as $maison) {array_push($MAISONS, $maison);}

	// Et des postes :
	$info = $annuaire -> getInfoById($id_adh); // on recup le nom et prenom, pas envoyes par getPostes
	$lesPostes = $postes -> getPostes($id_adh);
	foreach ($lesPostes as $poste) {
		//print_r($poste);
			$tps = $poste; // on ajout le nom et prenom, pas envoyes par getPostes
			$tps['nom_adh'] = $info[0]['nom_adh'];
			$tps['prenom_adh'] = $info[0]['prenom_adh'];
			// On ajoute le nom de l'entreprise, pas envoyé par poste :
			//$ent = $entreprises->getEntreprise($poste['id_entreprise']);
			//$tps['employeur'] = $ent['employeur'];
			array_push($POSTES, $tps);
	}

	// Retour :
	return array(
		'MAISONS' => $MAISONS,
		'POSTES' => $POSTES
	);
}

if ($type == 'multi') {
	//Recuperation cycles
	$allCycles = $cycles->getAllCycles();
	foreach ($allCycles as $key => $cycle) {
		$tmp[$key] = $cycle["nom"];
	}
	//Tri ascendant
	array_multisort($tmp, SORT_ASC, $allCycles);

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
		
	$eleves = $annuaire -> getStudent($req);

	// Obtient une liste de colonnes
	foreach ($eleves as $key => $row) {
		$temp = recupererInfoAdherent($row['id_adh'], $MAISONS, $POSTES);
		$MAISONS = $temp['MAISONS'];
		$POSTES = $temp['POSTES'];
	}

} elseif ($type == 'mono') {
	$id_adh = (isset($_GET["id_adh"]) ? intval($_GET["id_adh"]) : '0');

	$temp = recupererInfoAdherent($id_adh, $MAISONS, $POSTES);
	$MAISONS = $temp['MAISONS'];
	$POSTES = $temp['POSTES'];
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
		$info .= '<p><a href="voir_adherent_public.php?id_adh=' . $poste['id_adh'] . '" title="' . $poste['nom_adh'] . ' ' . $poste['prenom_adh'] . '">' . $poste['activite_principale'] . '</a></p>';
		// TODO : Lien vers la bonne page

		array_push($lieux, array(
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