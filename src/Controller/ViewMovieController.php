<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ViewMovieController extends AbstractController
{
    /**
     * @Route("/view/movie", name="view_movie")
     */
    public function displayAction(): Response
    {
        $movies = $this->getDoctrine()
            ->getRepository('App:Movie')
            ->findAll();

        return $this->render('view_movie/index.html.twig', array('data' => $movies));

    }
}
