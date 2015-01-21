<?php

define('GALETTE_BASE_PATH', '../../');
require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';

//Constants and classes from plugin
require_once '_config.inc.php';

require_once 'lib/GaletteAAE/Formations.php';
use Galette\AAE\Formations as Formations;

require_once 'lib/GaletteAAE/Cycles.php';
use Galette\AAE\Cycles as Cycles;

if ( !$preferences->showPublicPages($login) ) {
    //public pages are not actives
    header('location:' . GALETTE_BASE_PATH  . 'index.php');
    die();
}

$formation = new Formations();
$cycles = new Cycles();

//Recup cycle
$allCycles = $cycles->getAllCycles();
foreach ($allCycles as $key => $cycle) {
	$tmp[$key] = $cycle["nom"];
}
//Tri
array_multisort($tmp, SORT_ASC, $allCycles);
$tpl->assign('cycles', $allCycles);

$simple = !empty($_POST['id_cycle_simple']);
if($simple) {
	$id_cycle_simple = $_POST['id_cycle_simple'];
}
$id_cycle = get_numeric_form_value('id_cycle', '');
$annee_debut = get_numeric_form_value('annee_debut', '');

$param_selected = ($simple || ($id_cycle != '') ) && ($annee_debut != '');

if($param_selected) {
	
	if($simple){
	
		switch ($id_cycle_simple) {
		case "IT":
		    $eleves = $formation->getIngPromotion($annee_debut);
		    break;
		case "G":
		    $eleves = $formation->getPromotion(52,$annee_debut);
		    break;
		case "DC":
		    $eleves = $formation->getDCPromotion($annee_debut);
		    break;
		}
	//
	
	} else{
		$eleves = $formation->getPromotion($id_cycle,$annee_debut);
	}
	


	// Obtient une liste de colonnes
	foreach ($eleves as $key => $row) {
		$nom[$key]  = $row['nom_adh'];
		$prenom[$key] = $row['prenom_adh'];
	}

	// Trie les données par nom et prenom croissant
	// Ajoute $eleves en tant que dernier paramètre, pour trier par la clé commune
	array_multisort($nom, SORT_ASC, $prenom, SORT_ASC, $eleves);

	$tpl->assign('eleves', $eleves);
	$tpl->assign('nb_eleves', count($eleves));
}

$tpl->assign('page_title', _T("Former students list"));
$tpl->assign('param_selected', $param_selected);
$tpl->assign('id_cycle', $id_cycle);
$tpl->assign('id_cycle_simple', $id_cycle_simple);
$tpl->assign('annee_debut', $annee_debut);

//Set the path to the current plugin's templates,
//but backup main Galette's template path before
$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;

$content = $tpl->fetch('liste_membres_aae.tpl');
$tpl->assign('content', $content);

//Set path back to main Galette's template
$tpl->template_dir = $orig_template_path;
$tpl->display('public_page.tpl');

