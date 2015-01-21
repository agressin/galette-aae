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
			$select->from($this->getTableName())->where("valide= ?",false);
			
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
                $insert = $zdb->insert(AAE_PREFIX . self::TABLE);
                $insert->values($data);
                $add = $zdb->execute($insert);

            } else {
                //Offer already exists, just update
                $update = $zdb->update(AAE_PREFIX . self::TABLE);
                $update->set($data)->where->equalTo(self::PK,$id_offre);
                $edit = $zdb->execute($update);
            }
            return (true);
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
     * Get table's name
     *
     * @return string
     */
    protected function getTableName()
    {
        return PREFIX_DB . AAE_PREFIX  . self::TABLE;
    }
}
?>
