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
        $this->denyAccessUnlessGranted('ROLE_NATURALIST');
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
            // check if image exists
            if ($user->getAvatar() !== null) {
                // get current from directory
                $current_avatar = $this->getParameter('avatar_directory').'/'.$user->getAvatar();
                // delete file
                unlink($current_avatar);
                // set to null
                $user->setAvatar(null);
            }
            $file_name = $uploader->upload($avatar_form->getData()['avatar'], $this->getParameter('avatar_directory'));
            $user->setAvatar($file_name);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', "Votre avatar a bien été changé !");
            return $this->redirectToRoute('naturalist_account');
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
