<?php
/**
 * Created by PhpStorm.
 * User: seymos
 * Date: 06/08/18
 * Time: 12:16
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends Controller
{
    /**
     * @Route("/administration", name="admin")
     */
    public function admin()
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        return $this->render(
            'admin/admin.html.twig',
            array(
                'user' => $this->getUser()
            )
        );
    }

    /**
     * @Route("/upgrade", name="upgrade")
     */
    public function upgrade()
    {
        dump($this->getUser());
        $user = $this->getUser();
        $user->addRole("ROLE_ADMIN");
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        $this->get('session')->getFlashBag()->add('success', "Upgraded with role admin");
        return $this->redirectToRoute('naturalist_account');
    }
}
