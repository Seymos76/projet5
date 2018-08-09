<?php

// src/Services/Capture/NAOShowMap.php

namespace App\Services\Capture;

use App\Services\NAOManager;
use App\Services\Capture\NAOCaptureManager;
use App\Entity\Capture;
use Symfony\Component\HttpFoundation\JsonResponse;

class NAOShowMap extends NAOManager
{
	private $naoCaptureManager;

	public function __construct(NAOCaptureManager $naoCaptureManager)
	{
		$this->naoCaptureManager = $naoCaptureManager;
	}

	public function formatPublishedCaptures()
	{
		$publishedCaptures = $this->naoCaptureManager->getPublishedCaptures();

        $formatted = [];
        foreach ($publishedCaptures as $publishedCapture) {
            $formatted[] = [
                'id' => $publishedCapture->getId(),
                'bird' => $publishedCapture->getBird()->getVernacularname(),
                'latitude' => $publishedCapture->getLatitude(),
                'longitude' => $publishedCapture->getLongitude(),
                'address' => $publishedCapture->getAddress(),
                'complement' => $publishedCapture->getComplement(),
                'zipcode' => $publishedCapture->getZipcode(),
                'city' => $publishedCapture->getCity(),
            ];
        }

        return new JsonResponse($formatted);
	}	

	public function formatCapture($id)
	{
		$capture = $this->em->getRepository(Capture::class)->findOneById($id);

        $formatted[] = [
            'id' => $capture->getId(),
            'latitude' => $capture->getLatitude(),
            'longitude' => $capture->getLongitude(),
        ];

        return new JsonResponse($formatted);
	}
}