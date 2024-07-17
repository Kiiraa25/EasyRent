<?php

namespace App\Entity;

use App\Repository\ModelRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModelRepository::class)]
class Model
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\ManyToOne(targetEntity: VehicleType::class)]
    private $vehicleType;

    #[ORM\ManyToOne(targetEntity: Brand::class)]
    private $brand;

    #[ORM\ManyToOne(targetEntity: FuelType::class)]
    private $fuelType;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getVehicleType(): ?VehicleType
    {
        return $this->vehicleType;
    }

    public function setVehicleType(?VehicleType $vehicleType): self
    {
        $this->vehicleType = $vehicleType;
        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): self
    {
        $this->brand = $brand;
        return $this;
    }

    public function getFuelType(): ?FuelType
    {
        return $this->fuelType;
    }

    public function setFuelType(?FuelType $fuelType): self
    {
        $this->fuelType = $fuelType;
        return $this;
    }
}
