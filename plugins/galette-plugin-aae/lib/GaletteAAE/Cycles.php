<?php

namespace Galette\AAE;

use Analog\Analog as Analog;
use Zend\Db\Sql\Expression;

require_once 'lib/GaletteAAE/Formations.php';
use Galette\AAE\Formations as Formations;

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
    public function getAllCycles($only_used = true)
    {
        global $zdb;

        try {
      		/* select galette_aae_cycles.*
      		 * from galette_aae_cycles, galette_aae_formations
      		 * where galette_aae_cycles.id_cycle = galette_aae_formations.id_cycle
      		 * group by galette_aae_cycles.id_cycle
           */
          $select = $zdb->sql->select();

      		$select->from(array('c' => Cycles::getTableName()));

          if($only_used){
            $select->join(array('f' => Formations::getTableName()),
        			'f.id_cycle = c.id_cycle',
        			array());
            $select->group('c.id_cycle');
          }
          $select->order('nom');
          $select->where(true);

      		$res = $zdb->execute($select);
          $res = $res->toArray();


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
            $select = $zdb->select(AAE_PREFIX . self::TABLE);
            $select->where->equalTo(self::PK,$id);
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
     * Retrieve cycles stats
     *
     * @param
     *
     * @return array
     */
    public function getAllCyclesStats()
    {
        global $zdb;

        try {
      		/*
           */
          $select = $zdb->sql->select();

      		$select->from(array('c' => Cycles::getTableName()));

          $select->join(array('f' => Formations::getTableName()),
      			  'f.id_cycle = c.id_cycle',
      			  array('count' => new Expression('COUNT(*)'))
            );
          $select->group('c.id_cycle');

          $select->order('nom');
          $select->where(true);

      		$res = $zdb->execute($select);
          $res = $res->toArray();


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
     * Retrieve cycles stats
     *
     * @param
     *
     * @return array
     */
    public function getCycleStatByYear($id_cycle)
    {
        global $zdb;

        try {
      		/*
           */
          $select = $zdb->sql->select();

      		$select->from(array('c' => Cycles::getTableName()));

          $select->join(array('f' => Formations::getTableName()),
      			  'f.id_cycle = c.id_cycle',
      			  array('count' => new Expression('COUNT(*)'),'annee_debut')
            );

          $select->group('f.annee_debut');
          $select->where->equalTo('c.'.self::PK,$id_cycle);

      		$res = $zdb->execute($select);
          $res = $res->toArray();


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
