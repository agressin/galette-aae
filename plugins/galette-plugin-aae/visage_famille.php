<?php

/**
 * Page to get family data of one adherent
 */

// En dev
/*if ( ini_set( 'display_errors', '1' ) === false ) {
    echo 'Unable to set display_errors.';
}*/

define('GALETTE_BASE_PATH', '../../');
require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';

//Constants and classes from plugin
require_once '_config.inc.php';

require_once 'lib/GaletteAAE/Visage.php';
require_once 'lib/GaletteAAE/VisageRelation.php';

use Galette\Entity\Adherent as Adherent;
use Galette\AAE\Visage as Visage;
use Galette\AAE\VisageRelation as VisageRelation;


if ( !$login->isLogged() ) {
    header('location:'. GALETTE_BASE_PATH .'index.php');
    die();
}

//$AAE_Pref = new AAE_Preferences();
//$tpl->assign('AAE_Pref', $AAE_Pref);


// Recup id_adh
$id_adh = false;
if (isset($_GET["id_adh"])) {
	$raw = $_GET["id_adh"];
	if (ctype_digit($raw)) {
		$id_adh = intval($raw);
	}
}

// Validation $id_adh
if (($id_adh == false) || ( !$login->isUp2Date()) ){
	$id_adh = $login->id;
}

// Recup remonter
$remonter = false;
if (isset($_GET["remonter"]) && $_GET["remonter"] == 'true') {
	$remonter = true;
}

$data = Visage::getDataByAdherent($id_adh, $remonter);

header('Content-Type: application/json');

echo json_encode($data);