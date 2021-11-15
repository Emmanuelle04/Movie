<?php

namespace App\Controller;

use GuzzleHttp\Exception\GuzzleException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use GuzzleHttp\Client;

class ApiController extends AbstractController
{
    /**
     * @Route("/api", name="api")
     * @throws GuzzleException
     */
    public function getMovie(): Response
    {
        // http://www.omdbapi.com/?apikey=[1bbadff0]&
        // Create a client with a base URI (uniform resource identifier)
        // $client = new GuzzleHttp\Client(['base_uri' => 'https://foo.com/api/']);
        $client = new Client(['base_uri' => 'https://www.omdbapi.com/?apikey=[1bbadff0]&']);

//        "http://www.omdbapi.com/?s=inception&apikey=[yourkey]"
        // Send a request to https://foo.com/api/test
        $response = $client->request('GET', 't=[batman]');
        dd($response);

        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }
}
