<?php

// src/Tests/ServicesTests/Capture/NAOCaptureManagerTest.php

namespace App\Tests\ServicesTests\Capture;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use App\Entity\Capture;
use App\Services\Capture\NAOCaptureManager;

class NAOCaptureManagerTest extends WebTestCase
{
    public function testValidateCapture()
    {
        $capture = new Capture();
        $capture->setStatus('waiting for validation');

        $kernel = static::createKernel();
        $kernel->boot();
        $container = $kernel->getContainer();

        $service = $container->get(NAOP5CaptureManager::class);
        $service->validateCapture($capture);

       $this->assertSame('validated', $capture->getStatus()); 
    }
}