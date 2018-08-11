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

	public function CountNbPages($totalElements)
	{
		$nbElementsPerPage = $this->nbElementsPerPage;
		return $nbPages = ceil($totalElements/$nbElementsPerPage);
	}

	public function getCurrentPage($page, $totalElements)
	{
		$nbElementsPerPage = $this->nbElementsPerPage;

		if(isset($page)) 
		{
    		$currentPage = intval($page);
 
     		if(($currentPage * $nbElementsPerPage - $nbElementsPerPage) > $totalElements)
     		{
				$currentPage = 1;
     		}
		}
		else 
		{
    		$currentPage = 1;     
		}

		return $currentPage;
	}

	public function getFirstEntrance($page, $totalElements)
	{
		$nbElementsPerPage = $this->nbElementsPerPage;

		$currentPage = $this->getCurrentPage($page, $totalElements, $nbElementsPerPage);

		return $firstEntrance = ($currentPage - 1) * $nbElementsPerPage;
	}

	public function getNextPage($page) 
	{
		if (isset($page))
		{
			return $nextp = $page + 1;
		}
	}

	public function getPreviousPage($page)
	{
		if (isset($page))
		{
			return $previousp = $page - 1;
		}
	}
}