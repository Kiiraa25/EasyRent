<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Rental;
use App\Entity\Vehicle;
use App\Entity\User;
use App\Enum\PaymentMethodEnum;
use App\Enum\RentalStatusEnum;
use App\Enum\CancelledByEnum;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class RentalFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $today = new \DateTimeImmutable('today');

        // Récupération des véhicules et utilisateurs existants
        $vehicles = $manager->getRepository(Vehicle::class)->findAll();
        $users = $manager->getRepository(User::class)->findAll();

        
        foreach ($vehicles as $vehicle) {
            // Sélectionner un utilisateur aléatoire pour chaque location
            $renter = $faker->randomElement($users);

            // Choisir un statut pour la location
            $status = $faker->randomElement(RentalStatusEnum::cases());

            // Initialisation des dates en fonction du statut
            switch ($status) {
                case RentalStatusEnum::EN_ATTENTE_VALIDATION:
                    // Start date dans le futur
                    $startDate = $faker->dateTimeBetween('+1 day', '+1 year');
                    $endDate = (clone $startDate)->modify('+' . $faker->numberBetween(1, 14) . ' days');
                    break;

                case RentalStatusEnum::VALIDEE:
                    // Start date dans le futur
                    $startDate = $faker->dateTimeBetween('+1 day', '+1 year');
                    $endDate = (clone $startDate)->modify('+' . $faker->numberBetween(1, 14) . ' days');
                    break;

                case RentalStatusEnum::EN_COURS:
                    // Start date dans le passé et end date dans le futur
                    $startDate = $faker->dateTimeBetween('-25 day', '-1 day');
                    $endDate = $faker->dateTimeBetween('+1 day', '+25 day');
                    break;

                case RentalStatusEnum::TERMINEE:
                    // Start date et end date dans le passé
                    $startDate = $faker->dateTimeBetween('-25 day', '-1 day');
                    $endDate = $faker->dateTimeBetween($startDate, '-1 day');
                    break;

                case RentalStatusEnum::REFUSEE:
                case RentalStatusEnum::ANNULEE:
                case RentalStatusEnum::DEMANDE_ANNULEE:
                case RentalStatusEnum::EXPIREE:
                    // Start date peut être dans le passé ou le futur, end date après le start date
                    $startDate = $faker->dateTimeBetween('-1 year', '+1 year');
                    $endDate = (clone $startDate)->modify('+' . $faker->numberBetween(1, 14) . ' days');
                    break;

                default:
                    // Autres statuts, on utilise des valeurs par défaut
                    $startDate = $faker->dateTimeBetween('-1 year', '+1 year');
                    $endDate = (clone $startDate)->modify('+' . $faker->numberBetween(1, 14) . ' days');
                    break;
            }

            $days = $startDate->diff($endDate)->days;
            $totalPrice = $vehicle->getPricePerDay() * $days;

            $days = $startDate->diff($endDate)->days;
            $mileageLimit = $vehicle->getMileageAllowance() * $days;

            // Créer une location
            $rental = new Rental();
            $rental->setVehicle($vehicle)
                ->setRenter($renter)
                ->setStartDate($startDate)
                ->setEndDate($endDate)
                ->setPaymentMethod($faker->randomElement(PaymentMethodEnum::cases()))
                ->setTotalPrice($totalPrice)
                ->setStatus($status)
                ->setMileageLimit($mileageLimit);

            // Ajouter une raison d'annulation et la personne ayant annulé si la location est annulée
            if (in_array($status, [RentalStatusEnum::ANNULEE, RentalStatusEnum::DEMANDE_ANNULEE])) {
                $rental->setCancellationReason($faker->sentence)
                    ->setCancelledBy($faker->randomElement(CancelledByEnum::cases()));
            }

            $manager->persist($rental);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            VehicleFixtures::class,
            UserFixtures::class
        ];
    }
}
