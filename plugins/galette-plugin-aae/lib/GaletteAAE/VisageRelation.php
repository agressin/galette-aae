<?php

namespace Galette\AAE;

use Analog\Analog as Analog;

class VisageRelation
{
    const TABLE = 'aae_visage_relation';
    const PK = 'id_relation';


    /**
     * Get parrains id of specified adherent
     * @param   $id_adh
     * @return  parrains ids
     */
    static public function getParrainsIds($id_adh)
    {
        global $zdb;

        try {

            $select = $zdb->sql->select();
            $table_relation = PREFIX_DB . VisageRelation::TABLE;
            $select->from(
                 array('r' => $table_relation)
            );
            $select->columns(array('parrain'));
            $select->where->equalTo('r.fillot', $id_adh);
            //$select->order('parrain');

            $res = $zdb->execute($select);

            $parrainsIds = [];
            foreach ($res->toArray() as $parrain) {
                $parrainsIds[] = $parrain['parrain'];
            };
            return $parrainsIds;

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

        try {

            $select = $zdb->sql->select();
            $table_relation = PREFIX_DB . VisageRelation::TABLE;
            $select->from(
                 array('r' => $table_relation)
            );
            $select->columns(array('fillot'));
            $select->where->equalTo('r.parrain', $id_adh);
            //$select->order('fillot');

            $res = $zdb->execute($select);

            $fillotsIds = [];
            foreach ($res->toArray() as $fillot) {
                $fillotsIds[] = $fillot['fillot'];
            };
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
