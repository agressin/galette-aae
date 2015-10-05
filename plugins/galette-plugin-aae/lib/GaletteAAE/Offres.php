<?php

namespace Galette\AAE;

use Analog\Analog as Analog;

require_once 'lib/GaletteAAE/Domaines.php';
use Galette\AAE\Domaines as Domaines;

class Offres
{
    const TABLE = 'offres';
    const TABLE_LIEN = 'liens_offre_domaine';
    const PK = 'id_offre';

    /**
     * Retrieve all Offres
     *
     * @param
     *
     * @return array
     */
    public function getAllOffres($onlyValidOffer=true)
    {
        global $zdb;

        try {

            $select = $zdb->sql->select();

            if($onlyValidOffer) {
				$select->from($this->getTableName())->where->equalTo("valide",true);
			} else {
				$select->from($this->getTableName())->where(true);;
			}

            $res = $zdb->execute($select);
            $res = $res->toArray();

            if ( count($res) > 0 ) {
                return $res;
            } else {
                return array();
            }
        } catch (\Exception $e) {
            Analog::log(
                'Unable to retrieve offres : "' . $e->getMessage(),
                Analog::WARNING
            );
            return false;
        }
    }

 /**
     * Retrieve offers to valid
     *
     * @param
     *
     * @return array
     */
    public function getOffresToValid()
    {
        global $zdb;

        try {

          $select = $zdb->sql->select();
		      $select->from($this->getTableName())->where->equalTo("valide",false);

          $res = $zdb->execute($select);
          $res = $res->toArray();

          if ( count($res) > 0 ) {
              return $res;
          } else {
              return array();
          }
        } catch (\Exception $e) {
            Analog::log(
                'Unable to retrieve offres : "' . $e->getMessage(),
                Analog::WARNING
            );
            return false;
        }
    }

 /**
     * Retrieve offers of one adh
     *
     * @param id_adh
     *
     * @return array
     */
    public function getAdhOffres($id_adh)
    {
        global $zdb;

        try {

            $select = $zdb->sql->select();
			$select->from($this->getTableName())->where->equalTo("id_adh",$id_adh);

            $res = $zdb->execute($select);
            $res = $res->toArray();

            if ( count($res) > 0 ) {
                return $res;
            } else {
                return array();
            }
        } catch (\Exception $e) {
            Analog::log(
                'Unable to retrieve offres : "' . $e->getMessage(),
                Analog::WARNING
            );
            return false;
        }
    }

     /**
     * Retrieve offre information
     *
     * @param int $id offre id
     *
     * @return array
     */
    public function getOffre($id)
    {
        global $zdb;

        try {

            $select = $zdb->sql->select();
            $select->from($this->getTableName())->where->equalTo(self::PK,$id);

            $res = $zdb->execute($select);
            $res = $res->toArray();

            if ( count($res) > 0 ) {
                return $res[0];
            } else {
                return array();
            }
        } catch (\Exception $e) {
            Analog::log(
                'Unable to retrieve offre information for "' .
                $id  . '". | ' . $e->getMessage(),
                Analog::WARNING
            );
            return false;
        }
    }


    /**
     * SetOffre
     * @param int $id_offre
     * @param ...
     */
    public function setOffre($id_offre,$id_adh,$titre,$id_entreprise,$localisation,$nom_contact,$mail_contact,$tel_contact,$date_fin,
		$type_offre,$desc_offre,$mots_cles,$duree,$date_debut,$remuneration, $cursus, $array_domaines,$valide)
    {
		global $zdb;

        try {
			      $date_parution = date("Y-m-d");
            $res  = null;
            $data = null;

            $data = array(
                        'titre'   		  => $titre,
                        'id_adh'		    => $id_adh,
                        'id_entreprise' => $id_entreprise,
                        'localisation'  => $localisation,
                        'nom_contact'   => $nom_contact,
                        'mail_contact'  => $mail_contact,
                        'tel_contact'   => $tel_contact,
                        'date_fin'   	  => $date_fin,
                        'type_offre'   	=> $type_offre,
                        'desc_offre'   	=> $desc_offre,
                        'mots_cles'   	=> $mots_cles,
                        'duree'   		  => $duree,
                        'date_debut'   	=> $date_debut,
                        'remuneration'  => $remuneration,
                        'cursus'   		  => $cursus,
                        'date_parution' => $date_parution,
                        'valide'		    => $valide
                    );

            if ( $id_offre == '' ) {
                //Offer does not exists yet
                $insert = $zdb->insert(AAE_PREFIX . self::TABLE);
                $insert->values($data);
                $add = $zdb->execute($insert);
				        $id_offre = $add->getGeneratedValue();
            } else {
                //Offer already exists, just update
                $update = $zdb->update(AAE_PREFIX . self::TABLE);
                $update->set($data)->where->equalTo(self::PK,$id_offre);
                $edit = $zdb->execute($update);
                $this->removeAllDomainesOfOffre($id_offre);
            }

            foreach( $array_domaines as $id_domaine){
            	$this->addDomaineToOffre($id_domaine,$id_offre);
            }
            return $id_offre;
        } catch ( \Exception $e ) {
            Analog::log(
                'Unable to set offer ' .
                $id_offre . ' | ' . $e->getMessage(),
                Analog::ERROR
            );
            return false;
        }
    }

