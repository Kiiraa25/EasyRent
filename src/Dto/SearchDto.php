<?php

namespace App\Dto;

use App\Enum\FuelTypeEnum;
use App\Enum\GearboxTypeEnum;
use App\Enum\VehicleCategoryEnum;
use DateTimeInterface;

class SearchDto
{
    private ?string $search = null;
    private ?DateTimeInterface $startDate = null;
    private ?DateTimeInterface $endDate = null;
    private ?VehicleCategoryEnum $vehicleCategory = null;
    private ?GearboxTypeEnum $gearboxType = null;
    private ?FuelTypeEnum $fuelType = null;
    private ?int $totalPrice = null;

    // Getters and Setters

    public function getSearch(): ?string
    {
        return $this->search;
    }

    public function setSearch(?string $search): self
    {
        $this->search = $search;
        return $this;
    }

    public function getStartDate(): ?DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(?DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function getEndDate(): ?DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;
        return $this;
    }

    public function getVehicleCategory(): ?VehicleCategoryEnum
    {
        return $this->vehicleCategory;
    }

    public function setVehicleCategory(?VehicleCategoryEnum $vehicleCategory): self
    {
        $this->vehicleCategory = $vehicleCategory;
        return $this;
    }

    public function getGearboxType(): ?GearboxTypeEnum
    {
        return $this->gearboxType;
    }

    public function setGearboxType(?GearboxTypeEnum $gearboxType): self
    {
        $this->gearboxType = $gearboxType;
        return $this;
    }

    public function getFuelType(): ?FuelTypeEnum
    {
        return $this->fuelType;
    }

    public function setFuelType(?FuelTypeEnum $fuelType): self
    {
        $this->fuelType = $fuelType;
        return $this;
    }

    public function getTotalPrice(): ?int
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(?int $totalPrice): self
    {
        $this->totalPrice = $totalPrice;
        return $this;
    }
}
