<?php

define('GALETTE_BASE_PATH', '../../');
require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';

//Constants and classes from plugin
require_once '_config.inc.php';

require_once 'lib/GaletteAAE/Cycles.php';
use Galette\AAE\Cycles as Cycles;

$cycles = new Cycles();

if ( !($login->isUp2Date() || $login->isAdmin() || $login->isStaff()) ) {
    //public pages are not actives
    header('location:' . GALETTE_BASE_PATH  . 'index.php');
    die();
}

//restricted some features to Staff only
$tpl->assign('haveRights', ($login->isAdmin() || $login->isStaff()));


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
$all_cycles = array();
$tmp = $cycles->getAllCycles(false);
foreach ($tmp as $key => $value) {
  $all_cycles[$value['id_cycle']] = $value['nom'];
}
#var_dump($all_cycles);
#$all_cycles = $cycles->getAllCycles(false);
$tpl->assign('cycles', $all_cycles);
$cycles_stats = $cycles->getAllCyclesStats();
$tpl->assign('cycles_stats', $cycles_stats);
$cycles_stats_by_year = array();
foreach ($cycles_stats as $key => $value) {
  $cycles_stats_by_year[$key] = $cycles->getCycleStatByYear($key);
}
$tpl->assign('cycles_stats_by_year', $cycles_stats_by_year);


$tpl->assign('page_title', _T("Cycles managment:"));

//Set the path to the current plugin's templates,
//but backup main Galette's template path before
$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;

$content = $tpl->fetch('gestion_cycles.tpl');
$tpl->assign('content', $content);

//Set path back to main Galette's template
$tpl->template_dir = $orig_template_path;

#if ($login->isAdmin() || $login->isStaff())
#	$tpl->display('page.tpl');
#else
	$tpl->display('public_page.tpl');
