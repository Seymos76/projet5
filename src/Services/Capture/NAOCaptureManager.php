<?php

// src/Services/Capture/NAOCaptureManager.php

namespace App\Services\Capture;

use App\Services\NAOManager;
use App\Entity\Capture;
use App\Services\NAOPagination;

class NAOCaptureManager
{
	private $naoPagination;
	private $naoManager;

	public function __construct(NAOPagination $naoPagination, NAOManager $naoManager)
	{
		$this->naoManager = $naoManager;
		$this->naoPagination = $naoPagination;
	}

	public function getPublishedCaptures()
	{
		return $publishedCaptures = $this->naoManager->getEm()->getRepository(Capture::class)->getPublishedCaptures();
	}

	public function getPublishedCapturesPerPage($page, $numberOfPublishedCaptures)
	{
		$firstEntrance = $this->naoPagination->getFirstEntrance($page, $numberOfPublishedCaptures);
		$nbElementsPerPage = $this->naoPagination->getNbElementsPerPage();

		return $publishedCapturesPerPage = $this->naoManager->getEm()->getRepository(Capture::class)->getPublishedCapturesPerPage($nbElementsPerPage, $firstEntrance);
	}

	public function getPublishedCapture($id)
	{
		return $publishedCapture = $this->naoManager->getEm()->getRepository(Capture::class)->getPublishedCapture($id);
	}

	public function getWaintingForValidationCaptures()
	{
		return $waintingForValidationCaptures = $this->naoManager->getEm()->getRepository(Capture::class)->getCaptureByStatus('waiting for validation');
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
		return $birdCaptures = $this->naoManager->getEm()->getRepository(Capture::class)->getBirdPublishedCaptures($id);
	}
}