<?php

namespace Galette\AAE;

define('GALETTE_BASE_PATH', '../../');
require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';

//Constants and classes from plugin
require_once '_config.inc.php';

use Analog\Analog as Analog;
use Galette\Entity\Adherent as Adherent;
use Galette\Repository\Members as Members;

require_once 'lib/GaletteAAE/Cycles.php';
use Galette\AAE\Cycles as Cycles;

require_once 'lib/GaletteAAE/Annuaire.php';
use Galette\AAE\Annuaire as Annuaire;

require_once 'lib/GaletteAAE/Formations.php';
use Galette\AAE\Formations as Formations;

$annuaire = new Annuaire();
$member = new Adherent();

/**
 * Familles
 *
 * @category  Plugins
 */

class Familles
{
	/*GetParrains
	IN : id_fillot
	OUT : id_parrain
	COM : récupère les id des parrains pour un id fillot précis*/

	function getParrains($id_fillot)
	{
		global $zdb;

		try {
			//Requête SQL : on va chercher les parrains pour un id donné
			$select = $zdb->select(AAE_PREFIX . 'familles');
			$select->where->equalTo('id_fillot',$id_fillot);
			$res = $zdb->execute($select);
			//On convertit le résultat en tableau
			$res = $res->toArray();
			$idparrain=array();
			
			//Remplissage du tableau
			foreach ($res as $cle => $valeur) 
			{
				$idparrain[]=$valeur['id_parrain'];
			}
			//Si on a au moins 1 parrain, on renvoie son id
			if ( count($idparrain) > 0 ) {
				return $idparrain;
			} else {
				return array();
			}
			
		} catch (\Exception $e) {
			Analog::log(
				'Unable to retrieve parrain for "' .
				$id_fillot . '" | "" | ' . $e->getMessage(),
				Analog::WARNING
			);
			//Comme ça on montre bien que la requête a planté
			echo('raté');
			return false;
		}

	}	



	/*GetFillots
	IN : id_parrain
	OUT : id_fillot
	COM : récupère les id des fillots pour un id parrain précis*/

