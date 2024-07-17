<?php

namespace App\Entity;

use App\Repository\VehicleConditionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VehicleConditionRepository::class)]
class VehicleCondition
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

    #[ORM\Column]
    private ?int $mileage = null;

    #[ORM\Column]
    private ?int $fuelLevel = null;

    #[ORM\Column(type: 'text')]
    private ?string $condition = null;

    #[ORM\Column(type: 'string', length: 10)]
    private ?string $type = null; // 'entry' or 'exit'

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $createdAt = null;

    public function getRental(): ?Rental
    {
        return $this->rental;
    }

    public function setRental(?Rental $rental): static
    {
        $this->rental = $rental;

        return $this;
    }

    public function getMileage(): ?int
    {
        return $this->mileage;
    }

    public function setMileage(int $mileage): static
    {
        $this->mileage = $mileage;

        return $this;
    }

    public function getFuelLevel(): ?int
    {
        return $this->fuelLevel;
    }

    public function setFuelLevel(int $fuelLevel): static
    {
        $this->fuelLevel = $fuelLevel;

        return $this;
    }

    public function getCondition(): ?string
    {
        return $this->condition;
    }

    public function setCondition(string $condition): static
    {
        $this->condition = $condition;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
