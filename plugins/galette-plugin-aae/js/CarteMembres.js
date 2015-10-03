/**
	Class CarteMembre pour afficher les le logement et les postes des membres.
	In: options = {
			idCarte : string, identifiant de la div HTML où afficher la carte, ex : 'carteMembres'
			idNoResult : string, identifiant de la div HTML où afficher les erreurs/remarques, ex : 'noResult'
			idLoading : string, identifiant de la div HTML à afficher pendant les chargements, ex : 'loading'
			center : [integer, integer], position où centrer la carte, ex : [2.85645, 46.64944]
			zoom : integer, niveau de zoom de la carte, ex : 6
			hauteurAuto : boolean, adapter automatiquement la hauteur de la carte, ex : true
			scrollAuto : boolean, scroller automatiquement à la carte, ex : true
			layerTypeIGN = string, type du lager de l'IGN (cf. NOTE SUR LES COUCHES DISPO), ex : 'GEOGRAPHICALGRIDSYSTEMS.MAPS'
			spiderfyDistanceMultiplier = real, increase from 1 to increase the distance away from the center that spiderfied markers are placed, ex : 2
			keyMaps : string, clé Google Maps, ex : AIzaSyCpMXa7ZJn2L7WebriShk4v8NSU4n3N-s8
			keyIGN : string, clé GéoPortail, ex : 3lazvcze0jph9flnl6agpvss (localhost) ou 3s9er40tvaqliky3tswb38l2 (192.168.56.101, par défaut),
			boundsFrance : bounds/boolean, limites de la France, ex : L.latLngBounds([41.331554,-5.197418], [51.020073, 9.516022])
		}
**/
function CarteMembres(options) {
	// Para carte :
	this.idCarte = options.idCarte || 'carteMembres';
	this.idNoResult = options.idNoResult || 'noResult';
	this.idDetails = options.idDetails || 'details';
	this.center = options.center || [46.49839, 3.20801];
	this.zoom = options.zoom || 6;
	this.hauteurAuto = options.hauteurAuto || false;
	this.scrollAuto = options.scrollAuto || false;
	// Para layer :
	this.layerTypeIGN = options.layerTypeIGN || 'GEOGRAPHICALGRIDSYSTEMS.MAPS';
	this.boundsFrance = options.boundsFrance || false;
	// Para cluster :
	this.spiderfyDistanceMultiplier = options.spiderfyDistanceMultiplier || 2;
	// Para keys :
	this.keyMaps = options.keyMaps || 'AIzaSyCpMXa7ZJn2L7WebriShk4v8NSU4n3N-s8';
	this.keyIGN = options.keyIGN || '61fs25ymczag0c67naqvvmap';
	// Autres attributs :
	this.markers = L.markerClusterGroup({spiderfyDistanceMultiplier : this.spiderfyDistanceMultiplier});
	var that = this;

	/**
		GET CAPABILITIES WMTS IGN : http://wxs.ign.fr/3s9er40tvaqliky3tswb38l2/geoportail/wmts?SERVICE=WMTS&REQUEST=GetCapabilities

		NOTE SUR LES COUCHES DISPO :
			- GEOGRAPHICALGRIDSYSTEMS.MAPS
			- GEOGRAPHICALGRIDSYSTEMS.MAPS.SCAN-EXPRESS.CLASSIQUE
			- GEOGRAPHICALGRIDSYSTEMS.MAPS.SCAN-EXPRESS.STANDARD
	**/
	$('#'+this.idDetails).fadeIn();
	// Creation de la carte :
	this.map = L.map(this.idCarte, {
		center : this.center,
		zoom : this.zoom,
		zoomAnimation : false
	});

	var urlOSM='http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
    var attribOSM='Map data © <a href="http://openstreetmap.org">OpenStreetMap</a> contributors';
    this.layerOSM = L.tileLayer(urlOSM, {minZoom: 7, maxZoom: 18, attribution: attribOSM}).addTo(this.map);

	var urlIGN = "http://wxs.ign.fr/"+ this.keyIGN + "/wmts?LAYER=" + this.layerTypeIGN + "&EXCEPTIONS=text/xml&FORMAT=image/jpeg&SERVICE=WMTS&VERSION=1.0.0&REQUEST=GetTile&STYLE=normal&TILEMATRIXSET=PM&TILEMATRIX={z}&TILECOL={x}&TILEROW={y}";
	this.layerIGN = L.tileLayer(urlIGN, {
		attribution : '&copy; <a href="http://www.ign.fr/">IGN-France</a>',
		maxZoom : 6
	}).addTo(this.map);

	if (this.boundsFrance != false) {
		var urlIGN = "http://wxs.ign.fr/"+ this.keyIGN + "/wmts?LAYER=" + this.layerTypeIGN + "&EXCEPTIONS=text/xml&FORMAT=image/jpeg&SERVICE=WMTS&VERSION=1.0.0&REQUEST=GetTile&STYLE=normal&TILEMATRIXSET=PM&TILEMATRIX={z}&TILECOL={x}&TILEROW={y}";
		this.layerIGNzoom = L.tileLayer(urlIGN, {
			minZoom: 7,
			maxZoom : 18,
			bounds : this.boundsFrance
		}).addTo(this.map);

		var baseLayers = {
			"Open Street Map" : this.layerOSM,
			"Carte IGN" : this.layerIGN,
			"Carte IGN France" : this.layerIGNzoom
		};
	} else {
		var baseLayers = {
			"Open Street Map" : this.layerOSM,
			"Carte IGN" : this.layerIGN
		};
	}
	L.control.layers({}, baseLayers).addTo(this.map);

	if (this.hauteurAuto === true) {that.majTaille();}
}

