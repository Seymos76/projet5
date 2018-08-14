<?php

namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     * @Method("POST")
     * @param Request $request
     * @param AuthenticationUtils $authenticationUtils
     * @return JsonResponse
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils)
    {
        $last_username = $authenticationUtils->getLastUsername();
        $errors = $authenticationUtils->getLastAuthenticationError();
        return new JsonResponse();
    }

    public function register()
    {
        $user = new User();
    }
}
