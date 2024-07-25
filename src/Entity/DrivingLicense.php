<?php

namespace App\Entity;

use App\Repository\DrivingLicenseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DrivingLicenseRepository::class)]
class DrivingLicense
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'date')]
    private ?\DateTimeInterface $issueDate = null;

    #[ORM\Column(type: 'date')]
    private ?\DateTimeInterface $expiryDate = null;

    #[ORM\Column(length: 50)]
    private ?string $licenseNumber = null;

    #[ORM\Column(length: 255)]
    private ?string $frontImagePath = null;

    #[ORM\Column(length: 255)]
    private ?string $backImagePath = null;

    #[ORM\Column(length: 100)]
    private ?string $countryOfIssue = null;

    #[ORM\OneToOne(inversedBy: 'drivingLicense', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserProfile $userProfile = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIssueDate(): ?\DateTimeInterface
    {
        return $this->issueDate;
    }

    public function setIssueDate(\DateTimeInterface $issueDate): static
    {
        $this->issueDate = $issueDate;

        return $this;
    }

    public function getExpiryDate(): ?\DateTimeInterface
    {
        return $this->expiryDate;
    }

    public function setExpiryDate(\DateTimeInterface $expiryDate): static
    {
        $this->expiryDate = $expiryDate;

        return $this;
    }

    public function getLicenseNumber(): ?string
    {
        return $this->licenseNumber;
    }

    public function setLicenseNumber(string $licenseNumber): static
    {
        $this->licenseNumber = $licenseNumber;

        return $this;
    }

    public function getFrontImagePath(): ?string
    {
        return $this->frontImagePath;
    }

    public function setFrontImagePath(string $frontImagePath): static
    {
        $this->frontImagePath = $frontImagePath;

        return $this;
    }

    public function getBackImagePath(): ?string
    {
        return $this->backImagePath;
    }

    public function setBackImagePath(string $backImagePath): static
    {
        $this->backImagePath = $backImagePath;

        return $this;
    }

    public function getCountryOfIssue(): ?string
    {
        return $this->countryOfIssue;
    }

    public function setCountryOfIssue(string $countryOfIssue): static
    {
        $this->countryOfIssue = $countryOfIssue;

        return $this;
    }

    public function getUserProfile(): ?UserProfile
    {
        return $this->userProfile;
    }

    public function setUserProfile(UserProfile $userProfile): static
    {
        $this->userProfile = $userProfile;

        return $this;
    }
}
