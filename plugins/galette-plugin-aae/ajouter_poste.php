<?php

define('GALETTE_BASE_PATH', '../../');
require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';

//Constants and classes from plugin
require_once '_config.inc.php';

require_once 'lib/GaletteAAE/Postes.php';
use Galette\AAE\Postes as Postes;

require_once 'lib/GaletteAAE/Entreprises.php';
use Galette\AAE\Entreprises as Entreprises;

$postes = new Postes();
$entreprises = new Entreprises();

if ( !$login->isLogged() ) {
    header('location:'. GALETTE_BASE_PATH .'index.php');
    die();
}

if (isset($_POST['id_adh'])  && isset($_POST['annee_ini']) && isset($_POST['employeur']))
{

   $res2 = $entreprises->getAllEntreprises();
   $test = false;
   foreach ($res2 as $entreprise) {

    $name = $entreprise["employeur"];
    if($name == $_POST['employeur']) {
        $test = true;
        $entrId = $entreprise['id_entreprise'];
        if($entreprise["website"]!=$_POST['employeur_website'] && $_POST['employeur_website']!="")
        {$res1 = $entreprises->setEntreprise(
            $entreprise['id_entreprise'],
            $_POST['employeur'],
            $_POST['employeur_website']
            );

        print_r($res1);}
    }
        $dernier=$entreprise['id_entreprise'];
    }
    
    if (!$test) {
        $res1 = $entreprises->setEntreprise(
            '',
            $_POST['employeur'],
            $_POST['employeur_website']
            );

        print_r($res1);


        $entrId =$dernier + 1;
    }




    $res = $postes->setPoste(
        '',
        $_POST['id_adh'],
        $_POST['activite_principale'],
        $_POST['encadrement'],
        $_POST['nb_personne_encadre'],
        $entrId,
        $_POST['adresse'],
        $_POST['code_postal'],
        $_POST['ville'],
        $_POST['annee_ini'],
        $_POST['annee_fin']
        );

    print_r($res);


}

// page generation
$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;
$content = $tpl->fetch('ajouter_poste.tpl');

$tpl->assign('content', $content);

//Set path back to main Galette's template
$tpl->template_dir = $orig_template_path;

$tpl->display('public_page.tpl');


?>
