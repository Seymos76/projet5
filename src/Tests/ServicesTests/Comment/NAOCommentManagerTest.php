<?php

// src/Tests/ServicesTests/Comment/NAOCommentManagerTest.php

namespace App\Tests\ServicesTests\Comment;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use App\Entity\Comment;
use App\Services\Comment\NAOCommentManager;

class NAOCommentManagerTest extends WebTestCase
{
    public function testReportComment()
    {
        $comment = new Comment();
        $comment->setPublished(true);

        $kernel = static::createKernel();
        $kernel->boot();
        $container = $kernel->getContainer();

        $service = $container->get(NAOP5CommentManager::class);
        $service->reportComment($comment);

       $this->assertFalse($comment->getPublished()); 
    }

    public function testIgnoreReportedComment()
    {
        $comment = new Comment();
        $comment->setPublished(false);

        $kernel = static::createKernel();
        $kernel->boot();
        $container = $kernel->getContainer();

        $service = $container->get(NAOP5CommentManager::class);
        $service->ignoreReportedComment($comment);

       $this->assertTrue($comment->getPublished()); 
    }
}