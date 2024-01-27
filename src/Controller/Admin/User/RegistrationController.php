<?php

namespace App\Controller\Admin\User;

use App\Entity\User\Users;
use App\Form\User\RegistrationFormType;
use App\Service\User\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class RegistrationController extends AbstractController
{
    public function __construct(private readonly UserService $userService)
    {
    }

    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $em,
        TokenGeneratorInterface $tokenGenerator
    ): Response {
        $user = new Users();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->registration($form, $user, $userPasswordHasher, $em, $tokenGenerator);
            return $this->redirectToRoute('app_login');
        }

        return $this->render('admin/user/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/account-verify/{id}/{token}', name: 'account_verify', methods: ['GET'])]
    public function accountVerify(Users $user, string $token, EntityManagerInterface $em)
    {
        $this->userService->accountVerify($user, $token, $em);

        $this->addFlash('success', 'Votre a bien été confirmé. Veuillez vous connecter');

        return $this->redirectToRoute('app_login');
    }
}
