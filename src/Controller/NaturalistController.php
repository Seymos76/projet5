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
        $profil = array(
            'avatar' => "http://blogue-ton-ecole.ac-dijon.fr/wp-content/uploads/2016/07/Avatar_girl_face.png",
            'name' => "Julie Borine",
            'bio' => "Bonjour, je suis une passionnée d'oiseaux"
        );
        return $this->render(
            'naturalist/account.html.twig',
            array(
                'observations' => $observations,
                'profil' => $profil
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
}