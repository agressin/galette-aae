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
$haveRights = ($login->isAdmin() || $login->isStaff());
$tpl->assign('haveRights', $haveRights);


// on add cycle succes (from ajouter_cycle.php)
if ( isset($session['cycle_ok'] )){
	$success_detected[] = _T("Cycle has been successfully added / modified.");
  $tpl->assign('success_detected', $success_detected);
	unset($session['cycle_ok']);
}

//get action
if (isset($_GET['action']))
{
  $action = $_GET['action'];
  if ($action == "rm") {
    if (isset($_GET['id_cycle'])){
      $cycles->removeCycle($_GET['id_cycle']);
    }
  }
}

//Cycle detail
if (isset($_GET['cycle_detail'])){
  $tpl->assign('page_title', _T("Cycle detail:"));

  $id_cycle = $_GET['cycle_detail'];
  $cycle = $cycles->getCycle($id_cycle);
  $stat = $cycles->getCycleStatByYear($id_cycle);

  $tpl->assign('id_cycle', $id_cycle);
  $tpl->assign('cycle', $cycle);
  $tpl->assign('stat', $stat);
  //Set the path to the current plugin's templates,
  //but backup main Galette's template path before
  $orig_template_path = $tpl->template_dir;
  $tpl->template_dir = 'templates/' . $preferences->pref_theme;

  $content = $tpl->fetch('cycle_detail.tpl');
  $tpl->assign('content', $content);

  //Set path back to main Galette's template
  $tpl->template_dir = $orig_template_path;
  $tpl->display('public_page.tpl');

}else{
  //Recupération des cycles :
  $all_cycles = array();
  $tmp = $cycles->getAllCycles(!$haveRights);
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
  $tpl->display('public_page.tpl');
}
