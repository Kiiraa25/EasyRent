<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\UserProfileType;
use App\Form\PersonalInfoType;
use App\Form\AddressType;
use App\Form\DescriptionType;
use App\Form\DrivingLicenseType;
use App\Entity\User;
use App\Form\UserImageType;
use App\Entity\UserProfile;
use Symfony\Component\Security\Core\User\UserInterface;


class UserProfileController extends AbstractController
{

    //CREATE
    #[Route('dashboard/profile_verification_status', name: 'app_user_profile_verify')]
    public function verify(Request $request, EntityManagerInterface $entityManager): Response
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
        // $imageForm = $this->createForm(UserImageType::class, $userProfile);

        $form->handleRequest($request);
        // $imageForm->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $userProfile->setUpdatedAt(new \DateTimeImmutable());
            $userProfile->setVerified(true);
            $entityManager->flush();

            $this->addFlash('success', 'Votre profil a été mis à jour avec succès.');

            return $this->redirectToRoute('app_user_profile_edit');
        }

        return $this->render('user_profile/verifyProfile.html.twig', [
            'form' => $form,
            // 'imageForm' => $imageForm
        ]);
    }

    //UPDATE PROFILE PAGE
    #[Route('/user/profile/edit', name: 'app_user_profile_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {

        return $this->render('user_profile/editProfile.html.twig');
    }

    //UPDATE PERSONAL INFORMATIONS
    #[Route('/user/profile/edit/personal', name: 'app_user_profile_edit_personal')]
    public function editPersonalInfo(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        $userProfile = $user->getProfile();

        if (!$userProfile) {
            return $this->redirectToRoute('app_user_profile_verify');
        }

        $form = $this->createForm(PersonalInfoType::class, $userProfile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userProfile->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->flush();

            $this->addFlash('success', 'Informations personnelles mises à jour avec succès.');
            return $this->redirectToRoute('app_user_profile_edit');
        }

        return $this->render('user_profile/editPersonalInfo.html.twig', [
            'form' => $form,
        ]);
    }

    //UPDATE ADDRESS
    #[Route('/user/profile/edit/address', name: 'app_user_profile_edit_address')]
    public function editAddress(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        $userProfile = $user->getProfile();

        if (!$userProfile) {
            return $this->redirectToRoute('app_user_profile_verify');
        }

        $form = $this->createForm(AddressType::class, $userProfile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userProfile->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->flush();

            $this->addFlash('success', 'Adresse mise à jour avec succès.');
            return $this->redirectToRoute('app_user_profile_edit');
        }

        return $this->render('user_profile/editAddress.html.twig', [
            'form' => $form,
        ]);
    }

    //UPDATE DESCRIPTION
    #[Route('/user/profile/edit/description', name: 'app_user_profile_edit_description')]
    public function editDescription(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        $userProfile = $user->getProfile();

        if (!$userProfile) {
            return $this->redirectToRoute('app_user_profile_verify');
        }

        $form = $this->createForm(DescriptionType::class, $userProfile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userProfile->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->flush();

            $this->addFlash('success', 'Description mise à jour avec succès.');
            return $this->redirectToRoute('app_user_profile_edit');
        }

        return $this->render('user_profile/editDescription.html.twig', [
            'form' => $form,
        ]);
    }

    //UPDATE DRIVING LICENSE
    #[Route('/user/profile/edit/driving_license', name: 'app_user_profile_edit_driving_license')]
    public function editDrivingLicense(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        $userProfile = $user->getProfile();
        $drivingLicense = $userProfile->getDrivingLicense();

        if (!$userProfile || !$drivingLicense) {
            return $this->redirectToRoute('app_user_profile_verify');
        }

        $form = $this->createForm(DrivingLicenseType::class, $drivingLicense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Permis de conduire mis à jour avec succès.');
            return $this->redirectToRoute('app_user_profile_edit');
        }

        return $this->render('user_profile/editDrivingLicense.html.twig', [
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
