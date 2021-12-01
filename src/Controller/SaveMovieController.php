<?php

namespace App\Controller;

use App\Service\MovieService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SaveMovieController extends AbstractController
{
    /**
     * @Route("/save/movie", name="save_movie")
     */
    public function saveMovie(Request $request, MovieService $movieService): JsonResponse
    {
        $movie = $request->get('movie');

        $movieService->saveMovie($movie);

        return new JsonResponse('saved ok', Response::HTTP_OK);
    }
}
