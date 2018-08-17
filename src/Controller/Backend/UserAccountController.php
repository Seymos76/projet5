<?php

namespace App\Controller\Backend;

use App\Entity\Capture;
use App\Entity\User;
use App\Entity\Comment;
use App\Form\BiographyType;
use App\Form\ChangePasswordType;
use App\Services\NAOManager;
use App\Services\Capture\NAOCaptureManager;
use App\Services\Capture\NAOCountCaptures;
use App\Services\NAOPagination;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserAccountController extends Controller
{
    /**
     * @Route("/mon-compte/{page}", defaults={"page" = 1}, name="compteUtilisateur")
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
}