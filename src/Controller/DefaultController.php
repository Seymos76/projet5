<?php
/**
 * Created by PhpStorm.
 * User: seymos
 * Date: 02/08/18
 * Time: 17:04
 */

namespace App\Controller;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="index")
     * @return Response
     */
    public function index()
    {
        // récupérer derniers enregistrements Capture
        // renvoyer enregistrements
        return $this->render(
            'default/index.html.twig'
        );
    }

    public function repertoire()
    {
        // récupérer espèces
        // récupérer régions
        // tri alphabétique
        // renvoyer liste + pagination + régions
        return $this->render(
            'default/repertoire.html.twig'
        );
    }

    public function bird()
    {
        // récupérer oiseau
        // renvoyer oiseau
        return $this->render(
            'default/bird.html.twig'
        );
    }

    public function statistics()
    {
        return $this->render(
            'default/statistics.html.twig'
        );
    }

    public function contact()
    {
        return $this->render(
            'default/contact.html.twig'
        );
    }
}