	function getFillots($id_parrain)
	{
		global $zdb;

		try {
			//Requête SQL : on va chercher les fillots pour un id donné
			$select = $zdb->select(AAE_PREFIX . 'familles');
			$select->where->equalTo('id_parrain',$id_parrain);
			$res = $zdb->execute($select);
			//On convertit le résultat en tableau
			$res = $res->toArray();
			$idfillot=array();
			
			//Remplissage du tableau
			foreach ($res as $cle => $valeur) 
			{
				$idfillot[]=$valeur['id_fillot'];
			}
			//Si on a au moins 1 parrain, on renvoie son id
			if ( count($idfillot) > 0 ) {
				return $idfillot;
			} else {
				return array();
			}
			
		} catch (\Exception $e) {
			Analog::log(
				'Unable to retrieve parrain for "' .
				$id_fillot . '" | "" | ' . $e->getMessage(),
				Analog::WARNING
			);
			//Comme ça on montre bien que la requête a planté
			echo('raté');
			return false;
		}

	}
	
	
	function CreateJSON ($id)
	{
		$idn = 0;
		$ide = 100000;
		$idh = -1;

		$json = '{"elements": { ';
		$nodes = '"nodes":[';
		$edges = '"edges":[';
		$yatildesarretes = 0;

		//echo($_POST["id_adh"]);

		//var_dump($_GET);
		
		//var_dump($id);

		$array = [];
		$annee_debut = 9999;

		$annee_fin = 0;

		$annuaire = new Annuaire();
		$member = new Adherent();
		
		$array_points = $annuaire->getInfoById($id);

		//Récupération des parrains
		$id_fillot1 = $id;

		$idp = $id_fillot1;

		$roots = [];
		$idracines = [];

		$idparrain = $this->getParrains($id_fillot1);

		if (empty($idparrain)){
			array_push($idracines,$id);
			$infos = $annuaire->getInfoById($id);
			if ($infos[0][nom] == "B" || $infos[0][nom] == "IT"){
				$annee_debut = $infos[0][annee_debut];
			}
			else {
				$annee_debut = $infos[1][annee_debut];
			}
			//var_dump($infos[0]);
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
							$parrains = [];
							$infos = [];
							$infos = $annuaire->getInfoById($valeur);
							
							//var_dump($infos[0]);
							$pasdeparrains = 0;
							if ($valeur > 0){
								if (strlen ($infos[0][nom])>3 /*&& $infos[0][id_cycle]!=3*/){
									$annee_debut = $infos[0][annee_debut];
								}
								/*else {
									$annee_debut = $infos[1][annee_debut];
								}*/
								//var_dump($infos[0][nom]);
								$annee_deb = $infos[0][annee_debut];
								if ($annee_deb < $annee_debut){
									$annee_debut = $annee_deb;
								}
							}
							if (sizeOf($this->getParrains($valeur))>0){
								//for (i=1:sizeOf(getParrains($valeur))){
									$parrains = array_unique($this->getParrains($valeur));
									$idparrain2=array_merge($idparrain2,$parrains);//On stocke les parrains
									$idparrain3 = $parrains;
									//echo(" ");
									/*foreach ($idparrain2 as $cle => $valeur){
										echo ($valeur);
										echo(" ");
									}*/
								//}
							}
							else {
								$pasdeparrains = 1;
								//echo($infos[0][nom_adh]);
							}
							if ($pasdeparrains == 1 /*&&*sizeOf($idparrain)!=1 && sizeOf($idparrain2) == 0*/){
								//var_dump($idparrain2);
								//echo("blabla");
								//echo($valeur);
								//echo($idh);
								$personne = $infos[0][prenom_adh].' '.$infos[0][nom_adh];
								if ($valeur > 0){
									//echo($personne);
									$nodes = $nodes.'{"data":{"id":"'.$valeur.'","name":"'.$personne.'"}},';
									//$edges = $edges.'{"data":{"id":"'.$ide.'","source":"'.$idh.'","target":"'.$valeur.'"}},';
									//$ide++;
								}
								$idn = $idn+1;
								$idvieux = $idn-1;
								//$idparrain2 = [];
								//$idparrain2[] = $idh;
								//echo(sizeOf($idparrain));
								//echo("blabla");
								/*$nodes = $nodes.'{"data":{"id":"'.$idh.'","name":"'.$idh.'"}},';*/
								//var_dump($valeur);
								//var_dump($idparrain2);
								if (!empty ($idparrain2) || $idpositif > 0){
									$nodes = $nodes.'{"data":{"id":"'.$idh.'","name":"'.$idh.'"}},';
									$edges = $edges.'{"data":{"id":"'.$ide.'","source":"'.$idh.'","target":"'.$valeur.'"}},';
									//var_dump($valeur);
									//var_dump($idparrain2);
									//echo($idh);
									$yatildesarretes = 1;
									array_push($idparrain2,$idh);
									$idh = $idh - 1;
								}
								if ($idp == $id_fillot1){
									$edges = $edges.'{"data":{"id":"'.$ide.'","source":"'.$valeur.'","target":"'.$id.'"}},';
									$ide = $ide+1;
									$yatildesarretes = 1;
								}
								//$idh = $idh - 1;
								//echo($valeur);
								$ide++;
								array_push($idracines,$valeur);
								
								//echo($idh);
								//var_dump($idparrain2);
							}
							
							//TODO : pour les redoublants : insérer un élément vide entre eux et leur fillot
							else if ($infos[0][annee_fin] - $infos[0][annee_debut] == 4){
								
								echo("redoublant");
								$personne = $member->getSName($valeur);
								$nodes = $nodes.'{"data":{"id":'.$infos[0][id_adh].',"name":'.$personne.'}},';
								$idn = $idn+1;
								$idvieux = $idn-1;
								//$nodes = $nodes.'{"data":{"id":'.$idh.',"name":'.$idh/*." ".$infos[0][nom_adh].*/.'}},';
								$edges = $edges.'{"data":{"id":'.$ide.',"source":'.$idh.',"target":'.$infos[0][id_adh].'}},';
								$ide = $ide+1;
								$idh = $idh-1;
								$yatildesarretes = 1;
							}
							else {
								//var_dump($infos[0][nom_adh]);
								//echo($infos[0][annee_fin] - $infos[0][annee_debut]);
								$nom = json_encode($infos[0][nom_adh]);
								//$personne = $infos[0][prenom_adh].' '.$infos[0][nom_adh];
								$personne = $member->getSName($valeur);
								$nodes = $nodes.'{"data":{"id":"'.$valeur.'","name":'.json_encode($personne).'}},';
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
									$yatildesarretes = 1;
								}
								else {
									//echo($valeur);
									//foreach ($idparrain2 as $cle => $nouveauparrain){
										$edges = $edges.'{"data":{"id":"'.$ide.'","source":"'.$valeur.'","target":"'.$id.'"}},';
										$ide = $ide+1;
										foreach ($idparrain3 as $cle => $nouveauparrain){
											$edges = $edges.'{"data":{"id":"'.$ide.'","source":"'.$nouveauparrain.'","target":"'.$valeur.'"}},';
											$ide = $ide+1;
											$yatildesarretes = 1;
											array_push($idracines,$nouveauparrain);
										}
									//}
									//echo($idvieux);
								}
							}
						}
						$idp = $idvieux;
						$idparrain = array_unique($idparrain2);//On commence une nouvelle boucle avec les nouveaux parrains obtenus
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
		$idfillot = $this->getFillots($id_parrain1);
		$id_fillot = [];
		$idfillot2 = [];

