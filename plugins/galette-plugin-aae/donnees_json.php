<?php

define('GALETTE_BASE_PATH', '../../');
require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';

//Constants and classes from plugin
require_once '_config.inc.php';

require_once 'lib/GaletteAAE/Annuaire.php';
use Galette\AAE\Annuaire as Annuaire;

require_once 'fonctions_json.php';

$annuaire = new Annuaire();

//echo("coucou");

$idn = 0;
$ide = 1000;
$idh = -1;

$json = '{"elements": { ';
$nodes = '"nodes":[';
$edges = '"edges":[';

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
	$idparrain2 = [];
	$idparrain3 = [];
	//Pour chaque parrain on chercher ses parrains
	foreach ($idparrain as $cle => $valeur) 
			{
				//echo($idparrain[0]." ");
				//echo($valeur);
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
				if (sizeOf(getParrains($valeur))>0){
					//for (i=1:sizeOf(getParrains($valeur))){
						$idparrain2=array_merge($idparrain2,getParrains($valeur));//On stocke les parrains
						$idparrain3 = getParrains($valeur);
						//var_dump($idparrain3);
						//echo(" ");
						/*foreach ($idparrain2 as $cle => $valeur){
							echo ($valeur);
							echo(" ");
						}*/
					//}
				}
				else {
					$idparrain3 = [];
				}
				if (empty($idparrain3)/*&&*sizeOf($idparrain)!=1 && sizeOf($idparrain2) == 0*/){
					//var_dump($idparrain2);
					$nodes = $nodes.'{"data":{"id":"'.$valeur.'","name":"'.$infos[0][prenom_adh]/*.'" "'.$infos[0][nom_adh]*/.'"}},';
					$idn = $idn+1;
					$idvieux = $idn-1;
					//$idparrain2 = [];
					//$idparrain2[] = $idh;
					//echo(sizeOf($idparrain));
					//echo("blabla");
					$nodes = $nodes.'{"data":{"id":"'.$idh.'","name":"'.$idh.'"}},';
					$edges = $edges.'{"data":{"id":"'.$idh.'","source":"'.$idh.'","target":"'.$valeur.'"}},';
					$idh = $idh - 1;
				}
				
				//TODO : pour les redoublants : insérer un élément vide entre eux et leur fillot
				else if ($infos[0][annee_fin] - $infos[0][annee_debut] == 4){
					
					echo("redoublant");
					$nodes = $nodes.'{"data":{"id":'.$infos[0][id_adh].',"name":'.$infos[0][prenom_adh]/*." ".$infos[0][nom_adh]*/.'}},';
					$idn = $idn+1;
					$idvieux = $idn-1;
					$nodes = $nodes.'{"data":{"id":'.$idh.',"name":'.$idh/*." ".$infos[0][nom_adh].*/.'}},';
					$edges = $edges.'{"data":{"id":'.$ide.',"source":'.$idh.',"target":'.$infos[0][id_adh].'}},';
					$ide = $ide+1;
					$idh = $idh-1;
				}
				else {
					//var_dump($infos[0][annee_fin]);
					//echo($infos[0][annee_fin] - $infos[0][annee_debut]);
					$nodes = $nodes.'{"data":{"id":"'.$valeur.'","name":"'.$infos[0][prenom_adh]/*.'" "'.$infos[0][nom_adh]*/.'"}},';
					$idn = $idn+1;
					$idvieux = $idn-1;
					//echo($idvieux);
					if ($idp != $id_fillot1){
						foreach ($idparrain3 as $cle => $nouveauparrain){
							//echo($nouveauparrain);
							$edges = $edges.'{"data":{"id":"'.$ide.'","source":"'.$nouveauparrain.'","target":"'.$valeur.'"}},';
							$ide = $ide+1;
						}
						
					}
					else {
						//foreach ($idparrain2 as $cle => $nouveauparrain){
							$edges = $edges.'{"data":{"id":"'.$ide.'","source":"'.$valeur.'","target":"9999"}},';
							$ide = $ide+1;
							foreach ($idparrain3 as $cle => $nouveauparrain){
								$edges = $edges.'{"data":{"id":"'.$ide.'","source":"'.$nouveauparrain.'","target":"'.$valeur.'"}},';
								$ide = $ide+1;
							}
						//}
						//echo($idvieux);
					}
				}
			}
			$idp = $idvieux;
			$idparrain = $idparrain2;//On commence une nouvelle boucle avec les nouveaux parrains obtenus
			$idparrain2 = [];
} while (count($idparrain) > 0);

//Récupération des fillots
$id_parrain1 = 1847;
//Idem que pour les parrains mais avec les fillots
$idfillot = getFillots($id_parrain1);
$id_fillot = [];
$idfillot2 = [];

$idp = $id_fillot1;

$annee_fin = 0;

