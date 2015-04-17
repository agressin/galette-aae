<?php

define('GALETTE_BASE_PATH', '../../');
require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';

//Constants and classes from plugin
require_once '_config.inc.php';

require_once 'lib/GaletteAAE/Annuaire.php';
use Galette\AAE\Annuaire as Annuaire;

require_once 'fonctions_json.php';

$annuaire = new Annuaire();

$idn = 0;
$ide = 1000;
$idh = -1;

$json = "elements: { ";
$nodes = "nodes[";
$edges = "edges[";

$nom = 'GUINARD';
$prenom = 'Stéphane';

$array = [];
$annee_debut = 9999;

$annee_fin = 0;

$array_points = $annuaire->getStudent($nom,$prenom);

//Récupération des parrains
$id_fillot1 = 1847;

$idp = $id_fillot1;

$roots = [];

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
				if (strlen ($infos[0][nom])>3){ //Si jamais les infos sur le parrain sont liées à son cursus IT3, in enlève 2 ans à son année d'entrée pour savoir quand il et arrivé à l'école
					$infos[0][annee_debut] = $infos[0][annee_debut] - 2;
				}
				//var_dump($infos[0][nom]);
				$annee_deb = $infos[0][annee_debut];
				if ($annee_deb < $annee_debut){
					$annee_debut = $annee_deb;
				}
				$idparrain2=getParrains($valeur);//On stocke les parrains
				if (empty($idparrain2)){
					$idparrain2 = $idh;
					$nodes = $nodes."{data:{id:".$idh.",name:".$idh."}},";
					$idh = $idh - 1;
					$edges = $edges."{data:{id:".$idh.",source:".$idh.",target:".$idp."}},";
				}
				//TODO : pour les redoublants : insérer un élément vide entre eux et ler fillot
				/*else if (){
					
				}*/
				else {
					$nodes = $nodes."{data:{id:".$idn.",name:".$infos[0][prenom_adh]." ".$infos[0][nom_adh]."}},";
					$idn = $idn+1;
					$idvieux = $idn-1;
					echo($idvieux);
					if ($idp != $id_fillot1){
						$edges = $edges."{data:{id:".$ide.",source:".$idvieux.",target:".$idp."}},";
						$ide = $ide+1;
					}
					else {
						$edges = $edges."{data:{id:".$ide.",source:".$idvieux.",target:9999}},";
						$ide = $ide+1;
						echo($idvieux);
					}
				}
			}
			$idp = $idvieux;
			$idparrain = $idparrain2;//On commence une nouvelle boucle avec les nouveaux parrains obtenus
} while (count($idparrain) > 0);

//Récupération des fillots
$id_parrain1 = 1847;
//Idem que pour les parrains mais avec les fillots
$idfillot = getFillots($id_parrain1);
$id_fillot = [];
$idfillot2 = [];

$idp = $id_fillot1;

do  {
	foreach ($idfillot as $cle => $valeur) 
			{
				//Recuperation des annees fin
				$infos = [];
				$infos = $annuaire->getInfoById($valeur);
				if (strlen ($infos[0][nom])>3){ //Si jamais les infos sur le parrain sont liées à son cursus IT3, in enlève 2 ans à son année d'entrée pour savoir quand il et arrivé à l'école
					$infos[0][annee_debut] = $infos[0][annee_debut] - 2;
				}
				$annee_finale = $infos[0][annee_debut];
				if ($annee_finale > $annee_fin){
					$annee_fin = $annee_finale;
				}
				$idfillot2=getFillots($valeur);
				$id_fillot[] = $valeur;
				$nodes = $nodes."{data:{id:".$idn.",name:".$infos[0][prenom_adh]." ".$infos[0][nom_adh]."}},";
				$idn = $idn+1;
				$idvieux = $idn-1;
				if ($idp != $id_fillot1){
					$edges = $edges."{data:{id:".$ide.",source:".$idp.",target:".$idvieux."}},";
					$ide = $ide+1;
				}
				else {
					$edges = $edges."{data:{id:".$ide.",source:9999,target:".$idvieux."}},";
					$ide = $ide+1;
				}
			}
			$idp = $idvieux;
			$idfillot = $idfillot2;
} while (count($idfillot) > 0);

$layout = "layout: {name: 'beadthfirst', directed: true, roots:'', padding: 10}";

$infos = $annuaire->getInfoById($id_fillot1);
$nodes = $nodes."{data:{id:9999,name:".$infos[0][prenom_adh]." ".$infos[0][nom_adh]."}}";
$edges = substr($edges,0,-1);
$json = $json.$nodes."],".$edges."]},".$layout;

if (!$fp = fopen("donnees.json","w+")) { 
	echo('Ca marche pas !!'); 
} else {
	fputs($fp,$json); // on écrit le nom et email dans le fichier
	fclose($fp);
}
//fclose($fp);
?>
