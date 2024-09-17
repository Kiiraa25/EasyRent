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

    #[ORM\ManyToOne(targetEntity: VehicleCategory::class)]
    private $vehicleCategory;

    #[ORM\ManyToOne(targetEntity: Brand::class)]
    private $brand;

    #[ORM\ManyToOne(targetEntity: Fuel::class)]
    private $fuel;

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

    public function getVehicleCategory(): ?VehicleCategory
    {
        return $this->vehicleCategory;
    }

    public function setVehicleType(?VehicleCategory $vehicleCategory): self
    {
        $this->vehicleCategory = $vehicleCategory;
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

    public function getFuelType(): ?Fuel
    {
        return $this->fuel;
    }

    public function setFuelType(?Fuel $fuel): self
    {
        $this->fuel = $fuel;
        return $this;
    }
}
