<?php
/***************************************************************************
 *					arbre.php
 ***************************************************************************/

define('GALETTE_BASE_PATH', '../../');
require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';

//Constants and classes from plugin
require_once '_config.inc.php';

require_once 'lib/GaletteAAE/Formations.php';
use Galette\AAE\Formations as Formations;

require_once 'lib/GaletteAAE/Cycles.php';
use Galette\AAE\Cycles as Cycles;

if ( !$preferences->showPublicPages($login) ) { //$login->isLogged())
    //public pages are not actives
    header('location:' . GALETTE_BASE_PATH  . 'index.php');
    die();
}

$formation = new Formations();
$cycles = new Cycles();