		$idp = $id_fillot1;

		$annee_fin = 0;

		if (empty($idfillot)){
			$infos = $annuaire->getInfoById($id);
			echo($infos[0][id_cycle]);
			if ($infos[0][nom] == "B" || $infos[0][nom] == "IT"){
				$annee_fin = $infos[0][annee_debut];
			}
			else {
				$annee_fin = $infos[1][annee_debut];
			}
			echo($annee_fin);
		}
		else{
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
							if (sizeOf($this->getFillots($valeur))>0){
								//for (i=1:sizeOf(getParrains($valeur))){
								$idfillot3 = [];
									foreach ($this->getFillots($valeur) as $cle => $nouveaufillot){
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
								$personne = $member->getSName($valeur);
								if ($personne != ""){
									$nodes = $nodes.'{"data":{"id":"'.$valeur.'","name":"'.$personne.'"}},';
								}
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
									$yatildesarretes = 1;
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
										$yatildesarretes = 1;
									//}
									//echo($idvieux);
								}
								
							//}
						}
						$idp = $idvieux;
						$idfillot = $idfillot2;//On commence une nouvelle boucle avec les nouveaux parrains obtenus
						$idfillot2 = [];
			} while (count($idfillot) > 0);
		}

		//echo($annee_debut);
		//var_dump($annee_debut);
		$premiereannee = $annee_debut - 2000;
		$nodes = $nodes.'{"data":{"id":"'.$premiereannee .'","name":"'.$annee_debut .'"}},';
		//var_dump($annee_debut);
		//echo($annee_debut);
		$ancienneannee = $annee_debut - 2000;
		//echo($annee_debut+1);
		if ($annee_fin != 0){
			for ($i = $annee_debut+1; $i <= $annee_fin; $i++){
				$nouvelleannee = $i - 2000;
				$nodes = $nodes.'{"data":{"id":"'.$nouvelleannee .'","name":"'.$i .'"}},';
				$edges = $edges.'{"data":{"id":"'.$ide.'","source":"'. $ancienneannee .'","target":"'.$nouvelleannee .'"}},';
				$ide++;
				$ancienneannee = $i - 2000;
			}
		}
		$roots = '"roots":'.'"';
		//var_dump($idracines);
		if ($idracines == [0]){
			//echo("coucou");
			$pasderacines = 1;
		}
		foreach ($idracines as $cle => $racine){
			$roots = $roots.'#'.$racine.',';
		}
		//echo($roots);
		//$roots = substr($roots,0,-1);
		if ($pasderacines != 1){
			$layout = '"layout": {"name": "breadthfirst", "directed": true, '.$roots.'#'.$premiereannee.'", "padding": 10} }';
		}
		else {
			$layout = '"layout": {"name": "breadthfirst", "directed": true, "padding": 10} }';
		}
		//,"style": "node { content: data(name);}"
		$infos = $annuaire->getInfoById($id_fillot1);
		$personne = $member->getSName($id);
		$nodes = $nodes.'{"data":{"id":"'.$id.'","name":"'.$personne.'"}}';
		if ($yatildesarretes == 1){
			$edges = substr($edges,0,-1);
		}
		$json = $json.$nodes."],".$edges."]},".$layout;
		//echo($json);
		if (!$fp = fopen("donnees.json","w+")) { 
			echo('Ca marche pas !!'); 
		}else if ($idracines == [0]){
			fputs($fp,'{"elements": {"nodes":{},"edges":{}}}'); // on écrit le nom et email dans le fichier
			fclose($fp);
		} else {
			fputs($fp,$json); // on écrit le nom et email dans le fichier
			fclose($fp);
		}
	
	}
	
	
	/*ajoutLien
	IN : id_parrain,id_fillot
	COM : ajout du lien entre les personnes d'identifiant id_parrain et id_fillot*/
	function ajoutLienParrainFillot ($id_parrain, $id_fillot)
	{	
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
	
}

?>
