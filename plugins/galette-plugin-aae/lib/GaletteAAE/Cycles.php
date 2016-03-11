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
    public function getAllCyclesStats($only_used = true)
    {
        global $zdb;

        try {
      		/*
           */
          $select = $zdb->sql->select();

      		$select->from(array('c' => Cycles::getTableName()));

          if($only_used){
            $select->join(array('f' => Formations::getTableName()),
        			  'f.id_cycle = c.id_cycle',
        			  array('count' => new Expression('COUNT(*)'))
              );
          }else{
            $select->joinLeft(array('f' => Formations::getTableName()),
                'f.id_cycle = c.id_cycle',
                array('count' => new Expression('COUNT(*)'))
              );
          }

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

          $select->group('annee_debut');
          $select->order('annee_debut');
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
     * SetCycle
     * @param int $id_cycle
     * @param text $nom
     *
     */
    public function setCycle($id_cycle,$nom)
    {
        global $zdb;

        try {
          $res  = false;
          $data = array(
                'id_cycle' 	=> $id_cycle,
                'nom'       => $nom,
          );

          if ( $id_cycle == '' ) {
            //Cycle does not exists yet
            $insert = $zdb->insert(AAE_PREFIX . self::TABLE);
            $insert->values($data);
            $add = $zdb->execute($insert);

            if ( $add->count() == 0) {
              Analog::log('An error occured inserting new Cycle!' );
            } else {
              $id_cycle = $add->getGeneratedValue();

            }

          } else {
            //Cycle already exists, just update
            $update = $zdb->update(AAE_PREFIX . self::TABLE);
            $update->set($data)->where->equalTo(self::PK,$id_cycle);
            $edit = $zdb->execute($update);
          }
          return $id_cycle;
        } catch ( \Exception $e ) {
            Analog::log(
                'Unable to set cycle "' .
                $id_cycle . '" | ' . $e->getMessage(),
                Analog::ERROR
            );
            return false;
        }
    }

    /**
     * removeCycle
     * @param int $id_cycle
     */
    public function removeCycle($id_cycle)
    {
        global $zdb;

        try {
          $delete = $zdb->delete(AAE_PREFIX . self::TABLE);
          $delete->where->equalTo(self::PK, $id_cycle);
          $zdb->execute($delete);
          return true;
        } catch ( \Exception $e ) {
          Analog::log(
              'Unable to delete cycle ' .
              $id_cycle . ' | ' . $e->getMessage(),
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
