<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function showMovies(): Response
    {
        $movies = $this->getDoctrine()
            ->getRepository('App:Movie')
            ->findAll();

        return $this->render('home/index.html.twig', array('data' => $movies));

    }
}
