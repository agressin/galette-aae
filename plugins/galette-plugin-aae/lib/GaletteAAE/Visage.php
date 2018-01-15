<?php

namespace Galette\AAE;

use Analog\Analog as Analog;
use Galette\Entity\Adherent as Adherent;
//use Galette\Repository\Members as Members;

/**
 * Members Visage
 *
 * @category  Plugins
 */

class Visage
{

   /**
    * Get visage data of one adherent
    * @param Integer $id_cible The $id_adh
    * @param Boolean $remonter if need to go back
    * @return Array with all data
    */
   public function getDataByAdherent($id_cible, $remonter)
    {
        $data = [
            'success' => false,
            'error' => false,
            'cible' => false,
            'eleves' => false
        ];

        $cible = new Adherent();

        if ($cible->load($id_cible)) {

            $data['cible'] = $this->getAttributs($id_cible);

            $eleves = [];

            // On descend :
            $parrains = VisageRelation::getFillotsIds($id_cible);
            while (count($parrains) > 0) {
                $fillots = [];
                foreach ($parrains as $parrain) {
                    $fillots = array_merge($fillots, VisageRelation::getParrainsIds($parrain));
                    $eleves[$parrain] = $this->getAttributs($parrain);
                }
                $parrains = $fillots;
            }

            // On remonte ?
            if ($remonter === true) {

                $fillots = VisageRelation::getParrainsIds($id_cible);
                while (count($fillots) > 0) {
                    $parrains = [];
                    foreach ($fillots as $fillot) {
                        $parrains = array_merge($parrains, VisageRelation::getFillotsIds($fillot));
                        $eleves[$fillot] = $this->getAttributs($fillot);
                    }
                    $fillots = $parrains;
                }
            }

            $eleves[$cible->getIde()] = $data['cible'];

            $data['success'] = true;
            $data['error'] = true;
            $data['eleves'] = $eleves;
        }

        return $data;
    }


    /**
     * Get parrains of specified adherent
     * @param $id_adh
     * @return parrains Adherent
     * @deprecated a priori pas utile au final
     */
    public function getParrains($id_adh)
    {
        global $zdb;

        try {

            $parrainsIDs = VisageRelation::getParrainsIds($id_adh);
            foreach ($parrainsIDs as $parrainID) {
                $parrain = new Adherent();
                $parrain->load($parrainID);
                $parrains[] = $parrain;
            }
            return $parrains;

        } catch (\Exception $e) {
            Analog::log(
                'Unable to retrieve parrains for id_adh="' . $id_adh  . '" | "" | ' . $e->getMessage(),
                Analog::WARNING
            );
            return [];
        }
    }


    /**
     * Get fillots of specified adherent
     * @param $id_adh
     * @return fillots Adherent
     * @deprecated a priori pas utile au final
     */
    public function getFillots($id_adh)
    {
        global $zdb;

        try {

            $parrainsIDs = VisageRelation::getFillotsIds($id_adh);
            foreach ($parrainsIDs as $parrainID) {
                $parrain = new Adherent();
                $parrain->load($parrainID);
                $parrains[] = $parrain;
            }
            return $parrains;

        } catch (\Exception $e) {
            Analog::log(
                'Unable to retrieve fillots for id_adh="' . $id_adh  . '" | "" | ' . $e->getMessage(),
                Analog::WARNING
            );
            return [];
        }
    }


