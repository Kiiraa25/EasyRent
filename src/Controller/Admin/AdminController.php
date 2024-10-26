<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\UserProfile;
use App\Enum\UserStatusEnum;
use App\Repository\RentalRepository;
use App\Repository\UserRepository;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin', name: 'app_admin_')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'dashboard')]
    public function index(
        UserRepository $userRepository,
        RentalRepository $rentalRepository,
        VehicleRepository $vehicleRepository
    ): Response {
        // Calcul des statistiques globales
        $inscriptionsCount = $userRepository->count([]);
        $activeRentalsCount = $rentalRepository->count(['status' => 'ACTIVE']);
        $availableVehiclesCount = $vehicleRepository->count(['status' => 'AVAILABLE']);

        // Obtenir les données mensuelles pour l'année en cours
        $monthlySignups = $userRepository->getMonthlySignupsForCurrentYear();
        $monthlyRentals = $rentalRepository->getMonthlyRentalsForCurrentYear();
        $monthlyRevenues = $rentalRepository->getMonthlyRevenuesForCurrentYear();

        return $this->render('admin/index.html.twig', [
            'inscriptions_count' => $inscriptionsCount,
            'active_rentals_count' => $activeRentalsCount,
            'available_vehicles_count' => $availableVehiclesCount,
            'monthly_signups' => $monthlySignups,
            'monthly_rentals' => $monthlyRentals,
            'monthly_revenues' => $monthlyRevenues,
        ]);
    }


    #[Route('/admin/new', name: 'app_new_admin')]
    public function userAdmin(
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $user = new User();
        $userProfile = new UserProfile();

        $user->setProfile($userProfile);
        $user->setEmail('user@admin.com');
        $hashedPassword = $passwordHasher->hashPassword($user, '111111');
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $user->setStatus(UserStatusEnum::ACTIF);

        $userProfile->setCreatedAt(new \DateTimeImmutable());
        $userProfile->setUpdatedAt(new \DateTimeImmutable());
        $userProfile->setRating(0);
        $userProfile->setFirstName('admin');
        $userProfile->setLastName('admin');
        $userProfile->setVerified(true);

        $entityManager->persist($userProfile);
        $entityManager->persist($user);
        $entityManager->flush();


        // Redirection après la création de l'administrateur
        return $this->redirectToRoute('app_login');
    }
}
