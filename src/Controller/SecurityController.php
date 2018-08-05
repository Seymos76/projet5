<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @Route("/admin", name="admin")
     */
    public function admin()
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        return $this->render(
            'security/admin.html.twig',
            array(
                'user' => $this->getUser()
            )
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

    /**
     * @Route("/register", name="register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $register_form = $this->createForm(RegisterType::class, $user);
        $register_form->handleRequest($request);
        if ($register_form->isSubmitted() && $register_form->isValid()) {
            $user->setUsername($register_form->getData()->getEmail());
            if ($register_form->getData()->getAccountType() === "naturalist") {
                $user->addRole("ROLE_NATURALIST");
            }
            $encoded = $encoder->encodePassword($user, $register_form->getData()->getPassword());
            $user->setPassword($encoded);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', "Votre compte a été créé, veuillez confirmer votre adresse e-mail !");
            return $this->redirectToRoute('activation_code');
        }
        return $this->render(
            'security/register.html.twig',
            array(
                'form' => $register_form->createView()
            )
        );
    }

    /**
     * @Route("/activation-code", name="activation_code")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function activationCode(Request $request)
    {
        if ($request->isMethod("POST")) {
            $activation_code = $request->get('activation_code');
            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(
                array(
                    'activation_code' => $activation_code
                )
            );
            if (!$user) {
                $this->get('session')->getFlashBag()->add('error', "Ce code n'est pas valide.");
                return $this->redirectToRoute('login');
            }
            $user->setActivationCode(null);
            $user->setActive(true);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', "Votre compte a bien été activé !");
            return $this->redirectToRoute('admin');
        }
        return $this->render(
            'security/activation_code.html.twig'
        );
    }
}
