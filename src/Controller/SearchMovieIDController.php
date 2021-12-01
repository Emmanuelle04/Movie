<?php

namespace App\Controller;

use Exception;
use SensioLabs\AnsiConverter\AnsiToHtmlConverter;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\MovieService;
use GuzzleHttp\Exception\GuzzleException;

class SearchMovieIDController extends AbstractController
{
    /**
     * @Route("/search/movie/id", name="search_movie_id")
     * @throws Exception
     * @throws GuzzleException
     */
    public function getMovie(Request $request, MovieService $movieService, KernelInterface $kernel): Response
    {
        //  get movie imdbID from search bar
        $movieID = $request->get('search') ?? '';

        $responseData = [
            'data' => []
        ];

        $listTwigName = 'partials/admin/list.html.twig';

        if (!empty($movieID)) {
            try {
                $results = $movieService->processMovie($movieID, 'i');
            } catch (Exception $exception) {
                $this->addFlash('error', $exception->getMessage());

                return $this->render($listTwigName, $responseData);
            }

            return $this->render('partials/admin/list.html.twig', [
                'data' => $results
            ]);
        }

        return $this->render('search_movie_id/index.html.twig');

    }
}
