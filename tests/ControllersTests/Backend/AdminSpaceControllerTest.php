<?php

// tests/ControllersTests/Backend/AdminSpaceControllerTest.php

namespace App\tests\ControllersTests\Backend;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminSpaceControllerTest extends WebTestCase
{
    public function testShowAdminSpace()
    {
        $client = static::createClient();

        $client->request('GET', '/espace-administration/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testShowNextPublishedCaptures()
    {
        $client = static::createClient();

        $client->request('GET', '/espace-administration/observations-publiees');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testShowNextPublishedCapturesPageTwo()
    {
        $client = static::createClient();

        $client->request('GET', '/espace-administration/observations-publiees/2');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testShowNextWaitingCaptures()
    {
        $client = static::createClient();

        $client->request('GET', '/espace-administration/observations-en-attente');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testShowNextWaintingCapturesPageTwo()
    {
        $client = static::createClient();

        $client->request('GET', '/espace-administration/observations-en-attente/2');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testShowNexPublishedComments()
    {
        $client = static::createClient();

        $client->request('GET', '/espace-administration/commentaires-publies');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testShowNextPublishedCommentsPageTwo()
    {
        $client = static::createClient();

        $client->request('GET', '/espace-administration/commentaires-publies/2');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testShowNexReportedComments()
    {
        $client = static::createClient();

        $client->request('GET', '/espace-administration/commentaires-signales');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testShowNextReportedCommentsPageTwo()
    {
        $client = static::createClient();

        $client->request('GET', '/espace-administration/commentaires-signales/2');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testIgnoreReportedComment()
    {
        $client = static::createClient();

        $client->request('GET', '/ignorer-commentaire-signale/4');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testRemoveComment()
    {
        $client = static::createClient();

        $client->request('GET', '/supprimer-commentaire/4');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}