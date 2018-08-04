<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SecurityController extends Controller
{
    /**
     * @Route("/admin", name="admin")
     */
    public function admin()
    {
        return $this->render(
            'security/admin.html.twig'
        );
    }
}
