<?php

namespace App\Entity;

use App\Repository\RentalRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Exception;

/**
 * @ORM\Entity(repositoryClass=RentalRepository::class)
 */
class Rental
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $rentalDate;

    /**
     * @ORM\Column(type="date")
     */
    private $dueDate;

    /**
     * @ORM\ManyToOne(targetEntity=Movie::class, inversedBy="rentals")
     * @ORM\JoinColumn(nullable=false)
     */
    private $movie;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="rentals")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRentalDate(): ?DateTimeInterface
    {
        return $this->rentalDate;
    }

    public function setRentalDate(DateTimeInterface $rentalDate): self
    {
        $this->rentalDate = $rentalDate;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     * @throws Exception
     */
    public function getDueDate(): ?DateTimeInterface
    {
        return $this->dueDate;
    }

    /**
     * @param DateTimeInterface $dueDate
     * @return $this
     */
    public function setDueDate(DateTimeInterface $dueDate): self
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    public function getMovie(): ?Movie
    {
        return $this->movie;
    }

    public function setMovie(?Movie $movie): self
    {
        $this->movie = $movie;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
