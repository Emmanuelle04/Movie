<?php

namespace App\Controller;

use App\Command\MovieSynchroniseCommand;
use App\Service\MovieService;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use GuzzleHttp\Client;

class ApiController extends AbstractController
{
    /**
     * @Route("/api", name="api")
     * @param Request $request
     * @return Response
     * @throws GuzzleException
     */


    public function getMovie(Request $request, MovieService $movieService): Response
    {

        // value from search bar
        $movieName = $request->get('title');
//        dd($movieName);
//                $searchMovie = "hitman";

        if (!empty($movieName)) {

            //call function getMovies in service MovieService
            $movieDetails = $movieService->getMovies($movieName);

//            dd($movieDetails);
             
            return $this->render('partials/listapi.html.twig',[
                'data' => $movieDetails
            ]);


        } else {

            return $this->render('api/index.html.twig',[
                'data' => []
            ]);

        }


    }
}

