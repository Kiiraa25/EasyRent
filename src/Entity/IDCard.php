<?php

namespace App\Entity;

use App\Repository\IDCardRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IDCardRepository::class)]
class IDCard
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $issueDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $expiryDate = null;

    #[ORM\Column(length: 150)]
    private ?string $IDNumber = null;

    #[ORM\OneToOne(mappedBy: 'idCard', cascade: ['persist', 'remove'])]
    private ?Request $request = null;

    #[ORM\Column(length: 255)]
    private ?string $frontImagePath = null;

    #[ORM\Column(length: 255)]
    private ?string $backImagePath = null;

    #[ORM\Column(length: 100)]
    private ?string $countryOfIssue = null;

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

    public function getIDNumber(): ?string
    {
        return $this->IDNumber;
    }

    public function setIDNumber(string $IDNumber): static
    {
        $this->IDNumber = $IDNumber;

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
            $this->request->setIdCard(null);
        }

        // set the owning side of the relation if necessary
        if ($request !== null && $request->getIdCard() !== $this) {
            $request->setIdCard($this);
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

    public function getCountryOfIssue(): ?string
    {
        return $this->countryOfIssue;
    }

    public function setCountryOfIssue(string $countryOfIssue): static
    {
        $this->countryOfIssue = $countryOfIssue;

        return $this;
    }
}