    /**
     * Get attributs in key_value of specified adherent
     * @param  Adherent   $adherent
     * @return Array                 Adherent data
     * @deprecated Voir en bas, à priori plutot utiliser l'autre fonction
     */
    /*private function getAttributs(Adherent $adherent) {

        global $zdb;

        $data = [];

        try {
            $select = $zdb->select(Adherent::TABLE);
            $select->where(Adherent::PK . ' = ' . $id);

            $results = $zdb->execute($select);
            $row = $results->current();
            $data['ide'] = $row->id_adh;
            $data['nom'] = ucfirst(mb_strtolower($row->nom_adh, 'UTF-8');
            $data['prenom'] = ucfirst(mb_strtolower($row->prenom_adh, 'UTF-8');
            $data['annee'] = 2015;
            $data['has_picture'] => $adherent->hasPicture(),
            $data['src'] => $adherent->hasPicture() ? 'TODO' : '';
            $data['parrains'] => $adherent->getIde();
            $data['fillots'] => $adherent->getIde();
        } catch (\Exception $e) {
            Analog::log(
                'Cannot get attributs for member id=`' . $id . '` | ' . $e->getMessage(),
                Analog::WARNING
            );
            return false;
        }
    }*/


    /**
     * Get attributs in key_value of specified adherent
     * @param  Adherent   $adherent
     * @return Array                 Adherent data
     */
   public function getAttributs($id_adh)
    {
        global $zdb;

        try {
            $select = $zdb->sql->select();
            $table_adh = PREFIX_DB . Adherent::TABLE;
            $select->from(
                array('a' => $table_adh)
            );

            $select->columns(array(Adherent::PK, 'id_adh', 'nom_adh', 'prenom_adh'));

            $select->join(array('f' => Formations::getTableName()), 'f.id_adh = a.' . Adherent::PK, array('id_cycle','annee_debut'));

            $select->join(array('c' => Cycles::getTableName()), 'f.id_cycle = c.' . Cycles::PK, array('nom'));

            $select->where->equalTo('a.id_adh', $id_adh);

            $res = $zdb->execute($select);
            $res = $res->toArray();

            if ( count($res) == 1 ) {
                $d = $res[0];
                return [
                    'ide' => $d['id_adh'],
                    'nom' => $d['nom_adh'],
                    'prenom' => $d['prenom_adh'],
                    'annee' => $d['annee_debut'],
                    'cycle' => $d['cycle'],
                    'src' => false, // TODO
                    'parrains' => VisageRelation::getParrainsIds($d['id_adh']),
                    'fillots' => VisageRelation::getFillotsIds($d['id_adh'])
                ];
            } else {
                return false;
            }
        } catch (\Exception $e) {
            Analog::log(
                'Unable to retrieve attributs : "' . $id_adh .'" | ' . $e->getMessage(),
                Analog::WARNING
            );
            return false;
        }
    }









/*
Fonctions conservée pour avoir des exemples.
 */


    /*Select the closest element of one other in a list with levhenstein distance
   IN : one element+one liste of same element types
   OUT : the closest element found
   COM : levhenstein was soon implemented in php*/

   /*public function search_name_surname($researched_name_surname)
   {
      $req = explode(' ', $researched_name_surname);
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
         if($found_name['dist'] <= $found_surname['dist']){
            $out = array('nom' => $found_name['name']);
         } else { //if ($found_name['dist'] > $found_surname['dist'])
            $out = array('prenom' => $found_surname['name']);
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

         $dist_case_name_surname = $found_name_first['dist'] + $found_surname_second['dist'];

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

         $dist_case_surname_name = $found_name_second['dist'] + $found_surname_first['dist'];

         // Get the better combinaison :
         if($dist_case_name_surname <= $dist_case_surname_name ){
            $out = array(
               'nom' => $found_name_first['name'],
               'prenom' => $found_surname_second['name'],
            );
         } else {
            $out = array(
               'nom' => $found_name_second['name'],
               'prenom' => $found_surname_first['name'],
            );
         }

      }
      return $out;

   }

   /*Get student by all elements
   IN :   array(
   *          'nom' => ?,
   *          'prenom' => ?,
   *          'nom_prenom'  => ?,
   *          'cycle'  => ?,
   *          'cycle_simple'  => ?,
   *          'annee_debut' => ?,
   *           'employeur'  => ?,,
   *          'group_by_adh' => true/false
   *       )
   * (each one is optional, you just have to put only one)
   OUT : array
   */

