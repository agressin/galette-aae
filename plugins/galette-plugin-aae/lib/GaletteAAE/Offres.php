<?php

namespace Galette\AAE;

use Analog\Analog as Analog;

class Offres
{
    const TABLE = 'offres';
    const PK = 'id';

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
        //TODO
            $select = new \Zend_Db_Select($zdb->db);
            if($onlyValidOffer) {
				$select->from($this->getTableName())->where("valide= ?",true);
			} else {
				$select->from($this->getTableName())->where(true);;
			}
            $res = $select->query(\Zend_Db::FETCH_ASSOC)->fetchAll();
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
            Analog::log(
                'Query was: ' . $select->__toString() . ' ' . $e->__toString(),
                Analog::ERROR
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
        //TODO
            $select = new \Zend_Db_Select($zdb->db);
			$select->from($this->getTableName())->where("valide= ?",false);
            $res = $select->query(\Zend_Db::FETCH_ASSOC)->fetchAll();
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
            Analog::log(
                'Query was: ' . $select->__toString() . ' ' . $e->__toString(),
                Analog::ERROR
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
        //TODO
            $select = new \Zend_Db_Select($zdb->db);
            $select->from($this->getTableName())->where(self::PK . ' = ?', $id);
            $res = $select->query(\Zend_Db::FETCH_ASSOC)->fetchAll();
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
            Analog::log(
                'Query was: ' . $select->__toString() . ' ' . $e->__toString(),
                Analog::ERROR
            );
            return false;
        }
    }
    
 
    /**
     * SetOffre
     * @param int $id_offre
     * @param ...
     */
    public function setOffre($id_offre,$id_adh,$titre,$organisme,$localisation,$site,$nom_contact,$mail_contact,$tel_contact,$date_fin,
		$type_offre,$desc_offre,$mots_cles,$duree,$date_debut,$remuneration, $cursus, $tech_majeures,$valide)
    {
		global $zdb;

        try {
			$date_parution = date("Y-m-d");
            $res  = null;
            $data = null;
 
            $data = array(
                        'titre'   		=> $titre,
                        'id_adh'		=> $id_adh,
                        'organisme'   	=> $organisme,
                        'localisation'  => $localisation,
                        'site'   		=> $site,
                        'nom_contact'   => $nom_contact,
                        'mail_contact'  => $mail_contact,
                        'tel_contact'   => $tel_contact,
                        'date_fin'   	=> $date_fin,
                        'type_offre'   	=> $type_offre,
                        'desc_offre'   	=> $desc_offre,
                        'mots_cles'   	=> $mots_cles,
                        'duree'   		=> $duree,
                        'date_debut'   	=> $date_debut,
                        'remuneration'  => $remuneration,
                        'cursus'   		=> $cursus,
                        'tech_majeures' => $tech_majeures,
                        'date_parution' => $date_parution,
                        'valide'		=> $valide
                    );

            if ( $id_offre == '' ) {
                //Offer does not exists yet
                $res = $zdb->db->insert(
                    $this->getTableName(),
                    $data
                );
            } else {
                //Offer already exists, just update
                $res = $zdb->db->update(
                    $this->getTableName(),
                    $data,
                    self::PK . '=' . $id_offre
                );
            }
            return ($res > 0);
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
            $del = $zdb->db->delete(
                $this->getTableName(),
                self::PK . '=' . $id_offre
            );
            return ($del > 0);
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
            $res = $zdb->db->update(
                $this->getTableName(),
                $data,
                self::PK . '=' . $id_offre
            );
            return ($res > 0);
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
     * Get table's name
     *
     * @return string
     */
    static public function getTableName()
    {
        return PREFIX_DB . AAE_PREFIX  . self::TABLE;
    }
}
?>
