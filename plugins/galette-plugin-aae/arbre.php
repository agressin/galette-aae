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

require_once 'donnees_json.php';


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
	$eleves = $annuaire->rechercheParNom($_POST["nomprenom"]);
};
	

// Obtient une liste de colonnes
foreach ($eleves as $key => $row) {
	$id_adh[$key]=$row['id_adh'];
	$nom[$key]  = $row['nom_adh'];
	$prenom[$key] = $row['prenom_adh'];
}

//Lecture du fichier json
$contenu_json = "";
$lines = file("donnees.json");
foreach($lines as $n => $line){
	$contenu_json .=$line;
}

	
// Trie les données par nom et prenom croissant
// Ajoute $eleves en tant que dernier paramètre, pour trier par la clé commune
array_multisort($nom, SORT_ASC, $prenom, SORT_ASC, $eleves);	
$tpl->assign('eleves', $eleves);
$tpl->assign('nb_eleves', count($eleves));
$tpl->assign('tri',$tri);
$tpl->assign('page_title', _T("Parrain-fillots tree"));
$tpl->assign('content_json', $contenu_json);

//Set the path to the current plugin's templates,
//but backup main Galette's template path before
$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;

$content = $tpl->fetch('arbre.tpl');
$content .= $tpl->fetch('arbreJS.tpl');
$tpl->assign('content', $content);

//Set path back to main Galette's template
$tpl->template_dir = $orig_template_path;
$tpl->display('public_page.tpl');
