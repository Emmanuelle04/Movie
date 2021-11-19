<?php

namespace App\Command;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use App\Service\MovieService;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class MovieSynchroniseCommand extends Command
{
    /**
     * @var MovieService
     */
    private $movieService;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;

    /**
     * @var string
     */
    protected static $defaultName = 'app:movie-synchronise';

    /**
     * @var string
     */
    protected static $defaultDescription = 'Synchronise movie api with database';

    /**
     * @param MovieService $movieService
     * @param EntityManagerInterface $entityManager
     * @param ParameterBagInterface $parameterBag
     * @param string|null $name
     */
    public function __construct(
        MovieService $movieService,
        EntityManagerInterface $entityManager,
        ParameterBagInterface $parameterBag,
        string $name = null
    ) {
        $this->movieService = $movieService;
        $this->em           = $entityManager;
        $this->parameterBag = $parameterBag;

        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addArgument('movie_name', InputArgument::OPTIONAL, 'The name of the Movie')
        ;
    }

    /**
     * @throws GuzzleException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $movieName = $input->getArgument('movie_name');

        $output->writeln([
            sprintf('<info>Movie detected => %s</info>', $movieName),
            'Fetch Movie details from API in progress'
        ]);

        $film = $this->fetchMovieDetails($movieName, $output);

        if ($this->checkIfMovieExists($movieName)) {
            $output->writeln([
                '<comment>Movie in database detected, exiting</comment>',
            ]);

            return false;
        }

        $output->writeln([
            'Movie Details fetched, saving into database'
        ]);

        $this->saveMovie($film);

        $output->writeln([
            'Movie saved'
        ]);

        return true;
    }

    /**
     * @param $movieName
     * @param $output
     * @return array|false
     * @throws GuzzleException
     */
    private function fetchMovieDetails($movieName, $output)
    {
        try {
            // Calling movie api service
            return $this
                ->movieService
                ->getMovies($movieName)
                ;
        } catch (\Exception $exception) {
            $output->writeln([
                'Movie Details fetched from api failed, exit command'
            ]);

            return false;
        }
    }

    /**
     * @param $movieName
     * @return bool
     */
    private function checkIfMovieExists($movieName): bool
    {
        return !empty(
            $this
                ->em
                ->getRepository(Movie::class)
                ->findByTitleField($movieName)
                ->getResult()
        );
    }

    /**
     * @param $film
     */
    private function saveMovie($film)
    {
        $movie = new Movie();
        $date  = \DateTime::createFromFormat(
            'd M Y',
            $film['Released']
        );
        $movie->setTitle($film['Title']); //Respective entity methods
        $movie->setDescription($film['Plot']);
        $movie->setProducer($film['Director']);
        $movie->setReleasedDate($date);

        $posterName = uniqid() . '.jpg';

        $content    = file_get_contents($film['Poster']);
        $fp         = fopen(
            $this->parameterBag->get('kernel.project_dir') . "/public/uploads/poster/" . $posterName,
            "w"
        );
        fwrite($fp, $content);
        fclose($fp);

        $movie->setPoster($posterName);

        $this->em->persist($movie);
        $this->em->flush();
    }
}
