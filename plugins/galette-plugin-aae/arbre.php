<?php
/***************************************************************************
 *					arbre.php
 ***************************************************************************/
$eleves=array();
 
define('GALETTE_BASE_PATH', '../../');
require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';

//Constants and classes from plugin
require_once '_config.inc.php';

require_once 'lib/GaletteAAE/Annuaire.php';
use Galette\AAE\Annuaire as Annuaire;

require_once 'lib/GaletteAAE/Cycles.php';
use Galette\AAE\Cycles as Cycles;

//require_once 'generation_json.js';
//require_once 'donnees_json.php';


if ( !$preferences->showPublicPages($login) ) { //$login->isLogged())
    //public pages are not actives
    header('location:' . GALETTE_BASE_PATH  . 'index.php');
    die();
}


$cycles = new Cycles();
$annuaire = new Annuaire();

//Recuperation cycles
$allCycles = $cycles->getAllCycles();
foreach ($allCycles as $key => $cycle) {
	$tmp[$key] = $cycle["nomprenom"];
}

//Tri ascendant
array_multisort($tmp, SORT_ASC, $allCycles);
$tpl->assign('cycles', $allCycles);


	
if ($_POST["nomprenom"]!="")
{
	$req = explode(" ", $_POST["nomprenom"]);
	$count = count($req);

	if($count == 1){
		//Search the words of the request as names
		$researched_name=$req[0];			
		//Text to upper
		$researched_name = strtoupper($researched_name);			
		//Get all students' names
		$studentsName = $annuaire->getNameOfAllStudents();			
		//Récupération du nom le plus proche
		$found_name=$annuaire->proximite_levenshtein($researched_name,$studentsName);
		//Récupération de l'élève avec ce nom ou nom proche
		$eleves = $annuaire -> getStudent($found_name);
		
		//Search the words of the request as surnames
		$researched_surname=$req[0];
		//Text to lower, first letter to upper
		$researched_surname[0] = strtoupper($researched_surname[0]);
		$researched_surname = strtolower($researched_surname);
		//Get all students' surnames
		$studentsSurname= $annuaire->getSurnameOfAllStudents();
		//Récupération du prénom le plus proche
		$found_surname=$annuaire->proximite_levenshtein($researched_surname,$studentsSurname);
		//Récupération de l'élève avec ce prenom ou prenom proche
		$elevesprenom = $annuaire -> getStudent(NULL, $found_surname);
		
		//concatène les 2 tableaux obtenus
		$eleves = array_merge($eleves, $elevesprenom);
	}
	
	if($count == 2){
		//CASE NAME SURNAME
		//Search the first word of the request as a name
		$researched_namefirst=$req[0];			
		//Text to upper
		$researched_namefirst = strtoupper($researched_namefirst);			
		//Get all students name
		$studentsName1 = $annuaire->getNameOfAllStudents();			
		//Récupération des noms les plus proches
		$found_namefirst=$annuaire->proximite_levenshtein($researched_namefirst,$studentsName1);
		
		//Search the second word of the request as a surname
		$researched_surnamesecond=$req[1];
		//Text to lower, first letter to upper
		$researched_surnamesecond[0] = strtoupper($researched_surnamesecond[0]);
		$researched_surnamesecond = strtolower($researched_surnamesecond);
		//Get all students' surnames
		$studentsSurname1= $annuaire->getSurnameOfAllStudents();
		//Récupération des prénoms les plus proches
		$found_surnamesecond=$annuaire->proximite_levenshtein($researched_surnamesecond,$studentsSurname1);
		
		$eleves = $annuaire -> getStudent($found_namefirst, $found_surnamesecond);
		
		//CASE SURNAME NAME
		//Search the first word of the request as a surname
		$researched_surnamefirst=$req[0];					
		//Text to lower, first letter to upper
		$researched_surnamefirst[0] = strtoupper($researched_surnamefirst[0]);
		$researched_surnamefirst = strtolower($researched_surnamefirst);
		//Get all students name
		$studentsSurname2 = $annuaire->getSurnameOfAllStudents();			
		//Récupération des prénoms les plus proches
		$found_surnamefirst=$annuaire->proximite_levenshtein($researched_surnamefirst,$studentsSurname2);
		
		//Search the second word of the request as a name
		$researched_namesecond=$req[1];
		//Text to upper
		$researched_namesecond = strtoupper($researched_namesecond);
		//Get all students' names
		$studentsName2= $annuaire->getNameOfAllStudents();
		//Récupération des noms les plus proches
		$found_namesecond=$annuaire->proximite_levenshtein($researched_namesecond,$studentsName2);
		
		$eleves1 = $annuaire -> getStudent($found_namesecond, $found_surnamefirst);
		
		//UNION OF BOTH CASES TO DISPLAY THE RESULT		
		$eleves = array_merge($eleves, $eleves1);
	}
	
};
	

// Obtient une liste de colonnes
foreach ($eleves as $key => $row) {
	$id_adh[$key]=$row['id_adh'];
	$nom[$key]  = $row['nom_adh'];
	$prenom[$key] = $row['prenom_adh'];
}

	
// Trie les données par nom et prenom croissant
// Ajoute $eleves en tant que dernier paramètre, pour trier par la clé commune
array_multisort($nom, SORT_ASC, $prenom, SORT_ASC, $eleves);	
$tpl->assign('eleves', $eleves);
$tpl->assign('nb_eleves', count($eleves));
$tpl->assign('tri',$tri);
$tpl->assign('page_title', _T("Parrain-fillots tree"));

//Set the path to the current plugin's templates,
//but backup main Galette's template path before
$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;

$content = $tpl->fetch('arbre.tpl');
$tpl->assign('content', $content);

//Set path back to main Galette's template
$tpl->template_dir = $orig_template_path;
$tpl->display('public_page.tpl');
