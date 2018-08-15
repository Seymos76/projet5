$( function() {

	var lat = '48.852969';
	var lon = '2.349903';
	var myMap = null;
	var markerClusters; 

	var id = map.dataset.identifiant;
	if (typeof(map.dataset.identifiant) !== 'undefined')
	{
		var url = 'birdpublishedcaptures/'+id;
	}
	else 
	{
		var url = 'publishedcaptures';
	}
	
	function initMap() {
		$.getJSON('http://localhost:8000/api/'+ url +'/', function( data ) {
			var captures = data;
			var markers = [];  
	        myMap = L.map('map').setView([lat, lon], 11);
	        markerClusters = L.markerClusterGroup(); 
	        L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
	        	attribution: 'données © <a href="//osm.org/copyright">OpenStreetMap</a>/ODbL - rendu <a href="//openstreetmap.fr">OSM France</a>',
	            minZoom: 1,
	            maxZoom: 20
	            }).addTo(myMap);
	        for (var i = 0; i < captures.length; i++) {
				var marker = L.marker([captures[i].latitude, captures[i].longitude]);
				marker.bindPopup('<a href="http://localhost:8000/observation/' + captures[i].id + '">' + captures[i].bird + '</a>' + '<br/> ' + captures[i].address + ' ' + captures[i].zipcode + ' ' + captures[i].city);
				markerClusters.addLayer(marker); 
				markers.push(marker);
			} 
			var group = new L.featureGroup(markers); 
			myMap.fitBounds(group.getBounds().pad(0.5)); 
			myMap.addLayer(markerClusters); 
		});
	}

	window.onload = function(){
		initMap(); 
	};	
});
