<?php
/**
 * Created by PhpStorm.
 * User: seymos
 * Date: 08/08/18
 * Time: 16:36
 */

namespace App\Controller;

use App\Entity\Image;
use App\Entity\User;
use App\Form\AvatarType;
use App\Form\BiographyType;
use App\Form\ChangePasswordType;
use App\Services\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class AccountController
 * @package App\Controller
 * @Route("/account")
 */
class AccountController extends Controller
{
    /**
     * @Route("/", name="account")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function account(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $biographyType = $this->createForm(BiographyType::class);
        $user = $this->getDoctrine()->getRepository(User::class)->findUserByEmail($this->getUser()->getEmail());
        $biographyType->handleRequest($request);
        $account_type = $this->get('app.nao_user_manager')->getRoleFR($user);
        if ($biographyType->isSubmitted() && $biographyType->isValid()) {
            $new_biography = $biographyType->getData()['biography'];
            $user->setBiography($new_biography);
            $this->get('app.nao_manager')->addOrModifyEntity($user);
            $this->get('session')->getFlashBag()->add('success', "Votre biographie a été changée avec succès !");
            return $this->redirectToRoute('account');
        }
        $changePasswordType = $this->createForm(ChangePasswordType::class);
        $changePasswordType->handleRequest($request);
        if ($changePasswordType->isSubmitted() && $changePasswordType->isValid()) {
            $encoded = $encoder->encodePassword($user, $changePasswordType->getData()['new_password']);
            $user->setPassword($encoded);
            $this->get('app.nao_manager')->addOrModifyEntity($user);
            $this->get('app.nao.mailer')->sendConfirmationPasswordChanged($user);
            $this->get('session')->getFlashBag()->add('success', "Votre mot de passe a été changé avec succès !");
            return $this->redirectToRoute('account');
        }
        return $this->render(
            'account/account.html.twig',
            array(
                'user' => $user,
                'account_type' => $account_type,
                'observations' => $observations ?? null,
                'biography_form' => $biographyType->createView(),
                'change_password_form' => $changePasswordType->createView()
            )
        );
    }

    /**
     * @Route("/change-avatar", name="change_avatar")
     * @param Request $request
     * @return Response
     */
    public function changeAvatar(Request $request)
    {
        $user = $this->getUser();
        $avatar_form = $this->createForm(AvatarType::class);
        $avatar_form->handleRequest($request);
        if ($avatar_form->isSubmitted() && $avatar_form->isValid()) {
            // check if image exists
            if ($user->getAvatar() !== null) {
                $this->get('app.avatar_service')->removeCurrentAvatar($this->getUser()->getUsername());
            }
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $avatar_form->getData()['avatar'];
            $image = $this->get('app.avatar_service')->buildImage($uploadedFile, $this->getParameter('avatar_directory'));
            $user->setAvatar($image);
            $this->get('app.nao_manager')->addOrModifyEntity($user);
            $this->get('session')->getFlashBag()->add('success', "Votre avatar a bien été changé !");
            return $this->redirectToRoute('account');
        }
        return $this->render(
            'account/change_avatar.html.twig',
            array(
                'form' => $avatar_form->createView()
            )
        );
    }
}
