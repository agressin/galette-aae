<?php

namespace Galette\AAE;

use Analog\Analog as Analog;

class Preferences
{
    const TABLE = 'preferences';
    const PK = 'pref_aae_rib';

    /**
     * Retrieve RIB in preferences table
     *
     * @param 
     *
     * @return array
     */
    public function getRIB()
    {
        global $zdb;

        try {
            $select = $zdb->select(self::TABLE);
            $select->where->equalTo('nom_pref', self::PK);
            
            $res = $zdb->execute($select);
            $res = $res->toArray();
            
            if ( count($res) > 0 ) {
                return $res[0]["val_pref"];
            } else {
                return false;
            }
        } catch (\Exception $e) {
            Analog::log(
                'Unable to retrieve RIB in preferences table : "' . $e->getMessage(),
                Analog::WARNING
            );

            return false;
        }
    }
    
     /**
     * Will store the  RIB in the DB
     *
     * @param string $rib : le rib !
     *
     * @return boolean
     */
    public function setRIB($rib)
    {
    	global $zdb;
        try {
        
        	 $data = array(
                        'nom_pref'   => self::PK,
                        'val_pref' => $rib
                    );
             //Try to insert RIB
             $insert = $zdb->insert( self::TABLE);
             $insert->values($data);
             $add = $zdb->execute($insert);

            return true;
        } catch (\Exception $e) {
        	
        	 if($e->getCode() == 23000) {
             	Analog::log('RIB already exist, try to update it' );
             	$update = $zdb->update( self::TABLE);
                $update->set($data)->where->equalTo(nom_pref, self::PK);
                $edit = $zdb->execute($update);
                return true;
                    
             }else{
            	Analog::log(
            	    'Unable to store preferences RIB | ' . $e->getMessage(),
           	     	Analog::WARNING
            	);
            
           		return false;
            }
        }
    }

    /**
     * Get table's name
     *
     * @return string
     */
    protected function getTableName()
    {
        return PREFIX_DB . self::TABLE;
    }
}
?>

