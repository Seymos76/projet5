<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePassword;
use App\Form\ChangePasswordType;
use App\Form\LostPasswordType;
use App\Form\ReinitialisationPasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

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
            $em = $this->getDoctrine();
            // récupérer l'utilisateur correspondant
            $user = $em->getRepository(User::class)->findUserByEmail($lost_password_form->getData());
            // si !user -> rediriger vers register avec message erreur
            if (!$user) {
                $this->get('session')->getFlashBag()->add('error', "Vous n'êtes pas enregistré sur le site");
                return $this->redirectToRoute('register');
            }
            // si user -> send email with link+token
            $user->setToken(md5(uniqid("token_", true)));
            $this->get('app.nao_manager')->addOrModifyEntity($user);
            // redirect to confirmation page
            return $this->redirectToRoute('password_reinitialisation', array('token' => $user->getToken()));
        }
        return $this->render(
            'password/lost_password.html.twig',
            array(
                'form' => $lost_password_form->createView()
            )
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

    /**
     * @Route("/change-password", name="change_password")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function changePassword(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getDoctrine()->getRepository(User::class)->findUserByEmail($this->getUser()->getEmail());
            $new_password = $encoder->encodePassword($this->getUser(), $form->getData()['new_password']);
            $user->setPassword($new_password);
            $this->get('app.nao_manager')->addOrModifyEntity($user);
            return $this->redirectToRoute('naturalist_account');
        }
        return $this->render(
            'password/change_password.html.twig',
            array(
                'form' => $form->createView()
            )
        );
    }
}
