<?php

define('GALETTE_BASE_PATH', '../../');
require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';

//Constants and classes from plugin
require_once '_config.inc.php';

require_once 'lib/GaletteAAE/Annuaire.php';
use Galette\AAE\Annuaire as Annuaire;

require_once 'fonctions_json.php';

$annuaire = new Annuaire();

//$nom = $_POST['nom'];
//$prenom = $_POST['prenom'];

$nom = 'GUINARD';
$prenom = 'Stéphane';

$array = [];

$array_points = $annuaire->getStudent($nom,$prenom);

//Récupération des parrains
$id_fillot1 = 1847;

$idparrain = getParrains($id_fillot1);
$id_parrain = [];
$idparrain2 = [];
//Tant qu'on a des parrains on va chercher leurs parrains
do  {
	//Pour chaque parrain on chercher ses parrains
	foreach ($idparrain as $cle => $valeur) 
			{
				$idparrain2=getParrains($valeur);//On stocke les parrains
				$id_parrain[] = $valeur;//On les met dans un tableau qui contiendra tous les parrains les ns à la suite des autres
			}
			$idparrain = $idparrain2;//On commence une nouvelle boucle avec els nouveaux parrains obtenus
} while (count($idparrain) > 0);


var_dump($id_parrain); //Affichage


echo(' ');
//Récupération des fillots
$id_parrain1 = 1847;
//Idem que pour les parrains mais avec les fillots
$idfillot = getFillots($id_parrain1);
$id_fillot = [];
$idfillot2 = [];
do  {
	foreach ($idfillot as $cle => $valeur) 
			{
				$idfillot2=getFillots($valeur);
				$id_fillot[] = $valeur;
			}
			$idfillot = $idfillot2;
} while (count($ifillot) > 0);


var_dump($id_fillot);

//Construction type du JSON
$json_points = json_encode($array_points, JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE);

$array_nodes = "nodes" ;
$array_json_points = json_encode(array($json_points),JSON_UNESCAPED_UNICODE);
$nodes = json_encode("nodes".$array_json_points,JSON_UNESCAPED_UNICODE);
//echo ($nodes);

?>
