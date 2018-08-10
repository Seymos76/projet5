<?php

// src/Services/Capture/NAOCaptureManager.php

namespace App\Services\Capture;

use App\Services\NAOManager;
use App\Entity\Capture;

class NAOCaptureManager extends NAOManager
{
	public function getPublishedCaptures()
	{
		return $publishedCaptures = $this->em->getRepository(Capture::class)->getPublishedCaptures();
	}

	public function getPublishedCapture($id)
	{
		return $publishedCapture = $this->em->getRepository(Capture::class)->getPublishedCapture($id);
	}

	public function getWaintingForValidationCaptures()
	{
		return $waintingForValidationCaptures = $this->em->getRepository(Capture::class)->getCaptureByStatus('waiting for validation');
	}

	public function validateCapture(Capture $capture, $naturalist)
	{
		$capture->setStatus('validated');
		$capture->setValidatedBy($naturalist);
	}

	public function setWaitingStatus(Capture $capture)
	{
		$capture->setStatus('waiting for validation');
	}

	public function getBirdPublishedCaptures($id)
	{
		return $birdCaptures = $this->em->getRepository(Capture::class)->getBirdPublishedCaptures($id);
	}
}