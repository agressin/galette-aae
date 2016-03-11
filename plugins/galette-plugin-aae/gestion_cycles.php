<?php

define('GALETTE_BASE_PATH', '../../');
require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';

//Constants and classes from plugin
require_once '_config.inc.php';

require_once 'lib/GaletteAAE/Cycles.php';
use Galette\AAE\Cycles as Cycles;

$cycles = new Cycles();

//restricted to Staff only
if ( !$login->isStaff() && !$login->isAdmin()) {
    header('location: ' . GALETTE_BASE_PATH . 'index.php');
    die();
}


if (isset($error_detected)) {
    $tpl->assign('error_detected', $error_detected);
}
if (isset($warning_detected)) {
    $tpl->assign('warning_detected', $warning_detected);
}

if (isset($_GET['action']))
{
    $action = $_GET['action'];
  if($action == "add"){
    if (isset($_GET['nom'])){
      $cycles->setCycle("",$_GET['nom']);
    }

  }elseif ($action == "rm") {
    if (isset($_GET['id_cycle'])){
      $cycles->removeCycle($_GET['id_cycle']);
    }
  }
}

//Recupération des cycles :
$allCycles = $cycles->getAllCycles(false);
$tpl->assign('cycles', $allCycles);

var_dump($cycles->getAllCyclesStats());
#var_dump($cycles->getCycleStatByYear(1));


$tpl->assign('page_title', _T("Cycles managment:"));

//Set the path to the current plugin's templates,
//but backup main Galette's template path before
$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;

$content = $tpl->fetch('gestion_cycles.tpl');
$tpl->assign('content', $content);

//Set path back to main Galette's template
$tpl->template_dir = $orig_template_path;

$tpl->display('page.tpl');
