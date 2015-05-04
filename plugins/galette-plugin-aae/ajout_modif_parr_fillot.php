<?php
/***************************************************************************
 *					ajout_modif_parr_fillot.php
 ***************************************************************************/
 
//Look for a student in the database

use Analog\Analog as Analog;
use Galette\Entity\Adherent as Adherent;
use Galette\Repository\Members as Members;

define('GALETTE_BASE_PATH', '../../');
require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';

//Constants and classes from plugin
require_once '_config.inc.php';

require_once 'lib/GaletteAAE/Annuaire.php';
use Galette\AAE\Annuaire as Annuaire;

require_once 'lib/GaletteAAE/Cycles.php';
use Galette\AAE\Cycles as Cycles;

require_once 'lib/GaletteAAE/Formations.php';
use Galette\AAE\Formations as Formations;


//require_once 'generation_json.js';
//require_once 'donnees_json.php';

//teste l'égalité d'une chaine de caractères ch au milieu de plusieurs chaines chaines
/*function chaines_egales($ch, $chaines){
	for($i=0;$i<count($chaines);$i++){
		 if ($ch === $chaines[$i]) {
			return $ch;
		}
	}
}*/



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
	$tmp[$key] = $cycle["parrain"];
}

//Tri ascendant
array_multisort($tmp, SORT_ASC, $allCycles);
$tpl->assign('cycles', $allCycles);


if ($_POST["parrain"]!="")
{
	$req = explode(" ", $_POST["parrain"]);
	$count = count($req);
	
	if($count == 1){
		echo("il manque une information");
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
		/*foreach($studentsName1 as $word){
			if ($researched_namefirst === $word){
				$found_namefirst = $word;
			}
		}*/
		//$found_namefirst=chaines_egales($researched_namefirst,$studentsName1);
		
		//Search the second word of the request as a surname
		$researched_surnamesecond=$req[1];
		//Text to lower, first letter to upper
		$researched_surnamesecond[0] = strtoupper($researched_surnamesecond[0]);
		$researched_surnamesecond = strtolower($researched_surnamesecond);
		//Get all students' surnames
		$studentsSurname1= $annuaire->getSurnameOfAllStudents();
		//Récupération des prénoms les plus proches
		$found_surnamesecond=$annuaire->proximite_levenshtein($researched_surnamesecond,$studentsSurname1);
		/*foreach($studentsSurname1 as $word){
			if ($researched_surnamesecond === $word){
				$found_surnamesecond = $word;
			}
		}*/
		//$found_surnamesecond=$annuaire->chaines_egales($researched_surnamesecond,$studentsSurname1);
		
		$parr = $annuaire -> getStudent($found_namefirst, $found_surnamesecond);
		
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
		/*foreach($studentsSurname2 as $word){
			if ($researched_surnamefirst === $word){
				$found_surnamefirst = $word;
			}
		}*/
		//$found_surnamefirst=$annuaire->chaines_egales($researched_surnamefirst,$studentsSurname2);
		
		
		//Search the second word of the request as a name
		$researched_namesecond=$req[1];
		//Text to upper
		$researched_namesecond = strtoupper($researched_namesecond);
		//Get all students' names
		$studentsName2= $annuaire->getNameOfAllStudents();
		//Récupération des noms les plus proches
		$found_namesecond=$annuaire->proximite_levenshtein($researched_namesecond,$studentsName2);
		/*foreach($studentsName2 as $word){
			if ($researched_namesecond === $word){
				$found_namesecond = $word;
			}
		}*/
		//$found_namesecond=$annuaire->chaines_egales($researched_namesecond,$studentsName2);
		
		$parr1 = $annuaire -> getStudent($found_namesecond, $found_surnamefirst);
		
		//UNION OF BOTH CASES TO DISPLAY THE RESULT		
		$parr = array_merge($parr, $parr1);
		//print_r($parr);
		if (count($parr) == 0){
			echo("il y a 0 possibilité");
		}
		else if (count($parr) == 1){
			echo("une seule possibilite");
		}
		else{
			echo("il y a plusieurs possibilités de noms de parrain");
		}
	}
	
};

//Recuperation cycles
$allCycles = $cycles->getAllCycles();
foreach ($allCycles as $key => $cycle) {
	$tmp[$key] = $cycle["fillot"];
}
	
