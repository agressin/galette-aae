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

require_once 'lib/GaletteAAE/Domaines.php';
use Galette\AAE\Domaines as Domaines;

use Galette\Entity\Adherent as Adherent;
use Galette\Entity\FieldsConfig;


if ( !$login->isLogged() ) {
    header('location:'. GALETTE_BASE_PATH .'index.php');
    die();
}

$id_adh = get_numeric_form_value('id_adh', '');
if($id_adh == ''){
	$id_adh = $login->id;
}

$member = new Adherent();
$member->load($id_adh);

$annuaire = new Annuaire();
$form = $annuaire->getInfoById($id_adh);
$fc = new FieldsConfig(Adherent::TABLE, $member->fields);
$visibles = $fc->getVisibilities();

//----------------POSTES---------------------

require_once 'lib/GaletteAAE/Postes.php';
use Galette\AAE\Postes as Postes;
require_once 'lib/GaletteAAE/Entreprises.php';
use Galette\AAE\Entreprises as Entreprises;
$postes = new Postes();
$entreprises = new Entreprises();
$domaines = new Domaines();


$list_postes = $postes->getPostes($id_adh);  
foreach ($list_postes as $i => $pos){
        $id_ent = $pos['id_entreprise'];
        $ent = $entreprises->getEntreprise($id_ent);
        $list_postes[$i]['id_entreprise'] = $ent['id_entreprise'];
        $list_postes[$i]['employeur'] = $ent['employeur'];
        $list_postes[$i]['website'] = $ent['website'];
        $list_postes[$i]['domaines'] =$domaines->getDomainesFromPosteToString($pos['id_poste']);
    }
 
//Tri le tableau en fonction de la date de début.
usort($list_postes, function($a, $b) {
    return $b['annee_ini'] - $a['annee_ini'];
});

$tpl->assign('list_postes', $list_postes);

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

$content = $tpl->fetch('voir_adherent_public.tpl');
$tpl->assign('content', $content);

//Set path back to main Galette's template
$tpl->template_dir = $orig_template_path;
$tpl->display('public_page.tpl');
