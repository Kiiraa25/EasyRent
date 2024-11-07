<?php

namespace App\Controller\Admin;

use App\Entity\Rental;
use App\Enum\CancelledByEnum;
use App\Enum\RentalStatusEnum;
use App\Form\AdminForms\RentalType;
use App\Repository\RentalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin', name: 'app_admin_')]
#[IsGranted('ROLE_ADMIN')]
class AdminRentalController extends AbstractController
{

    // Afficher toutes les locations
    #[Route('/rentals', name: 'rentals')]
    public function index(RentalRepository $rentalRepository): Response
    {
       // Récupérer toutes les locations
       $rentals = $rentalRepository->findAll();

       // Trier les locations par statut
       usort($rentals, function ($a, $b) {
           $order = [
                RentalStatusEnum::EN_COURS->value => 1,
                RentalStatusEnum::VALIDEE->value => 2,
                RentalStatusEnum::EN_ATTENTE_VALIDATION->value => 3,
                RentalStatusEnum::TERMINEE->value => 4,
                RentalStatusEnum::REFUSEE->value => 5,
                RentalStatusEnum::ANNULEE->value => 6,
                RentalStatusEnum::EXPIREE->value => 7,
                RentalStatusEnum::DEMANDE_ANNULEE->value => 8,
           ];

           return $order[$a->getStatus()->value] <=> $order[$b->getStatus()->value];
       });

       return $this->render('dashBoard/admin/rental_features/rentals.html.twig', [
           'rentals' => $rentals,
       ]);
    }

    // Afficher une location
    #[Route('/rental/{id}', name: 'rental_show')]
    public function show(Rental $rental): Response
    {
        return $this->render('dashBoard/admin/rental_features/rental_show.html.twig', [
            'rental' => $rental,
        ]);
    }

    // Modifier une location
    #[Route('/rental/edit/{id}', name: 'rental_edit')]
    public function edit(Request $request, Rental $rental, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RentalType::class, $rental);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rental->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_rentals');
        }

        return $this->render('dashBoard/admin/rental_features/rental_edit.html.twig', [
            'form' => $form,
            'rental' => $rental,
        ]);
    }

    // Modifier statut d'une location (annuler, valider)
    #[Route('/rental/toggle-status/{id}', name: 'rental_toggle_status')]
    public function toggleStatus(Rental $rental, EntityManagerInterface $entityManager): Response
    {
        $newStatus = $rental->getStatus() === RentalStatusEnum::VALIDEE || $rental->getStatus() === RentalStatusEnum::EN_COURS
            ? RentalStatusEnum::ANNULEE
            : RentalStatusEnum::VALIDEE;

        $rental->setStatus($newStatus);
        $rental->setCancelledBy(CancelledByEnum::ADMIN);
        $entityManager->flush();

        return $this->redirectToRoute('app_admin_rentals');
    }
}
