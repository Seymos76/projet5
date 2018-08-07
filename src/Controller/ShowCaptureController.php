<?php

namespace App\Controller;

use App\Entity\Capture;
use App\Entity\User;
use App\Entity\Comment;
use App\Services\NAOManager;
use App\Services\Capture\NAOCaptureManager;
use App\Form\CommentType;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ShowCaptureController extends Controller
{
    /**
     * @Route("/observation/{id}", requirements={"id" = "\d+"}, name="observation")
     * @return Response
     */
    public function showCaptureAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $capture = $em->getRepository(Capture::class)->findOneById($id);

        $numberOfCaptureComments = $em->getRepository(Comment::class)->countCaptureComments($capture);

        $comment = new Comment();
        $form = $this->get('form.factory')->create(CommentType::class, $comment);

        return $this->render('Capture\showCapture.html.twig', array('capture' => $capture, 'id' => $id, 'numberOfCaptureComments' => $numberOfCaptureComments, 'form' => $form->createView())); 
    }

    /**
     * @Route("/observations/", name="observation")
     * @return Response
     */
    public function showCapturesAction(NAOCaptureManager $nAOCaptureManager)
    {
        $captures = $nAOCaptureManager->getPublishedCaptures();

        return $this->render('Capture\showCaptures.html.twig', array('captures' => $captures,));
    }
}
