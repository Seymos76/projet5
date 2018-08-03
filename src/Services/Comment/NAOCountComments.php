<?php

// src/Services/Comment/NAOCountComments.php

namespace App\Services\Comment;

use App\Services\NAOManager;

class NAOCountComments extends NAOManager
{	
	public function countPublishedComments()
	{
		$numberOfPublishedComments = $this->em->getRepository('Comment')->countPublishedOrReportedComments(true);
		return $numberOfPublishedComments;
	}

	public function countReportedComments()
	{
		$numberOfReportedComments = $this->em->getRepository('Comment')->countPublishedOrReportedComments(false);
		return $numberOfReportedComments;
	} 
}