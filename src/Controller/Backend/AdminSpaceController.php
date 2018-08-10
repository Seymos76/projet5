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

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminSpaceController extends Controller
{
    /**
     * @Route("/espace-administration", name="espaceAdministration")
     * @param Request $request
     * @return Response
     */
    public function showAdminSpaceAction(NAOCaptureManager $naoCaptureManager, NAOCommentManager $naoCommentManager, NAOCountCaptures $naoCountCaptures, NAOCountComments $naoCountComments)
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

        $publishedCaptures = $naoCaptureManager->getPublishedCaptures();
        $numberOfPublishedCaptures = $naoCountCaptures->countPublishedCaptures();

        $waitingForValidationCaptures = $naoCaptureManager->getWaintingForValidationCaptures();
        $numberOfWaitingForValidationCaptures = $naoCountCaptures->countWaitingForValidationCaptures();

        $publishedComments = $naoCommentManager->getPublishedComments();
        $numberOfPublishedComments = $naoCountComments->countPublishedComments();

        $reportedComments = $naoCommentManager->getReportedComments();
        $numberOfReportedComments  = $naoCountComments->countReportedComments();

        return $this->render('adminspace.html.twig', array('userRole' => $userRole, 'user' => $user, 'publishedcaptures' => $publishedCaptures, 'waitingforvalidationcaptures' => $waitingForValidationCaptures, 'publishedcomments' => $publishedComments, 'reportedcomments' => $reportedComments, 'numberOfPublishedCaptures' => $numberOfPublishedCaptures, 'numberOfWaitingforvalidationCaptures' => $numberOfWaitingForValidationCaptures, 'numberOfPublishedComments' => $numberOfPublishedComments, 'numberOfReportedComments' => $numberOfReportedComments, )); 
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