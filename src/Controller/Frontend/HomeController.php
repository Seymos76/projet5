<?php

namespace App\Controller\Frontend;

use App\Entity\Capture;
use App\Entity\Message;
use App\Form\MessageType;
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

        return $this->render('home/index.html.twig', array('captures' => $captures,));
	}

    /**
     * @Route(path="/statistiques", name="statistics")
     * @return Response
     */
	public function statistics()
    {
        return $this->render(
            'home/statistics.html.twig'
        );
    }

    /**
     * @Route("/contact", name="contact")
     * @param Request $request
     * @return Response
     */
    public function contact(Request $request)
    {
        $message = new Message();
        $message_form = $this->createForm(MessageType::class, $message);
        $message_form->handleRequest($request);
        if ($message_form->isSubmitted() && $message_form->isValid()) {
            $message->setSentAt(new \DateTime('now'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', "Message envoyé");
            return $this->redirectToRoute('contact');
        }
        return $this->render(
            'home/contact.html.twig',
            array(
                'form' => $message_form->createView()
            )
        );
    }
}