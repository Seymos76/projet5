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
     * @Route("/espace-administration/", name="espaceAdministration")
     * @param Request $request
     * @return Response
     */
    public function showAdminSpaceAction(NAOCaptureManager $naoCaptureManager, NAOCommentManager $naoCommentManager, NAOCountCaptures $naoCountCaptures, NAOCountComments $naoCountComments, NAOPagination $naoPagination)
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

        $page = '1';
        $numberOfElementsPerPage = $naoPagination->getNbElementsPerPage();

        $numberOfPublishedCaptures = $naoCountCaptures->countPublishedCaptures();
        $publishedCaptures = $naoCaptureManager->getPublishedCapturesPerPage($page, $numberOfPublishedCaptures);

        $numberOfWaitingForValidationCaptures = $naoCountCaptures->countWaitingForValidationCaptures();
        $waitingForValidationCaptures = $naoCaptureManager->getWaintingForValidationCapturesPerPage($page, $numberOfWaitingForValidationCaptures);

        $numberOfPublishedComments = $naoCountComments->countPublishedComments();
        $publishedComments = $naoCommentManager->getPublishedCommentsPerPage($page, $numberOfPublishedComments);

        $numberOfReportedComments  = $naoCountComments->countReportedComments();
        $reportedComments = $naoCommentManager->getReportedCommentsPerPage($page, $numberOfReportedComments);

        return $this->render('AdminSpace\adminspace.html.twig', array('userRole' => $userRole, 'user' => $user, 'publishedcaptures' => $publishedCaptures, 'waitingforvalidationcaptures' => $waitingForValidationCaptures, 'publishedcomments' => $publishedComments, 'reportedcomments' => $reportedComments, 'numberOfPublishedCaptures' => $numberOfPublishedCaptures, 'numberOfWaitingforvalidationCaptures' => $numberOfWaitingForValidationCaptures, 'numberOfPublishedComments' => $numberOfPublishedComments, 'numberOfReportedComments' => $numberOfReportedComments, 'page' => $page, 'numberOfElementsPerPage' => $numberOfElementsPerPage)); 
    }

    /**
     * @Route("/espace-administration/observations-publiees/{page}", name="espaceAdminObservationsPubliees", requirements={"page" = "\d+"})
     * @param Request $request
     * @return Response
     */
    public function showNextPublishedCapturesAction($page, NAOCaptureManager $naoCaptureManager, NAOCommentManager $naoCommentManager, NAOCountCaptures $naoCountCaptures, NAOCountComments $naoCountComments, NAOPagination $naoPagination)
    {
        $user = $this->getUser();

        $numberOfPublishedCaptures = $naoCountCaptures->countPublishedCaptures();
        $nbPublishedCapturesPages = $naoPagination->CountNbPages($numberOfPublishedCaptures);
        $publishedCaptures = $naoCaptureManager->getPublishedCapturesPerPage($page, $numberOfPublishedCaptures);

        $nextPage = $naoPagination->getNextPage($page);
        $previousPage = $naoPagination->getPreviousPage($page);

        return $this->render('AdminSpace\publishedCaptures.html.twig', array('publishedcaptures' => $publishedCaptures, 'numberOfPublishedCaptures' => $numberOfPublishedCaptures, 'page' => $page, 'nextPage' => $nextPage, 'previousPage' => $previousPage, 'nbPublishedCapturesPages' => $nbPublishedCapturesPages,)); 
    }

    /**
     * @Route("/espace-administration/observations-en-attente/{page}", name="espaceAdminObservationsEnAttente", requirements={"page" = "\d+"})
     * @param Request $request
     * @return Response
     */
    public function showNextWaitingCapturesAction($page, NAOCaptureManager $naoCaptureManager, NAOCommentManager $naoCommentManager, NAOCountCaptures $naoCountCaptures, NAOCountComments $naoCountComments, NAOPagination $naoPagination)
    {
        /$user = $this->getUser();

        $numberOfWaitingForValidationCaptures = $naoCountCaptures->countWaitingForValidationCaptures();
        $nbWaitingForValidationCapturesPages = $naoPagination->CountNbPages($numberOfWaitingForValidationCaptures);
        $waitingForValidationCaptures = $naoCaptureManager->getWaintingForValidationCapturesPerPage($page, $numberOfWaitingForValidationCaptures);

        $nextPage = $naoPagination->getNextPage($page);
        $previousPage = $naoPagination->getPreviousPage($page);

        return $this->render('AdminSpace\waitingCaptures.html.twig', array('waitingforvalidationcaptures' => $waitingForValidationCaptures, 'numberOfWaitingforvalidationCaptures' => $numberOfWaitingForValidationCaptures, 'page' => $page, 'nextPage' => $nextPage, 'previousPage' => $previousPage, 'nbWaitingForValidationCapturesPages' => $nbWaitingForValidationCapturesPages,)); 
    }

    /**
     * @Route("/espace-administration/commentaires-publies/{page}", name="espaceAdminCommentairesPublies", requirements={"page" = "\d+"})
     * @param Request $request
     * @return Response
     */
    public function showNextPublishedCommentsAction($page, NAOCaptureManager $naoCaptureManager, NAOCommentManager $naoCommentManager, NAOCountCaptures $naoCountCaptures, NAOCountComments $naoCountComments, NAOPagination $naoPagination)
    {
        $user = $this->getUser();

        $numberOfPublishedComments = $naoCountComments->countPublishedComments();
        $nbPublishedCommentsPages = $naoPagination->CountNbPages($numberOfPublishedComments);
        $publishedComments = $naoCommentManager->getPublishedCommentsPerPage($page, $numberOfPublishedComments);

        $nextPage = $naoPagination->getNextPage($page);
        $previousPage = $naoPagination->getPreviousPage($page);

        return $this->render('AdminSpace\publishedComments.html.twig', array('publishedcomments' => $publishedComments, 'numberOfPublishedComments' => $numberOfPublishedComments, 'page' => $page, 'nextPage' => $nextPage, 'previousPage' => $previousPage, 'nbPublishedCommentsPages' => $nbPublishedCommentsPages,)); 
    }

    /**
     * @Route("/espace-administration/commentaires-signales/{page}", name="espaceAdminCommentairesSignales", requirements={"page" = "\d+"})
     * @param Request $request
     * @return Response
     */
    public function showNextReportedCommentsAction($page, NAOCaptureManager $naoCaptureManager, NAOCommentManager $naoCommentManager, NAOCountCaptures $naoCountCaptures, NAOCountComments $naoCountComments, NAOPagination $naoPagination)
    {
        $user = $this->getUser();

        $numberOfReportedComments  = $naoCountComments->countReportedComments();
        $nbReportedCommentsPages = $naoPagination->CountNbPages($numberOfReportedComments);
        $reportedComments = $naoCommentManager->getReportedCommentsPerPage($page, $numberOfReportedComments);

        $nextPage = $naoPagination->getNextPage($page);
        $previousPage = $naoPagination->getPreviousPage($page);

        return $this->render('AdminSpace\reportedComments.html.twig', array('reportedcomments' => $reportedComments, 'numberOfReportedComments' => $numberOfReportedComments, 'page' => $page, 'nextPage' => $nextPage, 'previousPage' => $previousPage,'nbReportedCommentsPages' => $nbReportedCommentsPages)); 
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