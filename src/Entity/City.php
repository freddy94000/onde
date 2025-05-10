<?php

namespace App\Entity;

use App\Entity\Traits\SluggableTrait;
use App\Repository\CityRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CityRepository::class)]
class City
{
    use SluggableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\ManyToOne]
    private ?Island $island = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private Country $country;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getIsland(): ?Island
    {
        return $this->island;
    }

    public function setIsland(?Island $island): static
    {
        $this->island = $island;

        return $this;
    }

    public function getCountry(): Country
    {
        return $this->country;
    }

    public function setCountry(Country $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function __toString(): string
    {
        return $this->name . ' (' .
            (isset($this->island) ? $this->island->getName() . ' - ' : '') .
            $this->country->getName() . ')';
    }
}
