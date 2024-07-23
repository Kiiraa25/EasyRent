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
    // //DELETE
    // #[Route('/user/delete', name: 'app_user_delete')]
    // public function deleteUser(Request $request,UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    // {


    // }

// add 5 users
    #[Route('/addUsers', name: 'app_add_users')]
public function insertUsers(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager)
{
    $users = [
        ["lastName" => "Burke", "firstName" => "Maisie", "email" => "b-maisie@gmail.com", "birth_date" => '1990-05-15'],
        ["lastName" => "George", "firstName" => "Mannix", "email" => "m-george3282@gmail.com", "birth_date" => '1985-08-22'],
        ["lastName" => "Kane", "firstName" => "Vernon", "email" => "kanevernon@gmail.com", "birth_date" => '1978-11-03'],
        ["lastName" => "Blevins", "firstName" => "Eleanor", "email" => "eleanorblevins@gmail.com", "birth_date" => '1995-01-14'],
        ["lastName" => "Mcintosh", "firstName" => "Akeem", "email" => "mcintosh.akeem@gmail.com", "birth_date" => '1982-03-30'],
    ];

    foreach ($users as $userData) {
        $user = new User();
        $user->setActive(true);
        $user->setRoles(['ROLE_USER']);
        $user->setLastName($userData['lastName']);
        $user->setFirstName($userData['firstName']);
        $user->setEmail($userData['email']);
        $user->setPassword(
            $userPasswordHasher->hashPassword(
                $user, '000000'
            )
        );
        $user->setBirthDate(new \DateTime($userData['birth_date']));

        $entityManager->persist($user);
    }

    $entityManager->flush();
    return $this->redirectToRoute('app_login');
}

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