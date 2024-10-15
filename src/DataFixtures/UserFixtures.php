<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\UserProfile;
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

        for ($i = 0; $i < 7; $i++){
            $user = new User();
            $userProfile = new UserProfile();

            $firstName = $faker->firstName;
            $lastName = $faker->lastName;

            $user
            ->setProfile($userProfile)
            ->setEmail($firstName.$lastName.'@gmail.com')
            ->setActive(true)
            ->setPassword($this->userPasswordHasher->hashPassword(
                $user,
                plainPassword:'000000'
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
