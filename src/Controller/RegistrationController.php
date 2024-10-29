<?php

namespace App\Controller;

use App\Enum\RoleEnum;
use App\Entity\User;
use App\Form\RegistrationFormType;
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
    public function __construct(private EmailVerifier $emailVerifier)
    {
    }

    #[Route('/register', name: 'app_register')]
public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
{
    $user = new User();
    $userProfile = new UserProfile();
    $user->setProfile($userProfile);

    $form = $this->createForm(RegistrationFormType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) 
    {
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
                ->subject('Please Confirm your Email')
                ->htmlTemplate('registration/confirmation_email.html.twig')
        );

        return $this->redirectToRoute('app_login');
    }

    return $this->render('registration/register.html.twig', [
        'registrationForm' => $form,
    ]);
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
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_login');
    }
}