    /**
     * removeOffer
     * @param int $id_offre
     */
    public function removeOffre($id_offre)
    {
        global $zdb;

        try {

            $delete = $zdb->delete(AAE_PREFIX . self::TABLE);
            $delete->where->equalTo(self::PK, $id_offre);
            $zdb->execute($delete);

            return (true);
        } catch ( \Exception $e ) {
            Analog::log(
                'Unable to delete offer ' .
                $id_offre . ' | ' . $e->getMessage(),
                Analog::ERROR
            );
            return false;
        }
    }


 /**
     * ValidOffre
     * @param int $id_offre
     * @param ...
     */
    public function ValidOffre($id_offre,$valide)
    {
		global $zdb;

        try {

            $data = array( 'valide'	=> $valide);
            //update
            $update = $zdb->update(AAE_PREFIX . self::TABLE);
            $update->set($data)->where->equalTo(self::PK,$id_offre);
            $edit = $zdb->execute($update);

            return (true);
        } catch ( \Exception $e ) {
            Analog::log(
                'Unable to valid offer ' .
                $id_offre . ' | ' . $e->getMessage(),
                Analog::ERROR
            );
            return false;
        }
    }


         /**
         * Retrieve all domaines of one job
         *
         * @param int $id_offre domaine id
         *
         * @return array
         */
        public function getDomainesFromOffre($id_offre)
        {
            global $zdb;

            try {
              $domaines = new Domaines();

            	$select = $zdb->sql->select();
            	$select->from(array('l' => $this->getTableLienName()));

        			$select->join(array('d' => $domaines->getTableName()),
        				'd.id_domaine = l.id_domaine');

        			$select->where->equalTo('l.id_offre', $id_offre);

              $res = $zdb->execute($select);
              $res = $res->toArray();

              $out = array();
              foreach( $res as $k){
              	$out[] = $k['id_domaine'];
              }
        			return $out;
            } catch (\Exception $e) {
                Analog::log(
                    'Unable to retrieve domaine from offre "' .
                    $id_offre  . '". | ' . $e->getMessage(),
                    Analog::WARNING
                );
                return false;
            }
        }

         /**
         * Retrieve all domaines of one offre
         *
         * @param int $id_offre domaine id
         *
         * @return array
         */
        public function getDomainesFromOffreToString($id_offre)
        {
            $domaines = new Domaines();
            $dom = $this->getDomainesFromOffre($id_offre);
            $temp= "";
            $all_dom = $domaines->getAllDomaines();
            foreach( $dom as $d){
            	$temp .= $all_dom[$d] . ', ';
            }
            return rtrim($temp,', ');
        }

        /**
         * remove All Domaines Of offre
         * @param int $id_offre
         */
        public function removeAllDomainesOfOffre($id_offre)
        {
            global $zdb;

            try {

                $delete = $zdb->delete(AAE_PREFIX . self::TABLE_LIEN);
                $delete->where->equalTo('id_offre', $id_offre);
                $zdb->execute($delete);
                return true;
            } catch ( \Exception $e ) {
                Analog::log(
                    'Unable to delete domaines of offre ' .
                    $id_offre . ' | ' . $e->getMessage(),
                    Analog::ERROR
                );
                return false;
            }
        }

    /**
         * SetFormation
         * @param int $id_domaine
         * @param int $id_offre
         */
        public function addDomaineToOffre($id_domaine,$id_offre)
        {
    		global $zdb;

            try {
                $res  = null;
                $data = array(
                            'id_domaine' => $id_domaine,
                            'id_offre'   => $id_offre
                        );

                $insert = $zdb->insert(AAE_PREFIX . self::TABLE_LIEN);
                $insert->values($data);
                $add = $zdb->execute($insert);

                if ( $add->count() == 0) {
                    Analog::log('An error occured when adding Domaine To Offre!' );
                }

                return ($res > 0);
            } catch ( \Exception $e ) {
                Analog::log(
                    'Unable to add domaine to offre ' .
                    $id_offre . ' | ' . $e->getMessage(),
                    Analog::ERROR
                );
                return false;
            }
        }



    /**
     * Get table's name
     *
     * @return string
     */
    protected function getTableName()
    {
        return PREFIX_DB . AAE_PREFIX  . self::TABLE;
    }

    /**
     * Get table link's name
     *
     * @return string
     */
	static public function getTableLienName()
    {
        return  PREFIX_DB . AAE_PREFIX  .  self::TABLE_LIEN;
    }
}
?>