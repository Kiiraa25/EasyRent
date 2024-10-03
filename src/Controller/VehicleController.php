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

class VehicleController extends AbstractController
{
    #[Route('/vehicle/new', name: 'app_vehicle_new')]
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

            // dd($vehicle);
            
            $entityManager->persist($vehicle);
            $entityManager->flush();

            $this->addFlash('success', 'Votre annonce à été ajoutée avec succès.');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('vehicle/newVehicle.html.twig',[
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
     public function showUserVehicles(VehicleRepository $vehicleRepository): Response
     {
         // Récupérer l'utilisateur connecté
         $user = $this->getUser();
 
         // Vérifier si l'utilisateur est bien connecté
         if (!$user) {
             throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
         }
 
         // Utiliser le repository pour trouver les véhicules de l'utilisateur connecté
         $vehicles = $vehicleRepository->findBy([
             'owner' => $user
         ]);
 
         return $this->render('vehicle/showUserVehicles.html.twig', [
             'vehicles' => $vehicles,
             'user' => $user,
         ]);
     }

     // SHOW ALL VEHICLES
     #[Route('/vehicles', name: 'app_vehicles')]
     public function showVehicles(VehicleRepository $vehicleRepository): Response
     {
 
         // Utiliser le repository pour trouver les véhicules de l'utilisateur connecté
         $vehicles = $vehicleRepository->findAll();
 
         return $this->render('vehicle/showAllVehicles.html.twig', [
             'vehicles' => $vehicles,
         ]);
     }


     // UPDATE
     #[Route('/vehicle/{id}/edit', name: 'app_vehicle_edit')]
     public function edit(Request $request, Vehicle $vehicle, EntityManagerInterface $entityManager): Response
     {
         
        $owner = $vehicle->getOwner();

        if (!$owner instanceof User) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
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

    }