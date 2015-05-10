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

$parr=[];
$fill=[];

if ( !$preferences->showPublicPages($login) ) { //$login->isLogged())
    //public pages are not actives
    header('location:' . GALETTE_BASE_PATH  . 'index.php');
    die();
}

$cycles = new Cycles();
$annuaire = new Annuaire();

//get ce qu'il y a dans le hidden
$id_fillot = $_GET["id_f"];
$id_parrain = $_GET["id_p"];

//si le parrain n'est pas encore recherch�
if ($id_parrain=="")
{
	//recherche par nom de ce qu'il y a dans le champ parrain
	$parr = $annuaire->rechercheParNom($_POST["parrain"]);
	//assigner le parrain � rien dans le tpl
	$tpl->assign('parrain', '');
}else {
	//si le parrain est d�j� recherch�, on assigne ce qu'il y a dans la case � parrain
	$tpl->assign('parrain', $_POST["parrain"]);
	//si l'id du fillot n'est pas renseign�
	if ($id_fillot=="")
	{
		$fill = $annuaire->rechercheParNom($_POST["fillot"]);
		$tpl->assign('fillot', '');
	}else{
		$tpl->assign('fillot', $_POST["fillot"]);
	}
}


$val = $_GET["value"];

// Trie les donn�es par nom et prenom croissant
// Ajoute $eleves en tant que dernier param�tre, pour trier par la cl� commune
$tpl->assign('parrains', $parr);
$tpl->assign('nb_parr', count($parr));
$tpl->assign('fillots', $fill);
$tpl->assign('nb_fill', count($fill));
$tpl->assign('id_p', $id_parrain);
$tpl->assign('id_f', $id_fillot);

$tpl->assign('page_title', _T("Add to the tree"));
$tpl->assign('value', $val);

//Set the path to the current plugin's templates,
//but backup main Galette's template path before
$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;

$content = $tpl->fetch('ajout_modif_parr_fillot.tpl');
$tpl->assign('content', $content);

//Set path back to main Galette's template
$tpl->template_dir = $orig_template_path;
$tpl->display('public_page.tpl');

$annuaire->maFonction($content);

if($_POST["id_parrain"]!='' && $_POST["id_fillot"]!=''){
	global $zdb;

	try {
		$table_familles = AAE_PREFIX . 'familles';
		var_dump($fill[0]["id_adh"]);
		$data = array(
			'id_parrain'=>$id_parrain,
			'id_fillot'=>$id_fillot
			//'id_parrain' => $parr[0]["id_adh"],
			//'id_fillot' => $fill[0]["id_adh"]
		);
		 
		$table_familles->insert($data);
	} catch (\Exception $e) {
		Analog::log(
			'Unable to retrieve members promotion for "' .
			$id_fillot  . '" | "" | ' . $e->getMessage(),
			Analog::WARNING
		);
		echo('rat�');
		return false;
	}
}

?>