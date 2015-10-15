<?php

define('GALETTE_BASE_PATH', '../../');
require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';

//Constants and classes from plugin
require_once '_config.inc.php';

require_once 'lib/GaletteAAE/Offres.php';
use Galette\AAE\Offres as Offres;

require_once 'lib/GaletteAAE/Entreprises.php';
use Galette\AAE\Entreprises as Entreprises;

require_once 'lib/GaletteAAE/Domaines.php';
use Galette\AAE\Domaines as Domaines;

use Galette\Entity\Adherent as Adherent;

if ( !$preferences->showPublicPages($login) ) {
    //public pages are not actives
    header('location:' . GALETTE_BASE_PATH  . 'index.php');
    die();
}

$offres = new Offres();
$detail_mode = false;
$id_offre = get_numeric_form_value('id_offre', '');
if($id_offre !='') {
	$offre = $offres->getOffre($id_offre);
  $offre['domaines'] = $offres->getDomainesFromOffreToString($id_offre);

	$detail_mode = ! empty($offre); # si l'offre n'existe pas, on reste sur la liste des offres
}
$rss_mode = isset($_GET['rss']);

$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;

if($detail_mode){
	$tpl->assign('offre', $offre);

	$member = new Adherent();

	$member->load($offre['id_adh']);
	$tpl->assign('member', $member);

	$tpl->assign('page_title', _T("Job offer details"));

	$content = $tpl->fetch('detail_offre.tpl');
	$tpl->assign('content', $content);

} else {
  $entreprises = new Entreprises();
  $domaines = new Domaines();

  //Recuperation entreprises
  $allEntreprises = $entreprises->getAllEntreprises(false,true);
  $tpl->assign('entreprises', $allEntreprises);
  $tpl->assign('domaines',$domaines->getAllDomaines());

  //We get variables
  $is_valid = get_numeric_form_value('valid', false);

  $req = array();
  $id_entreprise='';
  if(isset($_POST['id_entreprise'])) {
  	$req["entreprise"] = $_POST['id_entreprise'];
  } else if(isset($_GET['id_entreprise'])) {
  	$req["entreprise"] = $_GET['id_entreprise'];
  	$is_valid=true;
  }
  $tpl->assign('id_entreprise', $req["entreprise"]);

  if(isset($_POST['type'])) {
  	$req["type"] = $_POST['type'];
  } else if(isset($_GET['type'])) {
  	$req["type"] = $_GET['type'];
  	$is_valid=true;
  }
  $tpl->assign('type', $req["type"]);

  if(isset($_POST['domaines'])) {
  	$req["domaines"] = $_POST['domaines'];
  } else if(isset($_GET['domaines'])) {
  	$req["domaines"] = $_GET['domaines'];
  	$is_valid=true;
  }
  $tpl->assign('req_domaines', $req["domaines"]);

	//Recup Offres
  $list_offres = $offres->getOffres($req);
	$tpl->assign('nb_offres', count($list_offres));

	$tpl->assign('offres', $list_offres);

	if($rss_mode) {
		$tpl->assign('url', "http://".$_SERVER['HTTP_HOST']);
		header("Content-Type: text/xml");
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
	$tpl->display('public_page.tpl');
}
