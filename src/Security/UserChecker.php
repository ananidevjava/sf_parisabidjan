<?php
namespace App\Security;

use App\Entity\User\Users;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof Users) {
            return;
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof Users) {
            return;
        }
        
        if (!$user->isVerified()) {
            throw new CustomUserMessageAccountStatusException("Votre compte n'a pas encore été validé. Veuillez verifier votre mail");
        }
    }
}