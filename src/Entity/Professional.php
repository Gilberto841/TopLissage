<?php

namespace App\Entity;

use App\Repository\ProfessionalRepository;
use Doctrine\ORM\Mapping as ORM;
use Scheb\TwoFactorBundle\Model\Email\TwoFactorInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: ProfessionalRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class Professional implements UserInterface, PasswordAuthenticatedUserInterface, TwoFactorInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $postalAdress = null;

    #[ORM\Column(length: 100)]
    private ?string $siret = null;

    #[ORM\Column]
    private ?bool $hairSalon = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $authCode = null;

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
/* Start Interface TwoFactorInterface */

public function isEmailAuthEnabled(): bool
{
    return true; // This can be a persisted field to switch email code authentication on/off
}

public function getEmailAuthRecipient(): string
{
    return $this->email;
}

public function getEmailAuthCode(): string
{
    if (null === $this->authCode) {
        throw new \LogicException('The email authentication code was not set');
    }

    return $this->authCode;
}

public function setEmailAuthCode(string $authCode): void
{
    $this->authCode = $authCode;
}
/* End Interface TwoFactorInterface */
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPostalAdress(): ?string
    {
        return $this->postalAdress;
    }

    public function setPostalAdress(string $postalAdress): static
    {
        $this->postalAdress = $postalAdress;

        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(string $siret): static
    {
        $this->siret = $siret;

        return $this;
    }

    public function isHairSalon(): ?bool
    {
        return $this->hairSalon;
    }

    public function setHairSalon(bool $hairSalon): static
    {
        $this->hairSalon = $hairSalon;

        return $this;
    }

    public function getAuthCode(): ?string
    {
        return $this->authCode;
    }

    public function setAuthCode(?string $authCode): static
    {
        $this->authCode = $authCode;

        return $this;
    }
}
