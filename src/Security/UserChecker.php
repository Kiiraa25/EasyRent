<?php

namespace App\Security;

use App\Enum\UserStatusEnum;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {

        /** @var User $user */

        if ($user->getStatus()===UserStatusEnum::INACTIF) {
            throw new CustomUserMessageAuthenticationException("Votre compte n'est pas vérifié, veuillez vérifier votre email");
        }

        if ($user->getStatus()===UserStatusEnum::SUPPRIME) {
            throw new CustomUserMessageAuthenticationException("Votre compte n'est pas actif, veuillez contacter un administrateur");
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        // Ajoutez ici des vérifications post-authentification, si nécessaire
    }
}
