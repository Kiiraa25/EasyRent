<?php

// namespace App\Entity;

// use App\Enum\RoleEnum;
// use App\Repository\UserRepository;
// use Doctrine\ORM\Mapping as ORM;
// use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
// use Symfony\Component\Security\Core\User\UserInterface as UserUserInterface;

// #[ORM\Entity(repositoryClass: UserRepository::class)]
// class OldUser
// {
//     #[ORM\Id]
//     #[ORM\GeneratedValue]
//     #[ORM\Column]
//     private ?int $id = null;

//     #[ORM\Column(length: 100)]
//     private ?string $email = null;

//     #[ORM\Column(length: 255)]
//     private ?string $password = null;

//     #[ORM\Column]
//     private ?bool $isActive = null;

//     #[ORM\Column(enumType: RoleEnum::class)]
//     private ?RoleEnum $type = null;

//     #[ORM\OneToOne(targetEntity: UserProfile::class, inversedBy: 'user', cascade: ['persist', 'remove'])]
//     private $profile;

//     public function getId(): ?int
//     {
//         return $this->id;
//     }

//     public function getEmail(): ?string
//     {
//         return $this->email;
//     }

//     public function setEmail(string $email): static
//     {
//         $this->email = $email;

//         return $this;
//     }

//     public function getPassword(): ?string
//     {
//         return $this->password;
//     }

//     public function setPassword(string $password): static
//     {
//         $this->password = $password;

//         return $this;
//     }

//     public function isActive(): ?bool
//     {
//         return $this->isActive;
//     }

//     public function setActive(bool $isActive): static
//     {
//         $this->isActive = $isActive;

//         return $this;
//     }

//     public function getType(): ?RoleEnum
//     {
//         return $this->type;
//     }

//     public function setType(RoleEnum $type): static
//     {
//         $this->type = $type;

//         return $this;
//     }

//     public function getProfile(): ?UserProfile
//     {
//         return $this->profile;
//     }

//     public function setProfile(UserProfile $profile): self
//     {
//         $this->profile = $profile;

//         if ($profile->getUser() !== $this) {
//             $profile->setUser($this);
//         }

//         return $this;
//     }
// }
