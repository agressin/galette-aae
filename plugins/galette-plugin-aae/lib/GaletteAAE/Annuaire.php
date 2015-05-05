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
			$shortest = -1;

			// boucle sur les des mots pour trouver le plus près
			foreach ($listOfStudents as $cle=>$possible_student) {
				// calcule la distance avec le mot mis en entrée,
				// et le mot courant
				$lev = levenshtein($researched_name, $possible_student);

				// cherche une correspondance exacte
				if ($lev == 0) {

					// le mot le plus près est celui-ci (correspondance exacte)
					$closest = $possible_student;
					$shortest = 0;

					// on sort de la boucle ; nous avons trouvé une correspondance exacte
					break;
				};

				// Si la distance est plus petite que la prochaine distance trouvée
				// OU, si le prochain mot le plus près n'a pas encore été trouvé
				if ($lev <= $shortest || $shortest < 0) {
					// définition du mot le plus près ainsi que la distance
					$closest  = $possible_student;
					$shortest = $lev;
				};
			};
			return $closest;
		} catch (\Exception $e) {
			Analog::log(
				'Unable to retrieve members promotion for "' .
				$id_cycle  . '" | "'  . $e->getMessage(),
				Analog::WARNING
			);
			return false;
		};
	}
	
	/*Get student by all elements
	IN : name+first name+promotion+cycle (each one is optional, you just have to put only one)
	OUT : array
	COM : - Our principal fonction to select student*/
	
	public function getStudent($post_nom,$post_prenom, $post_promo, $post_formation, $post_cycle)
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
				
			$compteur=0;
			
			
			#Count number of parameters selected
			if ($post_nom!=""){
				$compteur=$compteur+1;$nom=1;};
			if ($post_prenom!=""){
				$compteur=$compteur+1;$prenom=1;};
			if ($post_promo!=0){
				$compteur=$compteur+1;$promo=1;};
			if ($post_formation!=0){
				$compteur=$compteur+1;$formation=1;};
			if ($post_cycle!=0){
				$compteur=$compteur+1;$cycle=1;};
				
			
			if ($compteur==0){
				$select->where->equalTo('a.nom_adh', '-');}; #Displaying nothing if nothing is specified
				
			while ($compteur!=0){
				if ($nom==1){
					$select->where->equalTo('a.nom_adh', $post_nom);
					$compteur=$compteur-1;
					$nom==0;};#No request with name
				if ($prenom==1){
					$select->where->equalTo('a.prenom_adh', $post_prenom);
					$compteur=$compteur-1;
					$prenom=0;};#No request with first name
				if ($formation==1){
					$select->where->equalTo('f.id_cycle', $post_formation);
					$compteur=$compteur-1;
					$formation=0;
					$cycle=0;};#No request with cycle
				if ($cycle==1){
					$select->where->equalTo('f.id_cycle', $post_cycle);
					$compteur=$compteur-1;
					$cycle=0;
					$formation=0;};#No request with cycle
				if ($promo==1){
					$select->where->equalTo('f.annee_debut', $post_promo);
					$compteur=$compteur-1;
					$promo=0;}; #This line is never reach
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
                'Unable to retrieve members promotion for "' .
                $id_cycle  . '" | "' . $annee_debut .'" | ' . $e->getMessage(),
                Analog::WARNING
            );
            return false;
        }
    }
	
	public function getPromotion($id_cycle,$year)
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
							
			$select->where->equalTo('f.id_cycle', $id_cycle)
				   ->where->equalTo('f.annee_debut', $year);
            
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
	
	public function rechercheParNom($nomprenom)
    {
		$req = explode(" ", $nomprenom);
		$count = count($req);

		if($count == 1){
			//Search the words of the request as names
			$researched_name=$req[0];			
			//Text to upper
			$researched_name = strtoupper($researched_name);			
			//Get all students' names
			$studentsName = $this->getNameOfAllStudents();			
			//Récupération du nom le plus proche
			$found_name=$this->proximite_levenshtein($researched_name,$studentsName);
			//Récupération de l'élève avec ce nom ou nom proche
			$eleves = $this->getStudent($found_name);
			
			//Search the words of the request as surnames
			$researched_surname=$req[0];
			//Text to lower, first letter to upper
			$researched_surname[0] = strtoupper($researched_surname[0]);
			$researched_surname = strtolower($researched_surname);
			//Get all students' surnames
			$studentsSurname=$this->getSurnameOfAllStudents();
			//Récupération du prénom le plus proche
			$found_surname=$this->proximite_levenshtein($researched_surname,$studentsSurname);
			//Récupération de l'élève avec ce prenom ou prenom proche
			$elevesprenom = $this->getStudent(NULL, $found_surname);
			
			//concatène les 2 tableaux obtenus
			$eleves = array_merge($eleves, $elevesprenom);
		}
		
		if($count == 2){
			//CASE NAME SURNAME
			//Search the first word of the request as a name
			$researched_namefirst=$req[0];			
			//Text to upper
			$researched_namefirst = strtoupper($researched_namefirst);			
			//Get all students name
			$studentsName1 = $this->getNameOfAllStudents();			
			//Récupération des noms les plus proches
			$found_namefirst=$this->proximite_levenshtein($researched_namefirst,$studentsName1);
			
			//Search the second word of the request as a surname
			$researched_surnamesecond=$req[1];
			//Text to lower, first letter to upper
			$researched_surnamesecond[0] = strtoupper($researched_surnamesecond[0]);
			$researched_surnamesecond = strtolower($researched_surnamesecond);
			//Get all students' surnames
			$studentsSurname1=$this->getSurnameOfAllStudents();
			//Récupération des prénoms les plus proches
			$found_surnamesecond=$this->proximite_levenshtein($researched_surnamesecond,$studentsSurname1);
			
			$eleves =$this->getStudent($found_namefirst, $found_surnamesecond);
			
			//CASE SURNAME NAME
			//Search the first word of the request as a surname
			$researched_surnamefirst=$req[0];					
			//Text to lower, first letter to upper
			$researched_surnamefirst[0] = strtoupper($researched_surnamefirst[0]);
			$researched_surnamefirst = strtolower($researched_surnamefirst);
			//Get all students name
			$studentsSurname2 =$this->getSurnameOfAllStudents();			
			//Récupération des prénoms les plus proches
			$found_surnamefirst=$this->proximite_levenshtein($researched_surnamefirst,$studentsSurname2);
			
			//Search the second word of the request as a name
			$researched_namesecond=$req[1];
			//Text to upper
			$researched_namesecond = strtoupper($researched_namesecond);
			//Get all students' names
			$studentsName2= $this->getNameOfAllStudents();
			//Récupération des noms les plus proches
			$found_namesecond=$this->proximite_levenshtein($researched_namesecond,$studentsName2);
			
			$eleves1=$this->getStudent($found_namesecond, $found_surnamefirst);
			
			//UNION OF BOTH CASES TO DISPLAY THE RESULT		
			$eleves = array_merge($eleves, $eleves1);
		}
		if (count($eleves)>0){
			return $eleves;
		}
		else{
			return false;
		}
	}
}


?>
