<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

/**********Email************/
    #[ORM\Column(length: 180)]
    #[Assert\NotNull(
        message: 'Le champ ne doit pas être vide',
    )]
    #[Assert\Email(
        message: 'L\'email saisi: {{ value }} n\'est pas valide.',
        )]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /*****PASSWORD**********/
    #[ORM\Column(length: 20)]
    private ?string $password = null;

    /*****PERSONAL INFORMATION**********/
    /***********************************/

    /*********FIRSTNAME*****************/
    #[ORM\Column(length: 20)]
    #[Assert\NotNull(
        message: 'Le champ ne doit pas être vide',
    )]
    #[Assert\Type('string')]
    #[Assert\Length(
        min: 2,
        max: 20,
        minMessage: 'Le nom saisi doit avoir minimum {{ limit }} caractères',
        maxMessage: 'Le nom ne doit pas dépasser {{ limit }} caractères',
    )]
    private ?string $firstname = null;

    /*********LASTNAME*****************/
    #[ORM\Column(length: 20)]
    #[Assert\NotNull(
        message: 'Le champ ne doit pas être vide',
    )]
    #[Assert\Type('string')]
    #[Assert\Length(
        min: 2,
        max: 20,
        minMessage: 'Le prénom saisi doit avoir minimum {{ limit }} caractères',
        maxMessage: 'Le prénom ne doit pas dépasser {{ limit }} caractères',
    )]
    private ?string $lastname = 'Doe';

    /*********ADDRESS*****************/
    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotNull(
        message: 'Le champ ne doit pas être vide',  
    )]
    #[Assert\Positive]
    #[Assert\Regex(pattern:'/^[0-9 ]{1,3}\s[a-zA-Z ]{2,}$/')]
    private ?string $address = null;

    /*********CP*****************/
    #[ORM\Column]
    #[Assert\NotNull(
        message: 'Le champ ne doit pas être vide',
    )]
    #[Assert\Type(
        type: 'integer',
        message: 'la valeur saisie {{ value }} n\'est pas valide, des {{ type }} sont requis.',
    )]
    #[Assert\Positive]
    #[Assert\Regex(pattern:'/^[0-9]{5}$/')]
    private ?int $cp = null;

    /*********TOWN*****************/
    #[ORM\Column(length: 30)]
    #[Assert\NotNull(
        message: 'Le champ ne doit pas être vide',
    )]
    #[Assert\Type('string')]
    #[Assert\Regex(pattern:'/[a-zA-Z]{1,}$/')]
    private ?string $town = null;

    /*********COUNTRY*****************/
    #[ORM\Column(length: 25)]
    #[Assert\NotNull(
        message: 'Le champ ne doit pas être vide',
    )]
    #[Assert\Country]
    #[Assert\Type('string')]
    #[Assert\Regex(pattern:'/[a-zA-Z]{1,}$/')]
    private ?string $country = null;

    /*********PHONE NUMBER*****************/
    #[ORM\Column]
    #[Assert\NotNull(
        message: 'Le champ ne doit pas être vide',
    )]
    #[Assert\Type(
        type: 'integer',
        message: 'la valeur saisie {{ value }} n\'est pas valide, des {{ type }} sont requis.',
    )]
    #[Assert\Positive]
    #[Assert\Regex(pattern: "/^(?:\+?\d{1,3})?\s?\d{9,15}$/", message: 'Le numéro de téléphone saisi n\'est pas valide')]
    private ?int $phoneNumber = null;

    /*********CREATED AT*****************/
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }


    public function getId(): ?int
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

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function eraseCredentials(): void
    {
        
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getCp(): ?int
    {
        return $this->cp;
    }

    public function setCp(int $cp): static
    {
        $this->cp = $cp;

        return $this;
    }

    public function getTown(): ?string
    {
        return $this->town;
    }

    public function setTown(string $town): static
    {
        $this->town = $town;

        return $this;
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

    public function getPhoneNumber(): ?int
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(int $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
