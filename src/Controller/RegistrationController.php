<?php

namespace App\Controller;

use App\Enum\RoleEnum;
use App\Entity\User;
use App\Form\RegistrationForms\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use App\Form\NameUserProfileType;
use App\Entity\UserProfile;
use App\Enum\UserStatusEnum;

class RegistrationController extends AbstractController
{
    public function __construct(private EmailVerifier $emailVerifier) {}

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {

        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        $user = new User();
        $userProfile = new UserProfile();
        $user->setProfile($userProfile);

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Vérification de l'âge
            $birthDate = $userProfile->getBirthDate();
            $today = new \DateTime();
            $age = $today->diff($birthDate)->y;

            if ($age < 18) {
                $this->addFlash('register-error', 'Vous devez être majeur pour vous inscrire.');
                return $this->redirectToRoute('app_register');
            }

            $userProfile->setCreatedAt(new \DateTimeImmutable());
            $userProfile->setUpdatedAt(new \DateTimeImmutable());
            $userProfile->setRating(0);
            $userProfile->setVerified(false);

            $user->setStatus(UserStatusEnum::INACTIF);
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($userProfile);
            $entityManager->persist($user);
            $entityManager->flush();

            $this->emailVerifier->sendEmailConfirmation(
                'app_verify_email',
                $user,
                (new TemplatedEmail())
                    ->from(new Address('easy_rent@registration.com', 'easy_rent'))
                    ->to($user->getEmail())
                    ->subject('Veuillez confirmer votre email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route('/resend-verification/email', name: 'app_resend_verification_email')]
    public function resendVerification(Request $request, UserRepository $userRepository): Response
    {
        $userId = $request->query->get('id');
        $user = $userRepository->find($userId);

        if (!$user || $user->getStatus() === UserStatusEnum::ACTIF) {
            $this->addFlash('error', 'Ce compte est déjà vérifié ou n\'existe pas.');
            return $this->redirectToRoute('app_login');
        }

        // Envoyer un nouvel e-mail de vérification
        $this->emailVerifier->resendVerificationEmail($user, 'app_verify_email');

        $this->addFlash('success', 'Un nouveau lien de vérification vous a été envoyé.');
        return $this->redirectToRoute('app_login');
    }


    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $id = $request->query->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_register');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', 'Le lien a expiré, veuillez redemander un nouveau lien.');
            return $this->redirectToRoute('app_resend_verification', ['id' => $user->getId()]);
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Votre email a été vérifié avec success.');

        return $this->redirectToRoute('app_login');
    }
}
