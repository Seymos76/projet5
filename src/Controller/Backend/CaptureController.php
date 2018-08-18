<?php

namespace App\Controller\Backend;

use App\Entity\Capture;
use App\Entity\User;
use App\Services\NAOManager;
use App\Services\Capture\NAOCaptureManager;
use App\Services\User\NAOUserManager;
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
     * @param NAOManager $naoManager
     * @param NAOCaptureManager $naoCaptureManager
     * @param NAOUserManager $naoUserManager
     * @return Response
     */
    public function addCaptureAction(Request $request, NAOManager $naoManager, NAOCaptureManager $naoCaptureManager, NAOUserManager $naoUserManager)
    {
        $this->denyAccessUnlessGranted("ROLE_USER");
        $capture = new Capture();
        $current_user = $this->getUser();
        /** @var User $user */
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(
            array(
                'email' => $current_user->getEmail()
            )
        );
        $userRole = $naoUserManager->getRoleFR($user);
        $role = $naoUserManager->getNaturalistOrParticularRole($user);

        if ($userRole === 'particulier')
        {
            $form = $this->createForm(ParticularCaptureType::class);
        }
        elseif ($userRole === 'naturaliste' || $userRole === 'administrateur')
        {
            $form = $this->createForm(NaturalistCaptureType::class);
        }
        $form->add('Enregistrer',      SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            dump($form->getData());
            /** @var Capture $capture */
            $capture = $this->get('app.nao_capture_manager')->buildCapture($form->getData(), $this->getParameter('bird_directory'), $userRole);
            $capture->setUser($this->getUser());
            if ($userRole == 'Particulier')
            {
                $naoCaptureManager->setWaitingStatus($capture);
            }
            $validator = $this->get('validator');
            $listErrors = $validator->validate($capture->getImage());
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
        $title = 'Ajouter une observation';

        return $this->render(
            'Capture\addModifyOrValidateCapture.html.twig',
            array(
                'form' => $form->createView(),
                'userRole' => $userRole,
                'role' => $role,
                'titre' => $title
            )
        );
    }

    /**
     * @Route("/valider-observation/{id}", name="validerObservation", requirements={"id" = "\d+"})
     * @param Request $request
     * @return Response
     */
    public function validateCaptureAction(Request $request, NAOManager $naoManager, NAOCaptureManager $naoCaptureManager, NAOUserManager $naoUserManager, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $capture = $em->getRepository(Capture::class)->findOneById($id);

        $form = $this->createForm(ValidateCaptureType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $validator = $this->get('validator');
            $list_errors = $validator->validate($capture);
            if (count($list_errors) > 0) {
                return $this->redirectToRoute('validerObservation', array('list_errors' => $list_errors));
            }
            if ($form->get('validate')->isClicked()) {
                dump($form->getData());
                die('validating');
            } elseif ($form->get('waitingForValidation')->isClicked()) {
                die('waiting');
            } elseif ($form->get('remove')->isClicked()) {
                die('remove');
            } else {
                die('nothing');
            }
            dump($list_errors);
        }
        /*
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
                    $naturalist = $this->getUser();
                    dump($request->getContent());
                    die;

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

                return $this->redirectToRoute('espaceAdministration');
            }
        }*/

        $user = $this->getUser();
        $userRole = $naoUserManager->getRoleFR($user);
        $role = $naoUserManager->getNaturalistOrParticularRole($user);
        $title = 'Valider une observation';

        return $this->render(
            'Capture\addModifyOrValidateCapture.html.twig',
            array(
                'form' => $form->createView(),
                'userRole' => $userRole,
                'role' => $role,
                'titre' => $title
            )
        );
    }

    /**
     * @Route("/modifier-observation", name="modifierObservation")
     * @param Request $request
     * @return Response
     */
    public function modifyCaptureAction(Request $request, NAOManager $naoManager, NAOUserManager $naoUserManager, $capture)
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

        $user = $this->getUser();
        $userRole = $naoUserManager->getRoleFR($user);
        $title = 'Modifier une observation';

        return $this->render('Capture\addModifyOrValidateCapture.html.twig', array('form' => $form->createView(), 'userRole' => $userRole, 'titre' => $title)); 
    }
}
