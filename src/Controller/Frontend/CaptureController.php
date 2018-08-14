<?php

namespace App\Controller\Frontend;

use App\Entity\Bird;
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
use Symfony\Component\Serializer\Encoder\JsonDecode;

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
     * @Route("/observations/{pageNumber}", requirements={"pageNumber" = "\d+"}, defaults={"pageNumber"=1}, name="observations")
     * @return Response
     */
    public function showCapturesAction(Request $request, NAOCaptureManager $nAOCaptureManager, NAOCountCaptures $naoCountCaptures, NAOPagination $naoPagination, $pageNumber)
    {
        $regions = json_decode(file_get_contents("https://geo.api.gouv.fr/regions"), true);
        $birds = $this->getDoctrine()->getRepository(Bird::class)->getBirdsByOrderAsc();
        $numberOfPublishedCaptures = $naoCountCaptures->countPublishedCaptures();
        $captures = $nAOCaptureManager->getPublishedCapturesPerPage($pageNumber, $numberOfPublishedCaptures);
        $nbCapturesPages = $naoPagination->CountNbPages($numberOfPublishedCaptures);
        $nextPage = $naoPagination->getNextPage($pageNumber);
        $previousPage = $naoPagination->getPreviousPage($pageNumber);

        if ($request->isMethod('POST'))
        {
            $vernacularname = $request->get('bird');
            $region = $request->get('region');
            $em = $this->getDoctrine()->getManager();
            $capturesSearch = $em->getRepository(Capture::class)->searchCaptureByBirdAndRegion($vernacularname, $region);
            return $this->render('Capture\showCaptures.html.twig', array('captures' => $capturesSearch));
        }
        return $this->render(
            'Capture\showCaptures.html.twig',
            array(
                'captures' => $captures,
                'pageNumber' => $pageNumber,
                'nbCapturesPages' => $nbCapturesPages,
                'nextPage' => $nextPage,
                'previousPage' => $previousPage,
                'birds' => $birds,
                'regions' => $regions
            )
        );
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
