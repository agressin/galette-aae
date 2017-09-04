<?php

define('GALETTE_BASE_PATH', '../../');
require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';

//Constants and classes from plugin
require_once '_config.inc.php';

if ( !$login->isLogged() ) {
    header('location: index.php');
    die();
}
if ( !$login->isAdmin() && !$login->isStaff() ) {
    header('location: voir_adherent.php');
    die();
}

use Galette\IO\Csv;
use Galette\IO\CsvIn;
use Galette\Entity\Adherent;
use Galette\Entity\FieldsConfig;
use Galette\Repository\Members;

require_once 'lib/GaletteAAE/Cycles.php';
use Galette\AAE\Cycles as Cycles;

$cycles = new Cycles();

$csv = new CsvIn();

$written = array();
$dryrun = true;

if ( isset($_GET['sup']) ) {
    $res = $csv->remove($_GET['sup']);
    if ( $res === true ) {
        $success_detected[] = str_replace(
            '%export',
            $_GET['sup'],
            _T("'%export' file has been removed from disk.")
        );
    } else {
        $error_detected[] = str_replace(
            '%export',
            $_GET['sup'],
            _T("Cannot remove '%export' from disk :/")
        );
    }
}



// CSV file upload
if ( isset($_FILES['new_file']) ) {
    if ( $_FILES['new_file']['error'] === UPLOAD_ERR_OK ) {
        if ( $_FILES['new_file']['tmp_name'] !='' ) {
            if ( is_uploaded_file($_FILES['new_file']['tmp_name']) ) {
                $res = $csv->store($_FILES['new_file']);
                if ( $res < 0 ) {
                    $error_detected[] = $csv->getErrorMessage($res);
                }
            }
        }
    } else if ($_FILES['new_file']['error'] !== UPLOAD_ERR_NO_FILE) {
        Analog::log(
            $csv->getPhpErrorMessage($_FILES['new_file']['error']),
            Analog::WARNING
        );
        $error_detected[] = $csv->getPhpErrorMessage(
            $_FILES['new_file']['error']
        );
    } else if ( isset($_POST['upload']) ) {
        $error_detected[] = _T("No files has been seleted for upload!");
    }
}

if ( isset($_POST['import']) && isset($_POST['import_file']) ) {
    if ( isset($_POST['dryrun']) ) {
        $dryrun = true;
    } else {
        $dryrun = false;
    }
    $res = $csv->import($_POST['import_file'], $members_fields, $dryrun);
    if ( $res !== true ) {
        if ( $res < 0 ) {
            $error_detected[] = $csv->getErrorMessage($res);
            if ( count($csv->getErrors()) > 0 ) {
                $error_detected = array_merge($error_detected, $csv->getErrors());
            }
        } else {
            $error_detected[] = _T("An error occured importing the file :(");
        }
        $tpl->assign('import_file', $_POST['import_file']);
    } else {
        $success_detected[] = str_replace(
            '%filename%',
            $_POST['import_file'],
            _T("File '%filename%' has been successfully imported :)")
        );
    }
}

//Recupération des cycles :
$all_cycles = array();
$tmp = $cycles->getAllCycles(false);
foreach ($tmp as $key => $value) {
  $all_cycles[$value['id_cycle']] = $value['nom'];
}
if (isset($_POST['selected_cycle'])){
	$tpl->assign('selected_cycle',$_POST['selected_cycle']);
	//$_POST['StartYear']
	//$_POST['EndYear']
}


$tpl->assign('dryrun', $dryrun);
$existing = $csv->getExisting();

//$tpl->assign('page_title', _T("AAE members and formation import"));
$tpl->assign('page_title', serialize($tmp[0]));

$tpl->assign('cycles', $all_cycles);
$tpl->assign('require_dialog', true);
//$tpl->assign('written', $written);
$tpl->assign('existing', $existing);
$tpl->assign('success_detected', $success_detected);
$tpl->assign('error_detected', $error_detected);
$tpl->assign('warning_detected', $warning_detected);

//Set the path to the current plugin's templates,
//but backup main Galette's template path before
$orig_template_path = $tpl->template_dir;
$tpl->template_dir = 'templates/' . $preferences->pref_theme;

$content = $tpl->fetch('import.tpl');
$tpl->assign('content', $content);

//Set path back to main Galette's template
$tpl->template_dir = $orig_template_path;

$tpl->display('page.tpl');
