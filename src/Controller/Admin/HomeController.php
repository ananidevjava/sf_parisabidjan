<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HomeController extends AbstractController
{
<<<<<<< HEAD
    #[Route('/', name: 'app_home')]
    #[IsGranted('ROLE_USER')]
    public function home(): Response
    {
        return $this->redirectToRoute('app_admin_home');
    }

    #[Route('/admin', name: 'app_admin_home')]
    #[IsGranted('ROLE_USER')]
    public function adminHome(): Response
=======
    #[Route('/dashboard', name: 'app_admin_home')]
    public function index(): Response
>>>>>>> 29338cb1d687e4659155db8db790ebcc07ae01ab
    {
        return $this->render('admin/home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
