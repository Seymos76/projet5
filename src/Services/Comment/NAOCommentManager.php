<?php

// src/Services/Comment/NAOCommentManager.php

namespace App\Services\Comment;

use App\Services\NAOManager;
use App\Entity\Comment;

class NAOCommentManager extends NAOManager
{
	public function getPublishedComments()
	{
		$publishedComments = $this->em->getRepository('Comment')->findByPublished(true);

        return $publishedComments;
	}

	public function getReportedComments()
	{
		$reportedComments = $this->em->getRepository('Comment')->findByPublished(false);

		return $reportedComments;
	}

	public function reportComment(Comment $comment)
	{
		$comment->setPublished(false);
	}

	public function ignoreReportedComment(Comment $comment)
	{
		$comment->setPublished(true);
	}
}