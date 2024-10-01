<?php

namespace App\Entity;

use App\Repository\RegistrationCertificateRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

#[ORM\Entity(repositoryClass: RegistrationCertificateRepository::class)]
#[Vich\Uploadable()]
class RegistrationCertificate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'date')]
    private ?\DateTimeInterface $issueDate = null;

    #[ORM\Column(length: 200)]
    private ?string $certificateNumber = null;

    #[ORM\Column(length: 255)]
    private ?string $frontImagePath = null;

    #[Vich\UploadableField(mapping: 'registrationCertificate', fileNameProperty: 'frontImagePath')]
    private ?File $frontImageFile = null;

    #[ORM\Column(length: 255)]
    private ?string $backImagePath = null;

    #[Vich\UploadableField(mapping: 'registrationCertificate', fileNameProperty: 'backImagePath')]
    private ?File $backImageFile = null;

    #[ORM\Column(length: 100)]
    private ?string $countryOfIssue = null;

    #[ORM\Column(type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $updatedAt = null;

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

    public function getFrontImageFile(): ?File
    {
        return $this->frontImageFile;
    }

    public function setFrontImageFile(?File $frontImageFile): static
    {
        $this->frontImageFile = $frontImageFile;
        if (null !== $frontImageFile){
            $this->updatedAt = new \DateTimeImmutable();
        }

        return $this;
    }
    public function getBackImageFile(): ?File
    {
        return $this->backImageFile;
    }

    public function setBackImageFile(?File $backImageFile): static
    {
        $this->backImageFile = $backImageFile;
        if (null !== $backImageFile){
            $this->updatedAt = new \DateTimeImmutable();
        }

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
}
