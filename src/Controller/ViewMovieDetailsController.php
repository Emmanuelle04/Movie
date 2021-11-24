<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ViewMovieDetailsController extends AbstractController
{
    /**
     * @Route("/view/movie/details/{id}", name="view_movie_details")
     */
    public function displayAction($id): Response
    {
        $movies = $this->getDoctrine()
            ->getRepository('App:Movie')
            ->find($id);

        return $this->render('view_movie_details/index.html.twig', array('data' => $movies));

    }
}
