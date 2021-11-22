<?php

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;



class MovieService
{
    const TOKEN = "1bbadff0";
    const URI = "http://www.omdbapi.com";
    const QUERY_STRING = '/?t=%s&apikey=%s';

    /**
     * @throws GuzzleException
     */
    public function getMovies($movieName): array
    {
        $client = new Client(
            [
                'base_uri' => self::URI
            ]
        );

        // Send a request, use self to reference a class variable (constant) or method
        $response = $client->request(
            'GET',
            sprintf(self::QUERY_STRING, $movieName, self::TOKEN),
            [
                'headers' => [
                    'Authorization' => 'Bearer ',
                    'Accept' => 'application/json'
                ]
            ]
        )
            ->getBody()
            ->getContents();


        // Casting - convert a variable to array
        $movieDetails = (array)json_decode($response);

        if ($movieDetails)
        // Associative array - key and value (Ex: Genre and Action)
        $movieDetails['Genre'] = explode(
            ',',
            $movieDetails['Genre']
        );

        return $movieDetails;
    }


}