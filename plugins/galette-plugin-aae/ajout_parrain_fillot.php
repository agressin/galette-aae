<?php
/***************************************************************************
 *					ajout_parrain_fillot.php
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

$parr=[];
$fill=[];
$str_result = [];

if ( !$preferences->showPublicPages($login) ) { //$login->isLogged())
    //public pages are not actives
    header('location:' . GALETTE_BASE_PATH  . 'index.php');
    die();
}

$cycles = new Cycles();
$annuaire = new Annuaire();

//récupération de ce qu'il y a dans le formulaire
$p = $_POST["parrain"];
$f = $_POST["fillot"];

//si les 2 sont renseignés
if ($p!="" && $f!="")
{
	//vérifie s'il y a bien 2 noms dans chaque case
	if(count(explode(" ", $p)) == 2 && count(explode(" ", $f)) == 2){
		//recherche par nom de ce qu'il y a dans le champ parrain
		$parr = $annuaire->rechercheSimplifieeParNom($p);
		$fill = $annuaire->rechercheSimplifieeParNom($f);
		
		//s'il n'y a qu'un résultat pour le parrain et pour le fillot
		if (count($parr) == 1 && count($fill) == 1){
			//vérification cohérence des année_début entre parrain et fillot
			$annee_parrain = $parr[0]["annee_debut"];
			$annee_fillot = $fill[0]["annee_debut"];
			$intannee_parrain = (int)$annee_parrain;
			$intannee_fillot = (int)$annee_fillot;
			$diff = $intannee_parrain-$intannee_fillot;
			if (($diff <= 2) && ($intannee_fillot > $intannee_parrain)){
				$id_parrain = $parr[0]["id_adh"];
				$id_fillot = $fill[0]["id_adh"];
				$str_result = "Le lien a bien été ajouté la base !";
			}
			else{
				$str_result = "Ce lien est incohérent en ce qui concerne les années. Il n'a pas été ajouté dans la base";
			}
		}
		//parrain ou fillot n'existe pas dans la base
		else if (count($parr) == 0){
			$str_result = "Le nom du parrain est introuvable dans la base";
		}
		else if (count($fill) == 0){
			$str_result = "Le nom du fillot est introuvable dans la base";
		}
		//Aucun des 2 n'existe dans la base
		else if (count($fill) == 0 && count($parr) == 0){
			$str_result = "Ces noms sont introuvables dans la base";
		}
		//plusieurs solutions
		else if (count($parr) > 1){
			$str_result = "Plusieurs solutions possibles pour le parrain";
		}
		else if (count($fill) > 1){
			$str_result = "Plusieurs solutions possibles pour le fillot";
		}
		else if (count($parr) > 1 && count($fill) > 1){
			$str_result = "Plusieurs solutions possibles pour parrain et fillot";
		}
	}
	else{
		$str_result = "Veuillez saisir le nom ET le prénom pour chacun";
	}	
}
else{
	$str_result = "Il faut saisir les 2 champs afin d'ajouter un lien";
}

$tpl->assign('page_title', _T("Add to the tree"));
$tpl->assign('result', $str_result);

//Set the path to the current plugin's templates,
//but backup main Galette's template path before
$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;

$content = $tpl->fetch('ajout_parrain_fillot.tpl');
$tpl->assign('content', $content);

//Set path back to main Galette's template
$tpl->template_dir = $orig_template_path;

if($id_parrain!='' && $id_fillot!=''){
	global $zdb;
	try {
		    $res  = null;
            $data = array(
				'id_parrain'=>$id_parrain,
				'id_fillot'=>$id_fillot
            );
			//insertion dans la BDD
			$insert = $zdb->insert(AAE_PREFIX . 'familles');
			$insert->values($data);
			$add = $zdb->execute($insert);
			
			if ( $add->count() == 0) {
				Analog::log('An error occured inserting new parrain!' );
			}
	} 
		
	catch (\Exception $e) {
		Analog::log(
			'Unable to retrieve parrain for "' .
			$id_fillot . '" | "" | ' . $e->getMessage(),
			Analog::WARNING
		);
		echo('raté');
		return false;
	}

}	
$tpl->display('public_page.tpl');
?>