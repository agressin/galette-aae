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
$annee_debut = 9999;
$annee_fin = 0;

$array_points = $annuaire->getStudent($nom,$prenom);
//var_dump($array_points[0][id_adh]); //Affiche l'id d'une personne
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
				
				//Recuperation des annees debut
				$infos = [];
				$infos = $annuaire->getInfoById($valeur);
				$annee_deb = $infos[0][annee_debut];
				if ($annee_deb < $annee_debut){
					$annee_debut = $annee_deb;
				}
				$idparrain2=getParrains($valeur);//On stocke les parrains
				$id_parrain[] = $valeur;//On les met dans un tableau qui contiendra tous les parrains les ns à la suite des autres
			}
			$idparrain = $idparrain2;//On commence une nouvelle boucle avec les nouveaux parrains obtenus
} while (count($idparrain) > 0);


//var_dump($id_parrain); //Affichage
//echo($annee_debut);


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
				//Recuperation des annees fin
				$infos = [];
				$infos = $annuaire->getInfoById($valeur);
				$annee_finale = $infos[0][annee_debut];
				if ($annee_finale > $annee_fin){
					$annee_fin = $annee_finale;
				}
				$idfillot2=getFillots($valeur);
				$id_fillot[] = $valeur;
			}
			$idfillot = $idfillot2;
} while (count($ifillot) > 0);

//echo($annee_fin);
//var_dump($id_fillot);

//Construction type du JSON
$json_points = json_encode($array_points, JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE);

$array_nodes = "nodes" ;
$array_json_points = json_encode(array($json_points),JSON_UNESCAPED_UNICODE);
$nodes = json_encode("nodes".$array_json_points,JSON_UNESCAPED_UNICODE);
//echo ($nodes);

?>
