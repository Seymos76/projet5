<?php

namespace App\Controller\Frontend;

use App\Entity\Capture;
use App\Entity\User;
use App\Entity\Comment;
use App\Services\NAOManager;
use App\Services\Capture\NAOCaptureManager;
use App\Services\Comment\NAOCountComments;
use App\Services\Capture\NAOCountCaptures;
use App\Services\NAOPagination;
use App\Services\Capture\NAOShowMap;
use App\Form\CommentType;
use App\Form\Capture\SearchCaptureType;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CaptureController extends Controller
{
    /**
     * @Route("observation/{id}", requirements={"id" = "\d+"}, name="observation")
     * @return Response
     */
    public function showCaptureAction($id, Request $request, NAOManager $naoManager, NAOCaptureManager $naoCaptureManager, NAOCountComments $naoCountComments)
    {
        $capture = $naoCaptureManager->getPublishedCapture($id);
        
        $numberOfCaptureComments = $naoCountComments->countCapturePublishedComments($capture);

        $comment = new Comment();
        $form = $this->get('form.factory')->create(CommentType::class, $comment);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) 
        {
            $user = $this->getUser();
            $comment->setAuthor($user);
            $comment->setCapture($capture);

            $validator = $this->get('validator');
            $listErrors = $validator->validate($comment);
            if(count($listErrors) > 0) 
            {
                return new Response((string) $listErrors);
            } 
            else 
            {
                $naoManager->addOrModifyEntity($comment);

                return new Response('Le commentaire a été ajoutée');
            }
        }

        return $this->render('Capture\showCapture.html.twig', array('capture' => $capture, 'id' => $id, 'numberOfCaptureComments' => $numberOfCaptureComments, 'form' => $form->createView(),)); 
    }

    /**
     * @Route("/observations/{pageNumber}", requirements={"pageNumber" = "\d+"}, name="observations")
     * @return Response
     */
    public function showCapturesAction(Request $request, NAOCaptureManager $nAOCaptureManager, NAOCountCaptures $naoCountCaptures, NAOPagination $naoPagination, $pageNumber)
    {

        $page = 'observations';
        
        $numberOfPublishedCaptures = $naoCountCaptures->countPublishedCaptures();
        $captures = $nAOCaptureManager->getPublishedCapturesPerPage($pageNumber, $numberOfPublishedCaptures);

        $nbCapturesPages = $naoPagination->CountNbPages($numberOfPublishedCaptures);
        $nextPage = $naoPagination->getNextPage($pageNumber);
        $previousPage = $naoPagination->getPreviousPage($pageNumber);

        return $this->render('Capture\showCaptures.html.twig', array('captures' => $captures, 'page' => $page, 'pageNumber' => $pageNumber, 'nbCapturesPages' => $nbCapturesPages, 'nextPage' => $nextPage, 'previousPage' => $previousPage));
    }

    /**
     * @Rest\Get(
     *     path = "/api/publishedcaptures",
     *     name = "app_publishedcaptures_list"
     * )
     */
    public function getPublishedCapturesData(NAOCaptureManager $naoCaptureManager, NAOShowMap $naoShowMap)
    {
        $captures = $naoCaptureManager->getPublishedCaptures();

        return $publishedCaptures = $naoShowMap->formatPublishedCaptures($captures);
    }

    /**
     * @Rest\Get(
     *     path = "/api/birdpublishedcaptures/{id}",
     *     name = "app_birdpublishedcaptures_list",
     *     requirements={"id" = "\d+"}
     * )
     */
    public function getBirdPublishedCapturesData($id, NAOCaptureManager $naoCaptureManager, NAOShowMap $naoShowMap)
    {
        $captures = $naoCaptureManager->getBirdPublishedCaptures($id);

        return $publishedCaptures = $naoShowMap->formatPublishedCaptures($captures);
    }

    /**
     * @Rest\Get(
     *     path = "/api/latloncapture/{id}",
     *     name = "app_publishedcapture",
     *     requirements={"id" = "\d+"}
     * )
     */
    public function getLatitudeLongitudeCapture($id, NAOShowMap $naoShowMap)
    {
        return $capture = $naoShowMap->formatCapture($id);
    }
}
