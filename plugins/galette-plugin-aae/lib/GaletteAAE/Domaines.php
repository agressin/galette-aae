<?php

namespace Galette\AAE;

use Analog\Analog as Analog;

require_once 'lib/GaletteAAE/Postes.php';
use Galette\AAE\Postes as Postes;

class Domaines
{
    const TABLE = 'domaines';
    const TABLE_LIEN = 'liens_poste_domaine';
    const PK = 'id_domaine';

	/* TODO
	* addDomaine(nom)
	* removeDomaine(id)
	* addDomaineToPoste(id_poste,id_domaine)
	*/

    /**
     * Retrieve all domaines
     *
     * @param
     *
     * @return array
     */
    public function getAllDomaines()
    {
        global $zdb;

        try {

            $res = $zdb->selectAll(AAE_PREFIX . self::TABLE);

            $res = $res->toArray();
            $out = array();
			foreach($res as $k => $v){
				$out[$v['id_domaine']] = $v['nom'];
			}

            if ( count($out) > 0 ) {
                return $out;
            } else {
                return array();
            }
        } catch (\Exception $e) {
            Analog::log(
                'Unable to retrieve domaines : "' . $e->getMessage(),
                Analog::WARNING
            );
            return false;
        }
    }

     /**
     * Retrieve domaine information
     *
     * @param int $id domaine id
     *
     * @return array
     */
    public function getDomaine($id)
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
                'Unable to retrieve domaine information for "' .
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
        return  PREFIX_DB . AAE_PREFIX  .  self::TABLE;
    }
}
?>
