<?php

define('GALETTE_BASE_PATH', '../../');
require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';

//Constants and classes from plugin
require_once '_config.inc.php';

require_once 'lib/GaletteAAE/Annuaire.php';
use Galette\AAE\Annuaire as Annuaire;

require_once 'lib/GaletteAAE/Cycles.php';
use Galette\AAE\Cycles as Cycles;

use Galette\Entity\Adherent as Adherent;
use Galette\Entity\FieldsConfig;

if ( !$preferences->showPublicPages($login) ) {
    //public pages are not actives
    header('location:' . GALETTE_BASE_PATH  . 'index.php');
    die();
}

$id_adh = get_numeric_form_value('id_adh', '');

$member = new Adherent();
$member->load($id_adh);

$annuaire = new Annuaire();
$form = $annuaire->getInfoById($id_adh);
$fc = new FieldsConfig(Adherent::TABLE, $member->fields);
$visibles = $fc->getVisibilities();


$tpl->assign('form',$form);
$tpl->assign('visibles', $visibles);
$tpl->assign('member', $member);
$nom = $member->sfullname;

$tpl->assign('page_title', 'Profil de '.$nom);

//Set the path to the current plugin's templates,
//but backup main Galette's template path before

$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;

$content = $tpl->fetch('voir_adherent_public.tpl');
$tpl->assign('content', $content);

//Set path back to main Galette's template
$tpl->template_dir = $orig_template_path;
$tpl->display('public_page.tpl');