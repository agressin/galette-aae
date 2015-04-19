<?php

namespace Galette\AAE;

use Analog\Analog as Analog;
use Galette\Entity\Adherent as Adherent;
use Galette\Repository\Members as Members;

require_once 'lib/GaletteAAE/Cycles.php';
use Galette\AAE\Cycles as Cycles;

/**
 * Members postes
 *
 * @category  Plugins
 */

class postes
{
    const TABLE =  'postes';
    const PK = 'id_poste';
    const CA = 'id_adh';

    /**
     * Retrieve member poste
     *
     * @param int $id_adh Member id
     *
     * @return array
     */
    public function getPostes($id_adh)
    {
        global $zdb;

        try {
            $select = $zdb->select(AAE_PREFIX . self::TABLE);
            $select->where->equalTo('id_adh', $id_adh);
            
            $res = $zdb->execute($select);
            $res = $res->toArray();
            if ( count($res) > 0 ) {
                return $res;
            } else {
                return array();
            }

        } catch (\Exception $e) {
            Analog::log(
                'Unable to retrieve members postes for "' .
                $id_adh  . '". | ' . $e->getMessage(),
                Analog::WARNING
            );
            return false;
        }
    }

    /**
     * Retrieve all poste by id_entreprise
     *
     * @param int id_entreprise Entreprise id
     *
     * @return array
     */
    public function getPostesByEnt($id_entreprise)
    {
        global $zdb;

        try {
            $select = $zdb->select(AAE_PREFIX . self::TABLE);
            $select->where->equalTo('id_entreprise', $id_entreprise);
            
            $res = $zdb->execute($select);
            $res = $res->toArray();
            if ( count($res) > 0 ) {
                return $res;
            } else {
                return array();
            }

        } catch (\Exception $e) {
            Analog::log(
                'Unable to retrieve members postes for "' .
                $id_adh  . '". | ' . $e->getMessage(),
                Analog::WARNING
            );
            return false;
        }
    }
    
    /**
     * Retrieve one poste
     *
     * @param int $id_form Member id
     *
     * @return array
     */
    public function getPoste($id_form)
    {
        global $zdb;

        try {
            $select = $zdb->select($this->getTableName());
            $select->where->equalTo(postes::PK, $id_form);

            $res = $zdb->execute($select);
            $res = $res->toArray();
            
            if ( count($res) > 0 ) {
                return $res[0];
            } else {
                return array();
            }
        } catch (\Exception $e) {
            Analog::log(
                'Unable to retrieve poste with id : "' .
                $id_form  . '". | ' . $e->getMessage(),
                Analog::WARNING
            );
            return false;
        }
    }

    /**
     * SetPoste
     * @param int $id_form
     * @param text $activite_principale
     * @param boolean $encadrement
     * @param int $nb_personne_encadre
     * @param int $id_entreprise
     * @param varchar $adresse
     * @param int $code_postal
     * @param varchar $ville
     * @param int $annee_ini
     * @param int $annee_fin    
     * @param int $id_adh
     */
    public function setPoste($id_form,$id_adh,$activite_principale,$encadrement,$nb_personne_encadre,$id_entreprise,$adresse,$code_postal,$ville,$annee_ini,$annee_fin)
    {
        global $zdb;

        try {
            $res  = null;
            $data = array(
                        'activite_principale'   => $activite_principale,
                        'encadrement' => $encadrement,
                        'nb_personne_encadre' => $nb_personne_encadre,
                        'id_entreprise'  => $id_entreprise,
                        'adresse' => $adresse,
                        'code_postal' => $code_postal,
                        'ville' => $ville,
                        'annee_ini'   => $annee_ini,
                        'annee_fin' => $annee_fin,
                        'id_adh' => $id_adh
                    );

            if ( $id_form == '' ) {
                //Poste does not exists yet
                $insert = $zdb->insert(AAE_PREFIX . self::TABLE);
                $insert->values($data);
                $add = $zdb->execute($insert);
                
                if ( $add->count() == 0) {
                    Analog::log('An error occured inserting new poste!' );
                }
                
            } else {
                //Poste already exists, just update               
                $update = $zdb->update(AAE_PREFIX . self::TABLE);
                $update->set($data)->where->equalTo(self::PK,$id_form);
                $edit = $zdb->execute($update);
                //edit == 0 does not mean there were an error, but that there
                //were nothing to change
            }
            return ($res > 0);
        } catch ( \Exception $e ) {
            Analog::log(
                'Unable to set poste ' .
                $id_form . ' | ' . $e->getMessage(),
                Analog::ERROR
            );
            return false;
        }
    }

    /**
     * removeposte
     * @param int $id_form
     */
    public function removePoste($id_form)
    {
        global $zdb;

        try {

            $delete = $zdb->delete(AAE_PREFIX . self::TABLE);
            $delete->where->equalTo(self::PK, $id_form);
            $zdb->execute($delete);
            return true;
        } catch ( \Exception $e ) {
            Analog::log(
                'Unable to delete poste ' .
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
    protected function getTableName()
    {
        return PREFIX_DB . AAE_PREFIX . self::TABLE;
    }
}
?>
