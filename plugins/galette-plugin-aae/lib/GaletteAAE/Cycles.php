<?php

namespace Galette\AAE;

use Analog\Analog as Analog;

class Cycles
{
    const TABLE = 'cycles';
    const PK = 'id_cycle';

    /**
     * Retrieve all cycles
     *
     * @param 
     *
     * @return array
     */
    public function getAllCycles()
    {
        global $zdb;

        try {
            $select = new \Zend_Db_Select($zdb->db);
            $select->from($this->getTableName())->where(true);
            $res = $select->query(\Zend_Db::FETCH_ASSOC)->fetchAll();
            if ( count($res) > 0 ) {
                return $res;
            } else {
                return array();
            }
        } catch (\Exception $e) {
            Analog::log(
                'Unable to retrieve cycles : "' . $e->getMessage(),
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
     * Retrieve cycle information
     *
     * @param int $id Cycle id
     *
     * @return array
     */
    public function getCycle($id)
    {
        global $zdb;

        try {
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
                'Unable to retrieve cycle information for "' .
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

