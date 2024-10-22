<?php

namespace App\Controller;

use App\Entity\Rental;
use App\Enum\RentalStatusEnum;
use App\Enum\CancelledByEnum;
use App\Form\RentalType;
use App\Entity\User;
use App\Form\EditRentalType;
use App\Form\CancelRentalType;
use App\Repository\RentalRepository;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class RentalController extends AbstractController
{
    // ROUTE ADMIN ->
    // #[Route('/', name: 'app_rental_index', methods: ['GET'])]
    // public function index(RentalRepository $rentalRepository): Response
    // {
    //     return $this->render('rental/index.html.twig', [
    //         'rentals' => $rentalRepository->findAll(),
    //     ]);
    // }

    // CREATE DEMANDE DE LOCATION
    #[Route('/rental/new', name: 'app_rental_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, VehicleRepository $vehicleRepository, EntityManagerInterface $entityManager, Security $security): Response
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

        if ($vehicle->getOwner() === $user) {
            throw $this->createNotFoundException('Vous ne pouvez pas louer votre propre véhicule.');
        }


        // Créer la nouvelle réservation
        $rental = new Rental();
        $rental->setVehicle($vehicle);  // Associer le véhicule à la réservation

        $startDate = new \DateTime($request->query->get('startDate'));
        $endDate = new \DateTime($request->query->get('endDate'));

        $form = $this->createForm(RentalType::class, $rental, [
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $today = new \DateTimeImmutable();

            if ($startDate && $endDate) {

                if ($startDate < $today || $endDate < $today || $endDate < $startDate) {
                    $this->addFlash('error', 'Les dates saisies ne sont pas valides');
                    return $this->redirectToRoute('app_rental_new', ['vehicle_id' => $vehicleId]);
                }

                // recupérer les locations existantes pour ce véhicule pour vérifier sa disponibilité
                $existingRentals = $vehicle->getRentals()
                    ->filter(function ($rental) use ($startDate, $endDate) {
                        return $rental->getStatus() !== RentalStatusEnum::ANNULEE &&
                            $startDate <= $rental->getEndDate() &&
                            $endDate >= $rental->getStartDate();
                    });

                if (!$existingRentals->isEmpty()) {
                    $this->addFlash('error', 'Ce véhicule n\'est pas disponible pour ces dates.');
                    return $this->redirectToRoute('app_rental_new', ['vehicle_id' => $vehicleId]);
                }



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
            $this->addFlash('success', 'Votre demande de location a été créée avec succès !');

            return $this->redirectToRoute('app_user_rental_requests', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('rental/new.html.twig', [
            'rental' => $rental,
            'form' => $form,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }

    // READ LOCATIONS DE L'UTILISATEUR
    #[Route('/user/rentals', name: 'app_user_rentals', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function userRentals(RentalRepository $rentalRepository, Request $request): Response
    {

        // Récupérer l'utilisateur connecté
        $user = $this->getUser();


        // Vérifier si l'utilisateur est bien connecté
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        $filter = $request->query->get('filter', 'renter');
        $status = $request->query->get('status', 'a_venir');

        //status utilisés lors d'une location
        $rentalStatuses = [
            RentalStatusEnum::VALIDEE->value,
            RentalStatusEnum::EN_COURS->value,
            RentalStatusEnum::TERMINEE->value,
            RentalStatusEnum::ANNULEE->value,
        ];

        $rentals = $rentalRepository->createQueryBuilder('r');

        if ($filter === 'owner') {
            $rentals->join('r.vehicle', 'v')
                ->where('v.owner = :user')
                ->setParameter('user', $user);
        } else {
            $rentals->where('r.renter = :user')
                ->setParameter('user', $user);
        }

        // Appliquer le filtre de statut indépendamment
        switch ($status) {
            case 'a_venir':
                $rentals->andWhere('r.status = :valide')
                    ->setParameter('valide', RentalStatusEnum::VALIDEE->value);
                break;
            case 'en_cours':
                $rentals->andWhere('r.status = :en_cours')
                    ->setParameter('en_cours', RentalStatusEnum::EN_COURS->value);
                break;
            case 'termine':
                $rentals->andWhere('(r.status = :termine OR r.status = :annule)')
                    ->setParameter('termine', RentalStatusEnum::TERMINEE->value)
                    ->setParameter('annule', RentalStatusEnum::ANNULEE->value);

                break;
            case 'all':
                $rentals->andWhere('r.status IN (:statuses)')
                    ->setParameter('statuses', $rentalStatuses);
                break;
        }

        $rentals = $rentals->getQuery()->getResult();


        return $this->render('rental/userRentals.html.twig', [
            'rentals' => $rentals,
            'filter' => $filter,
            'status' => $status
        ]);
    }

    // READ DEMANDES DE LOCATIONS DE L'UTILISATEUR onwer ou renter
    #[Route('/user/rental_requests', name: 'app_user_rental_requests', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function userRentalRequests(RentalRepository $rentalRepository, Request $request): Response
    {

        $user = $this->getUser();
        $filter = $request->query->get('filter', 'renter');

        // Vérifier si l'utilisateur est bien connecté
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        $requestStatuses = [
            RentalStatusEnum::EN_ATTENTE_VALIDATION->value,
            RentalStatusEnum::REFUSEE->value,
            RentalStatusEnum::EXPIREE->value,
            RentalStatusEnum::DEMANDE_ANNULEE->value,
        ];

        if ($filter === 'owner') {
            $rentalRequests = $rentalRepository->createQueryBuilder('r')
                ->join('r.vehicle', 'v')
                ->where('v.owner = :user')
                ->andWhere('r.status IN (:statuses)')
                ->setParameter('user', $user)
                ->setParameter('statuses', $requestStatuses)
                ->getQuery()
                ->getResult();
        } else {
            $rentalRequests = $rentalRepository->createQueryBuilder('r')
                ->where('r.renter = :user')
                ->andWhere('r.status IN (:statuses)')
                ->setParameter('user', $user)
                ->setParameter('statuses', $requestStatuses)
                ->getQuery()
                ->getResult();
        }

        return $this->render('rental/userRentalRequests.html.twig', [
            'rentalRequests' => $rentalRequests,
            'filter' => $filter,
        ]);
    }


    // READ LOCATION/DEMANDE DE LOCATION
    #[Route('/rental/{id}', name: 'app_rental_show', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function show(Rental $rental): Response
    {
        return $this->render('rental/show.html.twig', [
            'rental' => $rental,
        ]);
    }

    // UPDATE LOCATION/DEMANDE DE LOCATION
    #[Route('rental/{id}/edit', name: 'app_rental_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function edit(Request $request, Rental $rental, EntityManagerInterface $entityManager): Response
    {

        $user = $this->getUser();
        $renter = $rental->getRenter();

        if (!$renter instanceof User || $user !== $renter) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à accéder à cette page.');
        }

        if (!$rental) {
            throw $this->createNotFoundException('Location non trouvé.');
        }

        $form = $this->createForm(EditRentalType::class, $rental);
        $form->handleRequest($request);

        $requestStatuses = [
            RentalStatusEnum::EN_ATTENTE_VALIDATION->value,
            RentalStatusEnum::VALIDEE->value,
            RentalStatusEnum::EN_COURS->value
        ];

        if ($form->isSubmitted() && $form->isValid() && in_array($rental->getStatus()->value, $requestStatuses)) {
            $startDate = $rental->getStartDate();
            $endDate = $rental->getEndDate();
            $pricePerDay = $rental->getVehicle()->getPricePerDay();
            $mileageAllowance = $rental->getVehicle()->getMileageAllowance();

            if ($startDate && $endDate) {
                // Vérification : La date de fin doit être supérieure à la date de début
                if ($endDate < $startDate) {
                    $this->addFlash('error', 'La date de fin doit être postérieure à la date de début.');
                    return $this->redirectToRoute('app_rental_edit', ['id' => $rental->getId()]);
                }

                $diff = $startDate->diff($endDate);
                $days = $diff->days;

                $totalPrice = $days * $pricePerDay;
                $mileageLimit = $days * $mileageAllowance;

                // Mettre à jour les champs totalPrice et mileageLimit
                $rental->setTotalPrice($totalPrice);
                $rental->setMileageLimit($mileageLimit);
                $rental->setUpdatedAt(new \DateTimeImmutable());

                // Enregistrer les modifications
                $entityManager->flush();
            }



            // Redirection selon le statut de la demande de location
            if ($rental->getStatus()->value === RentalStatusEnum::EN_ATTENTE_VALIDATION->value) {
                return $this->redirectToRoute('app_user_rental_requests', [], Response::HTTP_SEE_OTHER);
            } else {
                return $this->redirectToRoute('app_user_rentals', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('rental/edit.html.twig', [
            'rental' => $rental,
            'form' => $form,
        ]);
    }

    // UPDATE ACCEPTER UNE DEMANDE DE LOCATION EN TANT QUE OWNER
    #[Route('rental/{id}/accept', name: 'app_rental_accept', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function accept(Request $request, Rental $rental, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $owner = $rental->getVehicle()->getOwner();

        if (!$owner instanceof User || $user !== $owner) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à accéder à cette page.');
        }

        if (!$rental) {
            throw $this->createNotFoundException('Demande de location non trouvée.');
        }

        // Vérifie que le statut est en attente avant d'accepter
        if ($rental->getStatus()->value === RentalStatusEnum::EN_ATTENTE_VALIDATION->value) {
            $rental->setStatus(RentalStatusEnum::VALIDEE);  // Accepte la demande
            $rental->setUpdatedAt(new \DateTimeImmutable());

            $entityManager->flush();

            $this->addFlash('success', 'Demande de location acceptée avec succès.');
            return $this->redirectToRoute('app_user_rental_requests', [], Response::HTTP_SEE_OTHER);
        } else {
            $this->addFlash('error', 'Cette demande de location ne peut pas être acceptée.');
            return $this->redirectToRoute('app_user_rental_requests');
        }
    }

    // UPDATE ACCEPTER UNE DEMANDE DE LOCATION EN TANT QUE OWNER
    #[Route('rental/{id}/refuse', name: 'app_rental_refuse', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function refuse(Request $request, Rental $rental, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $owner = $rental->getVehicle()->getOwner();

        if (!$owner instanceof User || $user !== $owner) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à accéder à cette page.');
        }

        if (!$rental) {
            throw $this->createNotFoundException('Demande de location non trouvée.');
        }

        // Vérifie que le statut est en attente avant d'accepter
        if ($rental->getStatus()->value === RentalStatusEnum::EN_ATTENTE_VALIDATION->value) {
            $rental->setStatus(RentalStatusEnum::REFUSEE);
            $rental->setUpdatedAt(new \DateTimeImmutable());

            $entityManager->flush();

            $this->addFlash('success', 'Demande de location acceptée avec succès.');
            return $this->redirectToRoute('app_user_rental_requests', [], Response::HTTP_SEE_OTHER);
        } else {
            $this->addFlash('error', 'Cette demande de location ne peut pas être refusée.');
            return $this->redirectToRoute('app_user_rental_requests');
        }
    }

    // DELETE --> mettre en statut "annulé"
    #[Route('/rental/{id}/cancel', name: 'app_rental_cancel')]
    #[IsGranted('ROLE_USER')]
    public function Cancel(Request $request, Rental $rental, EntityManagerInterface $entityManager): Response
    {
        $currentStatus = $rental->getStatus();
        $currentUser = $this->getUser();
        $vehicle = $rental->getVehicle();


        if (!$currentUser instanceof User) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        if ($vehicle->getOwner() !== $currentUser && $rental->getRenter() !== $currentUser) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à accéder à cette page.');
        }


        if (!$vehicle) {
            throw $this->createNotFoundException('Véhicule non trouvé.');
        }

        $cancelForm = $this->createForm(CancelRentalType::class, $rental);
        $cancelForm->handleRequest($request);

        if ($cancelForm->isSubmitted() && $cancelForm->isValid()) {
            if ($currentStatus === RentalStatusEnum::EN_ATTENTE_VALIDATION) {
                $rental->setStatus(RentalStatusEnum::DEMANDE_ANNULEE);
            } else if ($currentStatus === RentalStatusEnum::VALIDEE || $currentStatus === RentalStatusEnum::EN_COURS) {
                $rental->setStatus(RentalStatusEnum::ANNULEE);
            }
            if ($currentUser === $vehicle->getOwner()) {
                $rental->setCancelledBy(CancelledByEnum::OWNER);
            } else if ($currentUser === $rental->getRenter()) {
                $rental->setCancelledBy(CancelledByEnum::RENTER);
            }

            $rental->setUpdatedAt(new \DateTimeImmutable());

            // $entityManager->persist($rental);
            $entityManager->flush();

            $this->addFlash('success', 'Location annulée avec succès.');
            return $this->redirectToRoute('app_user_rentals');
        }
        return $this->render('rental/cancelRental.html.twig', [
            'cancelForm' => $cancelForm,
        ]);
    }
}
