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

if ( !$login->isLogged() ) {
    header('location:'. GALETTE_BASE_PATH .'index.php');
    die();
}

if (isset($_POST['employeur']))
{
 
	$res = $entreprises->getEntrepriseByName($_POST['employeur']);
	if ( count($res) > 0 ) {
		$warning_detected[] = _T("Entreprise already exist in the DB.");
	} else {	
		$res = $entreprises->setEntreprise(
			'',
			$_POST['employeur'],
			$_POST['employeur_website']
			);

		if ( !$res ) {
			$error_detected[] = _T("Entreprise has not been added!");
		} else {
			$session['ent_ok'] = true;
			if(isset($session['caller']))				
				header('location:'. $session['caller']);
			else
				header('location:'. 'ajouter_poste.php');
		    die();	
		}

	}
}

//Error
$tpl->assign('warning_detected', $warning_detected);
$tpl->assign('error_detected', $error_detected);

// page generation
$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;
$content = $tpl->fetch('ajouter_ent.tpl');

$tpl->assign('content', $content);

//Set path back to main Galette's template
$tpl->template_dir = $orig_template_path;

$tpl->display('public_page.tpl');


?>
