<?php

// src/Services/Capture/NAOShowMap.php

namespace App\Services\Capture;

use App\Entity\Capture;

class NAOShowMap
{
	public function getMapLink(Capture $capture)
	{
		$latitude = $capture->getLatitude();
        $longitude = $capture->getLongitude();
        return $map = 'http://cartosm.eu/map?lon='. $longitude .'&lat='. $latitude .'&zoom=18&width=400&height=350&mark=true&nav=true&pan=true&zb=inout&style=default&icon=down';
	}	
}