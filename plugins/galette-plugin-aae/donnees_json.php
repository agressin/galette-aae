<?php

define('GALETTE_BASE_PATH', '../../');
require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';

//Constants and classes from plugin
require_once '_config.inc.php';

require_once 'lib/GaletteAAE/Annuaire.php';
use Galette\AAE\Annuaire as Annuaire;

$annuaire = new Annuaire();

//$nom = $_POST['nom'];
//$prenom = $_POST['prenom'];

$nom = 'GUINARD';
$prenom = 'Stéphane';

$array = [];

$array = $annuaire->getStudent($nom,$prenom);


echo json_encode($array);

?>
