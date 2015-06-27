<?php

define('GALETTE_BASE_PATH', '../../');
require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';

//Constants and classes from plugin
require_once '_config.inc.php';

require_once 'lib/GaletteAAE/Postes.php';
use Galette\AAE\Postes as Postes;

require_once 'lib/GaletteAAE/Entreprises.php';
use Galette\AAE\Entreprises as Entreprises;

$postes = new Postes();
$entreprises = new Entreprises();
$member = new Galette\Entity\Adherent();


if ( !$login->isLogged() ) {
	header('location:'. GALETTE_BASE_PATH .'index.php');
	die();
}


// on add entreprise succes (from ajouter_ent.php)
if ( isset($_GET['ent_ok']) ) {
	$success_detected[] = _T("Entreprise has been successfully added.");
}

//Récupération de id_poste
$id_poste = '';
if(isset($_GET['id_poste'])) {
	$id_poste = $_GET['id_poste'];
}
else if(isset($_POST['id_poste'])) {
	$id_poste = $_POST['id_poste'];
}

//Récupération de id_adh
$id_adh = '';
if(isset($_GET['id_adh'])) {
	$id_adh = $_GET['id_adh'];
}
else if(isset($_POST['id_adh'])) {
	$id_adh = $_POST['id_adh'];
}



//Gestion des droits
if ( ($login->isAdmin() || $login->isStaff() || $id_adh == $login->id ) ){
    $haveRights = true;
}else{
    $haveRights = false;
}
$tpl->assign('vis',!$haveRights);

if ($id_adh =='') {
	$id_adh = $login->id;
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
if( isset($_POST['valid']) && $haveRights ){

	$res = $postes->setPoste(
		$id_poste,
		$id_adh,
		$_POST['activite_principale'],
		$_POST['type'],
		$_POST['encadrement'],
		$_POST['nb_personne_encadre'],
		$_POST['id_employeur'],
		$_POST['adresse'],
		$_POST['code_postal'],
		$_POST['ville'],
		$_POST['annee_ini'],
		$_POST['annee_fin']
	);
	if($res){
		$id_poste = $res;
		header('location:'. 'gestion_postes.php?pos_ok&id_adh='.$id_adh);
	}
	else {
		$error_detected[] = _T("Unabled to add/modify the job");
	}
}
    
#----------VISUALISATION----------#

if($id_poste){
    $tpl->assign('id_poste', $id_poste);

    $pst = $postes->getPoste($id_poste);
    $tpl->assign('poste',$pst);
	$member->load($pst['id_adh']);
}

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

// page generation
$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;
$content = $tpl->fetch('ajouter_poste.tpl');

$tpl->assign('content', $content);

//Set path back to main Galette's template
$tpl->template_dir = $orig_template_path;

$tpl->display('public_page.tpl');


?>
