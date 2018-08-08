<?php

namespace App\Controller\Backend;

use App\Entity\Capture;
use App\Entity\User;
use App\Services\NAOManager;
use App\Services\Capture\NAOCaptureManager;
use App\Form\Capture\ParticularCaptureType;
use App\Form\Capture\NaturalistCaptureType;
use App\Form\Capture\ValidateCaptureType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CaptureController extends Controller
{
    /**
     * @Route("ajouter-observation", name="ajouterObservation")
     * @param Request $request
     * @return Response
     */
    public function addCaptureAction(Request $request, NAOManager $naoManager)
    {
        $capture = new Capture();
        
        $user = $this->getUser();
        $roles = $user->getRoles();

        if (in_array('particular', $roles))
        {
            $userRole = 'particulier';
            $form = $this->get('form.factory')->create(ParticularCaptureType::class, $capture);
        }
        elseif ((in_array('naturalist', $roles)) OR (in_array('administrator', $roles)))
        {
            $userRole = 'naturaliste';
            $form = $this->get('form.factory')->create(NaturalistCaptureType::class, $capture);
        }
        $form->add('Enregistrer',      SubmitType::class);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) 
        {
            $capture->setUser($user);

            if (in_array('particular', $roles))
            {
                $naoCaptureManager->setWaitingStatus($capture);
            }

            $validator = $this->get('validator');
            $listErrors = $validator->validate($capture);
            if(count($listErrors) > 0) 
            {
                return new Response((string) $listErrors);
            } 
            else 
            {
                $naoManager->addOrModifyEntity($capture);

                return new Response('L\'observation a été ajoutée');
            }
        }

        $account = 'Compte '. $userRole;
        $title = 'Ajouter une observation';

        return $this->render('Capture\addModifyOrValidateCapture.html.twig', array('form' => $form->createView(), 'compte' => $account, 'titre' => $title)); 
    }

    /**
     * @Route("/valider-observation", name="validerObservation")
     * @param Request $request
     * @return Response
     */
    public function validateCaptureAction(Request $request, NAOManager $naoManager, NAOCaptureManager $naoCaptureManager, $capture)
    {
        $em = $this->getDoctrine()->getManager();
        $capture = $em->getRepository(Capture::class)->find($capture);

        $form = $this->get('form.factory')->create(validateCaptureType::class, $capture);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) 
        {   
            $validator = $this->get('validator');
            $listErrors = $validator->validate($capture);
            if(count($listErrors) > 0) 
            {
                return new Response((string) $listErrors);
            } 
            else 
            {
                if ($form->get('validate')->isClicked())
                {
                    $naoCaptureManager->validateCapture($capture, $naturalist);
                    $naoManager->addOrModifyEntity($capture);
                }
                elseif ($form->get('waitingForValidation')->isClicked())
                {
                    $naoManager->addOrModifyEntity($capture);
                }
                elseif ($form->get('remove')->isClicked())
                {
                    $naoManager->removeEntity($capture);
                }
            }
        }

        $account = 'Compte Naturaliste';
        $title = 'Valider une observation';

        return $this->render('Capture\addModifyOrValidateCapture.html.twig', array('form' => $form->createView(), 'compte' => $account, 'titre' => $title)); 
    }

    /**
     * @Route("/modifier-observation", name="modifierObservation")
     * @param Request $request
     * @return Response
     */
    public function modifyCaptureAction(Request $request, NAOManager $naoManager, $capture)
    {
        $em = $this->getDoctrine()->getManager();
        $capture = $em->getRepository(Capture::class)->find($capture); 
        $form = $this->createForm(NaturalistCaptureType::class, $capture);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) 
        {
            $validator = $this->get('validator');
            $listErrors = $validator->validate($capture);
            if(count($listErrors) > 0) 
            {
                return new Response((string) $listErrors);
            } 
            else 
            {
                $naoManager->addOrModifyEntity($capture);

                return new Response('L\'observation a été modifiée');
            }
        }

        $account = 'Compte Naturaliste';
        $title = 'Modifier une observation';

        return $this->render('Capture\addModifyOrValidateCapture.html.twig', array('form' => $form->createView(), 'compte' => $account, 'titre' => $title)); 
    }
}
