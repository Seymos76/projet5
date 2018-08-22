<?php

namespace App\Controller\Backend;

use App\Entity\User;
use App\Form\Password\ChangePassword;
use App\Form\Password\ChangePasswordType;
use App\Form\Password\LostPasswordType;
use App\Form\Password\ReinitialisationPasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class PasswordController extends Controller
{
    /**
     * @Route("/mot-de-passe-oublie", name="lost_password")
     * @param Request $request
     * @return Response
     */
    public function lostPassword(Request $request)
    {
        $lost_password_form = $this->createForm(LostPasswordType::class);
        $lost_password_form->handleRequest($request);
        if ($lost_password_form->isSubmitted() && $lost_password_form->isValid()) {
            $em = $this->getDoctrine();
            // récupérer l'utilisateur correspondant
            $user = $em->getRepository(User::class)->findUserByEmail($lost_password_form->getData()['email']);
            // si !user -> rediriger vers register avec message erreur
            if (!$user) {
                $this->get('session')->getFlashBag()->add('error', "Vous n'êtes pas enregistré sur le site");
                return $this->redirectToRoute('register');
            }
            $user->setToken(md5(uniqid("token_", true)));
            $this->get('app.nao.mailer')->sendLostPasswordEmail($user);
            $this->get('app.nao_manager')->addOrModifyEntity($user);
            // redirect to confirmation page
            return $this->redirectToRoute('password_reinitialisation_pending');
        }
        return $this->render(
            'password/lost_password.html.twig',
            array(
                'form' => $lost_password_form->createView()
            )
        );
    }

    /**
     * @Route("/reinitialisation-en-attente", name="password_reinitialisation_pending")
     * @return Response
     */
    public function passwordReinitialisationPending()
    {
        return $this->render(
            'password/pending.html.twig'
        );
    }

    /**
     * @Route("/password-reinitialisation/{token}", name="password_reinitialisation")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function reinitialisationPassword(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $em = $this->getDoctrine();
        $token = $request->get('token');
        $user = $em->getRepository(User::class)->findUserByToken($token);
        if (!$user) {
            $this->get('session')->getFlashBag()->add('error', "Vous n'êtes pas autorisé à accéder à cette page !");
            return $this->redirectToRoute('index');
        }
        $form = $this->createForm(ReinitialisationPasswordType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // encoder le mot de passe
            if ($user instanceof UserInterface) {
                $encoded = $encoder->encodePassword($user, $form->getData()->getPassword());
                $user = $this->getDoctrine()->getRepository(User::class)->findUserByToken($token);
                $user->setPassword($encoded);
                $user->setToken(null);
                $this->get('app.nao_manager')->addOrModifyEntity($user);
                $this->get('app.nao.mailer')->sendPasswordReinitialisationSuccessEmail($user);
                $this->get('session')->getFlashBag()->add('success', "Votre mot de passe a bien été mis à jour !");
                return $this->redirectToRoute('login');
            } else {
                $this->get('session')->getFlashBag()->add('error', "Un problème est survenu durant la réinitialisation du mot de passe.");
                return $this->redirectToRoute('lost_password');
            }
        }
        return $this->render(
            'password/reinitialisation_password.html.twig',
            array(
                'form' => $form->createView()
            )
        );
    }
}