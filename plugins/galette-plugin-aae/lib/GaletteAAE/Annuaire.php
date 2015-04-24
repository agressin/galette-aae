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
	
	public function getStudent($post_nom,$post_prenom, $post_promo, $post_formation, $post_cycle, $post_employeur)
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
			if ($post_nom!=""){
				$select->where->equalTo('a.nom_adh', $post_nom);
				$init=true;
			};
			if ($post_prenom!=""){
				$select->where->equalTo('a.prenom_adh', $post_prenom);
				$init=true;
			};
			if ($post_cycle!=0){
				$select->where->equalTo('f.id_cycle', $post_cycle);
				$init=true;
			};
			if ($post_promo!=0){
				$select->where->equalTo('f.annee_debut', $post_promo);
				$init=true;
			};
			if ($post_employeur!=0){
				$select->where->equalTo('f.id_employeur', $post_employeur);
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
	
}


?>
