<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePassword;
use App\Form\LostPasswordType;
use App\Form\ReinitialisationPasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordController extends Controller
{
    /**
     * @Route("/lost-password", name="lost_password")
     * @param Request $request
     * @return Response
     */
    public function lostPassword(Request $request)
    {
        $lost_password_form = $this->createForm(LostPasswordType::class);
        $lost_password_form->handleRequest($request);
        if ($lost_password_form->isSubmitted() && $lost_password_form->isValid()) {
            dump($lost_password_form->getData());
            // récupérer l'utilisateur correspondant
            // si user -> send email with link+token
            // save token to user in database
            // redirect to confirmation page
            return $this->redirectToRoute('lost_password_email_sent');
        }
        return $this->render(
            'password/lost_password.html.twig',
            array(
                'form' => $lost_password_form->createView()
            )
        );
    }

    /**
     * @Route("/password-reinitialisation", name="password_reinitialisation")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function reinitialisationPassword(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $form = $this->createForm(ReinitialisationPasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            dump($form->getData()['new_password']);
            // récupérer l'utilisateur courant
            // encoder le mot de passe
            // mettre à jour la base de données
            // réinitialiser le token de l'utilisateur
            die;
        }
        return $this->render(
            'password/reinitialisation_password.html.twig',
            array(
                'form' => $form->createView()
            )
        );
    }

    /**
     * @Route("/lost-password-email-sent", name="lost_password_email_sent")
     * @return Response
     */
    public function lostPasswordEmailSent()
    {
        return $this->render(
            'password/lost_password_email_sent.html.twig'
        );
    }

    /**
     * @Route("/change-password", name="change_password")
     * @param Request $request
     * @return Response
     */
    public function changePassword(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $form = $this->createForm(ChangePassword::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(
                array(
                    'email' => $this->getUser()->getEmail()
                )
            );
            $new_password = $encoder->encodePassword($this->getUser(), $form->getData()['new_password']);
            $user->setPassword($new_password);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('admin');
        }
        return $this->render(
            'password/change_password.html.twig',
            array(
                'form' => $form->createView()
            )
        );
    }
}
