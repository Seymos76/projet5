<?php

namespace App\Controller\Frontend;

use App\Entity\Bird;
use App\Services\NAOManager;
use App\Services\Bird\NAOBirdManager;
use App\Services\Bird\NAOCountBirds;
use App\Services\NAOPagination;
use App\Services\Capture\NAOCaptureManager;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BirdController extends Controller
{
    /**
     * @Route("repertoire/{letter}/{page}", defaults={"page"=1}, requirements={"page" = "\d+"}, name="repertoireParLettre")
     * @return Response
     */
    public function showRepertoryByLetterAction($letter, NAOBirdManager $naoBirdManager, NAOPagination $naoPagination, NAOCountBirds $naoCountBirds, $page)
    {
        $numberOfBirds = $naoCountBirds->countBirdsByLetter($letter);
        $numberOfBirdsPerPage = $naoPagination->getNbBirdsPerPage();

        $birds = $naoBirdManager->getBirdsByLetter($letter, $page, $numberOfBirds, $numberOfBirdsPerPage);

        $nbRepertoryPages = $naoPagination->CountNbPages($numberOfBirds, $numberOfBirdsPerPage);
        $nextPage = $naoPagination->getNextPage($page);
        $previousPage = $naoPagination->getPreviousPage($page);

        return $this->render('Bird\repertory.html.twig', array('birds' => $birds, 'nbRepertoryPages' => $nbRepertoryPages, 'nextPage' => $nextPage, 'previousPage' => $previousPage, 'page' => $page, 'letter' => $letter)); 
    }

    /**
     * @Route("repertoire/{page}", defaults={"page"=1}, requirements={"page" = "\d+"}, name="repertoire")
     * @return Response
     */
    public function showRepertoryAction(NAOBirdManager $naoBirdManager, NAOPagination $naoPagination, NAOCountBirds $naoCountBirds, $page)
    {
        $numberOfBirds = $naoCountBirds->countBirds();
        $numberOfBirdsPerPage = $naoPagination->getNbBirdsPerPage();

        $birds = $naoBirdManager->getBirdsPerPage($page, $numberOfBirds, $numberOfBirdsPerPage);

        $nbRepertoryPages = $naoPagination->CountNbPages($numberOfBirds, $numberOfBirdsPerPage);
        $nextPage = $naoPagination->getNextPage($page);
        $previousPage = $naoPagination->getPreviousPage($page);

        return $this->render('Bird\repertory.html.twig', array('birds' => $birds, 'nbRepertoryPages' => $nbRepertoryPages, 'nextPage' => $nextPage, 'previousPage' => $previousPage, 'page' => $page, )); 
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
