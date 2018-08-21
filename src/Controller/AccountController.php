<?php
/**
 * Created by PhpStorm.
 * User: seymos
 * Date: 08/08/18
 * Time: 16:36
 */

namespace App\Controller;

use App\Entity\Image;
use App\Entity\User;
use App\Form\AvatarType;
use App\Form\BiographyType;
use App\Form\ChangePasswordType;
use App\Services\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class AccountController
 * @package App\Controller
 * @Route("/account")
 */
class AccountController extends Controller
{
    /**
     * @Route("/change-avatar", name="change_avatar")
     * @param Request $request
     * @throws \Exception
     * @return Response
     */
    public function changeAvatar(Request $request)
    {
        $user = $this->getUser();
        $avatar_form = $this->createForm(AvatarType::class);
        $avatar_form->handleRequest($request);
        if ($avatar_form->isSubmitted() && $avatar_form->isValid()) {
            // check if image exists
            if ($user->getAvatar() !== null) {
                $this->get('app.avatar_service')->removeCurrentAvatar($this->getUser()->getUsername());
            }
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $avatar_form->getData()['avatar'];
            $image = $this->get('app.avatar_service')->buildImage($uploadedFile, $this->getParameter('avatar_directory'));
            $user->setAvatar($image);
            $this->get('app.nao_manager')->addOrModifyEntity($user);
            $this->get('session')->getFlashBag()->add('success', "Votre avatar a bien été changé !");
            return $this->redirectToRoute('account');
        }
        return $this->render(
            'account/change_avatar.html.twig',
            array(
                'form' => $avatar_form->createView()
            )
        );
    }
}
