<?php

use Analog\Analog as Analog;
use Galette\Entity\Adherent as Adherent;
use Galette\Repository\Members as Members;

require_once 'lib/GaletteAAE/Cycles.php';
use Galette\AAE\Cycles as Cycles;

require_once 'lib/GaletteAAE/Formations.php';
use Galette\AAE\Formations as Formations;

/*GetParrains
IN : id_fillot
OUT : id_parrain
COM : récupère les id des parrains pour un id fillot précis*/

function getParrains($id_fillot)
{
	global $zdb;

	try {
		//Requête SQL : on va chercher les parrains pour un id donné
		$select = $zdb->select(AAE_PREFIX . 'familles');
        $select->where->equalTo('id_fillot',$id_fillot);
        $res = $zdb->execute($select);
        //On convertit le résultat en tableau
        $res = $res->toArray();
		$idparrain=array();
		
		//Remplissage du tableau
		foreach ($res as $cle => $valeur) 
		{
			$idparrain[]=$valeur['id_parrain'];
		}
		//Si on a au moins 1 parrain, on renvoie son id
		if ( count($idparrain) > 0 ) {
			return $idparrain;
		} else {
			return array();
		}
		
	} catch (\Exception $e) {
		Analog::log(
			'Unable to retrieve parrain for "' .
			$id_fillot . '" | "" | ' . $e->getMessage(),
			Analog::WARNING
		);
		//Comme ça on montre bien que la requête a planté
		echo('raté');
		return false;
	}

}	



/*GetFillots
IN : id_parrain
OUT : id_fillot
COM : récupère les id des fillots pour un id parrain précis*/

function getFillots($id_parrain)
{
	global $zdb;

	try {
		//Requête SQL : on va chercher les fillots pour un id donné
		$select = $zdb->select(AAE_PREFIX . 'familles');
        $select->where->equalTo('id_parrain',$id_parrain);
        $res = $zdb->execute($select);
        //On convertit le résultat en tableau
        $res = $res->toArray();
		$idfillot=array();
		
		//Remplissage du tableau
		foreach ($res as $cle => $valeur) 
		{
			$idfillot[]=$valeur['id_fillot'];
		}
		//Si on a au moins 1 parrain, on renvoie son id
		if ( count($idfillot) > 0 ) {
			return $idfillot;
		} else {
			return array();
		}
		
	} catch (\Exception $e) {
		Analog::log(
			'Unable to retrieve parrain for "' .
			$id_fillot . '" | "" | ' . $e->getMessage(),
			Analog::WARNING
		);
		//Comme ça on montre bien que la requête a planté
		echo('raté');
		return false;
	}

}	

?>
