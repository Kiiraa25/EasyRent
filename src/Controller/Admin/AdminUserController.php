<?php

namespace App\Controller\Admin;

use App\Entity\Rating;
use App\Entity\Rental;
use App\Entity\User;
use App\Enum\UserStatusEnum;
use App\Form\AdminForms\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin', name: 'app_admin_')]
#[IsGranted('ROLE_ADMIN')]
class AdminUserController extends AbstractController
{
    #[Route('/users', name: 'users')]
    public function index(UserRepository $userRepository): Response
    {
        // Récupérer tous les utilisateurs
        $users = $userRepository->findAll();

        // Trier les utilisateurs par statut
        usort($users, function ($a, $b) {
            $order = [
                UserStatusEnum::ACTIF->value => 1,
                UserStatusEnum::INACTIF->value => 2,
                UserStatusEnum::BANNI->value => 3,
                UserStatusEnum::SUPPRIME->value => 4,
            ];

            return $order[$a->getStatus()->value] <=> $order[$b->getStatus()->value];
        });

        return $this->render('dashBoard/admin/user_features/users.html.twig', [
            'users' => $users,
        ]);
    }

    // ÉDITER UTILISATEUR
    #[Route('/user/edit/{id}', name: 'user_edit')]
    public function edit(User $user, EntityManagerInterface $entityManager, Request $request): Response
    {

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($user->getProfile()) {
                $user->getProfile()->setUpdatedAt(new \DateTimeImmutable());
            }
            $entityManager->flush();

            $this->addFlash('success', 'Informations personnelles mises à jour avec succès.');
            return $this->redirectToRoute('app_admin_users');
        }

        return $this->render('dashBoard/admin/user_features/user_edit.html.twig', [
            'form' => $form,
        ]);
    }


    //  BANNIR/DÉBANNIR
    #[Route('/user/toggle-ban/{id}', name: 'user_toggle_ban', methods: ['POST'])]
    public function toggleBan(User $user, EntityManagerInterface $entityManager): RedirectResponse
    {
        // Bascule le statut entre BANNI et ACTIF
        if ($user->getStatus() === UserStatusEnum::BANNI) {
            $user->setStatus(UserStatusEnum::ACTIF);
            $this->addFlash('success', 'L\'utilisateur a été dé-banni.');
        } else {
            $user->setStatus(UserStatusEnum::BANNI);
            $this->addFlash('success', 'L\'utilisateur a été banni.');
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_admin_users');
    }


    // SUPPRIMER UTILISATEUR (DÉFINIR COMME SUPPRIMÉ)
    #[Route('/user/delete/{id}', name: 'user_delete', methods: ['POST'])]
    public function delete(User $user, EntityManagerInterface $entityManager): RedirectResponse
    {
        // Marquer l'utilisateur comme "supprimé" pour conserver les données
        $user->setStatus(UserStatusEnum::SUPPRIME);
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_admin_users');
    }

     // Afficher le profil utilisateur
    #[Route('/user/{id}', name: 'user_profile')]
    public function show(User $user, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les informations utilisateur, réservations et avis associés
        $reservations = $entityManager->getRepository(Rental::class)->findBy(['renter' => $user]);
        $receivedReviews = $entityManager->getRepository(Rating::class)->findAll();
        $givenReviews = $entityManager->getRepository(Rating::class)->findAll();

        return $this->render('dashBoard/admin/user_features/user_show.html.twig', [
            'user' => $user,
            'reservations' => $reservations,
            'receivedReviews' => $receivedReviews,
            'givenReviews' => $givenReviews,
        ]);
    }
}
