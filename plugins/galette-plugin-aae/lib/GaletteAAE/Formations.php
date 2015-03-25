<?php

namespace Galette\AAE;

use Analog\Analog as Analog;
use Galette\Entity\Adherent as Adherent;
use Galette\Repository\Members as Members;

require_once 'lib/GaletteAAE/Cycles.php';
use Galette\AAE\Cycles as Cycles;

/**
 * Members Formations
 *
 * @category  Plugins
 */

class Formations
{
    const TABLE =  'formations';
    const PK = 'id';

    /**
     * Retrieve member formations
     *
     * @param int $id_adh Member id
     *
     * @return array
     */
    public function getFormations($id_adh)
    {
        global $zdb;

        try {
        
        	$select = $zdb->sql->select();
        	$select->from(array('f' => $this->getTableName()));
							
			$select->join(array('c' => Cycles::getTableName()),
				'f.id_cycle = c.' . Cycles::PK,
				array('id_cycle','nom'));
				

			$select->where->equalTo('f.'. Adherent::PK, $id_adh);
            
            $res = $zdb->execute($select);
            $res = $res->toArray();
            
            if ( count($res) > 0 ) {
                return $res;
            } else {
                return array();
            }

        } catch (\Exception $e) {
            Analog::log(
                'Unable to retrieve members formations for "' .
                $id_adh  . '". | ' . $e->getMessage(),
                Analog::WARNING
            );
            return false;
        }
    }
    
    /**
     * Retrieve one formation
     *
     * @param int $id_form Member id
     *
     * @return array
     */
    public function getFormation($id_form)
    {
        global $zdb;

        try {
            $select = $zdb->select($this->getTableName());
            $select->where->equalTo(Formations::PK, $id_form);

            $res = $zdb->execute($select);
            $res = $res->toArray();
            
            if ( count($res) > 0 ) {
                return $res[0];
            } else {
                return array();
            }
        } catch (\Exception $e) {
            Analog::log(
                'Unable to retrieve formation with id : "' .
                $id_form  . '". | ' . $e->getMessage(),
                Analog::WARNING
            );
            return false;
        }
    }

    /**
     * SetFormation
     * @param int $id_form
     * @param int $id_cycle
     * @param string $specialite
     * @param int $date_debut
     * @param int $date_fin    
     * @param int $id_adh
     */
    public function setFormation($id_form,$id_adh,$id_cycle,$specialite,$date_debut,$date_fin)
    {
		global $zdb;

        try {
            $res  = null;
            $data = array(
                        'id_cycle'   => $id_cycle,
                        'specialite' => $specialite,
                        'annee_debut' => $date_debut,
                        'annee_fin'   => $date_fin,
                        'id_adh' => $id_adh
                    );

            if ( $id_form == '' ) {
                //Formation does not exists yet
                $insert = $zdb->insert(AAE_PREFIX . self::TABLE);
                $insert->values($data);
                $add = $zdb->execute($insert);
                
                if ( $add->count() == 0) {
                    Analog::log('An error occured inserting new formation!' );
                }
                
            } else {
                //Formation already exists, just update               
                $update = $zdb->update(AAE_PREFIX . self::TABLE);
                $update->set($data)->where->equalTo(self::PK,$id_form);
                $edit = $zdb->execute($update);
                //edit == 0 does not mean there were an error, but that there
                //were nothing to change
            }
            return ($res > 0);
        } catch ( \Exception $e ) {
            Analog::log(
                'Unable to set formation ' .
                $id_form . ' | ' . $e->getMessage(),
                Analog::ERROR
            );
            return false;
        }
    }

    /**
     * removeFormation
     * @param int $id_form
     */
    public function removeFormation($id_form)
    {
        global $zdb;

        try {

            $delete = $zdb->delete(AAE_PREFIX . self::TABLE);
            $delete->where->equalTo(self::PK, $id_form);
            $zdb->execute($delete);
            return true;
        } catch ( \Exception $e ) {
            Analog::log(
                'Unable to delete formation ' .
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
    static function getTableName()
    {
        return PREFIX_DB . AAE_PREFIX . self::TABLE;
    }
}


?>
