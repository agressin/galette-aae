<?php

define('GALETTE_BASE_PATH', '../../');
require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';

//Constants and classes from plugin
require_once '_config.inc.php';

require_once 'lib/GaletteAAE/Postes.php';
use Galette\AAE\Postes as Postes;

require_once 'lib/GaletteAAE/Entreprises.php';
use Galette\AAE\Entreprises as Entreprises;

require_once 'lib/GaletteAAE/Domaines.php';
use Galette\AAE\Domaines as Domaines;

$postes = new Postes();
$entreprises = new Entreprises();
$domaines = new Domaines();
$member = new Galette\Entity\Adherent();


if ( !$login->isLogged() ) {
	header('location:'. GALETTE_BASE_PATH .'index.php');
	die();
}

// on add entreprise succes (from ajouter_ent.php)
if ( isset($session['ent_ok'] )){
	$success_detected[] = _T("Entreprise has been successfully added.");
	unset($session['ent_ok']);
}

$id_poste = '';
$id_adh = '';

//Récupération de id_poste
if(isset($_GET['id_poste'])) {
	$id_poste = $_GET['id_poste'];
	unset($session['ajouter_poste']);
} else if(isset($_POST['id_poste'])) {
	$id_poste = $_POST['id_poste'];
	unset($session['ajouter_poste']);
} else if( isset($session['ajouter_poste']) and isset($session['ajouter_poste']['id_poste'])  ) {
	$id_poste = $session['ajouter_poste']['id_poste'];
}

if($id_poste != '')
{
    $poste = $postes->getPoste($id_poste);
    $id_adh = $poste['id_adh'];
} else {
	//Récupération de id_adh
	if(isset($_POST['id_adh'])) {
		$id_adh = $_POST['id_adh'];
	} else if(isset($_GET['id_adh'])) {
		$id_adh = $_GET['id_adh'];
	} else if( isset($session['ajouter_poste']) and isset($session['ajouter_poste']['id_adh']) ){
		$id_adh = $session['ajouter_poste']['id_adh'];
	} else {
		$id_adh = $login->id;
	}
}

//Gestion des droits
if ( !($login->isAdmin() || $login->isStaff() || $id_adh == $login->id ) ){
	header('location:'. GALETTE_BASE_PATH .'index.php');
	die();
}

$member->load($id_adh);



//Recupération des entreprises :
$allEntreprises = $entreprises->getAllEntreprises();
foreach ($allEntreprises as $entreprise) {
	$pk = Entreprises::PK;
	$name = $entreprise["employeur"];
	$entreprises_options[$entreprise[$pk]]["employeur"] = $name;
}
$tpl->assign('entreprises', $entreprises_options);

#----------CREATION / MODIFICATION ----------#
if( isset($_POST['valid']) ){

	$res = $postes->setPoste(
		$id_poste,
		$id_adh,
		$_POST['id_employeur'],
		$_POST['type'],
		$_POST['titre'],
		$_POST['activites'],
		$_POST['domaines'],
		$_POST['adresse'],
		$_POST['annee_ini'],
		$_POST['annee_fin']
	);
	
	if($res){
		$id_poste = $res;
		
		unset($session['ajouter_ent']);

		if( !isset($session['ajouter_poste']))
			$session['ajouter_poste'] = [];

		$session['ajouter_poste']['poste_ok'] = true;
		
		if( isset($session['ajouter_poste']['caller']) )
			$caller = $session['ajouter_poste']['caller'];
		else
			$caller ="gestion_postes.php";
		
		header('location:'. $caller);
		die();
	}
	else {
		$error_detected[] = _T("Unabled to add/modify the job");
	}
}
    
#----------VISUALISATION / MODIFICATION ----------#
if($id_poste != ''){
    $tpl->assign('id_poste', $id_poste);
    
    $poste['domaines'] = $domaines->getDomainesFromPoste($id_poste);
    $tpl->assign('poste',$poste);
}
$tpl->assign('domaines',$domaines->getAllDomaines() );

if($member->id != null)
	$tpl->assign('member', $member);
else
	$error_detected[] = _T("No member found.");   

//Erreur
if (isset($error_detected)) {
    $tpl->assign('error_detected', $error_detected);
}
if (isset($success_detected)) {
    $tpl->assign('success_detected', $success_detected);
}

//Session
if( !isset($session['ajouter_poste']))
	$session['ajouter_poste'] = [];

$session['ajouter_poste']['id_poste'] = $id_poste;
$session['ajouter_poste']['id_adh'] = $id_adh;


// page generation
$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;
$content = $tpl->fetch('ajouter_poste.tpl');

$tpl->assign('content', $content);

//Set path back to main Galette's template
$tpl->template_dir = $orig_template_path;

$tpl->display('public_page.tpl');


?>
