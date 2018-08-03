<?php

// src/Services/NAOP5Manager.php

namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;

class NAOP5Manager
{
	protected $em;
 
	public function __construct(EntityManagerInterface $em)
	{
	    $this->em = $em;
	}

	public function addOrModifyEntity($entity)
	{
 		$this->em->persist($entity);
		$this->em->flush();
	}

	public function removeEntity($entity)
	{
		$this->em->remove($entity);
		$this->em->flush();
	}
}