<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Vehicle;
use App\Entity\Model;
use App\Entity\Brand;
use App\Entity\RegistrationCertificate;
use App\Entity\User;
use App\Entity\VehiclePhoto;
use App\Enum\FuelTypeEnum;
use App\Enum\GearboxTypeEnum;
use App\Enum\PhotoTypeEnum;
use App\Enum\VehicleCategoryEnum;
use App\Enum\VehicleStatusEnum;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;
use Symfony\Component\HttpFoundation\File\File;

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

        $vehicleImagesDirectory = __DIR__ . '/../../assets/img/vehicles';
        $vehicleImages = array_diff(scandir($vehicleImagesDirectory), ['.', '..']); // Récupère toutes les images sauf "." et ".."

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
                for ($i = 0; $i < 3; $i++) { // Ajoute deux véhicules par modèle pour varier les données

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

                    $bigCities = [
                        'Paris' => 75000,
                        'Lyon' => 69000,
                        'Marseille' => 13000,
                        'Toulouse' => 31000,
                        'Nice' => 06000,
                        'Nantes' => 44000,
                        'Strasbourg' => 67000,
                        'Montpellier' => 34000,
                        'Bordeaux' => 33000,
                        'Lille' => 59000,
                    ];
                    $city = $faker->randomElement(array_keys($bigCities));
                    $postalCode = $bigCities[$city];

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
                        ->setCity($city)
                        ->setPostalCode($postalCode)
                        ->setStatus($faker->randomElement(
                            VehicleStatusEnum::cases()
                        ))
                    ;

                    for ($j = 0; $j < 5; $j++) {
                        // Sélectionner une image aléatoire du dossier
                        $randomImage = $faker->randomElement($vehicleImages);

                        // Créer une nouvelle instance de VehiclePhoto
                        $photo = new VehiclePhoto();
                        $photo->setVehicle($vehicle);
                        $photo->setImagePath($randomImage);
                        $photo->setType(PhotoTypeEnum::VEHICLE); // Assigner le type de photo
                        $photo->setCreatedAt(new \DateTimeImmutable());
                        $photo->setUpdatedAt(new \DateTimeImmutable());

                        // Définir le chemin du fichier
                        $imageFile = new File($vehicleImagesDirectory . '/' . $randomImage);
                        $photo->setImageFile($imageFile);

                        // Note : VichUploaderBundle gère le déplacement du fichier et l'attribution du nom.
                        // Ici, on peut laisser le champ imagePath vide, il sera rempli par VichUploader.

                        $manager->persist($photo);
                    }

                    $manager->persist($registrationCertificate);
                    $manager->persist($vehicle);
                }
            }
        }

        $manager->flush();
    }
}
