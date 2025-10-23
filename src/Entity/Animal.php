<?php
namespace App\Entity;

use App\Repository\AnimalRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnimalRepository::class)]
#[ORM\Table(name: 'animal')]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'species', type: 'string')]
#[ORM\DiscriminatorMap([
    'lion' => 'App\Entity\Animal\Lion',
    'elephant' => 'App\Entity\Animal\Elephant',
    'crocodile' => 'App\Entity\Animal\Crocodile'
])]

class Animal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'animals')]
    #[ORM\JoinColumn(nullable: false)]
    private Cell $cell;

    public function getSpecies(): string
    {
        $class = (new \ReflectionClass($this))->getShortName();
        return strtolower($class);
    }

    public function getId(): ?int { return $this->id; }

    public function setCell(Cell $cell): void { $this->cell = $cell; }
    public function getCell(): Cell { return $this->cell; }

}

