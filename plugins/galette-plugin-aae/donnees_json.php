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
$ide = 100000;
$idh = -1;

$json = '{"elements": { ';
$nodes = '"nodes":[';
$edges = '"edges":[';

//echo($_POST["id_adh"]);
$id = $_GET["id_adh"];
//var_dump($_GET);

$array = [];
$annee_debut = 9999;

$annee_fin = 0;

$array_points = $annuaire->getInfoById($id);

//Récupération des parrains
$id_fillot1 = $id;

$idp = $id_fillot1;

$roots = [];
$idracines = [];

$idparrain = getParrains($id_fillot1);
//var_dump($idparrain);
if (empty($idparrain)){
	array_push($idracines,$id);
}
else{
	$id_parrain = [];
	$idparrain2 = [];
	$idracines = [];
	//Tant qu'on a des parrains on va chercher leurs parrains
	do  {
		$idparrain2 = [];
		$idparrain3 = [];
		$idracines = [];
		//Pour chaque parrain on chercher ses parrains
		foreach ($idparrain as $cle => $valeur) 
				{
					//echo($idparrain[0]." ");
					//echo($valeur);
					//Recuperation des annees debut
					$infos = [];
					$infos = $annuaire->getInfoById($valeur);
					if ($valeur > 0){
						if (strlen ($infos[0][nom])>3){ //Si jamais les infos sur le parrain sont liées à son cursus IT3, in enlève 2 ans à son année d'entrée pour savoir quand il et arrivé à l'école
							$infos[0][annee_debut] = $infos[0][annee_debut] - 2;
						}
						//var_dump($infos[0][nom]);
						$annee_deb = $infos[0][annee_debut];
						if ($annee_deb < $annee_debut){
							$annee_debut = $annee_deb;
						}
					}
					if (sizeOf(getParrains($valeur))>0){
						//for (i=1:sizeOf(getParrains($valeur))){
							$idparrain2=array_merge($idparrain2,getParrains($valeur));//On stocke les parrains
							$idparrain3 = getParrains($valeur);
							//echo(" ");
							/*foreach ($idparrain2 as $cle => $valeur){
								echo ($valeur);
								echo(" ");
							}*/
						//}
					}
					else {
						$pasdeparrains = 1;
					}
					if ($pasdeparrains == 1 /*&&*sizeOf($idparrain)!=1 && sizeOf($idparrain2) == 0*/){
						//var_dump($idparrain2);
						//echo("blabla");
						$personne = $infos[0][prenom_adh].' '.$infos[0][nom_adh];
						$nodes = $nodes.'{"data":{"id":"'.$valeur.'","name":"'.$personne.'"}},';
						$idn = $idn+1;
						$idvieux = $idn-1;
						//$idparrain2 = [];
						//$idparrain2[] = $idh;
						//echo(sizeOf($idparrain));
						//echo("blabla");
						/*$nodes = $nodes.'{"data":{"id":"'.$idh.'","name":"'.$idh.'"}},';*/
						//var_dump($valeur);
						if (!empty ($idparrain2)){
							$nodes = $nodes.'{"data":{"id":"'.$idh.'","name":"'.$idh.'"}},';
							$edges = $edges.'{"data":{"id":"'.$ide.'","source":"'.$idh.'","target":"'.$valeur.'"}},';
							$idh = $idh - 1;
						}
						if ($idp == $id_fillot1){
							$edges = $edges.'{"data":{"id":"'.$ide.'","source":"'.$valeur.'","target":"'.$id.'"}},';
							$ide = $ide+1;
						}
						array_push($idparrain2,$idh);
						$ide++;
						array_push($idracines,$valeur);
						//var_dump($idparrain2);
					}
					
					//TODO : pour les redoublants : insérer un élément vide entre eux et leur fillot
					else if ($infos[0][annee_fin] - $infos[0][annee_debut] == 4){
						
						echo("redoublant");
						$personne = $infos[0][prenom_adh].' '.$infos[0][nom_adh];
						$nodes = $nodes.'{"data":{"id":'.$infos[0][id_adh].',"name":'.$personne.'}},';
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
						$personne = $infos[0][prenom_adh].' '.$infos[0][nom_adh];
						$nodes = $nodes.'{"data":{"id":"'.$valeur.'","name":"'.$personne.'"}},';
						$idn = $idn+1;
						$idvieux = $idn-1;
						//echo($idvieux);
						if ($idp != $id_fillot1){
							foreach ($idparrain3 as $cle => $nouveauparrain){
								//echo($nouveauparrain);
								$edges = $edges.'{"data":{"id":"'.$ide.'","source":"'.$nouveauparrain.'","target":"'.$valeur.'"}},';
								$ide = $ide+1;
								array_push($idracines,$nouveauparrain);
							}
							
						}
						else {
							//echo($valeur);
							//foreach ($idparrain2 as $cle => $nouveauparrain){
								$edges = $edges.'{"data":{"id":"'.$ide.'","source":"'.$valeur.'","target":"'.$id.'"}},';
								$ide = $ide+1;
								foreach ($idparrain3 as $cle => $nouveauparrain){
									$edges = $edges.'{"data":{"id":"'.$ide.'","source":"'.$nouveauparrain.'","target":"'.$valeur.'"}},';
									$ide = $ide+1;
									array_push($idracines,$nouveauparrain);
								}
							//}
							//echo($idvieux);
						}
					}
				}
				$idp = $idvieux;
				$idparrain = $idparrain2;//On commence une nouvelle boucle avec les nouveaux parrains obtenus
				$idpositif = 0;
				foreach ($idparrain2 as $cle => $positif){
					if ($positif > 0){
						$idpositif++;
					}
				}
				//var_dump($idparrain2);
				$idparrain2 = [];
	} while ($idpositif > 0);
}
//var_dump($idracines);

