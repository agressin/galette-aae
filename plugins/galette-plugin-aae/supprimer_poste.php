<?php

define('GALETTE_BASE_PATH', '../../');
require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';

//Constants and classes from plugin
require_once '_config.inc.php';

require_once 'lib/GaletteAAE/Postes.php';
use Galette\AAE\Postes as Postes;

$postes = new Postes();

if (isset($_GET['id_form']) ) {
            $postes->removePoste($_GET['id_form']);
}

?>