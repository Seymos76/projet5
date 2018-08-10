<?php

namespace App\Controller\Frontend;

use App\Entity\Bird;
use App\Services\NAOManager;
use App\Services\Capture\NAOCaptureManager;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BirdController extends Controller
{
    /**
     * @Route("repertoire/{letter}", name="repertoireParLettre")
     * @return Response
     */
    public function showRepertoryByLetterAction($letter)
    {
        $em = $this->getDoctrine()->getManager();
        $birds = $em->getRepository(Bird::class)->getBirdsByFirstLetter($letter);

        return $this->render('Bird\repertory.html.twig', array('birds' => $birds,)); 
    }

    /**
     * @Route("repertoire/", name="repertoire")
     * @return Response
     */
    public function showRepertoryAction()
    {
        $em = $this->getDoctrine()->getManager();
        $birds = $em->getRepository(Bird::class)->getBirdsByOrderAsc();

        return $this->render('Bird\repertory.html.twig', array('birds' => $birds,)); 
    }

    /**
     * @Route("oiseau/{id}", requirements={"id" = "\d+"}, name="oiseau")
     * @return Response
     */
    public function showBird($id)
    {
        $em = $this->getDoctrine()->getManager();
        $bird = $em->getRepository(Bird::class)->findOneById($id);

        $page = 'oiseau';

        return $this->render('Bird\bird.html.twig', array('bird' => $bird, 'page' => $page, 'id' => $id));      
    }
}
