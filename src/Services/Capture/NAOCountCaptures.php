<?php

// src/Services/Capture/NAOCountCaptures.php

namespace App\Services\Capture;

use App\Services\NAOManager;
use App\Entity\Capture;

class NAOCountCaptures extends NAOManager
{	
	public function countPublishedCaptures()
	{
		return $numberOfPublishedCaptures = $this->em->getRepository(Capture::class)->countPublishedCaptures();
	}

	public function countDraftsCaptures()
	{
		return $numberOfPublishedCaptures = $this->em->getRepository(Capture::class)->countByStatus('draft');
	} 

	public function countWaitingForValidationCaptures()
	{
		return $numberOfWaitingForValidationCaptures = $this->em->getRepository(Capture::class)->countByStatus('waiting for validation');
	}

	public function countAuthorPublishedCaptures($author)
	{
		return $numberOfPublishedCaptures = $this->em->getRepository(Capture::class)->countByStatusAndAuthor('published', $author);
	}

	public function countAuthorDraftsCaptures($author)
	{
		return $numberOfPublishedCaptures = $this->em->getRepository(Capture::class)->countByStatusAndAuthor('draft', $author);
	}

	public function countAuthorWaintingForValidationCaptures($author)
	{
		return $numberOfPublishedCaptures = $this->em->getRepository(Capture::class)->countByStatusAndAuthor('published', $author);
	}

	public function countAuthorValidatedCaptures($author)
	{
		return $numberOfPublishedCaptures = $this->em->getRepository(Capture::class)->countByStatusAndAuthor('draft', $author);
	}

	public function countUserCaptures($id)
	{
		return $numberOfCaptures = $this->em->getRepository(Capture::class)->countAuthorCapture($id);
	}

	public function countSearchCapturesByBirdAndRegion($bird, $region)
	{
		return $numberSearchCapturesByBirdAndRegion = $this->em->getRepository(Capture::class)->countSearchCapturesByBirdAndRegion($bird, $region);
	}
}