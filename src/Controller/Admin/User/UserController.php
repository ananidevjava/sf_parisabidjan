<?php
namespace App\Controller\Admin\User;

use App\Entity\User\Address;
use App\Entity\User\Users;
use App\Form\User\AddressType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UserController extends AbstractController{

    #[Route('/utilisateur/profile', name: 'user_profile', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function profile(Request $request){
        return $this->render("admin/user/profile.html.twig");
    }
}