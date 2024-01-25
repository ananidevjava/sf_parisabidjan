<?php

namespace App\Domain\Entities;

use Symfony\Component\Validator\Constraints\Uuid;

class PersonEntity
{
    private Uuid $id;
    private string $firstName;
    private string $lastName;
    private string $phone;
    private string $whatsapp;
    private string $email;
    private string $photo;
    private Address $addresses;

    public function getId(): Uuid
    {
        return $this->id;
    }

    
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    public function getWhatsapp(): string
    {
        return $this->whatsapp;
    }

    public function setWhatsapp(string $whatsapp): void
    {
        $this->whatsapp = $whatsapp;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPhoto(): string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): void
    {
        $this->photo = $photo;
    }

    public function getAddresses(): Address
    {
        return $this->addresses;
    }

    public function setAddresses(Address $addresses): void
    {
        $this->addresses = $addresses;
    }

    

}