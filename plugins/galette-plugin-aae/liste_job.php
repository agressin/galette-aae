<?php

define('GALETTE_BASE_PATH', '../../');
require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';

//Constants and classes from plugin
require_once '_config.inc.php';

use Analog\Analog as Analog;
use Galette\Entity\Adherent as Adherent;
use Galette\Repository\Members as Members;

require_once 'lib/GaletteAAE/Annuaire.php';
use Galette\AAE\Annuaire as Annuaire;

require_once 'lib/GaletteAAE/Postes.php';
use Galette\AAE\Postes as Postes;

require_once 'lib/GaletteAAE/Entreprises.php';
use Galette\AAE\Entreprises as Entreprises;

if ( !$login->isLogged() ) {
    header('location:'. GALETTE_BASE_PATH .'index.php');
    die();
}

$adherent = new Adherent();
$postes = new Postes();
$entreprises = new Entreprises();
$annuaire = new Annuaire();

//Recuperation entreprises
$allEntreprises = $entreprises->getAllEntreprises();
foreach ($allEntreprises as $key => $entreprise) {
	$tmp[$key] = $entreprise["employeur"];
}
//Tri ascendant
array_multisort($tmp, SORT_ASC, $allEntreprises);
$tpl->assign('entreprises', $allEntreprises);

//We get variables
$entreprise = get_numeric_form_value('entreprise', '');

if($entreprise!=''){
	$postes = $annuaire -> getPromotion($cycle,$year);
}
else{
	$id_entreprise = get_numeric_form_value('id_entreprise', '');//cycle id
	$tri=$_POST['tri'];
	$tpl->assign('id_entreprise', $id_entreprise);
	
	$param_selected = ($id_entreprise != '');
	
	if($param_selected) {
			$postes = $postes->getPostesByEnt($id_entreprise);
		}
}	
// Obtient une liste de colonnes
foreach ($postes as $key => $row) {
	$id_poste[$key]=$row['id_adh'];
	$anne_ini[$key]  = $row['anne_ini'];
	$anne_fin[$key] = $row['anne_fin'];
	$activite_principale[$key] = $row['activite_principale'];
	$encadrement[$key] = $row['encadrement'];
	$nb_personne_encadre[$key] = $row['nb_personne_encadre'];
	$adresse[$key] = $row['adresse'];
	$code_postal[$key] = $row['code_postal'];
	$ville[$key] = $row['ville'];
	$id_adh[$key] = $row['id_adh'];
	$id_entreprise[$key] = $row['id_entreprise'];
	$postes[$key]['nom_adh'] = $adherent->getSName($id_adh[$key]);
}
	
// Ajoute $postes en tant que dernier paramètre, pour trier par la clé commune
$tpl->assign('annee_ini', $annee_ini);
$tpl->assign('annee_fin', $annee_fin);	
$tpl->assign('activite_principale', $activite_principale);
$tpl->assign('encadrement', $encadrement);
$tpl->assign('nb_personne_encadre', $nb_personne_encadre);
$tpl->assign('adresse', $adresse);
$tpl->assign('code_postal', $code_postal);
$tpl->assign('ville', $ville);
$tpl->assign('nom_adh', $nom_adh);
$tpl->assign('postes', $postes);
$tpl->assign('nb_postes', count($postes));
$tpl->assign('tri',$tri);
$tpl->assign('page_title', _T("Jobs list"));
$tpl->assign('param_selected', $param_selected);

//Set the path to the current plugin's templates,
//but backup main Galette's template path before
$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;

$content = $tpl->fetch('liste_job.tpl');
$tpl->assign('content', $content);

//Set path back to main Galette's template
$tpl->template_dir = $orig_template_path;
$tpl->display('public_page.tpl');

