<?php

namespace Galette\AAE;

use Analog\Analog as Analog;

class Entreprises
{
    const TABLE = 'entreprises';
    const PK = 'id_entreprise';
    const EM = 'employeur';

    /**
     * Retrieve all entreprises
     *
     * @param 
     *
     * @return array
     */
    public function getAllEntreprises()
    {
        global $zdb;

        try {
            $select = $zdb->selectAll(AAE_PREFIX . self::TABLE);
            $res = $select->toArray();
            if ( count($res) > 0 ) {
                return $res;
            } else {
                return array();
            }
        } catch (\Exception $e) {
            Analog::log(
                'Unable to retrieve entreprises : "' . $e->getMessage(),
                Analog::WARNING
            );
            return false;
        }
    }
    
     /**
     * Retrieve entreprise information
     *
     * @param int $id Entreprise id
     *
     * @return array
     */
    public function getEntreprise($id)
    {
        global $zdb;

        try {
            $select = $zdb->select(AAE_PREFIX . self::TABLE);
            $select->where->equalTo('id_entreprise',$id);
            $res = $zdb->execute($select);
            $res = $res->toArray();
            if ( count($res) > 0 ) {
                return $res[0];
            } else {
                return array();
            }
        } catch (\Exception $e) {
            Analog::log(
                'Unable to retrieve entreprise information for "' .
                $id  . '". | ' . $e->getMessage(),
                Analog::WARNING
            );
            return false;
        }
    }



    

     /**
     * SetEntreprise
     * @param int $id_form
     * @param varchar $employeur
     * @param varchar $website
     */
    public function setEntreprise($id_form,$employeur,$website)
    {
        global $zdb;

        try {
            $res  = null;
            $data = array(
                        'employeur'   => $employeur,
                        'website'   => $website
                    );
            if ( $id_form == '' ) {
                //Entreprise does not exists yet
                $insert = $zdb->insert(AAE_PREFIX . self::TABLE);
                $insert->values($data);
                $add = $zdb->execute($insert);
                
                if ( $add->count() == 0) {
                    Analog::log('An error occured inserting new poste!' );
                }
                
            } else {
                //Entreprises already exists              
                $update = $zdb->update(AAE_PREFIX . self::TABLE);
                $update->set($data)->where->equalTo(self::PK,$id_form);
                $edit = $zdb->execute($update);
                //edit == 0 does not mean there were an error, but that there
                //were nothing to change
            }
            return ($res > 0);
        } catch ( \Exception $e ) {
            Analog::log(
                'Unable to set entreprise ' .
                $id_form . ' | ' . $e->getMessage(),
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
        return  PREFIX_DB . AAE_PREFIX  .  self::TABLE;
    }
}
?>