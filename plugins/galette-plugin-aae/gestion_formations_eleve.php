<?php

define('GALETTE_BASE_PATH', '../../');

require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';

//Constants and classes from plugin
require_once '_config.inc.php';

require_once 'lib/GaletteAAE/Formations.php';
use Galette\AAE\Formations as Formations;

require_once 'lib/GaletteAAE/Cycles.php';
use Galette\AAE\Cycles as Cycles;

require_once 'lib/GaletteAAE/Preferences.php';
use Galette\AAE\Preferences as AAE_Preferences;



if ( !$login->isLogged() ) {
    header('location:'. GALETTE_BASE_PATH .'index.php');
    die();
}

$AAE_Pref = new AAE_Preferences();
$tpl->assign('AAE_Pref', $AAE_Pref);

$formation = new Formations();
$cycles = new Cycles();

//Recupération des cycles :
$allCycles = $cycles->getAllCycles(false);
$tpl->assign('cycles', $allCycles);

$member = new Galette\Entity\Adherent();

//Gestion des droits : on ne peut voir que ses formations, sauf si on est admin / staff
$id_adh = $login->id;
if ( ($login->isAdmin() || $login->isStaff()) && isset($_GET['id_adh']) && $_GET['id_adh'] != '' ) {
	$id_adh = $_GET['id_adh'];
}

//
$tpl->assign('haveRights', ($login->isAdmin() || $login->isStaff()));

$list_formations = $formation->getFormations($id_adh);
$tpl->assign('list_formations', $list_formations);

//
$member->load($id_adh);
$tpl->assign('member', $member);

if (isset($error_detected)) {
    $tpl->assign('error_detected', $error_detected);
}
if (isset($warning_detected)) {
    $tpl->assign('warning_detected', $warning_detected);
}

$nom = $member->sfullname;
$tpl->assign('page_title', _T("Formations managment:")." ".$nom);

//Set the path to the current plugin's templates,
//but backup main Galette's template path before
$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;

$content = $tpl->fetch('gestion_formations.tpl');
$tpl->assign('content', $content);

//Set path back to main Galette's template
$tpl->template_dir = $orig_template_path;

if ($login->isAdmin() || $login->isStaff())
	$tpl->display('page.tpl');
else
	$tpl->display('public_page.tpl');

?>
