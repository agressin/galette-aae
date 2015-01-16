<?php

define('GALETTE_BASE_PATH', '../../');

require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';

//Constants and classes from plugin
require_once '_config.inc.php';

require_once 'lib/GaletteAAE/Formations.php';
use Galette\AAE\Formations as Formations;

require_once 'lib/GaletteAAE/Cycles.php';
use Galette\AAE\Cycles as Cycles;


if ( !$login->isLogged() ) {
    header('location:'. GALETTE_BASE_PATH .'index.php');
    die();
}

$tpl->assign('page_title', _T("Formations managment"));


$formation = new Formations();
$cycles = new Cycles();

//Recupération des cycles :
$allCycles = $cycles->getAllCycles();
foreach ($allCycles as $cycle) {
    $pk = Cycles::PK;
    $name = $cycle["nom"];
    $cycles_options[$cycle[$pk]] = $name;
}
$tpl->assign('cycles', $cycles_options);

$member = new Galette\Entity\Adherent();
//Liste les formations 
if ( ($login->isAdmin() || $login->isStaff()) && isset($_GET['id_adh']) && $_GET['id_adh'] != '' ) {
    $list_formations = $formation->getFormations($_GET['id_adh']);
    $tpl->assign('haveRights', true);
    $tpl->assign('mid', $_GET['id_adh']);

    $member->load($_GET['id_adh']);
    
}else{
    $list_formations = $formation->getFormations($login->id);
    $tpl->assign('haveRights', false);
    $tpl->assign('mid', $login->id);
    $member->load($login->id);
}
$tpl->assign('member', $member);

//Tri le tableau en fonction de la date de début.
usort($list_formations, function($a, $b) {
    return $a['annee_debut'] - $b['annee_debut'];
});


$tpl->assign('list_formations', $list_formations);

if (isset($error_detected)) {
    $tpl->assign('error_detected', $error_detected);
}
if (isset($warning_detected)) {
    $tpl->assign('warning_detected', $warning_detected);
}


//Set the path to the current plugin's templates,
//but backup main Galette's template path before
$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;

$content = $tpl->fetch('gestion_formations.tpl');
$tpl->assign('content', $content);

//Set path back to main Galette's template
$tpl->template_dir = $orig_template_path;
$tpl->display('page.tpl');



?>
