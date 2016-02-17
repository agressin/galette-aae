<?php

 ini_set('display_errors', 1);
define('GALETTE_BASE_PATH', '../../');
require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';

//Constants and classes from plugin
require_once '_config.inc.php';

require_once 'lib/GaletteAAE/Annuaire.php';
use Galette\AAE\Annuaire as Annuaire;

require_once 'lib/GaletteAAE/Cycles.php';
use Galette\AAE\Cycles as Cycles;

require_once 'lib/GaletteAAE/Postes.php';
use Galette\AAE\Postes as Postes;

require_once 'lib/GaletteAAE/Preferences.php';
use Galette\AAE\Preferences as AAE_Preferences;

use Galette\Entity\Adherent as Adherent;
use Galette\Entity\FieldsConfig;


if ( !$login->isLogged() ) {
    header('location:'. GALETTE_BASE_PATH .'index.php');
    die();
}

$AAE_Pref = new AAE_Preferences();
$tpl->assign('AAE_Pref', $AAE_Pref);

$id_adh = get_numeric_form_value('id_adh', '');
if(($id_adh == '') || ( !$login->isUp2Date()) ){
	$id_adh = $login->id;
}

$member = new Adherent();
$member->load($id_adh);

$annuaire = new Annuaire();
$form = $annuaire->getInfoById($id_adh);
$fc = new FieldsConfig(Adherent::TABLE, $member->fields);
$visibles = $fc->getVisibilities();

//----------------POSTES---------------------

$postes = new Postes();
$list_postes = $postes->getPostes(array('id_adh' => $id_adh, 'get_domaines' => true));
$nb_postes = count($list_postes);

//----------------POSTES Fin---------------------

$tpl->assign('form',$form);
$tpl->assign('visibles', $visibles);
$tpl->assign('member', $member);
$nom = $member->sfullname;

$tpl->assign('page_title', _T("Profil of ").$nom);

//Set the path to the current plugin's templates,
//but backup main Galette's template path before

$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;

$tpl->assign('postes', $list_postes);
$tpl->assign('nb_postes', $nb_postes);

$tpl->assign('require_map', true);

$content = $tpl->fetch('voir_adherent_public.tpl');

$tpl->assign('content', $content);

//Set path back to main Galette's template
$tpl->template_dir = $orig_template_path;
$tpl->display('public_page.tpl');
