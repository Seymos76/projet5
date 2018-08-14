<?php

// src/Services/NAOBirdManager.php

namespace App\Services\Bird;

use App\Services\NAOManager;
use App\Entity\Bird;

class NAOCountBirds extends NAOManager
{

	public function countBirds()
	{
		return $numberOfBirds = $this->em->getRepository(Bird::class)->countBirds();
	}

	public function countBirdsByLetter($letter)
	{
		return $numberBirdsLetter = $this->em->getRepository(Bird::class)->countBirdsByLetter($letter);
	}
}