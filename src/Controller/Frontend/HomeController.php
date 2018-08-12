<?php

namespace App\Controller\Frontend;

use App\Entity\Capture;
use App\Services\NAOManager;
use App\Services\Capture\NAOCaptureManager;
use App\Services\Capture\NAOShowMap;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{
	/**
     * @Route("/test", name="accueil")
     * @return Response
     */
	public function showHomeAction()
	{
		$numberCaptures = '4';
		$em = $this->getDoctrine()->getManager();
		$captures = $em->getRepository(Capture::class)->getLastPublishedCaptures($numberCaptures);

        return $this->render('homePage.html.twig', array('captures' => $captures, 'numberCaptures' => $numberCaptures)); 
	}
}