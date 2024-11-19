<?php

namespace App\Controller;

use App\Repository\RentalRepository;
use App\Repository\UserRepository;
use App\Repository\VehicleRepository;
use Mobile_Detect;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DashBoardController extends AbstractController
{

  
    #[Route('/user/dashboard', name: 'app_user_dashBoard')]
    #[IsGranted('ROLE_USER')]
    public function index(
        UserRepository $userRepository,
        RentalRepository $rentalRepository,
        VehicleRepository $vehicleRepository
    ): Response {
        // Calcul des statistiques globales
        $rentalsCount = $rentalRepository->count();
        $vehiclesCount = $vehicleRepository->count();

        // Obtenir les données mensuelles pour l'année en cours
        $monthlyRentals = $rentalRepository->getMonthlyRentalsForCurrentYear();
        $monthlyRevenues = $rentalRepository->getMonthlyRevenuesForCurrentYear();
        $globalRevenu = $rentalRepository->calculateTotalRevenue();

        return $this->render('dashBoard/user/statistics.html.twig', [
            'rentals_count' => $rentalsCount,
            'vehicles_count' => $vehiclesCount,
            'globalRevenu' => $globalRevenu,
            'monthly_rentals' => $monthlyRentals,
            'monthly_revenues' => $monthlyRevenues,
        ]);
    }

}