CarteMembres.prototype.ajax = function(paraAjax) {
	var that = this;
	$('#'+that.idLoading).show();
	$.ajax(paraAjax).done(function(data) {
		that.afficherLieux(data);
		that.map._onResize(); 
	});
}

CarteMembres.prototype.afficherLieux = function(data) {
	this.viderLieux();
	var that = this;
	var liste = JSON.parse(data);
	$('#'+that.idLoading).hide();
	if (liste.length == 0) {
		$('#'+this.idCarte).hide();
		$('#'+this.idNoResult).fadeIn();
	} else {
		$('#'+this.idNoResult).hide();
		$('#'+this.idCarte).fadeIn();
		$(liste).each(function(i, elm){
			if (elm.coords === false) {
				// Geocodage puis ajout :
				setTimeout(function(){
					$.ajax({
						url: 'https://maps.googleapis.com/maps/api/geocode/json?address=' + elm.adresse + '&key=' + that.keyMaps,
						success: function(retour) {
							var lat = retour.results[0].geometry.location.lat;
							var lon = retour.results[0].geometry.location.lng;

							var marker = L.marker([lat, lon], {
								title : elm.info,
								icon: L.icon({
									iconUrl: 'icone/' + elm.type + '.png',
									iconAnchor: [16, 16],
									shadowAnchor: [4, 62]
								})
							}).bindPopup(elm.info);
							that.ajouterLieu(marker);
						}
					});
				}, 200);
			} else {
				// Ajout direct :
				var marker = L.marker([elm.coords.lat, elm.coords.lon], {
					title : elm.info,
					icon: L.icon({
						iconUrl: 'icone/' + elm.type + '.png',
						iconAnchor: [16, 16],
						shadowAnchor: [4, 62]
					})
				}).bindPopup(elm.info);
				that.ajouterLieu(marker);
			}
		});
	}
}

CarteMembres.prototype.ajouterLieu = function(marker) {
	var that = this;
	// Ajout du lieu :
	that.markers.addLayer(marker);
	if (that.bounds) {that.bounds.extend(marker.getLatLng());}
	else {that.bounds = L.latLngBounds([marker.getLatLng()]);}
	// MAJ de la carte :
	that.map.addLayer(that.markers);
	that.map.fitBounds(that.markers.getBounds(), {padding : [42,42]});
}

CarteMembres.prototype.viderLieux = function() {
	var that = this;
	// On efface tout sur la carte :
	that.map.removeLayer(that.markers);
	that.markers = L.markerClusterGroup({spiderfyDistanceMultiplier : that.spiderfyDistanceMultiplier});
}

CarteMembres.prototype.majTaille = function() {
	$('#'+this.idCarte).height($(window).height());
	this.map.invalidateSize(false);
}

CarteMembres.prototype.scrollToCarte = function() {
	var that = this;
	$('html, body').animate({
		scrollTop:$('#'+that.idCarte).offset().top
	}, 'fast');
}
