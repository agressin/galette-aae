<?php

use Galette\Entity\Adherent;
use Galette\Entity\FieldsConfig;
use Galette\Entity\Texts;
use Galette\Repository\Members;
use Galette\Repository\PdfModels;
use Analog\Analog as Analog;

define('GALETTE_BASE_PATH', '../../');

require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';

//Constants and classes from plugin
require_once '_config.inc.php';

require_once 'lib/GaletteAAE/Preferences.php';
use Galette\AAE\Preferences as AAE_Preferences;

//restricted to Staff only
if ( !$login->isStaff() && !$login->isAdmin()) {
    header('location: ' . GALETTE_BASE_PATH . 'index.php');
    die();
}

//Set the path to the current plugin's templates,
//but backup main Galette's template path before
$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;

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

$AAE_Pref = new AAE_Preferences();
$tpl->assign('AAE_Pref', $AAE_Pref);

if (isset($_POST['valid']) && $_POST['valid'] == '1') {
    //form was send normally, we try to store new values

  $res = false;

  if(isset($_POST['pref_rib'])){
		$res = $AAE_Pref->setPref('rib',$_POST['pref_rib']) || $res;
  }
	if(isset($_POST['pref_webmaster'])){
		$res = $AAE_Pref->setPref('mail_webmaster',$_POST['pref_webmaster']);
  }
	if(isset($_POST['pref_cotis'])){
		$res = $AAE_Pref->setPref('mail_cotis',$_POST['pref_cotis']) || $res;
  }
	if(isset($_POST['pref_job'])){
		$res = $AAE_Pref->setPref('mail_job',$_POST['pref_job']) || $res;
  }
	if(isset($_POST['pref_montant'])){
		$res = $AAE_Pref->setPref('montant_cotis',$_POST['pref_montant']) || $res;
  }

  if(isset($_POST['api_key_ign'])){
		$res = $AAE_Pref->setPref('api_key_ign',$_POST['api_key_ign']) || $res;
  }
  if(isset($_POST['api_key_google'])){
		$res = $AAE_Pref->setPref('api_key_google',$_POST['api_key_google']) || $res;
  }

  if ( !$res ) {
      $error_detected[] = _T("Settings have not been modified!");
  } else {
      $success_detected[] = _T("Settings have been successfully modified.");
  }
}
$tpl->assign('error_detected', $error_detected);
$tpl->assign('success_detected', $success_detected);

$tpl->assign('page_title', _T("AAE Tools"));

$content = $tpl->fetch('aaetools.tpl', AAETOOLS_SMARTY_PREFIX);
//$content="Une page visible que par les gens du bureau";
$tpl->assign('content', $content);

//Set path back to main Galette's template
$tpl->template_dir = $orig_template_path;
$tpl->display('page.tpl', AAETOOLS_SMARTY_PREFIX);


?>
