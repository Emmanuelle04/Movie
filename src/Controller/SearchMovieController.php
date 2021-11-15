<?php

namespace App\Controller;

use App\Entity\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchMovieController extends AbstractController
{
    /**
     * @Route("/search/movie", name="search_movie")
     */
    public function searchMovie(Request $request): JsonResponse
    {
        $searchTitle = $request->get('search');
//dd(
//    $request
//);
        $em = $this->getDoctrine()->getManager();
        $search = $em->getRepository(Movie::class)->findByTitleField($searchTitle);
//dd(
//  $search->getSQL()
//);
        $results = $search->getResult();

//        dd($results);

        $content = $this->renderView('partials/list.html.twig', [
            'data' => $results
        ]);

        return new JsonResponse([
            'searchMovie' => $content
        ]);
    }
}
