<?php

// tests/RepositoriesTests/Capture/CaptureRepositoryTest.php
namespace App\Tests\RepositoriesTests\Capture;

use App\Entity\Capture;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CaptureRepositoryTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testGetPublishedCaptures()
    {
        $publishedCaptures = $this->entityManager
            ->getRepository(Capture::class)
            ->getPublishedCaptures()
        ;

        $this->assertCount(2, $publishedCaptures);
    }

    public function testCountByStatus()
    {
        $numberOfWaitingForValidationCaptures = $this->entityManager
            ->getRepository(Capture::class)
            ->countByStatus('waiting for validation')
        ;

        $this->assertEquals(1, $numberOfWaitingForValidationCaptures);
    }

    public function testCountPublishedCaptures()
    {
        $numberOfPublishedCaptures = $this->entityManager
            ->getRepository(Capture::class)
            ->countPublishedCaptures()
        ;

        $this->assertEquals(2, $numberOfPublishedCaptures);
    }

    public function testCountPublishedAuthorCaptures()
    {
        $user = $this->entityManager
            ->getRepository(User::class)
            ->findByUsername('username3');

        $numberOfAuthor3Captures = $this->entityManager
            ->getRepository(Capture::class)
            ->countByStatusAndAuthor('published', $user)
        ;

        $this->assertEquals(1, $numberOfAuthor3Captures);
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leaks
    }
}