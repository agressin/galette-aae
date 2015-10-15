<?php

namespace Galette\AAE;

use Analog\Analog as Analog;

require_once 'lib/GaletteAAE/Postes.php';
use Galette\AAE\Postes as Postes;

require_once 'lib/GaletteAAE/Offres.php';
use Galette\AAE\Offres as Offres;

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
    public function getAllEntreprises($onlyUsedByPoste = false, $onlyUsedByOffre = false)
    {
        global $zdb;

        try {

          $select = $zdb->sql->select();
          $select->from(array('e' => self::getTableName()));

    			if($onlyUsedByPoste){
    				$select->join(array('p' => Postes::getTableName()),
    					'p.id_entreprise = e.id_entreprise',
    					array());

    				$select->group('e.id_entreprise');
    			}
          if($onlyUsedByOffre){
    				$select->join(array('o' => Offres::getTableName()),
    					'o.id_entreprise = e.id_entreprise',
    					array());

    				$select->group('e.id_entreprise');
    			}

          $select->where(true);
          $select->order('employeur');
          $res = $zdb->execute($select);

          $res = $res->toArray();

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
     * Retrieve entreprise information by name
     *
     * @param string $name Entreprise naùe
     *
     * @return array
     */
    public function getEntrepriseByName($name)
    {
        global $zdb;

        try {
            $select = $zdb->select(AAE_PREFIX . self::TABLE);
            $select->where->equalTo('employeur',$name);
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
            $data = array(
                        'employeur' => $employeur,
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
            return true;
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
