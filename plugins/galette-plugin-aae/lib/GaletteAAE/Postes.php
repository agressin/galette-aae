<?php

namespace Galette\AAE;

use Analog\Analog as Analog;
use Galette\Entity\Adherent as Adherent;
use Galette\Repository\Members as Members;

require_once 'lib/GaletteAAE/Domaines.php';
use Galette\AAE\Domaines as Domaines;

require_once 'lib/GaletteAAE/Entreprises.php';
use Galette\AAE\Entreprises as Entreprises;

/**
 * Members postes
 *
 * @category  Plugins
 */

class postes
{
    const TABLE =  'postes';
    const TABLE_LIEN = 'liens_poste_domaine';
    const PK = 'id_poste';


     /**
     * Retrieve postes information
     *
     * @param array
     * array(
     * 			'id_poste' => ?,
     * 			'id_adh' => ?,
     * 			'domaines'  => ?,
     * 			'entreprise'  => ?,
     * 			'type'  => ?,
     * 			'get_domaines' => true/false,
     *  		'get_info_adh'  => true/false,
     * 		)
     *
     * @return array
     */
    public function getPostes($req)
      {
        global $zdb;
        try {
          $select = $zdb->sql->select();
          $select->from(
              array('p' => $this->getTableName())
            );

          $entreprises = new Entreprises();
          $select->join(array('e' => $entreprises->getTableName()),
            'p.id_entreprise = e.id_entreprise',
            array('employeur','website'));

          if(array_key_exists('get_info_adh',$req) && $req['get_info_adh'] ){

            $select->join(array('a' => PREFIX_DB . Adherent::TABLE),
              'p.id_adh = a.id_adh',
              array('nom_adh','prenom_adh'));
          }

          $init=false;

          if(array_key_exists('id_poste',$req)){
            $select->where->equalTo('id_poste',  $req['id_poste']);
            $init=true;
          }
          if(array_key_exists('id_adh',$req)){
            $select->where->equalTo('p.id_adh',  $req['id_adh']);
            $init=true;
          }

          if (array_key_exists('domaines',$req)){
            $select->join(array('d' => $this->getTableLienName()),
              'p.id_poste = d.id_poste',
              array('id_domaine'));
            $select->where(array('id_domaine' => $req['domaines']));
            $select->group('id_poste');
            $init=true;
          };

          if (array_key_exists('entreprise',$req) && ($req['entreprise'] != '')){
            $select->where->equalTo('e.id_entreprise',  $req['entreprise']);
            $init=true;
          };
          if (array_key_exists('type',$req) && ($req['type'] != '')){
            $select->where(array('type' => $req['type']));
            $init=true;
          };

          if (!$init){
            $select->where(true);
          }
          $select->order('annee_ini');
          $res = $zdb->execute($select);
          $res = $res->toArray();

          if(array_key_exists('get_domaines',$req) && $req['get_domaines'] ){
            foreach ($res as &$key){
                    $key['domaines'] = $this->getDomainesFromPosteToString($key['id_poste']);
                }
          }

          if ( count($res) > 0 ) {
              return $res;
          } else {
              return array();
          }
        } catch (\Exception $e) {
            Analog::log(
                'Unable to retrieve poste | ' . $e->getMessage(),
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
    public function getPoste($id)
    {
        global $zdb;

        try {
            $select = $zdb->select(AAE_PREFIX . self::TABLE);
            $select->where->equalTo('id_poste',$id);
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
     * SetPoste
     * @param int $id_poste
     * @param int $id_adh
     * @param int $id_entreprise
     * @param text $type
     * @param text $activites
	   * @param array[int] $domaines
     * @param text $adresse
     * @param int $annee_ini
     * @param int $annee_fin
     *
     */
    public function setPoste($id_poste,$id_adh,$id_entreprise,$type,$titre,$activites,$array_domaines,$adresse,$annee_ini,$annee_fin)
    {
        global $zdb;

        try {

        	$domaines = new Domaines();
          $res  = false;
          $data = array(
          			'id_adh' 		=> $id_adh,
          			'id_entreprise' => $id_entreprise,
                'type' 			=> $type,
                'titre' 		=> $titre,
                'activites'  	=> nl2br($activites),
                'adresse' 		=> $adresse,
                'annee_ini'   	=> $annee_ini,
                'annee_fin' 	=> $annee_fin
          );

          if ( $id_poste == '' ) {
            //Poste does not exists yet
            $insert = $zdb->insert(AAE_PREFIX . self::TABLE);
            $insert->values($data);
            $add = $zdb->execute($insert);

            if ( $add->count() == 0) {
              Analog::log('An error occured inserting new poste!' );
            } else {
			        $id_poste = $add->getGeneratedValue();

		        }

          } else {
            //Poste already exists, just update
            $update = $zdb->update(AAE_PREFIX . self::TABLE);
            $update->set($data)->where->equalTo(self::PK,$id_poste);
            $edit = $zdb->execute($update);
            $this->removeAllDomainesOfPoste($id_poste);
            //edit == 0 does not mean there were an error, but that there
            //were nothing to change
          }

          foreach( $array_domaines as $id_domaine){
          	$this->addDomaineToPoste($id_domaine,$id_poste);
          }
          return $id_poste;
        } catch ( \Exception $e ) {
            Analog::log(
                'Unable to set poste "' .
                $id_poste . '" | ' . $e->getMessage(),
                Analog::ERROR
            );
            return false;
        }
    }

    /**
     * removeposte
     * @param int $id_poste
     */
    public function removePoste($id_poste)
    {
        global $zdb;

        try {
		      $this->removeAllDomainesOfPoste($id_poste);
          $delete = $zdb->delete(AAE_PREFIX . self::TABLE);
          $delete->where->equalTo(self::PK, $id_poste);
          $zdb->execute($delete);
          return true;
        } catch ( \Exception $e ) {
          Analog::log(
              'Unable to delete poste ' .
              $id_poste . ' | ' . $e->getMessage(),
              Analog::ERROR
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
          $domaines = new Domaines();

        	$select = $zdb->sql->select();
        	$select->from(array('l' => $this->getTableLienName()));

    			$select->join(array('d' => $domaines->getTableName()),
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
                $id_poste  . '" | ' . $e->getMessage(),
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
        $domaines = new Domaines();
        $dom = $this->getDomainesFromPoste($id_poste);
        $temp= '';
        $all_dom = $domaines->getAllDomaines();
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
                'Unable to delete domaines of poste "' .
                $id_poste . '" | ' . $e->getMessage(),
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
                'Unable to add domaine to poste "' .
                $id_poste . '" | ' . $e->getMessage(),
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
        return PREFIX_DB . AAE_PREFIX . self::TABLE;
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
