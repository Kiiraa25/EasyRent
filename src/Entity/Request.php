<?php

namespace App\Entity;

use App\Repository\RequestRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RequestRepository::class)]
class Request
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'requests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserProfile $userProfile = null;

    #[ORM\OneToOne(inversedBy: 'request', cascade: ['persist', 'remove'])]
    private ?DrivingLicense $drivingLicense = null;

    #[ORM\OneToOne(inversedBy: 'request', cascade: ['persist', 'remove'])]
    private ?IDCard $idCard = null;

    #[ORM\OneToOne(inversedBy: 'request', cascade: ['persist', 'remove'])]
    private ?RegistrationCertificate $registrationCertificate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserProfile(): ?UserProfile
    {
        return $this->userProfile;
    }

    public function setUserProfile(?UserProfile $userProfile): static
    {
        $this->userProfile = $userProfile;

        return $this;
    }

    public function getDrivingLicense(): ?drivingLicense
    {
        return $this->drivingLicense;
    }

    public function setDrivingLicense(?drivingLicense $drivingLicense): static
    {
        $this->drivingLicense = $drivingLicense;

        return $this;
    }

    public function getIdCard(): ?IDCard
    {
        return $this->idCard;
    }

    public function setIdCard(?IDCard $idCard): static
    {
        $this->idCard = $idCard;

        return $this;
    }

    public function getRegistrationCertificate(): ?registrationCertificate
    {
        return $this->registrationCertificate;
    }

    public function setRegistrationCertificate(?registrationCertificate $registrationCertificate): static
    {
        $this->registrationCertificate = $registrationCertificate;

        return $this;
    }
}
