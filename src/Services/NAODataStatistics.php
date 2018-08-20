<?php

// src/Services/DataStatistics.php

namespace App\Services;

use App\Services\Capture\NAOCountCaptures;
use App\Services\Bird\NAOCountBirds;
use App\Services\Bird\NAOBirdManager;
use Symfony\Component\HttpFoundation\JsonResponse;

class NAODataStatistics
{
    private $naoCounCaptures;
    private $naoCountBirds;
    private $naoBirdManager;

    public function __construct(NAOCountCaptures $naoCountCaptures, NAOCountBirds $naoCountBirds, NAOBirdManager $naoBirdManager)
    {
        $this->naoCountCaptures = $naoCountCaptures;
        $this->naoCountBirds = $naoCountBirds;
        $this->naoBirdManager = $naoBirdManager;
    }

	public function formatBirdsByRegions($regions)
	{
        $formatted = [];

        $dates = [];
        for ($i = 2018; $i < 2100; $i++)
        {
            $dates[] += $i;
        }
        foreach ($dates as $date) {

        $numberOfPublishedCaptures = $this->naoCountCaptures->countPublishedCapturesByYear($date);

            $regionsData = [];
            foreach ($regions as $region)
            {
                $regionName = $region['nom'];
                $numberOfBirds = $this->naoCountBirds->countSearchBirdsByRegionAndDate($regionName, $date);
                $birds = $this->naoBirdManager->searchBirdsByregionAndDate($regionName, $date);

                $birdsName = [];
                foreach ($birds as $bird) {
                    if ($bird->getVernacularname() != null)
                    {
                        $birdsName[] = $bird->getVernacularname().' - '.$bird->getValidname();
                    }
                    else 
                    {
                        $birdsName[] = $bird->getValidname();
                    }
                }

                if ($numberOfBirds >= 1)
                {
                    $regionsData[] = [
                        'region' => $regionName,
                        'numberOfBirds' => $numberOfBirds,
                        'birds' => $birdsName
                    ];
            
                    $formatted[] = [
                        'year' => $date,
                        'numberOfCaptures' => $numberOfPublishedCaptures,
                        'regions' => $regionsData,
                    ];
                }
            }
        }

        return new JsonResponse($formatted);
	}
}