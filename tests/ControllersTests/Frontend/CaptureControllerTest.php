<?php

// tests/ControllersTests/Frontend/CaptureControllerTest.php

namespace App\tests\ControllersTests\Frontend;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CaptureControllerTest extends WebTestCase
{
    public function testShowCapture()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/observation/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertTrue($crawler->filter('h1:contains(\'"Epervier bicolore "\')')->count() > 0);
    }

    public function testShowCaptures()
    {
        $client = static::createClient();

        $client->request('GET', '/observations');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testShowCapturesPageTwo()
    {
        $client = static::createClient();

        $client->request('GET', '/observations/2');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}