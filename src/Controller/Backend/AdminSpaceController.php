<?php

namespace App\Controller\Backend;

use App\Entity\Capture;
use App\Entity\Comment;
use App\Entity\User;
use App\Services\NAOManager;
use App\Services\Capture\NAOCaptureManager;
use App\Services\Comment\NAOCommentManager;
use App\Services\Capture\NAOCountCaptures;
use App\Services\Comment\NAOCountComments;
use App\Services\NAOPagination;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminSpaceController extends Controller
{
    /**
     * @Route("/espace-administration/{page}", name="espaceAdministration", requirements={"page" = "\d+"})
     * @param Request $request
     * @return Response
     */
    public function showAdminSpaceAction($page, NAOCaptureManager $naoCaptureManager, NAOCommentManager $naoCommentManager, NAOCountCaptures $naoCountCaptures, NAOCountComments $naoCountComments, NAOPagination $naoPagination)
    {
        $user = $this->getUser();

        $roles = $user->getRoles();

        if (in_array('naturalist', $roles))
        {
            $userRole = 'Naturaliste';
        }
        elseif (in_array('administrator', $roles))
        {
            $userRole = 'Administrateur';
        }

        $numberOfPublishedCaptures = $naoCountCaptures->countPublishedCaptures();
        $nbPublishedCapturesPages = $naoPagination->CountNbPages($numberOfPublishedCaptures);
        $publishedCaptures = $naoCaptureManager->getPublishedCapturesPerPage($page, $numberOfPublishedCaptures);

        $numberOfWaitingForValidationCaptures = $naoCountCaptures->countWaitingForValidationCaptures();
        $nbWaitingForValidationCapturesPages = $naoPagination->CountNbPages($numberOfWaitingForValidationCaptures);
        $waitingForValidationCaptures = $naoCaptureManager->getWaintingForValidationCapturesPerPage($page, $numberOfWaitingForValidationCaptures);

        $numberOfPublishedComments = $naoCountComments->countPublishedComments();
        $nbPublishedCommentsPages = $naoPagination->CountNbPages($numberOfPublishedComments);
        $publishedComments = $naoCommentManager->getPublishedCommentsPerPage($page, $numberOfPublishedComments);

        $numberOfReportedComments  = $naoCountComments->countReportedComments();
        $nbReportedCommentsPages = $naoPagination->CountNbPages($numberOfReportedComments);
        $reportedComments = $naoCommentManager->getReportedCommentsPerPage($page, $numberOfReportedComments);

        $nextPage = $naoPagination->getNextPage($page);
        $previousPage = $naoPagination->getPreviousPage($page);

        return $this->render('adminspace.html.twig', array('userRole' => $userRole, 'user' => $user, 'publishedcaptures' => $publishedCaptures, 'waitingforvalidationcaptures' => $waitingForValidationCaptures, 'publishedcomments' => $publishedComments, 'reportedcomments' => $reportedComments, 'numberOfPublishedCaptures' => $numberOfPublishedCaptures, 'numberOfWaitingforvalidationCaptures' => $numberOfWaitingForValidationCaptures, 'numberOfPublishedComments' => $numberOfPublishedComments, 'numberOfReportedComments' => $numberOfReportedComments, 'page' => $page, 'nextPage' => $nextPage, 'previousPage' => $previousPage, 'nbPublishedCapturesPage' => $nbPublishedCapturesPages, 'nbWaitingForValidationCapturesPages' => $nbWaitingForValidationCapturesPages, 'nbPublishedCommentsPages' => $nbPublishedCommentsPages, 'nbReportedCommentsPages' => $nbReportedCommentsPages)); 
    }

    /**
     * @Route("/ignorer-commentaire-signale/{id}", name="ignorerCommentaireSignale", requirements={"id" = "\d+"})
     * @param Request $request
     * @return Response
     */
    public function ignoreReportedCommentAction($id, NAOCommentManager $naoCommentManager, NAOManager $naoManager)
    {
        $em = $this->getDoctrine()->getManager();
        $comment = $em->getRepository(Comment::class)->findOneById($id);

        $naoCommentManager->ignoreReportedComment($comment);
        $naoManager->addOrModifyEntity($comment);

        return $this->redirectToRoute('espaceAdministration');
    }

    /**
     * @Route("/supprimer-commentaire/{id}", name="supprimerCommentaire", requirements={"id" = "\d+"})
     * @param Request $request
     * @return Response
     */
    public function removeCommentAction($id, NAOManager $naoManager)
    {
        $em = $this->getDoctrine()->getManager();
        $comment = $em->getRepository(Comment::class)->findOneById($id);

        $naoManager->removeEntity($comment);

        return $this->redirectToRoute('espaceAdministration');
    }
}