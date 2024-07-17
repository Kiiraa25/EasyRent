<?php

namespace App\Entity;

use App\Repository\SinisterRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SinisterRepository::class)]
class Sinister
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[ORM\ManyToOne(targetEntity: Rental::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Rental $rental = null;

    #[ORM\Column(type: 'text')]
    private ?string $description = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $sinisterDate = null;

    #[ORM\Column(type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $reportDate = null;

    public function getRental(): ?Rental
    {
        return $this->rental;
    }

    public function setRental(?Rental $rental): static
    {
        $this->rental = $rental;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getSinisterDate(): ?\DateTimeInterface
    {
        return $this->sinisterDate;
    }

    public function setSinisterDate(\DateTimeInterface $sinisterDate): static
    {
        $this->sinisterDate = $sinisterDate;

        return $this;
    }

    public function getReportDate(): ?\DateTimeInterface
    {
        return $this->reportDate;
    }

    public function setReportDate(\DateTimeInterface $reportDate): static
    {
        $this->reportDate = $reportDate;

        return $this;
    }
}
