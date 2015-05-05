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
function chaines_egales($ch, $chaines){
	for($i=0;$i<count($chaines);$i++){
		 if ($ch === $chaines[$i]) {
			return $ch;
		}
	}
}

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
	$parr = $annuaire->rechercheParNom($_POST["parrain"]);
};

//Recuperation cycles
$allCycles = $cycles->getAllCycles();
foreach ($allCycles as $key => $cycle) {
	$tmp[$key] = $cycle["fillot"];
}
	
if ($_POST["fillot"]!="")
{
	$fill = $annuaire->rechercheParNom($_POST["fillot"]);
};

$id_fillot = $_GET["id_f"];
$id_parrain = $_GET["id_p"];
$val = $_GET["value"];

// Trie les données par nom et prenom croissant
// Ajoute $eleves en tant que dernier paramètre, pour trier par la clé commune
$tpl->assign('parrains', $parr);
$tpl->assign('nb_parr', count($parr));
$tpl->assign('fillots', $fill);
$tpl->assign('nb_fill', count($fill));
$tpl->assign('tri',$tri);
$tpl->assign('page_title', _T("Add or modify the tree"));
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

if($_POST["parrain"]!='' && $_POST["fillot"]!=''){
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
		echo('raté');
		return false;
	}
}

?>