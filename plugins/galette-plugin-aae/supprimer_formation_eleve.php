<?php

define('GALETTE_BASE_PATH', '../../');
require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';

//Constants and classes from plugin
require_once '_config.inc.php';

require_once 'lib/GaletteAAE/Formations.php';
use Galette\AAE\Formations as Formations;

if (!$login->isAdmin() && !$login->isStaff()) {
    header('location:'. GALETTE_BASE_PATH .'index.php');
    die();
}


$formations = new Formations();

if (isset($_GET['id_form']) ) {
            $formations->removeFormation($_GET['id_form']);
}

?>