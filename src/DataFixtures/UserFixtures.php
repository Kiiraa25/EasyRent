<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\UserProfile;
use App\Enum\UserStatusEnum;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Création des statuts spécifiques
        $statuses = [
            UserStatusEnum::BANNI, UserStatusEnum::BANNI,  // 2 bannis
            UserStatusEnum::SUPPRIME,                      // 1 supprimé
            UserStatusEnum::INACTIF,                       // 1 inactif
            UserStatusEnum::ACTIF, UserStatusEnum::ACTIF,  // 5 actifs
            UserStatusEnum::ACTIF, UserStatusEnum::ACTIF,
            UserStatusEnum::ACTIF, UserStatusEnum::ACTIF
        ];

        foreach ($statuses as $status) {
            $user = new User();
            $userProfile = new UserProfile();

            $firstName = $faker->firstName;
            $lastName = $faker->lastName;

            $user
                ->setProfile($userProfile)
                ->setEmail($firstName . '.' . $lastName . '@gmail.com')
                ->setStatus($status) // Définir le statut
                ->setPassword($this->userPasswordHasher->hashPassword(
                    $user,
                    '000000'
                ));

            $userProfile
                ->setLastName($lastName)
                ->setFirstName($firstName);

            $manager->persist($user);
            $manager->persist($userProfile);
        }

        $manager->flush();
    }
}
