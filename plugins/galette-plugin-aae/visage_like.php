<?php

/**
 * Page to get name
 */

/*if ( ini_set( 'display_errors', '1' ) === false ) {
    echo 'Unable to set display_errors.';
}*/

define('GALETTE_BASE_PATH', '../../');
require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';

//Constants and classes from plugin
require_once '_config.inc.php';

require_once 'lib/GaletteAAE/Visage.php';

use Galette\AAE\Visage as Visage;

if ( !$login->isLogged() ) {
    header('location:'. GALETTE_BASE_PATH .'index.php');
    die();
}
if ( ini_set( 'display_errors', '1' ) === false ) {
    echo 'Unable to set display_errors.';
}
// Recup str
$str = '';
if (isset($_GET["str"])) {
	$str = substr($_GET["str"], 0, 12); // 12
}

$elements = Visage::like($str);

header('Content-Type: application/json');

echo json_encode(['success' => true, 'elements' => $elements]);