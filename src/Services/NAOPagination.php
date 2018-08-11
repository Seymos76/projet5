<?php

// src/Services/NAOPagination.php

namespace App\Services;

class NAOPagination
{
	public function CountNbPages($totalElements, $elementsPerPage)
	{
		return $nbPages = ceil($totalElements/$elementsPerPage);
	}

	public function getCurrentPage($page, $totalElements, $elementsPerPage)
	{
		if(isset($page)) 
		{
    		$currentPage = intval($page);
 
     		if(($currentPage * $elementsPerPage - $elementsPerPage) > $totalElements)
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

	public function getFirstEntrance($page, $totalElements, $elementsPerPage)
	{
		$currentPage = $this->getCurrentPage($page, $totalElements, $elementsPerPage);

		return $firstEntrance = ($currentPage - 1) * $elementsPerPage;
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