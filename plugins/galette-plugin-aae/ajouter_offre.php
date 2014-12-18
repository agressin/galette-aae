<?php

define('GALETTE_BASE_PATH', '../../');
require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';

//Constants and classes from plugin
require_once '_config.inc.php';

require_once 'lib/GaletteAAE/Offres.php';
use Galette\AAE\Offres as Offres;


if (!$login->isLogged() ) {
    //header('location:'. GALETTE_BASE_PATH .'index.php');
    //die();
}

//print_r($login);

$valid = $login->isLogged();

$offres = new Offres();

$id_offre = get_numeric_form_value('id_offre', '');
$id_adh = get_numeric_form_value('id_adh', $login->id);

// template variable declaration
$title = _T("Job offer");
if ( $id_offre != '' ) {
    $title .= ' (' . _T("modification") . ')';
} else {
    $title .= ' (' . _T("creation") . ')';
}

$tpl->assign('page_title', $title);

if (isset($_POST['valid']) && $_POST['valid'] == '1') {
    //form was send normally, we try to store new values

    $res = $offres->setOffre(
		$id_offre,
		$id_adh,
		$_POST['titre_offre'],
		$_POST['organisme'],
		$_POST['localisation'],
		$_POST['site'],
		$_POST['nom_contact'],
		$_POST['mail_contact'],
		$_POST['tel_contact'],
		$_POST['date_fin'],
		$_POST['type_offre'],
		$_POST['desc_offre'],
		$_POST['mots_cles'],
		$_POST['duree'],
		$_POST['date_debut'],
		$_POST['remuneration'],
		$_POST['cursus'],
		$_POST['rech_majeures'],
		$valid
    );

    if ( !$res ) {
        $error_detected[] = _T("Offer has not been modified!");
    } else {
        $success_detected[] = _T("Offer has been successfully modified.");
    }
}

//Recup offre
if ($id_offre!='') {
	$offre = $offres->getOffre($id_offre);
	$tpl->assign('offer', $offre);
}else if ($login->isLogged() )  {
		$offre['nom_contact'] =  $login->name . " " . $login->surname;
		$tpl->assign('offer', $offre);
}

//Error
$tpl->assign('error_detected', $error_detected);
$tpl->assign('success_detected', $success_detected);

$tpl->assign('require_calendar', true);



// page generation
$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;
$content = $tpl->fetch('ajouter_offre.tpl');

$tpl->assign('content', $content);

//Set path back to main Galette's template
$tpl->template_dir = $orig_template_path;
$tpl->display('page.tpl');
?>
