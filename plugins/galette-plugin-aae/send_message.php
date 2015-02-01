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


if ( !$login->isLogged() ) {
    //only for logged user
    header('location:' . GALETTE_BASE_PATH  . 'index.php');
    die();
}


$id_adh = get_numeric_form_value('id_adh', '');

if( $id_adh !='') {
    $adh = new Adherent();

	//Expediteur = l'adh connecte
	$adh->load($login->id);
	$from_adh_name =  $adh->name . " " . $adh->surname;
	$from_adh_email = $adh->email;
	
	//Destinateur : choisi dans le formulaire
    $adh->load($id_adh);
	$to_adh_name =  $adh->name . " " . $adh->surname;
	$to_adh_email = $adh->email;
	$tpl->assign('to_adh_name', $to_adh_name);

	$sujet =  _T("[AAE-ENSG] Un adhérent cherche à vous contacter");
	$tpl->assign('sujet', $sujet);

	$pre_message =  _T("Un ancien de l'ENSG cherche a vous contacter :\n");
	$pre_message .= $from_adh_name . " (" . $from_adh_email . ")";
	$tpl->assign('pre_message', $pre_message);
	
	if(isset($_POST['message'])) {
		$message_user=$_POST['message'];
		if ( GaletteMail::isValidEmail($from_adh_email) and GaletteMail::isValidEmail($to_adh_email) ) {
			$mail = new GaletteMail();
		    $mail->setSubject($sujet);

		    $mail->setRecipients( array($to_adh_email => $to_adh_name));
			
			$message = $pre_message;
		    $message .= "\n\n";
		    $message .= $message_user;

		    $mail->setMessage($message);
		    $sent = $mail->send();
		    
		    $tpl->assign('subject', $sujet);
			$tpl->assign('message', $message_user);
		
		    if ( $sent ) {
		    	
		    	$txt = preg_replace(
		                array('/%name/'),
		                array($to_adh_name),
		                _T("Mail sent to user %name")
		            );
		        $hist->add($txt);
		        $success_detected[] = $txt;
		    } else {
		        $txt = preg_replace(
		            array('/%name/'),
		            array($to_adh_name),
		            _T("A problem happened while sending email to user %name")
		        );
		        $hist->add($txt);
		        $error_detected[] = $txt;
		    }
		}
	}
}

//Error
$tpl->assign('warning_detected', $warning_detected);
$tpl->assign('error_detected', $error_detected);
$tpl->assign('success_detected', $success_detected);

$tpl->assign('id_adh', $id_adh);


// page generation
$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;
$content = $tpl->fetch('send_message.tpl');

$tpl->assign('content', $content);

//Set path back to main Galette's template
$tpl->template_dir = $orig_template_path;

$tpl->display('page.tpl');

?>
