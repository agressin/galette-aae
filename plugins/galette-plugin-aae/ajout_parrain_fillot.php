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

//$parr=[];
//$fill=[];

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
			//vérification années
			//if (($parr[0]["annee_debut"]-$fillot[0]["annee_debut"]) <= 2{
				//echo((int)($parr[0]["annee_debut"])-(int)($fillot[0]["annee_debut"]));
				//echo("ok !");
				$diff = (int)($parr[0]["annee_debut"]-$fillot[0]["annee_debut"]);
				echo($diff);
				$id_parrain = $parr[0]["id_adh"];
				$id_fillot = $fill[0]["id_adh"];
		}
		//n'existe pas dans la base
		else if (count($parr) == 0 || count($fill) == 0){
			echo("un des noms n'est pas dans la base");
		}
		//plusieurs solutions
		else if (count($parr) > 1 && count($fill) > 1){
			echo("plusieurs solutions possibles");
		}
	}
	else{
		echo("rentrez nom+prénom pour chacun");
	}	
}

$tpl->assign('page_title', _T("Add to the tree"));
$tpl->assign('value', $val);

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