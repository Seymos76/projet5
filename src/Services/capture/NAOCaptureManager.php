<?php

// src/Services/Capture/NAOCaptureManager.php

namespace App\Services\Capture;

use App\Services\NAOManager;
use App\Entity\Capture;

class NAOCaptureManager extends NAOManager
{
	public function getPublishedCaptures()
	{
		$publishedCaptures = $this->em->getRepository('Capture')->getPublishedCaptures();

        return $publishedCaptures;
	}

	public function getWaintingForValidationCaptures()
	{
		$waintingForValidationCaptures = $this->em->getRepository('Capture')->getByStatus('waiting for validation');

		return $waintingForValidationCaptures;
	}

	public function validateCapture(Capture $capture)
	{
		$capture->setStatus('validated');
	}
}