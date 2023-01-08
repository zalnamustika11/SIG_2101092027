<?php

 require_once("db.php");
 $conn = new  connectToDB();
$companies = $conn->getCompaniesList();
$streets = $conn->getStreetsList();
$areas = $conn->getAreasList();
//  require_once 'views/header.php';

?>


 <div id="map" style="width: 1520px; height: 500px"></div>
 <script>
 var perusahaan = L.layerGroup();
 var jalan = L.layerGroup();
 var area = L.layerGroup();
 
 const osm = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
		maxZoom: 19,
		attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
	});
  const mapboxUrl = 'https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw';

  const street = L.tileLayer(mapboxUrl,{
		id: 'mapbox/street-v11',
    tilesize : 512,
    roomoffset : -1
	});
  const satellite = L.tileLayer(mapboxUrl,{
		id: 'mapbox/satellite-v9',
    tilesize : 512,
    roomoffset : -1
	});
  const map = L.map('map', {
		center : [0.010907259612137756, 101.33098903895802],
    zoom : 5,
    layers : [osm,jalan]
	});


// const map = L.map('map').setView([0.010907259612137756, 101.33098903895802], 5);
// const tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
//   maxZoom: 19,
//   attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
// }).addTo(map);

  $( document ).ready(function() {
   addCompanies();   
   addStreets();   
   addAreas();   
  });
  
  var baseMaps = {
    'OpenStreetMaps' : osm,
    'MapBoxStreet' : street,
    'Satellite' : satellite
  };
  var overlays = {
    'Perusahaan' : perusahaan,
    'Jalan' : jalan,
    'Area' : area
  };

  function addCompanies() {
   for(var i=0; i<companies.length; i++) {
    var marker = L.marker( [companies[i]['latitude'], companies[i]['longitude']]).addTo(perusahaan);
    marker.bindPopup( "<b>" + companies[i]['company']+"</b><br>Details:" + companies[i]['details'] + "<br />Telephone: " + companies[i]['telephone']);
   }
  }
  
  function stringToGeoPoints( geo ) {
   var linesPin = geo.split(",");

   var linesLat = new Array();
   var linesLng = new Array();

   for(i=0; i < linesPin.length; i++) {
    if(i % 2) {
     linesLat.push(linesPin[i]);
    }else{
     linesLng.push(linesPin[i]);
    }
   }

   var latLngLine = new Array();

   for(i=0; i<linesLng.length;i++) {
    latLngLine.push( L.latLng( linesLat[i], linesLng[i]));
   }
   
   return latLngLine;
  }
  
  
  function addStreets() {
   for(var i=0; i < streets.length; i++) {
    var polyline = L.polyline( stringToGeoPoints(streets[i]['geolocations_streets']), { color: 'red'}).addTo(jalan);
    polyline.bindPopup( "<b>" + streets[i]['name_streets']);   
   }
  }

  function addAreas() {
   for(var i=0; i < areas.length; i++) {
    var polygon = L.polygon( stringToGeoPoints(areas[i]['geolocations_areas']), { color: 'blue'}).addTo(area);
    polygon.bindPopup( "<b>" + areas[i]['name_areas']);   
   }
  }
  
  var companies = JSON.parse( '<?php echo json_encode($companies) ?>' );
  var streets = JSON.parse( '<?php echo json_encode($streets) ?>' );
  var areas = JSON.parse( '<?php echo json_encode($areas) ?>' );

  var layerControl = L.control.layers(baseMaps,overlays).addTo(map);


 </script>
<?php

// require_once 'views/footer.php';
?>