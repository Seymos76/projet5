<?php

// src/Services/NAOBirdManager.php

namespace App\Services\Bird;

use App\Services\NAOPagination;
use App\Services\NAOManager;
use App\Entity\Bird;

class NAOBirdManager
{
	private $naoPagination;
	private $naoManager;

	public function __construct(NAOPagination $naoPagination, NAOManager $naoManager)
	{
		$this->naoPagination = $naoPagination;
		$this->naoManager = $naoManager;
	}

	public function getBirdsPerPage($page, $numberOfBirds, $numberOfBirdsPerPage)
	{
		$firstEntrance = $this->naoPagination->getFirstEntrance($page, $numberOfBirds, $numberOfBirdsPerPage);

		return $publishedCapturesPerPage = $this->naoManager->getEm()->getRepository(Bird::class)->getBirdsPerPage($numberOfBirdsPerPage, $firstEntrance);
	}

	public function getBirdsByLetter($letter, $page, $numberOfBirds, $numberOfBirdsPerPage)
	{
		$firstEntrance = $this->naoPagination->getFirstEntrance($page, $numberOfBirds, $numberOfBirdsPerPage);

		return $birds = $this->naoManager->getEm()->getRepository(Bird::class)->getBirdsByFirstLetter($letter, $numberOfBirdsPerPage, $firstEntrance);
	}
}