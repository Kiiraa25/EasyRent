<?php

namespace App\Entity;

use App\Repository\VehicleRepository;
use App\Enum\VehicleStatusEnum;
use App\Enum\FuelTypeEnum;
use App\Enum\GearboxTypeEnum;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


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

    #[ORM\ManyToOne(targetEntity: Model::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Model $model = null;

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

    #[ORM\Column(type: "datetime")]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(type: 'string', length: 20)]
    private ?string $fuelType = null;

    #[ORM\Column(type: 'string', length: 20)]
    private ?string $gearboxType = null;

    #[ORM\Column(type: 'integer')]
    private ?int $doors = null;

    #[ORM\Column(type: 'integer')]
    private ?int $seats = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?float $pricePerDay = null;

    #[ORM\Column(type: 'string', length: 255)]
    private $address;

    #[ORM\Column(type: 'integer', length: 5)]
    private $postalCode;

    #[ORM\Column(type: 'string', length: 255)]
    private $city;

    #[ORM\OneToMany(targetEntity: Rental::class, mappedBy: 'vehicle', cascade: ['persist', 'remove'])]
    private Collection $rentals;

    public function __construct()
    {
        $this->rentals = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }


    public function getRentals(): Collection
    {
        return $this->rentals;
    }

    public function addRental(Rental $rental): self
    {
        if (!$this->rentals->contains($rental)) {
            $this->rentals[] = $rental;
            $rental->setVehicle($this);
        }

        return $this;
    }

    public function removeRental(Rental $rental): self
    {
        if ($this->rentals->removeElement($rental)) {

            if ($rental->getVehicle() === $this) {
                $rental->setVehicle(null);
            }
        }

        return $this;
    }


    #[ORM\Column(type: 'string')]
    private ?string $status = null;

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

    // Getter pour fuelType avec enum
    public function getFuelType(): ?FuelTypeEnum
    {
        return $this->fuelType ? FuelTypeEnum::from($this->fuelType) : null;
    }

    // Setter pour fuelType avec enum
    public function setFuelType(FuelTypeEnum $fuelType): self
    {
        $this->fuelType = $fuelType->value;

        return $this;
    }

    // Getter pour gearboxType avec enum
    public function getGearboxType(): ?GearboxTypeEnum
    {
        return $this->gearboxType ? GearboxTypeEnum::from($this->gearboxType) : null;
    }

    // Setter pour gearboxType avec enum
    public function setGearboxType(GearboxTypeEnum $gearboxType): self
    {
        $this->gearboxType = $gearboxType->value;

        return $this;
    }

    // Getter pour doors (nombre de portes)
    public function getDoors(): ?int
    {
        return $this->doors;
    }

    // Setter pour doors
    public function setDoors(int $doors): self
    {
        $this->doors = $doors;

        return $this;
    }

    // Getter pour seats (nombre de sièges)
    public function getSeats(): ?int
    {
        return $this->seats;
    }

    // Setter pour seats
    public function setSeats(int $seats): self
    {
        $this->seats = $seats;

        return $this;
    }

    // Getter pour pricePerDay (prix par jour)
    public function getPricePerDay(): ?float
    {
        return $this->pricePerDay;
    }

    // Setter pour pricePerDay (prix par jour)
    public function setPricePerDay(float $pricePerDay): self
    {
        $this->pricePerDay = $pricePerDay;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPostalCode(): ?int
    {
        return $this->postalCode;
    }

    public function setPostalCode(int $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getStatus(): ?VehicleStatusEnum
    {
        return $this->status ? VehicleStatusEnum::from($this->status) : null;
    }

    public function setStatus(VehicleStatusEnum $status): self
    {
        $this->status = $status->value;

        return $this;
    }
}
