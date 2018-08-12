<?php

// src/Services/Capture/NAOShowMap.php

namespace App\Services\Capture;

use App\Services\NAOManager;
use App\Entity\Capture;
use Symfony\Component\HttpFoundation\JsonResponse;

class NAOShowMap extends NAOManager
{
	public function formatPublishedCaptures($captures)
	{
        $formatted = [];
        foreach ($captures as $capture) {
            $formatted[] = [
                'id' => $capture->getId(),
                'bird' => $capture->getBird()->getVernacularname(),
                'latitude' => $capture->getLatitude(),
                'longitude' => $capture->getLongitude(),
                'address' => $capture->getAddress(),
                'complement' => $capture->getComplement(),
                'zipcode' => $capture->getZipcode(),
                'city' => $capture->getCity(),
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