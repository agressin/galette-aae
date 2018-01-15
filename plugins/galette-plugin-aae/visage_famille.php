<?php

/**
 * Page to get family data of one adherent
 */

 ini_set('display_errors', 1);
define('GALETTE_BASE_PATH', '../../');
require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';

//Constants and classes from plugin
require_once '_config.inc.php';

require_once 'lib/GaletteAAE/Visage.php';

use Galette\Entity\Adherent as Adherent;
use Galette\Entity\FieldsConfig;


if ( !$login->isLogged() ) {
    header('location:'. GALETTE_BASE_PATH .'index.php');
    die();
}

//$AAE_Pref = new AAE_Preferences();
//$tpl->assign('AAE_Pref', $AAE_Pref);

$id_adh = get_numeric_form_value('id_adh', '');
if(($id_adh == '') || ( !$login->isUp2Date()) ){
	$id_adh = $login->id;
}
$remonter = get_numeric_form_value('id_adh', 1) === 1 ? true : false;

$data = Visage::getDataByAdherent($id_adh, $remonter);

echo json_encode($data);