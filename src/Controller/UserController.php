<?php
/**
 * Created by PhpStorm.
 * User: seymos
 * Date: 07/08/18
 * Time: 13:53
 */

namespace App\Controller;


use App\Form\AvatarType;
use App\Form\BiographyType;
use App\Form\ChangePasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends Controller
{
    /**
     * @Route("/account/user", name="user_account")
     */
    public function account()
    {
        $biographyType = $this->createForm(BiographyType::class);
        $avatarType = $this->createForm(AvatarType::class);
        $changePasswordType = $this->createForm(ChangePasswordType::class);
        return $this->render(
            'user/account.html.twig',
            array(
                'user' => $this->getUser(),
                'biography_form' => $biographyType->createView(),
                'avatar_form' => $avatarType->createView(),
                'change_password_form' => $changePasswordType->createView()
            )
        );
    }

    /**
     * @Route("/check-role", name="check_role")
     * Fonction peut-être inutile, à voir
     */
    public function checkRoles()
    {
        $user = $this->getUser();
        dump($user->getRoles());
        $account_type = $user->getAccountType();
        if ($account_type === 'particular') {
            return $this->redirectToRoute('user_account');
        } elseif ($account_type === 'naturalist') {
            return $this->redirectToRoute('naturalist_account');
        } elseif ($account_type === 'admin') {
            return $this->redirectToRoute('admin');
        } else {
            return $this->redirectToRoute('login');
        }
    }
}
