<?php

// src/Services/Comment/NAOCommentManager.php

namespace App\Services\Comment;

use App\Services\NAOManager;
use App\Entity\Comment;

class NAOCommentManager extends NAOManager
{
	public function getPublishedComments()
	{
		return $publishedComments = $this->em->getRepository(Comment::class)->findByPublished(true);
	}

	public function getReportedComments()
	{
		return $reportedComments = $this->em->getRepository(Comment::class)->findByPublished(false);
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