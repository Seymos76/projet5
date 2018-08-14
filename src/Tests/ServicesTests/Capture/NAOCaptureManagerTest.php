<?php

// src/Tests/ServicesTests/Capture/NAOCaptureManagerTest.php

namespace App\Tests\ServicesTests\Capture;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use App\Entity\Capture;
use App\Entity\User;
use App\Services\Capture\NAOCaptureManager;

class NAOCaptureManagerTest extends WebTestCase
{
    public function testValidateCapture()
    {
        $capture = new Capture();
        $capture->setStatus('waiting for validation');

        $user = new User();
        $capture->setValidatedBy($user);

        $kernel = static::createKernel();
        $kernel->boot();
        $container = $kernel->getContainer();

        $service = $container->get(NAOCaptureManager::class);
        $service->validateCapture($capture, $user);

       $this->assertSame('validated', $capture->getStatus()); 
    }
}