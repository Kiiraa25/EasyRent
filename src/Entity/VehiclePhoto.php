<?php

namespace App\Entity;

use App\Repository\VehiclePhotoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use App\Enum\PhotoTypeEnum;

#[ORM\Entity(repositoryClass: VehiclePhotoRepository::class)]
#[Vich\Uploadable()]
class VehiclePhoto
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Vehicle::class, inversedBy: 'photos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Vehicle $vehicle = null;

    #[ORM\ManyToOne(targetEntity: VehicleCondition::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?VehicleCondition $condition = null;

    #[ORM\Column(length: 255)]
    private ?string $imagePath = null;

    #[Vich\UploadableField(mapping: 'vehiclePhoto', fileNameProperty: 'imagePath')]
    private ?File $imageFile = null;

    #[ORM\Column(type: 'string', length: 20)]
    private ?string $type = null; 

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $updatedAt = null;

    public function __construct(){
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

    public function setVehicle(?Vehicle $vehicle): static
    {
        $this->vehicle = $vehicle;
        return $this;
    }

    public function getCondition(): ?VehicleCondition
    {
        return $this->condition;
    }

    public function setCondition(?VehicleCondition $condition): static
    {
        $this->condition = $condition;
        return $this;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(string $imagePath): static
    {
        $this->imagePath = $imagePath;
        return $this;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile): static
    {
        $this->imageFile = $imageFile;
        if (null !== $imageFile){
            $this->updatedAt = new \DateTimeImmutable();
        }

        return $this;
    }

    public function getType(): ?PhotoTypeEnum
    {
        return $this->type ? PhotoTypeEnum::from($this->type) : null;
    }

    public function setType(PhotoTypeEnum $type): static
    {
        $this->type = $type->value;
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