if ($_POST["fillot"]!="")
{
	$req = explode(" ", $_POST["fillot"]);
	$count = count($req);

	if($count == 1){
		echo("il manque une information");
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
		/*foreach($studentsName1 as $word){
			if ($researched_namefirst === $word){
				$found_namefirst = $word;
			}
		}*/
		//$found_namefirst= chaines_egales($researched_namefirst, $studentsName1);
		
		//Search the second word of the request as a surname
		$researched_surnamesecond=$req[1];
		//Text to lower, first letter to upper
		$researched_surnamesecond[0] = strtoupper($researched_surnamesecond[0]);
		$researched_surnamesecond = strtolower($researched_surnamesecond);
		//Get all students' surnames
		$studentsSurname1= $annuaire->getSurnameOfAllStudents();
		//Récupération des prénoms les plus proches
		$found_surnamesecond=$annuaire->proximite_levenshtein($researched_surnamesecond,$studentsSurname1);
		/*foreach($studentsSurname1 as $word){
			if ($researched_surnamesecond === $word){
				$found_surnamesecond = $word;
			}
		}*/
		//$found_surnamesecond=chaines_egales($researched_surnamesecond,$studentsSurname1);
		
		$fill = $annuaire -> getStudent($found_namefirst, $found_surnamesecond);
		
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
		/*foreach($studentsSurname2 as $word){
			if ($researched_surnamefirst === $word){
				$found_surnamefirst = $word;
			}
		}*/
		//$found_surnamefirst=$annuaire->chaines_egales($researched_surnamefirst,$studentsSurname2);
		
		//Search the second word of the request as a name
		$researched_namesecond=$req[1];
		//Text to upper
		$researched_namesecond = strtoupper($researched_namesecond);
		//Get all students' names
		$studentsName2= $annuaire->getNameOfAllStudents();
		//Récupération des noms les plus proches
		$found_namesecond=$annuaire->proximite_levenshtein($researched_namesecond,$studentsName2);
		/*foreach($studentsName2 as $word){
			if ($researched_namesecond === $word){
				$found_namesecond = $word;
			}
		}*/
		//$found_namesecond=$annuaire->chaines_egales($researched_namesecond,$studentsName2);
		
		$fill1 = $annuaire -> getStudent($found_namesecond, $found_surnamefirst);
		
		//UNION OF BOTH CASES TO DISPLAY THE RESULT		
		$fill = array_merge($fill, $fill1);
		//print_r($fill);
		if (count($fill) == 0){
			echo("il y a 0 possibilité");
		}
		else if (count($fill) == 1){
			echo("une seule possibilite");
		}
		else{
			echo("il y a plusieurs possibilités de noms de fillot");
		}
	}
};


//Obtient une liste de colonnes
// foreach ($eleves as $key => $row) {
	// $id_adh[$key]=$row['id_adh'];
	// $nom[$key]  = $row['nom_adh'];
	// $prenom[$key] = $row['prenom_adh'];
// }

// Trie les données par nom et prenom croissant
// Ajoute $eleves en tant que dernier paramètre, pour trier par la clé commune
$tpl->assign('parrains', $parr);
$tpl->assign('fillots', $fill);
$tpl->assign('tri',$tri);
$tpl->assign('page_title', _T("Add or modify the tree"));

//Set the path to the current plugin's templates,
//but backup main Galette's template path before
$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;

$content = $tpl->fetch('ajout_modif_parr_fillot.tpl');
$tpl->assign('content', $content);

//Set path back to main Galette's template
$tpl->template_dir = $orig_template_path;
$tpl->display('public_page.tpl');



if($_POST["parrain"]!='' && $_POST["fillot"]!=''){
	global $zdb;

	try {
		$table_familles = AAE_PREFIX . 'familles';
		var_dump($fill[0]["id_adh"]);
		$data = array(
			'id_parrain' => $parr[0]["id_adh"],
			'id_fillot' => $fill[0]["id_adh"]
		);
		 
		$table_familles->insert($data);
	} catch (\Exception $e) {
		Analog::log(
			'Unable to retrieve members promotion for "' .
			$id_fillot  . '" | "" | ' . $e->getMessage(),
			Analog::WARNING
		);
		echo('raté');
		return false;
	}
}

?>