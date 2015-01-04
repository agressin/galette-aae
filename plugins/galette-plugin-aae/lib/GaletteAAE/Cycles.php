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
            $select = $zdb->selectAll($this->getTableName());
            $res = $select->toArray();
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
            $select = $zdb->select($this->getTableName());
            $select->where(self::PK . ' = ?', $id);
            $res = $select->toArray();
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
        return  AAE_PREFIX  . self::TABLE;
    }
}
?>

