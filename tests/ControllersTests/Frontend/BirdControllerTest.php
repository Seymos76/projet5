<?php

// tests/ControllersTests/Frontend/BirdControllerTest.php

namespace App\tests\ControllersTests\Frontend;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BirdControllerTest extends WebTestCase
{
    public function testShowRepertory()
    {
        $client = static::createClient();

        $client->request('GET', '/repertoire');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testShowRepertoryPageTwo()
    {
        $client = static::createClient();

        $client->request('GET', '/repertoire/2');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testShowRepertoryByLetter()
    {
        $client = static::createClient();

        $client->request('GET', '/repertoire/a');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testShowRepertoryByLetterPageTwo()
    {
        $client = static::createClient();

        $client->request('GET', '/repertoire/a/2');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testShowBird()
    {
        $client = static::createClient();

        $client->request('GET', '/oiseau/1/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}