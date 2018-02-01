<html lang="fr">

<head>
	<meta charset="utf-8">
	<title>Visage | AAE-ENSG</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Roboto+Condensed" rel="stylesheet">

	<link href="lib/visage/materialize.min.css" rel="stylesheet" />
	<script src="lib/visage/jquery-3.1.1.min.js"></script>
	<script src="lib/visage/materialize.min.js"></script>
	<script src="lib/visage/reimg.js"></script>

	<link href="css/visage_style.css" rel="stylesheet" />
	<script src="js/visage_ClassComplements.js"></script>
	<script src="js/visage_Eleve.js"></script>
	<script src="js/visage_Famille.js"></script>
	<script src="js/visage_initArbre.js"></script>
</head>

<body>

	<div class="popover bottom" id="popover">
		<div class="arrow"></div>
		<h3 class="popover-title" id="popoverTitle"></h3>
		<div class="popover-content" id="popoverContent"></div>
	</div>

	<div id="container">

		<div id="parentForm" class="">
			<div id="formEleve">

				<!--h3 class="red-text text-darken-4 center-align">Visage</h3-->
				<div id="logo">
					<a href="{$galette_base_path}" title="Retourer sur le site AAE-ENSG">
						<img src="icone/logo_visage.png">
					</a>
				</div>

				<p class="input-field">
					<input type="text" value="" id="str" name="str" data-n="0" data-ide="true" />
					<label for="str">Nom/Prénom d'un élève</label>
					<div id="str-results" class="autocomplete-content dropdown-content"></div>
				</p>
				<p>
					<input type="checkbox" name="remonter" id="remonter" checked="checked" />
					<label for="remonter">Remonter dans la lignée</label>
					<span class="popuphelper tooltipped" data-position="bottom" data-delay="50" data-tooltip="Afficher également les parrains de l'élève, aussi loin que possible !"><i class="material-icons">help</i></span>
				</p>

				<ul class="collapsible" data-collapsible="expandable">
					<li>
						<div class="collapsible-header">
							<i class="material-icons">settings</i>Paramètres
						</div>
						<div class="collapsible-body no-padding">
							<ul class="collection">
								<li class="collection-item">
									<input type="checkbox" name="recentrer" id="recentrer" checked="checked" />
									<label for="recentrer">Recentrer les élèves</label>
									<span class="popuphelper tooltipped" data-position="bottom" data-delay="50" data-tooltip="Améliore la position des élèves par rapport à leurs parrains/fillots. Décochez si vous rencontrez des problèmes d'affichage."><i class="material-icons">help</i></span>
								</li>
								<li class="collection-item">
									<p class="range-field">
										<label for="fontSize" class="range-label">Taille du texte : <span class="value red-text text-darken-4"></span> px</label>
										<input type="range" id="fontSize" name="fontSize" min="14" max="30" value="15" />
									</p>
								</li>
								<li class="collection-item">
									<p class="range-field">
										<label for="size" class="range-label">Taille des images : <span class="value red-text text-darken-4"></span> px</label>
										<span class="popuphelper tooltipped" data-position="bottom" data-delay="50" data-tooltip="Modifier la taille de l'arbre généré. Utile si vous souhaitez exporter le résultat."><i class="material-icons">help</i></span>
										<input type="range" id="size" name="size" min="40" max="100" value="50" />
									</p>
								</li>
							</ul>
						</div>
					</li>
				</ul>

				<div class="center-align">
					<button id="afficher" class="btn red darken-4 popuphelper waves-effect waves-light">Afficher</button>
					<!-- L'arbre peut parfois avoir des branches qui partent dans le mauvais sens ! Cliquez du boutton droit de la souris sur un élève pour intervertir ses fillots et retracer l'arbre. -->
					<button id="export-small-screen" class="btn red darken-4 popuphelper waves-effect waves-light margin-top">Exporter l'arbre</button>
				</div>

			</div>

			<a id="export-large-screen" class="btn-floating btn-large waves-effect waves-light red darken-4 right tooltipped" data-position="top" data-delay="50" data-tooltip="Exporter l'arbre"><i class="material-icons">save</i></a>
		</div>

		<div class="center-align loader animated" id="parentCanvas">
			<div class="loading"><img src="icone/visage_loading.gif" alt="Chargement..."></div>
			<div class="results">
				<ul id="results" class="collection with-header"></ul>
			</div>
			<p id="directParentCanvas">
				<canvas id="canvas" width="400px" height="400px"></canvas>
			</p>
		</div>

	</div>
</body>

</html>