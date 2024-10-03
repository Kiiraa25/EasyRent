<?php

namespace App\Controller;

use App\Entity\Rental;
use App\Enum\RentalStatusEnum;
use App\Form\RentalType;
use App\Repository\RentalRepository;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;

#[Route('/rental')]
class RentalController extends AbstractController
{
    #[Route('/', name: 'app_rental_index', methods: ['GET'])]
    public function index(RentalRepository $rentalRepository): Response
    {
        return $this->render('rental/index.html.twig', [
            'rentals' => $rentalRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_rental_new', methods: ['GET', 'POST'])]
    public function new(Request $request, VehicleRepository $vehicleRepository , EntityManagerInterface $entityManager, Security $security): Response
    {

        $user = $security->getUser();

    if (!$user) {
        throw $this->createAccessDeniedException('Vous devez être connecté pour louer un véhicule.');
    }

        // Récupérer l'ID du véhicule depuis l'URL
    $vehicleId = $request->query->get('vehicle_id');
    
    // Récupérer le véhicule correspondant dans la base de données
    $vehicle = $vehicleRepository->find($vehicleId);
    
    if (!$vehicle) {
        throw $this->createNotFoundException('Véhicule non trouvé.');
    }

    // Créer la nouvelle réservation
    $rental = new Rental();
    $rental->setVehicle($vehicle);  // Associer le véhicule à la réservation

    $form = $this->createForm(RentalType::class, $rental);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $startDate = $rental->getStartDate();
        $endDate = $rental->getEndDate();

        if ($startDate && $endDate) {
            $diff = $startDate->diff($endDate);
            $days = $diff->days;

            // Récupérer le prix par jour du véhicule
            $vehicle = $rental->getVehicle();

            $pricePerDay = $vehicle->getPricePerDay();
            $mileageAllowance = $vehicle->getMileageAllowance();

            // Calculer le totalPrice
            $totalPrice = $days * $pricePerDay;
            $mileageLimit = $days * $mileageAllowance;

            // Mettre à jour le champ totalPrice
            $rental->setTotalPrice($totalPrice);
            $rental->setMileageLimit($mileageLimit);
            $rental->setRenter($user);
            $rental->setStatus(RentalStatusEnum::EN_ATTENTE_VALIDATION);
            $rental->setCreatedAt(new \DateTimeImmutable());
            $rental->setUpdatedAt(new \DateTimeImmutable());

        }
        
        $entityManager->persist($rental);
        $entityManager->flush();

        return $this->redirectToRoute('app_rental_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('rental/new.html.twig', [
        'rental' => $rental,
        'form' => $form,
    ]);
    }

    #[Route('/{id}', name: 'app_rental_show', methods: ['GET'])]
    public function show(Rental $rental): Response
    {
        return $this->render('rental/show.html.twig', [
            'rental' => $rental,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_rental_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Rental $rental, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RentalType::class, $rental);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_rental_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('rental/edit.html.twig', [
            'rental' => $rental,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_rental_delete', methods: ['POST'])]
    public function delete(Request $request, Rental $rental, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rental->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($rental);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_rental_index', [], Response::HTTP_SEE_OTHER);
    }
}
