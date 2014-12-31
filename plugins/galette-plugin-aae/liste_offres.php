<?php

define('GALETTE_BASE_PATH', '../../');
require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';

//Constants and classes from plugin
require_once '_config.inc.php';

require_once 'lib/GaletteAAE/Offres.php';
use Galette\AAE\Offres as Offres;
use Galette\Entity\Adherent as Adherent;

if ( !$preferences->showPublicPages($login) ) {
    //public pages are not actives
    header('location:' . GALETTE_BASE_PATH  . 'index.php');
    die();
}

$offres = new Offres();

$id_offre = get_numeric_form_value('id_offre', '');
$detail_mode = $id_offre !='';

$rss_mode = isset($_GET['rss']);

$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;

if($detail_mode){
	
	$offre = $offres->getOffre($id_offre);
	$tpl->assign('offre', $offre);
	
	$member = new Adherent();

	$member->load($offre['id_adh']);
	$tpl->assign('member', $member);

	$tpl->assign('page_title', _T("Job offer details"));

	$content = $tpl->fetch('detail_offre.tpl');
	$tpl->assign('content', $content);

} else {

	//Recup Offres
	$allOffres = $offres->getAllOffres();
	$tpl->assign('nb_offres', count($allOffres));

	$tpl->assign('offres', $allOffres);

	if($rss_mode) {
		$tpl->display('liste_offres_rss.tpl');
	} else {
		$tpl->assign('page_title', _T("Job offers list"));

		$content = $tpl->fetch('liste_offres.tpl');
		$tpl->assign('content', $content);
	}
}

if(! $rss_mode) {
	//Set path back to main Galette's template
	$tpl->template_dir = $orig_template_path;

	if($login->isLogged())
		$tpl->display('page.tpl');
	else
		$tpl->display('public_page.tpl');
}

