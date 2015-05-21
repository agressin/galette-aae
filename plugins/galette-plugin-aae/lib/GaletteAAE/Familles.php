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
	
	/*CreateJSON
	IN : id de la personne dont on veut connaître la famille
	OUT : JSON de sa familles
	COM : Trouver les personnes de la famille de la personne en entrée*/
	function CreateJSON ($id)
	{
		//On initialise l'id des arrêtes et des éléments cachés
		$ide = 100000;
		$idh = -1;
	
		//Initialisation du JSON
		$json = '{"elements": { ';
		$nodes = '"nodes":[';
		$edges = '"edges":[';
		//Pour savoir s'il y a des arrêtes, s'il n'y en a pas, on fait un JSON diféérent
		$yatildesarretes = 0;

		//var_dump($_GET);
		
		//var_dump($id);

		//On initialise annee_debut et annee_fin (permettent de faire l'arbre des annees) à des valeurs très éloignées de ce que l'on peut avoir pour reprérer tout de suite s'il y a une erreur
		$array = [];
		$annee_debut = 9999;
		$annee_fin = 0;
		
		//On crée un $annuaire et un $member pour pouvoir utiliser les fonctions qui leurs sont reliées
		$annuaire = new Annuaire();
		$member = new Adherent();
		
		//Récupération des infos de la personne dont on veut connaître la famille
		$array_points = $annuaire->getInfoById($id);

		//Récupération des parrains
		$id_fillot1 = $id;

		$idp = $id_fillot1;
		
		//Initialisation des racines
		$roots = [];
		$idracines = [];
		
		//On récupère les premiers parrains de la personne qu'on recherche
		$idparrain = $this->getParrains($id_fillot1);

		//Si elle n'a pas de parrains, elle devient racine, et son année d'entrée à l'école est la racine de l'arbre des années
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
			//S'il y a des parrains, on initialise les racines et un tableau qui contiendra les parrains
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
								//Si le parrain est rentré à l'école avant tout le monde, son année d'entrée est conservée
								if ($infos[0][nom] == "B" || $infos[0][nom] == "IT"){
									$annee_debut = $infos[0][annee_debut];
								}
								//var_dump($infos[0][nom]);
								$annee_deb = $infos[0][annee_debut];
								if ($annee_deb < $annee_debut){
									$annee_debut = $annee_deb;
								}
							}
							//Si on a au moins 1 parrain, on complète le tableau $parrain, qui permet de vérifier l'unicité du parrain
							//Le tableau $idparrain2 contient tous les parrains
							//Et le tableau $idparrain3 contient seulement les parrains pour lesquels on va chercher leurs parrains
							if (sizeOf($this->getParrains($valeur))>0){
									$parrains = array_unique($this->getParrains($valeur));
									$idparrain2=array_merge($idparrain2,$parrains);//On stocke les parrains
									$idparrain3 = $parrains;
									//echo(" ");
							}
							else {
								//Si on n'a aucun parrain, on le note, afin de ne pas continuer d'en chercher inutilement
								$pasdeparrains = 1;
								//echo($infos[0][nom_adh]);
							}
							if ($pasdeparrains == 1){
								//Si on n'a pas de parrains, on rajoute la dernière personne qu'on a trouvé dans le JSON
								//var_dump($idparrain2);
								//echo("blabla");
								//echo($valeur);
								//echo($idh);
								$personne = $member->getSName($valeur);
								if ($valeur > 0){
									//echo($personne);
									$nodes = $nodes.'{"data":{"id":"'.$valeur.'","name":"'.$personne.'"}},';
								}
								$idvieux = $valeur;
								//echo(sizeOf($idparrain));
								//var_dump($valeur);
								//var_dump($idparrain2);
								if (!empty ($idparrain2) || $idpositif > 0){
									//Si on a au moins 1 parrain, on l'ajoute dans le JSON et on le relie à son fillot
									$nodes = $nodes.'{"data":{"id":"'.$idh.'","name":"'.$idh.'"}},';
									$edges = $edges.'{"data":{"id":"'.$ide.'","source":"'.$idh.'","target":"'.$valeur.'"}},';
									//var_dump($valeur);
									//var_dump($idparrain2);
									//echo($idh);
									//On valide le fait que l'arbre contient plus d'une personne
									$yatildesarretes = 1;
									array_push($idparrain2,$idh);
									$idh = $idh - 1;
								}
								if ($idp == $id_fillot1){
									//S'il s'agit d'un parrain de la personne dont on veut connapitre la famille, on crée juste l'arrête, cette personne sera ajoutée à la fin dans le JSON
									$edges = $edges.'{"data":{"id":"'.$ide.'","source":"'.$valeur.'","target":"'.$id.'"}},';
									$ide = $ide+1;
									$yatildesarretes = 1;
								}
								//echo($valeur);
								$ide++;
								//On ajoute le parrain dans le tableau des racines
								array_push($idracines,$valeur);
								//var_dump($idparrain2);
							}
							
							//TODO : pour les redoublants : insérer un élément vide entre eux et leur fillot
							//Problème : les redoublants ne sont pas inscrits dans la BDD, il faut soit modifier la base à la main, soit trouver un autre moyen de les repérer
							else if ($infos[0][annee_fin] - $infos[0][annee_debut] == 4){
								
								//echo("redoublant");
								$personne = $member->getSName($valeur);
								$nodes = $nodes.'{"data":{"id":'.$infos[0][id_adh].',"name":'.$personne.'}},';
								$idvieux = $valeur;
								$edges = $edges.'{"data":{"id":'.$ide.',"source":'.$idh.',"target":'.$personne.'}},';
								$ide = $ide+1;
								$idh = $idh-1;
								$yatildesarretes = 1;
							}
							else {
								//Si le parrain n'es pas un redoublant, on l'ajoute dans le JSON et on le relie à son fillot
								//var_dump($infos[0][nom_adh]);
								$personne = $member->getSName($valeur);
								$nodes = $nodes.'{"data":{"id":"'.$valeur.'","name":'.json_encode($personne).'}},';
								$idvieux = $valeur;
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
									$edges = $edges.'{"data":{"id":"'.$ide.'","source":"'.$valeur.'","target":"'.$id.'"}},';
									$ide = $ide+1;
									foreach ($idparrain3 as $cle => $nouveauparrain){
										$edges = $edges.'{"data":{"id":"'.$ide.'","source":"'.$nouveauparrain.'","target":"'.$valeur.'"}},';
										$ide = $ide+1;
										$yatildesarretes = 1;
										array_push($idracines,$nouveauparrain);
									}
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
			//echo($infos[0][id_cycle]);
			if ($infos[0][nom] == "B" || $infos[0][nom] == "IT"){
				$annee_fin = $infos[0][annee_debut];
			}
			else {
				$annee_fin = $infos[1][annee_debut];
			}
			//echo($annee_fin);
		}
		else{
			do  {
				$idfillot2 = [];
				$idfillot3 = [];
				//Pour chaque fillot on va chercher ses fillots
				foreach ($idfillot as $cle => $valeur) 
						{
							//echo($valeur);
							//Recuperation des annees debut
							$infos = [];
							if ($valeur > 0){
								$infos = $annuaire->getInfoById($valeur);
								//On stocke l'année du plus récent fillot
								if ($infos[0][nom] == "B" || $infos[0][nom] == "IT"){ 
									$infos[0][annee_debut] = $infos[0][annee_debut] - 2;
								}
								//var_dump($infos[0][nom]);
								$annee_final = $infos[0][annee_debut];
								if ($annee_final > $annee_fin){
									$annee_fin = $annee_final;
								}
							}
							if (sizeOf($this->getFillots($valeur))>0){
								//Si on a des fillots, on les stocke pour créer les liens avec leurs parrains et pour rechercher leurs propres fillots
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
							}
							else {
								$idfillot3 = [];
							}
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
							//var_dump($idfillot3);
								$personne = $member->getSName($valeur);
								if ($personne != ""){
									//S'il s'agit bien d'une personne, et pas d'un élément caché, on l'ajoute dans le JSON
									$nodes = $nodes.'{"data":{"id":"'.$valeur.'","name":"'.$personne.'"}},';
								}
								$idvieux = $vieux
								//On crée les liens entre parrains et fillots
								if ($idp != $id_fillot1){
									foreach ($idfillot3 as $cle => $nouveaufillot){
										//echo($nouveaufillot);
										$edges = $edges.'{"data":{"id":"'.$ide.'","source":"'.$valeur.'","target":"'.$nouveaufillot.'"}},';
										$ide = $ide+1;
									}
									$yatildesarretes = 1;
									//var_dump($idfillot3);
								}
								else {
									$edges = $edges.'{"data":{"id":"'.$ide.'","source":"'.$id.'","target":"'.$valeur.'"}},';
									$ide = $ide+1;
									foreach ($idfillot3 as $cle => $nouveaufillot){
										$edges = $edges.'{"data":{"id":"'.$ide.'","source":"'.$valeur.'","target":"'.$nouveaufillot.'"}},';
										$ide = $ide+1;
									}
									$yatildesarretes = 1;
								}
						}
						$idp = $idvieux;
						$idfillot = $idfillot2;//On commence une nouvelle boucle avec les nouveaux fillots obtenus
						$idfillot2 = [];
			} while (count($idfillot) > 0);
		}

		//var_dump($annee_debut);
		$premiereannee = $annee_debut - 2000;
		$nodes = $nodes.'{"data":{"id":"'.$premiereannee .'","name":"'.$annee_debut .'"}},';
		$ancienneannee = $annee_debut - 2000;
		//On crée l'arbre avec les années
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
			$pasderacines = 1;
		}
		//On ajoute les racines
		foreach ($idracines as $cle => $racine){
			$roots = $roots.'#'.$racine.',';
		}
		//echo($roots);
		if ($pasderacines != 1){
			//On crée le layout avec les racines
			$layout = '"layout": {"name": "breadthfirst", "directed": true, '.$roots.'#'.$premiereannee.'", "padding": 10} }';
		}
		else {
			//On crée le layout sans le racines
			$layout = '"layout": {"name": "breadthfirst", "directed": true, "padding": 10} }';
		}
		$infos = $annuaire->getInfoById($id_fillot1);
		$personne = $member->getSName($id);
		//On rajoute le nom de la personne dont on souhaitait connaître la famille
		$nodes = $nodes.'{"data":{"id":"'.$id.'","name":"'.$personne.'"}}';
		if ($yatildesarretes == 1){
			$edges = substr($edges,0,-1);
		}
		//JSON finit
		$json = $json.$nodes."],".$edges."]},".$layout;
		//echo($json);
		//On enregistre le JSON
		if (!$fp = fopen("donnees.json","w+")) { 
			echo('Ca marche pas !!'); 
		}else if ($idracines == [0]){
			//Si on n'a pas de racines, on enregistre un JSON vide mais qui ne fait pas planter
			fputs($fp,'{"elements": {"nodes":{},"edges":{}}}'); 
			fclose($fp);
		} else {
			//Sinon on enregistre le tout
			fputs($fp,$json); 
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
