<?php

define('GALETTE_BASE_PATH', '../../');
require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';

//Constants and classes from plugin
require_once '_config.inc.php';

require_once 'lib/GaletteAAE/Offres.php';
use Galette\AAE\Offres as Offres;

if (!$login->isAdmin() && !$login->isStaff()) {
    header('location:'. GALETTE_BASE_PATH .'index.php');
    die();
}


$offres = new Offres();

if (isset($_GET['id_offre']) ) {
            $offres->removeOffre($_GET['id_offre']);
}

?>
