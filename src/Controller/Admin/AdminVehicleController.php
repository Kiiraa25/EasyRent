<?php

namespace App\Controller\Admin;

use App\Entity\Rating;
use App\Entity\Vehicle;
use App\Enum\VehicleStatusEnum;
use App\Form\AdminForms\VehicleType;
use App\Repository\RatingRepository;
use App\Repository\RentalRepository;
use App\Repository\VehicleRepository;
use App\Service\DataGouvAddressService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin', name: 'app_admin_')]
#[IsGranted('ROLE_ADMIN')]
class AdminVehicleController extends AbstractController
{
    #[Route('/vehicles', name: 'vehicles')]
    public function index(VehicleRepository $vehicleRepository): Response
    {
        $vehicles = $vehicleRepository->findAll();

        // Trier les véhicules par statut
        usort($vehicles, function ($a, $b) {
            $order = [
                VehicleStatusEnum::ACTIVE->value => 1,
                VehicleStatusEnum::WAITING_FOR_VALIDATION->value => 2,
                VehicleStatusEnum::SUSPENDED->value => 3,
                VehicleStatusEnum::ARCHIVED->value => 4,
            ];

            return $order[$a->getStatus()->value] <=> $order[$b->getStatus()->value];
        });

        return $this->render('dashBoard/admin/vehicle_features/vehicles.html.twig', [
            'vehicles' => $vehicles,
        ]);
    }

    // Route pour afficher le détail d'un véhicule
#[Route('/vehicle/{id}', name: 'vehicle_show')]
public function show(Vehicle $vehicle, RentalRepository $rentalRepository, RatingRepository $reviewRepository): Response
{
    // Récupérer les réservations du véhicule
    $rentals = $rentalRepository->findBy(['vehicle' => $vehicle]);

    // Récupérer les avis pour ce véhicule
    $reviews = $reviewRepository->findAll();

    return $this->render('dashBoard/admin/vehicle_features/vehicle_show.html.twig', [
        'vehicle' => $vehicle,
        'rentals' => $rentals,
        'reviews' => $reviews,
    ]);
}


   // Route pour éditer un véhicule
#[Route('/vehicle/edit/{id}', name: 'vehicle_edit')]
public function edit(Vehicle $vehicle, Request $request, EntityManagerInterface $entityManager, DataGouvAddressService $dataGouvAddressService): Response
{
    // Création du formulaire avec les données du véhicule
    $form = $this->createForm(VehicleType::class, $vehicle);
    $form->handleRequest($request);

    // Vérification de la soumission et de la validité du formulaire
    if ($form->isSubmitted() && $form->isValid()) {
        // Récupération de l'adresse modifiée
        $newAddress = [
            'address' => $vehicle->getAddress(),
            'postal_code' => $vehicle->getPostalCode(),
            'city' => $vehicle->getCity(),
        ];

        // Obtenir les coordonnées de la nouvelle adresse si elle est modifiée
        $coordinates = $dataGouvAddressService->getVehicleCoordinates($newAddress['address'], $newAddress['postal_code']);

        // Vérifier si les coordonnées ont été retournées et les définir
        if (!empty($coordinates['features'])) {
            $latitude = $coordinates['features'][0]['geometry']['coordinates'][1] ?? null;
            $longitude = $coordinates['features'][0]['geometry']['coordinates'][0] ?? null;

            if ($latitude && $longitude) {
                $vehicle->setLatitude($latitude);
                $vehicle->setLongitude($longitude);
            }
        }

        // Mise à jour du véhicule dans la base de données
        $entityManager->flush();

        // Message de confirmation
        $this->addFlash('success', 'Les informations du véhicule ont été mises à jour avec succès.');

        // Redirection vers la liste des véhicules après l'édition
        return $this->redirectToRoute('app_admin_vehicles');
    }

    // Affichage du formulaire d'édition de véhicule
    return $this->render('dashBoard/admin/vehicle_features/vehicle_edit.html.twig', [
        'form' => $form,
        'vehicle' => $vehicle,
    ]);
}


    // Route pour activer/suspendre un véhicule
    #[Route('/vehicle/toggle-status/{id}', name: 'vehicle_toggle_status', methods: ['POST'])]
    public function toggleStatus(Vehicle $vehicle, EntityManagerInterface $entityManager): RedirectResponse
    {
        $newStatus = $vehicle->getStatus() === VehicleStatusEnum::ACTIVE
            ? VehicleStatusEnum::SUSPENDED
            : VehicleStatusEnum::ACTIVE;

        $vehicle->setStatus($newStatus);
        $vehicle->setUpdatedAt(new \DateTimeImmutable());
        $entityManager->flush();

        return $this->redirectToRoute('app_admin_vehicles');
    }

    // Route pour Archiver un véhicule. ne pas supprimer car cela supprimerait ses données
    #[Route('/vehicle/delete/{id}', name: 'vehicle_delete')]
    public function delete(Vehicle $vehicle, EntityManagerInterface $entityManager): RedirectResponse
    {
        $vehicle->setStatus(VehicleStatusEnum::ARCHIVED);
        $vehicle->setUpdatedAt(new \DateTimeImmutable());
        $entityManager->flush();

        return $this->redirectToRoute('app_admin_vehicles');
    }
}
