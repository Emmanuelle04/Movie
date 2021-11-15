<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminViewMovieController extends AbstractController
{
    /**
     * @Route("/admin/view/movie/{id}", name="admin_view_movie")
     */
    public function displayAction($id): Response
    {
        $movies = $this->getDoctrine()
            ->getRepository('App:Movie')->find($id);
//            ->findAll();

        return $this->render('admin_view_movie/index.html.twig', array('result' => $movies));

    }
}
