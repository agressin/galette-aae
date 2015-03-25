<?php

define('GALETTE_BASE_PATH', '../../');
require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';

//Constants and classes from plugin
require_once '_config.inc.php';

require_once 'lib/GaletteAAE/Annuaire.php';
use Galette\AAE\Annuaire as Annuaire;

require_once 'lib/GaletteAAE/Cycles.php';
use Galette\AAE\Cycles as Cycles;


if ( !$preferences->showPublicPages($login) ) {
    //public pages are not actives
    header('location:' . GALETTE_BASE_PATH  . 'index.php');
    die();
}

$annuaire = new Annuaire();
$cycles = new Cycles();

//Recuperation cycles
$allCycles = $cycles->getAllCycles();
foreach ($allCycles as $key => $cycle) {
	$tmp[$key] = $cycle["nom"];
}
//Tri ascendant
array_multisort($tmp, SORT_ASC, $allCycles);
$tpl->assign('cycles', $allCycles);

$cycle = get_numeric_form_value('cycle', '');
$year = get_numeric_form_value('year', '');
if(($year!="")&&($cycle!="")){
	$eleves = $annuaire->getStudent(0,0,$year,0,$cycle);
}

// Obtient une liste de colonnes
foreach ($eleves as $key => $row) {
	$id_adh[$key]=$row['id_adh'];
	$nom[$key]  = $row['nom_adh'];
	$prenom[$key] = $row['prenom_adh'];
	$cycle[$key] = $row['nom'];
	$promo[$key] = $row['annee_debut'];
}

// Trie les données par nom et prenom croissant
// Ajoute $eleves en tant que dernier paramètre, pour trier par la clé commune
array_multisort($nom, SORT_ASC, $prenom, SORT_ASC, $eleves);

$tpl->assign('eleves', $eleves);
$tpl->assign('nb_eleves', count($eleves));


$tpl->assign('page_title', _T("Former students list"));
$tpl->assign('param_selected', $param_selected);
$tpl->assign('id_cycle', $id_cycle);
$tpl->assign('id_cycle_simple', $id_cycle_simple);
$tpl->assign('annee_debut', $annee_debut);

//Set the path to the current plugin's templates,
//but backup main Galette's template path before
$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;

$content = $tpl->fetch('promotion.tpl');
$tpl->assign('content', $content);

//Set path back to main Galette's template
$tpl->template_dir = $orig_template_path;
$tpl->display('public_page.tpl');