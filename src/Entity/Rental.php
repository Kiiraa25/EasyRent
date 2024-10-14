<?php

namespace App\Entity;

use App\Repository\RentalRepository;
use App\Enum\RentalStatusEnum;
use App\Enum\CancelledByEnum;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Enum\PaymentMethodEnum;

#[ORM\Entity(repositoryClass: RentalRepository::class)]
class Rental
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Vehicle::class, inversedBy:"rentals")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Vehicle $vehicle = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $renter = null;

    #[ORM\Column(type: 'date')]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: 'date')]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(type: 'string', length: 40)]
    private ?string $paymentMethod = null;

    #[ORM\Column(type: 'integer')]
    private ?int $totalPrice = null;

    #[ORM\Column(type: 'string')]
    private ?string $status = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column]
    private ?int $mileageLimit = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $cancellationReason = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cancelledBy = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVehicle(): ?Vehicle
    {
        return $this->vehicle;
    }

    public function setVehicle(?Vehicle $vehicle): self
    {
        $this->vehicle = $vehicle;

        return $this;
    }

    public function getRenter(): ?User
    {
        return $this->renter;
    }

    public function setRenter(?User $renter): self
    {
        $this->renter = $renter;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getPaymentMethod(): ?PaymentMethodEnum
    {
        return $this->paymentMethod ? PaymentMethodEnum::from($this->paymentMethod) : null;
    }

    public function setPaymentMethod(?PaymentMethodEnum $paymentMethod): static
    {
        $this->paymentMethod = $paymentMethod->value;

        return $this;
    }

    public function getTotalPrice(): ?int
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(int $totalPrice): static
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }


    public function getStatus(): ?RentalStatusEnum
    {
        return $this->status ? RentalStatusEnum::from($this->status) : null;
    }

    public function setStatus(RentalStatusEnum $status): self
    {
        $this->status = $status->value;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getMileageLimit(): ?int
    {
        return $this->mileageLimit;
    }

    public function setMileageLimit(int $mileageLimit): static
    {
        $this->mileageLimit = $mileageLimit;

        return $this;
    }

    public function getCancellationReason(): ?string
    {
        return $this->cancellationReason;
    }

    public function setCancellationReason(string $cancellationReason): static
    {
        $this->cancellationReason = $cancellationReason;

        return $this;
    }

    public function getCancelledBy(): ?CancelledByEnum
    {
        return $this->cancelledBy ? CancelledByEnum::from($this->cancelledBy) : null;
    }

    public function setCancelledBy(?CancelledByEnum $cancelledBy): static
    {
        $this->cancelledBy = $cancelledBy?->value;

        return $this;
    }
}
