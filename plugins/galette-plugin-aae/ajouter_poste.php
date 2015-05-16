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

#----------CREATION----------#

if(($_GET['id_adh']!='') && ($_GET['id_poste']=='')){
 
    $vis=False;
    $tpl->assign('vis',$vis);

    $modif=False;
    $tpl->assign('modif',$modif);

    $id_adh = $login->id;
	if ( ($login->isAdmin() || $login->isStaff()) && isset($_GET['id_adh']) && $_GET['id_adh'] != '' ) {
		$id_adh = $_GET['id_adh'];
	}
    $tpl->assign('id_adh', $id_adh);

    //Recupération des entreprises :
    $allEntreprises = $entreprises->getAllEntreprises();
    foreach ($allEntreprises as $entreprise) {
        $pk = Entreprises::PK;
        $name = $entreprise["employeur"];
        $entreprises_options[$entreprise[$pk]]["employeur"] = $name;
        $entreprises_options[$entreprise[$pk]]["website"] = $entreprise["website"];
    }
    $tpl->assign('entreprises', $entreprises_options);

    if (isset($_POST['annee_ini']) && isset($_POST['employeur']))
    {
        

       $res2 = $entreprises->getAllEntreprises();
       foreach ($res2 as $entreprise) {

        $name = $entreprise["employeur"];
        if($name == $_POST['employeur'])
        {
            $entrId = $entreprise['id_entreprise'];
           
        }
           
    }
        


        $res = $postes->setPoste(
            '',
            $id_adh,
            $_POST['activite_principale'],
            $_POST['type'],
            $_POST['encadrement'],
            $_POST['nb_personne_encadre'],
            $entrId,
            $_POST['adresse'],
            $_POST['code_postal'],
            $_POST['ville'],
            $_POST['annee_ini'],
            $_POST['annee_fin']
            );


    }
}

#----------MODIFICATION----------#


if(($_GET['id_adh']!='') && ($_GET['id_poste']!='')){

    $vis=False;
    $tpl->assign('vis',$vis);

    $modif=True;
    $tpl->assign('modif',$modif);

    $id_adh = $login->id;
	if ( ($login->isAdmin() || $login->isStaff()) && isset($_GET['id_adh']) && $_GET['id_adh'] != '' ) {
		$id_adh = $_GET['id_adh'];
	}
    $tpl->assign('id_adh', $id_adh);

    $id_poste = $_GET['id_poste'];
    $tpl->assign('id_poste', $id_poste);

    //Recupération des entreprises :
    $allEntreprises = $entreprises->getAllEntreprises();
    foreach ($allEntreprises as $entreprise) {
        $pk = Entreprises::PK;
        $name = $entreprise["employeur"];
        $entreprises_options[$entreprise[$pk]]["employeur"] = $name;
        $entreprises_options[$entreprise[$pk]]["website"] = $entreprise["website"];
    }
    $tpl->assign('entreprises', $entreprises_options);

    $pst = $postes->getPoste($id_poste);
    $tpl->assign('poste',$pst);

    $idEntr = $pst['id_entreprise'];
    $ent = $entreprises->getEntreprise($idEntr);

    $nomEnt = $ent['employeur'];

    $tpl->assign('nomEnt',$nomEnt);

    if (isset($_POST['annee_ini']) && isset($_POST['employeur']))
    {
        

       $res2 = $entreprises->getAllEntreprises();
       foreach ($res2 as $entreprise)
       {
			$name = $entreprise["employeur"];
			if($name == $_POST['employeur'])
			{
				$entrId = $entreprise['id_entreprise'];
			   
			}     
		}
        
        $res = $postes->setPoste(
            $id_poste,
            $id_adh,
            $_POST['activite_principale'],
            $_POST['type'],
            $_POST['encadrement'],
            $_POST['nb_personne_encadre'],
            $entrId,
            $_POST['adresse'],
            $_POST['code_postal'],
            $_POST['ville'],
            $_POST['annee_ini'],
            $_POST['annee_fin']
            );

	}

}
    
#----------VISUALISATION----------#

if(($_GET['id_adh']=='') && ($_GET['id_poste']!='')){

    $vis=True;
    $tpl->assign('vis',$vis);

    $modif=False;
    $tpl->assign('modif',$modif);

    $id_poste = $_GET['id_poste'];
    $tpl->assign('id_poste', $id_poste);

    $pst = $postes->getPoste($id_poste);
    $tpl->assign('poste',$pst);

    $idEntr = $pst['id_entreprise'];
    $ent = $entreprises->getEntreprise($idEntr);

    $nomEnt = $ent['employeur'];

    $tpl->assign('nomEnt',$nomEnt);
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
