<?php

use Galette\Entity\Adherent;
use Galette\Entity\FieldsConfig;
use Galette\Entity\Texts;
use Galette\Repository\Members;
use Galette\Repository\PdfModels;

define('GALETTE_BASE_PATH', '../../');

require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';

//Constants and classes from plugin
require_once '_config.inc.php';

require_once 'lib/GaletteAAE/Preferences.php';
use Galette\AAE\Preferences as AAE_Preferences;

//Set the path to the current plugin's templates,
//but backup main Galette's template path before
$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;
$tpl->compile_id = AAETOOLS_SMARTY_PREFIX;
//set util paths
$plugin_dir = basename(dirname($_SERVER['SCRIPT_NAME']));
$tpl->assign(
    'galette_url',
    'http://' . $_SERVER['HTTP_HOST'] .
    preg_replace(
        "/\/plugins\/" . $plugin_dir . '/',
        "/",
        dirname($_SERVER['SCRIPT_NAME'])
    )
);
$tpl->assign(
    'plugin_url',
    'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . '/'
);

$tpl->assign('page_title', _T("Contribution"));

$AAE_Pref = new AAE_Preferences();
$tpl->assign('AAE_pref', $AAE_Pref);

$content = $tpl->fetch('aaecotiz.tpl', AAETOOLS_SMARTY_PREFIX);
$tpl->assign('content', $content);

//Set path back to main Galette's template
$tpl->template_dir = $orig_template_path;

//restricted to user only
if ( $login->isLogged() ) {
    $tpl->display('page.tpl', AAETOOLS_SMARTY_PREFIX);
} else {
		$tpl->display('public_page.tpl');
}

?>
