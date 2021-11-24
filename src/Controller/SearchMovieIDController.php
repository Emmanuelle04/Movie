<?php

namespace App\Controller;

use Exception;
use SensioLabs\AnsiConverter\AnsiToHtmlConverter;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
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
     */
    public function getMovie(Request $request, MovieService $movieService, KernelInterface $kernel): Response
    {
        $application = new Application($kernel);
        $application->setAutoExit(false);


//        get id from search bar
        $movieID = $request->get('search');

//            call command from controller
        $input = new ArrayInput([
            'command' => 'app:movie-synchronise',
            'movie_id' => $movieID ?? '',
            'searchParam' =>'i'
        ]);

        $output = new BufferedOutput(
            OutputInterface::VERBOSITY_NORMAL,
            true
        );

        $converter = new AnsiToHtmlConverter();

        $application->run($input, $output);

//        return new Response($output->fetch());


        return $this->render('search_movie_id/index.html.twig', [
            'controller_name' => 'SearchMovieIDController',
        ]);
    }
}
