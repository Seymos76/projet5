<?php
/**
 * Created by PhpStorm.
 * User: seymos
 * Date: 02/08/18
 * Time: 17:04
 */

namespace App\Controller;


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

    /**
     * @Route("/repertoire", name="repertoire")
     * @return Response
     */
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

    /**
     * @Route("/repertoire/bird", name="bird")
     * @return Response
     */
    public function bird()
    {
        // récupérer oiseau
        // renvoyer oiseau
        return $this->render(
            'default/bird.html.twig'
        );
    }

    /**
     * @Route("/statistiques", name="statistics")
     * @return Response
     */
    public function statistics()
    {
        return $this->render(
            'default/statistics.html.twig'
        );
    }

    /**
     * @Route("/contact", name="contact")
     * @return Response
     */
    public function contact()
    {
        return $this->render(
            'default/contact.html.twig'
        );
    }
}
