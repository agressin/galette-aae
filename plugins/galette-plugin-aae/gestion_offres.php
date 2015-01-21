<?php

define('GALETTE_BASE_PATH', '../../');
require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';

//Constants and classes from plugin
require_once '_config.inc.php';

require_once 'lib/GaletteAAE/Offres.php';
use Galette\AAE\Offres as Offres;

if ( !$login->isLogged() ) {
    header('location:'. GALETTE_BASE_PATH .'index.php');
    die();
}

$tpl->assign('page_title', _T("Job offers managment"));
$offres = new Offres();

//Recup Offres
if ( $login->isAdmin() || $login->isStaff() ){
    $tpl->assign('haveRights', true);
    $allOffres = $offres->getAllOffres(false);
    //$allOffres = $offres->getOffresToValid();

}else{
    $tpl->assign('haveRights', false);
    $allOffres = $offres->getAllOffres();
}

$tpl->assign('nb_offres', count($allOffres));
$tpl->assign('offres', $allOffres);

$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;

$content = $tpl->fetch('gestion_offres.tpl');

$tpl->assign('content', $content);

//Set path back to main Galette's template
$tpl->template_dir = $orig_template_path;
$tpl->display('page.tpl');

