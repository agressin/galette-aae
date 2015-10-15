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

$param_selected = isset($_POST["valid"]) || isset($_GET["id_cycle"]) || isset($_GET["annnee_debut"]);

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
