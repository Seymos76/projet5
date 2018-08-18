<?php

// src/Services/Capture/NAOCaptureManager.php

namespace App\Services\Capture;

use App\Entity\User;
use App\Services\NAOManager;
use App\Entity\Capture;
use App\Services\NAOPagination;
use Symfony\Component\DependencyInjection\ContainerInterface;

class NAOCaptureManager
{
	private $naoPagination;
	private $naoManager;
	private $container;

	public function __construct(NAOPagination $naoPagination, NAOManager $naoManager, ContainerInterface $container)
	{
		$this->naoManager = $naoManager;
		$this->naoPagination = $naoPagination;
		$this->container = $container;
	}

    /**
     * @param array $data
     * @param string $directory
     * @return Capture
     */
	public function buildCapture(array $data, string $directory, string $role): Capture
    {
        $bird_image = $this->container->get('app.avatar_service')->buildImage($data['image'], $directory);
        $capture = new Capture();
        $capture->setBird($data['bird']);
        $capture->setImage($bird_image);
        if ($role === 'particulier') $capture->setStatus('waiting_for_validation'); else $capture->setStatus($data['status']);
        $capture->setContent($data['content']);
        $capture->setLatitude($data['latitude']);
        $capture->setLongitude($data['longitude']);
        $capture->setAddress($data['address'] = $data['address'] ?? null);
        $capture->setComplement($data['complement'] = $data['complement'] ?? null);
        $capture->setZipcode($data['zipcode'] = $data['zipcode'] ?? null);
        $capture->setCity($data['city'] = $data['city'] ?? null);
        $capture->setRegion($data['region'] = $data['region'] ?? null);
        return $capture;
    }

	public function getPublishedCaptures()
	{
		return $publishedCaptures = $this->naoManager->getEm()->getRepository(Capture::class)->getPublishedCaptures();
	}

	public function getPublishedCapturesPerPage($page, $numberOfPublishedCaptures, $numberOfElementsPerPage)
	{
		$firstEntrance = $this->naoPagination->getFirstEntrance($page, $numberOfPublishedCaptures, $numberOfElementsPerPage);

		return $publishedCapturesPerPage = $this->naoManager->getEm()->getRepository(Capture::class)->getPublishedCapturesPerPage($numberOfElementsPerPage, $firstEntrance);
	}

	public function getPublishedCapture($id)
	{
		return $publishedCapture = $this->naoManager->getEm()->getRepository(Capture::class)->getPublishedCapture($id);
	}

	public function getWaintingForValidationCaptures()
	{
		return $waintingForValidationCaptures = $this->naoManager->getEm()->getRepository(Capture::class)->getCapturesByStatus('waiting for validation');
	}

	public function getWaintingForValidationCapturesPerPage($page, $numberOfWaitingForValidationCaptures, $numberOfElementsPerPage)
	{
		$firstEntrance = $this->naoPagination->getFirstEntrance($page, $numberOfWaitingForValidationCaptures, $numberOfElementsPerPage);

		return $waintingForValidationCaptures = $this->naoManager->getEm()->getRepository(Capture::class)->getCapturesByStatusPerPage('waiting for validation', $numberOfElementsPerPage, $firstEntrance);
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

	public function getUserCapturesPerPage($page, $numberOfUserCaptures, $numberOfElementsPerPage, $id)
	{
		$firstEntrance = $this->naoPagination->getFirstEntrance($page, $numberOfUserCaptures, $numberOfElementsPerPage);

		return $UserCapturesPerPage = $this->naoManager->getEm()->getRepository(Capture::class)->getUserCapturesPerPage($numberOfElementsPerPage, $firstEntrance, $id);
	}

	public function searchCapturesByBirdAndRegionPerPage($bird, $region, $pageNumber, $numberOfSearchCaptures, $numberOfPublishedCapturesPerPage)
	{
		$firstEntrance = $this->naoPagination->getFirstEntrance($pageNumber, $numberOfSearchCaptures, $numberOfPublishedCapturesPerPage);

		if (empty($bird))
		{
			return $numberSearchCapturesByBirdAndRegion = $this->naoManager->getEm()->getRepository(Capture::class)->searchCapturesByRegionPerPage($region, $numberOfPublishedCapturesPerPage, $firstEntrance);
		}

		if (empty($region))
		{
			return $numberSearchCapturesByBirdAndRegion = $this->naoManager->getEm()->getRepository(Capture::class)->searchCapturesByBirdPerPage($bird, $numberOfPublishedCapturesPerPage, $firstEntrance);
		}
		else
		{
			return $searchCaptureByBirdAndRegionPerPage = $this->naoManager->getEm()->getRepository(Capture::class)->searchCapturesByBirdAndRegionPerPage($bird, $region, $numberOfPublishedCapturesPerPage, $firstEntrance);
		}
	}
}