//Récupération des fillots
$id_parrain1 = $id;
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
				if ($valeur > 0){
					$infos = $annuaire->getInfoById($valeur);
					if (strlen ($infos[0][nom])>3){ //Si jamais les infos sur le parrain sont liées à son cursus IT3, in enlève 2 ans à son année d'entrée pour savoir quand il et arrivé à l'école
						$infos[0][annee_debut] = $infos[0][annee_debut] - 2;
					}
					//var_dump($infos[0][nom]);
					$annee_final = $infos[0][annee_debut];
					if ($annee_final > $annee_fin){
						$annee_fin = $annee_final;
					}
				}
				if (sizeOf(getFillots($valeur))>0){
					//for (i=1:sizeOf(getParrains($valeur))){
					$idfillot3 = [];
						foreach (getFillots($valeur) as $cle => $nouveaufillot){
							if (!in_array($nouveaufillot, $idfillot2)) {
								//$idfillot2=array_merge($idfillot2,getFillots($valeur));//On stocke les parrains
								array_push($idfillot2,$nouveaufillot);
							}
							if (!in_array($nouveaufillot, $idfillot3)) {
								//$idfillot3 = getFillots($valeur);
								array_push($idfillot3,$nouveaufillot);
							}
						}
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
				//var_dump($idfillot3);
					$personne = $infos[0][prenom_adh].' '.$infos[0][nom_adh];
					$nodes = $nodes.'{"data":{"id":"'.$valeur.'","name":"'.$personne.'"}},';
					$idn = $idn+1;
					$idvieux = $idn-1;
					//echo($idvieux);
					if ($idp != $id_fillot1){
						foreach ($idfillot3 as $cle => $nouveaufillot){
							/*echo($nouveaufillot);
							echo(" ");*/
							$edges = $edges.'{"data":{"id":"'.$ide.'","source":"'.$valeur.'","target":"'.$nouveaufillot.'"}},';
							$ide = $ide+1;
						}
						//var_dump($idfillot3);
					}
					else {
						//foreach ($idparrain2 as $cle => $nouveauparrain){
							$edges = $edges.'{"data":{"id":"'.$ide.'","source":"'.$id.'","target":"'.$valeur.'"}},';
							$ide = $ide+1;
							foreach ($idfillot3 as $cle => $nouveaufillot){
								$edges = $edges.'{"data":{"id":"'.$ide.'","source":"'.$valeur.'","target":"'.$nouveaufillot.'"}},';
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

//echo($annee_debut);
/*var_dump($annee_debut);
$premiereannee = $annee_debut - 2000;
$nodes = $nodes.'{"data":{"id":"'.$premiereannee .'","name":"'.$annee_debut .'"}},';
//var_dump($annee_debut);
//echo($annee_debut);
$ancienneannee = $annee_debut - 2000;
//echo($annee_debut+1);
for ($i = $annee_debut+1; $i <= $annee_fin; $i++){
	$nouvelleannee = $i - 2000;
	$nodes = $nodes.'{"data":{"id":"'.$nouvelleannee .'","name":"'.$i .'"}},';
	$edges = $edges.'{"data":{"id":"'.$ide.'","source":"'. $ancienneannee .'","target":"'.$nouvelleannee .'"}},';
	$ide++;
	$ancienneannee = $i - 2000;
}*/

$roots = '"roots":'.'"';
//var_dump($idracines);
foreach ($idracines as $cle => $racine){
	$roots = $roots.'#'.$racine.',';
}
//echo($roots);
$roots = substr($roots,0,-1);
$layout = '"layout": {"name": "breadthfirst", "directed": true, '.$roots/*.'#'.$premiereannee*/.'", "padding": 10} }';
//,"style": "node { content: data(name);}"
$infos = $annuaire->getInfoById($id_fillot1);
$personne = $infos[0][prenom_adh].' '.$infos[0][nom_adh];
$nodes = $nodes.'{"data":{"id":"'.$id.'","name":"'.$personne.'"}}';
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
