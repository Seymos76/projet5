<?php

namespace App\Controller\Frontend;

use App\Entity\Capture;
use App\Services\NAOManager;
use App\Services\Capture\NAOCaptureManager;
use App\Services\Capture\NAOShowMap;
use App\Services\NAOPagination;

use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{
	/**
     * @Route("/", name="accueil")
     * @return Response
     */
	public function showHomeAction(NAOPagination $naoPagination)
	{
		$numberCaptures = $naoPagination->getNbHomeCapturesPerPage();
		$em = $this->getDoctrine()->getManager();
		$captures = $em->getRepository(Capture::class)->getLastPublishedCaptures($numberCaptures);

        return $this->render('default/index.html.twig', array('captures' => $captures,));
	}

    /**
     * @Route(path="/statistiques", name="statistics")
     * @return Response
     */
	public function statistics()
    {
        return $this->render(
            'default/statistics.html.twig'
        );
    }

	/**
     * @Route(path="/api/lastcaptures", name="app_lastcaptures_list")
     * @Method("GET")
     */
	public function showLastCapturesAction(NAOShowMap $naoShowMap, NAOPagination $naoPagination)
	{
		$numberCaptures = $naoPagination->getNbHomeCapturesPerPage();
		$em = $this->getDoctrine()->getManager();
		$lastCaptures = $em->getRepository(Capture::class)->getLastPublishedCaptures($numberCaptures);

		return $naoShowMap->formatPublishedCaptures($lastCaptures);
	}
}