<?php

namespace Galette\AAE;

use Analog\Analog as Analog;
use Galette\Entity\Adherent as Adherent;
use Galette\Repository\Members as Members;

require_once 'lib/GaletteAAE/Cycles.php';
use Galette\AAE\Cycles as Cycles;

require_once 'lib/GaletteAAE/Formations.php';
use Galette\AAE\Formations as Formations;

/**
 * Members Annuaire
 *
 * @category  Plugins
 */

class Annuaire
{
	/*Get name of all student
	IN : 
	OUT : array with all names
	COM : For levhenstein research by name*/
	
	public function getNameOfAllStudents()
    {
        global $zdb;

        try {
            $select = $zdb->sql->select();
            $table_adh = PREFIX_DB . Adherent::TABLE;
			$select->from
				(
					array('a' => $table_adh)
				);
			$select->columns(array('nom_adh'));
			
            
            $res = $zdb->execute($select);
            $res = $res->toArray();
		
			//Get colums with students names
			foreach ($res as $cle => $valeur) 
			{
				$nom[$cle]  = $valeur['nom_adh'];
			};

			//Sort table 
			//Add $student to sort by same key
			array_multisort($nom, SORT_ASC, $res);

			//Table with all names 
			//Initialisation du tableau
			$listOfStudents=array();
			
			//Remplissage du tableau
			foreach ($res as $cle => $valeur) 
			{
				$listOfStudents[]=$valeur['nom_adh'];
			}
			
            if ( count($listOfStudents) > 0 ) {
                return $listOfStudents;
            } else {
                return array();
            }
        } catch (\Exception $e) {
            Analog::log(
                'Unable to retrieve members promotion for "' .
                $id_cycle  . '" | "" | ' . $e->getMessage(),
                Analog::WARNING
            );
            return false;
        }
    }

    /*Get first name of all student
	IN : 
	OUT : array with all names
	COM : For levhenstein research by first name
		  Same process as getNameOfAllStudents*/
	
	public function getSurnameOfAllStudents()
    {
        global $zdb;

        try {
            $select = $zdb->sql->select();
            $table_adh = PREFIX_DB . Adherent::TABLE;
			$select->from(
					array('a' => $table_adh)
				);
			$select->columns(array('prenom_adh'));
			
            
            $res = $zdb->execute($select);
            $res = $res->toArray();
            
			foreach ($res as $cle => $valeur) 
			{
				$prenom[$cle]  = $valeur['prenom_adh'];
			};

			array_multisort($prenom, SORT_ASC, $res);

			$listOfStudentsSurname=array();
			
			foreach ($res as $cle => $valeur) 
			{
				$listOfStudentsSurname[]=$valeur['prenom_adh'];
			};
            if ( count($listOfStudentsSurname) > 0 ) {
                return $listOfStudentsSurname;
            } else {
                return array();
            }
        } catch (\Exception $e) {
            Analog::log(
                'Unable to retrieve members promotion for "' .
                $id_cycle  . '" | "" | ' . $e->getMessage(),
                Analog::WARNING
            );
            return false;
        }
    }

    /*Select the closest element of one other in a list with levhenstein distance
	IN : one element+one liste of same element types
	OUT : the closest element found
	COM : levhenstein was soon implemented in php*/
	
	public function proximite_levenshtein($researched_name,$listOfStudents)
    {
        global $zdb;

        try {
			// aucune distance de trouvée pour le moment
			$res = array( "dist" => -1,
						  "name" => "");

			// boucle sur les des mots pour trouver le plus près
			foreach ($listOfStudents as $cle=>$possible_student) {
				// calcule la distance avec le mot mis en entrée,
				// et le mot courant
				$lev = levenshtein($researched_name, $possible_student);

				// cherche une correspondance exacte
				if ($lev == 0) {

					// le mot le plus près est celui-ci (correspondance exacte)
					$res["name"] = $possible_student;
					$res["dist"] = 0;

					// on sort de la boucle ; nous avons trouvé une correspondance exacte
					break;
				};

				// Si la distance est plus petite que la prochaine distance trouvée
				// OU, si le prochain mot le plus près n'a pas encore été trouvé
				if ($lev <= $res["dist"] || $res["dist"] < 0) {
					// définition du mot le plus près ainsi que la distance
					$res["name"]  = $possible_student;
					$res["dist"] = $lev;
				};
			};
			return $res;
			
		} catch (\Exception $e) {
			Analog::log(
				'Unable to retrieve members promotion for "' .
				$id_cycle  . '" | "'  . $e->getMessage(),
				Analog::WARNING
			);
			return false;
		};
	}

    /*Select the closest element of one other in a list with levhenstein distance
	IN : one element+one liste of same element types
	OUT : the closest element found
	COM : levhenstein was soon implemented in php*/
	
