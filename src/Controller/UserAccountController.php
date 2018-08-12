<?php

namespace App\Controller\Backend;

use App\Entity\Capture;
use App\Entity\User;
use App\Entity\Comment;
use App\Services\NAOManager;
use App\Services\Capture\NAOCaptureManager;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserAccountController extends Controller
{
    /**
     * @Route("compte-naturaliste/", name="compte-naturaliste")
     * @return Response
     */
    public function showNaturalistAccountAction()
    {
    	$user = $this->getUser();
        $captures = $user->getCaptures();

    	return $this->render('sharedCapturesModel.html.twig', array('captures' => $captures,)); 
    }
}