<?php

// src/Services/Comment/NAOCountComments.php

namespace App\Services\Comment;

use App\Services\NAOManager;
use App\Entity\Comment;

class NAOCountComments extends NAOManager
{	
	public function countPublishedComments()
	{
		return $numberOfPublishedComments = $this->em->getRepository(Comment::class)->countPublishedOrReportedComments(true);
	}

	public function countReportedComments()
	{
		return $numberOfReportedComments = $this->em->getRepository(Comment::class)->countPublishedOrReportedComments(false);
	} 

	public function countCapturePublishedComments($capture)
	{
		return $captureComments = $this->em->getRepository(Comment::class)->countCaptureCommentsByStatus($capture, true);
	}
}