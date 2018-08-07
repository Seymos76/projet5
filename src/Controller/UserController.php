<?php
/**
 * Created by PhpStorm.
 * User: seymos
 * Date: 07/08/18
 * Time: 13:53
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends Controller
{
    /**
     * @Route("/account/user", name="user_account")
     */
    public function account()
    {
        return $this->render(
            'user/account.html.twig',
            array(
                'user' => $this->getUser()
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
