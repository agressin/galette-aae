<?php

define('GALETTE_BASE_PATH', '../../');
require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';

//Constants and classes from plugin
require_once '_config.inc.php';

require_once 'lib/GaletteAAE/Cycles.php';
use Galette\AAE\Cycles as Cycles;

require_once 'lib/GaletteAAE/Annuaire.php';
use Galette\AAE\Annuaire as Annuaire;

require_once 'lib/GaletteAAE/Preferences.php';
use Galette\AAE\Preferences as AAE_Preferences;


if ( !$preferences->showPublicPages($login) ) {
    //public pages are not actives
    header('location:' . GALETTE_BASE_PATH  . 'index.php');
    die();
}

$AAE_Pref = new AAE_Preferences();
$tpl->assign('AAE_Pref', $AAE_Pref);

$cycles = new Cycles();
$annuaire = new Annuaire();

//Recuperation cycles
$allCycles = $cycles->getAllCycles();
foreach ($allCycles as $key => $cycle) {
	$tmp[$key] = $cycle["nom"];
}
//Tri ascendant
array_multisort($tmp, SORT_ASC, $allCycles);
$tpl->assign('cycles', $allCycles);

$req = array();
$id_cycle = get_numeric_form_value('id_cycle', '0');
if ($id_cycle != '0') {
	$req["cycle"] = $id_cycle;
}
$tpl->assign('id_cycle', $id_cycle);

$id_cycle_simple = $_POST["id_cycle_simple"];
if ($id_cycle_simple!="") {
	$req["cycle_simple"] = $id_cycle_simple;
}
$tpl->assign('id_cycle_simple', $id_cycle_simple);

$annee_debut = get_numeric_form_value('annee_debut', '0');
if ($annee_debut != '0') {
	$req["annee_debut"] = $annee_debut;
}
$tpl->assign('annee_debut', $annee_debut);

$param_selected = isset($_POST["valid"]);
if ( !$login->isLogged() ) {
	//Non connecté : on est obligé de sélectionner 1 cycle ET 1 année de début
	$param_selected = (($id_cycle != '0') || ($id_cycle_simple != '')) && ($annee_debut != '0');
} else {
	// Connecté on peut ne rien saisir
	//ou chercher par nom/prenom
	$nom_prenom = $_POST["nom_prenom"];
	if ($nom_prenom!="")
	{	
		$req["nom_prenom"] = $nom_prenom;
	};
	$tpl->assign('nom_prenom', $nom_prenom);
}

$tpl->assign('param_selected', $param_selected);

if($param_selected) {
	
	$eleves = $annuaire -> getStudent($req);

	// Obtient une liste de colonnes
	foreach ($eleves as $key => $row) {
		//$id_adh[$key]=$row['id_adh'];
		$nom[$key]  = $row['nom_adh'];
		$prenom[$key] = $row['prenom_adh'];
		//$cycletab[$key] = $row['nom'];
		//$promo[$key] = $row['annee_debut'];
		//$id_cycle[$key] = $row['id_cycle'];
	}
		
	// Trie les données par nom et prenom croissant
	// Ajoute $eleves en tant que dernier paramètre, pour trier par la clé commune
	array_multisort($nom, SORT_ASC, $prenom, SORT_ASC, $eleves);
		
	$tpl->assign('eleves', $eleves);
	$tpl->assign('nb_eleves', count($eleves));
} else {
	$tpl->assign('nb_eleves', 0);
}

$tpl->assign('page_title', _T("Former students list"));

//Set the path to the current plugin's templates,
//but backup main Galette's template path before
$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;

$content = $tpl->fetch('liste_membres_aae.tpl');
$tpl->assign('content', $content);


//Set path back to main Galette's template
$tpl->template_dir = $orig_template_path;
$tpl->display('public_page.tpl');
