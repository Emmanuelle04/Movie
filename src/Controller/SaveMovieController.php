<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SaveMovieController extends AbstractController
{
    /**
     * @Route("/save/movie", name="save_movie")
     */
    public function index(): Response
    {
        return $this->render('save_movie/index.html.twig', [
            'controller_name' => 'SaveMovieController',
        ]);
    }
}
