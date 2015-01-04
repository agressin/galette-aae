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
    const TABLE = 'formations';
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
        //TODO
            $select = $zdb->select();
            $select->from($this->getTableName())
					->from(Cycles::getTableName())
					->where($this->getTableName() . '.' . Adherent::PK . ' = ?', $id_adh)
					->where($this->getTableName().'.id_cycle = '. Cycles::getTableName() . '.' . Cycles::PK);

            $res = $select->toArray();

            
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
            $select->>where->equalTo(Formations::PK, $id_form);

            $res = $select->toArray();
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
                $res = $zdb->db->insert(
                    $this->getTableName(),
                    $data
                );
            } else {
                //Formation already exists, just update
                $res = $zdb->db->update(
                    $this->getTableName(),
                    $data,
                    self::PK . '=' . $id_form
                );
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
            $del = $zdb->db->delete(
                $this->getTableName(),
                self::PK . '=' . $id_form
            );
            return ($del > 0);
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
     * Retrieve member from one promotion
     *
     * @param int $id_cycle Cycle id
     * @param int $StartYear
     *
     * @return array
     */
    public function getPromotion( $id_cycle , $annee_debut)
    {
        global $zdb;

        try {
            $select = $zdb->sql->select();
            $table_adh = PREFIX_DB . Adherent::TABLE;
            /*
            $select->from($this->getTableName(),array('specialite'))
					->from($table_adh,array(Adherent::PK, 'nom_adh', 'prenom_adh'))
					//->distinct()
					->where($this->getTableName() . '.annee_debut = ?', $annee_debut)
					->where($this->getTableName() . '.id_cycle = ?', $id_cycle)
					->where($this->getTableName() . '.id_adh = '. $table_adh . '.' . Adherent::PK);
			*/
			$select->from(
					array('a' => $table_adh)
				);
			$select->columns(array(Adherent::PK, 'nom_adh', 'prenom_adh'));
							
			$select->join(array('f' => $this->getTableName()),
				'f.id_adh = a.' . Adherent::PK,
				array('specialite'));
				

			$select->where->equalTo('f.annee_debut', $annee_debut)
					->where->equalTo('f.id_cycle', $id_cycle);
            
            $res = $zdb->execute($select);
            $res = $res->toArray();
            
            if ( count($res) > 0 ) {
                return $res;
            } else {
                return array();
            }
        } catch (\Exception $e) {
            Analog::log(
                'Unable to retrieve members promotion for "' .
                $id_cycle  . '" | "' . $annee_debut .'" | ' . $e->getMessage(),
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
    protected function getTableName()
    {
        return PREFIX_DB . AAE_PREFIX  . self::TABLE;
    }
}
?>
