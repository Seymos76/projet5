<?php

// src/Services/Capture/NAOCountCaptures.php

namespace App\Services\Capture;

use App\Services\NAOManager;

class NAOCountCaptures extends NAOManager
{	
	public function countPublishedCaptures()
	{
		$numberOfPublishedCaptures = $this->em->getRepository('Capture')->countByStatus('published');
		return $numberOfPublishedCaptures;
	}

	public function countDraftsCaptures()
	{
		$numberOfPublishedCaptures = $this->em->getRepository('Capture')->countByStatus('draft');
		return $numberOfPublishedCaptures;
	} 

	public function countWaitingForValidationCaptures()
	{
		$numberOfWaitingForValidationCaptures = $this->em->getRepository('Capture')->countByStatus('waiting for validation');
		return $numberOfWaitingForValidationCaptures;
	}

	public function countAuthorPublishedCaptures($author)
	{
		$numberOfPublishedCaptures = $this->em->getRepository('Capture')->countByStatusAndAuthor('published', $author);
		return $numberOfPublishedCaptures;
	}

	public function countAuthorDraftsCaptures($author)
	{
		$numberOfPublishedCaptures = $this->em->getRepository('Capture')->countByStatusAndAuthor('draft', $author);
		return $numberOfPublishedCaptures;
	}

	public function countAuthorWaintingForValidationCaptures($author)
	{
		$numberOfPublishedCaptures = $this->em->getRepository('Capture')->countByStatusAndAuthor('published', $author);
		return $numberOfPublishedCaptures;
	}

	public function countAuthorValidatedCaptures($author)
	{
		$numberOfPublishedCaptures = $this->em->getRepository('Capture')->countByStatusAndAuthor('draft', $author);
		return $numberOfPublishedCaptures;
	}
}