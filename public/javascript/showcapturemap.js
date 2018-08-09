$( function() {
    
	var macarte = null;
	var id = map.dataset.identifiant;

	function initMap() {
		$.getJSON('http://localhost:8000/api/latloncapture/'+id+'/', function( data ) {
            macarte = L.map('map').setView([ data[0].latitude, data[0].longitude], 7);
            L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
                attribution: 'données © <a href="//osm.org/copyright">OpenStreetMap</a>/ODbL - rendu <a href="//openstreetmap.fr">OSM France</a>',
                minZoom: 1,
                maxZoom: 20
            }).addTo(macarte);
            var marker = L.marker([data[0].latitude, data[0].longitude]).addTo(macarte);
        })
    }
    
	window.onload = function(){
		initMap(); 
	};
});
