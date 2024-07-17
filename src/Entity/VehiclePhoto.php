<?php

namespace App\Entity;

use App\Repository\VehiclePhotoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VehiclePhotoRepository::class)]
class VehiclePhoto
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[ORM\ManyToOne(targetEntity: Vehicle::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Vehicle $vehicle = null;

    #[ORM\ManyToOne(targetEntity: VehicleCondition::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?VehicleCondition $condition = null;

    #[ORM\Column(length: 255)]
    private ?string $url = null;

    public function getVehicle(): ?Vehicle
    {
        return $this->vehicle;
    }

    public function setVehicle(?Vehicle $vehicle): static
    {
        $this->vehicle = $vehicle;

        return $this;
    }

    public function getCondition(): ?VehicleCondition
    {
        return $this->condition;
    }

    public function setCondition(?VehicleCondition $condition): static
    {
        $this->condition = $condition;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }
}
