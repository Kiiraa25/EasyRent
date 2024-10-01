<?php

namespace App\Entity;

use App\Enum\VehicleCategoryEnum;
use App\Repository\ModelRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModelRepository::class)]
class Model
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(type: "string", length: 50)]
    private ?string $vehicleCategory = null;

    #[ORM\ManyToOne(targetEntity: Brand::class)]
    private $brand;

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

    public function getVehicleCategory(): ?VehicleCategoryEnum
{
    return $this->vehicleCategory ? VehicleCategoryEnum::from($this->vehicleCategory) : null;
}

public function setVehicleCategory(VehicleCategoryEnum $vehicleCategory): self
{
    $this->vehicleCategory = $vehicleCategory->value;

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
}
