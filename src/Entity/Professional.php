<?php

namespace App\Entity;

use App\Repository\ProfessionalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProfessionalRepository::class)]
class Professional
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 80)]
    private ?string $name = null;

    #[ORM\Column(length: 180)]
    private ?string $postalAdress = null;

    #[ORM\Column(length: 100)]
    private ?string $email = null;

    #[ORM\Column(length: 30)]
    private ?string $password = null;

    #[ORM\Column(length: 30)]
    private ?string $phone = null;

    #[ORM\Column(length: 80)]
    private ?string $siret = null;

    #[ORM\Column(length: 50)]
    private ?string $role = null;

    #[ORM\Column]
    private ?bool $hairSalon = null;

    #[ORM\ManyToOne(inversedBy: 'professionals')]
    private ?Administrator $administrator = null;

    #[ORM\OneToMany(mappedBy: 'professional', targetEntity: HairSalon::class)]
    private Collection $hairSalons;

    #[ORM\OneToMany(mappedBy: 'professional', targetEntity: Order::class)]
    private Collection $orders;

    public function __construct()
    {
        $this->hairSalons = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPostalAdress(): ?string
    {
        return $this->postalAdress;
    }

    public function setPostalAdress(string $postalAdress): self
    {
        $this->postalAdress = $postalAdress;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(string $siret): self
    {
        $this->siret = $siret;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function isHairSalon(): ?bool
    {
        return $this->hairSalon;
    }

    public function setHairSalon(bool $hairSalon): self
    {
        $this->hairSalon = $hairSalon;

        return $this;
    }

    public function getAdministrator(): ?Administrator
    {
        return $this->administrator;
    }

    public function setAdministrator(?Administrator $administrator): self
    {
        $this->administrator = $administrator;

        return $this;
    }

    /**
     * @return Collection<int, HairSalon>
     */
    public function getHairSalons(): Collection
    {
        return $this->hairSalons;
    }

    public function addHairSalon(HairSalon $hairSalon): self
    {
        if (!$this->hairSalons->contains($hairSalon)) {
            $this->hairSalons->add($hairSalon);
            $hairSalon->setProfessional($this);
        }

        return $this;
    }

    public function removeHairSalon(HairSalon $hairSalon): self
    {
        if ($this->hairSalons->removeElement($hairSalon)) {
            // set the owning side to null (unless already changed)
            if ($hairSalon->getProfessional() === $this) {
                $hairSalon->setProfessional(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setProfessional($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getProfessional() === $this) {
                $order->setProfessional(null);
            }
        }

        return $this;
    }
}