	public function search_name_surname($researched_name_surname)
	{
		$req = explode(" ", $researched_name_surname);
		$count = count($req);
		
		//Get all students name
		$studentsName = $this->getNameOfAllStudents();
		//Creation d'un tableau contenant les prénoms de chaque eleve
		$studentsSurname = $this->getSurnameOfAllStudents();

		if($count == 1){
			// Search for Name
			$researched_name=$req[0];
			//Text to uppercase
			$researched_name = strtoupper($researched_name);
			//Récupération du nom le plus proche
			$found_name = $this->proximite_levenshtein($researched_name,$studentsName);

			// Search for Surname
			$researched_surname=$req[0];
			$researched_surname = strtolower($researched_surname);
			//Transforme la premièrer lettre en majuscule
			$researched_surname[0] = strtoupper($researched_surname[0]);
			//Récupération du nom le plus proche
			$found_surname = $this->proximite_levenshtein($researched_surname,$studentsSurname);

			// on garde le plus proche (ou les deux si =)
			if($found_name["dist"] <= $found_surname["dist"]){
				$out = array("nom" => $found_name["name"]);
			} else { //if ($found_name["dist"] > $found_surname["dist"])
				$out = array("prenom" => $found_surname["name"]);
			}
		}
		if($count == 2){
			//Case Name / Surname
			// Search for Name
			$researched_name=$req[0];
			//Text to uppercase
			$researched_name = strtoupper($researched_name);
			//Récupération du nom le plus proche
			$found_name_first = $this->proximite_levenshtein($researched_name,$studentsName);

			// Search for Surname
			$researched_surname=$req[1];
			$researched_surname = strtolower($researched_surname);
			//Transforme la premièrer lettre en majuscule
			$researched_surname[0] = strtoupper($researched_surname[0]);
			//Récupération du nom le plus proche
			$found_surname_second = $this->proximite_levenshtein($researched_surname,$studentsSurname);
			
			$dist_case_name_surname = $found_name_first["dist"] + $found_surname_second["dist"];

			//Case Surname / Name
			// Search for Name
			$researched_name=$req[1];
			//Text to uppercase
			$researched_name = strtoupper($researched_name);
			//Récupération du nom le plus proche
			$found_name_second = $this->proximite_levenshtein($researched_name,$studentsName);

			// Search for Surname
			$researched_surname=$req[0];
			$researched_surname = strtolower($researched_surname);
			//Transforme la premièrer lettre en majuscule
			$researched_surname[0] = strtoupper($researched_surname[0]);
			//Récupération du nom le plus proche
			$found_surname_first = $this->proximite_levenshtein($researched_surname,$studentsSurname);
			
			$dist_case_surname_name = $found_name_second["dist"] + $found_surname_first["dist"];
			
			// Get the better combinaison :
			if($dist_case_name_surname <= $dist_case_surname_name ){
				$out = array(
					"nom" => $found_name_first["name"],
					"prenom" => $found_surname_second["name"],		
				);
			} else {
				$out = array(
					"nom" => $found_name_second["name"],
					"prenom" => $found_surname_first["name"],		
				);
			}
			
		}
		return $out;
		
	}
	
	/*Get student by all elements
	IN :	array(
	* 			"nom" => ?,
	* 			"prenom" => ?,
	* 			"nom_prenom"  => ?,
	* 			"cycle"  => ?,
	* 			"cycle_simple"  => ?,
	* 			"annee_debut" => ?,
	*  			"employeur"  => ?,
	* 		)
	* (each one is optional, you just have to put only one)
	OUT : array
	*/
	
