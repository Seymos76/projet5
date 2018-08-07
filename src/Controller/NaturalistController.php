<?php
/**
 * Created by PhpStorm.
 * User: seymos
 * Date: 05/08/18
 * Time: 17:01
 */

namespace App\Controller;

use App\Entity\Image;
use App\Form\AvatarType;
use App\Form\BiographyType;
use App\Services\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class NaturalistController extends Controller
{
    /**
     * @Route("/account/naturalist", name="naturalist_account")
     * @return Response
     */
    public function naturalistAccount()
    {
        $observations = array(
            array(
                'name' => "Observation 01",
                'date' => "02/08/2018",
                'status' => "Brouillon"
            ),
            array(
                'name' => "Observation 02",
                'date' => "02/08/2018",
                'status' => "Publiée"
            ),
            array(
                'name' => "Observation 03",
                'date' => "02/08/2018",
                'status' => "Publiée"
            )
        );
        return $this->render(
            'naturalist/account.html.twig',
            array(
                'observations' => $observations,
                'user' => $this->getUser()
            )
        );
    }

    /**
     * @Route("/account/change-avatar", name="naturalist_change_avatar")
     * @param Request $request
     * @param FileUploader $uploader
     * @return Response
     */
    public function changeAvatar(Request $request, FileUploader $uploader)
    {
        $user = $this->getUser();
        $avatar_form = $this->createForm(AvatarType::class);
        $avatar_form->handleRequest($request);
        if ($avatar_form->isSubmitted() && $avatar_form->isValid()) {
            dump($avatar_form->getData());
            die;
        }
        return $this->render(
            'naturalist/change_avatar.html.twig',
            array(
                'form' => $avatar_form->createView()
            )
        );
    }

    /**
     * @Route("/account/change-biography", name="naturalist_change_biography")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function changeBiography(Request $request)
    {
        $user = $this->getUser();
        $biographyType = $this->createForm(BiographyType::class);
        $biographyType->handleRequest($request);
        if ($biographyType->isSubmitted() && $biographyType->isValid()) {
            $user->setBiography($biographyType->getData()['biography']);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', "Votre biographie a bien été mise à jour.");
            return $this->redirectToRoute('naturalist_account');
        }
        return $this->render(
            'naturalist/change_biography.html.twig',
            array(
                'form' => $biographyType->createView()
            )
        );
    }
}
