<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Vehicle;
use App\Entity\Model;
use App\Entity\Brand;
use App\Entity\RegistrationCertificate;
use App\Entity\User;
use App\Enum\FuelTypeEnum;
use App\Enum\GearboxTypeEnum;
use App\Enum\VehicleCategoryEnum;
use App\Enum\VehicleStatusEnum;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class VehicleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');


        // Récupération de tous les utilisateurs existants
        $users = $manager->getRepository(User::class)->findAll();


        $brandsAndModels = [
            'Toyota' => ['Corolla', 'Camry', 'Prius'],
            'Honda' => ['Civic', 'Accord', 'CR-V'],
            'Ford' => ['Focus', 'Mustang', 'Explorer'],
            'BMW' => ['3 Series', 'X5', 'Z4'],
            'Audi' => ['A4', 'Q5', 'A6']
        ];

        $brandRepo = $manager->getRepository(Brand::class);
        $modelRepo = $manager->getRepository(Model::class);

        foreach ($brandsAndModels as $brandName => $modelNames) {

            // Vérifier si la marque existe déjà dans la base de données
            $brand = $brandRepo->findOneBy(['name' => $brandName]);
            if (!$brand) {
                $brand = new Brand();
                $brand->setName($brandName);
                $manager->persist($brand);
            }

            foreach ($modelNames as $modelName) {
                // Vérifier si le modèle existe déjà dans la base de données
                $model = $modelRepo->findOneBy(['name' => $modelName, 'brand' => $brand]);
                if (!$model) {
                    $model = new Model();
                    $model->setName($modelName)
                          ->setBrand($brand)
                          ->setVehicleCategory($faker->randomElement(VehicleCategoryEnum::cases()));
                    $manager->persist($model);
                }

                // Créer des véhicules associés au modèle et à la marque
                for ($i = 0; $i < 2; $i++) { // Ajoute deux véhicules par modèle pour varier les données

                    // Création du certificat d'immatriculation
                    $registrationCertificate = new RegistrationCertificate;
                    $registrationCertificate->setIssueDate($faker->dateTimeBetween('-15 years', 'now'))
                        ->setCertificateNumber($faker->regexify('[A-Z0-9]{8}'))
                        ->setFrontImagePath('uploads/drivingLicense/img-20240721-wa0006-66d836b399354572405217.jpg') // chemin vers l'image front
                        ->setBackImagePath('uploads/drivingLicense/img-20240721-wa0006-66d836b399354572405217.jpg')
                        ->setCountryOfIssue($faker->country)
                        ->setCreatedAt(new \DateTimeImmutable())
                        ->setUpdatedAt(new \DateTimeImmutable());


                    $vehicle = new Vehicle();
                    $vehicle->setOwner($faker->randomElement($users))
                        ->setRegistrationCertificate($registrationCertificate)
                        ->setModel($model)
                        ->setMileage($faker->numberBetween(1, 200000))
                        ->setDescription($faker->paragraph)
                        ->setColor($faker->safeColorName)
                        ->setMileageAllowance(200)
                        ->setExtraMileageRate($faker->numberBetween(5, 15))
                        ->setCreatedAt(new \DateTimeImmutable())
                        ->setUpdatedAt(new \DateTimeImmutable())
                        ->setFuelType($faker->randomElement(FuelTypeEnum::cases()))
                        ->setGearboxType($faker->randomElement(GearboxTypeEnum::cases()))
                        ->setDoors($faker->numberBetween(2, 5))
                        ->setSeats($faker->numberBetween(2, 7))
                        ->setPricePerDay($faker->numberBetween(20, 150))
                        ->setAddress($faker->streetAddress)
                        ->setPostalCode((int) str_replace(' ', '', $faker->postcode))
                        ->setCity($faker->city)
                        ->setStatus($faker->randomElement(
                            VehicleStatusEnum::cases()))
                    ;

                    $manager->persist($registrationCertificate);
                    $manager->persist($vehicle);
                }
            }
        }

        $manager->flush();
    }

    
}
