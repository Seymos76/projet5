<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="index")
     * @return Response
     */
    public function index()
    {
        return new Response("Page d'accueil");
    }

    /**
     * @Route("/repertoire", name="directory")
     */
    public function directory()
    {
        return new Response("Répertoire");
    }

    /**
     * @Route("/observations", name="observations")
     */
    public function observation()
    {
        return new Response("Observations");
    }

    /**
     * @Route("/some-data", name="some_data")
     */
    public function someData()
    {
        return new Response("Some Data");
    }
}
