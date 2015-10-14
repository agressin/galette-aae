    <link rel="stylesheet" type="text/css" href="{$galette_base_path}{$aaetools_tpl_dir}galette_aae.css"/>
    <script type='text/javascript' src='{$galette_base_path}{$aaetools_tpl_dir}jquery.dynatable.js'></script>
		<link href="{$galette_base_path}{$aaetools_tpl_dir}jquery.dynatable.css" media="all" rel="stylesheet"></link>

{if $require_map}
  <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.3/leaflet.css" />
  <link rel="stylesheet" href="css/style_cartes.css" type="text/css" />
  <link rel="stylesheet" type="text/css" href="http://leaflet.github.io/Leaflet.markercluster/dist/MarkerCluster.css">
  <link rel="stylesheet" type="text/css" href="http://leaflet.github.io/Leaflet.markercluster/dist/MarkerCluster.Default.css">
  <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.3/leaflet.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/proj4js/2.3.6/proj4.js"></script>
  <script type="text/javascript" src="http://leaflet.github.io/Leaflet.markercluster/dist/leaflet.markercluster-src.js"></script>
  <script type="text/javascript" src="js/CarteMembres.js"></script>
{/if}
