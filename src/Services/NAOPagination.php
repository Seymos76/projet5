<?php

// src/Services/NAOPagination.php

namespace App\Services;

class NAOPagination
{
	private $nbElementsPerPage;

	public function __construct()
	{
		$this->nbElementsPerPage = '2';
	}

	public function getNbElementsPerPage()
	{
		return $this->nbElementsPerPage;
	}

	public function getNbBirdsPerPage()
	{
		return $this->nbBirdsPerPage;
	}

	public function CountNbPages($totalElements)
	{
		$nbElementsPerPage = $this->nbElementsPerPage;
		return $nbPages = ceil($totalElements/$nbElementsPerPage);
	}

	public function getFirstEntrance($page, $totalElements)
	{
		$nbElementsPerPage = $this->nbElementsPerPage;

		return $firstEntrance = ($page - 1) * $nbElementsPerPage;
	}

	public function getNextPage($page) 
	{
		return $nextp = $page + 1;
	}

	public function getPreviousPage($page)
	{
		return $previousp = $page - 1;
	}
}