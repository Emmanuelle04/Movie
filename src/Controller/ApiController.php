<?php

namespace App\Controller;

use App\Command\MovieSynchroniseCommand;
use App\Service\MovieService;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;

class ApiController extends AbstractController
{
    /**
     * @Route("/api", name="api")
     * @param Request $request
     * @return RedirectResponse
     * @throws GuzzleException
     */
    public function getMovie(Request $request, MovieService $movieService, MovieSynchroniseCommand $command, LoggerInterface $logger): Response
    {
        // value from search bar
        $movieName = $request->get('title');

        if (!empty($movieName)) {

            try {
                //call function getMovies in service MovieService
                $searchParam = 't';
                $movieDetails = $movieService->getMovies($movieName, $searchParam);

            } catch (\Exception $exception) {
                $this->addFlash('error', 'Movie not found!');

                return $this->render('partials/listapi.html.twig', [
                    'data' => []
                ]);
            }


            return $this->render('partials/listapi.html.twig', [
                'data' => $movieDetails
            ]);

        } else {
            return $this->render('api/index.html.twig', [
                'data' => [],
            ]);
        }
    }
}

