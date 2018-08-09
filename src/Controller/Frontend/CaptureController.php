<?php

namespace App\Controller\Frontend;

use App\Entity\Capture;
use App\Entity\User;
use App\Entity\Comment;
use App\Services\NAOManager;
use App\Services\Capture\NAOCaptureManager;
use App\Services\Capture\NAOShowMap;
use App\Form\CommentType;

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
    public function showCaptureAction($id, Request $request, NAOManager $naoManager, NAOShowMap $naoShowMap)
    {
        $em = $this->getDoctrine()->getManager();
        $capture = $em->getRepository(Capture::class)->findOneById($id);

        $numberOfCaptureComments = $em->getRepository(Comment::class)->countCaptureComments($capture);

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

        $map = $naoShowMap->getMapLink($capture);

        return $this->render('Capture\showCapture.html.twig', array('capture' => $capture, 'id' => $id, 'numberOfCaptureComments' => $numberOfCaptureComments, 'form' => $form->createView(), 'map' => $map,)); 
    }

    /**
     * @Route("/observations/", name="observations")
     * @return Response
     */
    public function showCapturesAction(NAOCaptureManager $nAOCaptureManager)
    {
        $captures = $nAOCaptureManager->getPublishedCaptures();

        return $this->render('Capture\showCaptures.html.twig', array('captures' => $captures,));
    }
}
