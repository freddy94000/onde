<?php

namespace App\Entity;

use App\Entity\Traits\SluggableTrait;
use App\Enum\Island;
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

    #[ORM\Column(enumType: Island::class)]
    private Island $island;

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

    public function getIsland(): Island
    {
        return $this->island;
    }

    public function setIsland(Island $island): static
    {
        $this->island = $island;

        return $this;
    }
}