   /*public function getStudent($req)
    {
        global $zdb;

        try {
          $select = $zdb->sql->select();
          $table_adh = PREFIX_DB . Adherent::TABLE;
               $select->from(
                     array('a' => $table_adh)
                  );

               if(array_key_exists('group_by_adh',$req) && $req['group_by_adh'] ){
                  $select->group('a.id_adh');
               }

                $select->join(array('f' => Formations::getTableName()),
                  'f.id_adh = a.' . Adherent::PK,
                  array('specialite','annee_debut'));

               $select->join(array('c' => Cycles::getTableName()),
               'f.id_cycle = c.' . Cycles::PK,
               array('nom','id_cycle'));

               $select->columns(array(Adherent::PK, 'id_adh','nom_adh', 'prenom_adh'));

               $init=false;
               if (array_key_exists('nom_prenom',$req)){
                  //Get possible name / surname
                  $res = $this->search_name_surname($req['nom_prenom']);
                  //Add it to the normal query
                  $req = array_merge($req, $res);
               };
               if (array_key_exists('nom',$req)){
                  //
                  $researched_name=$req['nom'];
                  //Text to uppercase
                  $researched_name = strtoupper($researched_name);
                  //Get all students name
                  $studentsName = $this->getNameOfAllStudents();
                  //Récupération du nom le plus proche
                  $found_name = $this->proximite_levenshtein($researched_name,$studentsName)['name'];

                  $select->where->equalTo('a.nom_adh', $found_name);
                  $init=true;
               };
               if (array_key_exists('prenom',$req)){
                  //
                  $researched_surname=$req['prenom'];

                  //Transforme le prenom en minuscule
                  $researched_surname = strtolower($researched_surname);
                  //Transforme la premièrer lettre en majuscule
                  $researched_surname[0] = strtoupper($researched_surname[0]);
                  //Creation d'un tableau contenant les prénoms de chaque eleve
                  $studentsSurname = $this->getSurnameOfAllStudents();
                  //Récupération du nom le plus proche
                  $found_surname = $this->proximite_levenshtein($researched_surname,$studentsSurname)['name'];

                  $select->where->equalTo('a.prenom_adh', $found_surname);
                  $init=true;
               };
               if (array_key_exists('cycle',$req)){
                  $select->where->equalTo('f.id_cycle', $req['cycle']);
                  $init=true;
               };

               if (array_key_exists('cycle_simple',$req)){
                  $all_cycle_simple=[];
                  foreach ($req['cycle_simple'] as $i => $value) {
                     switch ($value) {
                     case 'IT':
                        $all_cycle_simple[] = 2;
                        $all_cycle_simple[] = 51;
                        break;
                     case 'G':
                        $all_cycle_simple[] = 3;
                        $all_cycle_simple[] = 52;
                        break;
                     case 'DC':
                        $all_cycle_simple[] = 6;
                        $all_cycle_simple[] = 56;
                        break;
                     case 'LPRO':
                        $all_cycle_simple[] = 50;
                        break;
                     }//switch
                     $init=true;
                  }//foreach
                  $select->where(array('f.id_cycle' => $all_cycle_simple));
               };
               if (array_key_exists('annee_debut',$req)){
                  $select->where->equalTo('f.annee_debut', $req['annee_debut']);
                  $init=true;
               };
               if (array_key_exists('employeur',$req)){
                  //TODO : ça ne peut pas fonctionner !? il faut utiliser la table entreprises ...
                  $select->where->equalTo('f.id_employeur', $req['employeur']);
                  $init=true;
               };
               if (!$init){
                  $select->where(true);
               };
               $select->order('a.nom_adh')->order('a.prenom_adh')->order('f.annee_debut');

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

         /*$select->where->equalTo('a.id_adh', $id_adh);

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
   }*/

}


?>
