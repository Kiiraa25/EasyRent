<?php

namespace App\Controller;
use App\Entity\User;
use App\Entity\UserProfile;
use App\Form\UserType;
use App\Enum\RoleEnum;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\ChangeEmailType;
use App\Form\ChangePasswordType;
use App\Form\DeleteAccountType;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserController extends AbstractController
{

  //UPDATE EMAIL & PASSWORD // DELETE USER
  #[Route('/user/edit', name: 'app_user_edit')]
    public function edit(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        $changeEmailForm = $this->createForm(ChangeEmailType::class, $user);
        $changePasswordForm = $this->createForm(ChangePasswordType::class);
        $deleteAccountForm = $this->createForm(DeleteAccountType::class);

        $changeEmailForm->handleRequest($request);
        $changePasswordForm->handleRequest($request);
        $deleteAccountForm->handleRequest($request);

        if ($changeEmailForm->isSubmitted() && $changeEmailForm->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Votre email a été mis à jour avec succès.');
            return $this->redirectToRoute('app_user_edit');
        }

        if ($changePasswordForm->isSubmitted() && $changePasswordForm->isValid()) {
            $newPassword = $changePasswordForm->get('newPassword')->getData();
            $user->setPassword($passwordHasher->hashPassword($user, $newPassword));
            $entityManager->flush();
            $this->addFlash('success', 'Votre mot de passe a été changé avec succès.');
            return $this->redirectToRoute('app_user_edit');
        }

        if ($deleteAccountForm->isSubmitted() && $deleteAccountForm->isValid()) {
            $password = $deleteAccountForm->get('password')->getData();
            if ($passwordHasher->isPasswordValid($user, $password)) {

                $tokenStorage->setToken(null);
                $request->getSession()->invalidate();

                $entityManager->remove($user);
                $entityManager->flush();

                $this->addFlash('success', 'Votre compte a été supprimé avec succès.');

                return $this->redirectToRoute('app_home');
            } else {
                $this->addFlash('error', 'Mot de passe incorrect.');
            }
        }

        return $this->render('registration/editUser.html.twig', [
            'ChangeEmailForm' => $changeEmailForm,
            'ChangePasswordForm' => $changePasswordForm,
            'DeleteAccountForm' => $deleteAccountForm,
        ]);
    }

    #[Route('/user/edit-email', name: 'app_user_edit_email')]
    public function editEmail(Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        $changeEmailForm = $this->createForm(ChangeEmailType::class, $user);
        $changeEmailForm->handleRequest($request);

        if ($changeEmailForm->isSubmitted() && $changeEmailForm->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Votre email a été mis à jour avec succès.');
            return $this->redirectToRoute('app_user_edit_email');
        }

        return $this->render('registration/editEmail.html.twig', [
            'ChangeEmailForm' => $changeEmailForm
        ]);
    }

    #[Route('/user/edit-password', name: 'app_user_edit_password')]
    public function editPassword(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        $changePasswordForm = $this->createForm(ChangePasswordType::class);
        $changePasswordForm->handleRequest($request);

        if ($changePasswordForm->isSubmitted() && $changePasswordForm->isValid()) {
            $newPassword = $changePasswordForm->get('newPassword')->getData();
            $user->setPassword($passwordHasher->hashPassword($user, $newPassword));
            $entityManager->flush();
            $this->addFlash('success', 'Votre mot de passe a été changé avec succès.');
            return $this->redirectToRoute('app_user_edit_password');
        }

        return $this->render('registration/editPassword.html.twig', [
            'ChangePasswordForm' => $changePasswordForm
        ]);
    }

    #[Route('/user/delete-account', name: 'app_user_delete_account')]
    public function deleteAccount(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        $deleteAccountForm = $this->createForm(DeleteAccountType::class);
        $deleteAccountForm->handleRequest($request);

        if ($deleteAccountForm->isSubmitted() && $deleteAccountForm->isValid()) {
            $password = $deleteAccountForm->get('password')->getData();
            if ($passwordHasher->isPasswordValid($user, $password)) {
                $tokenStorage->setToken(null);
                $request->getSession()->invalidate();

                $entityManager->remove($user);
                $entityManager->flush();

                $this->addFlash('success', 'Votre compte a été supprimé avec succès.');

                return $this->redirectToRoute('app_home');
            } else {
                $this->addFlash('error', 'Mot de passe incorrect.');
            }
        }

        return $this->render('registration/deleteAccount.html.twig', [
            'DeleteAccountForm' => $deleteAccountForm
        ]);
    }



}

// // READ
// #[Route('/user/profile/{id}', name: 'app_user_profile')]
// public function show(User $user): Response
// {
//     $userProfile = $user->getProfile();

//     if (!$userProfile) {
//         return $this->redirectToRoute('app_user_profile_edit');
//     }

//     return $this->render('user_profile/showProfile.html.twig', [
//         'userProfile' => $userProfile,
//         'user' => $user,
//     ]);
// }