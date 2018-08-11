<?php

// src/Services/Comment/NAOCommentManager.php

namespace App\Services\Comment;

use App\Services\NAOManager;
use App\Services\NAOPagination;
use App\Entity\Comment;

class NAOCommentManager extends NAOManager
{
	private $naoPagination;
	private $naoManager;

	public function __construct(NAOPagination $naoPagination, NAOManager $naoManager)
	{
		$this->naoManager = $naoManager;
		$this->naoPagination = $naoPagination;
	}

	public function getPublishedComments()
	{
		return $publishedComments = $this->naoManager->getEm()->getRepository(Comment::class)->findByPublished(true);
	}

	public function getPublishedCommentsPerPage($page, $numberOfPublishedComments)
	{
		$nbElementsPerPage = $this->naoPagination->getNbElementsPerPage();
		$firstEntrance = $this->naoPagination->getFirstEntrance($page, $numberOfPublishedComments);

		return $publishedCommentsPerPage = $this->naoManager->getEm()->getRepository(Comment::class)->getCommentsByStatusPerPage(true, $nbElementsPerPage, $firstEntrance);
	}

	public function getReportedComments()
	{
		return $reportedComments = $this->naoManager->getEm()->getRepository(Comment::class)->findByPublished(false);
	}

	public function getReportedCommentsPerPage($page, $numberOfReportedComments)
	{
		$nbElementsPerPage = $this->naoPagination->getNbElementsPerPage();
		$firstEntrance = $this->naoPagination->getFirstEntrance($page, $numberOfReportedComments);

		return $reportedCommentsPerPage = $this->naoManager->getEm()->getRepository(Comment::class)->getCommentsByStatusPerPage(false, $nbElementsPerPage, $firstEntrance);
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