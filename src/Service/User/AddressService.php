<?php
namespace App\Service\User;

use App\Entity\User\Address;
use App\Entity\User\Users;
use Doctrine\ORM\EntityManagerInterface;

class AddressService
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }
    public function create(Address $address, Users $user)
    {
        $user->addAddress($address);

        $this->em->persist($address);
        $this->em->flush();
    }

    public function update(Address $address)
    {
        $this->em->flush();
    }

    public function delete(Address $address)
    {
        $address->getPerson()->removeAddress($address);
        $this->em->remove($address);
        $this->em->flush();
    }
}
