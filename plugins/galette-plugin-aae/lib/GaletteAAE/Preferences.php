<?php

namespace Galette\AAE;

use Analog\Analog as Analog;

class Preferences
{
    const TABLE = 'preferences';
    const PK = '"pref_aae_rib"';

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
            $select = new \Zend_Db_Select($zdb->db);
            $select->from($this->getTableName())->where('nom_pref = '. self::PK);
            $res = $select->query(\Zend_Db::FETCH_ASSOC)->fetchAll();
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
            Analog::log(
                'Query was: ' . $select->__toString() . ' ' . $e->__toString(),
                Analog::ERROR
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
       	
	// if pref don't already exist, we add it to the DB 
            $stmt = $zdb->db->prepare(
                'INSERT INTO ' . PREFIX_DB . self::TABLE .
                ' (' . $zdb->db->quoteIdentifier('nom_pref') . ',' . $zdb->db->quoteIdentifier('val_pref') . ') ' .
                ' VALUES (' . self::PK . ' , "'. $rib . '") ON DUPLICATE KEY UPDATE ' .
                 $zdb->db->quoteIdentifier('val_pref') . ' =  "'. $rib .'"'
            );
            Analog::log('Storing RIB', Analog::DEBUG);

            $stmt->execute();
            Analog::log(
                'Preferences RIB were successfully stored into database.',
                Analog::INFO
            );
            return true;
        } catch (\Exception $e) {
            /** TODO */
            Analog::log(
                'Unable to store preferences RIB | ' . $e->getMessage(),
                Analog::WARNING
            );
            Analog::log(
                $e->__toString(),
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
        return PREFIX_DB   . self::TABLE;
    }
}
?>

