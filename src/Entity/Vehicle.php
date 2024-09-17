<?php

namespace App\Entity;

use App\Repository\VehicleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VehicleRepository::class)]
class Vehicle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?RegistrationCertificate $RegistrationCertificate = null;

    #[ORM\ManyToOne(targetEntity: Model::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Model $model = null;

    #[ORM\Column]
    private ?int $year = null;

    #[ORM\Column]
    private ?int $mileage = null;

    #[ORM\Column(type: "text")]
    private ?string $description = null;

    #[ORM\Column(length: 20)]
    private ?string $color = null;

    #[ORM\Column]
    private ?int $mileageAllowance = 200;

    #[ORM\Column]
    private ?int $extraMileageRate = null;

    #[ORM\ManyToOne(targetEntity: Location::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Location $location = null;

    #[ORM\Column(type: "datetime", options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: "datetime", options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'vehicles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Fuel $fuel = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function getRegistrationCertificate(): ?RegistrationCertificate
    {
        return $this->RegistrationCertificate;
    }

    public function setRegistrationCertificate(RegistrationCertificate $RegistrationCertificate): static
    {
        $this->RegistrationCertificate = $RegistrationCertificate;

        return $this;
    }

    public function getModel(): ?Model
    {
        return $this->model;
    }

    public function setModel(?Model $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): static
    {
        $this->year = $year;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getMileageAllowance(): ?int
    {
        return $this->mileageAllowance;
    }

    public function setMileageAllowance(int $mileageAllowance): static
    {
        $this->mileageAllowance = $mileageAllowance;

        return $this;
    }

    public function getExtraMileageRate(): ?int
    {
        return $this->extraMileageRate;
    }

    public function setExtraMileageRate(int $extraMileageRate): static
    {
        $this->extraMileageRate = $extraMileageRate;

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): static
    {
        $this->location = $location;

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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getFuel(): ?Fuel
    {
        return $this->fuel;
    }

    public function setFuel(?Fuel $fuel): static
    {
        $this->fuel = $fuel;

        return $this;
    }

 
}
