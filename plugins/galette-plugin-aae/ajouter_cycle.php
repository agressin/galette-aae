<?php

define('GALETTE_BASE_PATH', '../../');
require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';

//Constants and classes from plugin
require_once '_config.inc.php';

require_once 'lib/GaletteAAE/Cycles.php';
use Galette\AAE\Cycles as Cycles;

$cycles = new Cycles();


if ( !($login->isAdmin() || $login->isStaff()) ) {
    //public pages are not actives
    header('location:' . GALETTE_BASE_PATH  . 'index.php');
    die();
}


if (isset($_GET['id_cycle'])){
	$id_cycle = $_GET['id_cycle'];
} else if (isset($_POST['id_cycle'])){
	$id_cycle = $_POST['id_cycle'];
} else {
	$id_cycle = '';
}

if($id_cycle != '')
{
    $cycle = $cycles->getcycle($id_cycle);
} else {
	#TODO : quoi ??
}

#----------CREATION / MODIFICATION ----------#
if( isset($_POST['valid']) ){

	$res = $cycles->setCycle(
		$id_cycle,
		$_POST['nom'],
		$_POST['detail']
	);

	if($res){
		$id_cycle = $res;
		$session['cycle_ok'] = true;
		$caller ="gestion_cycles.php";
		header('location:'. $caller);
		die();
	}
	else {
		$error_detected[] = _T("Unabled to add/modify the cycle");
	}
}

#----------VISUALISATION / MODIFICATION ----------#
if($id_cycle != ''){
    $tpl->assign('id_cycle', $id_cycle);
		$cycle = $cycles->getcycle($id_cycle);
    $tpl->assign('cycle',$cycle);
}

//Erreur
if (isset($error_detected)) {
    $tpl->assign('error_detected', $error_detected);
}
if (isset($success_detected)) {
    $tpl->assign('success_detected', $success_detected);
}

// page generation
$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;
$content = $tpl->fetch('ajouter_cycle.tpl');

$tpl->assign('content', $content);

//Set path back to main Galette's template
$tpl->template_dir = $orig_template_path;

$tpl->display('public_page.tpl');


?>
