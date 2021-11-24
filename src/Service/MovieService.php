<?php

namespace App\Service;

use App\Entity\Movie;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


class MovieService
{
    const TOKEN = "1bbadff0";
    const URI = "http://www.omdbapi.com";
    const QUERY_STRING = '/?%s=%s&apikey=%s';

    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;

    /**
     * @param EntityManagerInterface $entityManager
     * @param ParameterBagInterface $parameterBag
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ParameterBagInterface  $parameterBag
    )
    {
        $this->em = $entityManager;
        $this->parameterBag = $parameterBag;
    }

    // Get Movies from API
    /**
     * @throws GuzzleException
     */
    public function getMovies($movieName, $searchParam): array
    {
        $client = new Client(
            [
                'base_uri' => self::URI
            ]
        );

        // Send a request, use self to reference a class variable (constant) or method
        $response = $client->request(
            'GET',
            sprintf(self::QUERY_STRING, $searchParam, $movieName, self::TOKEN),
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

    /**
     * @param $movieID
     * @param $searchParam
     * @return array
     * @throws GuzzleException
     * @throws Exception
     */
    public function processMovie($movieID, $searchParam): array
    {
        if ($film = $this->fetchMovieDetails($movieID, $searchParam)) {
            throw new Exception('Movie not found');
        }

        if ($this->checkIfMovieExists($movieID)) {
            throw new Exception('Movie in database detected, exiting');
        }
        $this->saveMovie($film);
        return $film;
    }

    /**
     * @param $movieID
     * @param $searchParam
     * @return array
     * @throws Exception|GuzzleException
     */
    private function fetchMovieDetails($movieID, $searchParam): array
    {
        try {
            // Calling movie api service
            return $this
                ->getMovies($movieID, $searchParam);
        } catch (Exception $exception) {
            throw new Exception('Movie Details fetched from api failed, exit command');
        }
    }

    /**
     * @param $movieID
     * @return bool
     */
    private function checkIfMovieExists($movieID): bool
    {
        return !empty(
        $this
            ->em
            ->getRepository(Movie::class)
            ->findByID($movieID)
            ->getResult()
        );
    }

    /**
     * @param $film
     * @return void
     */
    private function saveMovie($film): void
    {
        $movie = new Movie();
        $date = \DateTime::createFromFormat(
            'd M Y',
            $film['Released']
        );
        $movie->setTitle($film['Title']); //Respective entity methods
        $movie->setDescription($film['Plot']);
        $movie->setProducer($film['Director']);
        $movie->setReleasedDate($date);
        $movie->setImdbID($film['imdbID']);

        $posterName = $this->saveImage($film);

        $movie->setPoster($posterName);

        $this->em->persist($movie);
        $this->em->flush();

    }

    /**
     * @param $film
     * @return string
     */
    private function saveImage($film): string
    {
        if (empty($film['Poster'])) {
            return 'not_found.png';
        }

        $posterName = uniqid() . '.jpg';

        $content = file_get_contents($film['Poster']);
        $fp = fopen(
            $this->parameterBag->get('kernel.project_dir') . "/public/uploads/poster/" . $posterName,
            "w"
        );
        fwrite($fp, $content);
        fclose($fp);

        return $posterName;
    }

    private function updateMovie()
    {

    }


}