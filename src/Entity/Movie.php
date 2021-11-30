<?php

namespace App\Entity;

use App\Repository\MovieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=MovieRepository::class)
 */
class Movie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message = "Title is required.")
     *
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message = "Description is required.")
     */
    private $description;

    /**
     * @ORM\Column(type="string")
     */
    private $producer;

    /**
     * @ORM\Column(type="date", name="date", nullable=true)
     * @Assert\LessThan("today")
     * @Assert\GreaterThan("1950-01-01")
     */
    private $releasedDate;

    /**
     * @ORM\Column(type="string")
     */
    private $poster;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantity;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $availability;

    /**
     * @ORM\OneToMany(targetEntity=Rental::class, mappedBy="movie")
     */
    private $rentals;

    /**
     * @ORM\Column(nullable=true)
     */
    private $imdbID;

    /**
     * @ORM\OneToMany(targetEntity=Genre::class, mappedBy="movie_id")
     */
    private $genres;

    public function __construct()
    {
        $this->rentals = new ArrayCollection();
        $this->genres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getProducer()
    {
        return $this->producer;
    }

    public function getReleasedDate()
    {
        return $this->releasedDate;
    }

    public function getPoster()
    {
        return $this->poster;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function setProducer($producer)
    {
        $this->producer = $producer;
    }

    public function setReleasedDate($releasedDate)
    {
        // if releasedDate is false, releasedDate is null else value is assigned
        $this->releasedDate = $releasedDate === false ? null : $releasedDate;
    }

    public function setPoster($poster)
    {
        $this->poster = $poster;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getAvailability(): ?int
    {
        return $this->availability;
    }

    public function setAvailability(int $availability): self
    {
        $this->availability = $availability;

        return $this;
    }

    /**
     * @return Collection|Rental[]
     */
    public function getRentals(): Collection
    {
        return $this->rentals;
    }

    public function addRental(Rental $rental): self
    {
        if (!$this->rentals->contains($rental)) {
            $this->rentals[] = $rental;
            $rental->setMovie($this);
        }

        return $this;
    }

    public function removeRental(Rental $rental): self
    {
        if ($this->rentals->removeElement($rental)) {
            // set the owning side to null (unless already changed)
            if ($rental->getMovie() === $this) {
                $rental->setMovie(null);
            }
        }

        return $this;
    }

    public function getImdbID()
    {
        return $this->imdbID;
    }

    public function setImdbID($imdbID): self
    {
        $this->imdbID = $imdbID;

        return $this;
    }

    /**
     * @return Collection|Genre[]
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): self
    {
        if (!$this->genres->contains($genre)) {
            $this->genres[] = $genre;
            $genre->setMovieId($this);
        }

        return $this;
    }

    public function removeGenre(Genre $genre): self
    {
        if ($this->genres->removeElement($genre)) {
            // set the owning side to null (unless already changed)
            if ($genre->getMovieId() === $this) {
                $genre->setMovieId(null);
            }
        }

        return $this;
    }


}
