<?php

namespace App\Command;

use App\Entity\Movie;
use App\Service\MovieService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use function PHPUnit\Framework\throwException;

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
     * @var string[]
     */
    public $movieList = [
        "Captain America: The First Avenger",
        "Captain Marvel",
        "Iron Man",
        "Iron Man 2",
        "The Incredible Hulk",
        "Thor",
        "The Avengers",
        "Iron Man 3",
        "Thor: The Dark World",
        "Captain America: The Winter Soldier",
        "Guardians of the Galaxy",
        "Guardians of the Galaxy 2",
        "Avengers: Age of Ultron",
        "Ant-Man",
        "Captain America: Civil War",
        "Black Widow",
        "Spider-Man: Homecoming",
        "Doctor Strange",
        "Black Panther",
        "Thor: Ragnarok",
        "Avengers: Infinity War",
        "Ant-Man and the Wasp",
        "Avengers: Endgame",
        "Shang-Chi and the Legend of the Ten Rings",
        "Spider-Man: Far From Home",
        "Eternals"
    ];

    /**
     * @param MovieService $movieService
     * @param string|null $name
     */
    public function __construct(
        MovieService           $movieService,
        string                 $name = null
    )
    {
        $this->movieService = $movieService;

        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addArgument('movie_id', InputArgument::OPTIONAL, 'The imdbID of the Movie')
            ->addArgument('searchParam', InputArgument::OPTIONAL, 'The parameter for the Movie API');
    }

    /**
     * @throws GuzzleException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $movieID = $input->getArgument('movie_id');
        $searchParam = $input->getArgument('searchParam');

        if ($movieID) {
            $output->writeln([
                sprintf('<info>Movie detected => %s, Search Parameter detected => %s</info>', $movieID, $searchParam),
                'Fetch Movie details from API in progress'
            ]);

            try {
                $this->movieService->processMovie($movieID, $searchParam);

            } catch (Exception $exception) {
                $output->writeln([
                    "<comment>{$exception->getMessage()}</comment>",
                ]);

                return false;
            }

            $output->writeln([
                'Movie saved'
            ]);

            return true;
        }

//        foreach ($this->movieList as $movieID) {
//           $this->processMovie($output, $movieID, $searchParam);
//        }

        return true;
    }

}
