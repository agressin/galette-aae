<?php

define('GALETTE_BASE_PATH', '../../');
require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';

//Constants and classes from plugin
require_once '_config.inc.php';
require_once 'lib/GaletteAAE/Offres.php';

use Analog\Analog;
use Galette\Core\GaletteMail;
use Galette\AAE\Offres as Offres;
use Galette\Entity\Adherent as Adherent;


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

//if (isset($_POST['valid']) && !empty($_POST['valid'] ) ) {
if (isset($_POST['titre_offre']) ) {
    //form was send normally, we try to store new values

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
		$_POST['date_fin'],
		$_POST['type_offre'],
		$_POST['desc_offre'],
		$_POST['mots_cles'],
		$_POST['duree'],
		$_POST['date_debut'],
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
                // TODO: add mail to offer contact
                $mail->setRecipients(
                    array($_POST['mail_contact'] => $_POST['nom_contact'])
                );

                $message = _T("Your job offer has been successfully created.");
                $message .= "\n" . _T("You can view or modify your offer using the link below.");
                $message .= "\n\n";
                $message .= "http://".$_SERVER['HTTP_HOST']."plugins/galette-plugin-aae/ajouter_offre.php?id_offre=".$id_offre; //TODO adresse plugin

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
		                _T("A problem happened while sending post offer confirmation to user %name (%email)")
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

if ( $id_offre!='') {
	//Recup offre
	$offre = $offres->getOffre($id_offre);
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

$tpl->display('page.tpl');

?>
