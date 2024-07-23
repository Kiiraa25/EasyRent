<?php

namespace App\Entity;

use App\Repository\RegistrationCertificateRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RegistrationCertificateRepository::class)]
class RegistrationCertificate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $issueDate = null;

    #[ORM\Column(length: 200)]
    private ?string $certificateNumber = null;

    #[ORM\OneToOne(mappedBy: 'registrationCertificate', cascade: ['persist', 'remove'])]
    private ?Request $request = null;

    #[ORM\Column(length: 255)]
    private ?string $frontImagePath = null;

    #[ORM\Column(length: 255)]
    private ?string $backImagePath = null;

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

    public function getCertificateNumber(): ?string
    {
        return $this->certificateNumber;
    }

    public function setCertificateNumber(string $certificateNumber): static
    {
        $this->certificateNumber = $certificateNumber;

        return $this;
    }

    public function getRequest(): ?Request
    {
        return $this->request;
    }

    public function setRequest(?Request $request): static
    {
        // unset the owning side of the relation if necessary
        if ($request === null && $this->request !== null) {
            $this->request->setRegistrationCertificate(null);
        }

        // set the owning side of the relation if necessary
        if ($request !== null && $request->getRegistrationCertificate() !== $this) {
            $request->setRegistrationCertificate($this);
        }

        $this->request = $request;

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
}
