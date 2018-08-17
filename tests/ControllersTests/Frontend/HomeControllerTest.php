<?php

// tests/ControllersTests/Frontend/HomeControllerTest.php

namespace App\tests\ControllersTests\Frontend;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use App\Entity\Capture;

class HomeControllerTest extends WebTestCase
{
	private $lastCaptures;
	private $naoShowMap;

	/**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();

        $this->lastCaptures = $this->entityManager->getRepository(Capture::class)->getLastPublishedCaptures('4');

        $this->naoShowMap = $kernel->getContainer()->get('app.nao_show_map');

        $this->client = static::createClient();
    }

    public function testShowHomePage()
    {
        $crawler = $this->client->request('GET', '/');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($crawler->filter('h1:contains("Nos amis les oiseaux")')->count() > 0);
        $this->assertTrue($crawler->filter('h2:contains("Les dernières observations")')->count() > 0);
    }

    public function testGetFormatedLastCpatures()
    {
    	$jsonLastPublishedCapture = $this->naoShowMap->formatPublishedCaptures($this->lastCaptures);

        $this->client->request('GET', '/api/lastcaptures', array('content' => $jsonLastPublishedCapture));
		$response = $this->client->getResponse();
		$this->assertSame(200, $this->client->getResponse()->getStatusCode());
		$this->assertSame('application/json', $response->headers->get('Content-Type'));
		$this->assertNotEmpty($this->client->getResponse()->getContent());
    }


	/**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null; 
    }
}