<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConnexionController extends AbstractController
{
    #[Route('/admin/connexion', name: 'app_admin_login')]
    public function index(): Response
    {
        return $this->render('admin/connexion/index.html.twig', [
            'controller_name' => 'ConnexionController',
        ]);
    }
}
