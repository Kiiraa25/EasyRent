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
use App\Enum\GearBoxTypeEnum;
use App\Enum\PhotoTypeEnum;
use App\Enum\VehicleCategoryEnum;
use App\Enum\VehicleStatusEnum;
use App\Service\Api\DataGouvAddressService;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;
use Symfony\Component\HttpFoundation\File\File;

class VehicleFixtures extends Fixture
{

    private DataGouvAddressService $dataGouvAddressService;

    public function __construct(DataGouvAddressService $dataGouvAddressService)
    {
        $this->dataGouvAddressService = $dataGouvAddressService;
    }

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

        // Tableau d'adresses existant réellement 
        $addresses = [
            ['address' => '5 Rue de Rivoli', 'postal_code' => '75004', 'city' => 'Paris'],
            ['address' => '18 Rue de la République', 'postal_code' => '69002', 'city' => 'Lyon'],
            ['address' => '22 Cours Jean Ballard', 'postal_code' => '13001', 'city' => 'Marseille'],
            ['address' => '10 Rue de Strasbourg', 'postal_code' => '67000', 'city' => 'Strasbourg'],
            ['address' => '15 Rue Saint-Ferréol', 'postal_code' => '13001', 'city' => 'Marseille'],
            ['address' => '50 Rue de Rennes', 'postal_code' => '75006', 'city' => 'Paris'],
            ['address' => '33 Avenue des Champs-Élysées', 'postal_code' => '75008', 'city' => 'Paris'],
            ['address' => '7 Rue Sainte-Catherine', 'postal_code' => '33000', 'city' => 'Bordeaux'],
            ['address' => '42 Boulevard Saint-Germain', 'postal_code' => '75005', 'city' => 'Paris'],
            ['address' => '14 Rue du Parlement', 'postal_code' => '33000', 'city' => 'Bordeaux'],
            ['address' => '8 Place de la Comédie', 'postal_code' => '34000', 'city' => 'Montpellier'],
            ['address' => '5 Rue du Rempart Saint-Etienne', 'postal_code' => '31000', 'city' => 'Toulouse'],
            ['address' => '35 Rue de Metz', 'postal_code' => '31000', 'city' => 'Toulouse'],
            ['address' => '18 Rue Sainte', 'postal_code' => '13001', 'city' => 'Marseille'],
            ['address' => '25 Boulevard Victor Hugo', 'postal_code' => '06000', 'city' => 'Nice'],
            ['address' => '22 Rue d\'Alsace-Lorraine', 'postal_code' => '31000', 'city' => 'Toulouse'],
            ['address' => '24 Rue Léon Gambetta', 'postal_code' => '59000', 'city' => 'Lille'],
            ['address' => '6 Place Masséna', 'postal_code' => '06000', 'city' => 'Nice'],
            ['address' => '9 Rue de Béthune', 'postal_code' => '59000', 'city' => 'Lille'],
            ['address' => '3 Place du Capitole', 'postal_code' => '31000', 'city' => 'Toulouse'],
            ['address' => '48 Rue Esquermoise', 'postal_code' => '59800', 'city' => 'Lille'],
            ['address' => '40 Rue Faidherbe', 'postal_code' => '59800', 'city' => 'Lille'],
            ['address' => '20 Boulevard Haussmann', 'postal_code' => '75009', 'city' => 'Paris'],
            ['address' => '15 Place Rihour', 'postal_code' => '59800', 'city' => 'Lille'],
            ['address' => '30 Avenue Jean Médecin', 'postal_code' => '06000', 'city' => 'Nice'],
            ['address' => '11 Rue du Faubourg Saint-Honoré', 'postal_code' => '75008', 'city' => 'Paris'],
            ['address' => '16 Rue Sainte-Catherine', 'postal_code' => '33000', 'city' => 'Bordeaux'],
            ['address' => '22 Rue du Mirail', 'postal_code' => '33000', 'city' => 'Bordeaux'],
            ['address' => '17 Rue des Changes', 'postal_code' => '31000', 'city' => 'Toulouse'],
            ['address' => '21 Rue Saint-Jean', 'postal_code' => '69005', 'city' => 'Lyon'],
            ['address' => '38 Rue de la Monnaie', 'postal_code' => '59800', 'city' => 'Lille'],
            ['address' => '6 Rue Bonaparte', 'postal_code' => '75006', 'city' => 'Paris'],
            ['address' => '12 Avenue Thiers', 'postal_code' => '06000', 'city' => 'Nice'],
            ['address' => '13 Rue Masséna', 'postal_code' => '06000', 'city' => 'Nice'],
            ['address' => '39 Rue Saint-Rome', 'postal_code' => '31000', 'city' => 'Toulouse'],
            ['address' => '26 Rue d\'Italie', 'postal_code' => '13100', 'city' => 'Aix-en-Provence'],
            ['address' => '15 Place d\'Albertas', 'postal_code' => '13100', 'city' => 'Aix-en-Provence'],
            ['address' => '27 Rue de la Loge', 'postal_code' => '13002', 'city' => 'Marseille'],
            ['address' => '23 Rue Victor Hugo', 'postal_code' => '69002', 'city' => 'Lyon'],
            ['address' => '6 Rue des Dominicains', 'postal_code' => '38000', 'city' => 'Grenoble'],
            ['address' => '9 Cours Berriat', 'postal_code' => '38000', 'city' => 'Grenoble'],
            ['address' => '31 Place Kléber', 'postal_code' => '67000', 'city' => 'Strasbourg'],
            ['address' => '5 Rue des Grandes Arcades', 'postal_code' => '67000', 'city' => 'Strasbourg'],
            ['address' => '8 Rue de l\'Église', 'postal_code' => '51100', 'city' => 'Reims'],
            ['address' => '10 Rue d\'Isly', 'postal_code' => '34000', 'city' => 'Montpellier'],
            ['address' => '17 Boulevard Jean Jaurès', 'postal_code' => '92100', 'city' => 'Boulogne-Billancourt'],
            ['address' => '18 Place de l\'Hôtel de Ville', 'postal_code' => '42000', 'city' => 'Saint-Étienne'],
            ['address' => '16 Rue Gambetta', 'postal_code' => '72000', 'city' => 'Le Mans'],
            ['address' => '27 Rue de la Madeleine', 'postal_code' => '33000', 'city' => 'Bordeaux'],
            ['address' => '22 Rue des Dominicains', 'postal_code' => '38000', 'city' => 'Grenoble']
        ];

        $brandRepo = $manager->getRepository(Brand::class);
        $modelRepo = $manager->getRepository(Model::class);

        $vehicleImagesDirectory = __DIR__ . '/../../public/uploads/vehiclePhoto';
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
                for ($i = 0; $i < 5; $i++) { // Ajoute deux véhicules par modèle pour varier les données

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

                    $address = $faker->randomElement($addresses);

                    $coordinates = $this->dataGouvAddressService->getVehicleCoordinates($address['address'], $address['postal_code']);
                    $latitude = $coordinates['features'][0]['geometry']['coordinates'][1];
                    $longitude = $coordinates['features'][0]['geometry']['coordinates'][0];

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
                        ->setGearboxType($faker->randomElement(GearBoxTypeEnum::cases()))
                        ->setDoors($faker->numberBetween(2, 5))
                        ->setSeats($faker->numberBetween(2, 7))
                        ->setPricePerDay($faker->numberBetween(20, 150))
                        ->setAddress($address['address'])
                        ->setCity($address['city'])
                        ->setLatitude($latitude)
                        ->setLongitude($longitude)
                        ->setPostalCode($address['postal_code'])
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