do  {
	$idfillot2 = [];
	$idfillot3 = [];
	//Pour chaque parrain on chercher ses parrains
	foreach ($idfillot as $cle => $valeur) 
			{
				//echo($idparrain[0]." ");
				//echo($valeur);
				//Recuperation des annees debut
				$infos = [];
				$infos = $annuaire->getInfoById($valeur);
				if (strlen ($infos[0][nom])>3){ //Si jamais les infos sur le parrain sont liées à son cursus IT3, in enlève 2 ans à son année d'entrée pour savoir quand il et arrivé à l'école
					$infos[0][annee_debut] = $infos[0][annee_debut] - 2;
				}
				//var_dump($infos[0][nom]);
				$annee_final = $infos[0][annee_debut];
				if ($annee_final > $annee_fin){
					$annee_fin = $annee_final;
				}
				if (sizeOf(getFillots($valeur))>0){
					//for (i=1:sizeOf(getParrains($valeur))){
						$idfillot2=array_merge($idfillot2,getFillots($valeur));//On stocke les parrains
						$idfillot3 = getFillots($valeur);
						//var_dump($idparrain3);
						//echo(" ");
						/*foreach ($idparrain2 as $cle => $valeur){
							echo ($valeur);
							echo(" ");
						}*/
					//}
				}
				else {
					$idfillot3 = [];
				}
				/*if (empty($idfillot3)/*&&*sizeOf($idparrain)!=1 && sizeOf($idparrain2) == 0*///){
					//var_dump($idparrain2);
					/*$nodes = $nodes.'{"data":{"id":"'.$valeur.'","name":"'.$infos[0][prenom_adh]/*.'" "'.$infos[0][nom_adh]*///.'"}},';
					/*$idn = $idn+1;
					$idvieux = $idn-1;
					//$idparrain2 = [];
					//$idparrain2[] = $idh;
					//echo(sizeOf($idparrain));
					//echo("blabla");
					$nodes = $nodes.'{"data":{"id":"'.$idh.'","name":"'.$idh.'"}},';
					$edges = $edges.'{"data":{"id":"'.$idh.'","source":"'.$idh.'","target":"'.$valeur.'"}},';
					$idh = $idh - 1;
				}*/
				//TODO : pour les redoublants : insérer un élément vide entre eux et leur fillot
				/*else if ($infos[0][annee_fin] - $infos[0][annee_fin] == 4){
					$nodes = $nodes."{data:{id:".$idn.",name:".$infos[0][prenom_adh]." ".$infos[0][nom_adh]."}},";
					$idn = $idn+1;
					$idvieux = $idn-1;
					$nodes = $nodes."{data:{id:".$idh.",name:".$infos[0][prenom_adh]." ".$infos[0][nom_adh]."}},";
					$edges = $edges."{data:{id:".$ide.",source:".$idh.",target:$idvieux}},";
					$ide = $ide+1;
					$idh = $idh-1;
				}*/
				//else {
					$nodes = $nodes.'{"data":{"id":"'.$valeur.'","name":"'.$infos[0][prenom_adh]/*.'" "'.$infos[0][nom_adh]*/.'"}},';
					$idn = $idn+1;
					$idvieux = $idn-1;
					//echo($idvieux);
					if ($idp != $id_fillot1){
						foreach ($idfillot3 as $cle => $nouveaufillot){
							//echo($nouveaufillot);
							$edges = $edges.'{"data":{"id":"'.$ide.'","source":"'.$valeur.'","target":"'.$nouveaufillot.'"}},';
							$ide = $ide+1;
						}
						
					}
					else {
						//foreach ($idparrain2 as $cle => $nouveauparrain){
							$edges = $edges.'{"data":{"id":"'.$ide.'","source":"9999","target":"'.$valeur.'"}},';
							$ide = $ide+1;
							foreach ($idfillot3 as $cle => $nouveaufillot){
								$edges = $edges.'{"data":{"id":"'.$ide.'","source":"9999","target":"'.$nouveaufillot.'"}},';
								$ide = $ide+1;
							}
						//}
						//echo($idvieux);
					}
				//}
			}
			$idp = $idvieux;
			$idfillot = $idfillot2;//On commence une nouvelle boucle avec les nouveaux parrains obtenus
			$idfillot2 = [];
} while (count($idfillot) > 0);

$ide = 1;
//echo($annee_debut);
$nodes = $nodes.'{"data":{"id":"'.$annee_debut .'","name":"'.$annee_debut .'"}},';
//var_dump($annee_debut);
//echo($annee_debut);
$ancienneannee = $annee_debut;
//echo($annee_debut+1);
for ($i = $annee_debut+1; $i <= $annee_fin; $i++){
	$nodes = $nodes.'{"data":{"id":"'.$i .'","name":"'.$i .'"}},';
	$edges = $edges.'{"data":{"id":"'.$ide.'","source":"'. $ancienneannee .'","target":"'.$i .'"}},';
	$ide++;
	$ancienneannee = $i;
}

$layout = '"layout": {"name": "breadthfirst", "directed": true, "roots":"#8772, #'.$annee_debut.'", "padding": 10} }';
//,"style": "node { content: data(name);}"
$infos = $annuaire->getInfoById($id_fillot1);
$nodes = $nodes.'{"data":{"id":"9999","name":"'.$infos[0][prenom_adh]/*.'" "'.$infos[0][nom_adh]*/.'"}}';
$edges = substr($edges,0,-1);
$json = $json.$nodes."],".$edges."]},".$layout;
//echo($json);
if (!$fp = fopen("donnees.json","w+")) { 
	echo('Ca marche pas !!'); 
} else {
	fputs($fp,$json); // on écrit le nom et email dans le fichier
	fclose($fp);
}

?>
