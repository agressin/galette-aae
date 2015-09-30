<?php

define('GALETTE_BASE_PATH', '../../');
require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';

//Constants and classes from plugin
require_once '_config.inc.php';

require_once 'lib/GaletteAAE/Annuaire.php';
use Galette\AAE\Annuaire as Annuaire;

require_once 'lib/GaletteAAE/Familles.php';
use Galette\AAE\Familles as Familles;


use Galette\Entity\Adherent as Adherent;

require_once 'fonctions_json.php';

$annuaire = new Annuaire();
$member = new Adherent();
$familles = new Familles();

$id = $_GET["id_adh"];

if (!empty($id)){
	$familles->CreateJSON($id);
}
?>
