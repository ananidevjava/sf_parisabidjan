<?php

namespace App\Entity\User;

use App\Entity\DateTrait;
use App\Repository\User\AddressRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Address
{
    use DateTrait;

    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    private ?string $country = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $zipCode = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $street = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $furtherInformation = null;

    #[ORM\ManyToOne(inversedBy: 'addresses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $person = null;

    public function __construct(){
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(?string $zipCode): static
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(?string $street): static
    {
        $this->street = $street;

        return $this;
    }

    public function getFurtherInformation(): ?string
    {
        return $this->furtherInformation;
    }

    public function setFurtherInformation(?string $furtherInformation): static
    {
        $this->furtherInformation = $furtherInformation;

        return $this;
    }

    public function getPerson(): ?Users
    {
        return $this->person;
    }

    public function setPerson(?Users $person): static
    {
        $this->person = $person;

        return $this;
    }
}
