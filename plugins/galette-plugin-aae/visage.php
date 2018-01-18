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

if ( !$login->isLogged() ) {
    header('location:'. GALETTE_BASE_PATH .'index.php');
    die();
}

//$AAE_Pref = new AAE_Preferences();
//$tpl->assign('AAE_Pref', $AAE_Pref);
//
$tpl->template_dir = 'templates/' . $preferences->pref_theme;
$tpl->display('visage.tpl');

?>