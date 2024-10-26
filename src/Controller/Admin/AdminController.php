<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\UserProfile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
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
        $user->setActive(true);

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