	public function getStudent($req)
    {
        global $zdb;

        try {
            $select = $zdb->sql->select();
            $table_adh = PREFIX_DB . Adherent::TABLE;
			$select->from(
					array('a' => $table_adh)
				);
			
 			$select->join(array('f' => Formations::getTableName()),
				'f.id_adh = a.' . Adherent::PK,
				array('specialite','annee_debut'));
			
			$select->join(array('c' => Cycles::getTableName()),
			'f.id_cycle = c.' . Cycles::PK,
			array('nom','id_cycle'));
						
			$select->columns(array(Adherent::PK, 'id_adh','nom_adh', 'prenom_adh'));
				
			$init=false;
			if (array_key_exists("nom_prenom",$req)){
				//Get possible name / surname
				$res = $this->search_name_surname($req["nom_prenom"]);
				//Add it to the normal query
				$req = array_merge($req, $res);
			};
			if (array_key_exists("nom",$req)){
				//
				$researched_name=$req["nom"];
				//Text to uppercase
				$researched_name = strtoupper($researched_name);
				//Get all students name
				$studentsName = $this->getNameOfAllStudents();
				//Récupération du nom le plus proche
				$found_name = $this->proximite_levenshtein($researched_name,$studentsName)["name"];

				$select->where->equalTo('a.nom_adh', $found_name);
				$init=true;
			};
			if (array_key_exists("prenom",$req)){
				//
				$researched_surname=$req["prenom"];
	
				//Transforme le prenom en minuscule
				$researched_surname = strtolower($researched_surname);
				//Transforme la premièrer lettre en majuscule
				$researched_surname[0] = strtoupper($researched_surname[0]);
				//Creation d'un tableau contenant les prénoms de chaque eleve
				$studentsSurname = $this->getSurnameOfAllStudents();
				//Récupération du nom le plus proche
				$found_surname = $this->proximite_levenshtein($researched_surname,$studentsSurname)["name"];
				
				$select->where->equalTo('a.prenom_adh', $found_surname);
				$init=true;
			};
			if (array_key_exists("cycle",$req)){
				$select->where->equalTo('f.id_cycle', $req["cycle"]);
				$init=true;
			};

			if (array_key_exists("cycle_simple",$req)){
				$all_cycle_simple=[];
				foreach ($req["cycle_simple"] as $i => $value) {
					switch ($value) {
					case "IT":
						$all_cycle_simple[] = 2;
						$all_cycle_simple[] = 51;
						break;
					case "G":
						$all_cycle_simple[] = 3;
						$all_cycle_simple[] = 52;
						break;
					case "DC":
						$all_cycle_simple[] = 6;
						$all_cycle_simple[] = 56;
						break;
					case "LPRO":
						$all_cycle_simple[] = 50;
						break;
					}//switch
					$init=true;
				}//foreach
				$select->where(array('f.id_cycle' => $all_cycle_simple));
			};
			if (array_key_exists("annee_debut",$req)){
				$select->where->equalTo('f.annee_debut', $req["annee_debut"]);
				$init=true;
			};
			if (array_key_exists("employeur",$req)){
				//TODO : ça ne peut pas fonctionner !? il faut utiliser la table entreprises ...
				$select->where->equalTo('f.id_employeur', $req["employeur"]);
				$init=true;
			}; 
			if (!$init){
				$select->where->equalTo('a.nom_adh', '-');
			};
            
            $res = $zdb->execute($select);
            $res = $res->toArray();
            
            if ( count($res) > 0 ) {
                return $res;
            } else {
                return array();
            }
        } catch (\Exception $e) {
            Analog::log(
                'Unable to retrieve members promotion for "' .
                $id_cycle  . '" | "' . $annee_debut .'" | ' . $e->getMessage(),
                Analog::WARNING
            );
            return false;
        }
    }

	
	/*Get information of one student
	IN : id
	OUT : array
	COM : - Our principal fonction to get informations and display member informations*/
	
	public function getInfoById($id_adh)
    {
        global $zdb;

        try {
            $select = $zdb->sql->select();
            $table_adh = PREFIX_DB . Adherent::TABLE;
			$select->from(
					array('a' => $table_adh)
				);
			
			$select->columns(array(Adherent::PK, 'nom_adh', 'prenom_adh'));
			
 			$select->join(array('f' => Formations::getTableName()),
				'f.id_adh = a.' . Adherent::PK,
				array('id_cycle','annee_debut'));
			
			$select->join(array('c' => Cycles::getTableName()),
			'f.id_cycle = c.' . Cycles::PK,
			array('nom'));
							
			$select->where->equalTo('a.id_adh', $id_adh);
            
            $res = $zdb->execute($select);
            $res = $res->toArray();
            
            if ( count($res) > 0 ) {
                return $res;
            } else {
                return array();
            }
        } catch (\Exception $e) {
            Analog::log(
                'Unable to retrieve Student : "' .
                $req .'" | ' . $e->getMessage(),
                Analog::WARNING
            );
            return false;
        }
    }
	

	public function getGeoSpatialInfo($id_adh)
	{
		global $zdb;

		try {
			$select = $zdb->sql->select();
			$table_adh = PREFIX_DB . Adherent::TABLE;
			$select->from(
					array('a' => $table_adh)
				);
			
			$select->columns(array(Adherent::PK, 'nom_adh', 'prenom_adh', 'pays_adh', 'cp_adh', 'ville_adh'));
			
 			/*$select->join(array('f' => Formations::getTableName()),
				'f.id_adh = a.' . Adherent::PK,
				array('id_cycle','annee_debut'));
			
			$select->join(array('c' => Cycles::getTableName()),
			'f.id_cycle = c.' . Cycles::PK,
			array('nom'));*///*
							
			$select->where->equalTo('a.id_adh', $id_adh);
			
			$res = $zdb->execute($select);
			$res = $res->toArray();
			
			if ( count($res) > 0 ) {
				return $res;
			} else {
				return array();
			}
		} catch (\Exception $e) {
			Analog::log(
				'Unable to retrieve Student : "' .
				$req .'" | ' . $e->getMessage(),
				Analog::WARNING
			);
			return false;
		}
	}
	
}


?>
