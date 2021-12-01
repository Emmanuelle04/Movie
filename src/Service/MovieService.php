<?php

namespace App\Service;

use App\Entity\Category;
use App\Entity\Genre;
use App\Entity\Movie;
use DateTime;
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
     * @throws Exception
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

        // If movie not found in API
        if ($movieDetails['Response'] == 'False') {
            throw new Exception();
        }

        if ($movieDetails)
            // Associative array - key and value (Ex: Genre and Action)
            $movieDetails['Genre'] = explode(
                ', ',
                $movieDetails['Genre']
            );

        return $movieDetails;
    }

    /**
     * @param $movieID
     * @param $searchParam
     * @return mixed
     * @throws GuzzleException
     * @throws Exception
     */
    public function processMovie($movieID, $searchParam)
    {
        // Check if movie exist in database
        if ($this->checkIfMovieExists($movieID)) {

            // If movie exists, update movie
            return $this->updateMovie($movieID);
        } else {
            // If movie does not exist, call movie API
            try {
                return $this->fetchMovieDetails($movieID, $searchParam);
            } catch (Exception $exception) {
                throw new Exception($exception->getMessage());
            }
        }
    }

    /**
     * @param $movieID
     * @param $searchParam
     * @return array
     * @throws Exception|GuzzleException
     */
    public function fetchMovieDetails($movieID, $searchParam): array
    {
        try {
            // Calling movie api service
            return $this
                ->getMovies($movieID, $searchParam);
        } catch (Exception $exception) {
            throw new Exception('Movie Details fetched from api failed');
        }
    }

    // check if movie exists in database

    /**
     * @param $movieID
     * @return bool
     */
    public function checkIfMovieExists($movieID): bool
    {
        return !empty(
        $this
            ->em
            ->getRepository(Movie::class)
            ->findByID($movieID)
        );
    }

    // Save Movie

    /**
     * @param $film
     * @return Movie
     * @throws Exception
     */
    public function saveMovie($film, $imdbID = null): Movie
    {
        $movie = new Movie();

        $date = new DateTime($film['released']);
        $date->format('d M Y');

        $movie->setTitle($film['title']); //Respective entity methods
        $movie->setPlot($film['plot']);
        $movie->setDirector($film['director']);
        $movie->setReleasedDate($date);
        $movie->setImdbID($film['imdbID']);

        $posterName = $this->saveImage($film);

        $movie->setPoster($posterName);
        $this->em->persist($movie);
        $this->em->flush();

        foreach ($film['Genre'] as $genre) {
            // check if Movie Category exists
            $category = $this->checkIfCategoryExist($genre);

            if (!$category) {
                // Insert new category in category table
                $cat = new Category();
                $cat->setName(trim($genre));
                $this->em->persist($cat);

                // Insert in genre table
                $genre = new Genre();
                $genre->setCategoryId($cat);
                $this->em->persist($genre);
            } else {
                // Set catID from Category table to catID in genre table
                $genre = new Genre();
                $genre->setCategoryId($category);
            }

            //Set movieID in genre table
            $genre->setMovieId($movie);
            $this->em->persist($genre);
        }

        $this->em->flush();

        return $movie;
    }

    /**
     * @param $film
     * @return string
     */
    private function saveImage($film): string
    {
        if (empty($film['poster'])) {
            return 'not_found.png';
        }

        $posterName = uniqid() . '.jpg';

        $content = file_get_contents($film['poster']);
        $fp = fopen(
            $this->parameterBag->get('kernel.project_dir') . "/public/uploads/poster/" . $posterName,
            "w"
        );
        fwrite($fp, $content);
        fclose($fp);

        return $posterName;
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function updateMovie($movieID)
    {
        $result = $this
            ->em
            ->getRepository(Movie::class)
            ->findByID($movieID);

        try {
            $film = $this->fetchMovieDetails($movieID, 'i');

            $date = \DateTime::createFromFormat(
                'd M Y',
                $film['Released']
            );
            $result->setTitle($film['Title']);
            $result->setPlot($film['Plot']);
            $result->setDirector($film['Director']);
            $result->setReleasedDate($date);
            $result->setImdbID($film['imdbID']);

            $posterName = $this->saveImage($film);

            $result->setPoster($posterName);

            foreach ($film['Genre'] as $genre) {
                // check if Movie Category exists
                $category = $this->checkIfCategoryExist($genre);

                if (!$category) {
                    // Insert new category in category table
                    $cat = new Category();
                    $cat->setName(trim($genre));
                    $this->em->persist($cat);

                    // Insert in genre table
                    $genre = new Genre();
                    $genre->setCategoryId($cat);
                    $this->em->persist($genre);
                }
//                else {
//                    // update category id in table genre
//                    $genre = $this->CheckGenre($movieID, $category);
//
//                    if (!$genre) {
//                        $genre->setCategoryId($category);
//                        $this->em->persist($genre);
//                        $this->em->flush();
//                    }
//                }
            }

            $this->em->persist($result);
            $this->em->flush();

            return $result;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * @param $cat
     * @return Category|null
     */
    public function checkIfCategoryExist($cat): ?Category
    {
        return
            $this
                ->em
                ->getRepository(Category::class)
                ->findByCategory($cat)
                ->getOneOrNullResult();
    }

    public function checkGenre($movieID, $category)
    {
        return
            $this
                ->em
                ->getRepository(Genre::class)
                ->findByGenre($movieID, $category);
    }
}