<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Entity\Vehicle;
use App\Entity\Model;
use App\Entity\RegistrationCertificate;
use App\Form\VehicleType;
use App\Form\EditVehicleType;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\VehicleRepository;
use App\Enum\VehicleStatusEnum;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class VehicleController extends AbstractController
{
    #[Route('/vehicle/new', name: 'app_vehicle_new')]
    #[IsGranted('ROLE_USER')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        $vehicle = new Vehicle();

        $vehicleForm = $this->createForm(VehicleType::class, $vehicle);
        $vehicleForm->handleRequest($request);

        if ($vehicleForm->isSubmitted() && $vehicleForm->isValid()) {
            $vehicle->setCreatedAt(new \DateTimeImmutable());
            $vehicle->setUpdatedAt(new \DateTimeImmutable());
            $vehicle->setOwner($user);
            $vehicle->setStatus(VehicleStatusEnum::WAITING_FOR_VALIDATION);

            $entityManager->persist($vehicle);
            $entityManager->flush();

            $this->addFlash('success', 'Votre annonce à été ajoutée avec succès.');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('vehicle/newVehicle.html.twig', [
            'vehicleForm' => $vehicleForm,
        ]);
    }

    // READ
    #[Route('/vehicle/{id}', name: 'app_vehicle_show')]
    public function show(Vehicle $vehicle): Response
    {
        if (!$vehicle) {
            throw $this->createNotFoundException('Véhicule non trouvé.');
        }

        $owner = $vehicle->getOwner();

        return $this->render('vehicle/showVehicle.html.twig', [
            'vehicle' => $vehicle,
            'owner' => $owner,
        ]);
    }

    // SHOW ALL USER VEHICLES
    #[Route('/user/vehicles', name: 'app_user_vehicles')]
    #[IsGranted('ROLE_USER')]
    public function showUserVehicles(VehicleRepository $vehicleRepository): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Vérifier si l'utilisateur est bien connecté
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        $vehicles = $vehicleRepository->createQueryBuilder('v')
        ->where('v.owner = :owner')
        ->andWhere('v.status IN (:statuses)')
        ->setParameter('owner', $user)
        ->setParameter('statuses', [VehicleStatusEnum::WAITING_FOR_VALIDATION, VehicleStatusEnum::ACTIVE, VehicleStatusEnum::SUSPENDED])
        ->getQuery()
        ->getResult();

    return $this->render('vehicle/showUserVehicles.html.twig', [
        'vehicles' => $vehicles
    ]);
    }

    // SHOW ALL VEHICLES
    #[Route('/vehicles', name: 'app_vehicles')]
    public function showVehicles(VehicleRepository $vehicleRepository): Response
    {

        $vehicles = $vehicleRepository->createQueryBuilder('v')
        ->where('v.status = :active')
        ->setParameter('active', VehicleStatusEnum::ACTIVE)
        ->getQuery()
        ->getResult();

        return $this->render('vehicle/showAllVehicles.html.twig', [
            'vehicles' => $vehicles,
        ]);
    }


    // UPDATE
    #[Route('/vehicle/{id}/edit', name: 'app_vehicle_edit')]
    #[IsGranted('ROLE_USER')]
    public function edit(Request $request, Vehicle $vehicle, EntityManagerInterface $entityManager): Response
    {

        $owner = $vehicle->getOwner();
        $currentUser = $this->getUser();


        if (!$owner instanceof User || $currentUser !== $owner) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à accéder à cette page.');

        }

        if (!$vehicle) {
            throw $this->createNotFoundException('Véhicule non trouvé.');
        }


        $form = $this->createForm(EditVehicleType::class, $vehicle);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $vehicle->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->flush();

            $this->addFlash('success', 'Véhicule mis à jour avec succès.');
            return $this->redirectToRoute('app_vehicle_show', ['id' => $vehicle->getId()]);
        }

        return $this->render('vehicle/editVehicle.html.twig', [
            'form' => $form,
            'vehicle' => $vehicle,
        ]);
    }

    // DELETE --> mettre en statut "supprimé" ou "archivé"
    #[Route('/vehicle/{id}/delete', name: 'app_vehicle_delete')]
    #[IsGranted('ROLE_USER')]
    public function delete(Request $request, Vehicle $vehicle, EntityManagerInterface $entityManager): Response
    {
        $owner = $vehicle->getOwner();
        $currentUser = $this->getUser();


        if (!$owner instanceof User || $currentUser == !$owner) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        if (!$vehicle) {
            throw $this->createNotFoundException('Véhicule non trouvé.');
        }

        $vehicle->setStatus(VehicleStatusEnum::ARCHIVED);
        $vehicle->setUpdatedAt(new \DateTimeImmutable());

        $entityManager->persist($vehicle);
        $entityManager->flush();

        $this->addFlash('success', 'Véhicule supprimé avec succès.');
        return $this->redirectToRoute('app_user_vehicles');

    }
}
