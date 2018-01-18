<?php

namespace Galette\AAE;

use Analog\Analog as Analog;
use Galette\Entity\Adherent as Adherent;
//use Galette\Repository\Members as Members;


require_once 'lib/GaletteAAE/Cycles.php';
use Galette\AAE\Cycles as Cycles;
require_once 'lib/GaletteAAE/Formations.php';
use Galette\AAE\Formations as Formations;

/**
 * Members Visage
 *
 * @category  Plugins
 */

/*if ( ini_set( 'display_errors', '1' ) === false ) {
    echo 'Unable to set display_errors.';
}*/

class Visage
{

   /**
    * Get visage data of one adherent
    * @param Integer $id_cible The $id_adh
    * @param Boolean $remonter if need to go back
    * @return Array with all data
    */
   public function getDataByAdherent($id_cible, $remonter)
    {
        $data = [
            'success' => false,
            'error' => false,
            'cible' => false,
            'eleves' => false
        ];

        $cible = new Adherent();

        if ($cible->load($id_cible)) {

            $data['cible'] = Visage::getAttributs($id_cible);

            $eleves = [];

            // On descend :
            $fillotsIDs = VisageRelation::getFillotsIds($id_cible);
            while (count($fillotsIDs) > 0) {
                $newFillotsIDs = [];
                foreach ($fillotsIDs as $fillotID) {
                    $newFillotsIDs = array_merge($newFillotsIDs, VisageRelation::getFillotsIds($fillotID));
                    $eleves[$fillotID] = Visage::getAttributs($fillotID);
                }
                $fillotsIDs = $newFillotsIDs;
            }

            // On remonte ?
            if ($remonter === true) {

                $parrainsIDs = VisageRelation::getParrainsIds($id_cible);
                while (count($parrainsIDs) > 0) {
                    $newParrainsIDs = [];
                    foreach ($parrainsIDs as $parrainID) {
                        $newParrainsIDs = array_merge($newParrainsIDs, VisageRelation::getParrainsIds($parrainID));
                        $eleves[$parrainID] = Visage::getAttributs($parrainID);
                    }
                    $parrainsIDs = $newParrainsIDs;
                }
            }

            $eleves[$id_cible] = $data['cible'];

            $data['success'] = true;
            $data['error'] = true;
            $data['eleves'] = $eleves;
        }

        return $data;
    }


    /**
     * Get parrains of specified adherent
     * @param $id_adh
     * @return parrains Adherent
     * @deprecated a priori pas utile au final
     */
    public function getParrains($id_adh)
    {
        global $zdb;

        try {

            $parrainsIDs = VisageRelation::getParrainsIds($id_adh);
            foreach ($parrainsIDs as $parrainID) {
                $parrain = new Adherent();
                $parrain->load($parrainID);
                $parrains[] = $parrain;
            }
            return $parrains;

        } catch (\Exception $e) {
            Analog::log(
                'Unable to retrieve parrains for id_adh="' . $id_adh  . '" | "" | ' . $e->getMessage(),
                Analog::WARNING
            );
            return [];
        }
    }


    /**
     * Get fillots of specified adherent
     * @param $id_adh
     * @return fillots Adherent
     * @deprecated a priori pas utile au final
     */
    public function getFillots($id_adh)
    {
        global $zdb;

        try {

            $parrainsIDs = VisageRelation::getFillotsIds($id_adh);
            foreach ($parrainsIDs as $parrainID) {
                $parrain = new Adherent();
                $parrain->load($parrainID);
                $parrains[] = $parrain;
            }
            return $parrains;

        } catch (\Exception $e) {
            Analog::log(
                'Unable to retrieve fillots for id_adh="' . $id_adh  . '" | "" | ' . $e->getMessage(),
                Analog::WARNING
            );
            return [];
        }
    }

    /**
     * Get attributs in key_value of specified adherent
     * @param  Adherent   $adherent
     * @return Array                 Adherent data
     */
   public static function getAttributs($id_adh)
    {
        global $zdb;

        try {
            $select = $zdb->sql->select();
            $table_adh = PREFIX_DB . Adherent::TABLE;
            $select->from(
                array('a' => $table_adh)
            );

            $select->columns(array(Adherent::PK, 'id_adh', 'nom_adh', 'prenom_adh'));

            $select->join(array('f' => Formations::getTableName()), 'f.id_adh = a.' . Adherent::PK, array('id_cycle', 'annee_debut'));

            $select->join(array('c' => Cycles::getTableName()), 'f.id_cycle = c.' . Cycles::PK, array('nom'));

            $select->where->equalTo('a.id_adh', $id_adh);
            $select->where->equalTo('c.nom', 'IT'); // TODO quand y'aura les géomètres...
            $select->where->notEqualTo('f.annee_debut', 0);

            $select->order('f.annee_debut');
            $select->limit(1);

            $res = $zdb->execute($select);
            $res = $res->toArray();

            if ( count($res) == 1 ) {
                $d = $res[0];
                return [
                    'ide' => intval($d['id_adh']),
                    'nom' => $d['nom_adh'],
                    'prenom' => $d['prenom_adh'],
                    'annee' => $d['annee_debut'],
                    'src' => sprintf('../../picture.php?id_adh=%d&rand=', $d['id_adh']),
                    'parrains' => VisageRelation::getParrainsIds($d['id_adh']),
                    'fillots' => VisageRelation::getFillotsIds($d['id_adh'])
                ];
            } else {
                return false;
            }
        } catch (\Exception $e) {
            print_r('Unable to retrieve attributs : "' . $id_adh .'" | ' . $e->getMessage());
            Analog::log(
                'Unable to retrieve attributs : "' . $id_adh .'" | ' . $e->getMessage(),
                Analog::ERROR
            );
            return false;
        }
    }

    /**
     * Get adherent who have nom / prenom that contain $str
     * @param  Adherent   $adherent
     * @return Array                 Adherent data
     */
    public static function like($str)
    {
        global $zdb;

        try {
            $select = $zdb->sql->select();
            $table_adh = PREFIX_DB . Adherent::TABLE;
            $select->from(
                array('a' => $table_adh)
            );

            $select->columns(array(Adherent::PK, 'id_adh', 'nom_adh', 'prenom_adh'));

            $select->join(array('f' => Formations::getTableName()), 'f.id_adh = a.' . Adherent::PK, array('id_cycle', 'annee_debut'));

            $select->join(array('c' => Cycles::getTableName()), 'f.id_cycle = c.' . Cycles::PK, array('nom'));

            $select->where("(UPPER(a.nom_adh) LIKE UPPER('%" . $str . "%') OR UPPER(a.prenom_adh) LIKE UPPER('%" . $str . "%'))");
            $select->where->equalTo('c.nom', 'IT'); // TODO quand y'aura les géomètres...
            $select->where->notEqualTo('f.annee_debut', 0);

            $select->order('a.prenom_adh, a.nom_adh');

            $res = $zdb->execute($select);
            $res = $res->toArray();

            $results = [];

            if ( count($res) > 0 ) {
                foreach ($res as $d) {
                    $results[] = [
                        'ide' => intval($d['id_adh']),
                        'nom' => $d['nom_adh'],
                        'prenom' => $d['prenom_adh'],
                        'annee' => $d['annee_debut']
                    ];
                }
            }

            return $results;
        } catch (\Exception $e) {
            print_r('Unable to retrieve attributs : "' . $id_adh .'" | ' . $e->getMessage());
            Analog::log(
                'Unable to retrieve attributs : "' . $id_adh .'" | ' . $e->getMessage(),
                Analog::ERROR
            );
        }

        return [];
    }

}


?>
