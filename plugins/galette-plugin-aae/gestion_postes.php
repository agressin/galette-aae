<?php

define('GALETTE_BASE_PATH', '../../');

require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';

//Constants and classes from plugin
require_once '_config.inc.php';

require_once 'lib/GaletteAAE/Postes.php';
use Galette\AAE\Postes as Postes;


if ( !$login->isLogged() ) {
    header('location:'. GALETTE_BASE_PATH .'index.php');
    die();
}

// on add poste succes (from ajouter_poste.php)
if ( isset($session['ajouter_poste']) ) {
	if( isset($session['ajouter_poste']['poste_ok']) ) {
		$success_detected[] = _T("Job has been successfully added.");
	}
	unset($session['ajouter_poste']);
}

$postes = new Postes();

$member = new Galette\Entity\Adherent();

$id_adh = $login->id;

if ( $login->isAdmin() || $login->isStaff() ) {
	//Récupération de id_adh
	if( isset($session['gestion_postes']) and isset($session['gestion_postes']['id_adh']) ){
		$id_adh = $session['gestion_postes']['id_adh'];
	} else if(isset($_GET['id_adh']) && $_GET['id_adh'] != '' ) {
		$id_adh = $_GET['id_adh'];
	}
}
$tpl->assign('id_adh', $id_adh);

//Liste les postes
$list_postes = $postes->getPostes(array('id_adh' => $id_adh));
$tpl->assign('list_postes', $list_postes);


$member->load($id_adh);
$tpl->assign('member', $member);

if(! isset($session['gestion_postes.php'])){
	$session['gestion_postes.php'] = [];
}
$session['gestion_postes.php']['id_adh'] = $id_adh;

$nom = $member->sfullname;
$tpl->assign('page_title', _T('Jobs managment').' '.$nom);


//Messages
if (isset($success_detected)) {
    $tpl->assign('success_detected', $success_detected);
}
if (isset($error_detected)) {
    $tpl->assign('error_detected', $error_detected);
}
if (isset($warning_detected)) {
    $tpl->assign('warning_detected', $warning_detected);
}

//Set the path to the current plugin's templates,
//but backup main Galette's template path before
$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;

$content = $tpl->fetch('gestion_postes.tpl');
$tpl->assign('content', $content);

//Set path back to main Galette's template
$tpl->template_dir = $orig_template_path;

$tpl->display('public_page.tpl');

?>
