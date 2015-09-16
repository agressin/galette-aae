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
     * Retrieve all domaines of one job
     *
     * @param int $id_poste domaine id
     *
     * @return array
     */
    public function getDomainesFromPoste($id_poste)
    {
        global $zdb;

        try {
        
        	$select = $zdb->sql->select();
        	$select->from(array('l' => $this->getTableLienName()));
							
			$select->join(array('d' => $this->getTableName()),
				'd.id_domaine = l.id_domaine');
				
			$select->where->equalTo('l.id_poste', $id_poste);
            
            $res = $zdb->execute($select);
            $res = $res->toArray();
            
            $out = array();
            foreach( $res as $k){
            	$out[] = $k['id_domaine'];
            }
			return $out;
        } catch (\Exception $e) {
            Analog::log(
                'Unable to retrieve domaine from poste "' .
                $id_poste  . '". | ' . $e->getMessage(),
                Analog::WARNING
            );
            return false;
        }
    }
    
     /**
     * Retrieve all domaines of one job
     *
     * @param int $id_poste domaine id
     *
     * @return array
     */
    public function getDomainesFromPosteToString($id_poste)
    {
        $dom = $this->getDomainesFromPoste($id_poste);
        $temp= "";
        $all_dom = $this->getAllDomaines();
        foreach( $dom as $d){
        	$temp .= $all_dom[$d] . ', ';
        }
        return rtrim($temp,', ');
    }

    /**
     * remove All Domaines Of Poste
     * @param int $id_poste
     */
    public function removeAllDomainesOfPoste($id_poste)
    {
        global $zdb;

        try {

            $delete = $zdb->delete(AAE_PREFIX . self::TABLE_LIEN);
            $delete->where->equalTo('id_poste', $id_poste);
            $zdb->execute($delete);
            return true;
        } catch ( \Exception $e ) {
            Analog::log(
                'Unable to delete domaines of poste ' .
                $id_poste . ' | ' . $e->getMessage(),
                Analog::ERROR
            );
            return false;
        }
    }

/**
     * SetFormation
     * @param int $id_domaine
     * @param int $id_poste
     */
    public function addDomaineToPoste($id_domaine,$id_poste)
    {
		global $zdb;

        try {
            $res  = null;
            $data = array(
                        'id_domaine' => $id_domaine,
                        'id_poste'   => $id_poste
                    );

            $insert = $zdb->insert(AAE_PREFIX . self::TABLE_LIEN);
            $insert->values($data);
            $add = $zdb->execute($insert);
            
            if ( $add->count() == 0) {
                Analog::log('An error occured when adding Domaine To Poste!' );
            }

            return ($res > 0);
        } catch ( \Exception $e ) {
            Analog::log(
                'Unable to add domaine to poste ' .
                $id_poste . ' | ' . $e->getMessage(),
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
    
    /**
     * Get table link's name
     *
     * @return string
     */
	static public function getTableLienName()
    {
        return  PREFIX_DB . AAE_PREFIX  .  self::TABLE_LIEN;
    }
}
?>

