<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\UserProfile;
use App\Entity\DrivingLicense;
use Faker\Factory;

class UserProfileFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Récupération de tous les profils utilisateurs existants
        $userProfiles = $manager->getRepository(UserProfile::class)->findAll();

        foreach ($userProfiles as $userProfile) {

            $userProfile
            ->setAddress($faker->streetAddress)
            ->setPostalCode((int) str_replace(' ', '', $faker->postcode))
            ->setCity($faker->city)
            ->setCountry($faker->country)
            ->setPhone($faker->phoneNumber)
            ->setBirthDate($faker->dateTimeBetween('-70 years', '-18 years'))
            ->setDescription($faker->paragraph);


            // Création du permis de conduire associé
            $drivingLicense = new DrivingLicense();
            $drivingLicense->setIssueDate($faker->dateTimeBetween('-50 years', '-2 year'))
                           ->setExpiryDate($faker->dateTimeBetween('now', '+30 years'))
                           ->setLicenseNumber($faker->regexify('[A-Z0-9]{10}'))
                           ->setFrontImagePath('uploads/drivingLicense/img-20240721-wa0006-66d836b399354572405217.jpg')
                           ->setBackImagePath('uploads/drivingLicense/img-20240721-wa0006-66d836b399354572405217.jpg')
                           ->setCountryOfIssue($faker->country)
                           ->setUserProfile($userProfile)
                           ->setCreatedAt(new \DateTimeImmutable())
                           ->setUpdatedAt(new \DateTimeImmutable());

            // Associer le permis de conduire au profil utilisateur
            $userProfile->setDrivingLicense($drivingLicense);

            // Persister les entités
            $manager->persist($drivingLicense);
            $manager->persist($userProfile);
        }

        $manager->flush();
    }
}
