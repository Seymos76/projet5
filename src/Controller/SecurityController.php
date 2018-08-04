<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

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

    /**
     * @Route("/login", name="login")
     * @param AuthenticationUtils $authenticationUtils
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $last_username = $authenticationUtils->getLastUsername();
        return $this->render(
            'security/login.html.twig',
            array(
                'error' => $error,
                'last_username' => $last_username
            )
        );
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout() {}
}
