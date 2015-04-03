<?php
/***************************************************************************
 *					arbre.php
 ***************************************************************************/

define('GALETTE_BASE_PATH', '../../');
require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';

//Constants and classes from plugin
require_once '_config.inc.php';

require_once 'lib/GaletteAAE/Annuaire.php';
use Galette\AAE\Annuaire as Annuaire;

require_once 'lib/GaletteAAE/Cycles.php';
use Galette\AAE\Cycles as Cycles;


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


//if ($nomprenom != ''){
	//search among "ingenieurs"
	//$id_cycle_simple = 51;//starting year
	//$annee_debut = get_numeric_form_value('annee_debut', '');//starting year
	//$tri=$_POST['tri'];
	//$tpl->assign('id_cycle', $id_cycle);
	
	//If there is a name
	if ($_POST["nomprenom"]!="")
	{
		$req = explode(" ", $_POST["nomprenom"]);
		$count = count($req);
		echo($count);
		$eleves = array();

		if($count == 1){
			//Search the words of the request as names
			$researched_name=$req[0];			
			//Text to upper
			$researched_name = strtoupper($researched_name);			
			//Get all students name
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
			//Get all students surname
			$studentsSurname= $annuaire->getSurnameOfAllStudents();
			//Récupération du prénom le plus proche
			$found_surname=$annuaire->proximite_levenshtein($researched_surname,$studentsSurname);
			//Récupération de l'élève avec ce prenom ou prenom proche
			$elevesprenom = $annuaire -> getStudent(NULL, $found_surname);
			
			//concatène les 2 tableaux obtenus
			$eleves = array_merge($eleves, $elevesprenom);
		}
		
		if($count == 2){
			$found_name = array();
			$found_surname = array();
			for ($i=0; $i<$count; $i++){
				//Search the words of the request as names
				$researched_name=$req[$i];			
				//Text to upper
				$researched_name = strtoupper($researched_name);			
				//Get all students name
				$studentsName = $annuaire->getNameOfAllStudents();			
				//Récupération du nom le plus proche
				$found_name1=$annuaire->proximite_levenshtein($researched_name,$studentsName);
				$found_name = array_merge($found_name, $found_name1);
				
				//Search the words of the request as surnames
				$researched_surname=$req[$i];
				//Text to lower, first letter to upper
				$researched_surname[0] = strtoupper($researched_surname[0]);
				$researched_surname = strtolower($researched_surname);
				//Get all students surname
				$studentsSurname= $annuaire->getSurnameOfAllStudents();
				//Récupération du prénom le plus proche
				$found_surname1=$annuaire->proximite_levenshtein($researched_surname,$studentsSurname);
				$found_surname = array_merge($found_surname, $found_surname1);
			}
		
			//Récupération de l'élève
			for($i=0; $i<count($found_name); $i++){
				for($j=0; $j<count($found_surname); $j++){
					$eleves = $annuaire -> getStudent($found_name[$i], $found_surname[$j]);
					$eleves1 = $annuaire -> getStudent($found_surname[$j], $found_name[$i]);
				}
			}
			
			//concatène les 2 tableaux obtenus
			$eleves = array_merge($eleves, $eleves1);
		}

	};
	

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

$content = $tpl->fetch('arbre.tpl');
$tpl->assign('content', $content);

//Set path back to main Galette's template
$tpl->template_dir = $orig_template_path;
$tpl->display('public_page.tpl');