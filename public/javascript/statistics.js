$.getJSON('http://localhost:8000/api/datastatistics', function( data ) {
	var stats = "";
	stats += '<h2>Années</h2>';
	for (i=0; i < data.length; i++)
	{
		stats += '<div>'
		stats += '<h3>' + data[i].year + '</h3>';
		stats += '<p> Nombre total d\'observations publiées : ' + data[i].numberOfCaptures + '</p>';
		stats += '<h2>Régions</h2>';
		for (a=0; a < data[i].regions.length; a++)
		{
			stats += '<h3>' + data[i].regions[a].region +'</h3>';
			stats += '<p>' + data[i].regions[a].numberOfBirds + ' espèces d\'oiseaux observées :</p>';
			stats += '<ul>';
			for (t=0; t < data[i].regions[a].birds.length; t++)
			{
				stats += '<li>';
				stats += data[i].regions[a].birds[t];
				stats += '</li>';
			}
		}
		stats += '</ul>'
		stats += '</div>'
	}
	$('#statisticsByYear').html(stats);
})