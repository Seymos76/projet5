<?php

namespace App\Controller\Backend;

use App\Entity\Capture;
use App\Entity\User;
use App\Entity\Comment;
use App\Form\AvatarType;
use App\Form\BiographyType;
use App\Form\ChangePasswordType;
use App\Services\NAOManager;
use App\Services\Capture\NAOCaptureManager;
use App\Services\Capture\NAOCountCaptures;
use App\Services\NAOPagination;

use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UserAccountController
 * @package App\Controller\Backend
 * @Route(path="/mon-compte")
 */
class UserAccountController extends Controller
{
    /**
     * @Route("/{page}", defaults={"page" = 1}, name="compteUtilisateur")
     * @param NAOPagination $naoPagination
     * @param NAOCaptureManager $naoCaptureManager
     * @param NAOCountCaptures $naoCountCaptures
     * @param Request $request
     * @return Response
     */
    public function showUserAccount($page, NAOPagination $naoPagination, NAOCaptureManager $naoCaptureManager, NAOCountCaptures $naoCountCaptures, Request $request)
    {
    	$user = $this->getUser();

        $numberOfElementsPerPage = $naoPagination->getNbElementsPerPage();
        $numberOfUserCaptures = $naoCountCaptures->countUserCaptures($user);
        $numberOfUserCapturesPages = $naoPagination->CountNbPages($numberOfUserCaptures, $numberOfElementsPerPage);

        $captures = $naoCaptureManager->getUserCapturesPerPage($page, $numberOfUserCaptures, $numberOfElementsPerPage, $user);

        $nextPage = $naoPagination->getNextPage($page);
        $previousPage = $naoPagination->getPreviousPage($page);

        $biographyType = $this->createForm(BiographyType::class);
        $user = $this->getDoctrine()->getRepository(User::class)->findUserByEmail($this->getUser()->getEmail());
        $biographyType->handleRequest($request);
        $account_type = $this->get('app.nao_user_manager')->getRoleFR($user);

        if ($biographyType->isSubmitted() && $biographyType->isValid()) {
            $this->get('app.nao_user_manager')->changeBiography($user, $biographyType->getData()['biography']);
            return $this->redirectToRoute('compteUtilisateur');
        }

        $changePasswordType = $this->createForm(ChangePasswordType::class);
        $changePasswordType->handleRequest($request);
        if ($changePasswordType->isSubmitted() && $changePasswordType->isValid()) {
            $this->get('app.nao_password_manager')->changePassword($user, $changePasswordType->getData()['new_password']);
            return $this->redirectToRoute('compteUtilisateur');
        }

    	return $this->render(
    	    'account/account.html.twig',
            array(
                'captures' => $captures,
                'account_type' => $account_type,
                'biography_form' => $biographyType->createView(),
                'change_password_form' => $changePasswordType->createView(),
                'page' => $page,
                'nbCapturesPages' => $numberOfUserCapturesPages,
                'previousPage' => $previousPage,
                'nextPage' => $nextPage
            )
        );
    }
    /**
     * @Route("/change-avatar", name="change_avatar")
     * @param Request $request
     * @throws \Exception
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
                $this->get('app.image_manager')->removeCurrentAvatar($this->getUser()->getUsername());
            }
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $avatar_form->getData()['avatar'];
            $image = $this->get('app.image_manager')->buildImage($uploadedFile, $this->getParameter('avatar_directory'));
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

    /**
     * @Route(path="/upgrade-naturalist/{username}", name="upgrade_to_naturalist")
     * @ParamConverter("user", class="App\Entity\User")
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function upgradeToNaturalist(User $user)
    {
        $user->addRole(array("ROLE_NATURALIST"));
        $this->get('app.nao_manager')->addOrModifyEntity($user);
        return $this->redirectToRoute('compteUtilisateur');
    }

    /**
     * @Route(path="/upgrade-admin/{username}", name="upgrade_to_admin")
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function upgradeToAdmin(User $user)
    {
        $user->addRole(array("ROLE_ADMIN"));
        $this->get('app.nao_manager')->addOrModifyEntity($user);
        return $this->redirectToRoute('compteUtilisateur');
    }
}
