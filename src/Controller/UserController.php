<?php
/**
 * Created by PhpStorm.
 * User: seymos
 * Date: 02/08/18
 * Time: 18:47
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
    public function account()
    {
        return $this->render(
            'user/account.html.twig'
        );
    }
}
