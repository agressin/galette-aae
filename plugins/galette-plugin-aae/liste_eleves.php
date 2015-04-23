<?php

define('GALETTE_BASE_PATH', '../../');
require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';

//Constants and classes from plugin
require_once '_config.inc.php';

require_once 'lib/GaletteAAE/Cycles.php';
use Galette\AAE\Cycles as Cycles;

require_once 'lib/GaletteAAE/Annuaire.php';
use Galette\AAE\Annuaire as Annuaire;


if ( !$preferences->showPublicPages($login) ) {
    //public pages are not actives
    header('location:' . GALETTE_BASE_PATH  . 'index.php');
    die();
}

$cycles = new Cycles();
$annuaire = new Annuaire();

//Recuperation cycles
$allCycles = $cycles->getAllCycles();
foreach ($allCycles as $key => $cycle) {
	$tmp[$key] = $cycle["nom"];
}
//Tri ascendant
array_multisort($tmp, SORT_ASC, $allCycles);
$tpl->assign('cycles', $allCycles);

$id_cycle = get_numeric_form_value('id_cycle', '');//cycle id
$id_cycle_simple = get_numeric_form_value('id_cycle_simple', '');//starting year
$id_formation = get_numeric_form_value('id_formation', '');//formation id same as cycle id but for a faster research
$annee_debut = get_numeric_form_value('annee_debut', '');//starting year
$tri=$_POST['tri'];
$tpl->assign('id_cycle', $id_cycle);

//If there is a name
if ($_POST["nom"]!="")
{	
	$researched_name=$_POST["nom"];
	
	//Text to uppercase
	$researched_name = strtoupper($researched_name);
	
	//Get all students name
	$studentsName = $annuaire->getNameOfAllStudents();
	
	//Récupération du nom le plus proche
	$found_name=$annuaire->proximite_levenshtein($researched_name,$studentsName);
};
	
if ($_POST["prenom"]!="")
{
	$researched_surname=$_POST["prenom"];
	
	//Transforme le prenom en minuscule
	$researched_surname = strtolower($researched_surname);
	
	//Transforme la premièrer lettre en majuscule
	$researched_surname[0] = strtoupper($researched_surname[0]);
	
	//Creation d'un tableau contenant les prénoms de chaque eleve
	$studentsSurname= $annuaire->getSurnameOfAllStudents();

	//Récupération du nom le plus proche
	$found_surname=$annuaire->proximite_levenshtein($researched_surname,$studentsSurname);
	
};

$param_selected = ((($id_cycle != '') && ($annee_debut != '')) || $found_name!=NULL || $found_surname!=Null);

if($param_selected) {
	
	if($id_cycle_simple==''){
		
		$eleves = $annuaire -> getStudent($found_name,$found_surname,$annee_debut,$id_formation,$id_cycle);
	}
	else{
	
		$eleves = $annuaire -> getStudent($found_name,$found_surname,$annee_debut,$id_formation,$id_cycle_simple);
		
	}
}

// Obtient une liste de colonnes
foreach ($eleves as $key => $row) {
	$id_adh[$key]=$row['id_adh'];
	$nom[$key]  = $row['nom_adh'];
	$prenom[$key] = $row['prenom_adh'];
	$cycletab[$key] = $row['nom'];
	$promo[$key] = $row['annee_debut'];
	$id_cycle[$key] = $row['id_cycle'];
}
	
// Trie les données par nom et prenom croissant
// Ajoute $eleves en tant que dernier paramètre, pour trier par la clé commune
array_multisort($nom, SORT_ASC, $prenom, SORT_ASC, $eleves);	
$tpl->assign('eleves', $eleves);
$tpl->assign('nb_eleves', count($eleves));
$tpl->assign('tri',$tri);
$tpl->assign('page_title', _T("Former students list"));
$tpl->assign('param_selected', $param_selected);
$tpl->assign('id_cycle_simple', $id_cycle_simple);
$tpl->assign('annee_debut', $annee_debut);

//Set the path to the current plugin's templates,
//but backup main Galette's template path before
$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;

$content = $tpl->fetch('liste_membres_aae.tpl');
$tpl->assign('content', $content);

//Set path back to main Galette's template
$tpl->template_dir = $orig_template_path;
$tpl->display('public_page.tpl');

