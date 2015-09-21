<?php

define('GALETTE_BASE_PATH', '../../');
require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';

//Constants and classes from plugin
require_once '_config.inc.php';

use Analog\Analog;
use Galette\Core\GaletteMail;
use Galette\Entity\Adherent as Adherent;

require_once 'lib/GaletteAAE/Offres.php';
use Galette\AAE\Offres as Offres;

require_once 'lib/GaletteAAE/Entreprises.php';
use Galette\AAE\Entreprises as Entreprises;

require_once 'lib/GaletteAAE/Domaines.php';
use Galette\AAE\Domaines as Domaines;

if ( !$preferences->showPublicPages($login) ) {
    //public pages are not actives
    header('location:' . GALETTE_BASE_PATH  . 'index.php');
    die();
}

//Recup Offres
if ( $login->isAdmin() || $login->isStaff() ){
    $haveRights = true;

}else{
    $haveRights = false;
}

$offres = new Offres();
$entreprises = new Entreprises();
$domaines = new Domaines();

$id_offre = get_numeric_form_value('id_offre', '');
$id_adh = get_numeric_form_value('id_adh', $login->id);

if( ! $haveRights) {
	//On vérifie que l'adhérent n'est pas l'auteur de l'offre
	$offre = $offres->getOffre($id_offre);
	if($offre["id_adh"] != $login->id)
	{
		$id_offre=''; // on efface pour interdire la consultation d'une offre sans etre connecte
		$warning_detected[] = _T("You don't have the permission to modify an existing offer.");
	}
}

// template variable declaration
$title = _T("Job offer");
if ( $id_offre != '' ) {
    $title .= ' (' . _T("modification") . ')';
} else {
    $title .= ' (' . _T("creation") . ')';
}

$tpl->assign('page_title', $title);

if (isset($_POST['titre_offre']) ) {
    //form was send normally, we try to store new values

    $d = \DateTime::createFromFormat(_T("Y-m-d"), $_POST['date_fin']);
    if ( $d === false ) {
        #throw new \Exception('Incorrect format');
        $date_fin = "";
    }else{
		$date_fin = $d->format('Y-m-d');
	}
	
    $d = \DateTime::createFromFormat(_T("Y-m-d"), $_POST['date_debut']);
    if ( $d === false ) {
        #throw new \Exception('Incorrect format');
        $date_debut = "";
    }else{
		$date_debut = $d->format('Y-m-d');
	}
    $res = $offres->setOffre(
		$id_offre,
		$id_adh,
		$_POST['titre_offre'],
		$_POST['organisme'],
		$_POST['localisation'],
		$_POST['site'],
		$_POST['nom_contact'],
		$_POST['mail_contact'],
		$_POST['tel_contact'],
		$date_fin,
		$_POST['type_offre'],
		$_POST['desc_offre'],
		$_POST['mots_cles'],
		$_POST['duree'],
		$date_debut,
		$_POST['remuneration'],
		$_POST['cursus'],
		$_POST['rech_majeures'],
		isset($_POST['valid'] ) //$haveRights
    );

    if ( !$res ) {
        $error_detected[] = _T("Offer has not been modified!");
    } else {
		if ( $id_offre != '' ) {
			$success_detected[] = _T("Offer has been successfully modified.");
		} else {
			$success_detected[] = _T("Offer has been successfully created, you will receive an confirmation email.");
			if ( GaletteMail::isValidEmail($_POST['mail_contact']) ) {
				$mail = new GaletteMail();
                $mail->setSubject(
                    _T("Job offer post")
                );
                
				//Send mail to contact + admin
                $mail->setRecipients(
                    array(
						$_POST['mail_contact'] => $_POST['nom_contact'],
						$preferences->pref_email => "Admin"
                    )
                );
                $message = _T("Your job offer has been successfully created.");
                $message .= "\n" . _T("You can view or modify your offer using the link below.");
                $message .= "\n\n";
                $message .= "http://".$_SERVER['HTTP_HOST']."/plugins/galette-plugin-aae/ajouter_offre.php?id_offre=".$res; 

                $mail->setMessage($message);
                $sent = $mail->send();

		        if ( $sent ) {
		            $hist->add(
		                preg_replace(
		                    array('/%name/', '/%email/'),
		                    array($_POST['nom_contact'], $_POST['mail_contact']),
		                    _T("Mail sent to user %name (%email)")
		                )
		            );
		        } else {
		            $txt = preg_replace(
		                array('/%name/', '/%email/'),
		                array($_POST['nom_contact'], $_POST['mail_contact']),
		                _T("A problem happened while sending job offer confirmation to user %name (%email)")
		            );
		            $hist->add($txt);
		            $error_detected[] = $txt;
		        }
		    } else {
		        $txt = preg_replace(
		            array('/%name/', '/%email/'),
		            array($_POST['nom_contact'], $_POST['mail_contact']),
		            _T("Trying to send a mail to a member (%name) with an invalid adress: %email")
		        );
		        $hist->add($txt);
		        $warning_detected[] = $txt;
		    }
		}
    }
}

//Recupération des entreprises :
$allEntreprises = $entreprises->getAllEntreprises();
foreach ($allEntreprises as $entreprise) {
	$pk = Entreprises::PK;
	$name = $entreprise["employeur"];
	$entreprises_options[$entreprise[$pk]]["employeur"] = $name;
}
$tpl->assign('entreprises', $entreprises_options);

if ( $id_offre!='') {
	//Recup offre
	$offre = $offres->getOffre($id_offre);
	
	$d = new \DateTime($offre->date_debut);
    $offre['date_debut'] = $d->format(_T("Y-m-d"));

	$d = new \DateTime($offre->date_fin);
    $offre['date_fin'] =  $d->format(_T("Y-m-d"));
    
	$tpl->assign('offer', $offre);
	
}else if ( $login->isLogged() )  {

    // Get member informations
    $adh = new Adherent();
    $adh->load($id_adh);
	$offre['nom_contact'] =  $login->name . " " . $login->surname;
	$offre['mail_contact'] = $adh->email;
	$tpl->assign('offer', $offre);
}

$tpl->assign('haveRights', $haveRights);
$tpl->assign('domaines',$domaines->getAllDomaines() );


//Error
$tpl->assign('warning_detected', $warning_detected);
$tpl->assign('error_detected', $error_detected);
$tpl->assign('success_detected', $success_detected);

$tpl->assign('require_calendar', true);

// page generation
$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;
$content = $tpl->fetch('ajouter_offre.tpl');

$tpl->assign('content', $content);

//Set path back to main Galette's template
$tpl->template_dir = $orig_template_path;

$tpl->display('public_page.tpl');

?>
