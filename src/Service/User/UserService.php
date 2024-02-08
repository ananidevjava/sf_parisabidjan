<?php

namespace App\Service\User;

use App\Entity\User\Users;
use Symfony\Component\Form\Form;
use App\Service\Mail\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class UserService
{
    public function __construct(private readonly MailService $mailService)
    {
    }

    public function registration(Form $form, Users $user, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $em, TokenGeneratorInterface $tokenGenerator)
    {
        $token = $tokenGenerator->generateToken();

        // encode the plain password
        $user->setPassword(
            $userPasswordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            )
        );

        $user->setTokenRegistreation($token);

        $em->persist($user);
        $em->flush();
        // do anything else you need here, like send an email

        $this->mailService->send(
            $user->getEmail(),
            "Confirmation de compte",
            "mail/registration_confirmation.html.twig",
            [
                "user" => $user,
                "token" => $user->getTokenRegistreation(),
                "tokenLifeTime" => $user->getTokenRegistrationLifeTime()->format('d/m/Y H:i:s')
            ]
        );
    }

    public function accountVerify(Users $user, string $token, EntityManagerInterface $manager)
    {
        $userToken = $user->getTokenRegistreation();

        if ($token === null) {
            throw new AccessDeniedException();
        }

        if ($token !== $userToken) {
            throw new AccessDeniedException();
        }

        $now = new \DateTimeImmutable();
        if ($now > $user->getTokenRegistrationLifeTime()) {
            throw new AccessDeniedException();
        }

        $user->setVerified(true)
            ->setTokenRegistreation(null)
            ->setVerifiedAt($now);

        $manager->flush();
    }

    public function resetForgetPassword(Users $user, FormInterface $form, EntityManagerInterface $manager, UserPasswordHasherInterface $userPasswordHasher){
        $user->setPassword(
            $userPasswordHasher->hashPassword(
                $user,
                $form->get('password')->getData()
            )
        );

        $user->setPasswordForgetUpdatedAt(new \DateTimeImmutable());

        $manager->flush();
    }

    public function resetPassword(Users $user, FormInterface $form, EntityManagerInterface $manager, UserPasswordHasherInterface $userPasswordHasher): bool{
        $oldPassword = $form->get('old_password')->getData();
        if(!$userPasswordHasher->isPasswordValid($user, $oldPassword)){
            return false;
        }

        $newPassword = $form->get('new_password')->getData();
        $user->setPassword(
            $userPasswordHasher->hashPassword(
                $user,
                $newPassword
            )
        );

        $user->setPasswordForgetUpdatedAt(new \DateTimeImmutable());

        $manager->flush();
        return true;
    }
}
