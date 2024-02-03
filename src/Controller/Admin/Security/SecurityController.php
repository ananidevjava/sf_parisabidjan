<?php

namespace App\Controller\Admin\Security;

use DateInterval;
use App\Entity\User\Users;
use App\Service\Mail\MailService;
use App\Service\User\UserService;
use function PHPUnit\Framework\isEmpty;

use App\Form\User\PasswordResetFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class SecurityController extends AbstractController
{
    public function __construct(private readonly MailService $mailService, private readonly UserService $userService)
    {
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('admin/connexion/index.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/utilisateur/mot-de-passe-oublie', name: 'forget_password')]
    public function fortgetPassword(Request $request, EntityManagerInterface $manager, TokenGeneratorInterface $tokenGenerator)
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_admin_home');
        }

        $email = $request->request->get('email');
        $validator = Validation::createValidator();
        $errors = $validator->validate(
            $email,
            new Email()
        );

        if ($email !== null) {
            if (count($errors) === 0) {
                $user = $manager->getRepository(Users::class)->findOneByEmail($email);
                if (!$user) {
                    throw new NotFoundHttpException("Utilisateur introuvable");
                }

                $user
                    ->setTokenForgetPassword($tokenGenerator->generateToken())
                    ->setTokenForgetPasswordLifeTime((new \DateTimeImmutable)->add(new DateInterval('P1D')));
                $manager->flush();

                $this->mailService->send(
                    $email,
                    "Mot de passe oublié",
                    "mail/forget_password.html.twig",
                    [
                        'user' => $user,
                        'token' => $user->getTokenForgetPassword(),
                        'tokenLifeTime' => $user->getTokenForgetPasswordLifeTime()->format('d/m/Y H:i:s')
                    ]
                );

                $this->addFlash('success', "Un lien de modification de mot de passe vient d'être envoyé à votre email");
            }
        }

        return $this->render('admin/connexion/password_update.html.twig');
    }

    private function resetPassword(Request $request, Users $user, EntityManagerInterface $manager, UserPasswordHasherInterface $userPasswordHasher){
        $form = $this->createForm(PasswordResetFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->passwordReset($user, $form, $manager, $userPasswordHasher);
            return $this->redirectToRoute('app_login');
        }

        return $this->render('admin/connexion/password_reset.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/utilisateur/maj-mdp-oublie/{id}/{token}', name: 'user_reset_forget_password')]
    public function updateForgetPassword(Request $request, Users $user, string $token, EntityManagerInterface $manager, UserPasswordHasherInterface $userPasswordHasher)
    {
        $userToken = $user->getTokenForgetPassword();

        if ($token === null) {
            throw new AccessDeniedException();
        }

        if ($token !== $userToken) {
            throw new AccessDeniedException();
        }

        $now = new \DateTimeImmutable();
        if ($now > $user->getTokenForgetPasswordLifeTime()) {
            throw new AccessDeniedException();
        }
        
        return $this->resetPassword($request, $user, $manager, $userPasswordHasher);
    }

    #[Route('/utilisateur/maj-mdp', name: 'user_reset_password')]
    #[IsGranted('ROLE_USER')]
    public function resetUserPassword(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $userPasswordHasher){
        $user = $this->getUser();
        return $this->resetPassword($request, $user, $manager, $userPasswordHasher);
    }
}
