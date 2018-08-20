<?php

namespace App\Controller\Frontend;

use App\Entity\Capture;
use App\Services\NAOManager;
use App\Services\Capture\NAOCaptureManager;
use App\Services\Capture\NAOCountCaptures;
use App\Services\Bird\NAOCountBirds;
use App\Services\Capture\NAOShowMap;
use App\Services\NAOPagination;
use App\Services\NAODataStatistics;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{
	/**
     * @Route("/", name="home")
     * @return Response
     */
	public function showHomeAction(NAOCaptureManager $naoCaptureManager)
	{
		$captures = $naoCaptureManager->getLastPublishedCaptures();

        return $this->render('homePage.html.twig', array('captures' => $captures,)); 
	}

	/**
     * @Route("/statistiques", name="statistics")
     * @return Response
     */
    public function statistics(NAOCountCaptures $naoCountCaptures, NAOCountBirds $naoCountBirds)
    {
    	$numberOfPublishedCaptures = $naoCountCaptures->countPublishedCaptures();
    	$numberOfBirds = $naoCountBirds->countBirds();
        return $this->render(
            'default/statistics.html.twig',
            array(
            		'numberOfBirds' => $numberOfBirds,
            		'numberOfPublishedCaptures' => $numberOfPublishedCaptures,
        		)
        );
    }
}