<?php

namespace App\Controller\Admin\User;

use App\Entity\User\Address;
use App\Form\User\AddressType;
use App\Service\User\AddressService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AddressController extends AbstractController
{

    public function __construct(private readonly AddressService $addressService)
    {
    }

    #[Route('/user/address/create', name: "user_address_create")]
    #[IsGranted('ROLE_USER')]
    public function create(Request $request)
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addressService->create($address, $this->getUser());
            return $this->redirectToRoute('user_profile');
        }

        return $this->render("admin/user/address.html.twig", [
            'form' => $form
        ]);
    }

    #[Route('/user/address/{id}/update', name: "user_address_update")]
    #[IsGranted('ROLE_USER')]
    public function update(Request $request, Address $address)
    {
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addressService->update($address);
            return $this->redirectToRoute('user_profile');
        }

        return $this->render("admin/user/address.html.twig", [
            'form' => $form
        ]);
    }

    #[Route('/user/address/{id}/delete', name: "user_address_delete")]
    #[IsGranted('ROLE_USER')]
    public function delete(Request $request, Address $address)
    {
        $this->addressService->delete($address);
        return $this->redirectToRoute("user_profile");
    }
}
