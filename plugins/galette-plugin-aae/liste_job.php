<?php

define('GALETTE_BASE_PATH', '../../');
require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';

//Constants and classes from plugin
require_once '_config.inc.php';

use Analog\Analog as Analog;
use Galette\Entity\Adherent as Adherent;

require_once 'lib/GaletteAAE/Annuaire.php';
use Galette\AAE\Annuaire as Annuaire;

require_once 'lib/GaletteAAE/Postes.php';
use Galette\AAE\Postes as Postes;

require_once 'lib/GaletteAAE/Entreprises.php';
use Galette\AAE\Entreprises as Entreprises;

require_once 'lib/GaletteAAE/Preferences.php';
use Galette\AAE\Preferences as AAE_Preferences;

if ( !$login->isLogged() ) {
    header('location:'. GALETTE_BASE_PATH .'index.php');
    die();
}

$AAE_Pref = new AAE_Preferences();
$tpl->assign('AAE_pref', $AAE_Pref);

$adherent = new Adherent();
$postes = new Postes();
$entreprises = new Entreprises();

//Recuperation entreprises
$allEntreprises = $entreprises->getAllEntreprises(true);
foreach ($allEntreprises as $key => $entreprise) {
	$tmp[$key] = $entreprise["employeur"];
}
//Tri ascendant
array_multisort($tmp, SORT_ASC, $allEntreprises);
$tpl->assign('entreprises', $allEntreprises);

//We get variables
$id_entreprise='';
$is_valid = get_numeric_form_value('valid', false);

if(isset($_POST['id_entreprise'])) {
	$id_entreprise = $_POST['id_entreprise'];
} else if(isset($_GET['id_entreprise'])) {
	$id_entreprise = $_GET['id_entreprise'];
	$is_valid=true;
}

$tpl->assign('id_entreprise', $id_entreprise);

if($is_valid) {
	$postes = $postes->getPostesByEnt($id_entreprise);
	$nb_postes = count($postes);

	// Obtient une liste de colonnes
	foreach ($postes as $key => $row) {
		$id_adh[$key] = $row['id_adh'];
		$postes[$key]['nom_adh'] = $adherent->getSName($id_adh[$key]);
	}
	$tpl->assign('postes', $postes);
	$tpl->assign('param_selected',true);
} else {
	$nb_postes = 0;
}


$tpl->assign('nb_postes', $nb_postes);

$tpl->assign('page_title', _T("Jobs list"));

//Set the path to the current plugin's templates,
//but backup main Galette's template path before
$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;

$content = $tpl->fetch('liste_job.tpl');
$tpl->assign('content', $content);

//Set path back to main Galette's template
$tpl->template_dir = $orig_template_path;
$tpl->display('public_page.tpl');
