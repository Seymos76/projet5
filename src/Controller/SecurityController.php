<?php
/**
 * Created by PhpStorm.
 * User: seymos
 * Date: 02/08/18
 * Time: 18:29
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use App\Services\FileUploader;
use App\Services\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        $lastusername = $authenticationUtils->getLastUsername();
        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render(
            "security/login.html.twig",
            array(
                'last_username' => $lastusername,
                'error' => $error
            )
        );
    }

    /**
     * @Route("/register", name="register")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function register(Request $request, FileUploader $fileUploader)
    {
        $user = new User();
        $register_form = $this->createForm(RegisterType::class, $user);
        $register_form->handleRequest($request);
        if ($register_form->isSubmitted() && $register_form->isValid()) {
            // service vérification data facultatives
            $file = $user->getAvatar();
            dump($register_form->getData());
            die('register form submitted');
        }

        return $this->render(
            "security/register.html.twig",
            array(
                'form' => $register_form->createView()
            )
        );
    }

    public function changePassword() {}
    public function askForPasswordReinit() {}
    public function passwordReinit() {}
}
