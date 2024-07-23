<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\UserProfileType;
use App\Entity\UserProfile;
use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;


class UserProfileController extends AbstractController
{
    // CREATE
    #[Route('/user/profile/verify', name: 'app_user_profile_verify')]
    public function verify(Request $request, EntityManagerInterface $entityManager): Response
    {

        $userProfile = new UserProfile();

        /** @var User $user */
        $user = $this->getUser();
        $userProfile->setCreatedAt(new \DateTimeImmutable());
        $userProfile->setUpdatedAt(new \DateTimeImmutable());
        $userProfile->setRating(0);


        $form = $this->createForm(UserProfileType::class, $userProfile);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setProfile($userProfile);

            $entityManager->persist($userProfile);
            $entityManager->flush();

            return $this->redirectToRoute('app_login');
        }
        return $this->render('user_profile/verifyProfile.html.twig', [
            'verificationForm' => $form,
        ]);
    }

    

    //UPDATE
    #[Route('/user/profile/edit', name: 'app_user_profile_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        $userProfile = $user->getProfile();

        if (!$userProfile) {
            return $this->redirectToRoute('app_user_profile_verify');
        }

        $form = $this->createForm(UserProfileType::class, $userProfile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userProfile->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->flush();

            $this->addFlash('success', 'Votre profil a été mis à jour avec succès.');

            return $this->redirectToRoute('app_user_profile',['id' => $user->getId()]);
        }

        return $this->render('user_profile/editProfile.html.twig', [
            'form' => $form,
        ]);
    }

    // READ
    #[Route('/user/profile/{id}', name: 'app_user_profile')]
    public function show(User $user): Response
    {
        $userProfile = $user->getProfile();

        if (!$userProfile) {
            return $this->redirectToRoute('app_user_profile_edit');
        }

        return $this->render('user_profile/showProfile.html.twig', [
            'userProfile' => $userProfile,
            'user' => $user,
        ]);
    }

    // DELETE
    #[Route('/user/delete/{id}', name: 'app_user_delete')]
    public function deleteUser($id, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('No user found for id ' . $id);
        }

        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_home');
    }
}
