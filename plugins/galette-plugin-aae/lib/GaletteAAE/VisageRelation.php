<?php

namespace Galette\AAE;

use Analog\Analog as Analog;

class VisageRelation
{
    const TABLE = 'visage_relations';
    const PK = 'id_relation';


    /**
     * Get parrains id of specified adherent
     * @param   $id_adh
     * @return  parrains ids
     */
    static public function getParrainsIds($id_adh)
    {
        global $zdb;

        /*$sql = 'SELECT parrain FROM relation WHERE fillot = ' . $id_adh;
        $request = $this->pdo->query($sql);
        $parrainsIds = array();
        while ($parrainId = $request->fetch()) {
            $parrainsIds[] = $parrainId;
        }
        return $parrainsIds;*/

        try {

            $select = $zdb->sql->select();
            $table_relation = PREFIX_DB . VisageRelation::TABLE;
            $select->from(
                 array('r' => $table_relation)
            );
            $select->columns(array('parrain'));
            $select->order('parrain');
            $select->where->equalTo('fillot', $id_adh);

            $res = $zdb->execute($select);
            $parrainsIds = $res->toArray();

            return $parrainsIds;

        } catch (\Exception $e) {
            Analog::log(
                'Unable to retrieve parrains_ids id_adh="' . $id_adh  . '" | "" | ' . $e->getMessage(),
                Analog::WARNING
            );
            return [];
        }
    }


    /**
     * Get fillots id of specified adherent
     * @param   $id_adh
     * @return  fillots ids
     */
    static public function getFillotsIds($id_adh)
    {
        global $zdb;

        /*$sql = 'SELECT fillot FROM relation WHERE parrain = ' . $id_adh;
        $request = $this->pdo->query($sql);
        $fillotsIds = array();
        while ($fillotId = $request->fetch()) {
            $fillotsIds[] = $fillotId;
        }
        return $fillotsIds;*/

        try {

            $select = $zdb->sql->select();
            $table_relation = PREFIX_DB . VisageRelation::TABLE;
            $select->from(
                 array('r' => $table_relation)
            );
            $select->columns(array('fillot'));
            $select->order('fillot');
            $select->where->equalTo('parrain', $id_adh);

            $res = $zdb->execute($select);
            $fillotsIds = $res->toArray();

            return $fillotsIds;

        } catch (\Exception $e) {
            Analog::log(
                'Unable to retrieve fillots_ids id_adh="' . $id_adh  . '" | "" | ' . $e->getMessage(),
                Analog::WARNING
            );
            return [];
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
