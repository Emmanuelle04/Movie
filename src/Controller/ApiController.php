<?php

namespace App\Controller;

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
//    define constant
    const TOKEN = "1bbadff0";
    const URI = "http://www.omdbapi.com";

    /**
     * @Route("/api", name="api")
     * @param Request $request
     * @return Response
     * @throws GuzzleException
     */
    public function getMovie(Request $request): Response
    {
//        $searchMovie = $request->get('search');
        $searchMovie = "the+witcher";

        // Create a client with a base URI (uniform resource identifier)
        $client = new Client(
            [
                'base_uri' => self::URI
            ]
        );

        $headers = [
            'Authorization' => 'Bearer ',
            'Accept'        => 'application/json',
        ];

        // Send a request
        // use self to reference a class variable (constant) or method
        $response = $client->request(
            'GET',
            '/?t='.$searchMovie.'&apikey=' . self::TOKEN,
            [
                'headers' => $headers
            ]
        )->getBody()->getContents();

        // casting -> convert a variable to array
        $movieDetails = (array) json_decode($response);

        //associative array -> key and value (Ex: Genre and Action)
        $genre = $movieDetails['Genre'];

//        genre = Action, Crime, Thriller
//        php explode -> remove a string from an array of string
        $genreArray = explode(',', $genre);

        // override array $movieDetails['Genre']; with $genreArray
        $movieDetails['Genre'] = $genreArray;

        return $this->render('api/index.html.twig',[
            'data' => $movieDetails
        ]);

    }
}

