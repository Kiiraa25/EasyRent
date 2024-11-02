<?php


namespace App\Security;

use App\Entity\User;
use App\Enum\UserStatusEnum;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function checkPreAuth(UserInterface $user): void
    {
        /** @var User $user */
        if ($user->getStatus() === UserStatusEnum::INACTIF) {
            $verificationUrl = $this->urlGenerator->generate('app_resend_verification_email', [
                'id' => $user->getId()
            ], UrlGeneratorInterface::ABSOLUTE_URL);

            throw new CustomUserMessageAuthenticationException(
                "Votre compte n'est pas vérifié, veuillez vérifier votre email.</br><a href=\"{$verificationUrl}\">Recevoir un nouvel email de vérification</a>"
            );
        }

        if ($user->getStatus() === UserStatusEnum::SUPPRIME) {
            throw new CustomUserMessageAuthenticationException("Votre compte n'est pas actif, veuillez contacter un administrateur");
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        // Ajoutez ici des vérifications post-authentification, si nécessaire
    }
}
