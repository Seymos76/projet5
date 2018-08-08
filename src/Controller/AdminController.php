<?php
/**
 * Created by PhpStorm.
 * User: seymos
 * Date: 07/08/18
 * Time: 16:33
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends Controller
{
    /**
     * @Route("/account/admin", name="admin_account")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function account()
    {
        return $this->render(
            'admin/account.html.twig'
        );
    }
}
