<?php

// src/Tests/ServicesTests/NAOP5ManagerTest.php

namespace App\Tests\ServicesTests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use App\Entity\Capture;
use App\Entity\Bird;
use App\Entity\User;
use App\Services\NAOP5Manager;

class NAOP5CaptureManagerTest extends WebTestCase
{
	public function testAddNaturalistCapture() 
	{
		$capture = new Capture();
		$capture->setContent('Nouvelle observation lors de ma promenade en vallée de Chevreuse');
		$capture->setLatitude('48.70981');
		$capture->setLongitude('2.041563');
		$capture->setAddress('Chemin Jean Racine');
		$capture->setZipcode('78460');
		$capture->setCity('Chevreuse');
		$capture->setPublished(true);
		$capture->setDraft(false);

		$bird = new Bird();
		$capture->setBird($bird);

		$user = new user();
		$capture->setUser($user);
		$capture->setValidatedBy($user);

 		$kernel = static::createKernel();
        $kernel->boot();
        $container = $kernel->getContainer();

        $service = $container->get(NAOP5Manager::class);

 		$result = $service->addOrModifyEntity($capture);

		$this->assertSame(true, $result);
	}
}