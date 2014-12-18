<?php

define('GALETTE_BASE_PATH', '../../');
require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';

//Constants and classes from plugin
require_once '_config.inc.php';


require_once 'lib/GaletteAAE/Offres.php';
use Galette\AAE\Offres as Offres;

if ( !$preferences->showPublicPages($login) ) {
    //public pages are not actives
    header('location:' . GALETTE_BASE_PATH  . 'index.php');
    die();
}

$offres = new Offres();

$id_offre = get_numeric_form_value('id_offre', '');
$detail_mode= $id_offre !='';

$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;

if($detail_mode){
	
	$offre = $offres->getOffre($id_offre);
	$tpl->assign('offre', $offre);

	$tpl->assign('page_title', _T("Job offer details"));

	$content = $tpl->fetch('detail_offre.tpl');
	$tpl->assign('content', $content);

} else {

	//Recup Offres
	$allOffres = $offres->getAllOffres();
	$tpl->assign('nb_offres', count($allOffres));

	$tpl->assign('offres', $allOffres);
	$tpl->assign('page_title', _T("Job offers list"));

	$content = $tpl->fetch('liste_offres.tpl');
	$tpl->assign('content', $content);
}

//Set path back to main Galette's template
$tpl->template_dir = $orig_template_path;

$tpl->display('public_page.tpl');

