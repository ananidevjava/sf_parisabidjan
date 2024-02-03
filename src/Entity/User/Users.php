<?php

namespace App\Entity\User;

use DateInterval;
use DateTimeImmutable;
use App\Entity\DateTrait;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\User\UsersRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    use DateTrait;

    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dob = null;

    #[ORM\Column(length: 20)]
    private ?string $phone1 = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $phone2 = null;

    #[ORM\OneToMany(mappedBy: 'person', targetEntity: Address::class, orphanRemoval: true)]
    private Collection $addresses;

    #[ORM\Column(length: 10)]
    private ?string $gender = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tokenRegistreation = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $tokenRegistrationLifeTime = null;

    #[ORM\Column]
    private ?bool $verified = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $verifiedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tokenForgetPassword = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $tokenForgetPasswordLifeTime = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $passwordForgetUpdatedAt = null;

    public function __construct()
    {
        $this->addresses = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        $this->verified = false;

        $now = new DateTimeImmutable();
        $this->tokenRegistrationLifeTime = $now->add(new DateInterval('P1D'));
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Address>
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function addAddress(Address $address): static
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses->add($address);
            $address->setPerson($this);
        }

        return $this;
    }

    public function removeAddress(Address $address): static
    {
        if ($this->addresses->removeElement($address)) {
            // set the owning side to null (unless already changed)
            if ($address->getPerson() === $this) {
                $address->setPerson(null);
            }
        }

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFullname()
    {
        return $this->firstName . " " . $this->lastName;
    }

    public function getDob(): ?\DateTimeInterface
    {
        return $this->dob;
    }

    public function setDob(\DateTimeInterface $dob): static
    {
        $this->dob = $dob;

        return $this;
    }

    public function getPhone1(): ?string
    {
        return $this->phone1;
    }

    public function setPhone1(string $phone1): static
    {
        $this->phone1 = $phone1;

        return $this;
    }

    public function getPhone2(): ?string
    {
        return $this->phone2;
    }

    public function setPhone2(?string $phone2): static
    {
        $this->phone2 = $phone2;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getTokenRegistreation(): ?string
    {
        return $this->tokenRegistreation;
    }

    public function setTokenRegistreation(?string $tokenRegistreation): static
    {
        $this->tokenRegistreation = $tokenRegistreation;

        return $this;
    }

    public function getTokenRegistrationLifeTime(): ?\DateTimeInterface
    {
        return $this->tokenRegistrationLifeTime;
    }

    public function setTokenRegistrationLifeTime(\DateTimeInterface $tokenRegistrationLifeTime): static
    {
        $this->tokenRegistrationLifeTime = $tokenRegistrationLifeTime;

        return $this;
    }

    public function isVerified(): ?bool
    {
        return $this->verified;
    }

    public function setVerified(bool $verified): static
    {
        $this->verified = $verified;

        return $this;
    }

    public function getVerifiedAt(): ?\DateTimeImmutable
    {
        return $this->verifiedAt;
    }

    public function setVerifiedAt(?\DateTimeImmutable $verifiedAt): static
    {
        $this->verifiedAt = $verifiedAt;

        return $this;
    }

    public function getTokenForgetPassword(): ?string
    {
        return $this->tokenForgetPassword;
    }

    public function setTokenForgetPassword(?string $tokenForgetPassword): static
    {
        $this->tokenForgetPassword = $tokenForgetPassword;

        return $this;
    }

    public function getPasswordForgetUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->passwordForgetUpdatedAt;
    }

    public function setPasswordForgetUpdatedAt(?\DateTimeImmutable $passwordForgetUpdatedAt): static
    {
        $this->passwordForgetUpdatedAt = $passwordForgetUpdatedAt;

        return $this;
    }

    public function getTokenForgetPasswordLifeTime(): ?\DateTimeInterface
    {
        return $this->tokenForgetPasswordLifeTime;
    }

    public function setTokenForgetPasswordLifeTime(\DateTimeInterface $tokenForgetPasswordLifeTime): static
    {
        $this->tokenForgetPasswordLifeTime = $tokenForgetPasswordLifeTime;

        return $this;
    }
}
