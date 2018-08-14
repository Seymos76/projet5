<?php

// tests/RepositoriesTests/Comment/CommentRepositoryTest.php
namespace App\Tests\RepositoriesTests\Comment;

use App\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CommentRepositoryTest extends KernelTestCase
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

    public function testSearchByPublishedTrue()
    {
        $publishedComments = $this->entityManager
            ->getRepository(Comment::class)
            ->findByPublished(true)
        ;

        $this->assertCount(2, $publishedComments);
    }

    public function testSearchByPublishedFalse()
    {
        $reportedComments = $this->entityManager
            ->getRepository(Comment::class)
            ->findByPublished(false)
        ;

        $this->assertCount(1, $reportedComments);
    }

    public function testCountPublishedComments()
    {
        $numberOfPublishedComments = $this->entityManager
            ->getRepository(Comment::class)
            ->countPublishedOrReportedComments(true)
        ;

        $this->assertEquals(2, $numberOfPublishedComments);
    }

    public function testCountReportedComments()
    {
        $numberOfReportedComments = $this->entityManager
            ->getRepository(Comment::class)
            ->countPublishedOrReportedComments(false)
        ;

        $this->assertEquals(1, $numberOfReportedComments);
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